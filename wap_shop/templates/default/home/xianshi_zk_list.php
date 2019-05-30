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
<title>抢购列表</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
</head>

<body class="bg_gray">

<div class="integral_mall">
    <div class="search_results">
        <div class="search_results_nav_wrap fixed">
            <ul class="search_results_nav">
                <?php
                    $choice_class['groupbuy_list'] = '';
                    $choice_class['groupbuy_soon'] = '';
					$choice_class['groupbuy_history'] = '';

                    if($_GET['op']=='xianshi_zk_list') {
                        $choice_class['xianshi_zk_list'] = ' class="choice"';
                    } elseif($_GET['op']=='xianshi_zk_soon') {
                        $choice_class['xianshi_zk_soon'] = ' class="choice"';
                    } else {
                        $choice_class['xianshi_zk_history'] = ' class="choice"';
                    }
                ?>
                <li><a href="<?php echo urlShopWAP('show_groupbuy', 'xianshi_zk_list');?>"<?php echo $choice_class['xianshi_zk_list']; ?>>正在进行</a></li>
                <li><a href="<?php echo urlShopWAP('show_groupbuy', 'xianshi_zk_soon');?>"<?php echo $choice_class['xianshi_zk_soon']; ?>>即将开始</a></li>
                <li><a href="<?php echo urlShopWAP('show_groupbuy', 'xianshi_zk_history');?>"<?php echo $choice_class['xianshi_zk_history']; ?>>已经结束</a></li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>
</div>
<div class="potions_list">
    <!--抢购列表-->
	<?php if (!empty($output['groupbuy_list']) && is_array($output['groupbuy_list'])) { ?>
    <div class="order_states_list">
        <ul>
		<?php foreach ($output['groupbuy_list'] as $groupbuy) { ?>
            <li class="<?php echo $output['current']; ?>">
                <div class="middle_wrap">
                    <div class="middle">
                        <a href="javascript:;">
                            <div class="order_goods">
                                <div class="goods_pic">
								<div class="self_width">
		                        <a title="<?php echo $groupbuy['goods_name'];?>"
                                   href="index.php?act=show_groupbuy&op=xianshi_detail&group_id=<?php echo $groupbuy['xianshi_goods_id'];?>" class="pic-thumb" target="_blank"><img src="<?php echo XianShiThumb($groupbuy['goods_image'],'240'); ?>" alt=""></a>
								</div>
								</div>

                                <div class="goods_panic_buying">
								<a  href="index.php?act=show_groupbuy&op=xianshi_detail&group_id=<?php echo $groupbuy['xianshi_goods_id'];?>"
                                class="buy-button">点击进入</a>
								</div>

                                <div class="goods_dis">
                                    <div class="title">
									<h3 class="title"><a title="<?php echo $groupbuy['goods_name'];?>" href="index.php?act=show_groupbuy&op=xianshi_detail&group_id=<?php echo $groupbuy['xianshi_goods_id'];?>" target="_blank"><?php echo $groupbuy['goods_name'];?></a></h3>
									</div>


                                    <div class="goods_price">

                                    <span class="present_price"><i><?php echo $lang['currency'];?></i><em>.<?php echo $groupbuy['xianshi_price'] ;?></em></span>

                                    <div class="dock">
			                           <del class="original_price"><?php echo $lang['currency'].$groupbuy['goods_price'];?>
			                           </del>
			                        </div>

                                    <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
               </div>
            </li>
		<?php } ?>
        </ul>
    <?php } else { ?>
    <div class="no-content"><?php echo $lang['no_groupbuy_info'];?></div>
    <?php } ?>


    </div>
</div>

<!--公共底部导航-->
<div id="footer_html"></div>
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/open_popup.css" />
<script type="text/javascript" src="/wap/js/tmpl/footer_html.js"></script>

<script>

$(function(){
	//公共底部 点击选中效果范例展示
	$(".footer ul li").click(function(){
		$(this).find("a").addClass("selected").end().siblings().find("a").removeClass("selected");
		})

	//搜索框关闭按钮显示/隐藏 和事件
	$(".search input").keydown(function(){
		$(".empty").show();
		})
	$(".empty").on("click",function(){
		$(this).hide().siblings("input").val("").focus();
		})


	})
</script>
</body>
</html>


<!--
<?php require('groupbuy_head.php');?>

