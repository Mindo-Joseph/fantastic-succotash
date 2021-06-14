<?php

namespace App\Imports;

use App\Models\{Vendor, CsvVendorImport};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VendorImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $data = array();
        $error = array();
        $i = 0;

        foreach ($rows as $row) {
            $row = $row->toArray();

            if ($row[0] != "logo") { //header of excel check
                if ($row[2] != "") { //check if name is empty
                    $vendor_check = Vendor::where('name', $row[0])->first();
                    if ($vendor_check) { //if not empty, then is it already exists
                        $error[] = "Row " . $i . " : Vendor name already Exist";
                    } else { //if not empty, neither exist, add it for entry
                        $data[] = $row;
                    }
                } else {
                    $error[] = "Row " . $i . " : Name cannot be empty";
                }
            }
            $i++;
        }

        if (!empty($data)) {
            foreach ($data as $da) {
                if (!Vendor::where('name', $da[0])->exists()) {
                    // insert vendor
                    $product = Vendor::insertGetId([
                        'name' => $da[2],
                        'desc' => ($da[3] == "") ? "NULL" : $da[3],
                        'logo' => ($da[0] == "") ? "NULL" : $da[0],
                        'banner' => ($da[1] == "") ? "NULL" : $da[1],
                        'address' => ($da[4] == "") ? "NULL" : $da[4],
                        'latitude' => ($da[5] == "") ? "NULL" : $da[5],
                        'longitude' => ($da[6] == "") ? "NULL" : $da[6],
                        'dine_in' => ($da[7] == 'TRUE') ? 1 : 0,
                        'takeaway' => ($da[8] == 'TRUE') ? 1 : 0,
                        'delivery' => ($da[9] == 'TRUE') ? 1 : 0,
                    ]);
                }
            }
        }

         if (!empty($error)) {
            dd($error);
            $vendor_csv = CsvVendorImport::first();
            $vendor_csv->status = 3;
            $vendor_csv->error = json_encode($error);
            $vendor_csv->save();
        }
        else{
            $vendor_csv = CsvVendorImport::first();
            $vendor_csv->status = 2;
            $vendor_csv->save();
        }
    }
}
