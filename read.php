<?php
include 'db.php';
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Posts</title>
</head>
<body>
  <h2>All Blog Posts</h2>
  <a href="create.php">Add New Post</a><br><br>

  <?php
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo "<h3>" . $row['title'] . "</h3>";
          echo "<p>" . $row['content'] . "</p>";
          echo "<a href='update.php?id=" . $row['id'] . "'>Edit</a> | ";
          echo "<a href='delete.php?id=" . $row['id'] . "'>Delete</a>";
          echo "<hr>";
      }
  } else {
      echo "No posts found.";
  }
  ?>
</body>
</html>