<?php

$serverName = "";

$dbUsr = "";
$dbPw = "";

$dbName = "";

$con = mysqli_connect($serverName, $dbUsr, $dbPw, $dbName);

if (!$con) {
  header("location: noconnection.htm");
  exit();
  die("Connection failed: " . mysqli_connect_error());
}
