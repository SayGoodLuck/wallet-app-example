<?php
require_once 'config/database.php';

// Проверка отправленных данных
if (isset($_POST['email']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Поиск пользователя по email
  $sql = "SELECT * FROM users WHERE email = $1";
  $result = pg_query_params($connection, $sql, array($email));

  // Проверка пароля и аутентификация
  if ($result && pg_num_rows($result) > 0) {
    $foundUser = pg_fetch_assoc($result);

    // Проверка хеша пароля
    if (password_verify($password, $foundUser['password'])) {
      // Аутентификация прошла успешно
      // Здесь можно начать сессию и сохранить данные пользователя
      session_start();
      $_SESSION['user'] = $foundUser;
      header('Location: main.php'); // Перенаправление на защищенную страницу
      exit();
    } else {
      // Ошибка аутентификации
      header('Location: login.php?error=1'); // Возвращение на страницу авторизации с кодом ошибки
      exit();
    }
  } else {
    // Ошибка аутентификации
    header('Location: login.php?error=1'); // Возвращение на страницу авторизации с кодом ошибки
    exit();
  }
} else {
  // Ошибка отправки данных
  header('Location: login.php?error=2');
  exit();
}
