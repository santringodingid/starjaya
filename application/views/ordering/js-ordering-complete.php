<script>
    let url = $('#url').val()
    let id = $('#id').val()

    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
    }

    $(function() {
        loadData()
    })

    const loadData = () => {
        $('#show-data').html('')
        $.ajax({
            url: `${url}ordering/loaddatacomplete`,
            method: 'POST',
            data: {
                id
            },
            beforeSend: function() {
                $('.skeleton_loading__').show()
            },
            success: function(res) {
                $('.skeleton_loading__').hide()
                $('#show-data').html(res)
            }
        })
    }

    const cancelOrder = id => {
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
                    url: `${url}ordering/cancel`,
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('.wrap-loading__').show()
                    },
                    success: function(res) {
                        $('.wrap-loading__').hide()
                        if (res.status == 400) {
                            errorAlert(res.message)
                            return false
                        }

                        toastr.success('Yeaah..! Pesanan berhasil dibatalkan')
                        loadData()
                    }
                })
            }
        })
    }

    $('#modal-edit').on('shown.bs.modal', () => {
        $('#package').focus()
    })

    $('#modal-edit').on('hidden.bs.modal', () => {
        $('#form-edit')[0].reset()
        $('#id-edit').val(0)
    })

    const editOrder = id => {
        $('#id-edit').val(id)
        $.ajax({
            url: `${url}ordering/getqty`,
            method: 'POST',
            data: {
                id
            },
            dataType: 'JSON',
            beforeSend: function() {
                $('.wrap-loading__').show()
            },
            success: function(res) {

                if (res.status == 400) {
                    errorAlert(res.message)
                    return false
                }

                $('#stock-info').text(res.stock)
                $('#package').val(res.data.package)
                $('#unit').val(res.data.unit)
                $('#modal-edit').modal('show')
            },
            complete: function() {
                $('.wrap-loading__').hide()
            }
        })
    }

    $('#submit-button-edit').on('click', function() {
        let id = $('#id-edit').val()
        let package = $('#package').val()
        let unit = $('#unit').val()
        if (package == 0 || package == '' && unit == 0 || unit == '') {
            errorAlert('Kuantiti Paket dan Satuan tidak boleh sama-sama NOL')
            return false;
        }

        $.ajax({
            url: `${url}ordering/update`,
            method: 'POST',
            data: {
                id,
                package,
                unit
            },
            dataType: 'JSON',
            beforeSend: function() {
                $('.wrap-loading__').show()
            },
            success: function(res) {
                if (res.status == 400) {
                    errorAlert(res.message)
                    return false
                }

                toastr.success('Yeaah..! Detail pesanan berhasil diubah')
                $('#modal-edit').modal('hide')
                loadData()
            },
            complete: function() {
                $('.wrap-loading__').hide()
            }
        })
    })

    const saveOrder = () => {
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
                    url: `${url}ordering/saveorder`,
                    method: 'POST',
                    data: {
                        id
                    },
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('.wrap-loading__').show()
                    },
                    success: function(res) {
                        $('.wrap-loading__').hide()
                        if (res.status == 400) {
                            errorAlert(res.message)
                            return false
                        }

                        toastr.success('Yeaah..! Pesanan berhasil disimpan')
                        loadData()
                    }
                })
            }
        })
    }
</script>
</body>

</html>