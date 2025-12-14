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

// Get post ID
if (!isset($_GET['id'])) {
    header("Location: posts.php");
    exit();
}

$id = $_GET['id'];

// Fetch existing post data securely
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: posts.php");
    exit();
}

$post = $result->fetch_assoc();
$stmt->close();

// Update post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // ✅ Validation
    if (empty($title) || empty($content)) {
        $error = "All fields are required";
    } else {

        // ✅ Prepared Statement (Security Task-4)
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {
            $success = "Post updated successfully";
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
    <title>Update Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-box">
    <h2>Edit Post</h2>

    <?php if ($error != "") { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($success != "") { ?>
        <p class="success"><?php echo $success; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" class="input-box" required><br><br>
        <textarea name="content" class="textarea-box" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>
        <button type="submit" class="btn">Update Post</button>
    </form>

    <br>
    <a href="posts.php">Back to Posts</a>
</div>

</body>
</html>