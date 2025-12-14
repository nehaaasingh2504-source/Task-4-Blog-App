<?php
session_start();
include "db.php";

// 🔐 Login protection
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ Pagination settings
$limit = 5; // posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ✅ Fetch posts with LIMIT
$result = $conn->query("SELECT * FROM posts ORDER BY id DESC LIMIT $limit OFFSET $offset");

// ✅ Total posts count
$total_result = $conn->query("SELECT COUNT(*) AS total FROM posts");
$total_row = $total_result->fetch_assoc();
$total_posts = $total_row['total'];

$total_pages = ceil($total_posts / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>All Posts</h2>
<a href="create.php">➕ Create New Post</a> |
<a href="logout.php">Logout</a>
<hr>

<?php while ($row = $result->fetch_assoc()) { ?>
    <div class="post-box">
        <h3><?php echo $row['title']; ?></h3>
        <p><?php echo $row['content']; ?></p>

        <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a> |
        <a href="delete.php?id=<?php echo $row['id']; ?>" 
           onclick="return confirm('Are you sure?');">Delete</a>
    </div>
    <hr>
<?php } ?>

<!-- 🔢 Pagination links -->
<div class="pagination">
<?php
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
        echo "<strong>$i</strong> ";
    } else {
        echo "<a href='posts.php?page=$i'>$i</a> ";
    }
}
?>
</div>

</body>
</html>