$(document).ready(function () {
	$.ajaxSetup({
		headers:{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	})
	$('#show_hide_password a').on('click', (e)=>{
		e.preventDefault()
		const type = $('#show_hide_password input').attr('type')
		if(type==='text'){
			$('#show_hide_password input').attr('type', 'password')
			$('#show_hide_password i').addClass('bx-hide')
			$('#show_hide_password i').removeClass('bx-show')
			return // die()
		}
		$('#show_hide_password input').attr('type', 'text')
		$('#show_hide_password i').removeClass('bx-hide')
		$('#show_hide_password i').addClass('bx-show')
	})

	$('.btnLogin').click(async(e)=>{
		e.preventDefault()
		const data = new FormData($('.formLogin')[0])
		try{
			await $.ajax({
				url: doLogin,
				type: 'POST',
				data: data,
				contentType: false,
				processData: false,
			}).done(async (data, status, xhr)=>{
				// console.log(data);return
				await Swal.fire({
					icon: 'success',
					title: data.metadata.message,
					showConfirmButton: false,
					timer: 900,
				})
				window.location.href = routeDashboard
			})
		}catch(e){ // 400-500 level error
			const code = e.status
			if(code===401){
				Swal.fire({
					icon: 'warning',
					title: 'Oops..',
					text: e.responseJSON.metadata.message,
				})
				return
			}
			Swal.fire({
				icon: 'error',
				title: 'Oops..',
				text: 'Terjadi kesalahan sistem',
			})
		}
	})
})
