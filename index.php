<?php
include 'db.php';

// PAGINATION SETTINGS
$limit = 5; 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// SEARCH FEATURE
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM posts
            WHERE title LIKE '%$search%'
            OR content LIKE '%$search%'
            ORDER BY created_at DESC
            LIMIT $limit OFFSET $offset";
} else {
    $sql = "SELECT * FROM posts 
            ORDER BY created_at DESC
            LIMIT $limit OFFSET $offset";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<h1>My Blog</h1>

<!-- SEARCH BAR -->
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search posts..." value="<?php echo $search; ?>">
    <button type="submit">Search</button>
</form>

<br>

<a href="create.php" class="btn">Create New Post</a>
<br><br>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='post-box'>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['content'] . "</p>";
        echo "<a href='update.php?id=" . $row['id'] . "' class='edit-btn'>Edit</a> | ";
        echo "<a href='delete.php?id=" . $row['id'] . "' class='delete-btn'>Delete</a>";
        echo "</div>";
    }
} else {
    echo "No posts found.";
}
?>

<!-- PAGINATION -->
<?php
$countSql = "SELECT COUNT(*) AS total FROM posts";
if ($search != "") {
    $countSql = "SELECT COUNT(*) AS total FROM posts
                 WHERE title LIKE '%$search%'
                 OR content LIKE '%$search%'";
}

$countResult = $conn->query($countSql);
$totalPosts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $limit);

echo "<div class='pagination'>";

if ($page > 1) {
    echo "<a href='?page=".($page-1)."&search=$search'>Previous</a>";
}

if ($page < $totalPages) {
    echo "<a href='?page=".($page+1)."&search=$search'>Next</a>";
}

echo "</div>";
?>

</div>
</body>
</html>