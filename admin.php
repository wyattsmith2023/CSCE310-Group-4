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

  $appointment_detailed = "SELECT * from `admin_appointments`";
  $appointments_detailed_list = $mysqli->query($appointment_detailed);

  $users_detailed = "SELECT * FROM `user`";
  $users_detailed_list = $mysqli->query($users_detailed);

  $reviews_detailed = "SELECT `all_reviews`.*, CONCAT(F_NAME, ' ', L_NAME) AS STUDENT FROM `all_reviews` INNER JOIN `user` on STUDENT_ID = `user`.USER_ID";
  $reviews_detailed_list = $mysqli->query($reviews_detailed);

  $all_subject = $mysqli->query("SELECT * FROM subject");
  $all_tags = $mysqli->query("SELECT * FROM tag");
  $all_classes = $mysqli->query("SELECT * FROM class");

  // $profile_query = $mysqli->query(" SELECT USERNAME, PASSWORD, F_NAME, L_NAME, PHONE, EMAIL FROM `user` WHERE `USER_ID`=$user_id  ");
  $profile_query = "SELECT USERNAME, PASSWORD, F_NAME, L_NAME, PHONE, EMAIL\n" 
  . "FROM `user`\n" 
  . "WHERE `USER_ID`=$user_id";
  $profile = $mysqli->query($profile_query);

  //FUNCTIONS
  function update($table, $variable, $value, $where, $id){
    global $mysqli;
    $sql = "UPDATE $table SET $variable='$value' WHERE $where=$id"; 
    $mysqli->query($sql);
  }

  function update_num($table, $variable, $value, $where, $id){
    global $mysqli;
    $sql = "UPDATE $table SET $variable=$value WHERE $where=$id";        
    $mysqli->query($sql);
    
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
    $mysqli->query($sql);
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

    //echo $sql;

    $query = $mysqli->query($sql);
    $_POST=array();

  }

  function delete_review($review_id) {
    global $db_host, $db_user, $db_password, $db_db;
    $tag_bridge_del = "DELETE FROM `tag_bridge` WHERE `REVIEW_ID`=$review_id";
    $review_del = "DELETE FROM `review` WHERE `REVIEW_ID`=$review_id";

    echo $tag_bridge_del;
    echo $review_del;

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
            echo "<th>USER ID</th>";
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
                    if(isset($_POST['Is_Student'])){
                        update('user','IS_STUDENT',$_POST['Is_Student'],'USER_ID',$id);
                    }
                    if(isset($_POST['Is_Tutor'])){
                        update('user','IS_TUTOR',$_POST['Is_Tutor'],'USER_ID',$id);
                    }
                    if(isset($_POST['Is_Admin'])){
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
            echo "<th>APPT ID</th>";
            echo "<th>STUDENT ID</th>";
            echo "<th>STUDENT</th>";
            echo "<th>TUTOR ID</th>";
            echo "<th>TUTOR</th>";
            echo "<th>SUBJECT ID</th>";
            echo "<th>SUBJECT</th>";
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
                echo "<th>" . $row["SUBJECT_ID"] . "</th>";
                echo "<th>" . $row["SUBJECT"] . "</th>";
                echo "<th>" . $row["AVAILABILITY_ID"] . "</th>";
                echo "<th>" . $row["LOCATION"] . "</th>";
                echo "<th>" . $row["DAY"] . "</th>";
                echo "<th>" . $row["START_TIME"] . "</th>";
                echo "<th>" . $row["END_TIME"] . "</th>";
                echo "</tr>";
            }
            echo "</table>";
        ?>
        <button onclick="show('Appointment_ID');show('Appointment_ID_Entry');show('Student_ID');show('Student_ID_Entry');show('Tutor_ID');show('Tutor_ID_Entry');show('Subject_ID');show('Subject_ID_Entry');show('Availability_ID');show('Availability_ID_Entry');show('Location');show('Location_Entry');show('Appointment_Edit');">Edit</button>
        <button onclick="show('Student_ID');show('Student_ID_Entry');show('Tutor_ID');show('Tutor_ID_Entry');show('Availability_ID');show('Availability_ID_Entry');show('Subject_ID');show('Subject_ID_Entry');show('Location');show('Location_Entry');show('Appointment_Add');">Add</button>
        <button onclick="show('Appointment_ID');show('Appointment_ID_Entry');show('Appointment_Delete');">Delete</button>
        <form name = "form" action="" method="post">
            <label id="Appointment_ID" for="Appointment_ID" style="display:none">ID:</label>
            <input id="Appointment_ID_Entry" name="Appointment_ID" type="text" style="display:none"> 
            <label id="Student_ID" for="Student_ID" style="display:none">Student ID:</label>
            <input id="Student_ID_Entry" name="Student_ID" type="text" style="display:none"> 
            <label id="Tutor_ID" for="Tutor_ID" style="display:none">Tutor ID:</label>
            <input id="Tutor_ID_Entry" name="Tutor_ID" type="text" style="display:none">
            <label id="Availability_ID" for="Availability_ID" style="display:none">Availability ID:</label>
            <input id="Availability_ID_Entry" name="Availability_ID" type="text" style="display:none">
            <label id="Subject_ID" for="Subject_ID" style="display:none">Subject:</label>
            <?php
                echo "<select id=\"Subject_ID_Entry\" name=\"Subject_ID\"style=\"display:none\" size=\"6\">";
                foreach($all_subject as $subject)
                    echo "<option value=".$subject['SUBJECT_ID'].">".$subject['NAME']."</option>";
                echo "</select>";
            ?> 
            <label id="Location" for="Location" style="display:none">Location:</label>
            <input id="Location_Entry" name="Location" type="text" style="display:none">
            <input id="Appointment_Edit" name="Appointment_Edit" type="submit" style="display:none">
            <input id="Appointment_Add" name="Appointment_Add" type="submit" style="display:none">
            <input id="Appointment_Delete" name="Appointment_Delete" type="submit" style="display:none">
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['Appointment_Edit'])){
                if(isset($_POST['Appointment_ID']) && !empty($_POST['Appointment_ID'])){
                    $id = $_POST['Appointment_ID'];
                    if(isset($_POST['Student_ID']) && !empty($_POST['Student_ID'])){
                        update('appointment','STUDENT_ID',$_POST['Student_ID'],'APPOINTMENT_ID',$id);
                    }
                    if(isset($_POST['Tutor_ID']) && !empty($_POST['Tutor_ID'])){
                        update('appointment','TUTOR_ID',$_POST['Tutor_ID'],'APPOINTMENT_ID',$id);
                    }
                    if(isset($_POST['Location']) && !empty($_POST['Location'])){
                        update('appointment','LOCATION',$_POST['Location'],'APPOINTMENT_ID',$id);
                    }
                    if(isset($_POST['Subject_ID']) && !empty($_POST['Subject_ID'])){
                        update('appointment','SUBJECT_ID',$_POST['Subject_ID'],'APPOINTMENT_ID',$id);
                    }
                    if(isset($_POST['Availability_ID']) && !empty($_POST['Availability_ID'])){
                        update('appointment','AVAILABILITY_ID',$_POST['Availability_ID'],'APPOINTMENT_ID',$id);
                    }           
                }

                header("Refresh:0");
            }
            else if(isset($_POST['Appointment_Add'])){
                if(isset($_POST['Student_ID']) && isset($_POST['Tutor_ID']) && isset($_POST['Availability_ID']) && isset($_POST['Subject_ID']) && isset($_POST['Location'])){
                    add('appointment', array('STUDENT_ID','TUTOR_ID','AVAILABILITY_ID','SUBJECT_ID', 'LOCATION'), array("'".$_POST['Student_ID']."'", "'".$_POST['Tutor_ID']."'", "'".$_POST['Availability_ID']."'", "'".$_POST['Subject_ID']."'", "'".$_POST['Location']."'"));
                    header("Refresh:0");
                }
            }
            else if(isset($_POST['Appointment_Delete'])){    
                if(isset($_POST['Appointment_ID'])){
                    drop('appointment', 'APPOINTMENT_ID', $_POST['APPOINTMENT_ID']);
                }
            }
        }
        ?>
    <h2>Update Reviews: </h2>
        <p>
        <?php
            echo "<table>";
            echo "<tr>";
            echo "<th>REVIEW ID</th>";
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
        <button onclick="show('Review_ID');show('Review_ID_Entry');show('Student_ID');show('Student_ID_Entry');show('Tutor_ID');show('Tutor_ID_Entry');show('Comment');show('Comment_Entry');show('Stars');show('Stars_Entry');show('Review_Edit');">Edit</button>
        <button onclick="show('Student_ID');show('Student_ID_Entry');show('Tutor_ID');show('Tutor_ID_Entry');show('Comment');show('Comment_Entry');show('Stars');show('Stars_Entry');show('Review_Add');">Add</button>
        <button onclick="show('Review_ID');show('Review_ID_Entry');show('Review_Delete');">Delete</button>
        <form name = "form" action="" method="post">
            <label id="Review_ID" for="Review_ID" style="display:none">ID:</label>
            <input id="Review_ID_Entry" name="Review_ID" type="text" style="display:none"> 
            <label id="Student_ID" for="Student_ID" style="display:none">Student ID:</label>
            <input id="Student_ID_Entry" name="Student_ID" type="text" style="display:none"> 
            <label id="Tutor_ID" for="Tutor_ID" style="display:none">Tutor ID:</label>
            <input id="Tutor_ID_Entry" name="Tutor_ID" type="text" style="display:none"> 
            <label id="Comment" for="Comment" style="display:none">Comment:</label>
            <input id="Comment_Entry" name="Comment" type="text" style="display:none">
            <label id="Stars" for="Stars" style="display:none">Stars:</label>
            <input id="Stars_Entry" name="Stars" type="text" style="display:none">
            <input id="Review_Edit" name="Review_Edit" type="submit" style="display:none">
            <input id="Review_Add" name="Review_Add" type="submit" style="display:none">
            <input id="Review_Delete" name="Review_Delete" type="submit" style="display:none">
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['Review_Edit'])){
                if(isset($_POST['Review_ID']) && !empty($_POST['Review_ID'])){
                    $id = $_POST['Review_ID'];
                    if(isset($_POST['Student_ID']) && !empty($_POST['Student_ID'])){
                        update('review','STUDENT_ID',$_POST['Student_ID'],'REVIEW_ID',$id);
                    }
                    if(isset($_POST['Tutor_ID']) && !empty($_POST['Tutor_ID'])){
                        update('review','TUTOR_ID',$_POST['Tutor_ID'],'REVIEW_ID',$id);
                    }
                    if(isset($_POST['Comment']) && !empty($_POST['Comment'])){
                        update('review','COMMENT',$_POST['Comment'],'REVIEW_ID',$id);
                    }
                    if(isset($_POST['Stars']) && !empty($_POST['Stars'])){
                        update('review','STARS',$_POST['Stars'],'REVIEW_ID',$id);
                    }                 
                }

                header("Refresh:0");
            }
            else if(isset($_POST['Review_Add'])){
                if(isset($_POST['Student_ID']) && isset($_POST['Tutor_ID']) && isset($_POST['Comment']) && isset($_POST['Stars'])){
                    add('review', array('STUDENT_ID','TUTOR_ID','COMMENT','STARS', 'DATE'), array("'".$_POST['Student_ID']."'", "'".$_POST['Tutor_ID']."'", "'".$_POST['Comment']."'", "'".$_POST['Stars']."'", "'".date('Y-m-d')."'"));
                    header("Refresh:0");
                }
            }
            else if(isset($_POST['Review_Delete'])){    
                if(isset($_POST['Review_ID'])){
                    drop('tag_bridge', 'REVIEW_ID', $_POST['Review_ID']);
                    drop('review', 'REVIEW_ID', $_POST['Review_ID']);
                }
            }
        }
        ?>
        <h2>Update Tags: </h2>
        <p>
            <?php
            echo "<table>";
            echo "<tr>";
            echo "<th>TAG ID</th>";
            echo "<th>TAG NAME</th>";
            while($row = mysqli_fetch_array($all_tags))
            {
                echo "<tr>";
                echo "<th>" . $row["TAG_ID"] . "</th>";
                echo "<th>" . $row["NAME"] . "</th>";
                echo "</tr>";
            }
            echo "</table>";
        ?>
        <button onclick="show('Tag_ID');show('Tag_ID_Entry');show('Name');show('Name_Entry');show('Tag_Edit');">Edit</button>
        <button onclick="show('Name');show('Name_Entry');show('Tag_Add');">Add</button>        
        <button onclick="show('Tag_ID');show('Tag_ID_Entry');show('Tag_Delete');">Delete</button>
        <form name = "form" action="" method="post">
            <label id="Tag_ID" for="Tag_ID" style="display:none">ID:</label>
            <input id="Tag_ID_Entry" name="Tag_ID" type="text" style="display:none"> 
            <label id="Name" for="Name" style="display:none">Name:</label>
            <input id="Name_Entry" name="Name" type="text" style="display:none"> 
            <input id="Tag_Edit" name="Tag_Edit" type="submit" style="display:none">
            <input id="Tag_Add" name="Tag_Add" type="submit" style="display:none">
            <input id="Tag_Delete" name="Tag_Delete" type="submit" style="display:none">
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['Tag_Edit'])){
                if(isset($_POST['Tag_ID']) && !empty($_POST['Tag_ID'])){
                    $id = $_POST['Tag_ID'];
                    if(isset($_POST['Name']) && !empty($_POST['Name'])){
                        update('tag','NAME',$_POST['Name'],'TAG_ID',$id);
                    }             
                }
                header("Refresh:0");
            }
            else if(isset($_POST['Tag_Add'])){
                if(isset($_POST['Name'])){
                    add('tag', array('NAME'), array("'".$_POST['Name']."'"));
                    header("Refresh:0");
                }
            }
            else if(isset($_POST['Tag_Delete'])){    
                if(isset($_POST['Tag_ID'])){
                    drop('tag_bridge', 'TAG_ID', $_POST['Tag_ID']);
                    drop('tag', 'TAG_ID', $_POST['Tag_ID']);
                }
            }
        }
        ?>
        <h2>Update Classes: </h2>
        <p>
        <?php
            echo "<table>";
            echo "<tr>";
            echo "<th>CLASS ID</th>";
            echo "<th>CLASS CODE</th>";
            echo "<th>CLASS NUMBER</th>";
            echo "<th>NAME</th>";
            while($row = mysqli_fetch_array($all_classes))
            {
                echo "<tr>";
                echo "<th>" . $row["CLASS_ID"] . "</th>";
                echo "<th>" . $row["CLASS_CODE"] . "</th>";
                echo "<th>" . $row["CLASS_NUMBER"] . "</th>";
                echo "<th>" . $row["NAME"] . "</th>";
                echo "</tr>";
            }
            echo "</table>";
        ?>
        <button onclick="show('Class_ID');show('Class_ID_Entry');show('Class_Code');show('Class_Code_Entry');show('Class_Number');show('Class_Number_Entry');show('Class_Name');show('Class_Name_Entry');show('Class_Edit');">Edit</button>
        <button onclick="show('Class_Code');show('Class_Code_Entry');show('Class_Number');show('Class_Number_Entry');show('Class_Name');show('Class_Name_Entry');show('Class_Add');">Add</button>
        <button onclick="show('Class_ID');show('Class_ID_Entry');show('Class_Delete');">Delete</button>
        <form name = "form" action="" method="post">
            <label id="Class_ID" for="Class_ID" style="display:none">ID:</label>
            <input id="Class_ID_Entry" name="Class_ID" type="text" style="display:none"> 
            <label id="Class_Code" for="Class_Code" style="display:none">Class Code:</label>
            <input id="Class_Code_Entry" name="Class_Code" type="text" style="display:none">
            <label id="Class_Number" for="Class_Number" style="display:none">Class Number:</label>
            <input id="Class_Number_Entry" name="Class_Number" type="text" style="display:none"> 
            <label id="Class_Name" for="Class_Name" style="display:none">Name:</label>
            <input id="Class_Name_Entry" name="Class_Name" type="text" style="display:none"> 
            <input id="Class_Edit" name="Class_Edit" type="submit" style="display:none">
            <input id="Class_Add" name="Class_Add" type="submit" style="display:none">
            <input id="Class_Delete" name="Class_Delete" type="submit" style="display:none">
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['Class_Edit'])){
                if(isset($_POST['Class_ID']) && !empty($_POST['Class_ID'])){
                    $id = $_POST['Class_ID'];
                    if(isset($_POST['Class_Code']) && !empty($_POST['Class_Code'])){
                        update('class','CLASS_CODE',$_POST['Class_Code'],'CLASS_ID',$id);
                    }
                    if(isset($_POST['Class_Number']) && !empty($_POST['Class_Number'])){
                        update('class','CLASS_NUMBER',$_POST['Class_Number'],'CLASS_ID',$id);
                    }
                    if(isset($_POST['Class_Name']) && !empty($_POST['Class_Name'])){
                        update('class','NAME',$_POST['Class_Name'],'CLASS_ID',$id);
                    }        
                }
                header("Refresh:0");
            }
            else if(isset($_POST['Class_Add'])){
                if(isset($_POST['Class_Code']) && isset($_POST['Class_Number']) && isset($_POST['Class_Name'])){
                    add('class', array('CLASS_CODE', 'CLASS_NUMBER', 'NAME'), array("'".$_POST['Class_Code']."'", "'".$_POST['Class_Number']."'", "'".$_POST['Class_Name']."'"));
                    header("Refresh:0");
                }
            }
            else if(isset($_POST['Class_Delete'])){    
                if(isset($_POST['Class_ID'])){
                    drop('class_bridge', 'CLASS_ID', $_POST['Class_ID']);
                    drop('class', 'CLASS_ID', $_POST['Class_ID']);
                }
            }
        }
        ?>
        <button><a href=<?php echo "/select.php?user_id=".$user_id?>>Back</a></button>
    </body>
</html>