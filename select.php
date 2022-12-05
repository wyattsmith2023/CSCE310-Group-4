<?php
    $user_id = $_GET['user_id'];

    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'tutor_app';

    $conn = new mysqli($db_host, $db_user, $db_password, $db_db);

    $sql = 'SELECT IS_STUDENT, IS_TUTOR, IS_ADMIN FROM `user` WHERE `USER_ID` = '.$user_id;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $arr = array($row['IS_STUDENT'],$row['IS_TUTOR'],$row['IS_ADMIN']);

    $count = 0;
    $hideStudentBtn = TRUE;
    $hideTutorBtn = TRUE;
    $hideAdminBtn = TRUE;

    if($arr[0]) { $hideStudentBtn = FALSE; $count++; }
    if($arr[1]) { $hideTutorBtn = FALSE; $count++; }
    if($arr[2]) { $hideAdminBtn = FALSE; $count++; }

    if($count == 1) {
        if($arr[0]) echo 'I need to go to Student Page <br>';
        elseif($arr[1]) echo 'I need to go to Tutor Page <br>';
        else echo 'I need to go to Admin Page';
    }
?>

<!doctype html>
    <html>
        <button id = 'studentButton' type="button" <?php if($hideStudentBtn) {?> hidden="" <?php } ?>>Go to your Student Page</button>
        <button id = 'tutorButton' type="button" <?php if($hideTutorBtn) {?> hidden="" <?php } ?>>Go to your Tutor Page</button>
        <button id = 'adminButton' type="button" <?php if($hideAdminBtn) {?> hidden="" <?php } ?>>Go to your Admin Page</button> 
    </html>
