<?php
session_start();

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

if (isset($_POST['recipient_account_number']) && isset($_POST['send_amount'])) {
  require_once 'config/database.php';

  $sender_id = $_SESSION['user']['id'];
  $recipient_account_number = $_POST['recipient_account_number'];
  $send_amount = $_POST['send_amount'];

  // Найти получателя по номеру счета
  $sql = "SELECT * FROM users WHERE account_number = $1";
  $result = pg_query_params($connection, $sql, array($recipient_account_number));

  if ($result && pg_num_rows($result) > 0) {
    $recipient = pg_fetch_assoc($result);
    $recipient_id = $recipient['id'];

    // Проверка, достаточно ли средств на счете отправителя
    $sql = "SELECT * FROM users WHERE id = $1";
    $result = pg_query_params($connection, $sql, array($sender_id));
    $sender = pg_fetch_assoc($result);

    if ($sender['balance'] < $send_amount) {
      header('Location: send_funds.php?error=1'); // Недостаточно средств
      exit();
    }

    // Начать транзакцию
    pg_query($connection, "BEGIN");

    // Списать средства со счета отправителя
    $sql = "UPDATE users SET balance = balance - $1 WHERE id = $2";
    $result1 = pg_query_params($connection, $sql, array($send_amount, $sender_id));

    // Зачислить средства на счет получателя
    $sql = "UPDATE users SET balance = balance + $1 WHERE id = $2";
    $result2 = pg_query_params($connection, $sql, array($send_amount, $recipient_id));

    // Добавить запись о транзакции
    $sql = "INSERT INTO transactions (sender_id, recipient_id, amount) VALUES ($1, $2, $3)";
    $result3 = pg_query_params($connection, $sql, array($sender_id, $recipient_id, $send_amount));

    if ($result1 && $result2 && $result3) {
      pg_query($connection, "COMMIT"); // Завершение транзакции
      header('Location: main.php?success=1'); // Операция успешно выполнена
      exit();
    } else {
      pg_query($connection, "ROLLBACK"); // Откат транзакции
      header('Location: send_funds.php?error=2'); // Ошибка при выполнении операции
      exit();
    }
  } else {
    header('Location: send_funds.php?error=3'); // Получатель не найден
    exit();
  }
} else {
  header('Location: send_funds.php?error=4'); // Неправильные данные
  exit();
}
