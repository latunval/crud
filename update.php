<?php
include('config/db_connect.php');
$email = $title = $ingredients = '';
$found_food = null;
$errors = ['email' => '', 'title' => '', 'ingredients' => ''];

// If coming from edit button, fetch the record to edit
if (isset($_POST['Edit']) && !empty($_POST['id_to_edit'])) {
    $id_to_edit = mysqli_real_escape_string($conn, $_POST['id_to_edit']);
    
    $sql = "SELECT * FROM cohort_food WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_to_edit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result) {
        $found_food = mysqli_fetch_assoc($result);
        $email = $found_food['email'];
        $title = $found_food['title'];
        $ingredients = $found_food['ingredients'];
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}

// If submitting the update form
if (isset($_POST['update'])) {
    // Validate Email
    if (empty($_POST['email'])) {
        $errors['email'] = 'An email is required';
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email must be a valid email address';
        }
    }

    // Validate Title
    if (empty($_POST['title'])) {
        $errors['title'] = 'A title is required';
    } else {
        $title = $_POST['title'];
        if (!preg_match('/^[a-zA-Z\s]+$/', $title)) {
            $errors['title'] = 'Title must be letters and spaces only';
        }
    }

    // Validate Ingredients
    if (empty($_POST['ingredients'])) {
        $errors['ingredients'] = 'At least one ingredient is required';
    } else {
        $ingredients = $_POST['ingredients'];
        if (!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)) {
            $errors['ingredients'] = 'Ingredients must be a comma-separated list';
        }
    }

    // If no errors, proceed to update
    if (!array_filter($errors)) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
        $id_to_update = mysqli_real_escape_string($conn, $_POST['id_to_update']);

        $sql = "UPDATE cohort_food SET email = ?, title = ?, ingredients = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $email, $title, $ingredients, $id_to_update);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: index.php');
            exit();
        } else {
            echo 'Query error: ' . mysqli_error($conn);
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

<?php require('templates/header.php'); ?>

<section class="container py-5">
    <h4 class="text-secondary text-center mb-4">Edit Cohort Delicacy</h4>
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <input type="hidden" name="id_to_update" value="<?php echo htmlspecialchars($_POST['id_to_update'] ?? $found_food['id'] ?? ''); ?>">
    <p><?php echo htmlspecialchars($_POST['id_to_update'] ?? $found_food['id'] ?? 'hello'); ?></p>    
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email"
                                   class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : ''; ?>"
                                   id="email"
                                   name="email"
                                   value="<?php echo htmlspecialchars($email ?: ($found_food['email'] ?? '')); ?>">
                            <?php if (!empty($errors['email'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['email']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Delicacy Title</label>
                            <input type="text"
                                   class="form-control <?php echo !empty($errors['title']) ? 'is-invalid' : ''; ?>"
                                   id="title"
                                   name="title"
                                   value="<?php echo htmlspecialchars($title ?: ($found_food['title'] ?? '')); ?>">
                            <?php if (!empty($errors['title'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['title']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="ingredients" class="form-label">Ingredients (comma separated)</label>
                            <textarea
                                class="form-control <?php echo !empty($errors['ingredients']) ? 'is-invalid' : ''; ?>"
                                id="ingredients"
                                name="ingredients"
                                rows="3"><?php echo htmlspecialchars($ingredients ?: ($found_food['ingredients'] ?? '')); ?></textarea>
                            <?php if (!empty($errors['ingredients'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['ingredients']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="update" class="btn btn-primary px-4 py-2">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require('templates/footer.php'); ?>

</body>
</html>
