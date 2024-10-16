<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasMapel extends Model
{
    use HasFactory;
    protected $table = "kelas_mapel";
    protected $primaryKey = "id_kelas_mapel";

    public $timestamps = false;

    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'id_mapel');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }
}
