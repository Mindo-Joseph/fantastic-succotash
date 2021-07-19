<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use App\Models\Nomenclature;

class NomenclatureController extends BaseController

{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $result = NomenClature::first();
        if($result){
            $result->update(['label' => "vendors", 'value' => $request->custom_domain]);
        }else{
            NomenClature::create(['label' => "vendors", 'value' => $request->custom_domain]);
        }
        return redirect()->route('configure.customize')->with('success', 'Nomenclature Saved Successfully!');
    }

}
