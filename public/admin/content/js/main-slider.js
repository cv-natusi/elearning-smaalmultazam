// var table = $('#dataTable').DataTable();

$(document).ready( async () => {
	await dataTable($('#status').val(),true)
})

function filter() {
	dataTable($('#status').val())
}

async function dataTable(status='',reorderInit=false) {
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

    var table = await $('#dataTable').DataTable({
        // sDom: sDom,
		// rowReorder: true,
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
			// search: 'Cari',
			// searchPlaceholder: 'Masukkan kata kunci',
		},
		ajax: {
			url: routeDatatable,
			data: {status: status},
		},
		columns: [
			// {data:'DT_RowIndex', className:'reorder', render: (data, type, row)=>{
			// 	// return `<p class="m-0 p-1">${data}</p>`
			// 	return `<p class="m-0 p-1"><a><i class="bx bx-radio-circle"></i></a></p>`
			// }},
			{data:'icon', className:'reorder'},
			{data:'id_slider', name:'id_slider'},
			{data:'gambar', name:'gambar'},
			{data:'actions', name:'actions'}
		],
		columnDefs: [{ orderable: false, targets: [0,1,2] }],
		dom: 'Bfrtip',
		rowReorder: {
			dataSrc: 'id_slider'
		},
		select: false
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

	if(reorderInit) {
		table.on( 'row-reorder', function ( e, diff, edit ) {
			// e.preventDefault()
			// console.log(diff);
			// // console.log(diff[0]);
			// // console.log(edit);
			// return
			if(diff.length==0) {
				return
			}
			var newSlider = [];
			for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
				// get id row
				// let idQ = diff[i].node.id;
				let idQ = diff[i].oldData;
				let idNewQ = idQ;
				// get position
				let position = diff[i].newPosition+1;
				//call funnction to update data
				// updateOrder(idNewQ, position);
				newSlider.push({id:idNewQ,position:position})
			}
			updateOrder(newSlider);
		} );
	}
    // $("div.templateStatus").html(templateStatus)
}

// function updateOrder(idQuestion, order){
function updateOrder(newSlider){
	// console.log(idQuestion);
	// console.log(order);
	$.post(routeUpdatePosition, { slider: newSlider } )
	.done(async function( data ) {
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
			setTimeout(async ()=>{
				await dataTable($('#status').val())
				// $('#dataTabel').DataTable().ajax.reload()
				// location.reload()
			}, 1100);
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

function tambahSliderGambar(id='') {
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

function hapusSliderGambar(id) {
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