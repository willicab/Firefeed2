<?php

    include_once('db.php');
    include_once('general.php');
    
    $id_user = mysql_real_escape_string($_POST['id_user']);
    $pag = array_key_exists('pag', $_POST) == false ? 0 : mysql_real_escape_string($_POST['pag']);
    $all = array_key_exists('all', $_POST) == false ? -1 : mysql_real_escape_string($_POST['all']);
    $cat = array_key_exists('cat', $_POST) == false ? -1 : mysql_real_escape_string($_POST['cat']);
    $sub = array_key_exists('sub', $_POST) == false ? -1 : mysql_real_escape_string($_POST['sub']);


    $sql = "select id_item, published, title, name, subscriptions.url from user_category inner join user_category_subscription on user_category.id_user_category=user_category_subscription.user_category_id inner join items on user_category_subscription.subscription_id=items.subscription_id inner join subscriptions on user_category_subscription.subscription_id=subscriptions.id_subscription where user_category.user_id = $id_user";
    if ($all != -1) {
        $sql = $sql;
    } else if ($cat != -1) {
        $sql .= " and user_category.category_id=$cat";
    } else if ($sub != -1) {
        $sql .= " and subscriptions.id_subscription=$sub";
    } else {
        return;
    }

    $res = mysql_query($sql, $hconn) or die("Error"); 

    while ($row = mysql_fetch_array($res)) {
        $id_item = $row['id_item'];
        $published = $row['published'];
        if (is_readed($id_user, $id_item) == false) {
            if (id_user_item($id_user, $id_item) == -1) {
                $sql = "insert into user_item values (null, $id_user, $id_item, 1, 0)";
                mysql_query($sql, $hconn) or die("Error: add");
            } else {
                $sql = "update user_item set reader=1 where user_id=$id_user and item_id=$id_item";
                mysql_query($sql, $hconn) or die("Error Update");
            }
        }
    }
?>
