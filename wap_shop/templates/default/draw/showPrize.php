<?php defined('InShopNC') or exit('Access Invalid!');?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $output['html_title']?></title>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="一元抢购，伊美假日"/>
    <meta name="author" content="伊美假日"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
    <link rel="stylesheet"      type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/draw/reset.css">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/draw/public.css">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/draw/draw.css">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/draw/less.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/draw/main.js"></script>
</head>
<body style="background: #da0837">
<div class="draw_tt">
    <h1><?php echo $lang['activity_past'] ;?></h1>
</div>
<?php if(!empty($output['list'])){?>
    <?php foreach ($output['list'] as $k =>$val){?>
        <!-- <span>ID号:<?php //echo $val['activity_detail_id'];?></span>
          <span>几等奖:<?php //echo $val['activity_detail_sort'];?></span>
          <span>奖品:<?php //echo $val['goods_name'];?></span>
          <span>奖品Id:<?php //echo $val['goods_id'];?></span>
          <span>奖品图片:<?php //echo $val['goods_image'];?></span>
          <span>中奖:<?php //echo $val['member_id'];?></span>
         -->
       <div class="newyear_draw_img animate_wrap" title="<?php echo $val['goods_id'];?>">
            <?php if($val['activity_detail_sort'] == '1'){?>
              <img src="<?php echo SHOP_TEMPLATES_URL;?>/img/draw/newyear_draw_img1.jpg" 
		          alt="<?php echo $val['activity_detail_sort'];?>" />
	        <?php }else if($val['activity_detail_sort'] == '2'){?>
	          <img src="<?php echo SHOP_TEMPLATES_URL;?>/img/draw/newyear_draw_img2.jpg" 
		         alt="<?php echo $val['activity_detail_sort'];?>" />
	        <?php }else{?>
	            <img src="<?php echo SHOP_TEMPLATES_URL;?>/img/draw/newyear_draw_img3.jpg" 
		          alt="<?php echo $val['activity_detail_sort'];?>" />
	        <?php }?>
		    <div class="startTime">
		       <?php echo date('m月d日  H:i',$output['ruleData']['activity_time'])?>开始
		    </div>
		    <a class="link" href="index.php?act=activity_person&op=myDrawPrize&activity_id=<?php echo $output['ruleData']['activity_id'];?>&store_id=<?php echo $output['ruleData']['store_id'];?>&activity_detail_sort=<?php echo $val['activity_detail_sort'];?>">
		    <img src="<?php echo SHOP_TEMPLATES_URL;?>/img/draw/newyear_draw_btn.jpg">
	   </a>
	</div>
	<div class="top_product_detail animate_wrap" >
	    <p class="tt"><span>奖品详情</span><em class="icon"></em></p>
	    <ul class="content">
	        <?php if($val['activity_detail_sort'] == '1'){?>
		        <li><span>花之城豪生国际大酒店客房礼券（1张）        </span><em>￥1280</em></li>
		        <li><span>海埂公园直升机体验券（1张）       </span><em>  ￥1000</em></li>
		        <li><span>《云南映像》VIP票（4张）       </span><em>   ￥1920</em></li>
		        <li><span>《云南的响声》VIP票（4张）       </span><em> ￥1520</em></li>
		        <li><span>《黄山映像》VIP票（10张）       </span><em> ￥3800</em></li>
		        <li><span>云南民族村门票（2张）       </span><em> ￥180</em></li>
		        <li><span>《高原的呼唤》入场券（2张）       </span><em>  ￥300</em></li>
		        <li><span>大观公园门票（4张）        </span><em> ￥32</em></li>
		        <li><span>西山风景区门票（1张）       </span><em> ￥40</em></li>
		        <li><span>金殿风景区门票（3张）        </span><em>￥90</em></li>
		        <li><span>世博园门票兑换卷（1张）        </span><em>   ￥100</em></li>
		        <li><span>石林风景区赠票（2张）       </span><em> ￥350</em></li>
		        <li><span>石林地质博物馆参观券（4张）       </span><em>  ￥480</em></li>
		        <li><span>水上石林旅游度假村消费抵用券（4张）        </span><em> ￥792</em></li>
		        <li><span>石林冰雪海洋世界成人票（2张）       </span><em> ￥300</em></li>
		        <li><span>古滇游船船票（1张）       </span><em>  ￥88</em></li>
		        <li><span>花之城花花卡（1张）        </span><em> ￥50</em></li>
		        <li><span>轿子山景区门票（1张）       </span><em> ￥54</em></li>
		        <li><span>总计各类票券（48张）                    </span><em>￥12376</em></li>
	        <?php }else if($val['activity_detail_sort'] == '2'){?>
	             <li><span>《云南映像》VIP票（3张）   </span><em>￥1440</em></li>
		         <li><span>《云南的响声》VIP票（3张） </span><em>￥1140</em></li>
		         <li><span>《黄山映像》VIP票（14张） </span><em>￥5320</em></li>
		         <li><span>云南民族村门票（1张） </span><em>￥90</em></li>
		         <li><span>《高原的呼唤》入场券（1张）  </span><em>￥150</em></li>
		         <li><span>大观公园门票（2张）  </span><em>￥16</em></li>
		         <li><span>西山风景区门票（1张） </span><em>￥40</em></li>
		         <li><span>金殿风景区门票（2张） </span><em>￥60</em></li>
		         <li><span>世博园门票兑换卷（1张）    </span><em>￥100</em></li>
		         <li><span>石林风景区赠票（2张） </span><em>￥350</em></li>
		         <li><span>石林地质博物馆参观券（2张）  </span><em>￥240</em></li>
		         <li><span>水上石林旅游度假村消费抵用券（2张）  </span><em>￥396</em></li>
		         <li><span>石林冰雪海洋世界成人票（3张） </span><em>￥450</em></li>
		         <li><span>古滇游船船票（1张）  </span><em>￥88</em></li>
		         <li><span>花之城花花卡（1张）  </span><em>￥50</em></li>
		         <li><span>轿子山景区门票（1张） </span><em>￥54</em></li>
		         <li><span>总计各类票券（40张）             </span><em>￥9984</em></li>
	        <?php }else{?>
	            <li><span>《云南映像》VIP票（3张）  </span><em>￥1440</em></li>
		        <li><span>《云南的响声》VIP票（3张） </span><em>￥1140</em></li>
		        <li><span>《黄山映像》VIP票（10张） </span><em>￥3800</em></li>
		        <li><span>云南民族村门票（1张） </span><em>￥90</em></li>
		        <li><span>《高原的呼唤》入场券（1张）  </span><em>￥150</em></li>
		        <li><span>大观公园门票（2张）  </span><em>￥16</em></li>
		        <li><span>西山风景区门票（1张） </span><em>￥40</em></li>
		        <li><span>金殿风景区门票（2张） </span><em>￥60</em></li>
		        <li><span>世博园门票兑换卷（1张）    </span><em>￥100</em></li>
		        <li><span>石林风景区赠票（1张） </span><em>￥175</em></li>
		        <li><span>石林地质博物馆参观券（2张）  </span><em>￥240</em></li>
		        <li><span>水上石林旅游度假村消费抵用券（2张）  </span><em>￥396</em></li>
		        <li><span>石林冰雪海洋世界成人票（2张） </span><em>￥300</em></li>
		        <li><span>古滇游船船票（1张）  </span><em>￥88</em></li>
		        <li><span>花之城花花卡（1张）  </span><em>￥50</em></li>
		        <li><span>轿子山景区门票（1张） </span><em>￥54</em></li>
		        <li><span>总计各类票券（34张）             </span><em>￥8139</em></li>
	        <?php }?>
	    </ul>
	</div>
    <?php }?>
<?php }else{?>
     <div class="newyear_draw_img animate_wrap">
        <div class="startTime"><?php echo $lang['no_record'];?></div>
     </div>
<?php }?>
<div class="newyear_info animate_wrap">
    <img src="<?php echo SHOP_TEMPLATES_URL;?>/img/draw/draw_info.jpg" alt="" class="tt">
     <div class="content">
        <p id="hh"></p>
        <p><span><?php echo $lang['activity_time'] ;?></span>
            2016-11-11 14:00至2016-11-11 18:00
        </p>
        <p>
            <span><?php echo  $lang['activity_desc'] ;?></span><br/>
             <div style="color: #fff; line-height:20px;"> 1、扫描二维码进行活动签到即获得抽奖资格；</div>
			 <div style="color: #fff; line-height:20px;">2、一、二、三等奖，抽奖顺序按现场主持人引导进行；</div>
			 <div style="color: #fff; line-height:20px;">3、抽奖过程中点击加运气按钮可提高中奖概率哦；</div>
			 <div style="color: #fff; line-height:20px;">4、中奖名单会在相应的抽奖页面底部实时显示。</div>
        </p>
    </div>
