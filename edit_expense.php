<?php
session_start();
require_once 'config.php';
if (empty($_SESSION['user_identifier'])) {
    header('Location: index.php');
    exit;
}
$user_identifier = $_SESSION['user_identifier'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $mysqli->prepare('SELECT id, amount, category, expense_date, note FROM expenses WHERE id=? AND user_identifier=? LIMIT 1');
    $stmt->bind_param('is', $id, $user_identifier);
    $stmt->execute();
    $res = $stmt->get_result();
    $expense = $res->fetch_assoc();
    $stmt->close();
    if (!$expense) { die('Not found'); }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $amount = floatval($_POST['amount']);
    $category = trim($_POST['category']);
    $date = $_POST['expense_date'];
    $note = trim($_POST['note']);
    $stmt = $mysqli->prepare('UPDATE expenses SET amount=?, category=?, expense_date=?, note=? WHERE id=? AND user_identifier=?');
    $stmt->bind_param('dsssis', $amount, $category, $date, $note, $id, $user_identifier);
    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        die('Update failed: ' . $mysqli->error);
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Edit expense</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <h1>Edit expense</h1>
    <form method="post" class="card">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($expense['id']); ?>">
      <label>Amount
        <input name="amount" type="number" step="0.01" required value="<?php echo htmlspecialchars($expense['amount']); ?>" />
      </label>
      <label>Category
        <input name="category" required value="<?php echo htmlspecialchars($expense['category']); ?>" />
      </label>
      <label>Date
        <input name="expense_date" type="date" required value="<?php echo htmlspecialchars($expense['expense_date']); ?>" />
      </label>
      <label>Note
        <input name="note" value="<?php echo htmlspecialchars($expense['note']); ?>" />
      </label>
      <button type="submit">Save</button>
      <a href="dashboard.php" class="btn-link">Cancel</a>
    </form>
  </div>
</body>
</html>
