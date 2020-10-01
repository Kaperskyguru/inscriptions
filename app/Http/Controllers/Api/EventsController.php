<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Event;
use App\User;
use Auth;
use App\Http\Resources\UserResource;

class EventsController extends Controller
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

        $events = Event::whereDoesntHave("subscriptions", function($subQuery) use($organization_id){
            $subQuery->where("organization_id", "=", $organization_id);
          })
          ->where('event_type_id', config('EVENT_TYPE_ID'))
          ->get();

        if(!$events) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        return response()->json($events, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }
}
