<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Nomenclature;
use App\Models\NomenclatureTranslation;
use App\Http\Controllers\Client\BaseController;

class NomenclatureController extends BaseController

{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $this->validate($request, [
          'names.0' => 'required|string',
        ]);
        NomenclatureTranslation::truncate();
        $language_ids = $request->language_ids;
        foreach ($request->names as $key => $name) {
            $nomenclature = NomenClature::first();
            if($nomenclature){
                $nomenclature->update(['label' => "vendors"]);
            }else{
                $nomenclature = NomenClature::create(['label' => "vendors"]);
            }
            $nomenclature_translation =  new NomenclatureTranslation();
            $nomenclature_translation->name = $name;
            $nomenclature_translation->language_id = $language_ids[$key];
            $nomenclature_translation->nomenclature_id = $nomenclature->id;
            $nomenclature_translation->save();
        }
        return redirect()->route('configure.customize')->with('success', 'Nomenclature Saved Successfully!');
    }

}
