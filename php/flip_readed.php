<?php
    include_once('db.php');
    include_once('general.php');
    
    $id_user = mysql_real_escape_string($_POST['id_user']);
    $val = mysql_real_escape_string($_POST['val']);
    
    $sql = "UPDATE users set hide_readed=$val WHERE id_user=$id_user";
    mysql_query($sql, $hconn) or die("Error guardando la opción 'Hide Readed'");
?>
