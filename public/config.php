<?php

$server = "localhost:3306";
$user = "root";
$pass = "";
$database = "cms_db";

$conn = mysqli_connect($server, $user, $pass, $database);

if (!$conn) {
  die("<script>alert('Connection Failed.')</script>");
}

?>
