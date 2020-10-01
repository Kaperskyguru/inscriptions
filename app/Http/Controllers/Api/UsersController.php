<?php

namespace App\Http\Controllers\Api;

use App\User;
//use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Hash;
//use Faker\Factory as Faker;
use Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\UserResource
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\UserResource
     */
    public function store(Request $request)
    {
        

        // $user->push();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \App\Http\Resources\UserResource
    //  */

    // public function update(Request $request)
    // {
    //     $v = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|max:255',
    //     ]);
    //     if ($v->fails())
    //     {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $v->errors()
    //         ], 422);
    //     }

    //     if(!Auth::guard()->check()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'msg' => __('messages.user.notLoggedin')
    //         ], 401);
    //     }


    //     $user_id = Auth::guard()->user()->id;
    //     $user = User::find($user_id);
       
    //     if (!$user)
    //     {
    //         return response()->json([
    //             'status' => 'error',
    //             'msg' => __('messages.user.notFound')
    //         ], 400);
    //     }
    //     $data = $request->toArray();

       

    //     if(strlen($data['password']) > 0) {
    //         //
    //         if(!Hash::check($data['password'], $user->password)) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'msg' => __('messages.password.wrong')
    //             ], 400);
    //         }
    //         $v = Validator::make($request->all(), [
    //             'password' => 'required|string|min:6',
    //             'new_password' => 'required|confirmed|string|min:6',
    //         ]);
    //         if ($v->fails())
    //         {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'errors' => $v->errors()
    //             ], 422);
    //         }
    //         $data['password'] = Hash::make($data['new_password']);
           
    //     }
    //     if(!$user->update($data)) {
    //         return response()->json([
    //             'status' => 'error',
    //             'msg' => __('messages.global.fail')
    //         ], 400);
    //     }
        
    //     return response()->json([
    //         'status' => 'sucess',
    //         'msg' => __('messages.global.success'),
    //         'user' => User::find($user_id)
    //     ], 200);
    //     // $data = $request->validate([
    //     //     'name' => 'required',
    //     //     'email' => 'required|email',
    //     // ]);

    //     // $user->update($data);

    //     // return new UserResource($user);
    // }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(null, 204);
    }
}
