<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

require_once 'config/database.php';

// Получение данных текущего пользователя
$user_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM transactions WHERE sender_id = $1 ORDER BY created_at DESC";
$result = pg_query_params($connection, $sql, array($user_id));

if (!$result) {
  // Если не удалось получить данные, выводим сообщение об ошибке
  echo "Error getting transaction history.";
  exit();
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>eSender - Transaction History</title>
  <link rel="stylesheet" type="text/css" href="css/transactions_history.css">
  <link rel="icon" href="favico.png" type="image/png">
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>eSender - Transaction History</h1>
    </div>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Recipient Account Number</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = pg_fetch_assoc($result)) : ?>
          <tr>
            <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
            <td><?php
                $recipient_id = $row['recipient_id'];
                $sql2 = "SELECT account_number FROM users WHERE id = $1";
                $result2 = pg_query_params($connection, $sql2, array($recipient_id));
                if ($result2 && pg_num_rows($result2) > 0) {
                  $user = pg_fetch_assoc($result2);
                  echo htmlspecialchars($user['account_number']);
                } else {
                  echo "N/A";
                }
                ?></td>
            <td>$<?php echo number_format($row['amount'], 2); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <div class="actions">
      <a href="main.php" class="back-button">Back</a>
    </div>
  </div>
</body>

</html>