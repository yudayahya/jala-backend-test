<?php

namespace App\Http\Controllers;

use App\Models\DetailPurchaseOrder;
use App\Models\Product;
use App\Models\PurchaseCart;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $data['title'] = 'Purchase Order';
        $data['products'] = Product::latest()->get();
        return view('PurchaseOrder.index', $data);
    }

    public function history()
    {
        $data['title'] = 'Riwayat Purchase Order';
        $data['purchases'] = PurchaseOrder::latest()->get();
        return view('PurchaseOrder.data', $data);
    }

    public function show($invoice)
    {
        $data['title'] = "Riwayat Purchase Order";
        $data['purchase'] = PurchaseOrder::where('invoice', $invoice)->with('detail_purchase_orders')->firstOrFail();
        return view('PurchaseOrder.detail', $data);
    }

    public function data_cart()
    {
        $data = PurchaseCart::where('user_id', Auth::user()->id)->with('product')->get();
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
            "data" => $output,
        ]);
    }

    public function add_cart(Request $request)
    {
        $rules = [
            'sku' => 'required|exists:products,sku',
            'qty' => 'required|integer|gt:0',
            'price' => 'required|integer|gt:0',
        ];

        $customMessages = [
            'sku.required' => 'SKU produk tidak boleh kosong.',
            'sku.exists' => 'Produk tidak ditemukan.',
            'qty.required' => 'Jumlah tidak boleh kosong.',
            'qty.integer' => 'Jumlah harus berupa angka.',
            'qty.gt' => 'Jumlah harus lebih dari 0.',
            'price.required' => 'Harga produk tidak boleh kosong.',
            'price.integer' => 'Harga harus berupa angka.',
            'price.gt' => 'Harga harus lebih dari 0.',
        ];

        $this->validate($request, $rules, $customMessages);

        $product = Product::where('sku', $request->sku)->first();
        $cek = PurchaseCart::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();
        if ($cek) {
            $cek->qty += $request->qty;
            $cek->save();
        } else {
            $data = new PurchaseCart;
            $data->user_id = Auth::user()->id;
            $data->product_id = $product->id;
            $data->qty = $request->qty;
            $data->price = $request->price;
            $data->save();
        }

        return response()->json([
            'message' => 'Produk Berhasil Ditambah ke Purchase Cart.',
        ]);
    }

    public function destroy_cart(Request $request)
    {
        if ($request->type == 'all') {
            PurchaseCart::where('user_id', Auth::user()->id)->delete();
        } else {
            PurchaseCart::findOrFail($request->id)->delete();
        }
        return response()->json(['message' => 'Purchase Cart Berhasil Diperbarui!']);
    }

    public function store()
    {
        $data = PurchaseCart::where('user_id', Auth::user()->id)->get();

        $lastPurchase = PurchaseOrder::create([
            'invoice' => "INV-P-" . date('YmdHi') . strtoupper(uniqid()),
        ]);

        foreach ($data as $d) {
            DetailPurchaseOrder::create([
                'purchase_order_id' => $lastPurchase->id,
                'product_id' => $d->product_id,
                'qty' => $d->qty,
                'price' => $d->price,
            ]);

            $product = Product::find($d->product_id);
            $product->stock += $d->qty;
            $product->save();
            $d->delete();
        }

        return response()->json([
            'invoice' => $lastPurchase->invoice,
            'message' => 'Purchase Order Telah Diproses.',
        ]);
    }
}
