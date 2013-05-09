<?php
    include_once('db.php');
    include_once('general.php');
    
    $id_user = mysql_real_escape_string($_POST['id_user']);

    $sql = "select categories.id_category, categories.category from user_category inner join categories on user_category.category_id=categories.id_category where user_id = '$id_user'";
    $sal[0] = $sql;
    $res = mysql_query($sql, $hconn) or die("Error"); 
    
    $i = 1;
    while ($row = mysql_fetch_array($res)) {
        $sal[$i]['id_category'] = $row['id_category'];
        $sal[$i]['category'] = $row['category'];
        $i++;
    }
    echo json_encode($sal);
?>
