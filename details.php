<?php
include('config/db_connect.php');

if (isset($_POST['delete'])) {
	$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);
	$sql = "DELETE FROM cohort_food WHERE id = $id_to_delete";

	if (mysqli_query($conn, $sql)) {
		header('Location: index.php');
	} else {
		echo 'query error: ' . mysqli_error($conn);
	}
}

// check GET request id param
if (isset($_GET['id'])) {
	// escape sql chars
	$id = mysqli_real_escape_string($conn, $_GET['id']);

	// make sql
	$sql = "SELECT * FROM cohort_food WHERE id = $id";

	// get the query result
	$result = mysqli_query($conn, $sql);

	// fetch result in array format
	$cohort_food = mysqli_fetch_assoc($result);

	mysqli_free_result($result);
	mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html>

<?php include('templates/header.php'); ?>

<div class="container text-center my-5">
	<?php
	if (isset($cohort_food)) {
		if ($cohort_food) {
			// Food exists
	?>
			<h4><?php echo $cohort_food['title']; ?></h4>
			<div class="row mb-4">
				<div class="col">
					<h5 class="text-secondary">Created by <?php echo $cohort_food['email']; ?></h5>
				</div>
			</div>

			<p><?php echo date($cohort_food['created_at']); ?></p>

			<div class="row mb-4">
				<div class="col">
					<h5 class="text-secondary">Ingredients:</h5>
				</div>
			</div>
			<p><?php echo $cohort_food['ingredients']; ?></p>

			<!-- DELETE FORM -->
			<form action="details.php" method="POST" onsubmit="return confirmDelete()">
				<input type="hidden" name="id_to_delete" value="<?php echo $cohort_food['id']; ?>">
				<input type="submit" name="delete" value="Delete" class="btn btn-danger">
			</form>
		<?php
		} else {
			// Food doesn't exist
		?>
			<div class="alert alert-warning" role="alert">
				<i class="bi bi-exclamation-triangle-fill me-2"></i>
				No such cohort Delicacy exists.
			</div>
		<?php
		}
	} else {
		// No ID provided
		?>
		<div class="alert alert-warning" role="alert">
			<i class="bi bi-exclamation-triangle-fill me-2"></i>
			No delicacy ID provided.
		</div>
	<?php
	}
	?>
</div>

<!-- Add this JavaScript before closing body tag -->
<script>
	function confirmDelete() {
		return confirm('Are you sure you want to delete this delicacy? This action cannot be undone.');
	}
</script>

<?php include('templates/footer.php'); ?>

</html>