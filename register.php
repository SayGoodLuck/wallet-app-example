<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" type="text/css" href="css/register.css">
  <link rel="icon" href="favico.png" type="image/png">
</head>

<body>
  <div class="container">
    <form action="register_handler.php" method="post">
      <label for="email"><b>Email</b></label>
      <input type="email" placeholder="Enter Email" name="email" required>

      <label for="password"><b>Password</b></label>
      <input type="password" placeholder="Enter password" name="password" required>

      <button type="submit">Sign Up</button>
    </form>
    <form action="login.php">
      <button type="submit" class="login-button">Login</button>
    </form>
  </div>
</body>

</html>