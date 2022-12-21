<?php

  session_start();
  require "connection.php";
  require '../phpqrcode/qrlib.php';
  $conn = connect();

  // generate password
  function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 10; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }


  if (isset($_POST["add-employee"])) {
    
    $employee_id = str_pad(date('Y'), 10, RAND(), STR_PAD_RIGHT);
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $department = $_POST["department"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $password = randomPassword();
    $qrcode_value = password_hash($employee_id, PASSWORD_DEFAULT);

    // $qrcode
    $path = "generated-qrcodes/";
    $qrcode = $path.$employee_id.".png";
    
    QRcode::png($qrcode_value, $qrcode, QR_ECLEVEL_H, 25, 3);

    $logopath = "../img/logo/cvsu-logo.png";
    $QR = imagecreatefrompng($qrcode);

    // START TO DRAW THE IMAGE ON THE QR CODE
    $logo = imagecreatefromstring(file_get_contents($logopath));

    // Fix for the transparent background
    imagecolortransparent($logo , imagecolorallocatealpha($logo , 0, 0, 0, 127));
    imagealphablending($logo , false);
    imagesavealpha($logo , true);

    $QR_width = imagesx($QR);
    $QR_height = imagesy($QR);
    $logo_width = imagesx($logo);
    $logo_height = imagesy($logo);

    // Scale logo to fit in the QR Code
    $logo_qr_width = $QR_width / 5;
    $scale = $logo_width / $logo_qr_width;
    $logo_qr_height = $logo_height / $scale;

    imagecopyresampled($QR, $logo, $QR_width / 2.5, $QR_height / 2.5, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

    // Save QR code again, but with logo on it
    imagepng($QR, $qrcode);

    $sql_info = "INSERT INTO `employee_information`(`employee_id`, `firstname`, `lastname`, `department`, `email`, `gender`, `qrcode`, `qrcode_value`, `password`) 
                VALUES ('$employee_id', '$firstname', '$lastname', '$department', '$email', '$gender', '$qrcode', '$qrcode_value', '$password')";

    if (mysqli_query($conn, $sql_info)) {
      echo 
        "<script> 
          window.onload = () => {
            Swal.fire({
              icon: 'success',
              title: 'Added Employee',
              html: `<b class='text-uppercase'> </b> Saved!`,
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
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>