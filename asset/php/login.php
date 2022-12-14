<?php

  session_start();
  require "connection.php";
  $conn = connect();

  if (isset($_POST["login"])) {
    
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM `admin_account` WHERE username = '$username' ");
    $row = mysqli_fetch_assoc($result);
    
    if (mysqli_num_rows($result) > 0) {
      if (password_verify($password, $row['password'])) {
        $_SESSION["logged_in"] = true;
        $_SESSION["admin_id"] = $row["admin_id"];
        echo 
          "<script> 
            window.onload = () => {
              Swal.fire({
                icon: 'success',
                title: 'Login Success',
                html: 'Redirecting...',
                timer: 3000,
                showConfirmButton: false,
                allowOutsideClick: false,
                timerProgressBar: true
              }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                  location.href = '/qr-attendance';
                }
              });
            }; 
          </script>";
      }
      else {
        echo 
          "<script> 
            window.onload = () => {
              Swal.fire({
                icon: 'error',
                title: 'Incorrect Credentials',
                text: 'Please check your Username and Password',
                confirmButtonText: 'Try Again!',
              }).then((result) => {
                if (result.isConfirmed) {
                  location.href = 'login.php';
                }
              });
            }; 
          </script>";
      }
    }
    else { 
      echo 
        "<script> 
          window.onload = () => {
            Swal.fire({
              icon: 'error',
              title: 'Opsss...',
              text: 'No record Found',
              confirmButtonText: 'Try Again!',
            }).then((result) => {
              if (result.isConfirmed) {
                location.href = 'login.php';
              }
            });
          }; 
        </script>";
    }
  }
  if ($_SESSION['logged_in'] == true) {
    header("Location: /qr-attendance");
  }  

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <!-- css -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/form.css">
  <title>Log in</title>
</head>
<body>



  <div class="container">
    <div class="row shadow rounded">
      <div class="col form-img">
        <img src="../img/images/login.svg" alt="">
      </div>
      <div class="col">
        <form action="" method="post" class="p-2">
          <h3 class="mb-3 text-center">LOGIN</h3>
          <input type="text" class="form-control mb-3" name="username" placeholder="Username" autocomplete="off" required>
          <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
          <div class="d-flex justify-content-center ">
            <button type="submit" class="btn btn-success w-100" name="login" id="login">Log in</button>
          </div>
          <div class="text-end py-3">
            <a href="signup.php" class="btn btn-sm">Create Account? </a>
          </div>
        </form>
      </div>
    </div>
  </div>


<!-- Sweet Alert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>