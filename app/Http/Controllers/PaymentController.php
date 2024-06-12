<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Initiate payments for all employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function initiatePayment()
    {
       // $admin = Auth::user();  //returns the authenticated user thus used when you needs the auth user, while auth('sanctum')->check() returns the auth status
        
        if(!(auth('sanctum')->check())){
            return response()->json(['message' => 'You do not have the necessary permissions to initiate a payment'], 403);
        }

        $admin = auth('sanctum')->user();
        $currentMonth = Carbon::now()->month;
        
        $unpaidPayments = Payment::where('is_effected', false)->exists();
            logger('unpaid payments', [$unpaidPayments]);
        if ($unpaidPayments) {
        return response()->json(['status' => false, 'message' => 'Cannot initiate a new payment. There are existing payments that have not been made.'], 400);
         } elseif ((Payment::whereMonth('payslip_issue_date', $currentMonth)->exists())) {
            return response()->json(['status' => false, 'message' => 'Payment for this month has already been initiated.Cannot initiate a new payment.'], 400);
         }
         else{
            $this->paymentService->initiatePayment($admin);
            return response()->json(['message' => 'Payment initiated sucessfully'], 200);
         }
        
    }

    /**
     * Process the payment.
     *
     * @param \App\Models\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function makePayment()
    {
        $admin = auth('sanctum')->user();   

         if(!$admin){
            return response()->json(['message' => 'You do not have the necessary permissions to initiate a payment'], 403);
        }

        $payment = Payment::where('is_effected', false)->first();
         // Check if a payment that is not affected exists
        if (!$payment) {
             return response()->json(['message' => 'No pending payments to be processed'], 400);
         }

        // Process the payment
        $this->paymentService->makePayment($payment, $admin);

       // return response()->json(['message' => 'Payment processed successfully', 'payment' => new PaymentResource($payment->fresh())]);
        return response()->json(['message' => 'Payment processed successfully'], 200);
    }
}