<form id="search_form">
  <input name="act" type="hidden" value="show_groupbuy" />
  <input name="op" type="hidden" value="<?php echo $_GET['op'];?>" />
  <input id="groupbuy_class" name="groupbuy_class" type="hidden" value="<?php echo $_GET['groupbuy_class'];?>"/>
  <input id="groupbuy_price" name="groupbuy_price" type="hidden" value="<?php echo $_GET['groupbuy_price'];?>"/>
  <input id="groupbuy_order_key" name="groupbuy_order_key" type="hidden" value="<?php echo $_GET['groupbuy_order_key'];?>"/>
  <input id="groupbuy_order" name="groupbuy_order" type="hidden" value="<?php echo $_GET['groupbuy_order'];?>"/>
</form>
<!--
<div class="nch-breadcrumb-layout" style="display: block;">
  <div class="nch-breadcrumb wrapper"> <i class="icon-home"></i> <span> <a href="<?php echo urlShopWAP(); ?>">首页</a> </span> <span class="arrow">></span> <span>线上抢</span></div>
</div>

<div class="ncg-container">
  <div class="ncg-category" id="ncgCategory">
    <h3>线上抢</h3>
    <ul>
<?php $i = 0; $names = $output['groupbuy_classes']['name']; foreach ((array) $output['groupbuy_classes']['children'][0] as $v) { if (++$i > 6) break; ?>
      <li><a href="<?php echo urlShopWAP('show_groupbuy', 'groupbuy_list', array('class' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
    </ul>
    <h3>虚拟抢</h3>
    <ul>
<?php $i = 0; $names = $output['groupbuy_vr_classes']['name']; foreach ((array) $output['groupbuy_vr_classes']['children'][0] as $v) { if (++$i > 6) break; ?>
      <li><a href="<?php echo urlShopWAP('show_groupbuy', 'vr_groupbuy_list', array('vr_class' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
    </ul>
  </div>

  <div class="ncg-content">
    <div class="ncg-nav">
      <ul>
        <li<?php if ($output['current'] == 'online') echo ' class="current"'; ?>><a href="<?php echo urlShopWAP('show_groupbuy', 'groupbuy_list');?>">正在进行</a></li>
        <li<?php if ($output['current'] == 'soon') echo ' class="current"'; ?>><a href="<?php echo urlShopWAP('show_groupbuy', 'groupbuy_soon');?>">即将开始</a></li>
        <li<?php if ($output['current'] == 'history') echo ' class="current"'; ?>><a href="<?php echo urlShopWAP('show_groupbuy', 'groupbuy_history');?>">已经结束</a></li>
      </ul>
    </div>

    <div class="ncg-screen">

<?php if ($output['groupbuy_classes']['children'][0]) { ?>
      <!-- 分类过滤列表 -->
	  <!--
      <dl>
        <dt>分类：</dt>
        <dd class="nobg<?php if (!($hasChildren = !empty($_GET['class']))) echo ' selected'; ?>"><a href="<?php echo dropParam(array('class', 's_class')); ?>"><?php echo $lang['text_no_limit']; ?></a></dd>
<?php $names = $output['groupbuy_classes']['name']; foreach ($output['groupbuy_classes']['children'][0] as $v) { ?>
        <dd<?php if ($hasChildren && $_GET['class'] == $v) echo ' class="selected"'; ?>><a href="<?php echo replaceAndDropParam(array('class' => $v), array('s_class')); ?>"><?php echo $names[$v]; ?></a></dd>
<?php } ?>
<?php if ($hasChildren && $output['groupbuy_classes']['children'][$_GET['class']]) { ?>
        <ul>
<?php foreach ($output['groupbuy_classes']['children'][$_GET['class']] as $v) { ?>
          <li<?php if ($_GET['s_class'] == $v) echo ' class="selected"'; ?>><a href="<?php echo replaceParam(array('s_class' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
        </ul>
<?php } ?>
      </dl>
<?php } ?>

      <!-- 价格过滤列表 -->
	  <!--
      <dl>
        <dt><?php echo $lang['text_price'];?>：</dt>
        <dd class="<?php echo empty($_GET['groupbuy_price'])?'selected':''?>"><a href="<?php echo dropParam(array('groupbuy_price'));?>"><?php echo $lang['text_no_limit'];?></a></dd>
        <?php if(is_array($output['price_list'])) { ?>
        <?php foreach($output['price_list'] as $groupbuy_price) { ?>
        <dd <?php echo $_GET['groupbuy_price'] == $groupbuy_price['range_id']?"class='selected'":'';?>> <a href="<?php echo replaceParam(array('groupbuy_price' => $groupbuy_price['range_id']));?>"><?php echo $groupbuy_price['range_name'];?></a> </dd>
        <?php } ?>
        <?php } ?>
      </dl>
      <dl class="ncg-sortord">
        <dt>排序：</dt>
        <dd class="<?php echo empty($_GET['groupbuy_order_key'])?'selected':''?>"><a href="<?php echo dropParam(array('groupbuy_order_key', 'groupbuy_order'))?>"><?php echo $lang['text_default'];?><i></i></a></dd>
        <dd <?php echo $_GET['groupbuy_order_key'] == '1'?"class='selected'":'';?>><a <?php echo $_GET['groupbuy_order_key'] == '1'?"class='". ($_GET['groupbuy_order'] == 1 ? 'asc' : 'desc') ."'":'';?> href="<?php echo ($_GET['groupbuy_order_key'] == '1' && $_GET['groupbuy_order'] == '2' ? replaceParam(array('groupbuy_order_key' => '1', 'groupbuy_order' => '1')) : replaceParam(array('groupbuy_order_key' => '1', 'groupbuy_order' => '2')));?>"><?php echo $lang['text_price'];?><i></i></a></dd>
        <dd <?php echo $_GET['groupbuy_order_key'] == '2'?"class='selected'":'';?>><a <?php echo $_GET['groupbuy_order_key'] == '2'?"class='". ($_GET['groupbuy_order'] == 1 ? 'asc' : 'desc') ."'":'';?> href="<?php echo ($_GET['groupbuy_order_key'] == '2' && $_GET['groupbuy_order'] == '2' ? replaceParam(array('groupbuy_order_key' => '2', 'groupbuy_order' => '1')) : replaceParam(array('groupbuy_order_key' => '2', 'groupbuy_order' => '2')));?>"><?php echo $lang['text_rebate'];?><i></i></a></dd>
        <dd <?php echo $_GET['groupbuy_order_key'] == '3'?"class='selected'":'';?>><a <?php echo $_GET['groupbuy_order_key'] == '3'?"class='". ($_GET['groupbuy_order'] == 1 ? 'asc' : 'desc') ."'":'';?> href="<?php echo ($_GET['groupbuy_order_key'] == '3' && $_GET['groupbuy_order'] == '2' ? replaceParam(array('groupbuy_order_key' => '3', 'groupbuy_order' => '1')) : replaceParam(array('groupbuy_order_key' => '3', 'groupbuy_order' => '2')));?>"><?php echo $lang['text_sale'];?><i></i></a></dd>
      </dl>
    </div>

    <?php if (!empty($output['groupbuy_list']) && is_array($output['groupbuy_list'])) { ?>
    <!-- 抢购活动列表
    <div class="group-list">
      <ul>
        <?php foreach ($output['groupbuy_list'] as $groupbuy) { ?>

        <li class="<?php echo $output['current']; ?>">
          <div class="ncg-list-content">

		  <a title="<?php echo $groupbuy['groupbuy_name'];?>" href="<?php echo $groupbuy['groupbuy_url'];?>" class="pic-thumb" target="_blank"><img src="<?php echo gthumb($groupbuy['groupbuy_image'],'mid');?>" alt=""></a>

            <h3 class="title"><a title="<?php echo $groupbuy['groupbuy_name'];?>" href="<?php echo $groupbuy['groupbuy_url'];?>" target="_blank"><?php echo $groupbuy['groupbuy_name'];?></a></h3>

            <?php list($integer_part, $decimal_part) = explode('.', $groupbuy['groupbuy_price']);?>
            <div class="item-prices"> <span class="price"><i><?php echo $lang['currency'];?></i><?php echo $integer_part;?><em>.<?php echo $decimal_part;?></em></span>
              <div class="dock"><span class="limit-num"><?php echo $groupbuy['groupbuy_rebate'];?>&nbsp;<?php echo $lang['text_zhe'];?></span> <del class="orig-price"><?php echo $lang['currency'].$groupbuy['goods_price'];?></del></div>
              <span class="sold-num"><em><?php echo $groupbuy['buy_quantity']+$groupbuy['virtual_quantity'];?></em><?php echo $lang['text_piece'];?><?php echo $lang['text_buy'];?></span><a href="<?php echo $groupbuy['groupbuy_url'];?>" target="_blank" class="buy-button"><?php echo $output['buy_button'];?></a></div>
          </div>
        </li>
        <?php } ?>
      </ul>
    </div>
	<!--
    <div class="tc mt20 mb20">
      <div class="pagination"><?php echo $output['show_page'];?></div>
    </div>
    <?php } else { ?>
    <div class="no-content"><?php echo $lang['no_groupbuy_info'];?></div>
    <?php } ?>
  </div>
</div>
-->
