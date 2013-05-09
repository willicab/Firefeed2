<?php
    include_once('db.php');
    include_once('general.php');
    
    $user_id = mysql_real_escape_string($_POST['user_id']);
    $category = mysql_real_escape_string($_POST['category']);
    if ($category == '') $category = 'Uncategorized';

    $category_id = id_category($category);
    if ($category_id == -1) {
        $sql = "INSERT INTO `categories` VALUES (null, '$category');";
        mysql_query($sql, $hconn); 
        $category_id = id_category($category);
    }
    
    if (exist_user_category($user_id, $category_id) == false) {
        $sql = "INSERT INTO `user_category` VALUES (null, $user_id, $category_id);";
        $sal[0] = $sql;
        mysql_query($sql, $hconn); 
    } else {
        $sal[0] = 'error';
    }
    echo json_encode($sal);
?>
