<?php

namespace App\Exports;

use App\OrderVendor;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderPromoCodeExport implements FromCollection{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return OrderVendor::all();
    }

    
}
