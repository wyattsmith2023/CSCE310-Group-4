<?php
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
	
  // QUERIES
  if ($mysqli->connect_error) {
    echo 'Errno: '.$mysqli->connect_errno;
    echo '<br>';
    echo 'Error: '.$mysqli->connect_error;
    exit();
  }
  
  $appointments_list = $mysqli->query(" SELECT appointment.STUDENT_ID, appointment.LOCATION, availability.DAY, availability.START_TIME, availability.END_TIME FROM appointment JOIN availability ON appointment.APPOINTMENT_ID = availability.AVAILABILITY_ID ORDER BY availability.DAY  ");


  $profile_query = $mysqli->query(" SELECT USERNAME, PASSWORD, F_NAME, L_NAME, PHONE, EMAIL FROM `user` WHERE `USER_ID`=1  ");
?>


<!DOCTYPE html>
<html>
  
<head>
  <style>
    h1 {
      color: green;
    }
  </style>
</head>
  
<body>
  <h1>Student Home</h1>
  <p><?php 
        $profile_info = $profile_query->fetch_array(MYSQLI_ASSOC);
        echo "<p><strong>Howdy, " . $profile_info["F_NAME"] . " " . $profile_info["L_NAME"] . "</p>";
        echo "<h3><strong><i>Profile Info</i></strong></h3>";
        echo "<p><strong>Username: </strong> " . $profile_info["USERNAME"] . "</p>";
        echo "<p><strong>Password: </strong> " . $profile_info["PASSWORD"] . "</p>";
        echo "<p><strong>Email: </strong> " . $profile_info["EMAIL"] . "</p>";
        echo "<p><strong>Phone: </strong>" . $profile_info["PHONE"] . "</p>";
      ?>
  </p>
  <h1>Your Appointments</h1>
  <p><?php 
  

  echo "<table>";
  echo "<tr>";
  echo "<th>LOCATION</th>";
  echo "<th>DAY</th>";
  echo "<th>START TIME</th>";
  echo "<th>END TIME</th>";
  while($row = mysqli_fetch_array($appointments_list))
  {
    echo "<tr>";
    echo "<th>" . $row["LOCATION"] . "</th>";
    echo "<th>" . $row["DAY"] . "</th>";
    echo "<th>" . $row["START_TIME"] . "</th>";
    echo "<th>" . $row["END_TIME"] . "</th>";
    echo "</tr>";
  }
  echo "</table>";

  
  ?></p>

  <h1>Tutor Search</h1>
  <button><a href="http://landonpalmer.com">SEARCH</a></button>
</body>
  
</html>

