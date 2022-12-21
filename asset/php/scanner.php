<?php

  session_start();
  require "connection.php";
  $conn = connect();


  date_default_timezone_set("Asia/Manila");

  if (isset($_POST["qrcode-value"])) {

    $qrcode_value = $_POST["qrcode-value"];

    $late = "late";
    $ontime = "ontime";
    $complete = "complete";
    $incomplete = "incomplete";
    $today_date = date("Y-m-d");
    $day = date("l");

    $result = mysqli_query($conn, "SELECT * FROM `employee_information` WHERE `qrcode_value` = '$qrcode_value' ");
    $row = mysqli_fetch_assoc($result);

    $fullname = $row["firstname"] . ' ' . $row["lastname"];
    $info_id = $row["information_id"];

    $schedule = mysqli_query($conn, " SELECT * FROM `employee_schedule` WHERE `information_id` = '$info_id' AND `day` = '$day'"); 
    $schedule_row = mysqli_fetch_assoc($schedule);

    $time_min = $schedule_row["time_min"];
    $time_max = $schedule_row["time_max"];
    
    $current_time = date("H:i");

    // echo $time_min . ' ' . $time_max;
    // echo $current_time;

    if (mysqli_num_rows($result) > 0) { 

      // registered Employee
      $getAttendance = mysqli_query($conn, "SELECT * FROM employee_attendance WHERE `information_id` = '$info_id' ORDER BY `attendance_id` DESC LIMIT 1");
      $attendanceRow = mysqli_fetch_assoc($getAttendance);

      if (mysqli_num_rows($getAttendance) == 0) {
        // first time record
        if ($time_min > $current_time) {
          // ontime
          $insert_attendance = "INSERT INTO `employee_attendance`(`fullname`, `date`, `time_in`, `time_out`, `total_hour`, `status`, `information_id`) 
                                VALUES ('$fullname', '$today_date', '$current_time', '', '', '$ontime', '$info_id')";

          if (mysqli_query($conn, $insert_attendance)) {
            echo 
              "<script> 
                window.onload = () => {
                  Swal.fire({
                    icon: 'success',
                    title: 'Ontime',
                    html: `<b class='text-uppercase'> $fullname </b> Successfully Time In`,
                    timer: 3000,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true
                  }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                      location.href = 'admin.php';
                    }
                  });
                }; 
              </script>";
          }
        }
        else if ($time_min < $current_time) {
          // LATE
          // echo "late";
          $insert_attendance = "INSERT INTO `employee_attendance`(`fullname`, `date`, `time_in`, `time_out`, `total_hour`, `status`, `information_id`) 
                              VALUES ('$fullname', '$today_date', '$current_time', '', '', '$late', '$info_id')";

          if (mysqli_query($conn, $insert_attendance)) {
            echo 
              "<script> 
                window.onload = () => {
                  Swal.fire({
                    icon: 'success',
                    title: '',
                    html: `<b class='text-uppercase'> $fullname </b> Successfully Time In`,
                    timer: 3000,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true
                  }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                      location.href = 'admin.php';
                    }
                  });
                }; 
              </script>";
          }
        }

      }
      else {

      }

    }
    else {
      // not yet registered
      echo 
        "<script> 
          window.onload = () => {
            Swal.fire({
              icon: 'error',
              title: 'Qrcode Error',
              text: 'Qrcode Not Recognized',
              timer: '3000',
              showConfirmButton: false,
              allowOutsideClick: false,
              timerProgressBar: true
            }).then((result) => {
              if (result.dismiss === Swal.DismissReason.timer) {
                location.href = 'admin.php';
              }
            });
          }
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
  <!-- sweet alert -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  
</body>
</html>