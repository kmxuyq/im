/**
 * Created by Administrator on 2016/6/16 0016.
 */
// 范例    alertPopWin('信息错误请重新输入',function(){ window.location.href="/wap_shop/index.php?act=member_weixin&op=singledog"});
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

/**
 * 返回上一步历史消息
 */
$(function(){
    $('.top-header .go-perv-btn').click(function(){
        history.go(-1);
    });
});


/**/
function tabFn(args){
    var args={
        tt:args.tt,
        tb:args.tb,
        _index:args._index
    };

    $(args.tt).eq(args._index).addClass('on').siblings().removeClass('on');
    $(args.tb).hide().eq(args._index).show();

    $(args.tt).click(function(){
        var _thisIndex=$(this).index();
        $(this).addClass('on').siblings().removeClass('on');
        $(args.tb).hide().eq(_thisIndex).show();
    });
}