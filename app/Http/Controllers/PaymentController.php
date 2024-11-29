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

    public function initialize(Request $request)
{
    $validated = $request->validate([
        'amount' => 'required|numeric|min:1',
        'currency' => 'required|string',
        'email' => 'required|email',
        'redirectUrl' => 'required|url', // Ensure redirect URL is validated
    ]);

    $response = $this->startButtonService->initializeTransaction(
        $validated['amount'],
        $validated['currency'],
        $validated['email'],
        $validated['redirectUrl'] // Pass the redirect URL
    );

    return response()->json($response);
}


public function paymentResponse(Request $request)
{
    // Log all query parameters for debugging
    logger()->info('Payment Response', $request->all());

    // Capture the response data
    $paymentStatus = $request->query('status'); // Example: 'success' or 'failed'
    $transactionId = $request->query('transaction_id'); // Example: Transaction ID
    $responseData = $request->all(); // Capture all query parameters or response body

    // Return the response to a view for further processing
    return view('payment-response', compact('paymentStatus', 'transactionId', 'responseData'));
}

    }
