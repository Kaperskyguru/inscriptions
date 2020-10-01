<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\State;

class StatesController extends Controller
{
    //

    /**
     * Display the specified resource.
     *
     * @param  int  $country_id
     * @return \Illuminate\Http\Response
     */
    public function getByCountryID($country_id)
    {
        //

        $states = State::where('country_id', $country_id)->orderBy('code', 'ASC')->get();

        if(!$states) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        return response()->json($states, 200);
    }
}
