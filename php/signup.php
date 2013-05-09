<?php
    include_once('db.php');
    include_once('general.php');
    
    $user = mysql_real_escape_string($_POST['user']);
    $email = mysql_real_escape_string($_POST['email']);
    $pass = md5($_POST['pass']);

    if (exist_user($user) == true) {
        echo "ya existe el usuario";
        return;
    }

    if (exist_email($email) == true) {
        echo "ya existe el correo";
        return;
    }
    
    $sql = "INSERT INTO `users` VALUES (null, '$user','$email','$pass', 0);";
    
    $result = mysql_query($sql, $hconn) or die("0"); 
    echo "1";

    

?>
