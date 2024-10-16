$(document).ready( async () => {
    await dataTable();
	var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
		keyboard: false
	})
	$('.btn-import').click(()=>{
		myModal.show()
	})
	$('.btn-save').click(()=>{
		var data = new FormData($('#alumniImport')[0])
		$('.btn-save').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
                url: routeAlumniImport,
                type: 'POST',
                data: data,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    if(data.code==200){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1200
                        })
                        setTimeout(()=>{
                            $('.other-page').fadeOut(()=>{
                                $('#datatabel').DataTable().ajax.reload()
                                location.reload()
                            })
                        }, 1100);
                        location.reload()
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: 'Whoops',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1300,
                        })
                    }
                    $('.btn-save').attr('disabled',false).html('SIMPAN')
                }
            }).fail(()=>{
                Swal.fire({
                    icon: 'error',
                    title: 'Whoops..',
                    text: 'Terjadi kesalahan silahkan ulangi kembali',
                    showConfirmButton: false,
                    timer: 1300,
                })
                $('.btn-save').attr('disabled',false).html('SIMPAN')
            })
	})
})

async function dataTable() {
    const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
	let sDom = `
		<'row mb-2'
            <'col-sm-2'l>
            <'col-sm-4 templateTahunLulus'>
			<'col-sm-2'f>
			<'col-sm-2 templateImport'>
			<'col-sm-2 templateExport'>
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
		},
		columns: [
			{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
				return `<p class="m-0 p-1">${data}</p>`
			}},
			{data:'updated_at', name:'updated_at',render: (data, type, row)=>{
				var tgl = new Date(data)
				var hari = tgl.getDate()
				var bulan = tgl.getMonth()
				var tahun = tgl.getFullYear()
				return `<p class="m-0 p-1">${hari}-${bulan}-${tahun}</p>`
			}},
			{data:'nisn', name:'nisn'},
			{data:'nama', name:'nama'},
			{data:'tahun_lulus', name:'tahun_lulus'},
			{data:'no_telp', name:'no_telp'},
			{data:'keterangan', name:'keterangan'},
		],
    });

    const templateTahunLulus = `
		<div class="d-flex">
			<label class="my-1 pe-1 d-block">Tahun Lulus</label>
			<select name="tahun_lulus" aria-controls="tahun_lulus" class="form-select form-select-sm w-50" id="tahun_lulus">
				<option value="">Semua</option>
				<option value="">Aktif</option>
				<option value="">Tidak Aktif</option>
			</select>
		</div>
	`;

	const templateExport = `
		<div class="d-flex w-100">
			<button class="btn btn-danger w-100 py-1" style="font-size:10pt;"><i class='bx bxs-file-export'></i>Export to Excel</button>
		</div>
	`
	const templateImport = `
		<div class="d-flex w-100">
			<button class="btn btn-success w-100 py-1 btn-import" style="font-size:10pt;"><i class='bx bxs-file-import'></i>Import Excel</button>
		</div>
	`

    $("div.templateTahunLulus").html(templateTahunLulus)
    // $("div.templateExport").html(templateExport)
    $("div.templateImport").html(templateImport)
}