<?php
include 'includes/auth.php';
include 'includes/db.php';

$id = $_GET['id'];
$stmt = $db->prepare("DELETE FROM barang WHERE id = ?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit();
?>