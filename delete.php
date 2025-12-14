<?php
session_start();
include "db.php";

// 🔐 Login protection
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check post ID
if (!isset($_GET['id'])) {
    header("Location: posts.php");
    exit();
}

$id = $_GET['id'];

// ✅ Prepared Statement (Security Task-4)
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();

// Redirect back
header("Location: posts.php");
exit();
?>