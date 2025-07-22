<?php
include('config/db_connect.php');
$email = $title = $ingredients = '';
$found_food = null;
$errors = ['email' => '', 'title' => '', 'ingredients' => ''];


// If coming from edit button, fetch the record to edit
if (isset($_POST['Edit'])) {
    $id_to_edit = mysqli_real_escape_string($conn, $_POST['id_to_edit']);
    $sql = "SELECT * FROM cohort_food WHERE id = $id_to_edit";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $found_food = mysqli_fetch_assoc($result);
        $email = $found_food['email'];
        $title = $found_food['title'];
        $ingredients = $found_food['ingredients'];
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// If submitting the update form
if (isset($_POST['update'])) {

    // check email
    if (empty($_POST['email'])) {
        $errors['email'] = 'An email is required';
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email must be a valid email address';
        }
    }

    // check title
    if (empty($_POST['title'])) {
        $errors['title'] = 'A title is required';
    } else {
        $title = $_POST['title'];
        if (!preg_match('/^[a-zA-Z\s]+$/', $title)) {
            $errors['title'] = 'Title must be letters and spaces only';
        }
    }

    // check ingredients
    if (empty($_POST['ingredients'])) {
        $errors['ingredients'] = 'At least one ingredient is required';
    } else {
        $ingredients = $_POST['ingredients'];
        if (!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)) {
            $errors['ingredients'] = 'Ingredients must be a comma separated list';
        }
    }

    if (array_filter($errors)) {
        // errors in form, do not update
    } else {
        // escape sql chars
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
        $id_to_update = mysqli_real_escape_string($conn, $_POST['id_to_update']);
        $sql = "UPDATE cohort_food SET email='$email', title='$title', ingredients='$ingredients' WHERE id=$id_to_update";
        if (mysqli_query($conn, $sql)) {
            header('Location: index.php');
            exit();
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}
?>

    
  <!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <?php
    require('templates/header.php');
    ?>
  <h2>Edit User</h2>
  <form method="POST">
    <div class="mb-3">
      <label>Name:</label>
      <input type="text" name="title" class="form-control" value="<?= $row['name'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Email:</label>
      <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
    </div>
    <div class="mb-3">
							<label for="ingredients" class="form-label">Ingredients (comma separated)</label>
							<textarea type="text"
								class="form-control <?php echo !empty($errors['ingredients']) ? 'is-invalid' : ''; ?>"
								id="ingredients"
								name="ingredients"
								value="<?php echo htmlspecialchars($ingredients) ?>">
								</textarea>
							<?php if (!empty($errors['ingredients'])): ?>
								<div class="invalid-feedback">
									<?php echo $errors['ingredients']; ?>
								</div>
							<?php endif; ?>
						</div>
    <button type="submit" class="btn btn-success" name="update">Update</button>
  </form>
</body>
</html>
    <?php
    require('templates/footer.php')
    ?>
</body>

</html>