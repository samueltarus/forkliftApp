<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forklift Hire Payment</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4285f4;
            font-size: 2em;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-size: 1.2em;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
            font-size: 1em;
        }

        button {
            background-color: #4285f4;
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Forklift Hire Payment</h1>

    <form action="{{ route('initiatePayment') }}" method="post">
        @csrf
        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" placeholder="Enter amount" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone" id="phone" placeholder="Enter phone number" required>

        <button type="submit">Initiate Payment</button>
    </form>
</div>
</body>
</html>
