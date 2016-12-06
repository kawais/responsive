$(function(){
    if($('.view-source'))
    {
        $('.view-source').css('cursor','pointer').on('click',goSourceCode);
    }
    $('ul[class="breadcrumb"] li.file').css({"float":"right"});
});

function goSourceCode(evt)
{
    var obj=$(evt.currentTarget)
    var url=obj.attr('data-link');
    var vsUrl=url.replace('\\App','../source').replace('.html','.php.html');
    var method=obj.attr('data-method');
    $.get(vsUrl, function(html) {
        $('.btn-group .btn.active').css('z-index',1);
        $('.modal').remove();
        $(html).appendTo('body');
        $('.modal').on($.modal.OPEN, function(event, modal) {
            if(method)
            {
                Prism.hooks.add("complete",function(){
                    var obj=$('code span.keyword').filter(function(key,item){return item.innerText=='function' && $(item).next().text()==method});
                    $('code').animate({'scrollTop':obj.position().top},500);
                });
            }
        });
        $('.modal').modal().css({"left":0,"top":0,"margin":0,"overflow":"visible","max-width":'100%','max-height':'800px','height':'800px'});
        $('.modal pre').css({'height':'100%'});

    });
}