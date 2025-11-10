<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;

class RaporController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data['title'] = 'E-RAPOR';
    }

    public function main()
    {
        $data = $this->data;

        // Ambil URL iframe yang sudah di-set admin
        $data['iframe_url'] = env('IFRAME_RAPOR_URL', '');

        return view('main.content.guru.rapor.main', $data);
    }
}
