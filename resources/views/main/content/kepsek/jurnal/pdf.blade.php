<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<style>
		table.minimalistBlack {
			width: 100%;
			text-align: left;
			border-collapse: collapse;
		}
		table.minimalistBlack td, table.minimalistBlack th {
			border: 1px solid #000000;
			padding: 5px 4px;
		}
		table.minimalistBlack tbody td {
			font-size: 13px;
		}
		table.minimalistBlack thead {
			background: #CFCFCF;
			background: -moz-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
			background: -webkit-linear-gradient(top, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
			background: linear-gradient(to bottom, #dbdbdb 0%, #d3d3d3 66%, #CFCFCF 100%);
			border-bottom: 3px solid #000000;
		}
		table.minimalistBlack thead th {
			font-size: 14px;
			font-weight: bold;
			color: #000000;
			text-align: center;
		}
		table.minimalistBlack tfoot td {
			font-size: 16px;
		}
	</style>
</head>
<body>
	<span>Jurnal Guru Tgl {{date('d-m-Y',strtotime($start_date))}} - {{date('d-m-Y',strtotime($end_date))}}</span>
	<hr>
	<table class="minimalistBlack">
		<thead>
			<tr>
				<th width="5%">No</th>
				<th width="20%">Nama</th>
				<th width="20%">Tanggal Upload</th>
				<th width="50%">Isi Jurnal</th>
			</tr>
		</thead>
		<tbody class="table">
			@foreach ($data as $item)
			<tr>
				<td>{{$loop->index+1}}</td>
				<td>{{$item->user->guru->nama}}</td>
				<td>{{$item->tanggal_upload}}</td>
				<td>{{$item->jurnal}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>