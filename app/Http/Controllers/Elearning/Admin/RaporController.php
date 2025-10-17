<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\SpreadsheetShare;
use App\Models\TahunAjaran;
use App\Models\Rapor;
use Illuminate\Http\Request;
use DataTables, Help;
use Illuminate\Support\Facades\Validator;

class RaporController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'E-RAPOR';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$rapor = Rapor::with(['guru', 'tahun_ajaran', 'kelas'])->get();
			return DataTables::of($rapor)->addIndexColumn()->addColumn('nama_guru', function ($row) {
				return $row->guru ? $row->guru->nama : '-';
			})->addColumn('file', function ($row) {
                if ($row->file) {
                    $url = asset('storage/rapor_files/' . $row->file);
                    return "<a href='" . $url . "' target='_blank' rel='noopener noreferrer' title='Buka File'>
                                <button class='btn ms-1 btn-success p-2'>
                                    <i class='bx bx-file'></i>
                                </button>
                            </a>";
                }
                return '<span class="badge bg-secondary">Tidak Ada File</span>';
			})->addColumn('link', function ($row) {
                if ($row->file) {
                    $url = $row->link;
                    return "<a href='" . $url . "' target='_blank' rel='noopener noreferrer' title='Buka File'>
                                <button class='btn ms-1 btn-info p-2'>
                                    <i class='bx  bx-link'  ></i> 
                                </button>
                            </a>";
                }
                return '<span class="badge bg-secondary">Tidak Ada Link</span>';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahDataGuru($row->id_rapor)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusDataGuru($row->id_rapor)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'file', 'link'])->toJson();
		}
		return view('main.content.admin.rapor.main', $data);
	}

	public function add(Request $request)
	{
		$data['rapor'] = Rapor::where('id_rapor', $request->id)->first();
		$data['guru'] = Guru::get();
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$content = view('main.content.admin.rapor.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
    {
        $request->semester = intval($request->semester);
        $rules = [
            'judul'             => 'required|string|max:255',
            'guru_id'           => 'required|integer',
            'kelas_id'          => 'required|integer',
            'tahun_ajaran_id'   => 'required|integer',
            'semester'          => 'required|integer',
            'link'              => 'nullable|string', 
            'rapor_file'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:4096', 
        ];

        $message = [
            'judul.required'           => 'Judul wajib diisi.',
            'guru_id.required'         => 'Kolom guru wajib diisi.',
            'kelas_id.required'        => 'Kolom kelas wajib diisi.',
            'tahun_ajaran_id.required' => 'Kolom tahun ajaran wajib diisi.',
            'semester.required'         => 'Kolom Semester wajib diisi.',
            'rapor_file.mimes'          => 'File rapor harus berformat PDF, Word, atau Excel.',
            'rapor_file.max'            => 'Ukuran file rapor maksimal 4MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $dataToSave = [
                'judul'             => $request->judul,
                'guru_id'           => $request->guru_id,
                'kelas_id'          => $request->kelas_id,
                'tahun_ajaran_id'   => $request->tahun_ajaran_id,
                'semester'          => $request->semester,
                'link'              => $request->link,
            ];

            if ($request->hasFile('rapor_file')) {
                $file = $request->file('rapor_file');
                $path = $file->store('public/rapor_files');
                $dataToSave['file'] = basename($path);
            }

            $rapor = Rapor::updateOrCreate(
                ['id_rapor' => $request->id],
                $dataToSave
            );

            return response()->json([
                'status'  => 'success',
                'message' => 'Data rapor berhasil disimpan.',
                'data'    => $rapor
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

	public function delete(Request $request)
	{
		$data = SpreadsheetShare::where('id_spreadsheet_share', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}
}
