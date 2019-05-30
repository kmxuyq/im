<title><?php echo $output['title']; ?></title>
<style type="text/css">
@media screen and (min-width:320px) {
    html {
        font-size:17.06px;
    }
}

@media screen and (min-width:414px) {
    html {
        font-size:22.08px;
    }
}

@media screen and (min-width:600px) {
    html {
        font-size:32px;
    }
}

@media screen and (min-width:750px) {
    html {
        font-size:40px;
    }
    body.draw-bg{
        background-size:cover;
    }
}

@media screen and (min-width:768px) {
    html {
        font-size:40.96px;
    }
}

@media screen and (min-width:1280px) {
    html {
        font-size:68.26px;
    }
}
/*我的二维码*/
.my_two_stage_code{position: relative;}
.two_stage_code{ width: 9.8rem;margin:0 auto; position: relative;margin-top: 6rem;padding: 0;height:auto;} 
.two_stage_code .img_fx{width: 9.8rem;height:9.8rem;}
.my_two_stage_code p{width: 9.8rem;font-size: 0.65rem; color:#bba059;margin:0 auto;padding-top:0.6rem;text-align: center;}
.two_stage_code .left_bg{position: absolute;left:-3.5rem; top:0;width:3.5rem;height:9.5rem;background: url("<?php echo SHOP_TEMPLATES_URL;?>/images/left.jpg") no-repeat center center;background-size:cover;}
.two_stage_code .right_bg{position: absolute;right:-3.5rem; top:0;width:3.5rem;height:9.5rem;background: url("<?php echo SHOP_TEMPLATES_URL;?>/images/right.jpg") no-repeat center center;background-size:cover;}

/* 我的云南码 */
.ynma{padding: 2.5rem;color: #666666;}
.footer ul {
    margin: 0;
    padding: 0;
}
.footer ul li a {
    text-decoration: none;
}
</style>
<!--我的二维码-->
<div class="ys">
    <div class="my_two_stage_code">
        <div class="two_stage_code clearfix">
            <img class="img_fx" src="<?php echo $output['erweima']; ?>" />
            <div class="left_bg"></div>
            <div class="right_bg"></div>
        </div>
        <p>扫一扫,享受更多优惠！</p>
    </div>
</div>