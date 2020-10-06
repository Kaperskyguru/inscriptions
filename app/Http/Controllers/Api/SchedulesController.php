<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\ScheduleTitle;
use App\ScheduleItem;
use App\Organization;
use App\Event;
use App\Exports\ScheduleExport;
use App\Exports\ScheduleItemExport;
use App\Exports\ScheduleByOrganizationExport;
use App\Exports\ScheduleOrderPositionExport;
use Illuminate\Http\Request;
use Excel;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class SchedulesController extends Controller
{

    public function exportSchedule($event_slug) {
        $event = getEventInfos($event_slug);
        $id = $event['id'];
        $city = $event['city'];

        $data = ScheduleTitle::with([
            'scheduleItems' => function($query) {
                $query->orderBy(DB::raw('ISNULL(position), position'), 'ASC');
            },
            'scheduleItems.routine.dancers',
            'scheduleItems.routine.classification',
            'scheduleItems.organization',
            'scheduleItems.routine.category',
            'scheduleItems.routine.style',
            'scheduleItems.routine.level',
            // 'scheduleItems.routine.dancersCount',
        ])
        ->orderBy('order', 'ASC')
        ->whereHas('scheduleItems')
        ->where('schedule_id', $id)
        ->get();

        foreach($data as $k => $schedule) {

            foreach($schedule->scheduleItems as $j => $item) {

                $dancers_age = [];
                foreach ($item->routine->dancers as $dancer) {
                    $dancers_age[] = $dancer['age'];
                }
                if(count($dancers_age)) {
                    $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                }else {
                    $dancerAverageAge = 0;
                }

                $data[$k]['scheduleItems'][$j]['routine']['average'] = $dancerAverageAge;
                
            }
        }
        
       
        return Excel::download(new ScheduleExport($data), 'Base_de_donnees_'. $city . '_2020.xlsx');
    }
    public function exportScheduleItem($event_slug) {
        
        $event = getEventInfos($event_slug);
        $id = $event['id'];
        $city = $event['city'];
        
        $data = ScheduleTitle::with([
            'scheduleItems' => function($query) {
                $query->orderBy(DB::raw('ISNULL(position), position'), 'ASC');
            },
            'scheduleItems.routine.dancers',
            'scheduleItems.routine.classification',
            'scheduleItems.organization',
            'scheduleItems.routine.category',
            'scheduleItems.routine.style',
            'scheduleItems.routine.level',
            // 'scheduleItems.routine.dancersCount',
        ])
        ->orderBy(DB::raw('ISNULL(position), position'), 'ASC')
        ->whereHas('scheduleItems')
        ->where('schedule_id', $id)
        ->get();

        foreach($data as $k => $schedule) {
            foreach($schedule->scheduleItems as $j => $item) {
                $dancers_age = [];
                foreach ($item->routine->dancers as $dancer) {
                    $dancers_age[] = $dancer['age'];
                }
                if(count($dancers_age)) {
                    $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                }else {
                    $dancerAverageAge = 0;
                }

                $data[$k]['scheduleItems'][$j]['routine']['average'] = $dancerAverageAge;
                
            }
        }

       

        return Excel::download(new ScheduleItemExport($data), 'Base_de_donnees_Gatineau_2020.xlsx');
    }
    //
    public function update(Request $request, $id)
    {
       
        $data = $request->toArray();
       
       
        $event = Event::getEventInfos($id);
        foreach($data['elements'] as $key => $title) {
            if($key != 0) {
                $schedule_title = ScheduleTitle::firstOrCreate(['id' => $title['id'], 'schedule_id' => $event['id'], 'order' => $data['elements'][$key]['order']]);

                $schedule_title->name = $title['name'];

                if($data['saveOrder']) {
                    $schedule_title->order = $key;
                }

                
                $schedule_title->position = $title['schedule_items'][0]['position'];

                if(!$schedule_title->save()) {
                    return response()->json([
                        'status' => 'error',
                        'msg' => _('messages.global.error')
                    ], 400);
                }
                foreach($title['schedule_items'] as $j => $item) {
                    $schedule_item = ScheduleItem::find($item['id']);

                    $schedule_item->schedule_title_id = $schedule_title->id;
                    $schedule_item->order = $j;
                    $schedule_item->position = $item['position'];
                    
                    if(!$schedule_item->save()) {
                        return response()->json([
                            'status' => 'error',
                            'msg' => _('messages.global.error')
                        ], 400);
                    }
                }
            }else {
                foreach($data['elements'][0]['schedule_items'] as $j => $item) {
                    $schedule_item = ScheduleItem::find($item['id']);
                    $schedule_item->schedule_title_id = null;
                    $schedule_item->order = $j;
                    //$schedule_item->position = null;
                    if(!$schedule_item->save()) {
                        return response()->json([
                            'status' => 'error',
                            'msg' => _('messages.global.error')
                        ], 400);
                    }
                }
            }
        }
        
       
        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success')
        ], 200);
        

    }

    
    function exportScheduleOrganizations($event){
        $event = $this->getEventInfos($event);
        $city = $event['city'];
        $event_id = $event['id'];
        $organizations = Organization::orderBy('name')->whereHas('subscriptions', function ($query) use ($event_id) { 
            $query->where('subscriptions.event_id', $event_id)
            ->whereIn('subscriptions.status_id', [2,3,4]);
        })
        ->with([
            'subscriptions' => function($query) use ($event_id) {
                $query->where('subscriptions.event_id', $event_id);
            },
        ])
        ->get();
        Storage::deleteDirectory('tmp_exports');

        

        foreach($organizations as $organization) {
          

            $subscription_id = $organization->subscriptions[0]->id;
           
            $data = ScheduleItem::with(['scheduleTitle', 
            'routine',
            'routine.dancers',
            'routine.category',
            'routine.level',
            'routine.style',
            'routine.classification',
            'organization'
            ])
            ->where('organization_id', $organization->id)
            ->where('event_id', 1)
            ->whereNotNull('position')
            ->orderBy(DB::raw('ISNULL(position), position'), 'ASC')
            ->get();
            
           

            foreach($data as $k => $item) {
                $dancers_age = [];
                if(isset($item->routine)) {
                   
                    foreach ($item->routine->dancers as $dancer) {
                        $dancers_age[] = $dancer['age'];
                    }
                    if(count($dancers_age)) {
                        $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                    }else {
                        $dancerAverageAge = 0;
                    }

                    $data[$k]['routine']['average'] = $dancerAverageAge;
                }
                
            }
           $filename = 'Horaire_' . str_replace(' ', '_', Str::slug($organization->name, '_')) .  '_' . Carbon::now();
           Excel::store(new ScheduleByOrganizationExport($data), 'tmp_exports/' . $filename . '.xlsx');
        }
        return 'download';
    }

    function exportScheduleOrderPosition($event) {
        $event = $this->getEventInfos($event);
        $city = $event['city'];
        $event_id = $event['id'];
        $data = ScheduleTitle::with([
            'scheduleItems' => function($query) {
                $query->orderBy(DB::raw('ISNULL(position), position'), 'ASC');
            },
            'scheduleItems.routine.dancers',
            'scheduleItems.routine.classification',
            'scheduleItems.organization',
            'scheduleItems.routine.category',
            'scheduleItems.routine.style',
            'scheduleItems.routine.level',
            // 'scheduleItems.routine.dancersCount',
        ])
        ->orderBy(DB::raw('ISNULL(position), position'), 'ASC')
        ->whereHas('scheduleItems')
        ->where('schedule_id', $event_id)
        ->get();

        foreach($data as $k => $schedule) {
            foreach($schedule->scheduleItems as $j => $item) {
                $dancers_age = [];
                foreach ($item->routine->dancers as $dancer) {
                    $dancers_age[] = $dancer['age'];
                }
                if(count($dancers_age)) {
                    $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                }else {
                    $dancerAverageAge = 0;
                }

                $data[$k]['scheduleItems'][$j]['routine']['average'] = $dancerAverageAge;
                
            }
        }
        
        $filename = 'Ordre_de_Passage_' . $city . '_' . Carbon::now();

        return Excel::download(new ScheduleOrderPositionExport($data), $filename . '.xlsx');
    }

    function exportScheduleByOrganization($subscription_id, $organization_id){


        $data = ScheduleItem::with(['scheduleTitle', 
        'routine' => function($query) use($subscription_id) {
            $query->where('subscription_id', $subscription_id);
        }, 
        'routine.category',
        'routine.level',
        'routine.style',
        'organization'
        ])
        ->orderBy(DB::raw('ISNULL(position), position'), 'ASC')
        ->where('organization_id', $organization_id)
        ->get();

    
        foreach($data as $k => $item) {
                $dancers_age = [];
                foreach ($item->routine->dancers as $dancer) {
                    $dancers_age[] = $dancer['age'];
                }
                if(count($dancers_age)) {
                    $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
                }else {
                    $dancerAverageAge = 0;
                }

                $data[$k]['routine']['average'] = $dancerAverageAge;
        }
        $filename = 'Horaire_' . str_replace(' ', '_', Str::slug($data[0]->organization->name), '_') .  '_' . Carbon::now();
        
        // Excel::create('Filename', function($excel) {

        //     // Set sheets
        
        // })->store('xls');
        #Normal export to cu
        //Storage::makeDirectory($directory);
        //Storage::deleteDirectory('tmp_exports');
       // Excel::store(new ScheduleByOrganizationExport($data), 'tmp_exports/' . $filename . '.xlsx');

        return Excel::download(new ScheduleByOrganizationExport($data), $filename . '.xlsx');
    }
    private function getEventInfos($eventSlug) {
            
            $events = [
                'gatineau' => [
                    'city' => 'Gatineau',
                    'id' => 1
                ],
                'toronto' => [
                    'city' => 'Toronto',
                    'id' => 2
                ],
                'levis' => [
                    'city' => 'Lévis',
                    'id' => 3
                ],
                'flofest' => [
                    'city' => 'FLOFEST',
                    'id' => 4
                ],
                'saintehyacinthe' => [
                    'city' => 'Saintehyacinthe',
                    'id' => 5
                ]
            ];
            return $events[$eventSlug];

        }
   
}
