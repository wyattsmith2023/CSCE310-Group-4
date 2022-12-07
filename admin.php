<?php
  $user_id = $_GET['user_id'];

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
  
  $appointment_sql = "SELECT appointment.STUDENT_ID, appointment.LOCATION, availability.DAY, availability.START_TIME, availability.END_TIME \n"
    . "FROM appointment \n"
    . "JOIN availability ON appointment.AVAILABILITY_ID = availability.AVAILABILITY_ID \n"
    . "WHERE STUDENT_ID=$user_id\n";

  $appointments_list = $mysqli->query($appointment_sql);

  $appointment_detailed = "SELECT * from `admin_appointments`";
  $appointments_detailed_list = $mysqli->query($appointment_detailed);

  $users_detailed = "SELECT * FROM `user`";
  $users_detailed_list = $mysqli->query($users_detailed);

  $reviews_detailed = "SELECT `all_reviews`.*, CONCAT(F_NAME, ' ', L_NAME) AS STUDENT FROM `all_reviews` INNER JOIN `user` on STUDENT_ID = `user`.USER_ID";
  $reviews_detailed_list = $mysqli->query($reviews_detailed);

  // $profile_query = $mysqli->query(" SELECT USERNAME, PASSWORD, F_NAME, L_NAME, PHONE, EMAIL FROM `user` WHERE `USER_ID`=$user_id  ");
  $profile_query = "SELECT USERNAME, PASSWORD, F_NAME, L_NAME, PHONE, EMAIL\n" 
  . "FROM `user`\n" 
  . "WHERE `USER_ID`=$user_id";
  $profile = $mysqli->query($profile_query);

  //FUNCTIONS
  function update($table, $variable, $value, $where, $id){
    global $mysqli;
    $sql = "UPDATE $table SET $variable='$value' WHERE $where=$id";            
    $query = $mysqli->query($sql);
  }

  function edit_button($elem){
    echo "<button onclick=\"show('$elem');show('" . $elem . "_Entry');show('" . $elem . "_Submit');\">Edit</button><br>";
    echo "<form name = \"form\" action=\"\" method=\"post\">";
    echo "<label id=\"$elem\" for=\"$elem\" style=\"display:none\">$elem:</label>";
    echo "<input id=\"" . $elem . "_Entry\" name=\"$elem\" type=\"text\" style=\"display:none\">";
    echo "<input id=\"" . $elem . "_Submit\" type=\"submit\" style=\"display:none\">";
    echo "</form>";
  }

  function button_php($name, $column){
    global $user_id;
    global $_POST;
    if(isset($_POST[$name]) && !empty($_POST[$name])){
        update('user',$column,$_POST[$name], 'USER_ID', $user_id);
        header("Refresh:0");
    }
  }

  function drop($table, $where, $id){
    global $mysqli;
    $sql = "DELETE FROM $table WHERE $where = $id";
    $query = $mysqli->query($sql);
    header("Refresh:0");
  }

  function add($table,$columns,$values){
    global $mysqli;
    $sql = "INSERT INTO $table (";
    foreach($columns as $column){
        $sql .= $column . ", ";
    } 
    $sql = substr($sql, 0, -2);
    $sql .= ") VALUES (";

    foreach($values as $value){
        $sql .= $value . ", ";
    } 
    $sql = substr($sql, 0, -2);
    $sql .= ")";

    $query = $mysqli->query($sql);
    $_POST=array();

  }

  // function add_appointment($avail_num, $location) {
  //   global $mysqli;
  //   global $user_id;
  //   // Need help with this query, making it custom
  //   echo "<script>console.log("appointment clicked")</script>";
  //   $mysqli->query("INSERT INTO appointment (STUDENT_ID, APPOINTMENT_ID, TUTOR_ID, SUBJECT_ID, AVAILABILITY_ID, LOCATION) VALUES ('1','999', '1', '1', '1', 'Norway');");
  // }

?>

<!DOCTYPE html>
<head>
    <style> h2 { color: green; }</style>
