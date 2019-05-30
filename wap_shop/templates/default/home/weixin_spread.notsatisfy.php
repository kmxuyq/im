<title><?php echo $output['title']; ?></title>
<style type="text/css">
@media screen and (min-width:320px) {
    .ynma {
        font-size:17.06px;
    }
}

@media screen and (min-width:414px) {
    .ynma {
        font-size:22.08px;
    }
}

@media screen and (min-width:600px) {
    .ynma {
        font-size:32px;
    }
}

@media screen and (min-width:750px) {
    .ynma {
        font-size:40px;
    }
    body.draw-bg{
        background-size:cover;
    }
}

@media screen and (min-width:768px) {
    .ynma {
        font-size:40.96px;
    }
}

@media screen and (min-width:1280px) {
    .ynma {
        font-size:68.26px;
    }
}
/*我的二维码*/
.my_two_stage_code{position: relative;}
.two_stage_code{ width: 9.8rem;margin:0 auto; position: relative;margin-top: 6rem;} 
.two_stage_code .img_fx{width: 9.8rem;}
.two_stage_code p{color:#bba059;text-align: center;padding-top:0.6rem;}
.two_stage_code .left_bg{position: absolute;left:-3.5rem; top:0;width:3.5rem;height:9.5rem;background: url("http://s0.yimayholiday.com/YM_MB/img/left.jpg") no-repeat center center;background-size:cover;}
.two_stage_code .right_bg{position: absolute;right:-3.5rem; top:0;width:3.5rem;height:9.5rem;background: url("http://s0.yimayholiday.com/YM_MB/img/right.jpg") no-repeat center center;background-size:cover;}

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
<!--我的云南码-->
<div class="ynma">
    提示：只有单笔购物满<?php echo $output['money']; ?>元才可拥有云商码
</div>