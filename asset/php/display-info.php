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
      // echo "<script> alert('Sucess'); location.href = 'display-info.php?id=$id'; </script>";
      echo "<script> 
        window.onload = () => {
          Swal.fire({
            icon: 'success',
            title: 'Updated',
            text: 'Data Changed',
            confirmButtonText: 'Ok',
          }).then((result) => {
            if (result.isConfirmed) {
              location.href = 'display-info.php?id=$id';
            }
          });
        }; 
      </script>";
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
      echo "<script> 
        window.onload = () => {
          Swal.fire({
            icon: 'success',
            title: 'Added',
            text: 'Schedule Added',
            confirmButtonText: 'Try Again!',
          }).then((result) => {
            if (result.isConfirmed) {
              location.href = 'display-info.php?id=$id';
            }
          });
        }; 
      </script>";
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
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>


  <div class="container shadow-sm p-3">
    <nav class="navbar py-3 mb-3">
      <div class="container-fluid">
        <h3 class="mb-0">Employee Information</h3>
        <a href="admin.php#employee" class="btn btn btn-danger"><i class="bi bi-x-lg"></i></a>
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
          <table class="table table-sm" id="edit-info">
            <tbody>
              <tr>
                <th>EMP ID</th>
                <td>
                  <input type="text" class="form-control form-control-sm m-0" value="<?php echo $row["employee_id"]; ?>" readonly disabled>
                </td>
              </tr>
              <tr>
                <th>First Name</th>
                <td>
                  <input type="text" class="form-control form-control-sm m-0" name="firstname" value="<?php echo $row["firstname"]; ?>" autocomplete="off">
                </td>
              </tr>
              <tr>
                <th>Last Name</th>
                <td>
                  <input type="text" class="form-control form-control-sm m-0" name="lastname" value="<?php echo $row["lastname"]; ?>" autocomplete="off">
                </td>
              </tr>
              <tr>
                <th>Email</th>
                <td>
                  <input type="email" class="form-control form-control-sm m-0" name="email" value="<?php echo $row["email"]; ?>" autocomplete="off">
                </td>
              </tr>
              <tr>
                <th>Department</th>
                <td>
                  <select class="form-select form-select-sm" name="department">
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
                  <select class="form-select form-select-sm" name="gender">
                    <option value="">Gender</option>
                    <option value="male"  <?php if($row["gender"] == "male") echo 'selected'; ?>>Male</option>
                    <option value="female"  <?php if($row["gender"] == "female") echo 'selected'; ?>>Female</option>
                  </select>
                </td>
              </tr> 
            </tbody>
          </table>
          <div class="d-flex justify-content-end mb-2">
            <button type="submit" class="btn btn-success " name="update-employee"><i class="bi bi-save2 me-1"></i>Update</button>
          </div>
        </form>
      </div>
      <div class="col-md">
        <div class="d-flex justify-content-between">
          <h3>Schedule</h3>
            <div>
            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#add-sched"><i class="bi bi-file-earmark-plus me-1"></i>Add Schedule</button>
          </div> 
        </div>
        <table class="stripe hover cell-border" id="mysched">
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
                <a class='btn btn-sm btn-danger' onclick = "return confirm('Are you sure you want to delete this Schedule')" href='delete-sched.php?sched-id=<?php echo $row['schedule_id']; ?>'><i class='bi bi-trash3'></i></a>
              </td>
            </tr>

            <!-- Edit Schedule -->
            <div class="modal fade" id="edit-sched<?php echo $row["schedule_id"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form action="" method="post">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Schedule</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col">
                          <div class="container">
                            <select class="form-select mb-2" name="day" required>
                              <option>Select days</option>
                              <option value="monday" <?php if($row["day"] == "monday") echo 'selected'; ?>>Monday</option>
                              <option value="tuesday" <?php if($row["day"] == "tuesday") echo 'selected'; ?>>Tuesday</option>
                              <option value="wednesday" <?php if($row["day"] == "wednesday") echo 'selected'; ?>>Wednesday</option>
                              <option value="thursday" <?php if($row["day"] == "thursday") echo 'selected'; ?>>Thursday</option>
                              <option value="friday" <?php if($row["day"] == "friday") echo 'selected'; ?>>Friday</option>
                              <option value="saturday" <?php if($row["day"] == "saturday") echo 'selected'; ?>>Saturday</option>
                              <option value="sunday" <?php if($row["day"] == "sunday") echo 'selected'; ?>>Sunday</option>
                            </select>
                            <label for="in">Time in</label>
                            <input type="time" class="form-control mb-3" name="time-in" id="in" value="<?php echo $row["time_min"];  ?>" required />
                            <label for="out">Time out</label>
                            <input type="time" class="form-control mb-3" name="time-out" id="out" value="<?php echo $row["time_max"]; ?>"  required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" name="update-schedule">Save changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
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
            <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="bi bi-file-earmark-plus-fill me-1"></i>Add Schedule</h1>
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





<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<!-- Sweet Alert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script>
  $(document).ready( function () {
    $('#mysched').DataTable({
      "dom": 'rtip',
      ordering:  false
    });
  });
</script>
</body>
</html>
