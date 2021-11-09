@extends('Layout.app')
@section('title')
    JALA - {{ $title }}
@endsection

@section('addHeader')
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }} {{ $purchase->invoice }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="mr-3">{{ $purchase->invoice }}</h3>
                </div>
            </div>

            <div class="row mt-3">
                @foreach ($purchase->detail_purchase_orders as $detail)
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>SKU</span><span>{{ $detail->product->sku }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Nama Produk</span><span>{{ $detail->product->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Harga</span><span>{{ 'Rp ' . number_format($detail->price, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Qty</span><span>{{ $detail->qty }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Total</span><span>{{ 'Rp ' . number_format($detail->qty * $detail->price, 0, ',', '.') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer">
            <a href="/purchase/history" class="btn btn-icon icon-left btn-outline-secondary"><i
                    class="fas fa-arrow-left"></i>
                Kembali</a>
        </div>
    </div>
@endsection

@section('addFooter')
@endsection
