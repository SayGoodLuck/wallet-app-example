<?php
require_once 'config/database.php';

function generateRandomAccountNumber($length = 10)
{
  $characters = '0123456789';
  $characters_length = strlen($characters);
  $random_account_number = '';
  for ($i = 0; $i < $length; $i++) {
    $random_account_number .= $characters[rand(0, $characters_length - 1)];
  }
  return $random_account_number;
}

// Проверка отправленных данных
if (isset($_POST['email']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Проверка существующего email
  $email_check_query = "SELECT * FROM users WHERE email = $1";
  $email_check_result = pg_query_params($connection, $email_check_query, array($email));

  if (pg_num_rows($email_check_result) > 0) {
    // Email уже существует
    header('Location: register.php?error=3');
    exit();
  }

  // Хеширование пароля
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Генерация случайного account_number
  $account_number = generateRandomAccountNumber();

  // Подготовка SQL-запроса для вставки нового пользователя
  $insert_query = "INSERT INTO users (email, password, account_number) VALUES ($1, $2, $3)";
  $result = pg_query_params($connection, $insert_query, array($email, $hashed_password, $account_number));

  // Выполнение запроса
  if ($result) {
    // Регистрация прошла успешно
    header('Location: login.php?success=1');
    exit();
  } else {
    // Ошибка при регистрации
    header('Location: register.php?error=1');
    exit();
  }
} else {
  // Ошибка отправки данных
  header('Location: register.php?error=2');
  exit();
}

// Закрытие подключения к базе данных
pg_close($connection);
