<?php
    include_once('db.php');
    include_once('general.php');
    
    $published = mysql_real_escape_string($_POST['id_item']);
    $id_item = id_item($published);
    $id_user = mysql_real_escape_string($_POST['id_user']);

    if (id_user_item($id_user, $id_item) == -1) {
        $sql = "insert into user_item values (null, $id_user, $id_item, 1, 0)";
        mysql_query($sql, $hconn) or die("Error: add");
    } else {
        $sql = "update user_item set reader=1 where user_id=$id_user and item_id=$id_item";
#        echo $sql.'<br />';
        mysql_query($sql, $hconn) or die("Error Update");
    }
    
    
    $sql = "select link, title, content from items where published = '$published'";
    $result = mysql_query($sql, $hconn) or die("0"); 
    $v = mysql_fetch_row($result);
    echo '<h3 style="margin:0"><a style="text-decoration:none" target="_blank" href="' . $v[0] . '">' . $v[1] . '</a></h3>';
    echo '<p style="margin:0;text-align:justify">'.$v[2].'</p>';
    echo '<input id="idItemHidden" type="hidden" value="' . $published . '" />';
#    echo $id_item;
?>
