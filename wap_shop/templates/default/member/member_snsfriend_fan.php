<?php defined('InShopNC') or exit('Access Invalid!');?>
<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>我的好友</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
	<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns_friend.js" charset="utf-8"></script> 
</head>
<body style="background:#FFFFFF;">
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onClick="history.back()"></i>
    <h1 class="qz-color">我的好友</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<div id="leftTabBox" class="tabBox-main">
    <div class="hd tabBox-tt">
        <ul>
            <li onClick="location.href = 'index.php?act=member_snsfriend&op=find'" class="">查找好友</li>
            <li onClick="location.href = 'index.php?act=member_snsfriend&op=follow'" class="">我关注的</li>
            <li onClick="location.href = 'index.php?act=member_snsfriend&op=fan'" class="on">关注我的</li>
        </ul>
    </div>
    <div class="tempWrap" style="clear: both">
	    <div class="bd">
            <?php if ($output['fan_list']) { ?>
            <div class="attention">
			<?php foreach($output['fan_list'] as $k => $v){ ?>
                <div class="attention_item">
                    <div class="item_left">
                        <p class="imgBox">
						<!--
						//个人中心详情
						<a href="index.php?act=member_snshome&mid=<?php echo $v['friend_frommid'];?>" target="_blank" data-param="{'id':<?php echo $v['friend_tomid'];?>}" nctype="mcard">
						<img src="<?php if ($v['member_avatar']!='') { echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.DS.$v['member_avatar']; } else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait'); } ?>" alt=""/>
						</a>-->
						<img src="<?php if ($v['member_avatar']!='') { echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.DS.$v['member_avatar']; } else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait'); } ?>" alt=""/>
						</p>
                        <div>
						<a href="index.php?act=member_snshome&mid=<?php echo $v['friend_frommid'];?>" target="_blank" data-param="{'id':<?php echo $v['friend_tomid'];?>}" nctype="mcard">
						<?php echo $v['friend_frommname']; ?>
						</a>
						</div>
                    </div>
                    <div class="item_right">
                        <div class="attention_type">
						<?php echo $v['friend_followstate']!=2?'关注我的':$lang['snsfriend_follow_eachother'];?>
						</div>
                        <input id="add_follow" style="<?php echo $v['friend_followstate']==2?'display:none;':'';?>"  data-param='{"mid":"<?php echo $v['friend_frommid'];?>"}' class="attention_btn" type="button" value="加关注"/>
						<input id="cancel_follow" style="<?php echo $v['friend_followstate']!=2?'display:none;':'';?>"  data-param='{"mid":"<?php echo $v['friend_frommid'];?>"}' class="attention_btn" type="button" value="取消关注"/>
                    </div>
                </div>
            <?php } ?>   
            </div>
           <?php } ?>
        </div>
		
    <div>	  
</div>
<script type="text/javascript">
$(function(){
	//添加关注
	$("#add_follow").live('click',function(){
		var data_str = $(this).attr('data-param');
        eval( "data_str = "+data_str);
        $.getJSON('index.php?act=member_snsfriend&op=addfollow&mid='+data_str.mid, function(data){
        	if(data){
				window.location.reload()
    			alert('关注成功');
        	}else{
        		alert('关注失败');
        	}
        });
        return false;
	});
    //取消关注
	$("#cancel_follow").live('click',function(){
		var data_str = $(this).attr('data-param');
        eval( "data_str = "+data_str);
        $.getJSON('index.php?act=member_snsfriend&op=delfollow&mid='+data_str.mid, function(data){
        	if(data){
				window.location.reload()

        	}else{
        		alert('取消失败');
        	}
        });
        return false;
	});
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
