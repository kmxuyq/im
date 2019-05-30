<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $output['html_title']?></title>
<meta charset="UTF-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no" />
<meta http-equiv="Content-Language" content="UTF-8" />
<meta name="Keywords" content="一元抢购，伊美假日" />
<meta name="author" content="伊美假日" />
<meta name="Copyright" content="版权所有,违者必究" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/draw/reset.css">
<link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/draw/public.css">
<link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/draw/draw.css">
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/draw/less.min.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/draw/main.js"></script>
</head>
<body style="background: #da0837">
	<div class="slot_machines slot_machines_amite">
		<ul class="machin_top_dot" style="display:block;">
		      
		</ul>
		<div class="tt">
		  <?php 
		    switch (intval($output['activity_detail_sort'])) {
		    	case 1: echo '一等奖'; break;
		    	case 2: echo '二等奖';break;
		    	case 3: echo '三等奖';break;
		    	case 4: echo '四等奖';break;
		    	case 5: echo '五等奖';break;
		    	default:echo '六等奖';break;
		    }
		  ?>
		</div>
		  <div class="win_name">
		        <?php if($output['drawflag']){ echo '抽奖结束，请关注下次开奖';}?>
		  </div>
		<div class="lucky_up">
			<span class="btn_text"><?php echo $lang['good_luck']?></span>
			<span class="btn_animate"><em class="up_number">+1</em></span>
		</div>
	</div>
	  <div class="win_list_div">
          <?php if(!empty($output['giftPerson'])){ 
                $member_ids = '';
        	 ?>
        	 <ul>
             <?php foreach ( $output['giftPerson'] as $k=>$v){?>
		         <li><?php echo $v['member_name'];?></li>
		         <li><?php echo date('m月d日  H:i',$v['active_time']);?></li>
		         <li><img style="width: 35px;height: 35px; border-radius:50px" 
             			src="<?php echo getMemberAvatar($v['member_avatar']); ?>" />
         		 </li>
            <?php $member_ids.=$v['member_id'].',';}?>
               <input type = "hidden" name="member_ids" value="<?php echo $member_ids;?>" ></input>
             </ul>
           <?php  }else{?>
              <input type = "hidden" name="member_ids" value="" ></input>
           <?php }?>
       </div>
<script>
    var name_array=[
        <?php foreach ($output['personinfo'] as $val){?>
            '<?php echo $val['member_name'];?>',
        <?php }?>
    ];
    //var img_array =[
		<?php // foreach ($output['personinfo'] as $val){?>
		//	'<?php  //echo getMemberAvatar($val['member_avatar']);?>',
		<?php //}?> 
   // ];
    //var luck_name='最终中奖者名字';
    var onOff=true;
    //全局变量
    var startTime;
    //刷新标示
    var reFreshen = true;
    $(function(){
        $('.lucky_up').click(function(){
            if(!reFreshen){
            	location.reload(true);
            	return false;
             }
            var _span=$(this).find('.btn_animate');
            var _em='<em class="up_number"> +1 </em>';
            _span.append(_em);
            var last_em=$(this).find('.btn_animate em:last');
            setTimeout(function(){
                last_em.addClass('on')
            },30);

            last_em.get(0).addEventListener('webkitTransitionEnd',function(){
                last_em.removeClass('on').remove()
            });
            last_em.get(0).addEventListener('transitionend',function(){
                last_em.removeClass('on').remove()
            });

            var lk=$('.lucky_up').get(0);
            lk.style.transform='scale(0.95)';

            lk.addEventListener('webkitTransitionEnd',function(){
                lk.style.transform='scale(1)';
            });

            //滚动抽奖
            <?php if(!$output['drawflag']){ ?>
                drawStart(name_array);
                //drawStart(name_array,img_array);
            <?php }?>
        });
        //循环从后台查询person表中detail的信息
        //function drawStart(name_array,img_array){
        function drawStart(name_array){
            if(!onOff) return;
            onOff=false;
            //console.log(1);
            startTime=setInterval(function(){
                var rd = parseInt(Math.random()*4);
                //$('#imgs').attr('src',img_array[rd]);
                //$('#names').text(name_array[rd]);
				$('.win_name').text(name_array[rd]);
            },30);
            //循环后台取值
            gett();
        }
        //自动触发
        $('.lucky_up').trigger("click"); 
    });
    function gett(){
    	$(function(){
    		$.ajax({
    			url:'index.php?act=activity_person&op=getDrawPerson',
                data:{activity_id          :<?php echo $output['ruleData']['activity_id']; ?>,
                	  activity_detail_sort :<?php echo $output['activity_detail_sort']; ?>,
                      store_id             :<?php echo $output['ruleData']['store_id']; ?>,
                      member_ids           :$('input[name="member_ids"]').val()
                      },
    			type:"POST",
    			dataType:"json",
    			timeout:3000,
    			cache:false,
    			success:function(data){
        			//后台错误停止跑数。弹出错误
    				if(data.state == "0" || data.state == 0 ){
                        //停止循环后台取数字
    					clearTimeout(timeID);
    					//停止跑数字,并进行相应的赋值
    		            setTimeout(function(){
    		                clearInterval(startTime);
    		            },0);
    		            alert(data.msg);
    		        //已经抽奖完毕，停止跑数     
    				}else if(data.state == "1" || data.state == 1 ){
    					//停止循环后台取数字
    					clearTimeout(timeID);
    					//停止跑数字,并进行相应的赋值
    		            setTimeout(function(){
    		                clearInterval(startTime);
    		                $('.win_name').text("该批次奖项已经抽奖结束<br/>请关注下一批次的开奖");
    		            },0);
    		            //是否需要赋值
    		            if(data.state1 == "1" || data.state1 == 1){
							var str='<ul>'+
										'<li>'+data.msg+'</li>'+
										'<li>'+data.pesontime+'</li>'+
										'<li><img style="width: 35px;height: 35px; border-radius:50px" src="'+data.img+'" /></li>'+
									'</ul>';
 						   $(".win_list_div").append(str);
        		            //往input 赋值
        		            var old =$('input[name="member_ids"]').val();
        		            $('input[name="member_ids"]').val(old+data.mid+",");
            		    }
    		        //还没有跑完，继续跑 
    				}else{
    					//是否需要赋值
    		            if(data.state1 == "1" || data.state1 == 1){
							var str='<ul>'+
										'<li>'+data.msg+'</li>'+
										'<li>'+data.pesontime+'</li>'+
										'<li><img style="width: 35px;height: 35px; border-radius:50px" src="'+data.img+'" /></li>'+
									'</ul>';
							$(".win_list_div").append($(str));
        		            //往input 赋值
        		            var old =$('input[name="member_ids"]').val();
        		            $('input[name="member_ids"]').val(old+data.mid+","); 
                            //有人中奖了， 停止
        					clearTimeout(timeID);
        					//停止跑数字,并进行相应的赋值
        		            setTimeout(function(){
        		                clearInterval(startTime);
        		                $('.win_name').text("这一轮的抽奖结束<br/>请关注下一轮的开奖");
        		            },0);
							//刷新标示打开
        		            reFreshen = false;
            		    }
            		}
    			 }
    			});
    	});
    	//每3秒查询一下
    	timeID = setTimeout("gett()",3000);
    }
</script>
</body>
</html>