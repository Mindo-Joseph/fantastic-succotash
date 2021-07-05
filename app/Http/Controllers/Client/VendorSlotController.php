<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\{ClientPreference, VendorSlotDate, Vendor, VendorSlot, SlotDay, ServiceArea};
use Illuminate\Http\Request;

class VendorSlotController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '', $id)
    {
        $vendor = Vendor::where('id', $id)->firstOrFail();

        $slotData = array();
        $dine_in = in_array('dine_in', $request->slot_type) ? '1' : 0;
        $takeaway = in_array('takeaway', $request->slot_type) ? '1' : 0;
        $delivery = in_array('delivery', $request->slot_type) ? '1' : 0;

        if(empty($request->slot_date) || $request->slot_date == 'null'){

            $slot = new VendorSlot();
            $slot->vendor_id    = $vendor->id;
            $slot->start_time   = $request->start_time;
            $slot->end_time     = $request->end_time;
            $slot->dine_in      = $dine_in;
            $slot->takeaway     = $takeaway;
            $slot->delivery     = $delivery;
            $slot->save();

            foreach ($request->week_day as $key => $value) {
                $slotData['slot_id']    = $slot->id;
                $slotData['day']        = $value;
                SlotDay::insert($slotData);  
            }
        }else{
            $slotData['vendor_id']          = $vendor->id;
            $slotData['start_time']         = $request->start_time;
            $slotData['end_time']           = $request->end_time;
            $slotData['specific_date']         = $request->slot_date;
            $slotData['dine_in']            = $dine_in;
            $slotData['takeaway']           = $takeaway;
            $slotData['delivery']           = $delivery;
            $slotData['working_today']      = 1;

            VendorSlotDate::insert($slotData);
        }
        return redirect()->back()->with('success', 'Slot saved successfully!');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorSlot  $vendorSlot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $vendor = Vendor::where('id', $id)->firstOrFail();
        $dine_in = in_array('dine_in', $request->slot_type) ? '1' : 0;
        $takeaway = in_array('takeaway', $request->slot_type) ? '1' : 0;
        $delivery = in_array('delivery', $request->slot_type) ? '1' : 0;

        if(empty($request->slot_date) || $request->slot_date == 'null'){

            $slot = new VendorSlot();
            $slot->vendor_id    = $vendor->id;
            $slot->start_time   = $request->start_time;
            $slot->end_time     = $request->end_time;
            $slot->dine_in      = $dine_in;
            $slot->takeaway     = $takeaway;
            $slot->delivery     = $delivery;
            $slot->save();

            $slotDay = SlotDay::where('id', $request->edit_type_id)->where('day', $request->edit_day)->delete();

            $sday = new SlotDay();
            $sday->slot_id =  $slot->id;
            $sday->day = $request->edit_day;
            $sday->save();

        }else{
            $dateSlot = VendorSlotDate::whereDate('specific_date', $request->slot_date)->first();
            if(!$dateSlot){
                $dateSlot = new VendorSlotDate();
            }
            $dateSlot->vendor_id        = $vendor->id;
            $dateSlot->start_time       = $request->start_time;
            $dateSlot->end_time         = $request->end_time;
            $dateSlot->specific_date    = $request->slot_date;
            $dateSlot->dine_in          = $dine_in;
            $dateSlot->takeaway         = $takeaway;
            $dateSlot->delivery         = $delivery;
            $dateSlot->working_today    = 1;
            $dateSlot->save();

        }
        return redirect()->back()->with('success', 'Slot saved successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorSlot  $vendorSlot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $domain = '', $id)
    {
        $vendor = Vendor::where('id', $id)->firstOrFail();

        if($request->slot_type == 'date'){
            $dateSlot = VendorSlotDate::where('id', $request->slot_id)->delete();
        } else {

            $slotDay = SlotDay::where('slot_id', $request->slot_id)->get();

            if($slotDay->count() == 1){
                $vendorSlot = VendorSlot::where('id', $request->slot_id)->delete();
            }
            $slot_day = SlotDay::where('id', $request->slot_day_id)->delete();
        }
        return redirect()->back()->with('success', 'Slot deleted successfully!');
    }

    public function returnJson(Request $request, $domain = '', $id)
    {
        $vendor = Vendor::findOrFail($id);
        $date = $day = array();

        if($request->has('start')){
            $start = explode('T', $request->start);
            $end = explode('T', $request->end);

            $startDate = date('Y-m-d', strtotime($start[0])); 
            $endDate = date('Y-m-d', strtotime($end[0]));

            $datetime1 = new \DateTime($startDate);
            $datetime2 = new \DateTime($endDate);

            $interval = $datetime2->diff($datetime1);
            $days = $interval->format('%a');

            $date[] = $startDate;
            $day[] = 1;

            for ($i = 1; $i < $days; $i++) {
                $date[] = date('Y-m-d', strtotime('+'.$i.' day', strtotime($startDate)));
                $day[] = $i + 1;
            }
        }else{
            $dayArray = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
            foreach ($dayArray as $key => $value) {
                $th = ($value == 'sunday') ? 'previous sunday' : $value.' this week';
                $date[] =  date( 'Y-m-d', strtotime($th));
                $day[] = $key + 1;
            }
        }

        $lst = count($date) - 1;
        $slot = VendorSlot::join('slot_days', 'slot_days.slot_id', 'vendor_slots.id')->where('vendor_id', $id)->orderBy('slot_days.day', 'asc')->get();
        
        $slotDate = VendorSlotDate::whereBetween('specific_date', [$date[0], $date[$lst]])->orderBy('specific_date','asc')->get();

        $showData = array();
        $count = 0;

        $client_preferences = ClientPreference::first();
        if($client_preferences){
            $dinein_check = $client_preferences->dinein_check;
            $takeaway_check = $client_preferences->takeaway_check;
            $delivery_check = $client_preferences->delivery_check;
        }

        foreach ($day as $key => $value) {
            $exist = 0;
            $title = $start = $end = $color = '';

            if($slotDate){
                foreach ($slotDate as $k => $v) {

                    if($date[$key] == $v->specific_date){
                        $exist = 1;
                        $title .= ($dinein_check == 1 && $v->dine_in == 1 && $vendor->dine_in == 1) ? ' Dine' : '';
                        $title .= ($takeaway_check == 1 && $v->takeaway == 1 && $vendor->takeaway == 1) ? ' Takeaway' : '';
                        $title .= ($delivery_check == 1 && $v->delivery == 1 && $vendor->delivery == 1) ? ' Delivery' : '';

                        $showData[$count]['title'] = trim($title);
                        $showData[$count]['start'] = $date[$key].'T'.$v->start_time;
                        $showData[$count]['end'] = $date[$key].'T'.$v->end_time;
                        $showData[$count]['color'] = ($v->working_today == 0) ? '#43bee1' : '';
                        $showData[$count]['type'] = 'date';
                        $showData[$count]['type_id'] = $v->id;
                        $showData[$count]['slot_id'] = $v->id;
                        $count++;
                    }
                }
            }

            if($exist == 0){

                foreach ($slot as $k => $v) {
                    if($value == $v->day){

                        $title .= ($dinein_check == 1 && $v->dine_in == 1 && $vendor->dine_in == 1) ? ' Dine' : '';
                        $title .= ($takeaway_check == 1 && $v->takeaway == 1 && $vendor->takeaway == 1) ? ' Takeaway' : '';
                        $title .= ($delivery_check == 1 && $v->delivery == 1 && $vendor->delivery == 1) ? ' Delivery' : '';

                        $showData[$count]['title'] = trim($title);
                        $showData[$count]['start'] = $date[$key].'T'.$v->start_time;
                        $showData[$count]['end'] = $date[$key].'T'.$v->end_time;
                        $showData[$count]['type'] = 'day';
                        $showData[$count]['color'] = ($v->working_today == 0) ? '#43bee1' : '';
                        $showData[$count]['type_id'] = $v->id;
                        $showData[$count]['slot_id'] = $v->slot_id;
                        $count++;
                    }
                }
            } 
        }
        echo $json  = json_encode($showData);
    }
}
