<body ontouchstart>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.go(-1)" ></i>
    <h1 class="qz-color">用户评价</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <div class="qz-padding qz-padding-t"style="padding: 0">
        <div class="qz-con1 lbdb-user-msg">
            <div class="qz-t1 tt" ><?php echo $output['goods_evaluate_info']['good_percent'];?>%<font>好评</font></div>
            <p class="text">共有<?php echo $output['goods_evaluate_info']['all'];?>人参与评分</p>
            <div class="qz-bk10"></div>
            
            <ul class="qz-progress-list">
                <li class="clearfix">
                    <span>好评 </span><span>（<?php echo $output['goods_evaluate_info']['good_percent'];?>%）</span>
                </li>
                
                <li class="clearfix">
                    <span>中评 </span><span>（<?php echo $output['goods_evaluate_info']['normal_percent'];?>%）</span>
                </li>
                
                <li class="clearfix">
                    <span>差评 </span><span>（<?php echo $output['goods_evaluate_info']['bad_percent'];?>%）</span>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="qz-padding qz-padding-t ui-border-b clearfix">
        <span class="qz-fl qz-color" style="height: 40px;line-height: 55px;text-align: right;display: block;"><?php echo $output['goods_evaluate_info']['all'];?>条评价</span>
        <!--<span class="qz-fr">
            <button class="ui-btn ui-btn-primary qz-btn-padding" onClick="location.href='<?php if ($output['goods']['is_virtual']) { echo urlShopWap('member_vr_order', 'index');} else { echo urlShopWap('member_order', 'index');}?>'">  评 价  </button>
        </span>
		<span class="qz-fr">
		<?/*php  if($_SESSION['is_login']){if ($output['order_info']['if_evaluation']) {*/?>
            <a href="index.php?act=member_evaluate&op=add&order_id=<?php echo $output['order_info']['order_id']; ?>" class="ui-btn ui-btn-primary qz-btn-padding" >
            评 价  </a>
			<?/*php}}else{*/?><a href="<?php echo urlShopWap('login', 'index');?>'" class="ui-btn ui-btn-primary qz-btn-padding">评 价</a><?/*php}*/?>
        </span>-->
    </div>
    
    <div class="ui-tab">
        <ul class="ui-tab-nav ui-border-b">
            <li class="current">全部评价</li>
            <li>好评</li>
            <li>中评</li>
            <li>差评</li>
        </ul>
        
        <ul class="ui-tab-content" style="width:400%">
		 <li>
			 <?php if(!empty($output['goodsevallist']) && is_array($output['goodsevallist'])){?>
			
                <div class="qz-padding qz-padding-t">
                    <div class="qz-list">
                        <ul class="ui-list ui-list-pure ui-border-tb lbdb-user-msg_list">
            <?php foreach($output['goodsevallist'] as $k=>$v){?>
			<li class="ui-border-t">
                                <h4>
                                    <?php if($v['geval_isanonymous'] == 1){?>
                                        <?php echo str_cut($v['geval_frommembername'],2).'***';?>
                                    <?php }else{?>
                                        <?php echo $v['geval_frommembername'];?>
                                    <?php }?>
                                </h4>
                                <div class="qz-bk10"></div>
                                <p style="line-height: 20px">
                                    <span class="qz-color">
                                        <?php echo $v['geval_content'];?>
								    </span>
                                    <span class="qz-fr"><?php echo @date('Y-m-d',$v['geval_addtime']);?></span>
                                </p>
                            </li>
           
            <?php }?>
            </ul>
                    </div>
                </div>
            
            <?php }else{?>
            
            <?php }?>
			
			</li>
			
			<li>
            
                <?php if(!empty($output['goodsevallist3']) && is_array($output['goodsevallist1'])){?>
			 
                <div class="qz-padding qz-padding-t">
                    <div class="qz-list">
                        <ul class="ui-list ui-list-pure ui-border-tb">
            <?php foreach($output['goodsevallist3'] as $k=>$v){?>
			<li class="ui-border-t">
                                <h4><?php echo $v['geval_content'];?></h4>
                                <div class="qz-bk10"></div>
                                <p class="msg"><span class="qz-color">
								<?php if($v['geval_isanonymous'] == 1){?>
                  <?php echo str_cut($v['geval_frommembername'],2).'***';?>
                  <?php }else{?>
                  <?php echo $v['geval_frommembername'];?>
                  <?php }?>
								</span><span class="qz-fr"><?php echo @date('Y-m-d',$v['geval_addtime']);?></span></p>
                            </li>
           
            <?php }?>
            </ul>
                    </div>
                </div>
          
            <?php }else{?>
            
            <?php }?>
			  </li>
			  <li>
			 <?php if(!empty($output['goodsevallist2']) && is_array($output['goodsevallist2'])){?>
			 
                <div class="qz-padding qz-padding-t">
                    <div class="qz-list">
                        <ul class="ui-list ui-list-pure ui-border-tb">
            <?php foreach($output['goodsevallist2'] as $k=>$v){?>
			<li class="ui-border-t">
                                <h4><?php echo $v['geval_content'];?></h4>
                                <div class="qz-bk10"></div>
                                <p><span class="qz-color">
								<?php if($v['geval_isanonymous'] == 1){?>
                  <?php echo str_cut($v['geval_frommembername'],2).'***';?>
                  <?php }else{?>
                  <?php echo $v['geval_frommembername'];?>
                  <?php }?>
								</span><span class="qz-fr"><?php echo @date('Y-m-d',$v['geval_addtime']);?></span></p>
                            </li>
           
            <?php }?>
            </ul>
                    </div>
                </div>
            
            <?php }else{?>
            
            <?php }?>
			</li>
			<li>
			 <?php if(!empty($output['goodsevallist1']) && is_array($output['goodsevallist3'])){?>
			 
                <div class="qz-padding qz-padding-t">
                    <div class="qz-list">
                        <ul class="ui-list ui-list-pure ui-border-tb">
            <?php foreach($output['goodsevallist1'] as $k=>$v){?>
			<li class="ui-border-t">
                                <h4><?php echo $v['geval_content'];?></h4>
                                <div class="qz-bk10"></div>
                                <p><span class="qz-color">
								<?php if($v['geval_isanonymous'] == 1){?>
                  <?php echo str_cut($v['geval_frommembername'],2).'***';?>
                  <?php }else{?>
                  <?php echo $v['geval_frommembername'];?>
                  <?php }?>
								</span><span class="qz-fr"><?php echo @date('Y-m-d',$v['geval_addtime']);?></span></p>
                            </li>
           
            <?php }?>
            </ul>
                    </div>
                </div>
            
            <?php }else{?>
            
            <?php }?>
			</li>
        </ul>
    </div>
    <div id="menu"></div>
