var w=window;
var d=w.document;
var b=d.body; 

var sendingIssueLock = false;

mobileAndTabletcheck = function() {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
}();

function getIssueRngLmt(o){
	//предотвращение бесконечной рекурсии
	if(o.tagName && o.getAttribute('id')=='results'){
		return o;
 	}
	if(o.tagName && (o.tagName.toLowerCase()=='u' || o.tagName.toLowerCase()=='i')){
		o = o.parentNode;
	}
	else if(!o.tagName){
		o = getIssueRngLmt(o.parentNode);
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
    
function getIssueRng(){    
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
                preRng.moveToElementText(getIssueRngLmt(preRng.parentElement()));
                //И наконец доводим его конец до начала выделения, получая префикс
                preRng.setEndPoint('EndToStart',sel);
                
                //Теперь проводим то же с постфиксом
                postRng = sel.duplicate();
			    postRng.collapse(false);      
				postRng.moveToElementText(getIssueRngLmt(postRng.parentElement()));                      
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
	            preRng.setStart(getIssueRngLmt(sel.anchorNode),0);
	            preRng.setEnd(sel.anchorNode,sel.anchorOffset);
	            
	            postRng = document.createRange();
	            postRng.setStart(sel.focusNode,sel.focusOffset);
	            postRng.setEndAfter(getIssueRngLmt(sel.focusNode));	            
	            
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

function showIssueReport(e){
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
        var g = getIssueRng();
        if(g){
            var s = '<pre>'+g.p+'<b>'+g.h+'</b>'+g.s+'</pre>';
            $('#issue-range').html(s);
            $('#issue-comment').val('');
            $("#issueModal").modal('show');
        }
    }
}

function sendIssue(){
    if(sendingIssueLock) return;
    sendingIssueLock = true;
    $('#issueModal .loading').show();
    
    $.post(
        '/api/v1/corpus/issue/index.php',
        {
            'range':$('#issue-range pre').html(),
            'comment':$('#issue-comment').val()
        },
        function(data){
            sendingIssueLock = false;
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
        {"keyword":code, 'corpus': 'warodai'},
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
    $.get('/api/v1/corpus/lookup/',{"keyword":keyword, 'corpus':'warodai'},function(data){

        var html = '', article = '';
        keyword = keyword.replace(/^\*/,'');
        keyword = keyword.replace(/\*$/,'');
        keyword = keyword.replace(/\*/,'.*?');
        $.each(data,function(){
            article = '\
                <div class="card"> \
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
        if (!mobileAndTabletcheck){
            $('#issueNote').show();
        }
        
        $('#keyword').val(decodeURI(keyword.substr(1)));
        lookup();
    }
    else{
        $('#results').html('');
        $('#notes').show();
        $('#issueNote').hide();
    }
}

$(window).on('hashchange', onhashchange);

$(document).ready(function(){
    onhashchange();

    document.onkeydown = showIssueReport;

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