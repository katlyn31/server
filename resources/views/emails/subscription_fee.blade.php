<!DOCTYPE html>
<html>
<head>
    <title>Subscription Details</title>
</head>
<body>
    <h1>Welcome, {{ $user->fullname }}!</h1>
    <p>Your subscription fee is {{ $subscriptionFee }}.</p>
    <p>Carrier: {{ $user->carrier }}</p>
    <p>Token: {{ $user->token }}</p>
    <p>Email: {{ $user->email }}</p>
    <p>Phone: {{ $user->phone }}</p>
    <p>Thank you for registering!</p>
</body>
</html>
