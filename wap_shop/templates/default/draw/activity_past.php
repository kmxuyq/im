<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE html>
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
<body style="background: #ebebeb">
<div class="draw_tt">
    <h1><?php echo $lang['activity_past'] ;?></h1>
</div>
<ul class="draw_sign">
    <?php if(!empty($output['pesonList'])){ ?>
         <?php foreach ($output['pesonList'] as $k => $val){?>
              <li class="user_hd_wrap">
		        <div class="center">
		            <img  src="<?php echo getMemberAvatar($val['member_avatar']);?>" alt="<?php echo $val['member_id']; ?>">
		            <p><?php echo $val['member_name']; ?></p>
		            <span><?php echo $val['addtimeStr']; ?></span>
		        </div>
        	  </li>   
         <?php }?>
    <?php }else{?>
    <?php }?>
</ul>
<div class="draw_pop_win">
    <img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>"  
         alt="<?php echo $output['member_info']['member_name'];?>" 
         class="draw_pop_userhd"/>
    <button class="draw_btns"></button>
    <div class="close-btn"></div>
</div>
<!-- 去抽奖页面展示 -->
<div class="jion_draw_wrap"><a href="index.php?act=activity_person&op=showPrize&activity_id=<?php echo $output['activity_info']['activity_id']; ?>&store_id=<?php echo  $output['activity_info']['store_id'];?>&member_id=<?php echo $output['member_info']['member_id']; ?>" ></a></div>
<div class="bg-mark" >
</div>
<script>
    window.onload=function(){
        $('.draw_sign').css({
            height:$(window).height()-105,
            'overflow-y':'auto'
        });
        <?php if(!$output['existflag']){?>
	        setTimeout(function(){
	           openPopLayer('.draw_pop_win');
	        },500)
		<?php }?>		
        //签到
        $('.draw_btns').on('click',function(){
        	$.post('index.php?act=activity_person&op=pastSave',
                   {activity_id:<?php echo $output['activity_info']['activity_id']; ?>,
        			store_id: <?php   echo $output['activity_info']['store_id']; ?>,
        			member_id:<?php   echo $output['member_info']['member_id']; ?>},
              function(data){
                if (data.state){
                	var _li='<li class="user_hd_wrap" style="width:0;transform:scale(0)">' +
		                    '<div class="center">' +
		                        '<img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']); ?>" alt="">' +
		                        '<p><?php echo $output['member_info']['member_name'];?></p>' +
		                        '<span>0秒前</span>' +
			                '</div>' +
			                '</li>';
			        var first_li=$('.draw_sign li').eq(0);
			        if( !first_li.offset()){
		                first_li.offset=function(){
		                    return {
		                        left:20,
		                        top:80,
		                    }
		                }
		                $('.draw_sign').append(_li)
		            }else{
		                first_li.before(_li);
		            }
			        //console.log(first_li.offset().top)
			        $('.draw_pop_win').css({
			            'transform':'translate('+(first_li.offset().left-120)+'px,'+(first_li.offset().top-130)+'px) scale(0)'
			        });
			        $('.bg-mark').hide();
			        $('.draw_sign li').eq(0).css({'width':'25%','transform':'scale(1)'})
                }else{
                    alert(data.msg);
                }
            },'json');
        });
        //关闭
        $('.close-btn').on('click',function(){
            $('.draw_pop_win').hide();
            $('.bg-mark').hide();
        });
    }
</script>
	<script>
//自动刷新 每过5秒
var timeID;
gett();
function gett(){
	$(function(){
		$.ajax({
			url:'index.php?act=activity_person&op=getPersonData',
            data:{activity_id:<?php echo $output['activity_info']['activity_id'] ?> ,
            	  store_id   :<?php echo $output['activity_info']['store_id'] ?>},
			type:"POST",
			dataType:"json",
			timeout:3000,
			cache:false,
			success:function(data){
				if(data.state == "1" || data.state == 1 ){
					$(".draw_sign").find("li").remove();
					for(var i=0;i<data.msg.length;i++){
                         var arr = data.msg[i];
                         var str='';
                         str+='<li class="user_hd_wrap"><div class="center">';
    					 str+='<img  src="'+arr['member_avatar']+'" alt="'+arr['member_name']+'"/>';
    					 str+='<p>'+arr['member_name']+'</p>';
    					 str+='<span>'+arr['addtimeStr']+'</span>';
    					 str+='</div></li>'; 
    					 $(".draw_sign").append(str);
				     }
				}
			 }
			});
	});
	//每过5秒自动刷新一下
	timeID = setTimeout("gett()",5000);
}             		
</script>
</body>
</html>