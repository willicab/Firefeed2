<?php
    include_once('db.php');
    include_once('general.php');
    
    $id_user = array_key_exists('id_user', $_POST) == false ? $id_user : mysql_real_escape_string($_POST['id_user']);
    $pag = array_key_exists('pag', $_POST) == false ? 0 : mysql_real_escape_string($_POST['pag']);
    $all = array_key_exists('all', $_POST) == false ? -1 : mysql_real_escape_string($_POST['all']);
    $cat = array_key_exists('cat', $_POST) == false ? -1 : mysql_real_escape_string($_POST['cat']);
    $sub = array_key_exists('sub', $_POST) == false ? -1 : mysql_real_escape_string($_POST['sub']);
    $hide_readed = mysql_real_escape_string($_POST['hide_readed']);
    
    $max = 20;
    $ini = ($pag * $max);
    $sql = "select id_item, published, title, name, subscriptions.url, hide_readed from user_category inner join user_category_subscription on user_category.id_user_category=user_category_subscription.user_category_id inner join items on user_category_subscription.subscription_id=items.subscription_id inner join subscriptions on user_category_subscription.subscription_id=subscriptions.id_subscription inner join users on user_category.user_id=users.id_user where user_category.user_id = $id_user";
    if ($all != -1) {
        $sql = $sql;
    } else if ($cat != -1) {
        $sql .= " and user_category.category_id=$cat";
    } else if ($sub != -1) {
        $sql .= " and subscriptions.id_subscription=$sub";
    } else {
        return;
    }

    $sql .= " order by id_item desc limit $ini, $max";
#    echo $sql."<br />";
    $res = mysql_query($sql, $hconn) or die("Error"); 

#    echo '<li data-role="list-divider">' . $ini . ' to ' . ($ini + $max) . "</li>\n";
    while ($row = mysql_fetch_array($res)) {
        $hide_readed = $row['hide_readed'];
        $id_item = $row['id_item'];
        $published = $row['published'];
        $title = $row['title'];
        $name = $row['name'];
        $favicon = $row['url'] . '/favicon.icon';
        $is_readed = is_readed($id_user, id_item($published));
        if ($hide_readed == 1 && $is_readed == true) continue;
        if ($is_readed == true) {
            $data_theme = ' data-theme="f"';
        } else {
            $data_theme = '';
        }
        echo "<li$data_theme>";
        echo "<a class=\"btnItem\" style=\"padding-top:0;padding-bottom:0;\" id=\"$published\" href=\"#item1\">";
        echo "<h2 style=\"white-space:normal;\">$title</h2><p>$name</p>";
        echo "</a>";
        echo "</li>\n";
    }
#    echo '<li class="itemDivisor" data-role="list-divider">Loading...</li>\n';
?>
