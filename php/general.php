<?php
    function exist_user($user) {
        global $hconn;
        $sql = "select id_user from `users` where user = '$user'";
        $result = mysql_query($sql, $hconn) or die("Error: exist_user $sql"); 
        if (mysql_num_rows($result) == 0) return false;
        return true;
    }
    function exist_email($email) {
        global $hconn;
        $sql = "select id_user from `users` where email = '$email'";
        $result = mysql_query($sql, $hconn) or die("Error: Exist_email $sql"); 
        if (mysql_num_rows($result) == 0) return false;
        return true;
    }
    function exist_user_category($user_id, $category_id) {
        global $hconn;
        $sql = "select * from `user_category` where user_id = $user_id and category_id = $category_id";
        $result = mysql_query($sql, $hconn) or die("Error exist_user_category $sql"); 
        if (mysql_num_rows($result) == 0) return false;
        return true;
    }
    function id_user_category($user_id, $category_id) {
        global $hconn;
        $sql = "select * from `user_category` where user_id = $user_id and category_id = $category_id";
        $result = mysql_query($sql, $hconn) or die("Error: id_user_category $sql"); 
        if (mysql_num_rows($result) == 0) return -1;
        $v = mysql_fetch_row($result);
        return $v[0];
    }
    function id_subscription($subscription) {
        global $hconn;
        $sql = "select * from `subscriptions` where subscription = '$subscription'";
        $result = mysql_query($sql, $hconn) or die("Error: id_subscription $sql"); 
        if (mysql_num_rows($result) == 0) return -1;
        $v = mysql_fetch_row($result);
        return $v[0];
    }
    function id_item($published) {
        global $hconn;
        $sql = "select * from `items` where published = $published";
#        echo $sql.'<br />';
        $result = mysql_query($sql, $hconn) or die("Error: id_item $sql"); 
        if (mysql_num_rows($result) == 0) return -1;
        $v = mysql_fetch_row($result);
        return $v[0];
    }
    function id_category($category) {
        global $hconn;
        $sql = "select id_category from `categories` where category = '$category'";
#        echo $sql.'<br />';
        $result = mysql_query($sql, $hconn) or die('Error: id_category $sql'); 
        if (mysql_num_rows($result) == 0) return -1;
        $v = mysql_fetch_row($result);
        return $v[0];
    }
    function id_user_item($id_user, $id_item) {
        global $hconn;
        $sql = "select * from `user_item` where user_id=$id_user and item_id=$id_item";
#        echo $sql.'<br />';
        $result = mysql_query($sql, $hconn) or die("Error: id_user_item$sql"); 
        if (mysql_num_rows($result) == 0) return -1;
        $v = mysql_fetch_row($result);
        return $v[0];
    }
    function is_readed($id_user, $id_item) {
        global $hconn;
        $sql = "select reader from `user_item` where user_id=$id_user and item_id=$id_item";
#        echo $sql.'<br />';
        $result = mysql_query($sql, $hconn) or die("Error: id_user_item $sql"); 
        if (mysql_num_rows($result) == 0) return false;
        $v = mysql_fetch_row($result);
        return $v[0] == 0 ? false : true;
    }
    function set_log($user_id, $action, $description) {
        global $hconn;
        $ip = $_SERVER['REMOTE_ADDR'];
        $now = date("Y-m-d H:i:s", time());
        $sql = "insert into log values(null, '$now', $user_id, '$ip', '$action', '$description')";
        mysql_query($sql, $hconn);
    }
    function exist_user_category_subscription($id_user, $id_category, $id_subscription) {
        global $hconn;
        $sql = "select * from user_category inner join user_category_subscription on user_category.id_user_category=user_category_subscription.user_category_id where user_id = $id_user and category_id = $id_category and subscription_id = $id_subscription";
        $result = mysql_query($sql, $hconn) or die("Error  exist_user_category_subscription $sql"); 
        if (mysql_num_rows($result) == 0) return false;
        return true;
    }
    function add_subscription($id_user = '', $subscription = '', $id_category = '') {
        global $hconn;
        $url = "http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=10&output=json&q=" . $subscription;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        # Chequea el resultado, si no retorna código 200, dará un mensaje de error y terminará
        $info = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if ($info!=200) { echo "Error: $info"; return;}
        curl_close($ch);
        $obj_php = json_decode($response, true);                # Obtiene el array del json
        if ($obj_php['responseData'] == null) {
            echo "responseDataError";
            return;
        };
        $url = mysql_real_escape_string($obj_php['responseData']['feed']['link']);
        $name = mysql_real_escape_string($obj_php['responseData']['feed']['title']);
        # Agrega la subscripción si esta no existe
        $id_subscription = id_subscription($subscription);
        $last_refresh = date("Y-m-d H:i:s", time());
        if ($id_subscription == -1) {
            $sql = "INSERT INTO subscriptions VALUES (null, '$subscription', '$url', '$name', '$last_refresh')";
            mysql_query($sql, $hconn) or die("Error add_subscription: '$sql'");
            $id_subscription = mysql_insert_id();
        } else {
            $sql = "UPDATE subscriptions SET last_refresh = '$last_refresh' WHERE id_subscription = $id_subscription;";
            mysql_query($sql, $hconn) or die("Error add_subscription: '$sql'");
        }
        # Relaciona la subscripción a la categoría y el usuario si no existe
        if ($id_category != '') {
            if (exist_user_category_subscription($id_user, $id_category, $id_subscription) == false) {
                $id_user_category = id_user_category($id_user, $id_category);
                $sql = "INSERT INTO user_category_subscription VALUES (null, '$id_user_category', '$id_subscription')";
                mysql_query($sql, $hconn) or die("Error add_subscription: '$sql'");
            }
        }
        $entries = $obj_php['responseData']['feed']['entries']; # Obtiene el array de las entradas
        foreach ($entries as $entrie) {
            $title = mysql_real_escape_string($entrie['title']);
            $link = mysql_real_escape_string($entrie['link']);
            $author = mysql_real_escape_string($entrie['author']);
            $published = strtotime($entrie['publishedDate']);
            if ($entrie['publishedDate'] == '') continue;
#            $published = $entrie['publishedDate'] == '' ? strtotime(date("Y-m-d H:i:s", time())) : strtotime($entrie['publishedDate']);
            $content = mysql_real_escape_string($entrie['content']);
            # Agrega el item si este no existe
            if (id_item($published) == -1) {
                $sql = "INSERT INTO items VALUES (null, $id_subscription, '$title', '$link', '$author', '$published', '$content')";
                mysql_query($sql, $hconn) or die("Error add_subscription: '$sql'");
            }
        }
    }
    function items_readed_in_cat($user_id, $category_id) {
        global $hconn;
        $sql = "select count(id_item) as count from items inner join user_item on items.id_item=user_item.item_id inner join subscriptions on items.subscription_id=subscriptions.id_subscription inner join user_category_subscription on subscriptions.id_subscription=user_category_subscription.subscription_id inner join user_category on user_category_subscription.user_category_id=user_category.id_user_category where user_item.reader = 1 and user_item.user_id=$user_id and user_category.category_id=$category_id";
        $result = mysql_query($sql, $hconn) or die("Error items_readed_in_cat $sql"); 
        if (mysql_num_rows($result) == 0) return 0;
        $v = mysql_fetch_row($result);
        return $v[0];
    }
    function items_readed_in_sub($user_id, $subscription_id) {
        global $hconn;
        $sql = "select count(id_item) as count  from items inner join user_item on items.id_item=user_item.item_id inner join subscriptions on items.subscription_id=subscriptions.id_subscription inner join user_category_subscription on subscriptions.id_subscription=user_category_subscription.subscription_id inner join user_category on user_category_subscription.user_category_id=user_category.id_user_category where user_item.reader = 1 and user_item.user_id= $user_id and user_category_subscription.subscription_id=$subscription_id";
        $result = mysql_query($sql, $hconn) or die("Error items_readed_in_sub $sql"); 
        if (mysql_num_rows($result) == 0) return 0;
        $v = mysql_fetch_row($result);
        return $v[0];
    }
?>
