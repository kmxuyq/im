/**
 * Created by Administrator on 2016/6/8 0008.
 */
//banner
function bannerInit(){
    var intVal=true;

    // top:     scaleWh(高)+toppx
    // left:    leftpx-scaleWh(宽)
    var bs=$(window).width()/750;
    $('.banner-bg').hide().fadeIn(1000,function(){

        $('.index-baner .icon').css({'transform':'scale('+bs+')'});
        $('.index-baner .baner-icon-clouds2').css({top:-scaleWh(34,bs)+203*bs,left:252*bs-scaleWh(233,bs)});
        $('.index-baner .baner-icon-yydb').css({top:-scaleWh(90,bs)+68*bs,left:260*bs-scaleWh(235,bs),opacity:1});
        $('.index-baner .baner-icon-qq').css({top:-scaleWh(73,bs)+20*bs,right:28*bs-scaleWh(54,bs)});
        $('.index-baner .baner-icon-ceil').css({top:-scaleWh(76,bs)+0,left:20*bs-scaleWh(87,bs)});
        $('.index-baner .baner-icon-map').css({top:-scaleWh(120,bs)+59*bs,left:205*bs-scaleWh(103,bs),opacity:1,zIndex:599});

        animateWaves();
        function animateWaves() {
            $('.index-baner .baner-icon-clouds').animate({opacity:1},{ duration: 1500, easing: "linear" }).animate({opacity:0.3},{ duration: 2000, easing: "linear", complete: animateWaves });
            $('.index-baner .baner-icon-clouds2').animate({opacity:1},{ duration: 1300, easing: "linear" }).animate({opacity:0.8},{ duration: 2500, easing: "linear", complete: animateWaves });
        }

        setInterval(function(){
            if(intVal){
                intVal=false;
                $('.index-baner .baner-icon-qq').css({'transform':'scale('+(bs+0.2)+')',opacity:1});
            }else{
                intVal=true;
                $('.index-baner .baner-icon-qq').css({'transform':'scale('+(bs)+')',opacity:0.8});
            }
        },1500);
    });
    //
    function scaleWh(num,sc){
        return (num-num*sc)/2
    }


}