var w=window;
var d=w.document;
var b=d.body; 

var misprintLock = false;

function getMisprintRngLmt(o){
	//предотвращение бесконечной рекурсии
	if(o.tagName && o.getAttribute('id')=='results'){
		return o;
 	}
	if(o.tagName && (o.tagName.toLowerCase()=='u' || o.tagName.toLowerCase()=='i')){
		o = o.parentNode;
	}
	else if(!o.tagName){
		o = getMisprintRngLmt(o.parentNode);
	}
	
	return o;
}

function checkBoundry(rng){
    var o = rng;
    while(!(o.tagName && o.tagName.toLowerCase()=='body')){
        if(o.id=='results') return true;
        
        o = o.parentNode;   
    }
    return false;
}
    
function getMisprintRng(){    
    var res={p:'',h:'',s:''};      
    try{        
        var preRng,postRng;
        var sel=(w.getSelection) ? sel=w.getSelection() : ((document.selection) ? d.selection.createRange() : null);
                               
        if(sel!=null){
            if(sel.text) {//Модель Microsoft Text Range - http://msdn.microsoft.com/en-us/library/ms535872.aspx#
              preRng = sel.duplicate();
              //Коллапсуем диапазон, чтобы он оказался нулевым и таким образом мы определили, внутри какого элемента
              //находится стартовый символ выделения
              preRng.collapse(true);
              //А теперь растягиваем диапазон, чтобы он гарантировано начался с первого символа элемента
              preRng.moveToElementText(getMisprintRngLmt(preRng.parentElement()));
              //И наконец доводим его конец до начала выделения, получая префикс
              preRng.setEndPoint('EndToStart',sel);
              
              //Теперь проводим то же с постфиксом
              postRng = sel.duplicate();
							postRng.collapse(false);      
							postRng.moveToElementText(getMisprintRngLmt(postRng.parentElement()));                      
              postRng.setEndPoint('StartToEnd',sel);              
              if(checkBoundry(postRng.parentElement()) && checkBoundry(preRng.parentElement())){
                res={p:preRng.text,h:sel.text,s:postRng.text};
              }
              else{
                return false; 
              }
            }
            else{//Модель W3C Range - http://www.w3.org/TR/2000/REC-DOM-Level-2-Traversal-Range-20001113/ranges.html#Level-2-Range-Containment
              if (sel.getRangeAt){
		            var rng = sel.getRangeAt(0);
		          }
	            else { // Safari! У него отсутствует метод getRangeAt
		            var rng = document.createRange();
		            rng.setStart(sel.anchorNode,sel.anchorOffset);
		            rng.setEnd(sel.focusNode,sel.focusOffset);		            
	            }

              if(!checkBoundry(sel.anchorNode) || !checkBoundry(sel.focusNode)){
                return false; 
              }
              	            
	            preRng = document.createRange();
	            preRng.setStart(getMisprintRngLmt(sel.anchorNode),0);
	            preRng.setEnd(sel.anchorNode,sel.anchorOffset);
	            
	            postRng = document.createRange();
	            postRng.setStart(sel.focusNode,sel.focusOffset);
	            postRng.setEndAfter(getMisprintRngLmt(sel.focusNode));	            
	            
	            res={
	            	p:preRng.toString(),
	            	h:rng.toString(),
	            	s:postRng.toString()
	            };	               
            }          	
            return res; 
        }
        else{
            return false;
        }
    }
    catch(e){
        return false;
    }
}

function showMisprintRep(e){
    var f=0;
    var we=w.event;
    if(we){
        f=we.keyCode==10||(we.keyCode==13&&we.ctrlKey);
    }
    else{
        if(e){
            f=(e.which==10&&e.modifiers==2)||(e.keyCode==0&&e.charCode==106&&e.ctrlKey)||(e.keyCode==13&&e.ctrlKey);
        }
    }
    
    if(f){        
        var g = getMisprintRng();
        if(g){
            var s = '<pre>'+g.p+'<b>'+g.h+'</b>'+g.s+'</pre>';
            $('#issue-range').html(s);
            $('#issue-comment').val('');
            $("#issueModal").modal('show');
        }
    }
}

function sendMisprint(){
    if(misprintLock) return;
    misprintLock = true;
    $('#issueModal .loading').show();
    
    $.post(
        '/api/v1/corpus/issue/index.php',
        {
            'range':$('#issue-range pre').html(),
            'comments':$('#issue-comment').val()
        },
        function(data){
            misprintLock = false;
            $('#issueModal .loading').hide();
            if(data.message){
                alert(data.message);
                return;
            }
            $("#issueModal").modal('hide');
        }
    );
}    

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

    $('#results').hide();
    $('#loading').show();

    getCards(
        keyword,
        function(){
            $('#loading').hide();
            $('#results').fadeIn();           
        }
    );

    if(typeof ga != typeof void 0){
        ga('send', 'event', 'Dictionary', 'lookup', 'Lookup: '+$('#keyword').val(), 1);
    }
    return false;
}

function peepCard(code){
    $('#peepModal .modal-body').html('');
    $('#peepModal').modal('show');

    $.get(
        '/api/v1/corpus/lookup/',
        {"keyword":code},
        function(data){
            if(data.length == 0) {
                return;
            }
            var article = data[0].article;
            article = article.replace(/\n/g,"<br/>\n");
            
            $('#peepModal .modal-body').html(article);
            $('#peepModal .modal-body a').click(
                function (e){
                    e.preventDefault();
                    var code = $(this).attr('href').replace(/#/,'');    
                    peepCard(code);
                }
            );
        }
    );
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
            article = '\
                <div class="card"> \
                     \
                    <div class="card-body">'+this.article.replace(/\n/g,"<br/>\n")+'</div>\
                </div>\
            ';

            if(!keyword.match(/^[A0-9-]+$/)){
                article = article.replace(new RegExp('('+keyword+')','gi'),'<u>$1</u>');
            }
            
            html += article;
        });

        $("#results").html(html);
        $('#results a').click(
            function (e){
                e.preventDefault(); 
                var code = $(this).attr('href').replace(/#/,'');                           
                peepCard(code);
            }
        )
       
        clbk();
    },'json');
}

function onhashchange(){
    var keyword = window.location.hash;
    $('#peepModal').modal('hide');
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

    document.onkeydown = showMisprintRep;

    $('#peepModal').modal({'show': false});
    $('#issueModal').modal({'show': false});

    $('#reset-btn').on('click',function(){
        $('#keyword').val('').focus();
    });

    $('#keyword')
        .on('focus',function(){
            if($(this).val()){
                $('#reset-btn').fadeIn();
            }
        })
        .on('blur', function(){
            $('#reset-btn').fadeOut();
        });

    $('.nav-link').each(function(){
        var t = $(this);
        if(window.location.pathname.match(new RegExp(t.data('match')))){
            t.addClass('active')
        }
    })
});