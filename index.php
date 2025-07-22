<?php
include('config/db_connect.php');

// write query for all delicacies
$sql = 'SELECT title, ingredients, id FROM cohort_food ORDER BY created_at';

// get the result set
$result = mysqli_query($conn, $sql);

// fetch the resulting rows as an array
$delicacies = mysqli_fetch_all($result, MYSQLI_ASSOC);

// free the result from memory
mysqli_free_result($result);

// close connections
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<?php include('templates/header.php'); ?>

<h4 class="text-center text-secondary mb-4">Delicacies!</h4>

<div class="container my-5">
	<div class="row g-4">
		<?php foreach ($delicacies as $delicacy): ?>
			<div class="col-12 col-sm-6 col-lg-4">
				<div class="card border-0 shadow-sm h-100 mb-4">
					<img src="img/delicacy.svg" class="delicacy">
					<div class="card-body text-center">
						<h6 class="card-title"><?php echo htmlspecialchars($delicacy['title']); ?></h6>
						<ul class="list-unstyled text-secondary">
							<?php foreach (explode(',', $delicacy['ingredients']) as $ingredient): ?>
								<li><?php echo htmlspecialchars($ingredient); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="card-footer bg-transparent border-0 text-end">
						<a class="btn-primary text-decoration-none"
							href="details.php?id=<?php echo $delicacy['id'] ?>">
							more info...
						</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<?php include('templates/footer.php'); ?>

</html>