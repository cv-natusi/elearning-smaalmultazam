<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterJenisFile extends Model
{
    use HasFactory;

    protected $table = 'master_jenis_file';

    protected $fillable = [
        'nama',
        'tampil_pada_siswa',
    ];

    protected $casts = [
        'tampil_pada_siswa' => 'boolean',
    ];

    public function soal(): HasMany
    {
        return $this->hasMany(Soal::class, 'jenis_file');
    }
}