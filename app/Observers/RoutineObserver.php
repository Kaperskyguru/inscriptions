<?php

namespace App\Observers;
use \Carbon\Carbon;

use App\Routine;
use App\Subscription;
use App\User;
use Auth;


class RoutineObserver
{
    /**
     * Handle the routine "created" event.
     *
     * @param  \App\Routine  $routine
     * @return void
     */
    public function created(Routine $routine)
    {
        //
        if (!Auth::guard()->check()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notLoggedin')
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
        
        $subscription_id = $routine->subscription_id;
        $subscription = Subscription::where('id', $subscription_id)->first();

        if($user->roles->first()->name == 'admin' && $subscription->status_id != 1) {
            $subscription->status_id = 2;
            $subscription->save();
        }  

    }

    /**
     * Handle the routine "updated" event.
     *
     * @param  \App\Routine  $routine
     * @return void
     */
    public function updated(Routine $routine)
    {
        if (!Auth::guard()->check()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notLoggedin')
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
        
        $subscription_id = $routine->subscription_id;
        $subscription = Subscription::where('id', $subscription_id)->first();

        if($user->roles->first()->name == 'admin' && $subscription->status_id != 1) {
            $subscription->status_id = 2;
            $subscription->save();
        }  

    }

    /**
     * Handle the routine "deleted" event.
     *
     * @param  \App\Routine  $routine
     * @return void
     */
    public function deleted(Routine $routine)
    {
        if (!Auth::guard()->check()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notLoggedin')
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
        
        $subscription_id = $routine->subscription_id;
        $subscription = Subscription::where('id', $subscription_id)->first();

        if($user->roles->first()->name == 'admin' && $subscription->status_id != 1) {
            $subscription->status_id = 2;
            $subscription->save();
        }  

    }

    /**
     * Handle the routine "restored" event.
     *
     * @param  \App\Routine  $routine
     * @return void
     */
    public function restored(Routine $routine)
    {
        //
    }

    /**
     * Handle the routine "force deleted" event.
     *
     * @param  \App\Routine  $routine
     * @return void
     */
    public function forceDeleted(Routine $routine)
    {
        //
    }
}
