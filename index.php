<?php
if (isset($_GET['username'])){
  $username = $_GET['username'];
  $password = $_GET['password'];
  $error = FALSE;

  $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'tutor_app';
    $db_port = 8889;

    $mysqli = new mysqli(
      $db_host,
      $db_user,
      $db_password,
      $db_db
    );
    
    if ($mysqli->connect_error) {
      echo 'Errno: '.$mysqli->connect_errno;
      echo '<br>';
      echo 'Error: '.$mysqli->connect_error;
      exit();
    }

    $sql = "SELECT * FROM user WHERE USERNAME='$username'";    
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()) {
      if ($row["PASSWORD"] == $password){
        $user_id = $row["USER_ID"];
        header("Location: http://localhost:8888/select.php?user_id=$user_id");    
      }
      else {
        $error = TRUE;
      }
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Tutoring Serice</title>
    <style>
      h1 { color: green; }
    </style>
  </head>
  <body>

  <h1> Tutoring Service Login </h1>
  <form name = "form" action="" method="get">
    <label for="username">Username:</label> <br> 
    <input type="text" id="username" name="username"> <br>
    <label for="password">Password:</label> <br>
    <input type="text" id="password" name="password"> <br>
    <input type="submit" name="submit">
  </form>
  <?php
    if (isset($_GET['username']) && isset($_GET['password']) && $error = TRUE){
      echo "Wrong username and/or password";
    }
  ?>
  </body>
</html>