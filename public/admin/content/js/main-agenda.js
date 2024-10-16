$(document).ready( async () => {
	await dataTable($('#status').val())
})

function filter() {
	dataTable($('#status').val())
}

async function dataTable(status='') {
    const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
	let sDom = `
		<'row mb-2'
            <'col-sm-2'l>
            <'col-sm-2 templateStatus'>
			<'col-sm-3'>
			<'col-sm-2'>
			<'col-sm-3'f>
		>
		<'row mt-2'<'col-sm-12'tr>>
		<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>
	`

    await $('#dataTable').DataTable({
        sDom: sDom,
		stateSave: false,
		scrollX: true,
		serverSide: true,
		processing: true,
		destroy: true,
        language: {
			processing: loading+' '+loading+' '+loading,
			// lengthMenu: `
			// 	Display<br>
			// 	<select name="dataTable_length" aria-controls="dataTable" class="form-select form-select-sm">
			// 		<option value="10">10</option>
			// 		<option value="20">20</option>
			// 		<option value="30">30</option>
			// 		<option value="40">40</option>
			// 		<option value="50">50</option>
			// 	</select>
			// `,
			search: 'Cari',
			searchPlaceholder: 'Masukkan kata kunci',
		},
		ajax: {
			url: routeDatatable,
			data: {status: status},
		},
		columns: [
			{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
				return `<p class="m-0 p-1">${data}</p>`
			}},
			{data:'penerbitan', name:'penerbitan'},
			{data:'judul', name:'judul'},
			{data:'tanggal_acara', name:'tanggal_acara'},
			{data:'status', name:'status'},
			{data:'actions', name:'actions'}
		],
    });

    const templateStatus = `
		<div class="d-flex">
			<label class="my-1 pe-1">Status</label>
			<select name="status" aria-controls="status" class="form-select form-select-sm" id="status" onchange="filter()">
				<option value="">Semua</option>
				<option value="1">Aktif</option>
				<option value="0">Tidak Aktif</option>
			</select>
		</div>
	`;

    $("div.templateStatus").html(templateStatus)
}

function tambahAgendaEvent(id='') {
	$('.main-page').hide();
	var url = routeBeritaAdd
	$.post(url, {id:id})
	.done(function(data){
		if(data.status == 'success'){
			$('.other-page').html(data.content).fadeIn();
		} else {
			$('.main-page').show();
		}
	})
	.fail(() => {
		$('.other-page').empty();
		$('.main-page').show();
	})
}

function aktifAgendaEvent(id) {
	var url = routeBeritaAktif
	$.post(url, {id:id})
	.done(function(data){
		console.log(data);
		if(data.code == 200){
			Swal.fire({
				icon: 'success',
				title: 'Berhasil',
				text: data.message,
				showConfirmButton: false,
				timer: 1200
			})
			setTimeout(async ()=>{
				await dataTable($('#status').val())
				// $('#dataTabel').DataTable().ajax.reload()
				// location.reload()
			}, 1100);
		} else {
			Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: data.message,
				showConfirmButton: false,
				timer: 1300,
			})
		}
	})
	.fail(() => {
		Swal.fire({
			icon: 'error',
			title: 'Whoops..',
			text: 'Terjadi kesalahan silahkan ulangi kembali',
			showConfirmButton: false,
			timer: 1300,
		})
	})
}

function hapusAgendaEvent(id) {
	Swal.fire({
		title: "Apakah Anda Yakin?",
		text: "Data Tersebut Akan Dihapus!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Ya, Hapus!"
	}).then((result) => {
		if (result.isConfirmed) {
			var url = routeBeritaDelete
			$.post(url, {id:id})
			.done(function(data){
				console.log(data);
				if(data.code == 200){
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: data.message,
						showConfirmButton: false,
						timer: 1200
					})
					setTimeout(async ()=>{
						await dataTable($('#status').val())
						// $('#dataTabel').DataTable().ajax.reload()
						// location.reload()
					}, 1100);
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Whoops',
						text: data.message,
						showConfirmButton: false,
						timer: 1300,
					})
				}
			})
			.fail(() => {
				Swal.fire({
					icon: 'error',
					title: 'Whoops..',
					text: 'Terjadi kesalahan silahkan ulangi kembali',
					showConfirmButton: false,
					timer: 1300,
				})
			})
		}
	});
	
}