</head>
<html>
    <body>
    <h2>Update Users: </h2>
        <p>
            <?php
            echo "<table>";
            echo "<tr>";
            echo "<th>USER ID#</th>";
            echo "<th>USERNAME</th>";
            echo "<th>PASSWORD</th>";
            echo "<th>FIRST NAME</th>";
            echo "<th>LAST NAME</th>";
            echo "<th>PHONE</th>";
            echo "<th>EMAIL</th>";
            echo "<th>IS_STUDENT</th>";
            echo "<th>IS_TUTOR</th>";
            echo "<th>IS_ADMIN</th>";
            while($row = mysqli_fetch_array($users_detailed_list))
            {
                echo "<tr>";
                echo "<th>" . $row["USER_ID"] . "</th>";
                echo "<th>" . $row["USERNAME"] . "</th>";
                echo "<th>" . $row["PASSWORD"] . "</th>";
                echo "<th>" . $row["F_NAME"] . "</th>";
                echo "<th>" . $row["L_NAME"] . "</th>";
                echo "<th>" . $row["PHONE"] . "</th>";
                echo "<th>" . $row["EMAIl"] . "</th>";
                echo "<th>" . $row["IS_STUDENT"] . "</th>";
                echo "<th>" . $row["IS_ADMIN"] . "</th>";
                echo "<th>" . $row["IS_TUTOR"] . "</th>";
                echo "<th>" . $row["IS_ADMIN"] . "</th>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<form></form>";
        ?>
    <h2>Update Appointments: </h2>
        <p>
            <?php
            echo "<table>";
            echo "<tr>";
            echo "<th>APPT ID#</th>";
            echo "<th>STUDENT ID</th>";
            echo "<th>STUDENT</th>";
            echo "<th>TUTOR ID</th>";
            echo "<th>TUTOR</th>";
            echo "<th>AVAIL ID</th>";
            echo "<th>LOCATION</th>";
            echo "<th>DAY</th>";
            echo "<th>START TIME</th>";
            echo "<th>END TIME</th>";
            while($row = mysqli_fetch_array($appointments_detailed_list))
            {
                echo "<tr>";
                echo "<th>" . $row["APPOINTMENT_ID"] . "</th>";
                echo "<th>" . $row["STUDENT_ID"] . "</th>";
                echo "<th>" . $row["STUDENT"] . "</th>";
                echo "<th>" . $row["TUTOR_ID"] . "</th>";
                echo "<th>" . $row["TUTOR"] . "</th>";
                echo "<th>" . $row["AVAILABILITY_ID"] . "</th>";
                echo "<th>" . $row["LOCATION"] . "</th>";
                echo "<th>" . $row["DAY"] . "</th>";
                echo "<th>" . $row["START_TIME"] . "</th>";
                echo "<th>" . $row["END_TIME"] . "</th>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<form></form>";
        ?>
    <h2>Update Reviews: </h2>
        <p>
            <?php
            echo "<table>";
            echo "<tr>";
            echo "<th>REVIEW ID#</th>";
            echo "<th>STUDENT ID</th>";
            echo "<th>STUDENT</th>";
            echo "<th>TUTOR ID</th>";
            echo "<th>TUTOR</th>";
            echo "<th>COMMENT</th>";
            echo "<th>STARS</th>";
            echo "<th>TAGS</th>";
            echo "<th>DATE</th>";
            while($row = mysqli_fetch_array($reviews_detailed_list))
            {
                echo "<tr>";
                echo "<th>" . $row["REVIEW_ID"] . "</th>";
                echo "<th>" . $row["STUDENT_ID"] . "</th>";
                echo "<th>" . $row["STUDENT"] . "</th>";
                echo "<th>" . $row["TUTOR_ID"] . "</th>";
                echo "<th>" . $row["TUTOR"] . "</th>";
                echo "<th>" . $row["COMMENT"] . "</th>";
                echo "<th>" . $row["STARS"] . "</th>";
                echo "<th>" . $row["TAGS"] . "</th>";
                echo "<th>" . $row["DATE"] . "</th>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<form></form>";
        ?>
    </body>
</html>