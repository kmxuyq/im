
<!--menu-btn-->
<div class="nav_touch"></div>

<!--menu-->
<div class="index_menu">
    <a class="nav_icon1" href="index.php?act=show_store&op=<?php if($output['share_shop']){echo 'share';}else{ echo 'index';}?>&store_id=<?php echo $output['route_store_id'];?>">
        <span>首页</span>
    </a>
    <a class="nav_icon2" href="index.php?act=member_vr_order">
        <span>我的订单</span>
    </a>
    <a class="nav_icon4" href="index.php?act=member&op=home">
        <span>个人中心</span>
    </a>
    <a class="nav_icon3" href="index.php?act=article&op=article&ac_id=2">
        <span>帮助中心</span>
    </a>
</div>

<link rel="stylesheet" href="/wap_shop/templates/default/css/foot_font/iconfont.css">
<style>
    .nav_touch {
        width: 75px;
        height: 75px;
        background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/nav_touch.png") no-repeat center;
        position: fixed;
        top: 200px;
        left: 0;
        background-size: contain;
        box-shadow: 0px 0px 14px 1px rgba(0, 0, 0, 0.5);
        border-radius: 50px;
        overflow: hidden;
        z-index: 9999;
        display: none
    }
    .index_menu {
        position: fixed;
        z-index: 200;
        left: -90px;
        top: 0;
        width: 80px;
        height: 100%;
        background: rgba(30, 41, 52, 0.6);
        box-sizing: border-box;
        padding-top: 50px;
        display: none;
    }
    .index_menu .number_cell {
        background: #3b84ed;
        width: 25px;
        height: 25px;
        line-height: 25px;
        border-radius: 20px;
        overflow: hidden;
        text-align: center;
        color: #fff;
        font-family: Arial, "Microsoft YaHei";
        display: block;
        font-size: 14px;
        margin: 6px 11px 0 0px;
        float: right;
    }
    .index_menu a {
        margin: 28% auto;
        box-sizing: border-box;
        padding-top: 90px;
        text-align: center;
        color: #fff;
        font-family: "Microsoft Yahei";
        font-size: 14px;
    }
    .nav_icon1 {
        width: 65px;
        height: 100px;
        background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/nav_icon1.png") no-repeat center;
        background-size: contain;
        display: block;
    }
    .nav_icon2 {
        width: 65px;
        height: 100px;
        background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/nav_icon2.png") no-repeat center;
        background-size: contain;
        display: block;
    }
    .nav_icon3 {
        width: 65px;
        height: 100px;
        background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/nav_icon3.png") no-repeat center;
        background-size: contain;
        display: block;
    }
    .nav_icon4 {
        width: 65px;
        height: 100px;
        background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/nav_icon4.png") no-repeat center;
        background-size: contain;
        display: block;
    }
    .footer_menu{
        height: 51px;
        position:fixed;
        bottom: 0;
        left:0;
        background: #fff;
        width: 100%;
        border-top: 1px #e4e4e4 solid;
        padding-top:3px;
        box-sizing: content-box;
    }
    .footer_menu a{ text-decoration: none}
    .footer_menu li{
        height: 50px;
        width:33.33%;
        float: left;
    }

    .footer_menu .icon1{background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/foot_menu_icon1.jpg") no-repeat center;  background-size: 25px; }
    .footer_menu .icon2{background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/foot_menu_icon2.jpg") no-repeat center;  background-size: 25px; }
    .footer_menu .icon3{background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/foot_menu_icon3.jpg") no-repeat center;  background-size: 25px; }

    .footer_menu .rows.on .icon1{background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/foot_menu_icon1_hover.jpg") no-repeat center;  background-size: 25px; }
    .footer_menu .rows.on .icon2{background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/foot_menu_icon2_hover.jpg") no-repeat center;  background-size: 25px; }
    .footer_menu .rows.on .icon3{background: url("<?php echo SHOP_TEMPLATES_URL;?>/style/images/foot_menu_icon3_hover.jpg") no-repeat center;  background-size: 25px; }
    .footer_menu .rows.on span{color: #3b84ed}
    .footer_menu i{
        width: 30px;
        height: 30px;
        display: block;
        margin: 0 auto;
        color: #fff;
        text-align: center;
        font-size: 24px;
        position: relative;
    }
    .footer_menu span{
        display: block;
        margin: 0 auto;
        text-align: center;
        height: 20px;
        line-height: 20px;
        overflow: hidden;
        color: #333;
        font-size: 12px;
        font-weight: 300;
    }
    body{}
</style>
<script>
    $('body').css({
        'padding-bottom':'50px'
    });
    $(function(){
        nav();
        document.ontouchmove=false;
    });

    function nav(){
        var touch_cell=$('.nav_touch').get(0);

        var iScroll=0;


        var showMenu=false;

        touch_cell.ontouchstart=function(e){


            try{
                myScroll.destroy();
            }catch(e){

            }

            e.preventDefault();

            showMenu=true;
            var distenceX= e.changedTouches[0].pageX - $('.nav_touch').position().left ;
            var distenceY= e.changedTouches[0].pageY - $('.nav_touch').position().top;

            touch_cell.ontouchmove=function(e){
                movePageX=e.changedTouches[0].pageX-distenceX;
                if(movePageX<-5 || movePageX>5){

                    showMenu=false;
                }

                var x = e.changedTouches[0].pageX - distenceX;
                var y =e.changedTouches[0].pageY - distenceY-$(window).scrollTop();

                if(x>$(window).width()-$('.nav_touch').width()){
                    x=$(window).width()-$('.nav_touch').width();
                }else if(x<0){
                    x=0;
                };

                if(y>$(window).height()-$('.nav_touch').height()){
                    y=$(window).height()-$('.nav_touch').height();
                }else if(y<0){
                    y=0;
                };


                $('.nav_touch').css({
                    'left':x+'px',
                    'top':y+'px'
                });

            };
            touch_cell.ontouchend=function()
            {
                touch_cell.ontouchmove=null;

                try{
                    scrollPullDown();
                }catch(e){

                }
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
</script>
<ul class="footer_menu">
    <li class="rows">
        <a href="index.php?act=show_store&op=<?php if($output['share_shop']){echo 'share';}else{ echo 'index';}?>&store_id=<?php echo $output['route_store_id'];?>">
        <i class="icon1"></i>
        <span>首页</span>
    </a>
    </li>
        <li class="rows">
        <a class="" href="index.php?act=member_vr_order">
            <i class="icon2" style="font-size: 26px"></i>
            <span>订单中心</span>
        </a>
    </li>
    <li class="rows">
        <a class="" href="index.php?act=member&op=home">
            <i class="icon3"></i>
            <span>个人中心</span>
        </a>
    </li>
</ul>
<script>
    //设置当前页面栏目高亮显示
    $(".footer_menu li a").each(function(){
        $this = $(this);
        if($this[0].href==String(window.location)){
            $this.parent().addClass("on");
        }
    })
</script>





