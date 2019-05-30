<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>我的好友</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
	<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns_friend.js" charset="utf-8"></script> 
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">我的好友</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<div id="leftTabBox" class="tabBox-main">
    <div class="hd tabBox-tt">
        <ul>
            <li onclick="location.href = 'index.php?act=member_snsfriend&op=find'" class="on">查找好友</li>
            <li onclick="location.href = 'index.php?act=member_snsfriend&op=follow'" class="">我关注的</li>
            <li onclick="location.href = 'index.php?act=member_snsfriend&op=fan'" class="">关注我的</li>
        </ul>
    </div>
    <div class="tempWrap" style="clear: both">
        <div class="bd">
            <div class="attention" >
                <div class="attention_search" style="margin-left:-10px;margin-right:-10px;">
				  <form id="search_form" method="post" action="index.php?act=member_snsfriend&op=findlist">
                    <input name="searchname" id="searchname" value="<?php echo $_GET['searchname'];?>" class="ipt_sch" type="text"/>
                    <input class="ipt_schbtn" type="button" value="<?php echo $lang['snsfriend_search'];?>"/>
                  </form>
			   </div>
                <?php if ($output['memberlist']) { ?>
				<?php foreach($output['memberlist'] as $k => $v){ ?>
				<div class="attention_item">
                    <div class="item_left">
                        <p class="imgBox">
						<a href="index.php?act=member_snshome&mid=<?php echo $v['member_id'];?>" target="_blank">
						<img src="<?php if ($v['member_avatar']!='') { echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.DS.$v['member_avatar']; } else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait'); } ?>" alt=""/>	
						<a/></p>
                        <div>
						<a href="index.php?act=member_snshome&mid=<?php echo $v['member_id'];?>" target="_blank">
						<?php echo $v['member_name']; ?>
						</a>
						</div>
                    </div>
					
                    <div class="item_right" >
                        <div class="attention_type" style="<?php echo $v['followstate']!=2?'display:none;':'';?>"><?php echo $lang['snsfriend_follow_eachother'];?></div>
						<div class="attention_type" style="<?php echo $v['followstate']!=1?'display:none;':'';?>"><?php echo $lang['snsfriend_followed'];?></div>
                        <input id="add_follow" data-param='{"mid":"<?php echo $v['member_id'];?>"}' class="attention_btn2" style="margin-top:10px;<?php echo $v['followstate']!=0?'display:none;':'';?>" type="button" value="加关注"/>
                        <input id="cancel_follow" data-param='{"mid":"<?php echo $v['member_id'];?>"}' class="attention_btn" style="<?php echo $v['followstate']==0?'display:none;':'';?>" type="button" value="取消关注"/>
				   </div>
					
                </div>
				<?php } ?>
				<?php } ?>     
			</div>
        </div>
    </div>
</div>
<script type="text/javascript">TouchSlide({ slideCell:"#leftTabBox" });</script>
<script type="text/javascript">
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
$(function(){
	$('.ipt_schbtn').click(function(){
		var search = $('#searchname').val();
		if(search !== ''){
			$('#search_form').submit()
		}else{
			alert('请输入会员名称(支持模糊搜索)')
		}
	})
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>