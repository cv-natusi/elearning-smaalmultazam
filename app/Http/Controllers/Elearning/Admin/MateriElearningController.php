<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;

class MateriElearningController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Materi';
        $this->data['breadCrumb'] = ['Data Master'];
	}

    public function main(Request $request)
    {
        $data = $this->data;
        if ($request->ajax()) {
            $mapel = MataPelajaran::orderBy('id_mapel', 'DESC')
                ->has('kelas_mapel')
                ->with('kelas_mapel', function ($q) {
                    $q->has('kelas')->with('kelas');
                })
                ->get();
            return DataTables::of($mapel)->addIndexColumn()->addColumn('kelas', function ($row) {
                $kelas = '';
                foreach ($row->kelas_mapel as $key => $value) {
                    $kelas .= $value->kelas->nama_kelas . ", ";
                };
                return $kelas;
            })->addColumn('actions', function ($row) {
                $html = "<button onclick='tambahMapel($row->id_mapel)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
                $html .= "<button onclick='hapusMapel($row->id_mapel)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
                return $html;
            })->rawColumns(['actions'])->toJson();
        }
        return view('elearning.admin.materi-elearning.main',$data);
    }

    public function add(Request $request)
    {
        $data['mapel'] = MataPelajaran::find($request->id);
        $content = view('main.content.admin.master.mata-pelajaran.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
}
