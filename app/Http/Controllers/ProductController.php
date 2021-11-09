<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $data['title'] = 'Product';
        return view('Product.index', $data);
    }

    public function get_data()
    {
        $data = Product::latest()->get();
        $output = array();
        $no = 1;
        foreach ($data as $d) {
            $row = array();
            $row[] = $no;
            $row[] = $d->sku;
            $row[] = $d->name;
            $row[] = 'Rp ' . number_format($d->price, 0, ',', '.');
            $row[] = $d->stock;
            if (Auth::user()->hasRole('Super Admin')) {
                $row[] = '<a class="btn btn-sm btn-primary" href="/product/' . $d->id . '/edit"><i class="far fa-edit"></i> Ubah</a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="hapus_data(' . "'" . $d->id . "'" . ')"><i class="fas fa-trash-alt"></i> Hapus</a>';
            }

            $output[] = $row;
            $no++;
        }

        return response()->json([
            "data" => $output,
        ]);
    }

    public function create()
    {
        $data['title'] = "Product";
        return view('Product.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'sku' => 'required|unique:products,sku',
            'name' => 'required',
            'price' => 'required|integer',
        ];

        $customMessages = [
            'sku.required' => 'SKU produk tidak boleh kosong.',
            'sku.unique' => 'SKU produk sudah ada.',
            'name.required' => 'Nama produk tidak boleh kosong.',
            'price.required' => 'Harga produk tidak boleh kosong.',
            'price.integer' => 'Hanya angka yang diperbolehkan.',
        ];

        $this->validate($request, $rules, $customMessages);

        Product::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Data Produk Berhasil Ditambah.',
        ]);
    }

    public function show($id)
    {
        $data['title'] = "Product";
        $data['row'] = Product::findOrFail($id);
        return view('Product.edit', $data);
    }

    public function update(Request $request)
    {
        $data = Product::find($request->id);
        if ($data) {
            $rules = [
                'name' => 'required',
                'price' => 'required|integer',
            ];

            $customMessages = [
                'name.required' => 'Nama produk tidak boleh kosong.',
                'price.required' => 'Harga produk tidak boleh kosong.',
                'price.integer' => 'Hanya angka yang diperbolehkan.',
            ];

            $this->validate($request, $rules, $customMessages);

            $data->name = $request->name;
            $data->price = $request->price;
            $data->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Product Berhasil Diubah.'
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Data Product Invalid!'
            ]);
        }
    }

    public function ubah_logo(Request $request)
    {
        $rules = [
            'id_fakultas' => 'required',
            'ubahLogo' => 'required|image|max:1024',
        ];

        $customMessages = [
            'id_fakultas.required' => 'Mohon isi ID fakultas.',
            'ubahLogo.image' => 'Pastikan file yang anda unggah adalah file gambar (JPG/JPEG/PNG).',
            'ubahLogo.max' => 'Pastikan file yang anda unggah berukuran maksimal 1MB.',
            'ubahLogo.required' => 'Silahkan pilih gambar.',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = Product::find($request->id_fakultas);

        if ($data) {

            $data->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Logo Fakultas Berhasil Diubah!'
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Data Invalid!'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        Product::findOrFail($request->id)->delete();

        return response()->json(['message' => 'Data Produk Berhasil Dihapus!']);
    }
}
