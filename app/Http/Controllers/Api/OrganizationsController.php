<?php

namespace App\Http\Controllers\Api;

use App\Organization;
use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

//use App\Http\Resources\UserResource;

class OrganizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $organization = Organization::all()->first()->subscription;
        if(!$organization) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        return response()->json($organization, 200);
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
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'phone' => 'required',
            'locale' => 'required',
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
            if(!$user->organization()->update($data)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.global.fail')
                ], 400);
            }
        }else {
            $organization = Organization::find($data['id']);

            if(!$organization->update($data)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.global.fail')
                ], 400);
            }
        }
        
        

        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success')
        ], 200);
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
