<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>StartButton Payment Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            background-color: #f4f4f9;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
        }
        input, select, button {
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .result {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .error {
            background: #fdecea;
            color: #d32f2f;
        }
    </style>
</head>
<body>
    <h1>StartButton Payment Integration Test</h1>
    <form id="paymentForm">
        @csrf
        <label for="email">Email:</label>
        <input type="email" id="email" placeholder="Enter customer email" required>

        <label for="amount">Amount:</label>
        <input type="number" id="amount" placeholder="Enter amount in cents" required>

        <label for="currency">Currency:</label>
        <select id="currency" required>
            <option value="GHS">GHS</option>
            <option value="USD">USD</option>
            <option value="NGN">NGN</option>
        </select>

        <button type="submit">Initialize Transaction</button>
    </form>

    <div id="result" class="result" style="display: none;"></div>

    <script>
        const paymentForm = document.getElementById('paymentForm');
        const resultDiv = document.getElementById('result');

        paymentForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const amount = document.getElementById('amount').value;
    const currency = document.getElementById('currency').value;
    const redirectUrl = "https://www.google.com/"; // Hardcoded redirect URL

    try {
        const response = await fetch('/payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ email, amount, currency, redirectUrl }), // Include redirectUrl
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = data.data;
        } else {
            throw new Error(data.message || 'An error occurred');
        }
    } catch (error) {
        resultDiv.textContent = `Error: ${error.message}`;
        resultDiv.classList.add('error');
        resultDiv.style.display = 'block';
    }
});


    </script>
</body>
</html>
