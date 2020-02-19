var access_token;
var user;

var opts = {
    'github_client_id': '',
    'github_access_token_proxy': '',
    'redirect_uri': encodeURI(window.location.protocol+'//'+window.location.host+window.location.pathname)
}

for (var k in window.CONF.github_client_ids){
    if(window.location.host.indexOf(k) >= 0) {
        opts.github_client_id = window.CONF.github_client_ids[k];
        opts.github_access_token_proxy = CONF.github_access_token_proxy + '?app='+k;
        break;
    }
}

function returnToStart(){
    window.location = window.location.protocol+'//'+window.location.host+window.location.pathname;
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function raiseError(msg, clbk){
    alert(msg);
    if(clbk) clbk();
}

function getAccessToken(code, state, success, failure){
    var q = {
        'client_id': opts.github_client_id,
        'code': code,
        'redirect_uri': opts.redirect_uri,
        'state': state,
        'grant_type': 'authorization_code'
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        crossDomain: true,
        data: q,
        url: opts.github_access_token_proxy,
        success: function(response){
            window.access_token = response.access_token;
            window.sessionStorage.setItem('access_token', response.access_token);
            success();
        },
        error: failure
    });
}

function checkAuthorization(success, failure){
    var accessToken = window.sessionStorage.getItem('access_token');
    var user = window.sessionStorage.getItem('user');
    if(accessToken){
        window.access_token = accessToken;
        if(!user){
            getUser(success, failure);
        }
        else{
            window.user = JSON.parse(user);
            success(JSON.parse(user));
        }
    }
    else{
        failure();
    }
}

function getOauthCode(){
    window.location = CONF.github_oauth_point+'?client_id='+opts.github_client_id+'&scope=repo&redirect_uri='+opts.redirect_uri+'&response_type=code&state='+Math.random();
}

function getUser(success, failure){    
    $.ajax({
        type: "GET",
        dataType: "json",
        crossDomain: true,
        headers: {
            "Authorization": "token "+access_token
        },                   
        url: CONF.github_api_root+'/user',
        success: function(usr_response){
            window.user = {
                name: usr_response.name || usr_response.login, 
                avatar: usr_response.avatar_url
            }
            window.sessionStorage.setItem('user', JSON.stringify(window.user));
            success(user);
        },
        error: failure
    });
}

function renderUser(user){
    $('#user-pane .start-signin').hide();
    $('#user-pane .avatar').css('backgroundImage','url('+user.avatar+')')
    $('#user-pane .user-spot').show();
}

function startSignIn(){
    $('#signinModal').modal('show');
}

function signin(){
    checkAuthorization(
        function(user){
            renderUser(user);
            $('.auth-only').show();
        },
        function() {
            getOauthCode();
        }
    );
}

function signout(){
    window.sessionStorage.removeItem('user');
    window.sessionStorage.removeItem('access_token');
    returnToStart();
}

$(function(){

    $('#signinModal').modal({'show': false});
    $('.auth-only').hide();
    var params = getUrlVars();

    if(params.code && params.state){
        getAccessToken(
            params.code, 
            params.state,
            function(){
                returnToStart();
            },
            function(error){
                raiseError('Не удалось авторизоваться через Github.');
            }
        );
    }
    else if(params.error){
        raiseError('При попытке авторизовать вас через Github произошла вот такая ошибка: '+params.error+' '+params.error_description);
    }
    else{
        if(window.sessionStorage.getItem('access_token')){
            signin();
        }
    }
});