<?php

namespace App\Http\Controllers;

use App\Services\StartButtonService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $startButtonService;

    public function __construct(StartButtonService $startButtonService)
    {
        $this->startButtonService = $startButtonService;
    }

    // Initialize the payment
    public function initialize(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'currency' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|integer', // Amount should be an integer (in cents)
        ]);

        // Prepare the amount (already in cents)
        $amountInCents = $validated['amount'];

        // Call the StartButton service to initialize the transaction
        $response = $this->startButtonService->initializeTransaction(
            $amountInCents,
            $validated['currency'],
            $validated['email'],
            route('payment.response')  // Redirect URL after payment
        );

        // Return the response data (which contains the payment URL for redirection)
        return response()->json($response);
    }

    // Handle the payment response (redirected here after the payment)
    public function paymentResponse(Request $request)
    {
        // Log query parameters (for debugging)
        logger()->info('Payment Response', $request->all());

        // Capture the payment status and transaction ID
        $paymentStatus = $request->query('status'); // e.g., 'success' or 'failed'
        $transactionId = $request->query('transaction_id'); // Transaction ID

        // Assuming the user's email or ID is passed in the query parameters
        $userEmail = "test@gmail.com";

        $responseData = $request->all(); // All query parameters or response body
        $user = \App\Models\User::where('email', $userEmail)->first();
            if ($user) {
                $user->update(['has_paid' => true]);
            }
        // Return the response data to a view for further processing
        return view('payment-response', compact('paymentStatus', 'transactionId', 'responseData'));
    }
}
