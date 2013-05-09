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
        <script type="text/javascript" src="js/prototypes.js"></script> 
        <script>
            $(document).ready(function(){
var ua = navigator.userAgent;

if (/Firefox\//.test(ua)) {
   var Firefox = /Firefox\/([0-9\.A-z]+)/.exec(ua)[1];
//   if (parseInt(Firefox) < 19) {
//      alert ('The Firefox versión is too old. please upgrade to version 19 or higher');
//      $.mobile.changePage("#about", {reverse: false,});
//      $('#aboutBack').css('display', 'none');
//      return;
//   }
}
                $('#flipReaded').val('1').change();
                refreshSelectCategory("selectCategory");
                if (localStorage['logged'] == 'false' && localStorage['actualPage'] != 'signup') {
                    $.mobile.changePage("#login", {reverse: false,});
                } else {
//                    refreshSelectCategory("selectCategory");
                    $.mobile.changePage("#main", {reverse: false,});
                }
                $("#btnAcceptSignup").click(function(e){signup();});
                $("#btnLogin").click(function(e){
                    showLoading();
                    var user = $('#txtUser').val();
                    var pass = $('#txtPass').val();
                    var parametros = {
                        "user" : user,
                        "pass" : pass
                    };
                    var post = $.post(
                        'php/login.php', 
                        parametros,
                        function(r){
                            if (r[0] != '') {
                                alert(r[0]);
                                return;
                            }
//                            console.log('id_user: ' + r[1]);
//                            console.log('hide_readed: ' + r[2]);
                            localStorage['id_user'] = r[1];
                            localStorage['hide_readed'] = r[2];
                            localStorage['logged'] = 'true';
                            $.mobile.changePage("#main", {reverse: false,});
                        },
                        'json'
                    );
                    post.always(function() { $.mobile.loading( "hide" ); });
                    post.fail(function(error) { console.log('Error:'); console.log(error.responseText)});
                });
                $(".signOut").click(function(e){
                    localStorage['logged'] = 'false';
                    localStorage['id_user'] = 0;
                    localStorage['hide_readed'] = 0;
                    $.mobile.changePage("#login", {reverse: false,});
                });
                $(".mnuAbout").click(function(e){
                    $('#aboutBack').prop('href', '#' + localStorage['actualPage'])
                    $.mobile.changePage("#about");
                });
                $(".mnuSettings").click(function(e){
                    $('#settingsBack').prop('href', '#' + localStorage['actualPage'])
                    $.mobile.changePage("#settings");
                });
                $("#aImport").click(function(e){
                    if ($('#aImport').prop('href').indexOf("&state=") == -1) {
                        $('#aImport').prop('href', $('#aImport').prop('href') + '&state=' + localStorage['id_user']);
                    }
                });
                $('[data-role="page"]').bind('pageshow', function(){
                    localStorage['actualPage'] = this.id;
                    $(window).unbind('scroll', wscroll);
                    if (localStorage['logged'] == 'false') $.mobile.changePage("#login", {reverse: false,});
                    if (this.id == 'main') { // Se abre la ventana principal
                        listCategories();
                    } else if(this.id == 'categories') { // Se abre la ventana de categorías
                        $('.lstSubs').remove();
                        $('#cCatAll').text(0);
                        listSubscriptions();
                    } else if(this.id == 'items') { // Se abre la ventana de items
                        localStorage['pagSelect'] = 0;
                        $('#listitems').empty();
                        refresh_list();
                        var wscroll = function(){
                            var perc = (($(window).scrollTop() + $(window).height()) / $(document).height());
                            if (perc == 1 && localStorage['refreshing'] == 0) {
                                localStorage['refreshing'] = 1;
                                localStorage['pagSelect'] = parseInt(localStorage['pagSelect']) + 1;
                                $('.itemDivisor').remove();
                                $('#listitems').append('<li class="itemDivisor" data-role="list-divider">Loading...</li>\n');
                                $('#listitems').listview('refresh');
                                refresh_list();
                            }
                        };
                        $(window).bind('scroll', wscroll);
                    }
                });
                $('#mainAll').click(function(){
                    localStorage['subSelect'] = -1;
                    localStorage['catSelect'] = -1;
                    localStorage['allSelect'] = 1;
                    $('#itemBack').prop('href', '#main');
                });
                $('#catAll').click(function(){
                    localStorage['subSelect'] = -1;
                    localStorage['catSelect'] = localStorage['actualCategory'].substr(4, 5);
                    localStorage['allSelect'] = -1;
                    $('#itemBack').prop('href', '#categories');
                });
                $(".itemPage").swiperight(function() {
                    toPrev();
                });
                $(".itemPage").swipeleft(function() {
                    toNext();
                });
                $('#listitems').on('click', 'a', function () {
                    var actualItem = $(this).parents('li').eq(0).index();
                    localStorage['actualItem'] = actualItem;
                });
                $("#markRead").click(function(){
                    markAsRead();
                });
                $('#flipReaded').bind( "change", function(event, ui) {
                    var post = $.post(
                        'php/flip_readed.php', 
                        {'val': $('#flipReaded').val(), 'id_user': localStorage['id_user']},
                        function(response){
//                            console.log($('#flipReaded').val());
                        },
                        'text'
                    );
                });
                $(window).resize(function() {
                    page = localStorage['actualPage'];
                    $('#' + page + ' iframe').css('max-width', ($(window).width() - 30) + 'px');
                    $('#' + page + ' iframe').css('height', ('auto'));
                    $('#' + page + ' img').css('max-width', ($(window).width() - 30) + 'px');
                    $('#' + page + ' img').css('height', ('auto'));
                });
            });
        </script>
        <style>
        @font-face {
            font-family: "Racing Sans One";
            src: url(ttf/RacingSansOne-Regular.ttf);
        }
        #address:after{
        /* \40 es un código para escribir el caracter '@' */
        content: " <info\40 willicab.com.ve>";
        }
        </style> 
    </head>
    <body>
        <!-- Login page -->
        <div data-theme="e" data-role="page" id="login">
            <div data-theme="e" data-role="header" data-position="fixed">
                <img src="images/icon-32.png" alt="image" style="float: left; margin: 6px">
                <h3>Firefeed</h3>
            </div>
            <div data-role="content">
                <form>
                    <div data-role="fieldcontain">
                        <label for="txtUser">User or Email</label>
                        <input name="txtUser" id="txtUser" placeholder="info@willicab.com.ve"
                        value="" type="text">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="txtPass">Password</label>
                        <input name="txtPass" id="txtPass" placeholder="secret" value="" type="password">
                    </div>
                    <!--input value="Login" type="submit" id="btnLogin" /-->
                    <a id="btnLogin" data-role="button" data-theme="e" href="#">Login</a>
                    <a data-role="button" data-theme="e" href="#signup">Signup</a>
                </form>
            </div>
        </div>
        
        <!-- Sign up page -->
        <div data-role="page" id="signup" data-theme="e">
            <div data-theme="e" data-role="header" data-position="fixed">
                <a data-role="button" data-inline="true" data-direction="reverse" href="#login" data-icon="back" data-iconpos="left" class="ui-btn-left btnBack">Back</a>
                <h3>Sign Up</h3>
            </div>
            <div data-role="content">
                <div data-role="fieldcontain">
                    <label for="txtSignupUser">User</label>
                    <input name="txtSignupUser" id="txtSignupUser" placeholder="nickname" value="" type="text">
                </div>
                <form action="">
                    <div data-role="fieldcontain">
                        <label for="txtSignupEmail">Email</label>
                        <input name="txtSignupEmail" id="txtSignupEmail" placeholder="info@willicab.com.ve" value="" type="text">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="txtSignupPass">Password</label>
                        <input name="txtSignupPass" id="txtSignupPass" placeholder="secret" value="" type="password">
                    </div>
                    <div data-role="fieldcontain">
                        <label for="txtSignupPass2">Repeat Password</label>
                        <input name="txtSignupPass2" id="txtSignupPass2" placeholder="secret" value="" type="password">
                    </div>
                    <a data-role="button" id="btnAcceptSignup" data-theme="e" href="#page1">Signup</a>
                </form>
            </div>
        </div>

        <!-- Main Page -->
        <div data-role="page" id="main" data-theme="e">
            <div data-theme="e" data-role="header" data-position="fixed">
                <img src="images/icon-32.png" alt="image" style="float: left; margin: 6px">
                <a data-rel="popup" data-role="button" href="#popupMenuMain" data-icon="gear" data-iconpos="right" data-transition="slideup" class="ui-btn-right" data-position-to="window">Menu</a>
                <h3>Firefeed</h3>
            </div>
            <div id="mainContent" data-role="content">
                <ul id="listmain" data-role="listview" data-theme="d" data-divider-theme="e" data-count-theme="e">
                    <li><a id="mainAll" href="#items">All Articles <span id="cAll" class="ui-li-count">0</span></a></li>
                    <li><a id="mainFav" href="#">Favorites</a></li>
                    <li id="mainDivisor" data-role="list-divider">Categories</li>
                </ul>
            </div>
            <?php 
                $idPage="Main"; include('php/template/menu.php');
                include('php/template/addCategory.php');
                include('php/template/addSubscription.php'); 
            ?>
        </div>
        
        <!-- Categories Page -->
        <div data-role="page" id="categories" data-theme="e">
            <div data-theme="e" data-role="header" data-position="fixed">
                <a data-role="button" href="#main" data-icon="arrow-l" data-iconpos="left" class="ui-btn-left btnBack">Back</a>
                <a data-rel="popup" data-role="button" href="#popupMenuCategories" data-icon="gear" data-iconpos="right" data-transition="slideup" class="ui-btn-right" data-position-to="window">Menu</a>
                <h3 id="titleCategory">Firefeed</h3>
            </div>
            <div id="categoryContent" data-role="content">
                <ul id="listcategories" data-role="listview" data-theme="d " data-divider-theme="e" data-count-theme="e">
                    <li><a id="catAll" href="#items">All Articles <span id="cCatAll" class="ui-li-count">0</span></a></li>
                    <li id="mainDivisor" data-role="list-divider">Categories</li>
                </ul>
            </div>
            <?php 
                $idPage="Categories"; include('php/template/menu.php');
                include('php/template/addCategory.php');
                include('php/template/addSubscription.php'); 
            ?>
        </div>
        <!-- Items Page -->
        <div data-role="page" id="items" data-theme="e">
            <div data-theme="e" data-role="header" data-position="fixed">
                <a id="itemBack" ata-role="button" href="#main" data-icon="arrow-l" data-iconpos="left" class="ui-btn-left btnBack">Back</a>
                <a id="markRead" data-role="button" href="#page1" class="ui-btn-right">Mark All As Read</a>
                <h3 id="titleItems">Firefeed</h3>
            </div>
            <div id="categoryContent" data-role="content">
                <ul id="listitems" data-role="listview" data-theme="d" data-divider-theme="e" data-count-theme="e">
                </ul>
            </div>
        </div>
        <!-- Article Page 1-->
        <div class="itemPage" data-role="page" id="item1" data-theme="c">
            <div data-theme="e" data-role="header" data-position="fixed">
                <a class="itemBack" data-role="button" href="#items" data-icon="arrow-l" data-iconpos="left" class="ui-btn-left btnBack">Back</a>
                <h3 id="titleItem1">Firefeed</h3>
            </div>
            <div class="itemContent" id="item1Content" data-role="content"></div>
        </div>
        <!-- Article Page 2-->
        <div class="itemPage" data-role="page" id="item2" data-theme="c">
            <div data-theme="e" data-role="header" data-position="fixed">
                <a class="itemBack" data-role="button" href="#items" data-icon="arrow-l" data-iconpos="left" class="ui-btn-left btnBack">Back</a>
                <h3 id="titleItem2">Firefeed</h3>
            </div>
            <div class="itemContent" id="item2Content" data-role="content"></div>
        </div>
        <!-- About Page -->
        <div data-role="page" id="about" data-theme="e" style="text-align:center; background-image:url('images/icon-back-256.png');background-repeat:no-repeat;background-position:center;background-attachment:fixed;">
            <div data-theme="e" data-role="header" data-position="fixed">
                <a id="aboutBack" ata-role="button" href="#main" data-icon="arrow-l" data-iconpos="left" class="ui-btn-left btnBack">Back</a>
                <h3 id="titleAbout">About Firefeed</h3>
            </div>
            <div id="aboutContent" data-role="content" style="padding:0;">
                <img style="margin-top: 6px;" src="images/icon-128.png" />
                <h2 style="margin:0;font-family:'Racing Sans One'">Firefeed</h2>
                <p style="margin:0;">2.0 Beta2</p>
                <p>Firefeed is a small and light feed reader for Firefox OS<br />
                <a rel="external" target="_blank" href="http://apps.willicab.com.ve/firefeed">http://apps.willicab.com.ve/firefeed</a></p>
                <p>Copyright &copy; 2013 William Cabrera<br />
                <a rel="external" target="_blank" href="http://www.gnu.org/licenses/agpl.txt">http://www.gnu.org/licenses/agpl.txt</a></p>
                <p style="margin-bottom: 3px;" id="address">Send feedback to </p>
                <form style="margin-top: 20px;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="KVR9TKES472N2">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
        </div>
        <!-- Settings Page -->
        <div data-role="page" id="settings" data-theme="e">
            <div data-theme="e" data-role="header" data-position="fixed">
                <a id="settingsBack" ata-role="button" href="#items" data-icon="arrow-l" data-iconpos="left" class="ui-btn-left btnBack">Back</a>
                <h3 id="titleSettings">Settings</h3>
            </div>
            <div id="settingsContent" data-role="content">
                <form>
                    <ul data-role="listview" data-inset="true" data-theme="d" data-divider-theme="e" data-count-theme="e">
                        <!--li><a href="#setCat">Manage Categories</a></li>
                        <li><a href="#setSub">Manage Subscriptions</a></li-->
                        <li data-role="fieldcontain">
                            <label for="flipReaded">Hide Read Items:</label>
                            <select name="flipReaded" id="flipReaded" data-role="slider">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </li>
                        <!-- BETA -->
                        <li><a id="aImport" target="_self" href="https://accounts.google.com/o/oauth2/auth?client_id=418906386516-v34v584f9fsd7b24c3esh7usckngnt8q.apps.googleusercontent.com&response_type=code&scope=https://www.google.com/reader/api&redirect_uri=http://apps.willicab.com.ve/beta/firefeed/import.php&approval_prompt=force">Import From Google Reader</a></li>
                        <!-- LOCALHOST -->
                        <!--li><a id="aImport" target="_self" href="https://accounts.google.com/o/oauth2/auth?client_id=418906386516-sms7mb6q6bioull59e3jhuq8dfg3pmgd.apps.googleusercontent.com&response_type=code&scope=https://www.google.com/reader/api&redirect_uri=http://localhost/FirefoxOS/firefeed/import.php&approval_prompt=force">Import From Google Reader</a></li-->
                    </ul>
                </form>
            </div>
        </div>
    </body>
</html> 
