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

  $profile_query = "SELECT USERNAME, PASSWORD, F_NAME, L_NAME, PHONE, EMAIL\n" 
    . "FROM `user`\n" 
    . "WHERE `USER_ID`=$user_id";
  $profile = $mysqli->query($profile_query);

  $availability_query = "SELECT AVAILABILITY_ID AS ID, DAY, START_TIME, END_TIME\n"
    . "FROM availability\n"
    . "WHERE TUTOR_ID=$user_id";
  $availabilty = $mysqli->query($availability_query);

  $subject_query = "SELECT SUBJECT_ID AS ID, NAME AS SUBJECT\n"
    . "FROM tutor_subjects\n"
    . "WHERE TUTOR_ID=$user_id";
  $subject = $mysqli->query($subject_query);

  $class_query = "SELECT CLASS_ID AS ID, CLASS_CODE, CLASS_NUMBER, NAME\n"
    . "FROM tutor_classes\n"
    . "WHERE TUTOR_ID=$user_id";
  $class = $mysqli->query($class_query);

  // Use of View : tutor_appointments
  $appointment_query = "SELECT AVAILABILITY_ID, APPOINTMENT_ID, TUTOR_ID, LOCATION, DAY, START_TIME, END_TIME\n"
    . "FROM tutor_appointments\n"
    . "WHERE TUTOR_ID=$user_id";
  $appointments = $mysqli->query($appointment_query);

  // Use of Index : review_rating
  $review_query = "SELECT STARS FROM review WHERE TUTOR_ID=$user_id";
  $review = $mysqli->query($review_query);

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

