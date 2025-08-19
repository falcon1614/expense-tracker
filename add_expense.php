<?php
session_start();
require_once 'config.php';
if (empty($_SESSION['user_identifier'])) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_identifier = $_SESSION['user_identifier'];
    $amount = floatval($_POST['amount']);
    $category = trim($_POST['category']);
    $date = $_POST['expense_date'];
    $note = trim($_POST['note']);

    $stmt = $mysqli->prepare('INSERT INTO expenses (user_identifier, amount, category, expense_date, note) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sdsss', $user_identifier, $amount, $category, $date, $note);
    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        die('Insert failed: ' . $mysqli->error);
    }
}
header('Location: dashboard.php');
exit;
