<?php

namespace App\Http\Controllers;

use App\Models\DetailSaleOrder;
use App\Models\Product;
use App\Models\SaleCart;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleOrderController extends Controller
{
    public function index()
    {
        $data['title'] = 'Sale Order';
        $data['products'] = Product::latest()->get();
        return view('SaleOrder.index', $data);
    }

    public function history()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            $order = SaleOrder::where('status', 'success')->latest()->get();
        } else {
            $order = SaleOrder::where('user_id', Auth::user()->id)->latest()->get();
        }
        $data['title'] = 'Riwayat Sale Order';
        $data['orders'] = $order;
        return view('SaleOrder.data', $data);
    }

    public function pending_data()
    {
        $data['title'] = 'Pending Sale Order';
        $data['orders'] = SaleOrder::where('status', 'pending')->with('user')->latest()->get();
        return view('SaleOrder.pending', $data);
    }

    public function pending_count()
    {
        $data = SaleOrder::where('status', 'pending')->get();

        return response()->json([
            'data' => count($data),
        ]);
    }

    public function pending_update(Request $request)
    {
        $order = SaleOrder::findOrFail($request->id);
        $order->invoice = "INV-P-" . date('YmdHi') . strtoupper(uniqid());
        $order->status = 'success';
        $order->save();

        return response()->json(['message' => 'Sale Order Berhasil Diapprove!']);
    }

    public function show($id)
    {
        $data['title'] = "Riwayat Sale Order";
        if (Auth::user()->hasRole('Super Admin')) {
            $order = SaleOrder::with('detail_sale_orders', 'user')->findOrFail($id);
        } else {
            $order = SaleOrder::where('user_id', Auth::user()->id)->with('detail_sale_orders', 'user')->findOrFail($id);
        }
        $data['orders'] = $order;
        return view('SaleOrder.detail', $data);
    }

    public function total_cart()
    {
        $data = SaleCart::where('user_id', Auth::user()->id)->get();
        $total = 0;

        foreach ($data as $d) {
            $total += ($d->qty * $d->price);
        }

        return response()->json([
            'data' => '<b>Rp ' . number_format($total, 0, ',', '.') . '</b>',
        ]);
    }

    public function data_cart()
    {
        $data = SaleCart::where('user_id', Auth::user()->id)->with('product')->get();
        $output = array();
        $no = 1;
        foreach ($data as $d) {
            $row = array();
            $row[] = $no;
            $row[] = $d->product->sku;
            $row[] = $d->product->name;
            $row[] = $d->qty;
            $row[] = 'Rp ' . number_format($d->price, 0, ',', '.');
            $row[] = 'Rp ' . number_format($d->qty * $d->price, 0, ',', '.');
            $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus_data(' . "'" . $d->id . "'" . ')"><i class="fas fa-trash-alt"></i></a>';

            $output[] = $row;
            $no++;
        }

        return response()->json([
            'data' => $output,
        ]);
    }

    public function add_cart(Request $request)
    {
        $rules = [
            'sku' => 'required|exists:products,sku',
            'qty' => 'required|integer|gt:0',
        ];

        $customMessages = [
            'sku.required' => 'SKU produk tidak boleh kosong.',
            'sku.exists' => 'Produk tidak ditemukan.',
            'qty.required' => 'Jumlah tidak boleh kosong.',
            'qty.integer' => 'Jumlah harus berupa angka.',
            'qty.gt' => 'Jumlah harus lebih dari 0.',
        ];

        $this->validate($request, $rules, $customMessages);

        $product = Product::where('sku', $request->sku)->first();
        if ($product->stock >= $request->qty) {
            $cek = SaleCart::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();
            if ($cek) {
                $cek->qty += $request->qty;
                $cek->save();
            } else {
                $data = new SaleCart;
                $data->user_id = Auth::user()->id;
                $data->product_id = $product->id;
                $data->qty = $request->qty;
                $data->price = $product->price;
                $data->save();
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok Produk ' . $product->name . ' Tidak Cukup.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Produk Berhasil Ditambah ke Sale Cart.',
        ]);
    }

    public function destroy_cart(Request $request)
    {
        if ($request->type == 'all') {
            SaleCart::where('user_id', Auth::user()->id)->delete();
        } else {
            SaleCart::findOrFail($request->id)->delete();
        }
        return response()->json(['message' => 'Sale Cart Berhasil Diperbarui!']);
    }

    public function store(Request $request)
    {
        $rules = [
            'customer' => 'required',
        ];

        $customMessages = [
            'customer.required' => 'Nama customer tidak boleh kosong.',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = SaleCart::where('user_id', Auth::user()->id)->with('product')->get();

        $cek_stok = 'passed';
        foreach ($data as $d) {
            if ($d->product->stock < $d->qty) {
                $d->delete();
                $cek_stok = 'fail';
            }
        }

        if ($cek_stok == 'passed') {
            if (Auth::user()->hasRole('Super Admin')) {
                $lastOrder = SaleOrder::create([
                    'user_id' => Auth::user()->id,
                    'invoice' => "INV-P-" . date('YmdHi') . strtoupper(uniqid()),
                    'customer' => $request->customer,
                    'status' => 'success',
                ]);
                $message = 'Sale Order Berhasil Diproses.';
            } else {
                $lastOrder = SaleOrder::create([
                    'user_id' => Auth::user()->id,
                    'customer' => $request->customer,
                ]);
                $message = 'Pending Sale Order Berhasil Diproses.';
            }

            foreach ($data as $d) {
                DetailSaleOrder::create([
                    'sale_order_id' => $lastOrder->id,
                    'product_id' => $d->product_id,
                    'qty' => $d->qty,
                    'price' => $d->price,
                ]);

                $product = Product::find($d->product_id);
                $product->stock -= $d->qty;
                $product->save();
                $d->delete();
            }

            return response()->json([
                'status' => 'success',
                'invoice' => $lastOrder->id,
                'message' => $message,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Opss.. Ada Stok Produk Yang Tidak Mencukupi, Kami Telah Memperbarui Sale Cart Anda.',
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $order = SaleOrder::findOrFail($request->id);
        $data = DetailSaleOrder::where('sale_order_id', $request->id)->without('product')->get();
        foreach ($data as $d) {
            $product = Product::find($d->product_id);
            $product->stock += $d->qty;
            $product->save();
            $d->delete();
        }

        $order->delete();
        return response()->json(['message' => 'Sale Order Berhasil Dihapus!']);
    }
}
