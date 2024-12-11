<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction; // Import the Transaction model
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $secret = env('STARTBUTTON_SECRET_KEY');
        $requestBody = $request->getContent();
        $signature = $request->header('x-startbutton-signature');

        // Debugging
        logger()->info('Webhook received', [
            'headers' => $request->headers->all(),
            'signature' => $signature,
            'body' => $requestBody,
        ]);

        if ($this->isValidSignature($secret, $requestBody, $signature)) {
            // Decode event data
            $eventData = json_decode($requestBody, true);
            logger()->info('Event Data', $eventData);

            if (isset($eventData['event']) && $eventData['event'] === 'collection.completed') {
                $transactionData = $eventData['data']['transaction'] ?? null;

                if ($transactionData) {
                    $customerEmail = $transactionData['customerEmail'] ?? null;

                    if ($customerEmail) {
                        $user = User::where('email', $customerEmail)->first();
                        logger()->info('Processing transaction for user:', ['email' => $customerEmail]);

                        if ($user) {
                            // Update user's payment status
                            $user->update(['has_paid' => true]);

                            // Create a new transaction record
                            Transaction::create([
                                'user_id' => $user->id,
                                'transaction_id' => $transactionData['_id'],
                                'status' => $transactionData['status'],
                                'amount' => $transactionData['amount'],
                                'currency' => $transactionData['currency'],
                                'created_at' => $transactionData['createdAt'],
                                'updated_at' => $transactionData['updatedAt'],
                            ]);

                            logger()->info('Transaction stored successfully.');
                        } else {
                            logger()->warning('No user found for email:', ['email' => $customerEmail]);
                        }
                    } else {
                        logger()->error('Customer email is missing in transaction data.');
                    }
                } else {
                    logger()->error('Transaction data is missing in event payload.');
                }
            } else {
                logger()->info('Event type not handled:', ['event' => $eventData['event'] ?? 'unknown']);
            }
        } else {
            logger()->error('Invalid webhook signature.');
        }

        return response()->json([], Response::HTTP_OK);
    }

    private function isValidSignature($secret, $data, $signature)
    {
        if (is_null($signature)) {
            logger()->error('Signature is missing.');
            return false;
        }

        $calculatedSignature = hash_hmac('sha512', $data, $secret);
        return hash_equals($calculatedSignature, $signature);
    }
}