</section>


<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/zepto.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/ui.js"></script>  
<script type="text/javascript">
(function (){
    var tab = new fz.Scroll('.ui-tab', {
        role: 'tab',
        autoplay: false,
        interval: 3000
    });
		    /* 滑动开始前 */
    tab.on('beforeScrollStart', function(fromIndex, toIndex) {
        console.log(fromIndex,toIndex);// from 为当前页，to 为下一页
    })
})();
</script>
<style>
    .qz-progress-list{
        clear: both;
        margin-top: 10px;
        overflow: hidden;
        border-bottom: 1px #ccc solid;
        border-top: 1px #ccc solid;
        padding: 0 10px;
        text-align: center;
    }
    .qz-progress-list .clearfix {
        float: left;
        height: 30px;
        line-height: 30px;
        width: 33.33%;
        text-align: center;
        font-size: 14px;
    }
    .lbdb-user-msg .text{
        padding: 0 10px;;
        height: 25px;
        line-height: 25px;
    }
    .lbdb-user-msg .tt{
        height: 40px;
        padding: 0 10px;
        line-height: 40px;
        color: #3b84ed;
    }
    .lbdb-user-msg_list li{
        border-top: 1px #ebebeb solid;
        overflow: hidden;
        padding: 13px 10px;
        margin: 0;}
    .lbdb-user-msg_list li h4{
        height: 20px;
        line-height: 20px
    }
    .lbdb-user-msg_list li .msg{
         color: #999;
         line-height: 20px;
         height: 20px;
    }
    .lbdb-user-msg_list li .msg .qz-color{
        color: #999;
    }


</style>
</body>
</html>