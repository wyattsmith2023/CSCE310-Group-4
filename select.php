<?php
    $user_id = $_GET['user_id'];
    $student_url = '/student.php?user_id='.$user_id;
    $tutor_url = '/tutor.php?user_id='.$user_id;
    $admin_url = '/admin.php?user_id='.$user_id;

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
        if($arr[0]) header("Location: $student_url");
        elseif($arr[1]) header("Location: $tutor_url");
        else header("Location: $admin_url");
    }

    if(isset($_POST['studentBtn'])) header("Location: $student_url");
    if(isset($_POST['tutorBtn'])) header("Location: $tutor_url");
    if(isset($_POST['adminBtn'])) header("Location: $admin_url");
?>

<!doctype html>
    <html>
    <form method="post">
    <button name='studentBtn' type="submit" <?php if($hideStudentBtn) {?> hidden="" <?php } ?>>Go to your Student Page</button>
    <button name='tutorBtn' type="submit" <?php if($hideTutorBtn) {?> hidden="" <?php } ?>>Go to your Tutor Page</button>
    <button name='adminBtn' type="submit" <?php if($hideAdminBtn) {?> hidden="" <?php } ?>>Go to your Admin Page</button> 
    </form>
    </html>
