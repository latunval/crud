<?php
include('config/db_connect.php');

// Delete logic
if (isset($_POST['delete'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);
    $sql = "DELETE FROM cohort_food WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_to_delete);
    
    if (mysqli_stmt_execute($stmt)) {
        session_start();
        $_SESSION['success'] = 'Delicacy deleted successfully.';
        header('Location: index.php');
        exit();
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}

// Get single item logic
$cohort_food = null;
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM cohort_food WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $cohort_food = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>

<?php include('templates/header.php'); ?>

<div class="container text-center my-5">
    <?php if ($cohort_food): ?>
        <h4><?php echo htmlspecialchars($cohort_food['title']); ?></h4>
        <h5 class="text-secondary mb-3">Created by <?php echo htmlspecialchars($cohort_food['email']); ?></h5>
        <p class="text-muted">
            <?php echo date("F j, Y, g:i a", strtotime($cohort_food['created_at'])); ?>
        </p>

        <h5 class="text-secondary">Ingredients:</h5>
        <p><?php echo htmlspecialchars($cohort_food['ingredients']); ?></p>

        <!-- DELETE FORM -->
        <form action="details.php?id=<?php echo $cohort_food['id']; ?>" method="POST" onsubmit="return confirmDelete()" class="d-inline-block me-2">
            <input type="hidden" name="id_to_delete" value="<?php echo $cohort_food['id']; ?>">
            <input type="submit" name="delete" value="Delete" class="btn btn-danger">
        </form>

        <!-- EDIT FORM -->
        <form action="update.php" method="POST" class="d-inline-block">
            <input type="hidden" name="id_to_edit" value="<?php echo $cohort_food['id']; ?>">
            <input type="submit" name="Edit" value="Edit" class="btn btn-primary">
        </form>
    <?php elseif (isset($_GET['id'])): ?>
        <div class="alert alert-warning">No such cohort delicacy found.</div>
    <?php else: ?>
        <div class="alert alert-warning">No delicacy ID provided.</div>
    <?php endif; ?>
</div>

<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this delicacy? This action cannot be undone.');
}
</script>

<?php include('templates/footer.php'); ?>
</html>
