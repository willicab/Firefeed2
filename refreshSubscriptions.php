<?php
    include_once('php/db.php');
    include_once('php/general.php');

    # Busca todas las subscripciones
    $sql = "SELECT subscription FROM subscriptions";
    $res = mysql_query($sql, $hconn) or die(mysql_error());

    $count = mysql_num_rows($res);
    $dateIni = date("Y-m-d H:i:s", time());

    while ($row = mysql_fetch_assoc($res)) {
        $subscription = $row['subscription'];    # URL de la subscripción
#        echo "$subscription<br />\n";
        add_subscription('', $subscription, '');
    }

    $dateEnd = date("Y-m-d H:i:s", time());
    $dateDiff = strtotime($dateEnd) - strtotime($dateIni);
    echo "Refresh: $count subscriptions\n";
    echo "Init: $dateIni\n";
    echo "End: $dateEnd\n";
    echo "$dateDiff Seconds\n";
?>
