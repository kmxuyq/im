<?php defined('InShopNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>我的评价</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/lightGallery.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/lightGallery.js"></script>
<style type="text/css">
  .my_evaluation_empty{    transform: translateY(0%);margin-top: -50%;}
</style>
</head>
<?php $starnum = array('zorestar','onestar','twostar','threestar','fourstar','fivestar'); ?>
<body class="bg_gray">
<!--我的评价-->
<div class="my_evaluation">
  <!--评价空-->
  <?php  if (count($output['list'])==0) { ?>
    <div class="my_evaluation_empty">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/npj_03.png" />
        <p>您还没有填写任何评价</p>
    </div>
  <?php } else { ?>
    <!--全部评价盒子-->
    <div class="evaluation_box evaluation_box0">
        <ul>
            <?php foreach($output['list'] as $evaluation_good){ ?>
            <li>
                <div class="goods_score_wrap">
                    <a href="javascript:;" class="goods_score">
                        <div class="pic"><div class="self_width"><img src="<?php echo cthumb($evaluation_good['geval_goodsimage'], 60); ?>" /></div></div>
                        <div class="pic_dis">
                            <div class="title"><?php echo $evaluation_good['geval_goodsname']; ?></div>
                            <div class="score_wrap"><span class="score"><span class="<?php echo $starnum[$evaluation_good['geval_scores']]; ?>"></span><!--给span添加 onestar一颗星，twostar两颗星，threestar三颗星，fourstar四颗星，fivestar五颗星--></span></div>
                        </div>
                    </a>
                    <p class="evaluation_txt"><?php echo $evaluation_good['geval_content']; ?>...</p>
                    <?php if(!empty($evaluation_good['geval_image'])) {?>
                    <?php $geval_images = explode(',', $evaluation_good['geval_image']); ?>
                    <div class="choice_updata_pic">
                        <?php foreach($geval_images as $geval_image){ ?>
                        <div class="single_pics"><div class="gd_widht"><img src="<?php echo  C('remote_upload_url').$geval_image;?>" /></div></div>
                        <?php } ?>
                        <div class="clear"></div>
                    </div>
                    <?php } ?>
                    <div class="evaluation_time">
                        <span class="data"><?php echo date('Y-m-d',$evaluation_good['geval_addtime']); ?></span>
                        <span class="time"><?php echo date('H:i:s',$evaluation_good['geval_addtime']); ?></span>
                    </div>
                    <?php if(!empty($evaluation_good['geval_explain'])) {?>
                    <div class="reply">
                        <div class="reply_top">
                            <span class="people">&#91;GF&#93; 回复</span> <!-- <span class="data"></span> -->
                        </div>
                        <p class="reply_txt"><?php echo $evaluation_good['geval_explain']; ?></p>
                    </div>
                    <?php } ?>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
  <?php } ?>
</div>
<script>
$(function(){
  //选择评论类型
  $(".my_evaluation_navs a").click(function(){
    var index=$(this).index();
    $(this).addClass("sel").siblings().removeClass("sel");
    $(".evaluation_box").hide();
    $(".evaluation_box"+index).show();
    })

  //点小图预览大图
  $(".choice_updata_pic .single_pics").on("click",function(){
    $("#auto-loop").remove();
    var index=$(this).index();
    var html1="<ul id='auto-loop' class='gallery'>";
    var html2='';
    var html3="</ul>";
    $(this).parent().find(".single_pics").each(function(){
      var src=$(this).find("img").attr("src");
      html2+='<li data-src="'+src+'"><img src="'+src+'" /></li>'
      })
    var html=html1+html2+html3;
    $("body").append(html);

    $("#auto-loop").lightGallery({
      loop:true, //是否循环播放
      auto:false,   //是否自动播放
      pause:3000,   //自动播放时，停留时间
      easing: 'ease-in-out',   //播放的动画类型
      speed : 500,    //图片滚动速度
      counter:true,   //是否显示图片的数量
      swipethreshold:50
    });
    $("#auto-loop li").eq(index).trigger("click");
    })
  })
</script>
</body>
</html>
