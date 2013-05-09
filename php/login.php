<?php
    include_once('db.php');
    include_once('general.php');
    
    $user = mysql_real_escape_string($_POST['user']);
    $pass = md5($_POST['pass']);

    $sql = "select id_user, hide_readed, password from `users` where user = '$user' or email = '$user'";
    $result = mysql_query($sql, $hconn) or die("Error"); 
    
    $sal[0] = '';
    if (mysql_num_rows($result) == 0) {
        $sal[0] = 'Usuario o Correo no existe';
    } else {
        $v = mysql_fetch_row($result);
        if ($v[2] == $pass) {
            set_log($v[0], 'LOGIN', "The user {$v[1]} has logged");
            $sal[1] = $v[0];
            $sal[2] = $v[1];
        } else {
            $sal[0] = 'La contraseña es incorrecta';
        }
    }
    echo json_encode($sal);
?>
