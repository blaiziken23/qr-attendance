<?php

  session_start();
  require "connection.php";
  $conn = connect();

  $id = $_SESSION["admin_id"];
  $result = mysqli_query($conn, "SELECT * FROM admin_account WHERE admin_id  = '$id' "); 
  $rows = mysqli_fetch_assoc($result);

  if ($_SESSION['logged_in'] == false) {
    header("Location: login.php");
  }

  date_default_timezone_set("Asia/Manila");

  $late = "late";
  $ontime = "ontime";
  $complete = "complete";
  $incomplete = "incomplete";
  $absent = "absent";
  $day = date("l");
  $today_date = date("Y-m-d");


  $attendance = mysqli_query($conn, "SELECT * FROM `employee_attendance` WHERE `date` = '$today_date'"); 
  $schedule_today = mysqli_query($conn, "SELECT * FROM `employee_schedule` WHERE `day` = '$day'");


  if (mysqli_num_rows($attendance) == 0) {
    $set_absent = "INSERT INTO employee_attendance (`status`, `fullname`, `date`, `information_id`)  
                    SELECT 
                      '$absent',
                      employee_information.firstname,
                      '$today_date',
                      employee_information.information_id
                    FROM 
                      `employee_information`
                    INNER JOIN 
                      `employee_schedule`
                    ON 
                      employee_information.information_id = employee_schedule.information_id
                    WHERE 
                      employee_schedule.day = '$day'";

    mysqli_query($conn, $set_absent) or die (mysqli_error());
    
  }
  else {
    // echo"<script> alert('Already Save!'); </script>";
  }

  if (isset($_POST["qrcode-value"])) {

    $qrcode_value = $_POST["qrcode-value"];

    $result = mysqli_query($conn, "SELECT * FROM `employee_information` WHERE `qrcode_value` = '$qrcode_value' ");
    $row = mysqli_fetch_assoc($result);
    
    $current_time = date("H:i");

    if (mysqli_num_rows($result) > 0) {   

      $fullname = $row["firstname"] . ' ' . $row["lastname"];
      $info_id = $row["information_id"];
  
      $schedule = mysqli_query($conn, "SELECT * FROM `employee_schedule` WHERE `information_id` = '$info_id' AND `day` = '$day'"); 
      $schedule_row = mysqli_fetch_assoc($schedule);

      if (mysqli_num_rows($schedule) == 0) { 
        echo
          "<script> 
            window.onload = () => {
              Swal.fire({
                icon: 'info',
                title: 'You Dont Have Schedule Today!',
                html: `<p> This means you dont have record schedule today from the database </p>`,
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
      else {
        $time_min = $schedule_row["time_min"];
        $time_max = $schedule_row["time_max"];

        // registered Employee
        $getAttendance = mysqli_query($conn, "SELECT * FROM employee_attendance WHERE `information_id` = '$info_id' ORDER BY `attendance_id` DESC LIMIT 1");
        $attendanceRow = mysqli_fetch_assoc($getAttendance);
  
        if ($attendanceRow["status"] == $absent) { 
  
          if ($time_min > $current_time) {

            $update_attendance = "UPDATE `employee_attendance` 
                                  SET `fullname` = '$fullname',
                                      `time_in` = '$current_time',
                                      `time_out` = '',
                                      `total_hour` = '',
                                      `status` = '$ontime',
                                      `in_and_out` = '0'
                                  WHERE `information_id` = '$info_id'
                                  ORDER BY `attendance_id`  
                                  DESC LIMIT 1";

            if (mysqli_query($conn, $update_attendance)) {
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
           
            $insert_attendance = "UPDATE `employee_attendance` 
                                  SET `fullname` = '$fullname',
                                      `time_in` = '$current_time',
                                      `time_out` = '',
                                      `total_hour` = '',
                                      `status` = '$late',
                                      `in_and_out` = '0'
                                  WHERE `information_id` = '$info_id'
                                  ORDER BY `attendance_id`  
                                  DESC LIMIT 1";



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
        else if ($attendanceRow["in_and_out"] == '0') {
  
          $update_attendance = "UPDATE `employee_attendance` 
                                SET `time_out` = '$current_time',
                                    `total_hour` = TIME_FORMAT(TIMEDIFF(`time_out`, `time_in`), '%H: %i'),
                                    `in_and_out`= '1'
                                WHERE `information_id` = '$info_id' 
                                ORDER BY `attendance_id`  
                                DESC LIMIT 1";
  
          if (mysqli_query($conn, $update_attendance)) {
            echo 
              "<script> 
                window.onload = () => {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    html: `<b class='text-uppercase'> $fullname </b> Successfully Time Out`,
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
        else if ($attendanceRow["in_and_out"] == '1' && $attendanceRow["date"] == $today_date) { 
          echo 
            "<script> 
              window.onload = () => {
                Swal.fire({
                  icon: 'info',
                  title: `Attendance Completed Today`,
                  text: `You're already Time in and Out Today`,
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
    }
    else { // not yet registered
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
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
  <!-- js -->
  <script src="../js/instascan/instascan.min.js"></script>
  <!-- sweet alert -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Dashboard</title>
</head>
<body>

  <!-- side nav -->
  <section class="shadow" id="sidenav">
    <div class="card mb-3">
      <img src="../img/logo/logo.png" class="card-img-top p-2" alt="">
      <div class="card-footer">
        <h6 class="card-title mb-0 text-center">Cavite State University General Trias</h6>
      </div>
    </div>
    <h5 class="text-center text-capitalize mb-3">Welcome <?php echo $rows["position"]; ?></h5>
    <hr>
    <div class="nav flex-column nav-pills w-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
      <button class="nav-link active" href="#dashboard" data-bs-toggle="pill" data-bs-target="#dashboard" type="button">
        <span>
          <i class="bi bi-speedometer2"></i>
          Dashboard
        </span>
      </button>
      <a class="nav-link" href="#monitor" type="button" data-bs-toggle="collapse" data-bs-target="#sub-monitor">
        <span>
          <i class="bi bi-tv"></i>
          Monitor
        </span>
        <div class="d-flex justify-content-end">
          <i class="bi bi-chevron-down"></i>
        </div>
      </a>
      <!-- sub monitor -->
      <div class="collapse" id="sub-monitor">
        <div class="ms-3 border-start border-3">
          <button class="nav-link w-100 ms-1" href="#present" data-bs-toggle="pill" data-bs-target="#present" type="button">
            <span class="">
              <i class="bi bi-building-add"></i>
              Present
            </span>
          </button>
          <button class="nav-link w-100 ms-1" href="#complete" data-bs-toggle="pill" data-bs-target="#complete" type="button">
            <span class="">
              <i class="bi bi-building-check"></i>
              Complete
            </span>
          </button>
          <button class="nav-link w-100 ms-1" href="#incomplete" data-bs-toggle="pill" data-bs-target="#incomplete" type="button">
            <span class="">
              <i class="bi bi-building-exclamation"></i>
              Incomplete
            </span>
          </button>
          <button class="nav-link w-100 ms-1" href="#absent" data-bs-toggle="pill" data-bs-target="#absent" type="button">
            <span class="">
              <i class="bi bi-building-fill-x"></i>
              Absent
            </span>
          </button>
          <button class="nav-link w-100 ms-1" href="#ontime" data-bs-toggle="pill" data-bs-target="#ontime" type="button">
            <span class="">
              <i class="bi bi-building"></i>
              Ontime
            </span>
          </button>
          <button class="nav-link w-100 ms-1" href="#late" data-bs-toggle="pill" data-bs-target="#late" type="button">
            <span class="">
              <i class="bi bi-building-fill-dash"></i>
              Late
            </span>
          </button>
        </div>
      </div>
      <button class="nav-link" href="#schedule" data-bs-toggle="pill" data-bs-target="#schedule" type="button">
        <span>
          <i class="bi bi-calendar"></i>
          Schedule
        </span>
      </button>
      <button class="nav-link" href="#employee" data-bs-toggle="pill" data-bs-target="#employee" type="button">
        <span>
          <i class="bi bi-person-lines-fill"></i>
          Employee
        </span>
      </button>
      <!-- <button class="nav-link" href="#profile" data-bs-toggle="pill" data-bs-target="#profile" type="button">
        <span>
          <i class="bi bi-person"></i>
          Profile
        </span>
      </button> -->
      <!-- <button class="nav-link" href="#v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button">
        <span>
          <i class="bi bi-chat-dots"></i>
          Messages
        </span>
      </button> -->
      <button class="nav-link" href="#attendance" data-bs-toggle="pill" data-bs-target="#attendance" type="button">
        <span>
          <i class="bi bi-gear"></i>
          Attendance
        </span>
      </button>
      <button class="nav-link" href="#report" data-bs-toggle="pill" data-bs-target="#report" type="button">
        <span>
          <i class="bi bi-save"></i>
          Attendance Report
        </span>
      </button>
    </div>
  </section>

  <!-- side nav content -->
  <div class="content" id="content">
    <nav class="navbar shadow">
      <div class="container-fluid"> 
        <button type="button" id="toggle-nav">
          <i class="bi bi-list m-0"></i>
        </button>
        <form class="d-flex" role="search">
          <div class="btn-group">
            <button class="btn btn-sm dropdown-toggle p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../img/images/default_profile.png" alt="" class="">
            </button>
            <ul class="dropdown-menu dropdown-menu-md-end">
              <li><a class="dropdown-item" href="admin.php?#attendance">
                <i class="bi bi-people"></i>
                Attendance
              </a> </li>
              <li><a class="dropdown-item" href="admin.php?#employee">Employee</a></li>
              <li><a class="dropdown-item" href="admin.php?#schedule">Schedule</a></li>
              <li><a class="dropdown-item" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                Logout
              </a></li>
            </ul>
          </div>
        </form>
      </div>
    </nav>
    <div class="tab-content">
      <div class="details pt-3 d-flex justify-content-between align-items-center">
        <div>
          <div class="d-flex " id="displayTime"></div>
          <div class="d-flex justify-content-end" id="getday"></div>
        </div>
        <button type="button" class="btn shadow-sm" data-bs-toggle="modal" data-bs-target="#qrcode" id="scan-qrcode">
          <i class="bi bi-qr-code-scan"></i>
          Scan
        </button>
      </div>
      <hr>
      
      <!-- content -->
      <!-- dashboard -->
      <div class="tab-pane fade show active" id="dashboard">
        <div class="row row-cols-lg-3">
          <div class="col-lg">
            <div class="card mb-3 shadow-sm">
              <div class="row g-0">
                <div class="col-md-4">
                  <i class="bi bi-building-add"></i>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">
                      Present
                    </h5>
                    <h5 class="card-text">
                      <?php
                        $sql = "SELECT * FROM `employee_attendance` WHERE `in_and_out` = '0' OR `in_and_out` = '1' AND  `date` = CURRENT_DATE";
                        $count = mysqli_query($conn, $sql); 
                        echo mysqli_num_rows($count);
                      ?>
                    </h5>
                    <div class="d-flex justify-content-end">
                      <a href="admin.php?#present" class="btn btn-sm">View more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg">
            <div class="card mb-3 shadow-sm">
              <div class="row g-0">
                <div class="col-md-4">
                  <i class="bi bi-building-check"></i>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">Complete</h5>
                    <h5 class="card-text">
                      <?php
                        $sql = "SELECT * FROM `employee_attendance` WHERE `date` = CURRENT_DATE AND `in_and_out` = '1' ";
                        $count = mysqli_query($conn, $sql);
                        echo mysqli_num_rows($count);
                      ?>
                    </h5>
                    <div class="d-flex justify-content-end">
                      <a href="admin.php?#complete" class="btn btn-sm ">View more</a>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </div>
          <div class="col-lg">
            <div class="card mb-3 shadow-sm">
              <div class="row g-0">
                <div class="col-md-4">
                  <i class="bi bi-building-exclamation"></i>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">Incomplete</h5>
                    <h5 class="card-text">
                      <?php
                        $sql = "SELECT * FROM `employee_attendance` WHERE `date` = CURRENT_DATE AND `in_and_out` = '0' ";
                        $count = mysqli_query($conn, $sql);
                        echo mysqli_num_rows($count);
                      ?>
                    </h5>
                    <div class="d-flex justify-content-end">
                      <a href="admin.php?#incomplete" class="btn btn-sm ">View more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg">
            <div class="card mb-3 shadow-sm">
              <div class="row g-0">
                <div class="col-md-4">
                  <i class="bi bi-building-fill-x"></i>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">
                      Absent
                    </h5>
                    <h5 class="card-text">
                      <?php
                        $sql = "SELECT * FROM `employee_attendance` WHERE `date` = CURRENT_DATE AND `status` = '$absent'";
                        $count = mysqli_query($conn, $sql);
                        echo mysqli_num_rows($count);
                      ?>
                    </h5>
                    <div class="d-flex justify-content-end">
                      <a href="admin.php?#absent" class="btn btn-sm">View more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg">
            <div class="card mb-3 shadow-sm">
              <div class="row g-0">
                <div class="col-md-4">
                  <i class="bi bi-building-fill-dash"></i>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">
                      Late
                    </h5>
                    <h5 class="card-text">
                      <?php
                        $sql = "SELECT * FROM `employee_attendance` WHERE `date` = CURRENT_DATE AND `status` = 'late'";
                        $count = mysqli_query($conn, $sql);
                        echo mysqli_num_rows($count);
                      ?>
                    </h5>
                    <div class="d-flex justify-content-end">
                      <a href="admin.php?#late" class="btn btn-sm">View more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg">
            <div class="card mb-3 shadow-sm">
              <div class="row g-0">
                <div class="col-md-4">
                  <i class="bi bi-building"></i>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">
                      Ontime
                    </h5>
                    <h5 class="card-text">
                      <?php
                        $sql = "SELECT * FROM `employee_attendance` WHERE `date` = CURRENT_DATE AND `status` = 'ontime'";
                        $count = mysqli_query($conn, $sql);
                        echo mysqli_num_rows($count);
                      ?>
                    </h5>
                    <div class="d-flex justify-content-end">
                      <a href="admin.php?#ontime" class="btn btn-sm">View more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- monitor -->
      <div class="tab-pane fade" id="monitor"> </div>
      <div class="tab-pane fade" id="attendance">
        <div class="d-flex justify-content-between mb-3">
          <h3>Attendance List</h3>
        </div>
        <div id="attendance-list-table"></div> 
      </div>
      <div class="tab-pane fade" id="report">
        <!-- <div id="report-table"></div>  -->
        <div class="d-flex justify-content-between mb-3">
          <h3>Attendance Report</h3>
           <form action="" method="POST" class="d-flex gap-2">
            <input type="text" class="form-control" placeholder="Name or Employee ID" name="id_or_name">
            <!-- <input type="text" class="form-control" placeholder="Status" name="status"> -->
            <input type="month" class="form-control" name="date" value="2022-01">
            <input type="submit" value="Filter" class="btn btn-success" name="filter" id="filter">
          </form>
        </div>
        <div>
          <?php

    
          ?>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-success mb-5" id="export">Export</button>
        </div>
      </div>
      <div class="tab-pane fade" id="schedule">
        <div class="">
          <h3>Today Schedule</h3>
          <div id="daily-sched" class="d-flex justify-content-center"></div>
        </div>
      </div>
      <div class="tab-pane fade" id="employee">
        <div class="d-flex justify-content-between">
          <h3>List of Employee</h3>
          <button type="button" class="btn shadow-sm" data-bs-toggle="modal" data-bs-target="#add-emp" id="add-employee">
            <i class="bi bi-person-add"></i>
            Add Employee
          </button>
        </div>  
        <div id="wrapper" class="d-flex justify-content-center"></div>
      </div>
      <!-- sub monitor tab -->
      <div class="tab-pane fade" id="present">
        <h5>Todays Present</h5>
        <div id="present-table"></div> 
      </div>
      <div class="tab-pane fade" id="complete">
        <h5>Complete</h5>
        <div id="complete-table"></div> 
      </div>
      <div class="tab-pane fade" id="incomplete">
        <h5>Incomplete</h5>
        <div id="incomplete-table"></div> 
      </div>
      <div class="tab-pane fade" id="absent">
        <h5>Absent</h5>
        <div id="absent-table"></div> 
      </div>
      <div class="tab-pane fade" id="late">
        <h5>Lates</h5>
        <div id="late-table"></div> 
      </div>
      <div class="tab-pane fade" id="ontime">
        <h5>Ontime</h5>
        <div id="ontime-table"></div>
      </div>

    </div>
  </div>

  <!-- qrocode modal -->
  <div class="modal fade" id="qrcode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Scan your Qr Code</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"  id="close"></button>
        </div>
        <div class="modal-body">
          <div class="container p-3">
            <div class="row">
              <div class="col">
                <video id="preview"></video>
              </div>
              <div class="col">
                <form action="" method="POST">
                  <input type="text" class="form-control" id="value" name="qrcode-value">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- add employee modal -->
  <div class="modal fade" id="add-emp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="add-employee.php" method="POST">
          <div class="modal-header">
            <h2 class="modal-title fs-5" id="staticBackdropLabel">
              <i class="bi bi-person-add"></i>
              Add Employee
            </h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"  id="close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col"> 
                <div>
                  <h4 class="mb-3 text-center">CvSU Gentri employee</h4>
                </div>
                <div class="row">
                  <div class="col">
                    <input type="text" class="form-control mb-3 form-control-sm" name="firstname" placeholder="First Name" autocomplete="off" required>
                  </div>
                  <div class="col">
                    <input type="text" class="form-control mb-3 form-control-sm" name="lastname" placeholder="Last Name" autocomplete="off" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <input type="email" class="form-control mb-3 form-control-sm" name="email" placeholder="Email" autocomplete="off" required>
                  </div>
                  <div class="col-4">
                    <select class="form-select mb-3 form-select-sm" name="gender" placeholder="gender" autocomplete="off" required>
                      <option>Gender</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <select class="form-select mb-3 form-select-sm" name="department" required>
                      <option>Select Department</option>
                      <option value="BSIT">Information Technology Department</option>
                      <option value="BSBM">Business Management Department</option>
                      <option value="BSOA">Office Administration Department</option>
                      <option value="BSHM">Hotel Management Department</option>
                      <option value="BSTM">Tourism Management Department</option>
                      <option value="BSE">Secondary Education - Major in English Department</option>
                      <option value="BSP">Psychology Department</option>
                      <option value="others">Others</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm btn-primary" name="add-employee">Add</button>
          </div>
        </form>
      </div>
    </div>
  </div>


<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
<script src="../js/bs-history/bs-history.js"></script>
<script src="../js/fetch/employee.js"></script>
<script src="../js/admin.js"></script>
</body>
</html>