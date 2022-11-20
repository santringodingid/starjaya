<script>
    let url = $('#url').val()
    $('[data-mask]').inputmask();

    toastr.options = {
        "positionClass": "toast-top-center",
        "timeOut": "2000"
    }

    const errorAlert = message => {
        toastr.error(`Opss.! ${ message }`)
    }

    $('body').on('keyup', e => {
        if (e.keyCode == 113) {
            $('#changeName').focus().val('')
        }
    })

    $(function() {
        loadData()
    })

    const loadData = () => {
        $('.skeleton_loading__').show()
        $('#show-master').html('')

        let name = $('#changeName').val()
        let category = $('#changeCategory').val()
        $.ajax({
            url: `${url}master/loaddata`,
            method: 'POST',
            data: {
                name,
                category
            },
            success: function(response) {
                $('#show-master').html(response)
            },
            complete: function() {
                $('.skeleton_loading__').hide()
            }
        })
    }

    $('#add-button').on('click', function() {
        let category = $('#changeCategory').val()
        if (category == '') {
            errorAlert('Pilih kategori dulu')
            return false
        }

        $('#modal-master').modal('show')
    })

    $('#changeCategory').on('change', function() {
        let tableType = $(this).val()
        $('#table-type').val(tableType);

        if (tableType == 'markets') {
            $('#type-name').html('Toko')
        } else if (tableType == 'customers') {
            $('#type-name').html('Pelanggan')
        } else {
            $('#type-name').html('')
        }
    })

    $('#modal-master').on('shown.bs.modal', () => {
        $('#name').focus()
    })

    $('#modal-master').on('hidden.bs.modal', () => {
        $('#form-master')[0].reset()
        $('#id').val(0)
        $('#table-type').val(0)
        let validator = $('#form-master').validate();
        validator.resetForm();
        $('.form-feedback').find('.is-invalid').removeClass('is-invalid')
    })


    $('#form-master').validate({
        rules: {
            name: {
                required: true
            },
            address: {
                required: true
            },
            phone: {
                required: true
            }
        },
        messages: {
            name: {
                required: 'Isi dulu nama'
            },
            address: {
                required: 'Isi dulu alamat'
            },
            phone: {
                required: 'Isi dulu nomor HP'
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-feedback').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function() {
            save()
        }
    });

    const save = () => {
        $.ajax({
            url: `${url}master/save`,
            method: 'POST',
            data: $('#form-master').serialize(),
            dataType: 'JSON',
            beforeSend: function() {
                $('.wrap-loading__').show()
                $('#submit-button').prop('disabled', true).text('Data sedang dikirm')
            },
            success: function(res) {
                $('.wrap-loading__').hide()
                $('#submit-button').prop('disabled', false).text('Simpan')
                if (res.status == 400) {
                    errorAlert(res.message)
                    return false
                }
                $('#modal-master').modal('hide')
                toastr.success(`Yeaah..! ${res.message}`)
                loadData()
            }
        })
    }

    const editMaster = (id, tableType) => {
        $('.wrap-loading__').show()
        $.post(`${url}master/edit`, {
                id,
                tableType
            }, function(res) {
                $('.wrap-loading__').hide()

                if (res.status == 400) {
                    errorAlert(res.message)
                    return false
                }
                $('#id').val(id)
                $('#table-type').val(tableType)
                $('#name').val(res.data.name)
                $('#address').val(res.data.address)
                $('#phone').val(res.data.phone)
                $('#modal-master').modal('show')
            }, 'JSON')
            .fail(function(jqXHR, textStatus, errorThrown) {
                $('.wrap-loading__').hide()
                errorAlert(formatErrorMessage(jqXHR, errorThrown))
            })
    }
</script>
</body>

</html>