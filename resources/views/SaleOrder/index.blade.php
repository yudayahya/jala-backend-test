@extends('Layout.app')
@section('title')
    JALA - {{ $title }}
@endsection

@section('addHeader')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card card-info">
                                <div class="card-body">
                                    <form id="form-add-cart">
                                        <div class="form-group row">
                                            <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                                            <div class="col-sm-10 input-group">
                                                <input type="text" class="form-control" id="sku" name="sku" autofocus>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal"
                                                        data-target="#modal-items">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                                <div class="invalid-feedback" id="sku-error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="qty" class="col-sm-2 col-form-label">Qty</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="qty" id="qty" value="1"
                                                    min="1">
                                                <div class="invalid-feedback" id="qty-error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row justify-content-end">
                                            <button type="submit" id="btn-form-add-cart" class="btn btn-primary">
                                                <i class="fa fa-cart-plus pr-2"></i>Add Items
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card card-info">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-lg-12 d-flex justify-content-end">
                                            <h1 id="total-cart">-</h1>
                                        </div>
                                    </div>
                                    <form id="form-sale-order">
                                        <div class="form-group row">
                                            <label for="customer" class="col-sm-4 col-form-label">Nama Customer</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="customer" id="customer">
                                                <div class="invalid-feedback" id="customer-error"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <button id="btn-form-sale-order" type="submit"
                                            class="btn btn-block btn-success btn-lg">
                                            <i class="fas fa-paper-plane pr-2"></i>Submit Order
                                        </button>
                                    </form>
                                    <button id="cancel_sale" type="button" onclick="cancel_sale()"
                                        class="btn btn-block btn-warning mt-2">
                                        <i class="fas fa-sync-alt pr-2"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered table-striped" id="table-cart">
                                        <thead>
                                            <tr>
                                                <th width="4%">#</th>
                                                <th width="15%">SKU</th>
                                                <th>Nama Produk</th>
                                                <th width="4%">Qty</th>
                                                <th width="15%">Harga</th>
                                                <th width="15%">Total</th>
                                                <th width="6%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /.modal -->
    <div class="modal fade" id="modal-items">
        <div class="modal-dialog modal-xl" style="max-width: 90%; margin: 10 auto; display: flex;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pilih Produk</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table-product-list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td class="text-center" width="8%">
                                        <button class="btn btn-info" id="select" data-dismiss="modal"
                                            data-sku="{{ $product->sku }}">
                                            <i class="fa fa-check"></i> Select
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('addFooter')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table;
        $(function() {
            $('#table-product-list').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

            table = $('#table-cart').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "ajax": {
                    "url": "/sale/cart",
                    "type": "GET"
                },
            });

            total_cart();
        });

        function refreshTable() {
            total_cart();
            table.ajax.reload(null, false);
        }

        function total_cart() {
            $.ajax({
                type: "GET",
                url: "/sale/cart/total",
                dataType: "json",
                success: function(response) {
                    $('#total-cart').html(response.data);
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Data tidak valid.',
                    });
                }
            });
        }

        $(document).on('click', '#select', function() {
            var sku = $(this).data('sku');
            $('#sku').val(sku);
        });

        $('#form-add-cart').on('submit', function(event) {
            event.preventDefault();
            $('#btn-form-add-cart').prop('disabled', true);
            var formData = new FormData(this);
            $.ajax({
                url: "/sale/cart",
                enctype: 'multipart/form-data',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $('#btn-form-add-cart').prop('disabled', false);
                    $('#qty-error').html('');
                    $('#qty').removeClass('is-invalid');
                    $('#sku-error').html('');
                    $('#sku').removeClass('is-invalid');
                    $('#price-error').html('');
                    $('#price').removeClass('is-invalid');
                    $('#form-add-cart')[0].reset();
                    if (response.status == 'success') {
                        refreshTable();
                        Toast.fire({
                            icon: 'success',
                            title: response.message,
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message,
                        });
                    }
                },
                error: function(response) {
                    $('#btn-form-add-cart').prop('disabled', false);
                    if (response.responseJSON.errors.qty) {
                        $('#qty').addClass('is-invalid');
                        $('#qty-error').html(response.responseJSON.errors.qty);
                    } else {
                        $('#qty-error').html('');
                        $('#qty').removeClass('is-invalid');
                    }
                    if (response.responseJSON.errors.sku) {
                        $('#sku').addClass('is-invalid');
                        $('#sku-error').html(response.responseJSON.errors.sku);
                    } else {
                        $('#sku-error').html('');
                        $('#sku').removeClass('is-invalid');
                    }
                    if (response.responseJSON.errors.price) {
                        $('#price').addClass('is-invalid');
                        $('#price-error').html(response.responseJSON.errors.price);
                    } else {
                        $('#price-error').html('');
                        $('#price').removeClass('is-invalid');
                    }
                    Toast.fire({
                        icon: 'error',
                        title: 'Data Tidak Valid!',
                    });
                }
            });
        });

        function hapus_data(id) {
            $.ajax({
                type: "DELETE",
                url: "/sale/cart",
                dataType: "json",
                data: {
                    type: 'one',
                    id: id
                },
                success: function(response) {
                    refreshTable();
                    Toast.fire({
                        icon: 'success',
                        title: response.message
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

        function cancel_sale() {
            if (!table.data().any()) {
                Toast.fire({
                    icon: 'error',
                    title: 'Sale Cart Masih Kosong.'
                })
            } else {
                $.ajax({
                    type: "DELETE",
                    url: "/sale/cart",
                    dataType: "json",
                    data: {
                        type: 'all'
                    },
                    success: function(response) {
                        refreshTable();
                        Toast.fire({
                            icon: 'success',
                            title: response.message
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
        }

        $('#form-sale-order').on('submit', function(event) {
            event.preventDefault();
            if (!table.data().any()) {
                Toast.fire({
                    icon: 'error',
                    title: 'Sale Cart Masih Kosong.'
                })
            } else {
                Swal.fire({
                    title: 'Kamu Yakin?',
                    text: "Pastikan Data Sale Order Telah Sesuai.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya. Proses!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#btn-form-sale-order').prop('disabled', true);
                        var formData = new FormData(this);
                        $.ajax({
                            url: "/sale",
                            enctype: 'multipart/form-data',
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function(response) {
                                $('#btn-form-sale-order').prop('disabled', false);
                                $('#customer-error').html('');
                                $('#customer').removeClass('is-invalid');
                                $('#form-sale-order')[0].reset();
                                if (response.status == 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sale Order Processed',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(function() {
                                        location.replace('/sale/history/' + response
                                            .invoice);
                                    });
                                } else {
                                    refreshTable();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Sale Order Unprocessed',
                                        text: response.message,
                                        showConfirmButton: true,
                                    });
                                }
                            },
                            error: function(response) {
                                $('#btn-form-sale-order').prop('disabled', false);
                                if (response.responseJSON.errors.customer) {
                                    $('#customer').addClass('is-invalid');
                                    $('#customer-error').html(response.responseJSON.errors
                                        .customer);
                                } else {
                                    $('#customer-error').html('');
                                    $('#customer').removeClass('is-invalid');
                                }
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Data Tidak Valid!',
                                });
                            }
                        });
                    }
                })
            }
        });
    </script>
@endsection
