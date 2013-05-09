<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Configuarciones por defecto</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="jquery.mobile-1.3.1.min.css" />
        <script src="jquery-1.9.1.min.js"></script>
        <script src="jquery.mobile-1.3.1.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script>
            $(document).ready(function(){
                console.log('Open');
                var id_user = localStorage['id_user'];
                var pag = 0;
                var all = <?php echo $_GET['all']?>;
                var cat = <?php echo $_GET['cat']?>;
                var sub = <?php echo $_GET['sub']?>;
                $(window).scroll(function(){
                    var perc = (($(window).scrollTop() + $(window).height()) / $(document).height());
                    if (perc > 0.95) {
                        console.log('loading...');
                        $('#listitems').append('<li class="itemDivisor" data-role="list-divider">Loading...</li>\n');
                        $('#listitems').listview('refresh');
                        refresh_list();
                    }
                });
                function refresh_list() {
                    pag = 0;
                    all = localStorage['allSelect'];
                    cat = localStorage['catSelect'];
                    sub = localStorage['subSelect'];
                    console.log('Open Page ' + pag);
                    var post = $.get(
                        "php/items.php", 
                        {"pag": pag, "all": all, "cat": cat, "sub": sub, "id_user": id_user},
                        function(r){
                            //console.log(r);
                            $('#listitems').append(r);
                            $('#listitems').listview('refresh');
                            $('.itemDivisor').remove();
                        },
                        'text'
                    );
                    pag += 1;
                }
            });
        </script>
        
    </head>
    <body>
        <!-- Items Page -->
        <div data-role="page" id="items" data-theme="e">
            <div data-theme="e" data-role="header" data-position="fixed">
                <h3 id="titleCategory">Firefeed</h3>
            </div>
            <div id="categoryContent" data-role="content">
                <ul id="listitems" data-role="listview" data-theme="d " data-divider-theme="e" data-count-theme="e">
                </ul>
            </div>
        </div>
    </body>
</html> 
