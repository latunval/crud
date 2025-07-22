<?php

include('config/db_connect.php');

$email = $title = $ingredients = '';
$errors = ['email' => '', 'title' => '', 'ingredients' => ''];

if (isset($_POST['submit'])) {

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
		//echo 'errors in form';
	} else {
		// escape sql chars
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$title = mysqli_real_escape_string($conn, $_POST['title']);
		$ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);

		// create sql
		$sql = "INSERT INTO cohort_food(title,email,ingredients) VALUES('$title','$email','$ingredients')";

		// save to db and check
		if (mysqli_query($conn, $sql)) {
			// success
			header('Location: index.php');
		} else {
			echo 'query error: ' . mysqli_error($conn);
		}
	}
} // end POST check

?>

<!DOCTYPE html>
<html>

<?php include('templates/header.php'); ?>
<section class="container py-5">
	<h4 class="text-secondary text-center mb-4">Add Cohort Delicacies</h4>

	<div class="row justify-content-center">
		<div class="col-md-8 col-lg-6">
			<div class="card shadow-sm">
				<div class="card-body p-4">
					<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
						<div class="mb-3">
							<label for="email" class="form-label">Your Email</label>
							<input type="email"
								class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : ''; ?>"
								id="email"
								name="email"
								value="<?php echo htmlspecialchars($email) ?>">
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
								value="<?php echo htmlspecialchars($title) ?>">
							<?php if (!empty($errors['title'])): ?>
								<div class="invalid-feedback">
									<?php echo $errors['title']; ?>
								</div>
							<?php endif; ?>
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

						<div class="text-center">
							<button type="submit"
								name="submit"
								class="btn btn-primary px-4 py-2">
								Submit
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include('templates/footer.php'); ?>

</html>