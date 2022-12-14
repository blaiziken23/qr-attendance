<?php

  session_start();
  unset($_SESSION["admin_id"]);
  unset($_SESSION["logged_in"]);
  header("Location: /qr-attendance");
  session_destroy();

?>