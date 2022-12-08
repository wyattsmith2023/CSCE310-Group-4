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
	
//**********QUERIES**********//
  if ($mysqli->connect_error) {
    echo 'Errno: '.$mysqli->connect_errno;
    echo '<br>';
    echo 'Error: '.$mysqli->connect_error;
    exit();
  }
  
  $appointment_sql = "SELECT * FROM all_appointments WHERE STUDENT_ID=$user_id";

  $appointments_list = $mysqli->query($appointment_sql);

  $appointment_detailed = "SELECT availability.AVAILABILITY_ID, availability.DAY, availability.START_TIME, availability.END_TIME, user.F_NAME, user.L_NAME, tutor.AVG_RATING FROM availability JOIN user ON availability.TUTOR_ID = user.USER_ID JOIN tutor ON availability.TUTOR_ID = tutor.USER_ID";
  $appointments_detailed_list = $mysqli->query($appointment_detailed);

  $profile_query = "SELECT USERNAME, PASSWORD, F_NAME, L_NAME, PHONE, EMAIL\n" 
  . "FROM `user`\n" 
  . "WHERE `USER_ID`=$user_id";
  $profile = $mysqli->query($profile_query);

  $user_phone_numbers = $mysqli->query("SELECT USERNAME, PHONE FROM user");

  $all_subject = $mysqli->query("SELECT * FROM subject");

//**********FUNCTIONS**********//
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
    global $user_id;
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
    header("Location: /temp_stu.php?user_id=".$user_id);

  }

  function add_appointment($avail_num, $location, $subject_num) {
      global $mysqli;
      global $user_id;

      $tutor_query = "SELECT TUTOR_ID FROM availability WHERE availability.AVAILABILITY_ID = " . $avail_num . ";";
      $tutor_result = $mysqli->query($tutor_query);
      $tutor_row = $tutor_result->fetch_assoc();
      $tutor_id = $tutor_row['TUTOR_ID'];
      $added_appointment_query = "INSERT INTO appointment (STUDENT_ID, TUTOR_ID, SUBJECT_ID, AVAILABILITY_ID, LOCATION) VALUES (" . "'" . $user_id . "','" . $tutor_id . "','" . $subject_num . "','" . $avail_num . "','" . $location . "'" . ");";
      $added_appointment = $mysqli->query($added_appointment_query);
      $_POST = array();
  }

  function delete_review($review_id) {
    global $db_host, $db_user, $db_password, $db_db;
    $tag_bridge_del = "DELETE FROM tag_bridge WHERE REVIEW_ID=$review_id";
    $review_del = "DELETE FROM review WHERE REVIEW_ID=$review_id";

    $conn = new mysqli($db_host, $db_user, $db_password, $db_db);
    $conn->query($tag_bridge_del);
    $conn->query($review_del);
    
    $conn->close();
}

//**********POST Handlers**********//
  // POST -- Appointment
  $a_num_bool = isset($_POST['A_Num']) && !empty($_POST['A_Num']);
  $a_loc_bool = isset($_POST['A_Loc']) && !empty($_POST['A_Loc']);
  $s_num_bool = isset($_POST['S_Num']) && !empty($_POST['S_Num']);
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($a_num_bool && $a_loc_bool && $s_num_bool){ 
      $tutor_query = "SELECT TUTOR_ID FROM availability WHERE availability.AVAILABILITY_ID = " . $_POST['A_Num'] . ";";
      $tutor_result = $mysqli->query($tutor_query);
      $tutor_row = $tutor_result->fetch_assoc();
      $tutor_id = $tutor_row['TUTOR_ID'];
      add("appointment", array("STUDENT_ID", "TUTOR_ID", "SUBJECT_ID", "AVAILABILITY_ID", "LOCATION"), array( "'".$user_id."'", "'".$tutor_id."'", "'".$_POST['S_Num']."'", "'".$_POST['A_Num']."'", "'".$_POST['A_Loc']."'"));
      header("Refresh:0");
    }
  }

  // POST -- Appointment
  if (isset($_POST['App_ID']) && !empty($_POST['App_ID'])){
    drop('appointment','APPOINTMENT_ID',$_POST['App_ID']);
    header("Refresh:0");
  }
  
  // POST -- Delete Account
  if(isset($_POST['Delete'])){
    global $mysqli;
    $sql = "SELECT REVIEW_ID FROM review WHERE TUTOR_ID =".$user_id." OR STUDENT_ID =".$user_id;
    $ids = $mysqli->query($sql);

    while($row = mysqli_fetch_array($ids)) {
        delete_review($row['REVIEW_ID']);
    }

    drop('appointment','STUDENT_ID', $user_id);
    drop('appointment','TUTOR_ID', $user_id);
    drop('class_bridge','TUTOR_ID', $user_id);
    drop('subject_bridge','TUTOR_ID', $user_id);
    drop('availability','TUTOR_ID', $user_id);
    drop('student', 'USER_ID', $user_id);
    drop('tutor', 'USER_ID', $user_id);
    drop('user','USER_ID', $user_id);

    header("Location: /index.php");
  }

  // POST -- Name
  $f_bool = isset($_POST['F_Name']) && !empty($_POST['F_Name']);
  $l_bool = isset($_POST['L_Name']) && !empty($_POST['L_Name']);
  if($f_bool || $l_bool){ 
      if($f_bool){
          update('user','F_NAME',$_POST['F_Name'], 'USER_ID', $user_id);
      }
      if($l_bool){
          update('user','L_NAME',$_POST['L_Name'], 'USER_ID', $user_id);
      }
      header("Refresh:0");
  }

?>


<!DOCTYPE html>
<html>
  
<head>
<style>
    h1 {
    color: green;
    }
