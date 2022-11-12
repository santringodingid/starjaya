let url = $('#url').val()
$('[data-mask]').inputmask();

$('#reservation').daterangepicker({
    ranges: {
        'Hari ini': [moment(), moment()],
        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 hari terakhir': [moment().subtract(6, 'days'), moment()],
        '30 hari terakhir': [moment().subtract(29, 'days'), moment()],
        'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
        'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Reset',
        applyLabel: 'Terapkan'
    }
})

$('#reservation').on('apply.daterangepicker', function(ev, picker) {
    $(this).val('').attr('placeholder', picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    $('#start-date').val(picker.startDate.format('YYYY-MM-DD'))
    $('#end-date').val(picker.endDate.format('YYYY-MM-DD'))

    loadData()
});

$('#reservation').on('cancel.daterangepicker', function(ev, picker) {
    $(this).attr('placeholder', 'Semua waktu').val('');
    $('#start-date').val('')
    $('#end-date').val('')

    loadData()
});



$(function(){
    loadData()
})

const loadData = () => {
	$('#show-data').html('')

    let status = $('#changeStatus').val()
    let customer = $('#changeCustomer').val()
    let startDate = $('#start-date').val()
    let endDate = $('#end-date').val()

    $.ajax({
        url: `${url}ordering/loaddata`,
        method: 'POST',
        data: { status, customer, startDate, endDate },
        beforeSend: function(){
            $('.skeleton_loading__').show()
        },
        success: function(res){
            $('#show-data').html(res)
        },
        complete: function(){
            $('.skeleton_loading__').hide()
        }
    })
}

const detailOrder = id => {
    $.ajax({
        url: `${url}ordering/detail`,
        method: 'POST',
        data: { invoice: id },
        beforeSend: function(){
            $('.wrap-loading__').show()
        },
        success: function(res){
            $('#show-detail').html(res)
        },
        complete: function(){
            $('#modal-detail').modal('show')
            $('.wrap-loading__').hide()
        }
    })
}

const approveOrder = id => {
    Swal.fire({
        title: 'Yakin, nih?',
        text: 'Tindakan ini hanya bisa dilakukan sekali',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin, dong!',
        cancelButtonText: 'Nggak jadi',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${url}ordering/approve`,
                method: 'POST',
                data: { id },
                dataType: 'JSON',
                beforeSend: function(){
                    $('.wrap-loading__').show()
                },
                success: function(res){
                    $('.wrap-loading__').hide()
                    if (res.status == 400) {
                        errorAlert(res.message)
                        return false
                    }
                    loadData()
                }
            })
        }
    })
}

const deliverOrder = id => {
    Swal.fire({
        title: 'Yakin, nih?',
        text: 'Tindakan ini hanya bisa dilakukan sekali',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin, dong!',
        cancelButtonText: 'Nggak jadi',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `${url}ordering/deliver`,
                method: 'POST',
                data: { id },
                dataType: 'JSON',
                beforeSend: function(){
                    $('.wrap-loading__').show()
                },
                success: function(res){
                    $('.wrap-loading__').hide()
                    if (res.status == 400) {
                        errorAlert(res.message)
                        return false
                    }
                    loadData()
                }
            })
        }
    })
}

const printOut = () => $('#form-print').submit()

