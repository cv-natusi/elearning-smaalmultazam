<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileB extends Model
{
    use HasFactory;
    protected $table = 'File2';
    protected $connection = 'test';
    protected $guarded = [];
    public $timestamps = false;
}
