<?php

  require "connection.php";
  $conn = connect();
  $sched_id = $_REQUEST["sched-id"];

  $info = mysqli_query($conn, "DELETE FROM `employee_schedule` WHERE `schedule_id` = '$sched_id'");

  if ($info) {
    echo "
      <script> 
        window.onload = () => {
          Swal.fire({
            icon: 'success',
            title: 'Deleted',
            text: 'Delete Schedule',
            timer: '1500',
            showConfirmButton: false,
            allowOutsideClick: false,
            timerProgressBar: true
          })
          setTimeout(() => {
            history.go(-1);
          }, 1500);
        };  
      </script>";
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Schedule</title>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="../css/style.css">

</head>
<body>
  
</body>
</html>