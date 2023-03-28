<?php
// Проверка параметра success в URL
$success = isset($_GET['success']) ? $_GET['success'] : null;
?>
<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <link rel="icon" href="favico.png" type="image/png">
</head>

<body>
  <?php if ($success == 1) : ?>
    <div class="alert alert-success" role="alert">
      Registration was successful! Log in using your email and password.
    </div>
  <?php endif; ?>

  <div class="container">
    <form action="auth.php" method="post">
      <label for="email"><b>Email</b></label>
      <input type="text" placeholder="Enter email" name="email" required>

      <label for="password"><b>Password</b></label>
      <input type="password" placeholder="Enter password" name="password" required>

      <button type="submit">Login</button>
    </form>
    <form action="register.php">
      <button type="submit" class="register-button">Sign Up</button>
    </form>
  </div>
</body>

</html>