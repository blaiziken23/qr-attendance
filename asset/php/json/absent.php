<?php

  require "../connection.php";
  $conn = connect();

  $absent = "SELECT 
              CONCAT(employee_information.firstname, ' ' , employee_information.lastname) AS fullname,
              employee_attendance.date, 
              employee_information.employee_id, 
              employee_attendance.status
            FROM 
              employee_attendance
            INNER JOIN 
              employee_information
            ON 
              employee_information.information_id = employee_attendance.information_id
            WHERE 
              employee_attendance.status = 'absent' AND employee_attendance.date = CURRENT_DATE";
  
  $sql = mysqli_query($conn, $absent) or die(mysqli_error());
  exit(json_encode(mysqli_fetch_all($sql, MYSQLI_ASSOC)));
  
?>
