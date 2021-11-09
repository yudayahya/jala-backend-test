@extends('Layout.app')
@section('title')
    JALA - {{ $title }}
@endsection

@section('addHeader')
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Data {{ $title }}</h6>
                </div>
                <form id="form-tambah">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="sku"><b>SKU Produk</b></label>
                            <input type="text" class="form-control" name="sku" id="sku">
                            <div class="invalid-feedback" id="sku-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="name"><b>Nama Produk</b></label>
                            <input type="text" class="form-control" name="name" id="name">
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="price"><b>Harga Produk</b></label>
                            <input type="text" class="form-control" name="price" id="price">
                            <div class="invalid-feedback" id="price-error"></div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="/product" class="btn btn-outline-secondary">&#8592; Kembali</a>
                        <button class="btn btn-primary" id="btn-form-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('addFooter')
    <script src="{{ asset('js/pages/product.js') }}"></script>
@endsection
