<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpreadsheetShare extends Model
{
    use HasFactory;
    protected $table = "spreadsheet_share";
    protected $primaryKey = "id_spreadsheet_share";

    public function guru() {
        return $this->belongsTo(Guru::class,'guru_id','id_guru');
    }
}
