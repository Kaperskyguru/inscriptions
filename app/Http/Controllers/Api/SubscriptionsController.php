<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subscription;
use Auth;
use App\User;
use App\Organization;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SubscriptionStatusChange;
use App\Exports\SubscriptionDancerExport;
use Excel;


class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::guard()->check()) {
            return response()->json([
                'Token Expired'
            ], 401);
        }

        $user_id = Auth::guard()->user()->id;
        $user = User::find($user_id);
       
        if (!$user)
        {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notFound') . $user_id
            ], 400);
        }

        $userResource = new UserResource($user);
        $organization_id = $userResource->organization->id;
        $subscriptions = Subscription::where('organization_id', $organization_id)
        ->whereHas('event', function($query) {
            $query->where('event_type_id', config('EVENT_TYPE_ID'));
        })
        ->with(['event', 'status', 'routines.dancers', 'routines.category', 'routines.style' , 'routines.level'])->withCount('routines')->get();
        
        if(!$subscriptions) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        return response()->json($subscriptions, 200);
    }
    public function all()
    {
        if(!Auth::guard()->check()) {
            return response()->json([
                'Token Expired'
            ], 401);
        }

        $user_id = Auth::guard()->user()->id;
        $user = User::find($user_id);
       
        if (!$user)
        {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notFound') . $user_id
            ], 400);
        }

        $userResource = new UserResource($user);
        $organization_id = $userResource->organization->id;
        $subscriptions = Subscription::where('organization_id', $organization_id)
        ->with(['event', 'status', 'routines.dancers'])->withCount('routines')->get();
        
        if(!$subscriptions) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        return response()->json($subscriptions, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
        //return $user;
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
        if(!Auth::guard()->check()) {
            return response()->json([
                'Token Expired'
            ], 401);
        }
        $user_id = Auth::guard()->user()->id;

        $user = User::find($user_id);
        if (!$user)
        {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notFound') . $user_id
            ], 400);
        }

        $userResource = new UserResource($user);
        $organization_id = $userResource->organization->id;

        $data = $request->toArray();
        $data['organization_id'] = $organization_id;

        $subscription = Subscription::create($data);
        
        if(!$subscription) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail'),
    
            ], 400);
        }
        $subscription = $subscription->where('id', $subscription->id)->with(['event', 'status'])->first();
        
        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success'),
            'subscription' => $subscription
        ], 200);

        //return response()->json(, 200);

        //return response()->json($data, 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        if(!Auth::guard()->check()) {
            return response()->json([
                'Token Expired'
            ], 401);
        }
        $user_id = Auth::guard()->user()->id;

        $user = User::find($user_id);

        if (!$user)
        {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notFound') . $user_id
            ], 400);
        }

        $userResource = new UserResource($user);
        $organization_id = $userResource->organization->id;

        $conditions = [
            'id' => $id,
            'organization_id' => $organization_id,
        ];

        $subscription = Subscription::where($conditions)->with(['event', 'status', 'organization', 'routines.category', 'routines.level', 'routines.style', 'routines.dancers'])->withCount('routines')->first();

        if (!$subscription)
        {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success'),
            'subscription' => $subscription
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        
        //
        

        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        $data = $request->toArray();

        if((int)$data['status_id'] == 2) {
            $v = Validator::make($request->all(), [
                'consent_video' =>'accepted',
                'consent_rules' => 'accepted',
            ]);
            if ($v->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.global.fail'),
                    'errors' => $v->errors()
                ], 422);
            }

            $mailData = [
                'name' => $subscription->organization->name,
                'type' => $subscription->organization->organizationType->name
            ];
            
            $subscription->event->notify(new SubscriptionStatusChange( $mailData));
        }

        if (!$subscription->update($data)) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

       
       

        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success'),
            'subscription' => $subscription
        ], 200);
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
        $subscription = Subscription::find($id);
       
        if (!$subscription->delete()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success'),
        ], 200);
    }


    public function exportSubscriptionDancer($event) {
        $event = getEventInfos($event);
        $id = $event['id'];

        $organizations = Organization::orderBy('name')->whereHas('subscriptions', function ($query) use ($id) { 
            $query->where(['subscriptions.event_id' => $id])->whereIn('subscriptions.status_id', [3,4]); 
        })
        
        ->with([
            'scheduleItems' => function($query) use($id) {
                $query->where(['event_id' => $id]); 
            },
            'scheduleItems.routine',
            'scheduleItems.routine.dancers',
        ])

 
        ->orderBy('name', 'ASC')
        ->get();

        //$organizations = $organizations->toArray();
        
        foreach($organizations as $key => $organization) {
            $dancers = [];
            $teachers = [];
            foreach ($organization->scheduleItems as $item) {
                $data = $item->routine->teacher;
                $teachers[] = $data;
                foreach ($item->routine->dancers as $subitem) {
                    $data = $subitem->toArray();
                    unset($data['pivot']);
                    $dancers[] = $data;
                }
                //$unique = array_keys(array_flip($dancers));
                $organizations[$key]['dancers'] = array_unique($dancers, SORT_REGULAR);

            }
            $organizations[$key]['teachers'] = array_unique($teachers, SORT_REGULAR);
        }
        
        

       

        $data = [];
    

        $data['organizations'] = $organizations;
        $data['city'] = strtoupper($event['city']);
        $title = 'HTF_Dancers_' . $event['city'] . '_' . date('Y');


    
        return Excel::download(new SubscriptionDancerExport($data), $title. '.xlsx');
    }
}
