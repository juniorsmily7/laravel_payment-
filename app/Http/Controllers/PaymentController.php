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
        ]);

        $response = $this->startButtonService->initializeTransaction(
            $validated['amount'],
            $validated['currency'],
            $validated['email']
        );

        return response()->json($response);
    }
}
