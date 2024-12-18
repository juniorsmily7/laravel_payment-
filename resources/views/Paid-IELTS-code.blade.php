<!-- resources/views/code.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Course Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .code-container {
            width: 100%;
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .code-container h1 {
            color: #d32f2f;
            margin-bottom: 20px;
        }
        .code-container p {
            font-size: 18px;
            color: #333;
        }
        .code-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #d32f2f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .code-container a:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>
    <div class="code-container">
        <h1>Your Course Code</h1>
        <p><strong>CODE12345</strong></p>
        <p>Use the code above to access your IELTS course.</p>
        <a href="#">Go to Course</a>
    </div>
</body>
</html>
