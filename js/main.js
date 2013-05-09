localStorage['pagSelect'] = 0;
function signup() {
    var user = $('#txtSignupUser').val();
    var email = $('#txtSignupEmail').val();
    var pass = $('#txtSignupPass').val();
    var pass2 = $('#txtSignupPass2').val();
    
    if (user.trim() == '') {
        alert('The user field must not be blank');
        return false;
    }
    if (email.trim() == '') {
        alert('The email field must not be blank');
        return false;
    }
    if (!email.isEmail()) {
        alert('The email entered is not valid');
        return false;
    }
    if (pass.trim() == '') {
        alert('The password field must not be blank');
        return false;
    }
    if (pass != pass2) {
        alert('Passwords do not match');
        return false;
    }
    
    showLoading();
    var parametros = {
        "user" : user,
        "email" : email,
        "pass" : pass
    };
    $.ajax({
        data:  parametros,
        url:   'php/signup.php',
        type:  'post',
        success:  function (response) {
            if (response != '1') {
                alert(response);
//                $.mobile.hidePageLoadingMsg();
                $.mobile.loading( "hide" );
                return;
            }
            $.mobile.changePage("#login", {reverse: true,}); 
        },
        error: function() {alert("Error");}
    });
    $.mobile.loading( "hide" );
}

function listCategories() {
    showLoading();
    var page = localStorage['actualPage'];
    var totalCount = 0;
    var post = $.post(
        "php/get_categories.php", 
        {"id_user" : localStorage['id_user']},
        function(r){
//            console.log(r.length);
            $('.lstCat').remove();
            $('#cAll').text(0);
            if (r.length == 1) {
                $('#list' + page).append('<li class="lstCat"><a href="#popupAddCategoryMain" data-rel="popup" data-position-to="window" data-theme="e" data-transition="pop">Add Category</a></li>');
                $('#list' + page).append('<li class="lstCat"><a target="_self" href="https://accounts.google.com/o/oauth2/auth?client_id=418906386516-v34v584f9fsd7b24c3esh7usckngnt8q.apps.googleusercontent.com&response_type=code&scope=https://www.google.com/reader/api&redirect_uri=http://apps.willicab.com.ve/beta/firefeed/import.php&approval_prompt=force&state=' + localStorage['id_user'] + '">Import From Google Reader</a></li>');
            }
            for (a in r) {
                if (a == 0) continue;
                category = r[a]['category'];
                id_category = r[a]['id_category'];
                count = r[a]['count_categories'];
                href = '#categories';
                totalCount += parseInt(count);
                $('#list' + page).append($('<li/>', {
                    'class': 'lstCat',
                }).append($('<a/>', {
                    'id': 'cat_' + id_category,
                    'href': href,
                    'text': category
                }).append($('<span/>', {
                    'id' : 'cCat_' + id_category,
                    'class': 'ui-li-count',
                    'text': count
                }))));
                $('#cAll').text(totalCount);
                $('#cat_' + id_category).click(function(){
                    localStorage['actualCategory'] = this.id;
                });
            }
            $('#list' + page).listview('refresh');
        }, 
        'json'
    );
    post.always(function() { $.mobile.loading( "hide" ); });
}

function addCategory(category){
    showLoading();
    if (category.trim() == '') {
        alert('You must enter a category');
        return false;
    }
    var id_user = localStorage['id_user'];
    var post = $.post(
        "php/add_category.php", 
        {"category" : category, "user_id": id_user},
        function(r){
//            console.log('actualPage: ' + localStorage['actualPage']);
            if(localStorage['actualPage'] == 'main') {
                listCategories();
                refreshSelectCategory("selectCategory");
            } else if(localStorage['actualPage'] == 'categories') {
                listSubscriptions();
            }
        }, 
        'json'
    );
    post.always(function() { $.mobile.loading( "hide" ); });
}

