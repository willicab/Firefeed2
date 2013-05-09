<?php

    include_once('db.php');
    include_once('general.php');
    
    $subscription = mysql_real_escape_string($_POST['subscription']);
    $id_user = mysql_real_escape_string($_POST['id_user']);
    $id_category = mysql_real_escape_string($_POST['id_category']);
#    $subscription = 'http://willicab.gnu.org.ve/feed/';
#    $subscription = 'http://www.fayerwayer.com/feed/';
#    $subscription = 'http://feeds.feedburner.com/EngadgetSpanish';
#    $subscription = 'http://feeds.feedburner.com/Androidsis';
#    $id_user = 3;
#    $id_category = 1;
    add_subscription($id_user, $subscription, $id_category);
    $sal[0] = "1";
    echo json_encode($sal);
?>
