<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Response</title>
    <script>
        // Log the payment response to the console for testing
        const responseData = @json($responseData);
        console.log('Payment Response:', responseData);
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .status {
            font-size: 24px;
            margin: 20px 0;
        }
        .success {
            color: green;
        }
        .failed {
            color: red;
        }
        pre {
            text-align: left;
            background: #f8f8f8;
            padding: 15px;
            border-radius: 5px;
            max-width: 600px;
            margin: 20px auto;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>Payment Status</h1>
    <p class="status {{ $paymentStatus === 'success' ? 'success' : 'failed' }}">
        {{ $paymentStatus === 'success' ? 'Your payment was successful!' : 'Payment failed. Please try again.' }}
    </p>
    @if(isset($transactionId))
        <p>Transaction ID: {{ $transactionId }}</p>
    @endif

    <h2>Full Response</h2>
    <pre>{{ json_encode($responseData, JSON_PRETTY_PRINT) }}</pre>

    <a href="{{ route('welcome') }}">Go Back to Home</a>
</body>
</html>
