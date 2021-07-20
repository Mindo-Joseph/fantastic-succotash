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
          'loyalty_cards_names.0' => 'required|string',
        ]);
        NomenclatureTranslation::truncate();
        $language_ids = $request->language_ids;
        $loyalty_cards_language_ids = $request->loyalty_cards_language_ids;
        foreach ($request->names as $key => $name) {
            if($name){
                $nomenclature = NomenClature::where('label', 'vendors')->first();
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
        }
        if(count($request->loyalty_cards_names) > 0){
            foreach ($request->loyalty_cards_names as $ke => $loyalty_cards_name) {
                $nomenclature = NomenClature::where('label', 'Loyalty Cards')->first();
                if($nomenclature){
                    $nomenclature->update(['label' => "Loyalty Cards"]);
                }else{
                    $nomenclature = NomenClature::create(['label' => "Loyalty Cards"]);
                }
                $nomenclature_translation =  new NomenclatureTranslation();
                $nomenclature_translation->name = $loyalty_cards_name;
                $nomenclature_translation->language_id = $loyalty_cards_language_ids[$ke];
                $nomenclature_translation->nomenclature_id = $nomenclature->id;
                $nomenclature_translation->save();
            }
        }
        return redirect()->route('configure.customize')->with('success', 'Nomenclature Saved Successfully!');
    }

}
