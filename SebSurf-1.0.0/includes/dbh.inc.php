<?php

$serverName = "mysql.host";

$dbUsr = "MySQLUser";
$dbPw = "UserPassword";

$dbName = "NameOfDatabase";

$con = mysqli_connect($serverName, $dbUsr, $dbPw, $dbName);

if (!$con) {
  header("location: noconnection.htm");
  exit();
}
