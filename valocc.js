function gagal() {
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Pastikan semua field sudah terisi!",
    });
}
    
$(document).ready(function () {
        $('form').on('submit', function (e) {
            var validations = [{
                field: '#rid',
                error: 'error-rid',
                condition: $('#rid').val() == "" || $('#rid').val().length > 6,
                message: 'Room ID belum diisi atau lebih dari 6 karakter'
            },
            {
                field: '#gid',
                error: 'error-gid',
                condition: $('#gid').val() == "",
                message: 'Group ID belum diisi'
            },
      
            {
                field: '#dari',
                error: 'error-dari',
                condition: $('#dari').val() == "",
                message: 'Dari belum diisi'
            },
            {
                field: '#sampai',
                error: 'error-sampai',
                condition: $('#sampai').val() == "" || new Date($('#sampai').val()) < new Date($('#dari').val()),
                message: 'Sampai belum diisi atau tanggalnya sebelum Dari'
            },
            {
                field: '#ready',
                error: 'error-ready',
                condition: $('#ready').val() == "",
                message: 'Ready belum diisi'
            },
            {
                field: '#checkin',
                error: 'error-checkin',
                condition: $('#checkin').val() == "",
                message: 'Check In belum diisi'
            },
            {
                field: '#checkout',
                error: 'error-checkout',
                condition: $('#checkout').val() == "" || new Date($('#checkout').val()) < new Date($('#checkin').val()),
                message: 'Check Out belum diisi atau tanggalnya sebelum Check In'
            },
            {
                field: '#rate',
                error: 'error-rate',
                condition: $('#rate').val() == "" || isNaN($('#rate').val()),
                message: 'Rate belum diisi atau bukan angka'
            },
            {
                field: '#groupid',
                error: 'error-groupid',
                condition: $('#groupid').val() == "" || $('#groupid').val().length > 6,
                message: 'Group ID belum diisi atau lebih dari 6 karakter'
            }
            ];
            $('.alert').addClass('d-none');
            $('.alert').text('');

            validations.forEach(function (validation) {
                if (validation.condition) {
                    $('#' + validation.error).removeClass('d-none');
                    $('#' + validation.error).text(validation.message);
                    e.preventDefault();
                }
            });
            if (!e.isDefaultPrevented()) {
                succes(e);
            } else {
                gagal();
            }
        });
  });