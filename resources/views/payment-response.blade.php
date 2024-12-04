<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Response</title>
</head>
<body>
    <h1>Payment Response</h1>

    <p><strong>Payment Status:</strong> {{ $paymentStatus }}</p>

    <p><strong>Transaction ID:</strong> {{ $transactionId }}</p>

    <p><strong>User Email:</strong> {{ $userEmail }}</p>

    <h3>Full Response Data:</h3>
    <pre>{{ print_r($responseData, true) }}</pre>
</body>
</html>
