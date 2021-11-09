@extends('Layout.app')
@section('title')
    JALA - {{ $title }}
@endsection

@section('addHeader')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data {{ $title }}</h6>
            @role('Super Admin')
                <a href="/product/create" class="btn btn-icon icon-left btn-info"><i class="fas fa-plus"></i>
                    Tambah
                    Data</a>
            @endrole
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-data" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 10px;">No.</th>
                            <th>SKU</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            @role('Super Admin')
                                <th style="width: 150px;">Action</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
            table = $('#table-data').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "ajax": {
                    "url": "/product/data",
                    "type": "GET"
                },
            });
        });

        function refreshTable() {
            table.ajax.reload(null, false);
        }

        function hapus_data(id) {
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
                        url: "/product",
                        dataType: "json",
                        data: {
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
            })
        };
    </script>
@endsection
