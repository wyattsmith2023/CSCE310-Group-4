<?php

$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'tutor_app';
$db_port = 8889;

$conn = new mysqli(
  $db_host,
  $db_user,
  $db_password,
  $db_db
);

if ($conn->connect_error) 
{
  echo 'Errno: '.$conn->connect_errno;
  echo '<br>';
  echo 'Error: '.$conn->connect_error;
  exit();
}

// echo 'Success: A proper connection to MySQL was made.';
// echo '<br>';
// echo 'Host information: '.$conn->host_info;
// echo '<br>';
// echo 'Protocol version: '.$conn->protocol_version;

?>