//**********POST Handler**********//
  //POST -- Delete Account
  if(isset($_POST['Delete'])){
    global $mysqli;
    $sql = "SELECT REVIEW_ID FROM `review` WHERE TUTOR_ID =".$user_id." OR STUDENT_ID =".$user_id;
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

    header("Location: /index.php");
  }

  //POST -- Name
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

  //POST -- Availability
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['Ava_Edit'])){
            if(isset($_POST['ID']) && !empty($_POST['ID'])){
                $id = $_POST['ID'];
                if(isset($_POST['Day']) && !empty($_POST['Day'])){
                    update('availability','DAY',$_POST['Day'],'AVAILABILITY_ID',$id);
                }
                if(isset($_POST['Start_Time']) && !empty($_POST['Start_Time'])){
                    update('availability','START_TIME',$_POST['Start_Time'],'AVAILABILITY_ID',$id);
                }
                if(isset($_POST['End_Time']) && !empty($_POST['End_Time'])){
                    update('availability','END_TIME',$_POST['End_Time'],'AVAILABILITY_ID',$id);
                }
                header("Refresh:0");
            }
        }
        else if(isset($_POST['Ava_Add'])){
            if(isset($_POST['Day']) && isset($_POST['Start_Time']) && isset($_POST['End_Time'])){
                add('availability', array('TUTOR_ID','DAY','START_TIME','END_TIME'), array($user_id,"'".$_POST['Day']."'", "'".$_POST['Start_Time']."'", "'".$_POST['End_Time']."'"));
                header("Location: /temp_tutor.php?user_id=".$user_id);
            }
        }
        else if(isset($_POST['Ava_Delete'])){    
            if(isset($_POST['ID'])){
                drop('availability','AVAILABILITY_ID',$_POST['ID']);
            }
        }
    }

    // POST -- Appointment
    if (isset($_POST['App_ID']) && !empty($_POST['App_ID'])){
        drop('appointment','APPOINTMENT_ID',$_POST['App_ID']);
        header("Refresh:0");
    }

    // POST -- Subject
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['Sub_Add'])){
            if(isset($_POST['Subject'])){
                add('subject', array('NAME'), array("'".$_POST['Subject']."'"));
                add('subject_bridge', array('TUTOR_ID','SUBJECT_ID'), array("'".$user_id."'","'".$mysqli->insert_id."'"));
                header("Location: /temp_tutor.php?user_id=".$user_id);
            }
        }
        else if(isset($_POST['Sub_Delete'])){    
            if(isset($_POST['Sub_ID'])){
                drop('subject','SUBJECT_ID',$_POST['Sub_ID']);
                $sub_id = $_POST['Sub_ID'];
                $sql = "DELETE FROM subject_bridge WHERE SUBJECT_ID = $sub_id AND TUTOR_ID = $user_id";
                $query = $mysqli->query($sql);
                header("Location: /temp_tutor.php?user_id=".$user_id);
            }
        }
    }

    // POST -- Class
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['Class_Add'])){
            if(isset($_POST['Code']) && isset($_POST['Number']) && isset($_POST['Name'])){
                add('class', array('CLASS_CODE','CLASS_NUMBER','NAME'), array("'".$_POST['Code']."'", "'".$_POST['Number']."'", "'".$_POST['Name']."'"));
                add('class_bridge', array('TUTOR_ID','CLASS_ID'), array("'".$user_id."'","'".$mysqli->insert_id."'"));
                header("Location: /temp_tutor.php?user_id=".$user_id);
            }
        }
        else if(isset($_POST['Class_Delete'])){    
            if(isset($_POST['Class_ID'])){
                drop('class','CLASS_ID',$_POST['Class_ID']);
                $class_id = $_POST['Class_ID'];
                $sql = "DELETE FROM class_bridge WHERE CLASS_ID = $class_id AND TUTOR_ID = $user_id";
                $query = $mysqli->query($sql);
                header("Refresh:0");
            }
        }
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
    <h1>Tutor Home</h1>
    <h1>Profile</h1>
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
    while($row = mysqli_fetch_array($appointments))
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
    ?>
    <form name = "form" action="" method="post">
            <label id="App_ID" for="App_ID" >ID:</label>
            <input id="App_ID_Entry" name="App_ID" type="text"> 
            <input id="App_Delete" type="submit" value="Delete">
    </form>
    </p>
    <h1>Your Availability</h1>
    <?php
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>DAY</th>";
    echo "<th>START TIME</th>";
    echo "<th>END_TIME</th>";
    while($row = mysqli_fetch_array($availabilty))
    {
        echo "<tr>";
        echo "<th>" . $row["ID"] . "</th>";
        echo "<th>" . $row["DAY"] . "</th>";
        echo "<th>" . $row["START_TIME"] . "</th>";
        echo "<th>" . $row["END_TIME"] . "</th>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <button onclick="show('ID');show('ID_Entry');show('Day');show('Day_Entry');show('Start_Time');show('Start_Time_Entry');show('End_Time');show('End_Time_Entry');show('Ava_Edit');">Edit</button>
    <button onclick="show('Day');show('Day_Entry');show('Start_Time');show('Start_Time_Entry');show('End_Time');show('End_Time_Entry');show('Ava_Add');">Add</button>
    <button onclick="show('ID');show('ID_Entry');show('Ava_Delete');">Delete</button>
        <form name = "form" action="" method="post">
            <label id="ID" for="ID" style="display:none">ID:</label>
            <input id="ID_Entry" name="ID" type="text" style="display:none"> 
            <label id="Day" for="Day" style="display:none">Day:</label>
            <input id="Day_Entry" name="Day" type="text" style="display:none"> 
            <label id="Start_Time" for="Start_Time" style="display:none">Start Time:</label>
            <input id="Start_Time_Entry" name="Start_Time" type="time" style="display:none"> 
            <label id="End_Time" for="End_Time" style="display:none">End Time:</label>
            <input id="End_Time_Entry" name="End_Time" type="time" style="display:none">  
            <input id="Ava_Edit" name="Ava_Edit" type="submit" style="display:none">
            <input id="Ava_Add" name="Ava_Add" type="submit" style="display:none">
            <input id="Ava_Delete" name="Ava_Delete" type="submit" style="display:none">
        </form>
    <h1>Your Subjects</h1>
    <?php
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Subject</th>";
    while($row = mysqli_fetch_array($subject))
    {
        echo "<tr>";
        echo "<th>" . $row["ID"] . "</th>";
        echo "<th>" . $row["SUBJECT"] . "</th>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <button onclick="show('Subject');show('Subject_Entry');show('Sub_Add');">Add</button>
    <button onclick="show('Sub_ID');show('Sub_ID_Entry');show('Sub_Delete');">Delete</button>
    <form name = "form" action="" method="post">
            <label id="Sub_ID" for="Sub_ID" style="display:none">ID:</label>
            <input id="Sub_ID_Entry" name="Sub_ID" type="text" style="display:none"> 
            <label id="Subject" for="Subject" style="display:none">Subject:</label>
            <input id="Subject_Entry" name="Subject" type="text" style="display:none"> 
            <input id="Sub_Add" name="Sub_Add" type="submit" style="display:none">
            <input id="Sub_Delete" name="Sub_Delete" type="submit" style="display:none">
        </form>
    
    <h1>Your Classes</h1>
    <?php
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>CODE</th>";
    echo "<th>NUMBER</th>";
    echo "<th>NAME</th>";
    while($row = mysqli_fetch_array($class))
    {
        echo "<tr>";
        echo "<th>" . $row["ID"] . "</th>";
        echo "<th>" . $row["CLASS_CODE"] . "</th>";
        echo "<th>" . $row["CLASS_NUMBER"] . "</th>";
        echo "<th>" . $row["NAME"] . "</th>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <button onclick="show('Code');show('Code_Entry');show('Number');show('Number_Entry');show('Name');show('Name_Entry');show('Class_Add');">Add</button>
    <button onclick="show('Class_ID');show('Class_ID_Entry');show('Class_Delete');">Delete</button>
    <form name = "form" action="" method="post">
            <label id="Class_ID" for="Class_ID" style="display:none">ID:</label>
            <input id="Class_ID_Entry" name="Class_ID" type="text" style="display:none"> 
            <label id="Code" for="Code" style="display:none">Class Code:</label>
            <input id="Code_Entry" name="Code" type="text" style="display:none">
            <label id="Number" for="Number" style="display:none">Class Number:</label>
            <input id="Number_Entry" name="Number" type="text" style="display:none">
            <label id="Name" for="Name" style="display:none">Name:</label>
            <input id="Name_Entry" name="Name" type="text" style="display:none">  
            <input id="Class_Add" name="Class_Add" type="submit" style="display:none">
            <input id="Class_Delete" name="Class_Delete" type="submit" style="display:none">
        </form>
    
    <h1>Your Ratings</h1>

    <?php
    $sum = 0;
    $total = 0;

    echo "<table>";
    echo "<tr>";
    echo "<th>Stars</th>";
    while($row = mysqli_fetch_array($review))
    {
        echo "<tr>";
        echo "<th>" . $row["STARS"] . "</th>";
        echo "</tr>";
        $sum += $row["STARS"];
        $total += 1;
    }
    echo "</table>";
    echo "Average Rating: ";
    if ($total > 0){
        echo $sum / $total;
    }
    ?>
    <br>
    <button><a href=<?php echo "/select.php?user_id=".$user_id?>>Back To Select</a></button>

    <form name = "form" action="" method="post">
        <input type="submit" name="Delete" value="Delete Account">
    </form>

</body>
</html>