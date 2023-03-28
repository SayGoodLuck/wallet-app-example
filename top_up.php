<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

// Проверка отправленных данных
if (isset($_POST['amount'])) {
  require_once 'config/database.php';

  $amount = (float)$_POST['amount'];
  $user_id = $_SESSION['user']['id'];

  // Проверка суммы на корректность
  if ($amount <= 0) {
    header('Location: top_up.php?error=1');
    exit();
  }

  // Запрос для обновления баланса пользователя
  $update_balance_query = "UPDATE users SET balance = balance + $1 WHERE id = $2";
  $result = pg_query_params($connection, $update_balance_query, array($amount, $user_id));

  if ($result) {
    // Обновление баланса в сессии
    $_SESSION['user']['balance'] += $amount;

    // Перенаправление на главную страницу с сообщением об успешном пополнении
    header('Location: main.php?success=1');
    exit();
  } else {
    // Ошибка при пополнении баланса
    header('Location: top_up.php?error=2');
    exit();
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>eSender - Top up balance</title>
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="icon" href="favico.png" type="image/png">
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>eSender - Top up balance</h1>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] == 1) : ?>
      <p class="error-message">Invalid amount. Please enter a positive number.</p>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 2) : ?>
      <p class="error-message">Error updating balance. Please try again.</p>
    <?php endif; ?>

    <div class="actions">
      <form action="" method="post">
        <input type="number" step="0.01" name="amount" placeholder="Enter amount" required>
        <input type="submit" value="Top up balance">
        <a href="main.php" class="back-button">Back</a>
      </form>
    </div>
  </div>
</body>

</html>