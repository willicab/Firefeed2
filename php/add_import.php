<?php
    include_once('db.php');
    include_once('general.php');
    
    $id_user = $_POST['id_user'];
    $subscription = $_POST['subscription'];
    $category = $_POST['category'];
    # Agrega la categoría si existe
    $id_category = id_category($category);

    if ($id_category == -1) {
        $sql = "INSERT INTO `categories` VALUES (null, '$category');";
        mysql_query($sql, $hconn); 
        $id_category = mysql_insert_id();
    }
    # Relaciona el usuario a la categoría si no existe
    if (exist_user_category($id_user, $id_category) == false) {
        $sql = "INSERT INTO `user_category` VALUES (null, $id_user, $id_category);";
        $sal[0] = $sql;
        mysql_query($sql, $hconn); 
    }
    #Actualiza las subscripciones
    add_subscription($id_user, $subscription, $id_category);
?>
