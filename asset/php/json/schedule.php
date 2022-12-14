<?php

  require "../connection.php";
  $conn = connect();

  $day = date("l");

  $getsched = "SELECT CONCAT(employee_information.firstname, ' ', employee_information.lastname) AS fullname,
                employee_information.employee_id,
                employee_schedule.day, 
                employee_schedule.time_min, 
                employee_schedule.time_max
              FROM 
                employee_schedule
              INNER JOIN 
                employee_information 
              ON 
                employee_schedule.information_id = employee_information.information_id
              WHERE
                employee_schedule.day = '$day'";

  $sql = mysqli_query($conn, $getsched) or die(mysqli_error());
  exit(json_encode(mysqli_fetch_all($sql, MYSQLI_ASSOC)));

?>