<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Dancer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Organization;
use App\Subscription;
use Carbon\Carbon;


class DancersController extends Controller
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
            return response()->json(__('messages.global.fail'), 400);
        }

        

        return response()->json($user->dancers, 200);
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
    public function search(Request $request)
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
                'msg' => __('messages.user.notFound')
            ], 400);
        }

        $data = $request->toArray();

        if($user->roles->first()->name == 'public') {
            $organization_id = $user->organization()->first()->id;
        }else {
            $organization_id = $data['organization_id'];
        }
        
        

        
        $dancers_filtered = [];
        foreach($data['dancers'] as $dancer) {
            array_push($dancers_filtered, $dancer['id']);
        }

        unset($data['dancers']);


        $dancers = Dancer::where([
                ['organization_id', $organization_id],
                ['first_name', 'like','%'.$data['query'].'%']
            ])->whereNotIn('id', $dancers_filtered)
            ->orWhere([
                ['organization_id', $organization_id],
                ['last_name', 'like','%'.$data['query'].'%']
            ])->whereNotIn('id', $dancers_filtered)
        
        ->orderBy('first_name', 'ASC')->get();

        if(count($dancers) === 0) {
            $dancers = [
                [
                    'id' => 0,
                    'name' => __('forms.label.noResult')
                ]
            ];
        }
        return response()->json($dancers, 200);
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
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
                'msg' => __('messages.user.notFound')
            ], 400);
        }   
        $data = $request->toArray();

        if($user->roles->first()->name == 'public') {
            $organization_id = $user->organization()->first()->id;
            $organization = Organization::find($organization_id);

            if(!$organization){
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.organization.notFound')
                ], 400);
            }
            $data['organization_id'] = $organization_id;

        }else {
           // $data['organization_id'] is set in the request
        }
        $data['first_name'] = titleCase($data['first_name']);
        $data['last_name'] = titleCase($data['last_name']);
        $dancer = Dancer::create($data);

        if(!$dancer) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success'),
            'dancer' => $dancer
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $v = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
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
        $data = $request->toArray();
        if($user->roles->first()->name == 'public') {
            $organization_id = $user->organization()->first()->id;
            $organization = Organization::find($organization_id);

            if(!$organization){
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.organization.notFound')
                ], 400);
            }
        }else {
            $organization_id = $data['organization_id'];
        }
        
        $data['first_name'] = titleCase($data['first_name']);
        $data['last_name'] = titleCase($data['last_name']);

        $id = $data['id'];
        unset($data['id']);


        $dancer = Dancer::where('organization_id', $organization_id)
            ->where('id', $id);

        

          if(!$dancer) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        
        if(!$dancer->update($data)) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success'),
            'dancer' => Dancer::find($id)
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
        
        //Dancer::destroy(1);
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
                'msg' => __('messages.user.notFound')
            ], 400);
        }
        if($user->roles->first()->name == 'public'){
            $organization_id = $user->organization()->first()->id;
            $organization = Organization::find($organization_id);

            if(!$organization){
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.organization.notFound')
                ], 400);
            }
        }
        
        $dancer = Dancer::with(['routines'])->where('id', $id)->first();

        if(!$dancer->delete()) {
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
    public function check()
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
            return response()->json(__('messages.global.fail'), 400);
        }
        
        if(count($user->dancers) == 0) {
            return response()->json(false, 200);
        }
        return response()->json(true, 200);
    }
}