function listSubscriptions() {
    showLoading();
    var page = localStorage['actualPage'];
    var totalCount = 0;
    var id_category = localStorage['actualCategory'].substr(4, 2);
    var post = $.post(
        "php/get_subscriptions.php", 
        {"id_user" : localStorage['id_user'], "id_category": id_category},
        function(r){
//            console.log(r);
            $('.lstSubs').remove();
            $('#cCatAll').text(0);
            if (r.length == 1) $('#list' + page).append('<li class="lstSubs"><a href="#popupAddSubscriptionCategories" data-rel="popup" data-position-to="window" data-theme="e" data-transition="pop">Add Subscription</a></li>');
            for (a in r) {
                if (a == 0) continue;
                id_subscription = r[a]['id_subscription'];
                subscription = r[a]['subscription'];
                count = r[a]['count'];
                href = '#items';
                totalCount += parseInt(count);
                $('#list' + page).append($('<li/>', {
                    'class': 'lstSubs',
                }).append($('<a/>', {
                    'id': 'sub_' + id_subscription,
                    'href': href,
//                    'href': 'items.php?all=-1&cat=-1&sub=' + id_subscription,
                    'text': subscription
                }).append($('<span/>', {
                    'id' : 'cSubs_' + id_subscription,
                    'class': 'ui-li-count',
                    'text': count
                }))));
                $('#cCatAll').text(totalCount);
                $('#sub_' + id_subscription).click(function(){
                    localStorage['subSelect'] = this.id.substr(4, 5);
                    localStorage['catSelect'] = -1;
                    localStorage['allSelect'] = -1;
                    $('#itemBack').prop('href', '#categories');
                });
            }
            $('#list' + page).listview('refresh');
//            console.log('#cat_' + id_category);
//            console.log($('#cat_' + id_category).html().split('<')[0]);
            $('#titleCategory').text($('#cat_' + id_category).html().split('<')[0]);
        }, 
        'json'
    );
    post.fail(function(error) { console.log('Error: ' + error.responseText); });
    post.always(function() { $.mobile.loading( "hide" ); });
}

function refreshSelectCategory(selectCategory) {
//    showLoading();
    var post = $.post(
        "php/refresh_categories.php", 
        {"id_user" : localStorage['id_user']},
        function(r){
            $('.' + selectCategory).empty();
            for (a in r) {
                if (a == 0) continue;
                id_category = r[a]['id_category'];
                category = r[a]['category'];
                $('.' + selectCategory).append('<option value="' + id_category + '">' + category + '</option>');
            }
            $('select').selectmenu('refresh', true);
        }, 
        'json'
    );
//    post.always(function() { $.mobile.loading( "hide" ); });
    post.fail(function(error) { console.log('Error: ' + error.responseText); });
}

function addSubscription(subscription, category) {
//    showLoading();
    if (subscription.trim() == '') {
        alert('You must enter a subscription');
        $.mobile.hidePageLoadingMsg();
        return false;
    }
    var id_user = localStorage['id_user'];
    var post = $.post(
        "php/add_subscription.php", 
        {"subscription": subscription, "id_category" : category, "id_user": id_user},
        function(r){
//            console.log('r: ' + r);
            if(localStorage['actualPage'] == 'main') {
                listCategories();
                refreshSelectCategory("selectCategory");
            } else if(localStorage['actualPage'] == 'categories') {
                listSubscriptions();
            }
            return true;
        },
        'text'
    );
//    post.always(function() { $.mobile.loading( "hide" ); });
    post.fail(function() { console.log('Error'); return false});
}

