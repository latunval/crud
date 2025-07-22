<?php
session_start();
$_SESSION['name'] = 'Interns';
$name = $_SESSION['name'] ?? 'Guest';
$gender = $_COOKIE['gender'] ?? 'Unknown';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cohort Delicacies</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style type="text/css">
    form {
      max-width: 460px;
      margin: 20px auto;
      padding: 20px;
    }

    .delicacy {
      width: 100px;
      margin: 40px auto -30px;
      display: block;
      position: relative;
      top: -30px;
    }

    /* Optional: Add hover effect */
    .card:hover {
      transform: translateY(-5px);
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .nav-bg {
      background-color: white;
      box-shadow: none;
    }
  </style>
</head>

<body class="bg-light">
  <nav class="navbar navbar-expand-lg nav-bg">
    <div class="container">
      <a href="index.php" class="navbar-brand">Cohort Delicacies</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <span class="nav-link text-secondary">
              Hello <?php echo htmlspecialchars($name); ?>
            </span>
          </li>
          <li class="nav-item">
            <span class="nav-link text-secondary">
              (<?php echo htmlspecialchars($gender); ?>)
            </span>
          </li>
          <li class="nav-item ms-2">
            <a href="add.php" class="btn btn-success text-white">Add a Delicacy</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>