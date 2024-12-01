<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Static Payment Integration Test</title>
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
        select, button {
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .amount-display {
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Static Payment Integration Test</h1>
    <form id="paymentForm" method="POST">
        @csrf

        <!-- Currency Selection -->
        <label for="currency">Currency:</label>
        <select id="currency" required>
            <option value="USD">USD</option>
            <option value="NGN">NGN</option>
            <option value="GHS">GHS</option>
            <option value="KES">KES</option>
            <option value="ZAR">ZAR</option>
            <option value="UGX">UGX</option>
            <option value="TZS">TZS</option>
            <option value="RWF">RWF</option>
            <option value="XOF">XOF</option>
        </select>

        <!-- Amount to Pay -->
        <div class="amount-display">
            <strong>Amount to Pay:</strong> <span id="displayAmount">10 USD</span>
        </div>

        <!-- Submit Button -->
        <button type="submit">Proceed to Pay</button>
    </form>

    <script>
        const paymentForm = document.getElementById('paymentForm');
        const currencySelect = document.getElementById('currency');
        const displayAmount = document.getElementById('displayAmount');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Exchange rates (1 USD equivalent)
        const exchangeRates = {
            USD: 1,
            NGN: 780,
            GHS: 12,
            KES: 145,
            ZAR: 18,
            UGX: 3700,
            TZS: 2500,
            RWF: 1200,
            XOF: 620,
        };

        // Function to update the displayed amount when the currency changes
        function updateAmountDisplay() {
            const selectedCurrency = currencySelect.value;
            const exchangeRate = exchangeRates[selectedCurrency];
            const amountInCurrency = (10 * exchangeRate).toFixed(2); // Convert $10 to selected currency
            displayAmount.textContent = `${amountInCurrency} ${selectedCurrency}`;
        }

        // Update the displayed amount on currency change
        currencySelect.addEventListener('change', updateAmountDisplay);

        // Handle form submission
        paymentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const selectedCurrency = currencySelect.value;
            const exchangeRate = exchangeRates[selectedCurrency];
            const amountInCents = Math.round(10 * exchangeRate * 100); // Convert to cents

            try {
                const response = await fetch('/payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        email: "test@gmail.com",  // Static or user-provided email
                        amount: amountInCents,       // Send the dynamic amount in cents
                        currency: selectedCurrency,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Redirecting to payment page...');
                    window.location.href = data.data; // Redirect to the payment page
                } else {
                    alert('Error initializing payment: ' + data.message);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                alert('An error occurred while initializing the payment.');
            }
        });

        // Initialize with default amount display
        updateAmountDisplay();
    </script>

</body>
</html>
