<?php
    include_once('db.php');
    include_once('general.php');
    
    $id_user = mysql_real_escape_string($_POST['id_user']);

    $sql = "select id_category from user_category inner join categories on user_category.category_id=categories.id_category where user_id = '$id_user'";
    $sal[0] = $sql;
    $res = mysql_query($sql, $hconn) or die("Error"); 
    
    $i = 1;
    while ($row = mysql_fetch_array($res)) {
        $id_category = $row['id_category'];
        $sql = "SELECT categories.category as category , count(categories.category) as count_categories FROM categories inner join user_category on categories.id_category=user_category.category_id inner join user_category_subscription on user_category.id_user_category=user_category_subscription.user_category_id inner join items on user_category_subscription.subscription_id=items.subscription_id where user_category.user_id=$id_user and user_category.category_id=$id_category";
        $res2 = mysql_query($sql, $hconn) or die("Error"); 
        while ($row = mysql_fetch_array($res2)) {
            $sal[$i]['id_category'] = $id_category;
            $sal[$i]['category'] = $row['category'];
            $items_readed_in_cat = items_readed_in_cat($id_user, $id_category);
            $sal[$i]['count_categories'] = $row['count_categories'] - $items_readed_in_cat;
            $i++;
        }
    }
    echo json_encode($sal);
?>
