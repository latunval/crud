<?php

try {
	// connect to the database
	$conn = mysqli_connect('localhost', 'cohort', 'cohort1234', 'cohort_db', 3307);

	// check connection
	if (!$conn) {
		throw new Exception('Connection error: ' . mysqli_connect_error());
	}    // If connection is successful
	echo '<div style="color: green; padding: 10px; border: 1px solid green; border-radius: 5px; margin: 10px; background-color: #e8f5e9;">
            <strong>✓ Success!</strong> Successfully connected to the database.
          </div>';

	error_log("Database connection established successfully");
} catch (mysqli_sql_exception $e) {
	// Handle MySQL specific errors
	error_log("MySQL Error: " . $e->getMessage());
	echo '<div style="color: red; padding: 10px; border: 1px solid red; border-radius: 5px; margin: 10px; background-color: #ffebee;">
            <strong>✕ Error!</strong> ' . $e->getMessage() . '
          </div>';
	exit();
} catch (Exception $e) {
	// Handle other types of errors
	error_log("General Error: " . $e->getMessage());
	echo '<div style="color: red; padding: 10px; border: 1px solid red; border-radius: 5px; margin: 10px; background-color: #ffebee;">
            <strong>✕ Error!</strong> ' . $e->getMessage() . '
          </div>';
	exit();
} finally {
	// This block will always execute
	if (isset($conn) && $conn) {
		// mysqli_close($conn);
	}
}
