<form method="post">
  <input type="textbox" name="str" required/>
  <input type="submit" name="submit" value="Search"/>
</form>

<?php
$user_id = $_GET['user_id'];

include("db.php");

if (isset($_POST['submit'])) {
  $str = mysqli_real_escape_string($conn, $_POST['str']);
  // $sql = "SELECT tutor_classes.NAME AS 'CLASS_NAME', tutor_classes.CLASS_CODE, tutor_classes.CLASS_NUMBER, 
  // tutor_classes.TUTOR_ID, tutor_classes.CLASS_ID, tutor_subjects.* DSQ
  // FROM `tutor_subjects`, `tutor_classes`
  
  // WHERE tutor_classes.NAME LIKE '%$str%'
  // OR tutor_classes.CLASS_CODE LIKE '%$str%'
  // OR tutor_classes.CLASS_NUMBER LIKE'%$str%'
  // OR tutor_subjects.NAME LIKE '%$str%';";
  
  $sql = "SELECT tutor_classes.NAME AS 'CLASS_NAME', tutor_classes.CLASS_CODE, tutor_classes.CLASS_NUMBER, 
  tutor_classes.TUTOR_ID, tutor_classes.CLASS_ID, tutor_subjects_search.*, tutor.AVG_RATING
  FROM `tutor_subjects_search`, `tutor_classes`, tutor
  
  WHERE tutor_classes.NAME LIKE '%$str%'
  OR tutor_classes.CLASS_CODE LIKE '%$str%'
  OR tutor_classes.CLASS_NUMBER LIKE'%$str%'
  OR tutor_subjects_search.NAME LIKE '%$str%';";
  
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    while($row = ($result->fetch_assoc())) {
      echo "<br>Name: " . $row['F_NAME'] . " " .$row['L_NAME'] ."<br>Class Code: ". $row['CLASS_CODE'] . "<br>Class Number: " . $row['CLASS_NUMBER'] 
      . "<br>Email: " . $row['EMAIL'] . "<br>Classes: ". $row['CLASS_NAME'] . "<br>Subjects: ". $row['NAME'].  "<br>Average Rating: " . $row['AVG_RATING'] . "<br>";
    }
  } else {
    echo "<br>0 results";
  }
}

?>

<button><a href=<?php echo "/student.php?user_id=".$user_id?>>Back</a></button>