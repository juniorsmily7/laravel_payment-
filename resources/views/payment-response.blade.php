<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Payment Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md text-center">
        <!-- Logo -->
        <div class="mb-6">
            <img alt="REdy Talent Logo" class="mx-auto h-16"
                src="https://www.redytalent.com/jobs/storage/company-icon-1-1.png">
        </div>

        <!-- Success Message -->
        <h1 class="text-2xl font-bold text-green-600 mb-4">Please don't leave this page!</h1>
        <p class="text-gray-700 mb-6">Thank you for your payment. Your transaction is being processed.</p>

        <!-- Timer -->
        <p id="timer" class="text-gray-500 mb-4">Payment processing <span id="time">02:00</span>...</p>

        <!-- Back to Home Button (initially hidden) -->
        <a id="homeButton"
            class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400"
            href="https://resume.redytalent.com/resume/"
            style="display: none;">
            Go Back to Home
        </a>
    </div>

    <script>
        // Timer variables
        const timerElement = document.getElementById("time");
        const button = document.getElementById("homeButton");

        let timeLeft = 2 * 60; // 2 minutes in seconds

        // Update the timer every second
        const timerInterval = setInterval(() => {
            const minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
            const seconds = String(timeLeft % 60).padStart(2, '0');
            timerElement.textContent = `${minutes}:${seconds}`;

            timeLeft--;

            if (timeLeft < 0) {
                clearInterval(timerInterval);
                // Show the button when the timer finishes
                button.style.display = "inline-block";
            }
        }, 1000);
    </script>
</body>

</html>
