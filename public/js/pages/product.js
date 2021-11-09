$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#form-tambah').on('submit', function(event) {
    event.preventDefault();
    $('#btn-form-submit').prop('disabled', true);
    var formData = new FormData(this);
    $.ajax({
        url: "/product",
        enctype: 'multipart/form-data',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            $('#btn-form-submit').prop('disabled', false);
            $('#name-error').html('');
            $('#name').removeClass('is-invalid');
            $('#sku-error').html('');
            $('#sku').removeClass('is-invalid');
            $('#price-error').html('');
            $('#price').removeClass('is-invalid');
            $('#form-tambah')[0].reset();
            Toast.fire({
                icon: 'success',
                title: response.message,
            });
        },
        error: function(response) {
            $('#btn-form-submit').prop('disabled', false);
            if (response.responseJSON.errors.name) {
                $('#name').addClass('is-invalid');
                $('#name-error').html(response.responseJSON.errors.name);
            } else {
                $('#name-error').html('');
                $('#name').removeClass('is-invalid');
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

$('#form-ubah').on('submit', function(event) {
    event.preventDefault();
    $('#btn-form-submit').prop('disabled', true);
    var formData = new FormData(this);
    $.ajax({
        url: "/product/update",
        enctype: 'multipart/form-data',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            $('#btn-form-submit').prop('disabled', false);
            $('#name-error').html('');
            $('#name').removeClass('is-invalid');
            $('#price-error').html('');
            $('#price').removeClass('is-invalid');
            if(response.status=='success'){
                Swal.fire({
                    icon: 'success',
                    title: 'Data Updated',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    location.replace('/product');
                });
            }else{
                Toast.fire({
                    icon: 'error',
                    title: response.message,
                });
            }
        },
        error: function(response) {
            $('#btn-form-submit').prop('disabled', false);
            if (response.responseJSON.errors.name) {
                $('#name').addClass('is-invalid');
                $('#name-error').html(response.responseJSON.errors.name);
            } else {
                $('#name-error').html('');
                $('#name').removeClass('is-invalid');
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
