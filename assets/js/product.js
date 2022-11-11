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

$(function () {
	loadData()
})

const loadData = () => {
	$('.skeleton_loading__').show()
	$('#show-product').html('')

	let name = $('#changeName').val()
	let category = $('#changeCategory').val()
	$.ajax({
		url: `${url}product/loaddata`,
		method: 'POST',
		data: {
			name,
			category
		},
		success: function (response) {
			$('#show-product').html(response)
		},
		complete: function () {
			$('.skeleton_loading__').hide()
		}
	})
}

function copyToClipboard(text) {
	var sampleTextarea = document.createElement("textarea");
	document.body.appendChild(sampleTextarea);
	sampleTextarea.value = text; //save main text in it
	sampleTextarea.select(); //select textarea contenrs
	document.execCommand("copy");
	document.body.removeChild(sampleTextarea);
	toastr.success('ID berhasil disalin ke clipboard')
}

$('#modal-product').on('shown.bs.modal', () => {
	$('#name').focus()
})

$('#modal-product').on('hidden.bs.modal', () => {
	$('#form-product')[0].reset()
	$('#id').val(0)
	let validator = $('#form-product').validate();
	validator.resetForm();
	$('.form-feedback').find('.is-invalid').removeClass('is-invalid')
})

    
$('#form-product').validate({
	rules: {
		name: {
			required: true
		},
		category: {
			required: true
		},
		package: {
			required: true
		},
		unit: {
			required: true
		},
		amount: {
			required: true,
			number: true
		},
		image: {
			required: true
		}
	},
	messages: {
		name: {
			required: 'Isi dulu nama barang'
		},
		category: {
			required: 'Pilih dulu kategori barang'
		},
		package: {
			required: 'Pilih dulu paket barang'
		},
		unit: {
			required: 'Pilih dulu satuan barang'
		},
		amount: {
			required: 'Isi kuantitas satuan',
			number: 'Harus angka'
		},
		image: {
			required: 'Pilih dulu foto barang'
		},
	},
	errorElement: 'span',
	errorPlacement: function (error, element) {
		error.addClass('invalid-feedback');
		element.closest('.form-feedback').append(error);
	},
	highlight: function (element, errorClass, validClass) {
		$(element).addClass('is-invalid');
	},
	unhighlight: function (element, errorClass, validClass) {
		$(element).removeClass('is-invalid');
	},
	submitHandler: function () {
		save()
	}
});

$(function(){
	if (window.File && window.FileList && window.FileReader) 
        {
          $('#image').on('change', function(e) 
          {
            var file = e.target.files,
            imagefiles = $('#image')[0].files;
            var i = 0;
            $.each(imagefiles, function(index, value){
              var f = file[i];
              var fileReader = new FileReader();
              fileReader.onload = (function(e) {
				$('#image-preview').prop('src', e.target.result)
				$('#image-label').text(value.name)
				$('#remove-image').click(function(){
					$('#image').val('')
					$('#image-preview').prop('src', '')
					$('#image-label').text('Pilih Image')
				});
              });
              fileReader.readAsDataURL(f);
              i++;
            });
          });
        } else {
          alert("Your browser doesn't support to File API")
        }
})

const save = () => {
	$.ajax({
		url: `${url}product/save`,
		method: 'POST',
		data: $('#form-product').serialize(),
		dataType: 'JSON',
		beforeSend: function () {
            $('.wrap-loading__').show()
			$('#submit-button').prop('disabled', true).text('Data sedang dikirm')
		},
		success: function (res) {
            $('.wrap-loading__').hide()
			$('#submit-button').prop('disabled', false).text('Simpan')
			if (res.status == 400) {
				errorAlert(res.message)
				return false
			}
			$('#modal-product').modal('hide')
			toastr.success(`Yeaah..! ${res.message}`)
			loadData()
		}
	})
}

const editProduct = id => {
	$('.wrap-loading__').show()
	$.post(`${url}product/edit`, {
		id
	}, function (res) {
		$('.wrap-loading__').hide()

		if (res.status == 400) {
			errorAlert(res.message)
			return false
		}
		$('#id').val(id)
		$('#name').val(res.data.name)
		$('#category').val(res.data.category)
		$('#package').val(res.data.package)
		$('#unit').val(res.data.unit)
		$('#amount').val(res.data.amount)
		$('#modal-product').modal('show')
	}, 'JSON')
	.fail(function (jqXHR, textStatus, errorThrown) {
		$('.wrap-loading__').hide()
		errorAlert(formatErrorMessage(jqXHR, errorThrown))
	})
}

$("#image").change(function(){
	var allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
	var file = this.files[0];
	var fileType = file.type;
	if(!allowedTypes.includes(fileType)) {
		errorAlert('Pilih image ekstensi JPG/PNG')
		$(this).val('');
		return false;
	}
});

const uploadImage = id => $('#id-upload').val(id)

$('#form-upload').on('submit', function(e){
	e.preventDefault();
	$.ajax({
		xhr: function() {
			var xhr = new window.XMLHttpRequest();         
			xhr.upload.addEventListener('progress', function(element) {
				if (element.lengthComputable) {
					$('.progress-wrapper').show()
					var percentComplete = Math.round(((element.loaded / element.total) * 100));
					$("#file-progress-bar").width(percentComplete + '%');
					$("#file-progress-bar").html(percentComplete+'%');
				}
			}, false);
			return xhr;
		},
		type: 'POST',
		url: `${url}product/upload`,
		data: new FormData(this),
		contentType: false,
		cache: false,
		processData:false,
		dataType:'JSON',
		beforeSend: function(){
			$("#file-progress-bar").width('0%');
		},
		success: function(res){
			$('.progress-wrapper').hide()
			$('#image').val('')
			$('#image-preview').prop('src', '')
			$('#image-label').text('Pilih Image')
			$('#id-upload').val(0)
			$('#modal-upload').modal('hide')
			if(res.status == 400){
				errorAlert(res.message)
				return false
			}

			toastr.success(`Yeaahh..! ${res.message}`)
			loadData()
		},
		error: function (xhr, ajaxOptions, thrownError) {
			errorAlert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText)
		}
	});
})

const previewImage = el => {
	$('#modal-content-image').prop('src', $(el).prop('src'))
	$('#modal-image').show()
}

$('#close').on('click', function(){
	$('#modal-image').hide()
})
