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
    public function store(Request $request)
    {
        $nomenclature = new NomenClature();
        $nomenclature->label = "vendors";
        $nomenclature->value = $request->custom_domain;
        $nomenclature->save();
        return redirect()->route('configure.customize')->with('success', 'Nomenclature Added Successfully!');
    }

}
