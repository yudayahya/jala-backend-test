<!-- Bootstrap core JavaScript-->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
    });
</script>
@role('Super Admin')
    <script>
        $(function() {
            $.ajax({
                type: "GET",
                url: "/sale/pending/count",
                dataType: "json",
                success: function(response) {
                    if (response.data > 0) {
                        $('#pending-count').html('<span class="badge badge-pill badge-danger">' +
                            response
                            .data + '</span>');
                    }
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Data tidak valid.',
                    });
                }
            });
        });
    </script>
@endrole
@yield('addFooter')
