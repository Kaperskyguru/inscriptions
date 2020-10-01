<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Style;
use App\Level;
use App\User;
use App\Classification;
use App\Subscription;
use App\Organization;
use App\Routine;
use App\ScheduleItem;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;



class RoutinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
        $styles = Style::where('event_type_id', config('EVENT_TYPE_ID'))->get();
        $levels = Level::where('event_type_id', config('EVENT_TYPE_ID'))->get();

        return response()->json(compact('styles', 'levels'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'teacher' => 'required|string|max:255',
            'style_id' => 'required|int',
            'level_id' => 'required|int',
            'dancers' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail'),
                'errors' => $v->errors()
            ], 422);
        }
        if (!Auth::guard()->check()) {
            return response()->json([
                'Token Expired'
            ], 401);
        }


        $user_id = Auth::guard()->user()->id;
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notFound')
            ], 400);
        }
        $data = $request->toArray();

        if($user->roles->first()->name == 'public') {
            $organization_id = $user->organization()->first()->id;
            $organization = Organization::find($organization_id)->where('user_id', $user_id);

            if (!$organization) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.organization.notFound')
                ], 400);
            }
            

            $data['organization_id'] = $organization_id;
            $subscription = Subscription::where([
                'id' => $data['subscription_id'],
                'organization_id' => $organization_id,
            ])->first();
    
            if (!$subscription) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.subscription.notFound')
                ], 400);
            }
        }
        
        $dancers = $data['dancers'];
        $whitelist = ['id'];
        $dancers_filtered = [];
        $dancers_age = [];
        foreach ($dancers as $key => $dancer) {
           
            if (count($dancers) === 1 && (int) $data['level_id'] === 1 && (int) $dancer['age'] > 15 && config('EVENT_TYPE_ID') == 1) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.routine.soloNovice'),
                ], 400);
            }
            $dancers_filtered[] = array_intersect_key($dancer, array_flip($whitelist));
            $dancers_filtered[$key]['dancer_id'] =  $dancers_filtered[$key]['id'];
            unset($dancers_filtered[$key]['id']);

            $dancers_age[] = $dancer['age'];
        }

        unset($data['dancers']);

        $total_dancer = count($dancers_filtered);

        if(count($dancers_age)) {
            $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
        }else {
            $dancerAverageAge = 0;
        }

        if ((int) $data['level_id'] === 4 && (int) $dancerAverageAge < 13) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.routine.elite'),
            ], 400);
        }

        if(count($dancers_age)) {
            $classification = Classification::where('event_type_id', config('EVENT_TYPE_ID'))->where('minage', '<=', $dancerAverageAge)->where('maxage', '>=', $dancerAverageAge)->first();

            if (!$classification) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.global.fail')
                ], 400);
            }
            $data['classification_id'] = $classification->id;

        }else {
            $data['classification_id'] = 7;
        }


        // The category is define with the total of dancers
        if(config('EVENT_TYPE_ID') == 2) {

            if ($total_dancer >= 16) {
                $data['category_id'] = 11; // Production
            } else if ($total_dancer <= 15 && $total_dancer >= 10) {
                $data['category_id'] = 10; // Grand Group
            } else if ($total_dancer <= 9 && $total_dancer >= 4) {
                $data['category_id'] = 9; // Petit Group
            } else if($total_dancer == 3){
                $data['category_id'] = 12; // Duo/Trio
            }else if($total_dancer == 2){
                $data['category_id'] = 12; // Duo/Trio
            }else if($total_dancer == 1){
                $data['category_id'] = 8; // Solo
            }else {
                $data['category_id'] = 7; // Unclassified
            }
        }else {
            if ($total_dancer >= 16) {
                $data['category_id'] = 6; // Production
            } else if ($total_dancer <= 15 && $total_dancer >= 10) {
                $data['category_id'] = 5; // Grand Group
            } else if ($total_dancer <= 9 && $total_dancer >= 4) {
                $data['category_id'] = 4; // Petit Group
            } else if ($total_dancer == 3) {
                $data['category_id'] = 2; // Duo/Trio
            } else if ($total_dancer == 2) {
                $data['category_id'] = 2; // Duo/Trio
            } else if($total_dancer == 1){
                $data['category_id'] = 1; // Solo
            }else {
                $data['category_id'] = 7; // Unclassified
            }
        }
        

        $routine = Routine::create($data);

        if (!$routine) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        $routine->dancers()->attach($dancers_filtered);

        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::guard()->check()) {
            return response()->json([
                'Token Expired'
            ], 401);
        }


        $user_id = Auth::guard()->user()->id;
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notFound')
            ], 400);
        }

        if($user->roles->first()->name == 'public') {
            $organization_id = $user->organization()->first()->id;
            $organization = Organization::find($organization_id)->where('user_id', $user_id)->first();

            if (!$organization) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.organization.notFound')
                ], 400);
            }

            $conditions = [
                'id' => $id,
                'organization_id' => $organization_id,
            ];
        }else {
            $conditions = [
                'id' => $id,
            ];
        }
        
       
        $routine = Routine::with('Dancers')->where($conditions)->first();
        //
        if (!$routine) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        $styles = Style::where('event_type_id', config('EVENT_TYPE_ID'))->get();
        $levels = Level::where('event_type_id', config('EVENT_TYPE_ID'))->get();


        return response()->json(compact('routine', 'styles', 'levels'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'teacher' => 'required|string|max:255',
            'style_id' => 'required|int',
            'level_id' => 'required|int',
            'dancers' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail'),
                'errors' => $v->errors()
            ], 422);
        }
        if (!Auth::guard()->check()) {
            return response()->json([
                'Token Expired'
            ], 401);
        }


        $user_id = Auth::guard()->user()->id;
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notFound')
            ], 400);
        }
        $data = $request->toArray();

        if($user->roles->first()->name == 'public') {
            $organization_id = $user->organization()->first()->id;
        $organization = Organization::find($organization_id)->where('user_id', $user_id);

        if (!$organization) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.organization.notFound')
            ], 400);
        }

        $data['organization_id'] = $organization_id;

        $subscription = Subscription::where([
            'id' => $data['subscription_id'],
            'organization_id' => $organization_id,
        ])->first();

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.subscription.notFound')
            ], 400);
        }
        }
        

        $dancers = $data['dancers'];



        $whitelist = ['id'];
        $dancers_filtered = [];
        $dancers_age = [];

        foreach ($dancers as $key => $dancer) {

            if (count($dancers) === 1 && (int) $data['level_id'] === 1 && (int) $dancer['age'] > 15 && config('EVENT_TYPE_ID') == 1) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.routine.soloNovice'),
                ], 400);
            }
            $dancers_filtered[] = array_intersect_key($dancer, array_flip($whitelist));
            $dancers_filtered[$key]['dancer_id'] =  $dancers_filtered[$key]['id'];
            unset($dancers_filtered[$key]['id']);

            $dancers_age[] = $dancer['age'];
        }


        $total_dancer = count($dancers_filtered);

        //$classification = Classification::where('minage', '<=', $dancerAverageAge)->where('maxage', '>=',$dancerAverageAge)->first();

        if(count($dancers_age)) {
            $dancerAverageAge = round(array_sum($dancers_age) / count($dancers_age));
        }else {
            $dancerAverageAge = 0;
        }
        

        if ((int) $data['level_id'] === 4 && (int) $dancerAverageAge < 13) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.routine.elite'),
            ], 400);
        }

        if(count($dancers_age)) {
            $classification = Classification::where('event_type_id', config('EVENT_TYPE_ID'))->where('minage', '<=', $dancerAverageAge)->where('maxage', '>=', $dancerAverageAge)->first();

            if (!$classification) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.global.fail')
                ], 400);
            }
            $data['classification_id'] = $classification->id;

        }else {
            $data['classification_id'] = 7;
        }

        

        // The category is define with the total of dancers

        if(config('EVENT_TYPE_ID') == 2) {

            if ($total_dancer >= 16) {
                $data['category_id'] = 11; // Production
            } else if ($total_dancer <= 15 && $total_dancer >= 10) {
                $data['category_id'] = 10; // Grand Group
            } else if ($total_dancer <= 9 && $total_dancer >= 4) {
                $data['category_id'] = 9; // Petit Group
            } else if($total_dancer == 3){
                $data['category_id'] = 12; // Duo/Trio
            }else if($total_dancer == 2){
                $data['category_id'] = 12; // Duo/Trio
            }else if($total_dancer == 1){
                $data['category_id'] = 8; // Solo
            }else {
                $data['category_id'] = 7; // Unclassified
            }
        }else {
            if ($total_dancer >= 16) {
                $data['category_id'] = 6; // Production
            } else if ($total_dancer <= 15 && $total_dancer >= 10) {
                $data['category_id'] = 5; // Grand Group
            } else if ($total_dancer <= 9 && $total_dancer >= 4) {
                $data['category_id'] = 4; // Petit Group
            } else if ($total_dancer == 3) {
                $data['category_id'] = 2; // Duo/Trio
            } else if ($total_dancer == 2) {
                $data['category_id'] = 2; // Duo/Trio
            } else if($total_dancer == 1){
                $data['category_id'] = 1; // Solo
            }else {
                $data['category_id'] = 7; // Unclassified
            }
        }

        unset($data['dancers']);


        $routine = Routine::find($id);

        if (!$routine) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        
        $resetSchedule = false;

        if($data['classification_id'] != $routine->classification_id) {
            $resetSchedule = true;
        }
        if($data['category_id'] != $routine->category_id) {
            $resetSchedule = true;
        }
        if($data['level_id'] != $routine->level_id) {
            $resetSchedule = true;
        }
        if($data['style_id'] != $routine->style_id) {
            $resetSchedule = true;
        }


        $scheduleItem = ScheduleItem::where('routine_id', $routine->id)->first();
        if( $scheduleItem && $resetSchedule) {
            $scheduleItem->schedule_title_id = null;
            $scheduleItem->save();
        }
            

        $routine->dancers()->detach();
        $routine->dancers()->attach($dancers_filtered);
        $routine->update($data);

        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success'),
            'routine' => $routine

        ], 200);
    }
    public function uploadSong(Request $request) {

        $v = Validator::make($request->all(), [
            'song' => 'required'
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail'),
                'errors' => $v->errors()
            ], 422);
        }

        $data = $request->toArray();
        $routine_id = $data['routine_id'];
        $routine = Routine::whereHas('scheduleItem')->with(['scheduleItem.event', 'scheduleItem.organization'])->where('id', $routine_id)->first();

    

        if (!$routine) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.routine.unqualified')
            ], 400);
        }



        if( $request->hasFile('song')) {
            $file = $request->file('song');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->extension($originalFilename);
            $disk = $routine->scheduleItem->event->slug;
            $path = '/';
            $filename = $routine->scheduleItem->position .  '_' . $routine->scheduleItem->organization->accronyme . '_' . str_replace(' ', '_', $routine->name) . '.' . $extension;

            if (Storage::disk($disk)->exists($filename)) {
                Storage::disk($disk)->delete($filename);
            }

            if(Storage::disk($disk)->putFileAs($path,  $file, $filename)) {
                $routine->music = $filename;
                if (!$routine->save()) {
                    return response()->json([
                        'status' => 'error',
                        'msg' => __('messages.global.fail')
                    ], 400);
                }
    
                return response()->json([
                    'status' => 'sucess',
                    'msg' => __('messages.global.success'),
        
                ], 200);
            }
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $routine = Routine::find($id);

        if(count($routine->dancers) > 0) {
            if (!$routine->dancers()->detach()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.global.fail')
                ], 400);
            }
        }
       
        if (!$routine->delete()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success'),
        ], 200);
    }

    public function getSubscriptionRoutines($id) {
        $routines = Routine::where('subscription_id', $id)->get();

        return response()->json($routines, 200);
    }
}
