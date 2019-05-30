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
  <title>商城首页</title>
  <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/Gift_details.css">
  <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
  <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
  <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
  <style type="text/css">
  .Exchange { margin-bottom:20px; }
  .img img{height:auto !important}
  </style>
</head>

<body>

<div class="box">
    <div class="img">
        <img src="<?php echo str_replace('_mid', '',$output['prodinfo']['pgoods_image']);?>">
    </div>
  <div class="Details">
    <h2><?php echo $output['prodinfo']['pgoods_name'];?></h2>
    <p>所需积分<img class="sr_Gold" src="<?php echo SHOP_TEMPLATES_URL;?>/images/Gold.png"><strong><?php echo $output['prodinfo']['pgoods_points']; ?></strong><b>￥<?php echo $output['prodinfo']['pgoods_price']; ?></b></p>
    <?php if ($output['prodinfo']['pgoods_islimittime'] == 1){ ?>
    <p>兑换时间<span><?php echo date('Y-m-d',$output['prodinfo']['pgoods_starttime']); ?>至<?php echo date('Y-m-d', $output['prodinfo']['pgoods_endtime']); ?></span></p>
    <?php } ?>
    <?php if ($output['prodinfo']['pgoods_islimit'] == 1){ ?>
    <p>限量兑换<span>最多每个会员可以兑换<?php echo $output['prodinfo']['pgoods_limitnum']; ?>个</span></p>
    <?php } ?>
    <p>礼品编号<span><?php echo $output['prodinfo']['goodsserial']; ?></span></p>
    <input type="hidden" id="storagenum" value="<?php echo $output['prodinfo']['pgoods_storage']; ?>"/>
    <?php if ($output['prodinfo']['pgoods_islimit'] == 1){?>
    <input type="hidden" id="limitnum" value="<?php echo $output['prodinfo']['pgoods_limitnum']; ?>"/>
    <?php } else {?>
    <input type="hidden" id="limitnum" value=""/>
    <?php } ?>
  </div>
  <footer class="Exchange">
    去兑换
  </footer>
  <div class="G_color">
    <div class="Gift">
      <ul>
        <li class="sel">礼品介绍</li>
        <li class="borderRnone">兑换记录</li>
        <div class="clear"></div>
      </ul>
      <div class="goods_type_box goods_type_box1">
          <?php echo $output['prodinfo']['pgoods_body']; ?>
      </div>
      <div class="goods_type_box goods_type_box2">
          <?php if ($output['orderprod_list'] && is_array($output['orderprod_list'])){ ?>
          <?php foreach ($output['orderprod_list'] as $v){ ?>
            <dl>
              <dt><img src="<?php echo $output['prodinfo']['pgoods_image'];?>" width="50" height="50"/></dt>
              <dd>
                <h2><?php echo $output['prodinfo']['pgoods_name'];?></h2>
                <!-- <p></p> -->
                <strong><?php echo date('Y-m-d H:i:s', $v['point_addtime']); ?></strong>
                <span>-<?php echo $output['prodinfo']['pgoods_points']; ?></span>
              </dd>
            </dl>
          <?php } ?>
          <?php } ?>
          </div>
      </div>
  </div>
  <div class="Popular_gifts">
    <div class="Hot_title">热门礼品</div>
      <?php if (is_array($output['recommend_pointsprod']) && count($output['recommend_pointsprod'])>0){?>
          <ul>
          <?php foreach ($output['recommend_pointsprod'] as $k=>$v){?>
            <li>
              <div class="Gift_left"><a target="_blank" href="<?php echo urlShopWAP('pointprod', 'pinfo', array('id' => $v['pgoods_id']));?>" title="<?php echo $v['pgoods_name']; ?>"> <img src="<?php echo $v['pgoods_image'] ?>" alt="<?php echo $v['pgoods_name']; ?>" /> </a></div>
              <div class="Gift_right">
                <h2><a href="<?php echo urlShopWAP('pointprod', 'pinfo', array('id' => $v['pgoods_id']));?>" target="_blank" tile="<?php echo $v['pgoods_name']; ?>"><?php echo $v['pgoods_name']; ?></a></h2>
                <div class="Money">
                  <span><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/Gold.png"><?php echo $v['pgoods_points']; ?></span>
                  <b>￥<?php echo $v['pgoods_price']; ?></b>
                  <img class="Refresh_gold" src="<?php echo SHOP_TEMPLATES_URL;?>/images/Grey_Gold.png">
                </div>
              </div>
            </li>
            <?php } ?>
          </ul>
      <?php }else{?>
          <div class="norecord"><?php echo $lang['pointprod_list_null'];?></div>
      <?php }?>
      <!-- <div class="pullup">
                <div class="pullupcons">
                    <div class="loading-3">
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                    </div>
                    <span class="msg">下拉加载更多</span>
                    <span id="over" style="display:none">已加载全部数据</span>
                </div>
      </div> -->
  </div>
</div>
     <div class="integral_problem">
        <div class="igl_ti" id="integral_problem_msg">您的积分不足,无法兑换此商品</div>
        <div class="igl_btn">
             <label class="igl_con" id="closeBtn">取消</label>
             <label class="igl_con igl_lk"><a href="index.php?act=pointcart">查看商品</a></label>
        </div>
     </div>
<script>
  $(".box .G_color .Gift>ul li").on("click",function(){
    var index=$(this).index()+1;
    $(this).addClass("sel").siblings("li").removeClass("sel");
    $(".goods_type_box"+index).show().siblings(".goods_type_box").hide();
  })
    //弹出登录
    $(".Exchange").hover(function () {
      $(this).stop().animate({
        opacity: '1'
      }, 600);
    }, function () {
      $(this).stop().animate({
        opacity: '0.8'
      }, 1000);
    }).on('click', function () {
      add_to_cart();
      
    });
    //取消
    $("#closeBtn").hover().on('click', function () {
      $(".integral_problem").fadeOut("fast");
      $("#mask").css({ display: 'none' });
    });
function showDialog_2 (msg) {
      $("#integral_problem_msg").text(msg);
      $("body").append("<div id='mask'></div>");
      $("#mask").addClass("mask").fadeIn("slow");
      $(".integral_problem").fadeIn("slow");
}
//加入购物车
function add_to_cart()
{
  var storagenum = parseInt($("#storagenum").val());//库存数量
  var limitnum = parseInt($("#limitnum").val());//限制兑换数量
  var quantity = parseInt(1);//兑换数量
  //验证数量是否合法
  var checkresult = true;
  var msg = '';
  if(!quantity >=1 ){//如果兑换数量小于1则重新设置兑换数量为1
    quantity = 1;
  }
  if(limitnum > 0 && quantity > limitnum){
    checkresult = false;
    msg = '<?php echo $lang['pointprod_info_goods_exnummaxlimit_error']; ?>';
  }
  if(storagenum > 0 && quantity > storagenum){
    checkresult = false;
    msg = '<?php echo $lang['pointprod_info_goods_exnummaxlast_error']; ?>';
  }
  if(checkresult == false){
    alert(msg);
    return false;
  }else{
    $.getJSON('index.php?act=pointcart&op=add&pgid=<?php echo $output['prodinfo']['pgoods_id']; ?>&quantity='+quantity, function(result){
          if(result.done){
            //window.location.href = 'index.php?act=pointcart';
            window.location.href = 'index.php?act=pointcart&op=address';
          } else {
            if(result.url){
              alert(result.msg);
              window.location.href = result.url;
            } else {
                alert(result.msg);
            }
          }
      });   
  }
}
</script>
</body>
</html>
