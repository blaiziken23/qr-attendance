<?php

  session_start();
  require "connection.php";
  $conn = connect();

  $id = $_REQUEST["id"];
  $query = mysqli_query($conn, "SELECT * FROM `employee_information` WHERE `information_id` = '$id' ");
  $row = mysqli_fetch_array($query);

  if (isset($_POST["update-employee"])) {

    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $department = $_POST["department"];
    $gender = $_POST["gender"];

    $sql = "UPDATE `employee_information` 
            SET `firstname` = '$firstname',
                `lastname` = '$lastname',
                `department` = '$department',
                `email` = '$email',
                `gender` = '$gender'
            WHERE `information_id` = '$id'";
    if (mysqli_query($conn, $sql)) {
      echo "<script> alert('Sucess'); location.href = 'display-info.php?id=$id'; </script>";
    }
    else {
      die(mysqli_connect_error());
    }

  } 

  // add schedule
  if (isset($_POST["add-sched"])) {

    $day = $_POST["day"];
    $time_in = $_POST["time-in"];
    $time_out = $_POST["time-out"];

    $sql = "INSERT INTO `employee_schedule`(`day`, `time_min`, `time_max`, `total_hour`, `information_id`) 
            VALUES ('$day', '$time_in', '$time_out', TIME_FORMAT(TIMEDIFF(`time_max`, `time_min`), '%H: %i'), '$id')";

    if (mysqli_query($conn, $sql)) {
      echo "<script> alert('Sucess added sched'); location.href = 'display-info.php?id=$id'; </script>";
    }
    else {
      die(mysqli_connect_error());
    }

  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Information</title>
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>


  <div class="container">
    <nav class="navbar py-3 mb-3">
      <div class="container-fluid">
        <h3 class="mb-0">Employee Information</h3>
        <a href="admin.php#employee" class="btn btn-sm btn-warning"><i class="bi bi-backspace"></i></a>
        <!-- <a href="admin.php#employee" class="btn-close" aria-label="Close"></a> -->
      </div>
    </nav>
    <div class="row">
      <div class="col-md-2">
        <div class="card">
          <img src="<?php echo $row["qrcode"] ?>" class="card-img-top p-1 rounded-4" alt="<?php echo $row["firstname"] ?> Qrcode">
        </div>
        <div class="card-body text-center">
          <a href="<?php echo $row["qrcode"] ?>" class="btn btn-sm" download> <i class="bi bi-box-arrow-down me-1"></i>Download</a>
        </div>
      </div>
      <div class="col-md-4">
        <form action="" method="post">
          <table class="table table-sm">
            <tbody>
              <tr>
                <th>EMP ID</th>
                <td>
                  <input type="text" class="form-control form-control-sm" value="<?php echo $row["employee_id"]; ?>" readonly>
                </td>
              </tr>
              <tr>
                <th>First Name</th>
                <td>
                  <input type="text" class="form-control form-control-sm" name="firstname" value="<?php echo $row["firstname"]; ?>">
                </td>
              </tr>
              <tr>
                <th>Last Name</th>
                <td>
                  <input type="text" class="form-control form-control-sm" name="lastname" value="<?php echo $row["lastname"]; ?>">
                </td>
              </tr>
              <tr>
                <th>Email</th>
                <td>
                  <input type="email" class="form-control form-control-sm" name="email" value="<?php echo $row["email"]; ?>">
                </td>
              </tr>
              <tr>
                <th>Department</th>
                <td>
                  <select class="form-select mb-3 form-select-sm" name="department">
                    <option>Select Department</option>
                    <option value="BSIT" <?php if($row["department"] == "BSIT") echo 'selected'; ?>>Information Technology Department</option>
                    <option value="BSBM" <?php if($row["department"] == "BSBM") echo 'selected'; ?>>Business Management Department</option>
                    <option value="BSOA" <?php if($row["department"] == "BSOA") echo 'selected'; ?>>Office Administration Department</option>
                    <option value="BSHM" <?php if($row["department"] == "BSHM") echo 'selected'; ?>>Hotel Management Department</option>
                    <option value="BSTM" <?php if($row["department"] == "BSTM") echo 'selected'; ?>>Tourism Management Department</option>
                    <option value="BSE" <?php if($row["department"] == "BSE") echo 'selected'; ?>>Secondary Education - Major in English Department</option>
                    <option value="BSP" <?php if($row["department"] == "BSP") echo 'selected'; ?>>Psychology Department</option>
                    <option value="others" <?php if($row["department"] == "others") echo 'selected'; ?>>Others</option>
                  </select>
                </td>
              </tr>
              <tr>
                <th>Gender</th>
                <td>
                  <select class="form-select mb-3 form-select-sm" name="gender">
                    <option>Gender</option>
                    <option value="male"  <?php if($row["gender"] == "male") echo 'selected'; ?>>Male</option>
                    <option value="female"  <?php if($row["gender"] == "female") echo 'selected'; ?>>Female</option>
                    <option value="others"  <?php if($row["gender"] == "others") echo 'selected'; ?>>Prefer Not to Say</option>
                  </select>
                </td>
              </tr> 
            </tbody>
          </table>
          <div class="d-flex justify-content-end mb-2">
            <input type="submit" class="btn btn-success " name="update-employee" value="Update">
          </div>
        </form>
      </div>
      <div class="col-md">
        <div class="d-flex justify-content-between py-2">
            <h4>Schedule</h4>
            <div>
              <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-sched"><i class="bi bi-person-add me-1"></i>Add Schedule</button>
            </div> 
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Days</th>
                <th>Time in</th>
                <th>Time out</th>
                <th>Total Hour</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sched = mysqli_query($conn, "SELECT * FROM `employee_schedule` WHERE `information_id` = '$id'");
                while ($row = mysqli_fetch_array($sched)) {
                    
                  $days = $row["day"];
                  $time_min = $row["time_min"];
                  $time_max = $row["time_max"];
                  $total_hour = $row["total_hour"];
                  // echo json_encode($row);
              ?>
              <tr>
                <td> <?php echo $days ?> </td>
                <td> <?php echo $time_min ?> </td>
                <td> <?php echo $time_max ?> </td>
                <td> <?php echo $total_hour ?> </td>
                <td>
                  <a class='btn btn-sm btn-warning'> <i class='bi bi-pencil-square'></i></a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
      </div>
    </div>
  </div>

  <!-- schedule modal -->
  <div class="modal fade" id="add-sched" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <form action="" method="post">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Schedule</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <div class="container">
                  <select class="form-select mb-2" name="day" required>
                    <option>Select days</option>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                    <option value="saturday">Saturday</option>
                    <option value="sunday">Sunday</option>
                  </select>
                  <label for="in">Time in</label>
                  <input type="time" class="form-control mb-3" name="time-in" id="in" required />
                  <label for="out">Time out</label>
                  <input type="time" class="form-control mb-3" name="time-out" id="out"  required />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="add-sched">Add Schedule</button>
          </div>
        </form> 
      </div>
    </div>
  </div>






<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
