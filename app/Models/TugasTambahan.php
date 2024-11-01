<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasTambahan extends Model
{
    use HasFactory;

    protected $table = 'tugas_tambahan';
    protected $primaryKey = 'id_tugas_tambahan';
    protected $guarded = ['id_tugas_tambahan'];
}
