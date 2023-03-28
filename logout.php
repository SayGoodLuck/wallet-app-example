<?php
session_start();

if (isset($_POST['logout'])) {
  // Завершение сессии и удаление данных пользователя
  session_unset();
  session_destroy();
}

// Перенаправление на страницу авторизации
header('Location: login.php');
exit();
