<form method="post">
  <input type="textbox" name="str" required/>
  <input type="submit" name="submit" value="Search"/>
</form>

<?php
include("db.php");

if (isset($_POST['submit'])) {
  $str = mysqli_real_escape_string($conn, $_POST['str']);
  $sql = "SELECT * FROM `class` WHERE `CLASS_CODE` LIKE '%$str%'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    while($row = ($result->fetch_assoc())) {
      echo "<br>Class Code: ". $row['CLASS_CODE'] . " Class Number: " . $row['CLASS_NUMBER'] . " Name: " . $row['NAME'];
  }
  }
  else {echo "<br>0 results";}
}

?>