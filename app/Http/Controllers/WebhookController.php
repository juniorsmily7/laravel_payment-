<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Log the incoming webhook payload
        Log::info('Received Webhook', ['payload' => $request->all()]);

        // Verify the signature
        $signature = $request->header('x-startbutton-signature');
        $secret = env('STARTBUTTON_SECRET_KEY');
        $computedHash = hash_hmac('sha512', $request->getContent(), $secret);

        if ($signature !== $computedHash) {
            Log::warning('Invalid Webhook Signature');
            return response('Invalid signature', 400);
        }

        // Process the event
        $event = $request->input('event');
        $data = $request->input('data.transaction');

        if ($event === 'collection.verified' || $event === 'collection.completed') {
            $email = $data['customerEmail'];
            $status = $data['status'];

            // Find the user and update the `has_paid` column
            $user = User::where('email', $email)->first();

            if ($user && $status === 'verified') {
                $user->update(['has_paid' => true]);
                Log::info("Payment marked as completed for user: $email");
            } else {
                Log::warning("User not found or status is not verified: $email");
            }
        }

        // Send a 200 response to acknowledge receipt of the webhook
        return response('Webhook received', 200);
    }
}
