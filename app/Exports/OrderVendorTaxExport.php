<?php

namespace App\Exports;

use App\Models\OrderVendor;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderVendorTaxExport implements FromCollection{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return OrderVendor::all();
    }
}
