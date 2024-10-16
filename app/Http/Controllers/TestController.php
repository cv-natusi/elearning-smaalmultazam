<?php

namespace App\Http\Controllers;

use App\Imports\FileAImport;
use App\Imports\FileBImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function import()
    {
        Excel::import(new FileAImport, storage_path('FileA.xlsx'));
        Excel::import(new FileBImport, storage_path('FileB.xlsx'));

        return 'AB';
    }
}
