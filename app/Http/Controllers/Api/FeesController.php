<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Fee;
class FeesController extends Controller
{
    //
    public function store(Request $request) {

        $data = $request->toArray();

        $fee = Fee::create($data);
        
        if(!$fee) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail'),
    
            ], 400);
        }

        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success'),
        ], 200);

    }
    public function delete($id) {

        // $subscription_id = $payment->subscription_id;
        $fee = Fee::find($id);

        if(!$fee->delete()) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }

        // $subscription = Subscription::find($subscription_id);

        // if (!$subscription) {
        //     return response()->json([
        //         'status' => 'error',
        //         'msg' => __('messages.global.fail')
        //     ], 400);
        // }

        // if($subscription->balance <= 0) {
        //     $subscription->status_id = 4;
        // }else {
        //     $subscription->status_id = 3;
        // }
        // if (!$subscription->save()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'msg' => __('messages.global.fail')
        //     ], 400);
        // }
        return response()->json([
            'status' => 'sucess',
            'msg' => __('messages.global.success'),
        ], 200);
    }
    public function update(Request $request)
    {
        // $v = Validator::make($request->all(), [
        //     'payment_type_id' => 'required',
        //     'receive_on' => 'required',
        //     'amount' => 'required|string'
        // ]);
        // if ($v->fails())
        // {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => $v->errors()
        //     ], 422);
        // }
        $data = $request->toArray();

        // $data['amount'] = number_format((float)$data['amount']*100., 0, '.', '');

        $fee = Fee::find($data['id']);
        
        if(!$fee->update($data)) {
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
