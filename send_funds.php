<!-- send_funds.php -->
<!DOCTYPE html>
<html>

<head>
  <title>Send Funds</title>
  <link rel="stylesheet" type="text/css" href="css/send_funds.css">
  <link rel="icon" href="favico.png" type="image/png">
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>eSender - Send Funds</h1>
    </div>
    <div class="send-funds">
      <h2>Details:</h2>
      <form action="process_send_funds.php" method="post">
        <label for="recipient_account_number">Recipient account number:</label>
        <input type="text" name="recipient_account_number" id="recipient_account_number" required>
        <label for="send_amount">Amount:</label>
        <input type="number" name="send_amount" id="send_amount" step="0.01" min="0.01" required>
        <input type="submit" name="send_funds" value="Send">
      </form>
      <a href="main.php" class="back-button">Back</a>
    </div>
  </div>
</body>

</html>