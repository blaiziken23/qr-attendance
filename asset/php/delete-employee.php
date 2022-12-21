<?php

  require "connection.php";
  $conn = connect();
  $id = $_REQUEST["id"];

  $info = mysqli_query($conn, "SELECT * FROM `employee_schedule` WHERE `information_id` = '$id'");

  if (mysqli_num_rows($info) == 0) {

    $one_table = "DELETE FROM `employee_information` WHERE `information_id` = '$id'";
    
    if (mysqli_query($conn, $one_table)) {
      echo "
        <script> 
          window.onload = () => {
            Swal.fire({
              icon: 'success',
              title: 'Deleted',
              text: 'Employee Deleted',
              timer: '1500',
              showConfirmButton: false,
              allowOutsideClick: false,
              timerProgressBar: true
            }).then((result) => {
              if (result.dismiss === Swal.DismissReason.timer) {
                location.href = 'admin.php#employee';
              }
            });
          };  
        </script>";
    }

  }
  else {
    $more_table = "DELETE employee_information, employee_schedule
                  FROM employee_information
                  INNER JOIN employee_schedule 
                  ON employee_information.information_id = employee_schedule.information_id
                  WHERE employee_information.information_id = '$id'";

    if (mysqli_query($conn, $more_table)) {
      echo "
        <script> 
          window.onload = () => {
            Swal.fire({
              icon: 'success',
              title: 'Deleted',
              text: 'Employee Deleted',
              timer: '1500',
              showConfirmButton: false,
              allowOutsideClick: false,
              timerProgressBar: true
            }).then((result) => {
              if (result.dismiss === Swal.DismissReason.timer) {
                location.href = 'admin.php#employee';
              }
            });
          };  
        </script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Employee</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>