@extends('Layout.app')
@section('title')
    JALA - {{ $title }}
@endsection

@section('addHeader')
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }}
                {{ $orders->invoice ? $orders->invoice : '-' }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <h3 class="mr-3">{{ $orders->invoice ? $orders->invoice : '-' }}</h3>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-4">
                            User
                        </div>
                        <div class="col-lg-8">
                            <b>{{ $orders->user->name }}</b>
                        </div>
                        <div class="col-lg-4">
                            Customer
                        </div>
                        <div class="col-lg-8">
                            <b>{{ $orders->customer }}</b>
                        </div>
                        <div class="col-lg-4">
                            Status
                        </div>
                        <div class="col-lg-8">
                            <span
                                class="badge badge-{{ $orders->status == 'success' ? 'success' : 'secondary' }}"><b>{{ $orders->status }}</b></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                @php
                    $total = 0;
                @endphp
                @foreach ($orders->detail_sale_orders as $detail)
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
                    @php
                        $total += $detail->qty * $detail->price;
                    @endphp
                @endforeach
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 d-flex justify-content-between">
                                    <h3>Total</h3>
                                    <h3><b>{{ 'Rp ' . number_format($total, 0, ',', '.') }}</b></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            @role('Super Admin')
                <a href="/sale/{{ $orders->status == 'success' ? 'history' : 'pending' }}"
                    class="btn btn-icon icon-left btn-outline-secondary"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
                @if ($orders->status == 'pending')
                    <button type="button" onclick="approve_order('{{ $orders->id }}')"
                        class="btn btn-icon icon-left btn-outline-success"><i class="fas fa-check"></i>
                        Approve Order</button>
                @endif
                <button type="button" onclick="hapus_order('{{ $orders->id }}')"
                    class="btn btn-icon icon-left btn-outline-danger"><i class="fas fa-trash"></i>
                    Cancel Order</button>
            @else
                <a href="/sale/history" class="btn btn-icon icon-left btn-outline-secondary"><i class="fas fa-arrow-left"></i>
                    Kembali</a>
            @endrole
        </div>
    </div>
@endsection

@section('addFooter')
    @role('Super Admin')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function approve_order(id) {
                Swal.fire({
                    title: 'Kamu Yakin?',
                    text: "Pending Sale Order Akan Disetujui Dan Berubah Menjadi Sale Order!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya. Approve!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "PUT",
                            url: "/sale/pending",
                            dataType: "json",
                            data: {
                                id: id
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sale Order Approved',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Data tidak valid.',
                                });
                            }
                        });
                    }
                })
            };

            function hapus_order(id) {
                Swal.fire({
                    title: 'Kamu Yakin?',
                    text: "Data Tidak Akan Bisa Dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya. Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "/sale",
                            dataType: "json",
                            data: {
                                id: id
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sale Order Deleted',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {
                                    location.replace('/sale/history');
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Data tidak valid.',
                                });
                            }
                        });
                    }
                })
            };
        </script>
    @endrole
@endsection
