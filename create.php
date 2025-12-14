<?php
session_start();
include "db.php";

// 🔐 Login protection
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // ✅ Server-side validation
    if (empty($title) || empty($content)) {
        $error = "All fields are required";
    } else {

        // ✅ Prepared Statement (Security Task-4)
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            $success = "Post created successfully";
        } else {
            $error = "Something went wrong";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-box">
    <h2>Create New Post</h2>

    <?php if ($error != "") { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($success != "") { ?>
        <p class="success"><?php echo $success; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="title" placeholder="Post Title" class="input-box" required><br><br>
        <textarea name="content" placeholder="Post Content" class="textarea-box" required></textarea><br><br>
        <button type="submit" class="btn">Create Post</button>
    </form>

    <br>
    <a href="posts.php">Back to Posts</a>
</div>

</body>
</html>