var w=window;
var d=w.document;
var b=d.body; 
    

function initLookup(e){
    var f=0;
    var we=w.event;
    if(we){
        f=we.keyCode==10||(we.keyCode==13);
    }
    else{
        if(e){
            f=(e.which==10)||(e.keyCode==0&&e.charCode==106)||(e.keyCode==13);
        }
    }
    
    if(f){   
        window.location = '#'+$('#keyword').val();
        lookup();	
    }	
}

function lookup(){    
    var keyword = $('#keyword').val();
    if(!keyword) return;

    $('#search-btn i').removeClass('fa fa-search').addClass('fas fa-circle-notch fa-spin');
    getCards(
        keyword,
        function(){
            $('#search-btn i').removeClass('fas fa-circle-notch fa-spin').addClass('fa fa-search');
        }
    );

    if(typeof ga != typeof void 0){
        ga('send', 'event', 'Dictionary', 'lookup', 'Lookup: '+$('#keyword').val(), 1);
    }
    return false;
}

function getCards(keyword,clbk){
    
    if(typeof clbk == typeof void 0){
        clbk = function(){};
    }
    $.get('/api/v1/corpus/lookup/',{"keyword":keyword},function(data){

        var html = '', article = '';
        keyword = keyword.replace(/^\*/,'');
        keyword = keyword.replace(/\*$/,'');
        keyword = keyword.replace(/\*/,'.*?');
        $.each(data,function(){
            article = '<div class="card"><div class="card-body">'+this.article.replace(/\n/g,"<br/>\n")+'</div></div>';

            if(!keyword.match(/^[A0-9-]+$/)){
                article = article.replace(new RegExp('('+keyword+')','gi'),'<u>$1</u>');
            }
            article = article.replace(/&lt;&lt;([^|]+)\|([^&]+)&gt;&gt;/,'<a href="#$1">$2</a>')
            html += article;
        });

        $("#results").html(html);
        $('#results a').click(
            function (){
                var code = $(this).attr('href');
                getCards(code.replace(/#/,''));
            }
        );
        clbk();
    },'json');
}

function onhashchange(){
    var keyword = window.location.hash;
    if(keyword.length > 0){
        $('#notes').hide();
        $('#keyword').val(decodeURI(keyword.substr(1)));
        lookup();
    }
    else{
        $('#results').html('');
        $('#notes').show();
    }
}

$(window).on('hashchange', onhashchange);

$(document).ready(function(){
    onhashchange();
});