</style>
<script>
    function show(id){
        elem = document.getElementById(id);
        elem.style.display = "inline"
    }
</script>
</head>
  
<body>
  <h1>Student Home</h1>
  <p><?php 
        $profile_info = $profile->fetch_array(MYSQLI_ASSOC);
        echo "<p><strong>Name: " . $profile_info["F_NAME"] . " " . $profile_info["L_NAME"] . "</p>";
        ?>
        <button onclick="show('F_Name');show('F_Name_Entry');show('L_Name');show('L_Name_Entry');show('Name_Submit');">Edit</button><br>

        <form name = "form" action="" method="post">
            <label id="F_Name" for="F_Name" style="display:none">First Name:</label>
            <input id="F_Name_Entry" name="F_Name" type="text" style="display:none"> 
            <label id="L_Name" for="L_Name" style="display:none">Last Name:</label>
            <input id="L_Name_Entry" name="L_Name" type="text" style="display:none"> 
            <input id="Name_Submit" type="submit" style="display:none">
        </form>

        <?php
        echo "<p><strong>Username: </strong> " . $profile_info["USERNAME"] . "</p>";
        edit_button("Username");
        button_php("Username", "USERNAME");

        echo "<p><strong>Password: </strong> " . $profile_info["PASSWORD"] . "</p>";
        edit_button("Password");
        button_php("Password", "Password");

        echo "<p><strong>Email: </strong> " . $profile_info["EMAIL"] . "</p>";
        edit_button("Email");
        button_php("Email", "EMAIL");

        echo "<p><strong>Phone: </strong>" . $profile_info["PHONE"] . "</p>";
        edit_button("Phone");
        button_php("Phone", "PHONE");

      ?>
  </p>
  <h1>Your Appointments</h1>
  <p><?php 
  

  echo "<table>";
  echo "<tr>";
  echo "<th>ID</th>";
  echo "<th>LOCATION</th>";
  echo "<th>DAY</th>";
  echo "<th>START TIME</th>";
  echo "<th>END TIME</th>";
  while($row = mysqli_fetch_array($appointments_list))
  {
    echo "<tr>";
    echo "<th>" . $row["APPOINTMENT_ID"] . "</th>";
    echo "<th>" . $row["LOCATION"] . "</th>";
    echo "<th>" . $row["DAY"] . "</th>";
    echo "<th>" . $row["START_TIME"] . "</th>";
    echo "<th>" . $row["END_TIME"] . "</th>";
    echo "</tr>";
  }
  echo "</table>";
  ?></p>

  <form name = "form" action="" method="post">
    <label id="App_ID" for="App_ID" >ID:</label>
    <input id="App_ID_Entry" name="App_ID" type="text"> 
    <input id="App_Delete" type="submit" value="Delete">
  </form>

  <h1>Tutor Search (by class)</h1>
  <button><a href=<?php echo "/search.php?user_id=".$user_id?>>SEARCH</a></button>

  <h1>Write a Review</h1>

  <p>
  <?php 
  $path = "/review.php?user_id=" . $user_id;
  echo "<button><a href='" . $path . "'>GO</a></button>";
  ?></p>

  <h1>Create Appointment</h1>
  <h2>Available appointments: </h2>
  <p><?php
    echo "<table>";
    echo "<tr>";
    echo "<th>AVAILABILITY #</th>";
    echo "<th>DAY</th>";
    echo "<th>START TIME</th>";
    echo "<th>END TIME</th>";
    echo "<th>TUTOR NAME</th>";
    echo "<th>RATING</th>";
    while($row = mysqli_fetch_array($appointments_detailed_list))
  {
    echo "<tr>";
    echo "<th>" . $row["AVAILABILITY_ID"] . "</th>";
    echo "<th>" . $row["DAY"] . "</th>";
    echo "<th>" . $row["START_TIME"] . "</th>";
    echo "<th>" . $row["END_TIME"] . "</th>";
    echo "<th>" . $row["F_NAME"] . " " . $row["L_NAME"] . "</th>";
    echo "<th>" . $row["AVG_RATING"] . "</th>";
    echo "</tr>";
  }
  echo "</table>";
  echo "<form></form>";
  ?>


</p>

<!-- Form for creating appointment -->
<form name = "form2" action="" method="post">
<label id="A_Num" for="A_Num">Availability #:</label>
<input id="A_Num_Entry" name="A_Num" type="text"> 
<label id="A_Loc" for="A_Loc">Location:</label>
<input id="A_Loc_Entry" name="A_Loc" type="text"> 
<?php
    echo "<label>Subject</label>";
    echo "<select name=\"S_Num\" size=\"6\">";
    foreach($all_subject as $subject)
        echo "<option value=".$subject['SUBJECT_ID'].">".$subject['NAME']."</option>";
    echo "</select>";
?>
<input type="submit">
</form>

<h1>Need more help?</h1>
<h2>We are a community. Here are people to reach out to if you need help!</h2>
<p><?php
    echo "<table>";
    echo "<tr>";
    echo "<th>USER</th>";
    echo "<th>PHONE NUMBER</th>";
    while($row = mysqli_fetch_array($user_phone_numbers))
  {
    echo "<tr>";
    echo "<th>" . $row["USERNAME"] . "</th>";
    echo "<th>" . $row["PHONE"] . "</th>";
    echo "</tr>";
  }
  echo "</table>";


  ?>
</p>

<p>
<h1 style="color:red">DELETE ACCOUNT</h1>
<button><a href=<?php echo "/select.php?user_id=".$user_id?>>Back To Select</a></button>

    <form name = "form" action="" method="post">
        <input type="submit" name="Delete" value="Delete Account">
    </form>
</p>

</body>
  
</html>

