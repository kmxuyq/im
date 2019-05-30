/**
 * 公共弹窗
 * @param msg  弹窗提示参数
 * @param callbacks 回调参数
 */
function alertPopWin(msg,callbacks){

    msg= !msg? '请输入弹窗提示信息':msg;
    $('#alertPop').remove();
    var alertPop=$('<div id="alertPop" >' +
        '<div class="pop_msg">' +
        '<p>'+msg+'</p>' +
        '</div>' +
        '<div class="btn-wrap">' +
        '<input class="onCallback" type="button" value="确认">' +
        '<input class="onClose" type="button" value="关闭">' +
        '</div>' +
        '</div>').appendTo($('body'));

    openPopWin(alertPop);
    alertPop.find('.onCallback').click(function(){
        if(callbacks &&  callbacks =='close'){
            closePopWind('#alertPop');
            return;
        }
        callbacks && callbacks();
    });

    alertPop.find('.onClose').click(function(){
        closePopWind('#alertPop');
    });
}

/**
 *
 * @param str   需要弹出的对象
 * @param is_Bodyscroll 是否静止屏幕跟随滚动
 * @param callback      回调函数
 */
function openPopWin(str,is_Bodyscroll,callback){

    $('.bg-mark').remove();
    $('body').after('<div class="bg-mark" ></div>');
    $('.bg-mark').click(function(){
        if(str=='.loading-box') return;
        closePopWind(str)
    });
    $('.bg-mark').fadeIn();
    var pop=$(str);
    pop.fadeIn('fast');

    pop.css({
        left:($(window).width()-pop.outerWidth())/2,
        top:($(window).height()-pop.height())/2
    });

    callback && callback();

    if(is_Bodyscroll!='false'){
        document.body.style.overflow='hidden';
    }
}
/**
 *
 * @param str 关闭弹窗对象
 */
function closePopWind(str){
    document.body.style.overflow='auto';
    $('.bg-mark').fadeOut();
    $(str).fadeOut('fast');
}


function openPopLayer(str,option,callback){
    var options={
        css3:false,
        is_Bodyscroll:true
    };
    $.extend(options,option);

    $('.bg-mark').remove();
    $('body').after('<div class="bg-mark" ></div>');
    $('.bg-mark').click(function(){
        if(str=='.loading-box') return;
        closePopWind(str)
    });
    $('.bg-mark').fadeIn();
    var pop=$(str);


    if(options.css==false){
        pop.fadeIn('fast');
        pop.css({
            left:($(window).width()-pop.outerWidth())/2,
            top:($(window).height()-pop.height())/2
        });
    }else{
        var defaultCss3=pop.get(0).style.webkitTransform;
        pop.show();
        pop.get(0).style.transform='translate(0,0) scale(0)'+defaultCss3;
        pop.get(0).style.transform='translate(0,0) scale(0)'+defaultCss3;
        pop.get(0).style.webkitTransform='translate('+($(window).width()-pop.outerWidth())/2+'px,'+($(window).height()-pop.height())/2+'px) scale(0)'+defaultCss3;
        pop.get(0).style.webkitTransform='translate('+($(window).width()-pop.outerWidth())/2+'px,'+($(window).height()-pop.height())/2+'px) scale(0)'+defaultCss3;
        setTimeout(function(){
            pop.get(0).style.transform='translate('+($(window).width()-pop.outerWidth())/2+'px,'+($(window).height()-pop.height())/2+'px) scale(1)'+defaultCss3;
            pop.get(0).style.webkitTransform='translate('+($(window).width()-pop.outerWidth())/2+'px,'+($(window).height()-pop.height())/2+'px) scale(1)'+defaultCss3;
        },300)
    }

    callback && callback();

    if(options.is_Bodyscroll!='false'){
        document.body.style.overflow='hidden';
    }
}

