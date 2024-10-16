$(document).ready( () => {
    dataTable();
})

function dataTable() {
    const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
	let sDom = `
		<'row mb-2'
            <'col-sm-2'l>
            <'col-sm-5'>
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

	const templateExport = `
		<div class="d-flex w-100">
			<button class="btn btn-success w-100 py-1" style="font-size:10pt;"><i class='bx bxs-file-export'></i>Export to Excel</button>
		</div>
	`
    $("div.templateExport").html(templateExport)
}