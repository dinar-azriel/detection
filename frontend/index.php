<?php
session_start();
if (isset($_SESSION['token'])) {
  header("Location: views/dashboard.php");
} else {
  header("Location: views/login.php");
}
exit;
