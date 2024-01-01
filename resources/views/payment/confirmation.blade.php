
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
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

        p {
            margin: 15px 0;
            font-size: 1.2em;
        }

        .success {
            color: #4caf50;
            font-weight: bold;
        }

        .failure {
            color: #f44336;
            font-weight: bold;
        }

        a {
            color: #4285f4;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive Styles */
        @media (max-width: 767px) {
            .container {
                width: 90%;
            }

            h1 {
                font-size: 1.8em;
            }

            p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Payment Confirmation</h1>

    @if($transaction->status == 'Success')
    <p class="success">Thank you for your payment! Your transaction was successful.</p>
    @else
    <p class="failure">Sorry, your payment was not successful. Please try again.</p>
    @endif

    <p>Transaction ID: {{ $transaction->transaction_id }}</p>
    <p>Amount: {{ $transaction->amount }}</p>
    <p>Status: {{ $transaction->status }}</p>

    <!-- You can add additional details as needed -->

    <!-- Add a link or button to return to your application's home page -->
    <p><a href="{{ url('/') }}">Return to Home</a></p>
</div>
</body>
</html>
