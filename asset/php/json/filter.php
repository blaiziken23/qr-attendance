<?php

  session_start();
  require "../connection.php";
  $conn = connect();

  if (isset($_POST["filter"])) { 

    $name_or_id = $_POST["id_or_name"];
    $status = $_POST["status"];
    $date = $_POST["date"];

    $sql_filter = "SELECT 
                    employee_attendance.fullname, employee_attendance.date, employee_attendance.status, employee_information.employee_id
                  FROM 
                    employee_attendance
                  INNER JOIN 
                    employee_information 
                  ON 
                    employee_attendance.information_id = employee_information.information_id
                  WHERE
                    employee_attendance.status LIKE '%$status%'
                    OR
                    employee_attendance.date LIKE '%$date%'
                    OR
                    employee_attendance.fullname LIKE '%$name_or_id%' 
                    AND
                    employee_information.employee_id LIKE '%$name_or_id%'";

    $sql = mysqli_query($conn, $sql_filter) or die(mysqli_error());
    exit(json_encode(mysqli_fetch_all($sql, MYSQLI_ASSOC)));

  }

?>