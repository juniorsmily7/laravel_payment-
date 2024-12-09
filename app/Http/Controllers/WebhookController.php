<?php

namespace App\Http\Controllers;

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
            // Request is valid, process the event
            $eventData = json_decode($requestBody, true);
            logger()->info('Event Data', $eventData);
            // Do something with the event data
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
