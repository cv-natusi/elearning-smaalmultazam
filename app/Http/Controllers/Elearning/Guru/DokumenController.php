<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use DataTables;

class DokumenController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data['title'] = 'Dokumen';
    }

    public function main(Request $request)
    {
        $data = $this->data;
        if ($request->ajax()) {
            $dokumen = Dokumen::orderBy('id_dokumen', 'DESC')
                ->get();
            return DataTables::of($dokumen)->addIndexColumn()->addColumn('tanggal', function ($row) {
                return date('Y F d H:i:s', strtotime($row->updated_at));
            })->editColumn('keterangan', function ($row) {
                $mapel = '';
                if (strlen($row->keterangan) > 20) {
                    $mapel = substr($row->keterangan, 0, 20) . '...';
                } else {
                    $mapel = $row->keterangan;
                }
                return $mapel;
            })->editColumn('judul', function ($row) {
                $judul = '';
                if (strlen($row->judul) > 20) {
                    $judul = substr($row->judul, 0, 20) . '...';
                } else {
                    $judul = $row->judul;
                }
                return $judul;
            })->addColumn('actions', function ($row) {
                $urlMateri = url('uploads/dokumen');
                $html = "<a href='$urlMateri/$row->file' target='_blank' class='btn btn-dark btn-purple p-2'><i class='bx bx-search-alt-2 mx-1'></i></a>";
                return $html;
            })->rawColumns(['actions'])->toJson();
        }
        return view('main.content.guru.dokumen.main', $data);
    }
}
