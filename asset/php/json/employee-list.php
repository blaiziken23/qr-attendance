<?php

  require "../connection.php";
  $conn = connect();

  $sql = mysqli_query($conn, "SELECT * FROM `employee_information`") or die(mysqli_error());
  exit(json_encode(mysqli_fetch_all($sql, MYSQLI_ASSOC)));


?>
