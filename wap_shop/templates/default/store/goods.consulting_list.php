<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.go(-1)" ></i>
    <h1 class="qz-color"><?/*php echo $output['goods']['goods_name'];*/ ?> 购买咨询</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <div class="qz-padding">
        <div class="qz-tip">
            <h4 class="ui-flex ui-flex-pack-center">温馨提示</h4>
            <p><?php echo html_entity_decode(C('consult_prompt'));?></p>
        </div>
    </div>
    
    <p align="center">
	<?php if(!$_SESSION['member_id']){ ?>
<a href="<?php echo urlShopWap('login', 'index');?>" target="_blank" class="ncs-btn ncs-btn-red"><button class="ui-btn ui-btn-primary qz-btn-padding">  我要提问  </button></a>
<?php } else{;?>
<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=goods&op=consulting_list_pj&goods_id=<?php echo $output['goods']['goods_id'];?>" target="_blank" class="ncs-btn ncs-btn-red"><button class="ui-btn ui-btn-primary qz-btn-padding">  我要提问  </button></a>
		<?php };?>
    </p>
    
    <div class="qz-bk10"></div>
    <div class="ui-tab">
        <ul class="ui-tab-nav ui-border-b qz-bottom-b">
		 <?php if (!empty($output['consult_type'])) {?>
            <?php foreach ($output['consult_type'] as $val) {?>
            <li class="<?php if (intval($_GET['ctid']) == $val['ct_id']) {?>current<?php }?>"><?php echo $val['ct_name'];?></li>
            <?php }?>
            <?php }?>
            
        </ul>
        
        <ul class="ui-tab-content" style="width:400%">
		 <li>
		<?php if(!empty($output['consult_list1'])) { ?>
          <?php foreach($output['consult_list1'] as $k=>$v){ ?>
		  
		 
                <dl class="qz-dl">
                    <div class="qz-cb qz-background-white"><?php echo nl2br($v['consult_content']);?></div>
                
                    <div class="qz-background-white qz-cb qz-bottom-b">
                        <span class="qz-color"> 
						<?php if($v['member_id']== '0') echo $lang['nc_guest']; else if($v['isanonymous'] == 1){?>
                <?php echo str_cut($v['member_name'],2).'***';?>
                <?php }else{?>
                <?php echo str_cut($v['member_name'],8);?>
                <?php }?>
				</span>  
                        <span class="qz-fr"><?php echo date("Y-m-d H:i:s",$v['consult_addtime']);?></span>
                    </div>
                <?php if($v['consult_reply']!=""){?>
                    <div class="qz-cb qz-background-white"><?php echo nl2br($v['consult_reply']);?></div>
					 <?php }?>
                </dl>
                
         
			
			
         
          <?php }?>
          
          <?php } ?>
		  
               </li>
           
		   <li>
		<?php if(!empty($output['consult_list2'])) { ?>
          <?php foreach($output['consult_list2'] as $k=>$v){ ?>
		  
		 
                <dl class="qz-dl">
                    <div class="qz-cb qz-background-white"><?php echo nl2br($v['consult_content']);?></div>
                
                    <div class="qz-background-white qz-cb qz-bottom-b">
                        <span class="qz-color"> 
						<?php if($v['member_id']== '0') echo $lang['nc_guest']; else if($v['isanonymous'] == 1){?>
                <?php echo str_cut($v['member_name'],2).'***';?>
                <?php }else{?>
                <?php echo str_cut($v['member_name'],8);?>
                <?php }?>
				</span>  
                        <span class="qz-fr"><?php echo date("Y-m-d H:i:s",$v['consult_addtime']);?></span>
                    </div>
                <?php if($v['consult_reply']!=""){?>
                    <div class="qz-cb qz-background-white"><?php echo nl2br($v['consult_reply']);?></div>
					 <?php }?>
                </dl>
                
         
			
			
         
          <?php }?>
          
          <?php } ?>
		  
               </li>
			   <li>
		<?php if(!empty($output['consult_list3'])) { ?>
          <?php foreach($output['consult_list3'] as $k=>$v){ ?>
		  
		 
                <dl class="qz-dl">
                    <div class="qz-cb qz-background-white"><?php echo nl2br($v['consult_content']);?></div>
                
                    <div class="qz-background-white qz-cb qz-bottom-b">
                        <span class="qz-color"> 
						<?php if($v['member_id']== '0') echo $lang['nc_guest']; else if($v['isanonymous'] == 1){?>
                <?php echo str_cut($v['member_name'],2).'***';?>
                <?php }else{?>
                <?php echo str_cut($v['member_name'],8);?>
                <?php }?>
				</span>  
                        <span class="qz-fr"><?php echo date("Y-m-d H:i:s",$v['consult_addtime']);?></span>
                    </div>
                <?php if($v['consult_reply']!=""){?>
                    <div class="qz-cb qz-background-white"><?php echo nl2br($v['consult_reply']);?></div>
					 <?php }?>
                </dl>
                
         
			
			
         
          <?php }?>
          
          <?php } ?>
		  
               </li>
			   <li>
		<?php if(!empty($output['consult_list4'])) { ?>
          <?php foreach($output['consult_list4'] as $k=>$v){ ?>
		  
		 
                <dl class="qz-dl">
                    <div class="qz-cb qz-background-white"><?php echo nl2br($v['consult_content']);?></div>
                
                    <div class="qz-background-white qz-cb qz-bottom-b">
                        <span class="qz-color"> 
						<?php if($v['member_id']== '0') echo $lang['nc_guest']; else if($v['isanonymous'] == 1){?>
                <?php echo str_cut($v['member_name'],2).'***';?>
                <?php }else{?>
                <?php echo str_cut($v['member_name'],8);?>
                <?php }?>
				</span>  
                        <span class="qz-fr"><?php echo date("Y-m-d H:i:s",$v['consult_addtime']);?></span>
                    </div>
                <?php if($v['consult_reply']!=""){?>
                    <div class="qz-cb qz-background-white"><?php echo nl2br($v['consult_reply']);?></div>
					 <?php }?>
                </dl>
                
         
			
			
         
          <?php }?>
          
          <?php } ?>
		  
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



