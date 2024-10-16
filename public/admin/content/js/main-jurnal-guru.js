$(document).ready( () => {
    dataTable();
})

function dataTable() {
    const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
	let sDom = `
		<'row mb-2'
            <'col-sm-2'l>
            <'col-sm-5 templateTahunAjaran'>
			<'col-sm-3'f>
			<'col-sm-2 templateExport'>
		>
		<'row mt-2'<'col-sm-12'tr>>
		<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>
	`

    $('#dataTable').DataTable({
        sDom: sDom,
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
    });

    const templateTahunAjaran = `
		<div class="d-flex">
			<label class="my-1 pe-1">Tahun Ajaran</label>
			<select name="tahun_ajaran" aria-controls="tahun_ajaran" class="form-select form-select-sm w-50" id="tahun_ajaran">
				<option value="">Semua</option>
				<option value="">Aktif</option>
				<option value="">Tidak Aktif</option>
			</select>
		</div>
	`;

	const templateExport = `
		<div class="d-flex w-100">
			<button class="btn btn-success w-100 py-1" style="font-size:10pt;"><i class='bx bxs-file-export'></i>Export to Excel</button>
		</div>
	`

    $("div.templateTahunAjaran").html(templateTahunAjaran)
    $("div.templateExport").html(templateExport)
}