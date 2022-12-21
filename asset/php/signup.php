<?php

  session_start();
  require "connection.php";
  $conn = connect();

  if (isset($_POST["sign-up"])) {

    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $position = $_POST["position"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $checkusername = mysqli_query($conn, "SELECT * FROM admin_account where username = '$username' ");
    $rows = mysqli_num_rows($checkusername);

    if ($rows > 0) {
      echo
        "<script> 
          window.onload = () => {
            Swal.fire({
              icon: 'error',
              title: 'Username Exists!',
              text: 'Please Choose another username',
              confirmButtonText: 'Try Again!'
            }).then((result) => {
              if (result.isConfirmed) {
                location.href = 'signup.php';
              }
            });
          }; 
        </script>";
    }
    else {

      $sql = "INSERT INTO `admin_account`(`firstname`, `lastname`, `position`, `username`, `password`) 
              VALUES ('$firstname', '$lastname', '$position', '$username', '$hashed_password')";
      
      if (mysqli_query($conn, $sql)) {
        echo
          "<script> 
            window.onload = () => {
              Swal.fire({
                icon: 'success',
                title: 'Account Created',
                text: 'You can now Login!',
                timer: '3000',
                showConfirmButton: false,
                allowOutsideClick: false,
                timerProgressBar: true
              }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                  location.href = 'login.php';
                }
              });
            }; 
          </script>";
      }
      else {
        echo "<script> alert('Account Not Created, Try Again'); </script>";
      }
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
  <!-- Sweet Alert -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- css -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/form.css">
  <title>Signup</title>
</head>
<body>



  <div class="container">
    <div class="row shadow-sm rounded">
      <div class="col form-img">
        <img src="../img/images/signup.svg" alt="">
      </div>
      <div class="col">
        <form action="" method="POST" class="p-2">
          <h3 class="mb-3 text-center">SIGNUP</h3>
          <input type="text" class="form-control mb-3" name="firstname" placeholder="Firstname" autocomplete="off" required>
          <input type="text" class="form-control mb-3" name="lastname" placeholder="Lastname" autocomplete="off" required>
          <select class="form-select mb-3" name="position" required>
            <option value="admin">Administrator</option>
            <option value="others">Others</option>
          </select>
          <input type="text" class="form-control mb-3" name="username" placeholder="Username" autocomplete="off" required>
          <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
          <div class="d-flex justify-content-center ">
            <button type="submit" class="btn btn-success w-100" name="sign-up" id="sign-up">Sign up</button>
          </div>
          <div class="text-end pt-3">
            <a href="login.php" class="btn btn-sm">Login Account? </a>
          </div>
        </form>
      </div>
    </div>
  </div>


<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>