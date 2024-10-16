<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\SpreadsheetShare;
use Illuminate\Http\Request;
use Auth;

class RaporController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'E-RAPOR';
	}

    public function main()
    {
        $id_user = Auth::user()->id;
        $data['rapor'] = SpreadsheetShare::whereHas('guru', function ($q) use ($id_user) {
            $q->where('users_id', $id_user);
        })->get();
        return view('main.content.guru.rapor.main', $data);
    }
}
