<!-- resources/views/form.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REdy Talent IELTS Subscription Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f9f9;
        }
        .form-container {
            width: 100%;
            max-width: 500px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container img {
            display: block;
            margin: 0 auto 20px;
            width: 150px;
        }
        .form-container h1 {
            text-align: center;
            color: #d32f2f;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-container input, .form-container select, .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            background-color: #d32f2f;
            color: #fff;
            cursor: pointer;
            border: none;
        }
        .form-container button:hover {
            background-color: #b71c1c;
        }
        .form-container .amount-display {
            text-align: right;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <img src="https://redytalent.com/jobs/storage/redy-talent-logo-1.png" alt="REdy Talent Logo">
        <h1>IELTS Subscription Form</h1>
        <form id="paymentForm" onsubmit="handlePayment(event)">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="country">Country</label>
            <input type="text" id="country" name="country" required>

            <label for="currency">Currency to Pay In</label>
            <select id="currency" name="currency" required>
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

            <div class="amount-display" id="displayAmount"></div>

            <button type="submit">Pay $10</button>
        </form>
    </div>

    <script>
        const paymentForm = document.getElementById('paymentForm');
        const currencySelect = document.getElementById('currency');
        const displayAmount = document.getElementById('displayAmount');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

        function updateAmountDisplay() {
            const selectedCurrency = currencySelect.value;
            const exchangeRate = exchangeRates[selectedCurrency];
            const amountInCurrency = (10 * exchangeRate).toFixed(2);
            displayAmount.textContent = `${amountInCurrency} ${selectedCurrency}`;
        }

        currencySelect.addEventListener('change', updateAmountDisplay);

        paymentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const selectedCurrency = currencySelect.value;
            const exchangeRate = exchangeRates[selectedCurrency];
            const amountInCents = Math.round(10 * exchangeRate * 100);

            try {
                const response = await fetch('/paymentIELTS', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        email: "test@gmail.com", // Replace with a dynamic email if needed
                        amount: amountInCents,
                        currency: selectedCurrency,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Redirecting to payment page...');
                    window.location.href = data.data; // Redirect to the payment URL
                } else {
                    alert('Error initializing payment: ' + data.message);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                alert('An error occurred while initializing the payment.');
            }
        });

        updateAmountDisplay();
    </script>


</body>
</html>
