<?php

  function connect() {
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";

    $conn = mysqli_connect($servername, $username, $password);

    // Create Database
    $create_db = "CREATE DATABASE IF NOT EXISTS $dbname";

    if (mysqli_query($conn, $create_db)) {
      
      // Choose default database
      mysqli_select_db($conn, $dbname);

      // Create admin_account Table
      $admin_account = "CREATE TABLE IF NOT EXISTS 
        admin_account (
          admin_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          firstname VARCHAR(50) NOT NULL,
          lastname VARCHAR(50) NOT NULL,
          position VARCHAR(50) NOT NULL,
          username VARCHAR(30) NOT NULL,
          password VARCHAR(255) NOT NULL,  
          reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

      mysqli_query($conn, $admin_account) or die("Creating Table admin_account failed" . mysqli_connect_error());

      // create employee_attendance Table
      $employee_attendance = "CREATE TABLE IF NOT EXISTS 
        employee_attendance (
          attendance_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          fullname VARCHAR(130) NOT NULL,
          date VARCHAR(30) NOT NULL,
          time_in VARCHAR(30) NOT NULL,
          time_out VARCHAR(30) NOT NULL,
          total_hour VARCHAR(30) NOT NULL,
          status VARCHAR(10) NOT NULL,
          in_and_out VARCHAR(3) NOT NULL,
          information_id INT(6) NOT NULL
        )";

      mysqli_query($conn, $employee_attendance) or die("Creating Table employee_attendance failed" . mysqli_connect_error());

      // create employee_sched Table
      $employee_sched = "CREATE TABLE IF NOT EXISTS
        employee_schedule (
          schedule_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          day VARCHAR(30) NOT NULL,
          time_min VARCHAR(10) NOT NULL,
          time_max VARCHAR(10) NOT NULL,
          total_hour VARCHAR(10) NOT NULL,
          information_id INT(6) NOT NULL
        )";

      mysqli_query($conn, $employee_sched) or die("Creating Table employee_sched failed" . mysqli_connect_error());

      // create employee_info Table
      $employee_info = "CREATE TABLE IF NOT EXISTS
        employee_information (
          information_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          employee_id INT(10) NOT NULL,
          firstname VARCHAR(50) NOT NULL,
          lastname VARCHAR(50) NOT NULL,
          department VARCHAR(150) NOT NULL,
          email VARCHAR(150) NOT NULL,
          mobile_number VARCHAR(30) NOT NULL,
          gender VARCHAR(10) NOT NULL,
          qrcode VARCHAR(255) NOT NULL,
          qrcode_value VARCHAR(255) NOT NULL,
          password VARCHAR(255) NOT NULL,
          reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

      mysqli_query($conn, $employee_info) or die("Creating Table employee_info failed" . mysqli_connect_error());

    } 
    else {
      echo "Error creating database: " . mysqli_error($conn);
    }

    // check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    else {
      return $conn;
    }
  }

?>