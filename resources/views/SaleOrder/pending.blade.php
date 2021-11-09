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
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-data" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 10px;">No.</th>
                            <th>User</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->customer }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td><span
                                        class="badge badge-{{ $order->status == 'success' ? 'success' : 'secondary' }}">{{ $order->status }}</span>
                                </td>
                                <td><a class="btn btn-sm btn-info" href="/sale/history/{{ $order->id }}"><i
                                            class="far fa-eye"></i> Lihat</a></td>
                            </tr>
                        @endforeach
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
        $(function() {
            $('#table-data').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
