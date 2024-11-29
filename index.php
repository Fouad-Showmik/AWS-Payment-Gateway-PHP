<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
</head>
<body>
    <h2>Payment Form</h2>
    <form action="payment.php" method="post">
        <label for="cardNumber">Card Number:</label>
        <input type="text" id="cardNumber" name="cardNumber" required><br><br>
        <label for="expiryMonth">Expiry Month:</label>
        <input type="text" id="expiryMonth" name="expiryMonth" required><br><br>
        <label for="expiryYear">Expiry Year:</label>
        <input type="text" id="expiryYear" name="expiryYear" required><br><br>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" required><br><br>
        <input type="submit" value="Pay Now">
    </form>
</body>
</html>