</div>
<script>
$(function(){
    $('.animate_wrap').css({
        opacity:0,
        transform:'scale(0)'
    });
    loadindgImg(0);
    function loadindgImg(number){
        if(number>=$('.animate_wrap').length) return;
        setTimeout(function(){
            $('.animate_wrap').eq(number).css({
                opacity:1,
                transform:'scale(1)'
            });
            number++;
            loadindgImg(number)
        },300)
    }
    $('.top_product_detail .tt').click(function(){
        var _detail=$(this).next('.content')
        if(_detail.is(":hidden")){
            $(this).addClass('on')
            _detail.slideDown()
        }else{
            $(this).removeClass('on')
            _detail.slideUp()
        }
    })
})
</script>
<script type="text/javascript">
var timeID;
function gett(){
	$(function(){
		$.ajax({
			url:'index.php?act=activity_person&op=getCountdown',
            data:{activitytime:<?php echo $output['ruleData']['activity_time'] ?>},
			type:"POST",
			dataType:"json",
			timeout:3000,
			cache:false,
			success:function(data){
				if(data.state == "1" || data.state == 1 ){
					$('#hh').text("距离活动开始时间还有: "+data.h+"小时 "+data.m+"分 "+data.s+"秒");
				}else{
					$('#hh').text("抽奖活动正在进行中");
					clearTimeout(timeID);
				}
			 }
			})
	});
	timeID = setTimeout("gett()",1000);
}
gett();
</script>
</body>
</html>

