<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Success</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin: 8px 0;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }

        ul li {
            background: #f7f7f7;
            margin: 5px 0;
            padding: 10px 15px;
            border-radius: 6px;
            text-align: left;
        }

        a.receipt-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #fff;
            background: #4CAF50;
            padding: 10px 20px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        a.receipt-link:hover {
            background: #45a049;
        }

        .status {
            font-weight: bold;
            color: #2196F3;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h2>Payment Completed ✅</h2>

        <p><strong>Session ID:</strong> {{ $session->id }}</p>
        <p><strong>Amount:</strong> {{ $session->amount_total / 100 }} {{ strtoupper($session->currency) }}</p>
        <p><strong>Status:</strong> <span class="status">{{ $paymentIntent->status }}</span></p>

        <h3>Line Items</h3>
        <ul>
            @foreach ($lineItems->data as $item)
                <li>{{ $item->description }} - {{ $item->amount_total / 100 }} {{ strtoupper($session->currency) }}</li>
            @endforeach
        </ul>

        @if(!empty($paymentIntent->charges->data[0]->receipt_url))
            <a href="{{ $paymentIntent->charges->data[0]->receipt_url }}" target="_blank" class="receipt-link">View Receipt</a>
        @endif
        <strong>{{ $email }}</strong>
    </div>
</body>
</html>
