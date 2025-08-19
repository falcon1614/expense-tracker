<?php
session_start();
require_once 'config.php';
if (empty($_SESSION['user_identifier'])) {
    header('Location: index.php');
    exit;
}
$user_identifier = $_SESSION['user_identifier'];
$username = $_SESSION['username'] ?? 'You';

// fetch summary: total this month and last 30 entries
$summary_stmt = $mysqli->prepare("SELECT IFNULL(SUM(amount),0) as total_month FROM expenses WHERE user_identifier = ? AND MONTH(expense_date)=MONTH(CURDATE()) AND YEAR(expense_date)=YEAR(CURDATE())");
$summary_stmt->bind_param('s', $user_identifier);
$summary_stmt->execute();
$summary_stmt->bind_result($total_month);
$summary_stmt->fetch();
$summary_stmt->close();

// fetch recent expenses
$list_stmt = $mysqli->prepare('SELECT id, amount, category, expense_date, note FROM expenses WHERE user_identifier = ? ORDER BY expense_date DESC, created_at DESC LIMIT 50');
$list_stmt->bind_param('s', $user_identifier);
$list_stmt->execute();
$res = $list_stmt->get_result();
$expenses = $res->fetch_all(MYSQLI_ASSOC);
$list_stmt->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Dashboard - Expense Tracker</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <header style="display:flex;justify-content:space-between;align-items:center;">
      <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
      <div>
        <a href="logout.php">Logout</a>
      </div>
    </header>

    <section class="card">
      <h2>Quick summary</h2>
      <p><strong>Total this month:</strong> ₹ <?php echo number_format($total_month,2); ?></p>
    </section>

    <section class="card">
      <h2>Add expense</h2>
      <form id="addForm" method="post" action="add_expense.php">
        <label>Amount
          <input name="amount" type="number" step="0.01" required />
        </label>
        <label>Category
          <input name="category" required placeholder="e.g. Food, Transport" />
        </label>
        <label>Date
          <input name="expense_date" type="date" required value="<?php echo date('Y-m-d'); ?>" />
        </label>
        <label>Note
          <input name="note" />
        </label>
        <button type="submit">Add</button>
      </form>
    </section>

    <section class="card">
      <h2>Recent expenses</h2>
      <?php if (empty($expenses)): ?>
        <p>No expenses yet.</p>
      <?php else: ?>
        <table class="expenses">
          <thead><tr><th>Date</th><th>Category</th><th>Note</th><th>Amount</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($expenses as $e): ?>
              <tr>
                <td><?php echo htmlspecialchars($e['expense_date']); ?></td>
                <td><?php echo htmlspecialchars($e['category']); ?></td>
                <td><?php echo htmlspecialchars($e['note']); ?></td>
                <td>₹ <?php echo number_format($e['amount'],2); ?></td>
                <td>
                  <a href="edit_expense.php?id=<?php echo $e['id']; ?>">Edit</a> |
                  <a href="delete_expense.php?id=<?php echo $e['id']; ?>" onclick="return confirm('Delete this expense?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>

    <footer style="margin-top:18px;">
      <small>Place the project folder inside <code>htdocs</code> (XAMPP) and import <code>db.sql</code> into MySQL.</small>
    </footer>
  </div>
</body>
</html>
