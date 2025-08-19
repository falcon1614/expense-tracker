<?php
session_start();
require_once 'config.php';
if (empty($_SESSION['user_identifier'])) {
    header('Location: index.php');
    exit;
}
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}
$id = intval($_GET['id']);
$user_identifier = $_SESSION['user_identifier'];
$stmt = $mysqli->prepare('DELETE FROM expenses WHERE id=? AND user_identifier=?');
$stmt->bind_param('is', $id, $user_identifier);
$stmt->execute();
header('Location: dashboard.php');
exit;
