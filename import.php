<?php
    # Hace la conexión
    include_once('php/db.php');
    include_once('php/general.php');

    $id_user = $_GET['state'];
    $code = $_GET["code"];

    # BETA
    $client_id = '418906386516-v34v584f9fsd7b24c3esh7usckngnt8q.apps.googleusercontent.com';
    $client_secret = '1O2Ok_m9vBD-BrFDedgQDx36';
    $redirect_uri = 'http://apps.willicab.com.ve/beta/firefeed/import.php';
    # LOCALHOST
#    $client_id = '418906386516-sms7mb6q6bioull59e3jhuq8dfg3pmgd.apps.googleusercontent.com';
#    $client_secret = '_r1F0oQc9D71RR7_SnC-wtPE';
#    $redirect_uri = 'http://localhost/FirefoxOS/firefeed/import.php';
    
    $grant_type = 'authorization_code';

    // our app is recognized, get access token by doing a post to the Google oAuth service
    $url = 'https://accounts.google.com//o/oauth2/token';
    $data = "code=" . urlencode($_GET['code']) . "&client_id=$client_id&client_secret=$client_secret&redirect_uri=$redirect_uri&grant_type=$grant_type";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    // check the result. anything but a 200 return code is an error
    $info = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    if ($info!=200) { echo "Error1: $info"; return;}
    curl_close($ch);
    $obj_php = json_decode($response);
    $access_token = $obj_php->access_token; 
    
    $url = 'https://www.google.com/reader/api/0/subscription/list?output=json&client=Firefeed';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: OAuth '. $access_token ,
        ));
    $response = curl_exec($ch);
    // check the result. anything but a 200 return code is an error
    $info = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    if ($info!=200) { echo "Error2: $info"; return;}
    curl_close($ch);
    $subscriptions = json_decode($response, true);
    $json = '';
    foreach ($subscriptions['subscriptions'] as $subscription) {
        $category = $subscription['categories'][0]['label'];
        $link = substr($subscription['id'], 5, strlen($subscription['id']));
        $json .= "{'category':'$category','subscription':'$link'},\n";
    }
    $json = "[$json]";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Configuarciones por defecto</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="jquery.mobile-1.3.1.min.css" />
        <link rel="stylesheet" type="text/css" href="css/tolito-1.0.3.min.css" />
        <script src="jquery-1.9.1.min.js"></script>
        <script src="jquery.mobile-1.3.1.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script type="text/javascript" src="js/prototypes.js"></script> 
        <script type="text/javascript" src="js/tolito-1.0.3.min.js"></script>
        <script>
            $(document).ready(function(){
                var progressBar = TolitoProgressBar('progressbar')
                    .setOuterTheme('b')
                    .setInnerTheme('e')
                    .isMini(false)
                    .setMax(100)
                    .setStartFrom(0)
                    .setInterval(10)
                    .showCounter(true)
                    .logOptions()
                    .build();
//                    .run();

                i = 0;
                id_user = <?=$id_user?>;
                jf = <?=$json?>;
//                console.log(jf);
                add_subscription(id_user, jf[0]['subscription'], jf[0]['category']);
                function add_subscription(id_user, subscription, category) {
                    i = i + 1;
                    if (i == (jf.length)) {
                        $('#importStatus').prepend('<li><a href="main.php">Go to main</a></li>\n');
                        $('#importStatus').listview('refresh');
                        return;
                    }
//                    if (i == 20) return;
                    $('#importStatus').prepend('<li>' + subscription + '<span id="status' + (i - 1) + '" class="ui-li-count"></span></li>\n');
                    $('#importStatus').listview('refresh');
                    var post = $.post(
                        "php/add_import.php", 
                        {"id_user": id_user, "subscription": subscription, "category": category},
                        function(r){
                            perc = Math.round((i * 100) / jf.length);
                            progressBar.setValue(perc);
//                            console.log('salida: ' + r);
                            if (r == 'responseDataError') {
                                $('#status' + (i - 1)).css('color', '#f00');
                                $('#status' + (i - 1)).text('FAIL');
                            } else {
                                $('#status' + (i - 1)).css('color', '#0f0');
                                $('#status' + (i - 1)).text('OK');
                            }
                            add_subscription(id_user, jf[i]['subscription'], jf[i]['category']);
                        },
                        'text'
                    );
                }
            });
        </script>
        <style>
        @font-face {
            font-family: "Racing Sans One";
            src: url(ttf/RacingSansOne-Regular.ttf);
        }
        </style> 
    </head>
    <body>
        <div data-role="page" id="settings" data-theme="e">
            <div data-theme="e" data-role="header" data-position="fixed">
                <h3 id="titleImport">Import from Google Reader</h3>
            </div>
            <div data-role="content" style="padding-top:0">
                <div id="progressbar"></div>
                <br />
                <ul id="importStatus" data-role="listview">
                </ul>
            </div>
        </div>
    </body>
</html> 
