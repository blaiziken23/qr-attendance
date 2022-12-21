<?php

  require "../connection.php";
  $conn = connect();

  $present = "SELECT 
                employee_attendance.fullname, 
                employee_attendance.date, 
                employee_information.employee_id, 
                employee_attendance.time_in,
                employee_attendance.status
              FROM 
                employee_attendance
              INNER JOIN 
                employee_information
              ON 
                employee_information.information_id = employee_attendance.information_id
              WHERE 
                employee_attendance.date = CURRENT_DATE AND (employee_attendance.in_and_out = '0' OR employee_attendance.in_and_out = '1')";
  
  $sql = mysqli_query($conn, $present) or die(mysqli_error());
  exit(json_encode(mysqli_fetch_all($sql, MYSQLI_ASSOC)));
  
?> 