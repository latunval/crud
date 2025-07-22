<?php
$conn = new mysqli("localhost", "root", "", "test_db");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];

  $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$id");

  echo "User updated successfully!<br>";
  echo "<a href='fetch.php'>Back to Users</a>";
} else {
  $result = $conn->query("SELECT * FROM users WHERE id=$id");
  $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Edit User</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Name:</label>
      <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Email:</label>
      <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="fetch.php" class="btn btn-secondary">Cancel</a>
  </form>
</body>
</html>

<?php
}
$conn->close();
?>
