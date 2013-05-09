<?php
    include_once('db.php');
    include_once('general.php');
    
    $id_user = mysql_real_escape_string($_POST['id_user']);
    $id_category = mysql_real_escape_string($_POST['id_category']);
    #$id_user = 3;

    $sql = "SELECT subscriptions.id_subscription as id FROM categories
inner join user_category on categories.id_category=user_category.category_id
inner join user_category_subscription on user_category.id_user_category=user_category_subscription.user_category_id
inner join subscriptions on user_category_subscription.subscription_id=subscriptions.id_subscription where user_category.user_id=$id_user and categories.id_category=$id_category";
    $sal[0]["sql1"] = $sql;
    $res = mysql_query($sql, $hconn) or die("Error"); 
    
#    echo print_r(mysql_fetch_array($res));
    
    $i = 1;
    while ($row = mysql_fetch_array($res)) {
        $id_subscription = $row['id'];
        $sql = "SELECT subscriptions.last_refresh as last_r, user_category.category_id as cat_id, subscriptions.subscription as url_sub, subscriptions.id_subscription as id_subscription, subscriptions.name as subscription, count(name) as count_items FROM subscriptions inner join user_category_subscription on subscriptions.id_subscription=user_category_subscription.subscription_id inner join user_category on user_category_subscription.user_category_id=user_category.id_user_category inner join items on user_category_subscription.subscription_id=items.subscription_id where user_category.user_id=$id_user and subscriptions.id_subscription = $id_subscription";
        $sal[0]['sql2'] = $sql;
        $res2 = mysql_query($sql, $hconn) or die("Error"); 

        while ($row = mysql_fetch_array($res2)) {
            $last_r = $row['last_r'];
            $now = date("Y-m-d H:i:s", time());
            if (((strtotime($now) - strtotime($last_r)) / 60) > 45) {
                $sal[0]["obj$i"] = "$id_user, {$row['url_sub']}, {$row['cat_id']}";
                $sal[0]['time'.$i] = "$last_r - $now";
                add_subscription($id_user, $row['url_sub'], $row['cat_id']);
            }
            $sal[$i]['id_subscription'] = $row['id_subscription'];
            $sal[$i]['subscription'] = $row['subscription'];
            $items_readed_in_sub = items_readed_in_sub($id_user, $id_subscription);
            $sal[$i]['count'] = $row['count_items'] - $items_readed_in_sub;
            $i++;
        }
    }
    
    echo json_encode($sal);

# Obtener el id de una categoria, el nombre y la cantidad
#SELECT subscriptions.id_subscription, subscriptions.name, count(name) FROM subscriptions inner join user_category_subscription on subscriptions.id_subscription=user_category_subscription.subscription_id inner join user_category on user_category_subscription.user_category_id=user_category.id_user_category inner join items on user_category_subscription.subscription_id=items.subscription_id where user_category.user_id=3 and subscriptions.id_subscription = 7
?>
