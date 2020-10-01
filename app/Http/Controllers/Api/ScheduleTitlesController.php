<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ScheduleTitle;
use App\ScheduleItem;

class ScheduleTitlesController extends Controller
{
    //
    public function update(Request $request, $id)
    {
        
        $data = $request->toArray();

        $scheduleTitle = ScheduleTitle::find($id);

        $scheduleTitle->name = $data['name'];

        $scheduleTitle->save();
        

        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success')
        ], 200);
    }
    public function delete($id)
    {
        
        $scheduleTitle = ScheduleTitle::with('ScheduleItems')->where('id', $id)->first();
        
        if( $scheduleTitle) {
            $data = $scheduleTitle->toArray();
            if(count($data['schedule_items']) > 0) {
                foreach($data['schedule_items'] as $j => $item) {
                    $schedule_item = ScheduleItem::find($item['id']);
                    $schedule_item->schedule_title_id = null;
                    //$schedule_item->position = null;
                    $schedule_item->order = $j;
                    if(!$schedule_item->save()) {
                        return response()->json([
                            'status' => 'error',
                            'msg' => _('messages.global.error')
                        ], 400);
                    }
                }
            }
            if(!$scheduleTitle->delete()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => _('messages.global.error')
                ], 400);
            }
            
        }
       
        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success')
        ], 200);
    }
}
