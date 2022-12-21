<?php

  require "../connection.php";
  $conn = connect();

  $attendance_list = "SELECT 
                employee_attendance.fullname, 
                employee_attendance.date, 
                employee_information.employee_id, 
                employee_attendance.time_in,
                employee_attendance.time_out,
                employee_attendance.total_hour,
                employee_attendance.status
              FROM 
                employee_attendance
              INNER JOIN 
                employee_information
              ON 
                employee_information.information_id = employee_attendance.information_id
              ORDER BY 
                employee_attendance.attendance_id DESC";

  $sql = mysqli_query($conn, $attendance_list) or die(mysqli_error());
  exit(json_encode(mysqli_fetch_all($sql, MYSQLI_ASSOC)));

?>