<?php

namespace App\Imports;

use App\Models\FileA;
use Maatwebsite\Excel\Concerns\ToModel;

class FileAImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new FileA([
            'no_faktur' => $row[0],
            'nilai' => $row[1],
        ]);
    }
}
