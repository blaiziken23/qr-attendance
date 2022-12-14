<?php

  // require "connection.php";
  require "display-info.php";
  
  $conn = connect();
  $id = $_GET["id"];

  $mysched = mysqli_query($conn, "SELECT * FROM `employee_schedule` WHERE `information_id` = '$id'");
  // // echo json_encode(mysqli_fetch_all($mysched, MYSQLI_ASSOC));

  exit(json_encode(mysqli_fetch_all($mysched, MYSQLI_ASSOC)));

?>