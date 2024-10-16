<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileA extends Model
{
    use HasFactory;
    protected $table = 'File1';
    protected $connection = 'test';
    protected $guarded = [];
    public $timestamps = false;
}
