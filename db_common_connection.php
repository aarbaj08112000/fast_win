<?php
$conn = mysqli_connect('localhost','root','Root@12345678','fastwin');

if (!$conn) {
  echo "Error: " . mysqli_connect_error();
  exit();
}
 ?>
