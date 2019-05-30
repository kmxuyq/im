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

//选项卡
(function($){
    var TabFn=function(json){
        this.tabTt=$(json.tt);
        this.tabBd=$(json.tb);
        this._index=json._index || 0;

        this.tab();
        this.showView();
    };

    TabFn.prototype.tab=function(){
        var _this=this;
        this.tabTt.click(function(){
            var _thisIndex=$(this).index();
            $(this).addClass('on').siblings().removeClass('on');
            $(_this.tabBd).hide().eq(_thisIndex).show();
        })
    };

    TabFn.prototype.showView=function(){
        this.tabTt.eq(this._index).addClass('on').siblings().removeClass('on');
        $(this.tabBd).hide().eq(this._index).show();
    };

    TabFn.init=function(json){
        new this(json);
    };
    window['TabFn']=TabFn;
})(jQuery);


/*鸟菜单*/
function nav(){
    var touch_cell=$('.nav_touch').get(0);

    var iScroll=0;


    var showMenu=false;

    touch_cell.ontouchstart=function(e){

        e.preventDefault();

        showMenu=true;
        var distenceX= e.changedTouches[0].pageX - $('.nav_touch').position().left ;
        var distenceY= e.changedTouches[0].pageY - $('.nav_touch').position().top;

        document.ontouchmove=function(e){

            movePageX=e.changedTouches[0].pageX-distenceX;
            if(movePageX<-5 || movePageX>5){

                showMenu=false;
            }

            var x = e.changedTouches[0].pageX - distenceX;
            var y =e.changedTouches[0].pageY - distenceY;

            if(x>$(document).width()-$('.nav_touch').width()){
                x=$(document).width()-$('.nav_touch').width();
            }else if(x<0){
                x=0;
            };

            if(y>$(document).height()-$('.nav_touch').height()){
                y=$(document).height()-$('.nav_touch').height();
            }else if(y<0){
                y=0;
            };


            $('.nav_touch').css({
                'left':x+'px',
                'top':y+'px'
            });

        };
        document.ontouchend=function()
        {
            document.ontouchmove=null;
            if(showMenu==true){
                showMemnu();
            }
        };
    };
    //显示菜单
    function showMemnu(){
        if($('.index_menu').is(":hidden")){
            $('.index_menu').show();
            $('.index_menu').animate({left:0},300);
        }else{
            $('.index_menu').animate({left:-90},300,function(){
                $('.index_menu').hide();
            });
        }
    }
};