<?php

namespace App\Imports;

use App\Models\FileB;
use Maatwebsite\Excel\Concerns\ToModel;

class FileBImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new FileB([
            'no_faktur' => $row[0],
            'nilai' => $row[1],
        ]);
    }
}
