<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    use HasFactory;

    /**
     *
     * @var string
     */
    protected $table = 'rapor';

    /**
     *
     * @var string
     */
    protected $primaryKey = 'id_rapor';

    /**
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'guru_id',
        'kelas_id',
        'tahun_ajaran_id',
        'semester',
        'link',
        'file',
    ];

    public function guru() {
        return $this->belongsTo(Guru::class,'guru_id','id_guru');
    }
    public function tahun_ajaran() {
        return $this->belongsTo(TahunAjaran::class,'tahun_ajaran_id','id_tahun_ajaran');
    }
    public function kelas() {
        return $this->belongsTo(Kelas::class,'kelas_id','id_kelas');
    }
}
