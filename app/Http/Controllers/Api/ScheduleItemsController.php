<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ScheduleItem;
use Carbon\Carbon;

class ScheduleItemsController extends Controller
{
    //
    public function addToSchedule(Request $request) {
        $data = $request->toArray();
        $event = getEventInfos($data['event']);

        foreach($data['routines'] as $routine) {

            $schedule_item = ScheduleItem::firstOrNew([
                'routine_id' => $routine['id'], 
                'event_id' => $event['id'], 
                'organization_id' => $data['organization_id'],
            ]);

            if ($schedule_item->exists) {
                // already exists do nothing
            } else {
                $schedule_item->start_date = Carbon::now()->toDateTimeString();
                $schedule_item->end_date = Carbon::now()->toDateTimeString();
                if(!$schedule_item->save() ) {
                    return response()->json([
                        'status' => 'error',
                        'msg' => _('messages.global.error')
                    ], 400);
                }
            }
            
        }
        return response()->json([
            'status' => 'sucess',
            'msg' =>_('messages.global.success')
        ], 200);

    }
    function updateAllItems(Request $request) {
        $data = $request->toArray();
        foreach($data as $j => $item) {
            $schedule_item = ScheduleItem::find($item['id']);
            $schedule_item->start_date = $item['start_date'];
            $schedule_item->block = $item['block'];
            $schedule_item->position = $item['position'];
            if(!$schedule_item->save()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => _('messages.global.error')
                ], 400);
            }
        }
        return response()->json([
            'status' => 'sucess',
            'msg' => $data
        ], 200);
    }
}
