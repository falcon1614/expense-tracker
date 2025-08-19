<?php
session_start();
require_once 'config.php';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if (strlen($username) < 3 || strlen($password) < 4) {
        $error = 'Username or password too short';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hash);
        if ($stmt->execute()) {
            $_SESSION['user_identifier'] = 'user_' . $stmt->insert_id;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Registration failed: ' . $mysqli->error;
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Register - Expense Tracker</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <h1>Create account</h1>
    <?php if (!empty($error)): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" class="card">
      <label>Username
        <input name="username" required />
      </label>
      <label>Password
        <input name="password" type="password" required />
      </label>
      <button name="register" type="submit">Create account & continue</button>
    </form>
    <p><a href="index.php">Back to login</a></p>
  </div>
</body>
</html>
