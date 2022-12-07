<?php
    $user_id = $_GET['user_id'];
    $tutor_id = $_GET['tutor_id'];

    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'tutor_app';

    $conn = new mysqli($db_host, $db_user, $db_password, $db_db);
    $tutor_info = ($conn->query("SELECT * FROM `user` WHERE `USER_ID`=$tutor_id"))->fetch_assoc();
    $user_reviews = ($conn->query("SELECT * FROM `all_reviews` WHERE `STUDENT_ID`=$user_id AND `TUTOR_ID`=$tutor_id"));
    $all_tags = $conn->query("SELECT * FROM `tag`");
    $conn->close();

    function add_review($comment, $stars, $tutor_id, $student_id, $tags) {
        global $db_host, $db_user, $db_password, $db_db;
        $db_is = "information_schema";
        
        $conn = new mysqli($db_host, $db_user, $db_password, $db_is);

        $auto_inc_query = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'tutor_app' AND TABLE_NAME = 'review'";
        $review_id = (($conn->query($auto_inc_query))->fetch_assoc())['AUTO_INCREMENT'];
        $conn->close();

        $conn = new mysqli($db_host, $db_user, $db_password, $db_db);
        
        $insert_col = "INSERT INTO `review`(`REVIEW_ID`, `COMMENT`, `STARS`, `TUTOR_ID`, `STUDENT_ID`, `DATE`) VALUES ";
        $insert_val = "($review_id, '$comment', $stars, $tutor_id, $student_id, '".date('Y-m-d')."')";
        $insert_sql = $insert_col.$insert_val;
        $conn->query($insert_sql);

        $tags = array_unique($tags);
        foreach($tags as $tag) {
            $insert_tag_sql = "INSERT INTO `tag_bridge`(`REVIEW_ID`, `TAG_ID`) VALUES ($review_id, $tag)";
            $conn->query($insert_tag_sql);
        }
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
?>

<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="review.css"></head>
<style>
    h1 { color: green; }
</style>
<body>
<h1>Reviews</h1>
<p>
    <form action="" method="post">
        <br><br>
        <p><label>Leave a comment below:</label></p>
        <textarea name="body" rows="4" cols="50"></textarea>
        <br><br>
        <table>
            <tr><td>
            <?php
                echo "<label>Add Tags?</label>";
                echo "<br>";
                echo "<select name=\"tags[]\" size=\"6\" multiple>";
                foreach($all_tags as $tag)
                    echo "<option value=".$tag['TAG_ID'].">".$tag['NAME']."</option>";
                echo "</select>";
            ?>
            </td>
            <td>
            <div class="rate">
                <input type="radio" id="star5" name="rate" value="5" />
                <label for="star5" title="text">5 stars</label>
                <input type="radio" id="star4" name="rate" value="4" />
                <label for="star4" title="text">4 stars</label>
                <input type="radio" id="star3" name="rate" value="3" />
                <label for="star3" title="text">3 stars</label>
                <input type="radio" id="star2" name="rate" value="2" />
                <label for="star2" title="text">2 stars</label>
                <input type="radio" id="star1" name="rate" value="1" />
                <label for="star1" title="text">1 star</label>
            </div>
            </td></tr>
        </table>
        <br><br>
        <input name="submitBtn" type="submit" value="Submit">
    </form>
    <?php
        if(isset($_POST['submitBtn'])) {
            $b_bool = isset($_POST['body']) && !empty($_POST['body']);
            $s_bool = isset($_POST['rate']) && !empty($_POST['rate']);
        
            if($b_bool && $s_bool) {
                add_review($_POST['body'], $_POST['rate'], $tutor_id, $user_id, $_POST['tags']);
            }
        }
    ?>
    <br><br>
    <h1>Your Current Reviews</h1>
    <?php
        echo "<table>";
        echo "<tr>";
        echo "<th>Comment</th>";
        echo "<th>Stars</th>";
        echo "<th>Tags</th>";
        echo "<th>Date</th>";
        echo "<th></th>";
        echo "<th></th>";
        echo "</tr>";
        
        foreach($user_reviews as $review) {
            echo "<tr>";
            echo "<td>".$review['COMMENT']."</td>";
            echo "<td>".$review['STARS']."</td>";
            echo "<td>".$review['TAGS']."</td>";
            echo "<td>".$review['DATE']."</td>";
            echo "<td><button>Edit Review</button></td>";
            echo "<td><button>Delete Review</button></td>";
            echo "</tr>";
        }
    ?>
</p>

</body>
</html>