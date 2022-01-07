<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorPayController extends Controller
{
    public function index()
    {
        return view('razorpay');
    }


    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $input = $request->all();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
            } catch (\Exception $e) {
                return back()->with('error',$e->getMessage());
            }
        }

        return back()->with('success','Payment successful');
    }
}
