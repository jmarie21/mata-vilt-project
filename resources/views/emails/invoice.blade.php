<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Invoice from Test Company</h2>
    <p>Dear {{ $invoice->client_name }},</p>
    <p>Please find attached your invoice.</p>
    <p>Total Amount: {{ $invoice->bill }}</p>
    <p>Thank you for your business!</p>
</body>
</html>