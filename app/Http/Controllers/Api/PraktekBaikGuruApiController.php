<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PraktekBaikGuru;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class PraktekBaikGuruApiController extends Controller
{
    public function main($id='') {
		if($id) {
			$data = PraktekBaikGuru::getPraktekBaikGuruDetail($id);
			// return PraktekBaikGuru::getPraktekBaikGuruDetail($id);
		} else {
            // return 'tes';
			$data = PraktekBaikGuru::getPraktekBaikGuruPaginate();
			// return PraktekBaikGuru::getPraktekBaikGuruPaginate();
		}
        return $data;
        // return response()->json(['data'=>$data]);
        // return new PaginatedResourceResponse($data);
    }
}
