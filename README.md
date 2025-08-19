# Expense Tracker (PHP + MySQL)

What you get:
- Simple expense tracker with optional account signup or quick 'Use as Guest'.
- Features: add, edit, delete expenses; per-user storage; monthly total.

Setup (XAMPP):
1. Copy the `expense-tracker` folder into `xampp/htdocs/`.
2. Start Apache and MySQL in XAMPP control panel.
3. Open phpMyAdmin (http://localhost/phpmyadmin) and import `db.sql`.
4. Visit http://localhost/expense-tracker in your browser.
5. Login (create account) or click **Use as Guest** to start.

Notes:
- DB credentials are in `config.php`. Default assumes root with empty password.
- Guest data is tracked by PHP session; if you clear browser session, guest data remains in DB but won't be linked to you.
- To clean test data, truncate `expenses` or drop + re-import `db.sql`.

Enjoy! 🎉
