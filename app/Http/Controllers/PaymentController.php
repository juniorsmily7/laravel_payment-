<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StartButtonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            'amount' => 'required|integer', // Amount should be an integer (in cents)
        ]);

        // Hardcode the email
        $email = 'test@gmail.com';

        // Prepare the amount (already in cents)
        $amountInCents = $validated['amount'];

        // Call the StartButton service to initialize the transaction
        $response = $this->startButtonService->initializeTransaction(
            $amountInCents,
            $validated['currency'],
            $email, // Using the hardcoded email here
            'https://4212-196-221-162-245.ngrok-free.app/payment-response' // Redirect URL after payment
        );

        // Return the response data (which contains the payment URL for redirection)
        return response()->json($response);
    }
    public function initializeIELTS(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'currency' => 'required|string',
            'amount' => 'required|integer', // Amount should be an integer (in cents)
        ]);

        // Hardcode the email
        $email = 'test@gmail.com';

        // Prepare the amount (already in cents)
        $amountInCents = $validated['amount'];

        // Call the StartButton service to initialize the transaction
        $response = $this->startButtonService->initializeTransaction(
            $amountInCents,
            $validated['currency'],
            $email, // Using the hardcoded email here
            route('paidCode') // Redirect URL after payment
        );

        // Return the response data (which contains the payment URL for redirection)
        return response()->json($response);
    }
    // Handle the payment response (redirected here after the payment)
    public function paymentResponse(Request $request)
    {
        // Log query parameters (for debugging)
        logger()->info('Payment Response', [$request]);

        // Capture the payment status and transaction ID
        $paymentStatus = $request->query('status'); // e.g., 'success' or 'failed'
        $transactionId = $request->query('transaction_id'); // Transaction ID

        // Use the hardcoded email here again
        $userEmail = 'test@gmail.com';

        // Initialize an array to capture the full response data
        $responseData = $request->all(); // Capture all response data

        // Update the 'has_paid' column if payment is successful
        if ($paymentStatus === 'success') {
            // Find the user by email
            $user = User::where('email', $userEmail)->first();

            if ($user) {
                // Update the 'has_paid' column to true
                $user->update(['has_paid' => true]);
                // Log success
                logger()->info("User {$user->email} has been marked as paid.");
            } else {
                // Log error if user is not found
                logger()->error("User with email {$userEmail} not found.");
                return response()->json(['error' => 'User not found.'], 404);
            }
        }

        // Return the response data to a view for further processing
        return view('payment-response', compact('paymentStatus', 'transactionId', 'userEmail', 'responseData'));
    }
    public function handleWebhook(Request $request)
    {
        // Log the raw request for debugging
        Log::info('StartButton Webhook Received:', $request->all());

        // Verify the webhook signature
        if (!$this->verifyWebhookSignature($request)) {
            return response()->json(['error' => 'Invalid Signature'], 400);
        }

        // Parse the event from the request
        $event = $request->input('event');
        $data = $request->input('data');

        // Handle different events (e.g., collection.verified, collection.completed)
        if ($event == 'collection.verified') {
            $transactionData = $data['transaction'];

            // Example: Update user based on transaction data
            $userEmail = $transactionData['customerEmail'];
            $user = User::where('email', $userEmail)->first();

            if ($user) {
                $user->update(['has_paid' => true]);
            }

            // Log transaction details
            Log::info("User {$userEmail} payment verified", ['transaction' => $transactionData]);
        }

        // Return a 200 OK response to acknowledge receipt of the webhook
        return response()->json(['status' => 'success']);
    }

    // Verify the webhook signature
    private function verifyWebhookSignature(Request $request)
    {
        // Replace this with your StartButton Secret Key
        $secret = env('STARTBUTTON_SECRET_KEY');

        // Get the signature from the request headers
        $signature = $request->header('x-startbutton-signature');

        // Calculate the expected signature
        $calculatedSignature = hash_hmac('sha512', json_encode($request->all()), $secret);

        // Compare signatures
        return hash_equals($calculatedSignature, $signature);
    }

}