function refresh_list() {
    showLoading();
    pag = localStorage['pagSelect'];
    all = localStorage['allSelect'];
    cat = localStorage['catSelect'];
    sub = localStorage['subSelect'];
    id_user = localStorage['id_user'];
    hide_readed = localStorage['hide_readed'];
    var post = $.post(
        "php/items.php", 
        {"pag": pag, "all": all, "cat": cat, "sub": sub, "id_user": id_user, "hide_readed": hide_readed},
        function(r){
            localStorage['refreshing'] = 0;
            $('.itemDivisor').remove();
            if (r == '' && pag == 0) {
                r = '<li data-role="list-divider">You have no unread items</li>';
                //return;
            }
            $('#listitems').append(r);
            $('#listitems').listview('refresh');
            $('.btnItem').click(function(){
                loadItem(this.id, 'item1');
            });
            if (all != -1) {
                title = 'All Items';
            } else if (cat != -1) {
                title = 'All ' + $('#cat_' + cat).html().split('<')[0];
            } else if (sub != -1) {
                title = $('#sub_' + sub).html().split('<')[0];
            } else {
                title = 'Firefeed';
            }
            $('#titleItems').text(title);
            if ((localStorage['actualPage'] == 'item1' || localStorage['actualPage'] == 'item2') && r != '') {
//                console.log('r: ' + r);
                toNext();
            }
        },
        'text'
    );
    post.always(function() { $.mobile.loading( "hide" ); });
}

function loadItem(id_item, page) {
//    console.log(id_item);
//    localStorage['actualItem'] = id_item;
    showLoading();
    id_user = localStorage['id_user'];
    $('#' + page + 'Content').html('');
    var post = $.post(
        "php/item.php", 
        {"id_item": id_item, "id_user": id_user},
        function(r){
            $('#' + page + 'Content').html(r);
            $('#' + page + ' iframe').css('max-width', ($(window).width() - 30) + 'px');
            $('#' + page + ' iframe').css('height', ('auto'));
            $('#' + page + ' img').css('max-width', ($(window).width() - 30) + 'px');
            $('#' + page + ' img').css('height', ('auto'));
        },
        'text'
    );
    post.always(function() { $.mobile.loading( "hide" ); });
}

function markAsRead(published) {
//    showLoading();
    showLoading();
    id_user = localStorage['id_user'];
    all = localStorage['allSelect'];
    cat = localStorage['catSelect'];
    sub = localStorage['subSelect'];
    var post = $.post(
        "php/mark_as_read.php", 
        {"published": published, "id_user": id_user, "all": all, "cat": cat, "sub": sub},
        function(r){
            localStorage['pagSelect'] = 0;
            $('#listitems').empty();
            refresh_list();
        },
        'text'
    );
//    post.always(function() { $.mobile.loading( "hide" ); });
    post.always(function() { $.mobile.loading( "hide" ); });
    post.fail(function(error) { console.log('Error: ' + error.responseText); });
}

function showLoading(msgText, textVisible, theme, textonly, html) {
    $.mobile.loading( 'show', {
        text: 'Loading',
        textVisible: true,
        theme: 'e',
        textonly: false,
        html: ''
    });
}

function toPrev() {
    prevPage = localStorage['actualPage'] == 'item1' ? 'item2' : 'item1';
    var actualItem = localStorage['actualItem'];
    if (actualItem == 0) return;
    prevItem = parseInt(actualItem) - 1;
    var prev = $('.btnItem:eq(' + (prevItem) + ')').prop('id');
    loadItem(prev, prevPage);
    localStorage['actualItem'] = prevItem;
    $.mobile.changePage("#" + prevPage, {transition: "slide", reverse: true});
}

function toNext(){
    nextPage = localStorage['actualPage'] == 'item1' ? 'item2' : 'item1';
    var actualItem = localStorage['actualItem'];
    nextItem = parseInt(actualItem) + 1;
    var next = $('.btnItem:eq(' + (nextItem) + ')').prop('id');
    if (next == undefined) {
        localStorage['pagSelect'] = parseInt(localStorage['pagSelect']) + 1;
        var val = refresh_list();
        return;
    }
//                    console.log(nextItem + ' ' + next);
    loadItem(next, nextPage);
    localStorage['actualItem'] = nextItem;
    $.mobile.changePage("#" + nextPage, {transition: "slide", reverse: false});
}
