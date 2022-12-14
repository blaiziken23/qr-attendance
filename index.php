<?php

  session_start();
  require "asset/php/connection.php";
  $conn = connect();

  if (!empty($_SESSION["admin_id"])) {

    $id = $_SESSION["admin_id"];
    // $result = mysqli_query($conn, "SELECT * FROM employee_account WHERE account_id = '$id' "); 
    // $rows = mysqli_fetch_assoc($result);
    // foreach ($rows as $value) {
    //   echo $value, "\n";
    // }
    $_SESSION['logged_in'] = true;
    header("Location: asset/php/admin.php");

  }
  else {
    $_SESSION['logged_in'] = false;
    header("Location: asset/php/login.php");
  }

?>

