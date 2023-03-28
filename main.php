<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

require_once 'config/database.php';

// Получение данных текущего пользователя
$user_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM users WHERE id = $1";
$result = pg_query_params($connection, $sql, array($user_id));

if ($result && pg_num_rows($result) > 0) {
  $currentUser = pg_fetch_assoc($result);
} else {
  // Если не удалось получить данные пользователя, перенаправляем на страницу входа
  header('Location: login.php');
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>eSender</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="icon" href="favico.png" type="image/png">
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>eSender</h1>
    </div>
    <div class="user-info">
      <h2>Email: <span class="email"><?php echo htmlspecialchars($currentUser['email']); ?></span></h2>
      <h2>Account number: <span class="account-number"><?php echo htmlspecialchars($currentUser['account_number']); ?></span> <button class="copy-account-number-button">Copy</button></h2>
    </div>


    <div class="balance">
      <h2>Balance:</h2>
      <p>$<?php echo number_format($currentUser['balance'], 2); ?></p>
    </div>
    <div class="actions">
      <a href="top_up.php" class="top-up-button">Top up balance</a>
      <a href="send_funds.php" class="send-funds-button">Send to</a>
      <a href="transactions_history.php" class="history-button">Transaction history</a>
    </div>
    <!-- Форма выхода -->
    <div class="logout">
      <form action="logout.php" method="post">
        <input type="submit" name="logout" value="Log out">
      </form>
    </div>
  </div>
  <script>
    const copyAccountNumberButton = document.querySelector('.copy-account-number-button');
    const accountNumber = document.querySelector('.account-number').innerText;

    copyAccountNumberButton.addEventListener('click', () => {
      navigator.clipboard.writeText(accountNumber);
      alert("Account number copied to clipboard!");
    });
  </script>

</body>

</html>