<?php
session_start();
require_once 'config.php';

// If user clicked 'Use as Guest' or logged in, redirect to dashboard
if (isset($_POST['guest'])) {
    // create a guest identifier stored in session
    $_SESSION['user_identifier'] = 'guest_' . session_id();
    $_SESSION['username'] = 'Guest';
    header('Location: dashboard.php');
    exit;
}

// If form login posted
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $stmt = $mysqli->prepare('SELECT id, password_hash FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['user_identifier'] = 'user_' . $id;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid credentials';
        }
    } else {
        $error = 'Invalid credentials';
    }
    $stmt->close();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Expense Tracker - Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <h1>Expense Tracker</h1>
    <p>Simple web app using PHP + MySQL. Use as Guest or create an account.</p>

    <?php if (!empty($error)): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" class="card">
      <h2>Login (optional)</h2>
      <label>Username
        <input name="username" required />
      </label>
      <label>Password
        <input name="password" type="password" required />
      </label>
      <div style="display:flex;gap:8px;">
        <button name="login" type="submit">Login</button>
        <a class="btn-link" href="register.php">Create account</a>
      </div>
    </form>

    <form method="post" style="margin-top:12px;">
      <button name="guest" type="submit">Use as Guest</button>
    </form>

    <hr />
    <p class="note">To reset the app, empty the <code>expenses</code> and <code>users</code> tables or re-import <code>db.sql</code>.</p>
  </div>
</body>
</html>
