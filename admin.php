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
    echo $sql;
    //header("Refresh:0");
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

  function delete_review($review_id) {
    global $db_host, $db_user, $db_password, $db_db;
    $tag_bridge_del = "DELETE FROM `tag_bridge` WHERE `REVIEW_ID`=$review_id";
    $review_del = "DELETE FROM `review` WHERE `REVIEW_ID`=$review_id";

    $conn = new mysqli($db_host, $db_user, $db_password, $db_db);
    $conn->query($tag_bridge_del);
    $conn->query($review_del);
    
    $conn->close();
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
<script>
    function show(id){
        elem = document.getElementById(id);
        elem.style.display = "inline"
    }
</script>
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
                echo "<th>" . $row["EMAIL"] . "</th>";
                echo "<th>" . $row["IS_STUDENT"] . "</th>";
                echo "<th>" . $row["IS_TUTOR"] . "</th>";
                echo "<th>" . $row["IS_ADMIN"] . "</th>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<form></form>";
        ?>
    <button onclick="show('User_ID');show('User_ID_Entry');show('Username');show('Username_Entry');show('Password');show('Password_Entry');show('First_Name');show('First_Name_Entry');show('Last_Name');show('Last_Name_Entry');show('Phone');show('Phone_Entry');show('Email');show('Email_Entry');show('Is_Student');show('Is_Student_Entry');show('Is_Tutor');show('Is_Tutor_Entry');show('Is_Admin');show('Is_Admin_Entry');show('User_Edit');">Edit</button>
    <button onclick="show('Username');show('Username_Entry');show('Password');show('Password_Entry');show('First_Name');show('First_Name_Entry');show('Last_Name');show('Last_Name_Entry');show('Phone');show('Phone_Entry');show('Email');show('Email_Entry');show('Is_Student');show('Is_Student_Entry');show('Is_Tutor');show('Is_Tutor_Entry');show('Is_Admin');show('Is_Admin_Entry');show('User_Add');">Add</button>
    <button onclick="show('User_ID');show('User_ID_Entry');show('User_Delete');">Delete</button>
        <form name = "form" action="" method="post">
            <label id="User_ID" for="User_ID" style="display:none">ID:</label>
            <input id="User_ID_Entry" name="User_ID" type="text" style="display:none"> 
            <label id="Username" for="Username" style="display:none">Username:</label>
            <input id="Username_Entry" name="Username" type="text" style="display:none"> 
            <label id="Password" for="Password" style="display:none">Password:</label>
            <input id="Password_Entry" name="Password" type="text" style="display:none"> 
            <label id="First_Name" for="First_Name" style="display:none">First Name:</label>
            <input id="First_Name_Entry" name="First_Name" type="text" style="display:none">
            <label id="Last_Name" for="Last_Name" style="display:none">Last Name:</label>
            <input id="Last_Name_Entry" name="Last_Name" type="text" style="display:none">  
            <label id="Phone" for="Phone" style="display:none">Phone:</label>
            <input id="Phone_Entry" name="Phone" type="text" style="display:none">
            <label id="Email" for="Email" style="display:none">Email:</label>
            <input id="Email_Entry" name="Email" type="text" style="display:none">
            <label id="Is_Student" for="Is_Student" style="display:none">Is Student:</label>
            <input id="Is_Student_Entry" name="Is_Student" type="text" style="display:none">
            <label id="Is_Tutor" for="Is_Tutor" style="display:none">Is Tutor:</label>
            <input id="Is_Tutor_Entry" name="Is_Tutor" type="text" style="display:none">
            <label id="Is_Admin" for="Is_Admin" style="display:none">Is Admin:</label>
            <input id="Is_Admin_Entry" name="Is_Admin" type="text" style="display:none">
            <input id="User_Edit" name="User_Edit" type="submit" style="display:none">
            <input id="User_Add" name="User_Add" type="submit" style="display:none">
            <input id="User_Delete" name="User_Delete" type="submit" style="display:none">
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['User_Edit'])){
                if(isset($_POST['User_ID']) && !empty($_POST['User_ID'])){
                    $id = $_POST['User_ID'];
                    if(isset($_POST['Username']) && !empty($_POST['Username'])){
                        update('user','USERNAME',$_POST['Username'],'USER_ID',$id);
                    }
                    if(isset($_POST['Password']) && !empty($_POST['Password'])){
                        update('user','PASSWORD',$_POST['Password'],'USER_ID',$id);
                    }
                    if(isset($_POST['First_Name']) && !empty($_POST['First_Name'])){
                        update('user','F_NAME',$_POST['First_Name'],'USER_ID',$id);
                    }
                    if(isset($_POST['Last_Name']) && !empty($_POST['Last_Name'])){
                        update('user','L_NAME',$_POST['Last_Name'],'USER_ID',$id);
                    }
                    if(isset($_POST['Email']) && !empty($_POST['Email'])){
                        update('user','EMAIL',$_POST['Email'],'USER_ID',$id);
                    }
                    if(isset($_POST['Phone']) && !empty($_POST['Phone'])){
                        update('user','PHONE',$_POST['Phone'],'USER_ID',$id);
                    }
                    if(isset($_POST['Is_Student']) && !empty($_POST['Is_Student'])){
                        update('user','IS_STUDENT',$_POST['Is_Student'],'USER_ID',$id);
                    }
                    if(isset($_POST['Is_Tutor']) && !empty($_POST['Is_Tutor'])){
                        update('user','IS_TUTOR',$_POST['Is_Tutor'],'USER_ID',$id);
                    }
                    if(isset($_POST['Is_Admin']) && !empty($_POST['Is_Admin'])){
                        update('user','IS_ADMIN',$_POST['Is_Admin'],'USER_ID',$id);
                    }
                    
                }
                    header("Refresh:0");
            }
            else if(isset($_POST['User_Add'])){
                if(isset($_POST['Username']) && isset($_POST['Password']) && isset($_POST['First_Name']) && isset($_POST['Last_Name']) && isset($_POST['Phone']) && isset($_POST['Email']) && isset($_POST['Is_Student']) && isset($_POST['Is_Tutor']) && isset($_POST['Is_Admin'])){
                    add('user', array('USERNAME','PASSWORD','F_NAME','L_NAME','PHONE','EMAIL','IS_STUDENT','IS_TUTOR','IS_ADMIN'), array("'".$_POST['Username']."'", "'".$_POST['Password']."'", "'".$_POST['First_Name']."'", "'".$_POST['Last_Name']."'", "'".$_POST['Phone']."'", "'".$_POST['Email']."'","'".$_POST['Is_Student']."'", "'".$_POST['Is_Tutor']."'", "'".$_POST['Is_Admin']."'"));
                    header("Refresh:0");
                }
            }
            else if(isset($_POST['User_Delete'])){    
                if(isset($_POST['User_ID'])){
                    global $mysqli;
                    $sql = "SELECT REVIEW_ID FROM `review` WHERE TUTOR_ID =".$_POST['User_ID']." OR STUDENT_ID =".$_POST['User_ID'];
                    $ids = $mysqli->query($sql);

                    while($row = mysqli_fetch_array($ids)) {
                        delete_review($row['REVIEW_ID']);
                    }

                    drop('appointment','STUDENT_ID', $_POST['User_ID']);
                    drop('appointment','TUTOR_ID', $_POST['User_ID']);
                    drop('class_bridge','TUTOR_ID', $_POST['User_ID']);
                    drop('subject_bridge','TUTOR_ID', $_POST['User_ID']);
                    drop('availability','TUTOR_ID', $_POST['User_ID']);
                    drop('student', 'USER_ID', $_POST['User_ID']);
                    drop('tutor', 'USER_ID', $_POST['User_ID']);
                    drop('user','USER_ID', $_POST['User_ID']);
                }
            }
        }
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
        <button><a href=<?php echo "/select.php?user_id=".$user_id?>>Back</a></button>
    </body>
</html>