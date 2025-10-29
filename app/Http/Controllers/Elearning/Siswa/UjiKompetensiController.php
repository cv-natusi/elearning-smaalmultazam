<?php

namespace App\Http\Controllers\Elearning\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

# Library / package
use DataTables;
# Models
use App\Models\Soal;

class UjiKompetensiController extends Controller
{
    public function main(Request $request)
    {
        if ($request->ajax()) {
            $data = Soal::has('mata_pelajaran')->with('mata_pelajaran')->withCount('pertanyaan')->get();
            // return DataTables::of($data)->addIndexColumn()->addColumn('actions', function ($row) {
            //         return "<button class='btn btn-primary btnKerjakan' onclick='kerjakanSoal($row->id_soal,`$row->pendahuluan`)'><i class='bx bx-key'></i>Kerjakan</button>";
            //     })->rawColumns(['actions'])->toJson();
            return DataTables::of($data)->addIndexColumn()->addColumn('actions', function ($row) {
                    $publicUrl = asset('storage/' . $row->file_soal);
                    
                    return "<button class='btn btn-primary' 
                                onclick='downloadSoal(\"".$row->judul_soal."\", \"". $publicUrl ."\")'>
                                <i class='bx bx-download'></i>Download
                            </button>";
                })->rawColumns(['actions'])->toJson();
        }
        return view('main.content.siswa.uji-kompetensi.main');
    }
}
