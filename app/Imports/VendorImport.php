<?php

namespace App\Imports;

use App\Models\Vendor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VendorImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if($row[0] != "logo"){
                
            }
        }
    }
}
