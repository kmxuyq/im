<!DOCTYPE HTML>
<?php defined('InShopNC') or exit('Access Invalid!');?>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="--------template_kewords----------"/>
    <meta name="author" content="--------template_author----------"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <meta name="format-detection" content="telephone=no"/>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/public.less">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/gt_newpage.less">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/flexible.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/less.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery.qqFace.js" ></script>
	<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload.js" ></script>
	<style>
	.send_msg_picking .send_main_msg img{
		max-width:100px;
	}
	</style>
<body class="gt_newpage_body">

<div class="train_position_top">
    <div class="arrow"></div>
    <div class="text">在线咨询</div>
</div>

<div class="talk_product">
    <div class="inner_img">
        <img src="<?php echo $output['goods_info'][4];?>" alt="">
    </div>
    <div class="text">
        <h1 class="tt"><?php echo $output['goods_info'][1];?></h1>
        <div class="price">
            <span class="now_price"><i>¥</i><?php echo $output['goods_info'][2];?></span>
            <span class="old_price">¥<?php echo $output['goods_info'][3];?></span>
        </div>
        <a href="javascript::void();" class="link" onclick="send_link('<?php echo $output['goods_info'][0];?>');">发送产品链接</a>
    </div>
</div>
<div id="face_msgshow">
<?php if(!empty($output['chat_data']['list'])){ ?>
<?php foreach($output['chat_data']['list'] as $v) { ?>
	<?php if($v['f_id'] == $output['service_info']['member_id']){ ?>
    <div class="send_msg_text">
        <div class="send_times"><?php echo date("Y-m-d H:i:s",$v['add_time']);?></div>
        <div class="imgBox"><img src="<?php echo $output['service_info']['headimg'];?>" alt=""/></div>
        <div class="send_main_msg_wrap">
            <div class="usr_name"><?php echo $output['service_info']['name'];?></div>
                <div class="send_main_msg"><?php echo htmlspecialchars_decode($v['t_msg']);?></div>
        </div>
    </div>
	<?php }else{ ?>
    <div class="send_msg_picking">
        <div class="send_times"><?php echo date("Y-m-d H:i:s",$v['add_time']);?></div>
        <div class="imgBox"><img src="<?php echo $output['user_info']['headimg'];?>" alt=""/></div>
        <div class="send_main_msg_wrap">
            <div class="usr_name"><?php echo $output['user_info']['name'];?></div>
            <div class="send_main_msg"><?php echo htmlspecialchars_decode($v['t_msg']);?></div>
        </div>
    </div>
	<?php } ?>
<?php } ?>
<?php }else{ ?>
	<div class="send_msg_text">
        <div class="send_times"><?php echo date("Y-m-d H:i:s");?></div>
        <div class="imgBox"><img src="<?php echo $output['service_info']['headimg'];?>" alt=""/></div>
        <div class="send_main_msg_wrap">
            <div class="usr_name"><?php echo $output['service_info']['name'];?></div>
                <div class="send_main_msg">
                    很高兴为您服务！请问有什么可以帮到您呢？
                </div>
        </div>
    </div>
<?php } ?>
</div>
<!--发送-->
<div class="talk_send_box clearfix">
    <div class="talk_send_mthod">
        <div class="talk_face emotion"></div>
        <div class="talk_text">
            <textarea name="" id="saytext" cols="30" rows="10" class="text_ipt" placeholder="我想说 ..."></textarea>
        </div>
        <span class="send_img">
            <input onchange="ajaxUpload();" id="sendImg" type="file" name="file" accept="/image/*" />
        </span>
        <input type="button" value="发送" class="talk_send_text"/>
    </div>
    <!--发送表情-->
    <div class="talk_faces"></div>
</div>
<!-- 表情 -->

<script>
		var from_id = '<?php echo $output['user_info']['member_id'];?>';
		var from_name = '<?php echo $output['user_info']['name'];?>';
		var from_headimg = '<?php echo $output['user_info']['headimg'];?>';
		var to_id ='<?php echo $output['service_info']['member_id'];?>';
		var to_name ='<?php echo $output['service_info']['name'];?>';
		var to_headimg ='<?php echo $output['service_info']['headimg'];?>';
		var setI = null;
		//setI = initSetI();
    $(function(){

        //调用到滚动到底部
        function goDown(){
            var scrollH = $('#face_msgshow')[0].scrollHeight;
            $('#face_msgshow').scrollTop(scrollH)
        }

        //获取光标关闭表情
        $('.talk_text #saytext').focus(function(){
            $('.emotion').removeClass('on');
            $('.talk_send_box').animate({height:50},function(){
                faceMsgshowAutoHight();
            });
        });
        $('#face_msgshow').css({
            height:$(window).height()-$('.train_position_top').height()-$('.talk_product').height()-$('.talk_send_box').height()
        })

        /**/
        $('.talk_choose_date .date_cell').click(function(){
            $('.talk_choose_date .date_cell').removeClass('on');
            $(this).addClass('on');
        });

        /*表情起展开*/
        $('.emotion').click(function(){
            if($(this).hasClass('on')){
                $('.emotion').removeClass('on');
                $('.talk_send_box').animate({height:50},function(){
                    faceMsgshowAutoHight();
                });
            }else{
                $('.emotion').addClass('on');
                $('.talk_send_box').css({})
                $('.talk_send_box').animate({height:138},function(){
                    faceMsgshowAutoHight();
                });
            }
        });
        /*表情发送*/
        $('.emotion').qqFace({
            id : 'facebox',
            assign:'saytext',
            path:'<?php echo SHOP_TEMPLATES_URL;?>/arclist/'	//表情存放的路径
        });
        $(".talk_send_text").click(function(){
            var str = $("#saytext").val();
                str =replace_em(str);   //发送的信心
				if($.trim(str) !== ''){
					send(from_id,from_name,to_id,to_name,str);
				}
        });
    });
	
	function send_link(url){
		var aDom = '<a href="'+url+'" target="_blank" style="color:#78E3F7">'+url+'</a>';
		send(from_id,from_name,to_id,to_name,aDom);
	}
	
	function send_img(dataObj){
			var iDom = '<img src="'+dataObj.sl_url+'" alt="'+dataObj.yt_url+'">';
			send(from_id,from_name,to_id,to_name,iDom);
	}
	
	function send(from_id,from_name,to_id,to_name,str){
		window.clearInterval(setI)
		$.post('index.php',{'act':'service','op':'send_chat_msg','from_id':from_id,'from_name':from_name,'to_id':to_id,'to_name':to_name,'msg':str},function(data){
			var objData =  JSON.parse(data);
			if(objData.respond == '1'){
				addMsg(objData.data);
				$("#saytext").val("");    //清空文本框
			}
		});
	}
	
	function initSetI(){
		var ints = setInterval('setIntervalMsg()',15000);
		return ints;
	} 
	
	function setIntervalMsg(){
		$.post('index.php',{'act':'service','op':'ajax_polling','from_id':from_id,'to_id':to_id},function(data){
			if(data){
				var objData = JSON.parse(data);
				if(objData.respond === '1'){
					addMsg(objData.data,true);
				}
				
			}
		});
	}
	function addMsg(data,empty = false){
		if(data.length > 0){
			htmlDom = '';
			for(var i = 0;i < data.length; i++){
				if(data[i].f_id == parseInt(from_id)){
				   htmlDom += '<div class="send_msg_picking">'+
								'<div class="send_times">'+getLocalTime(data[i].add_time)+'</div>'+
								'<div class="imgBox"><img src="'+from_headimg+'" alt=""/></div>'+
								'<div class="send_main_msg_wrap">'+
									'<div class="usr_name">'+from_name+'</div>'+
									'<div class="send_main_msg">'+data[i].t_msg+'</div></div></div>';  

				}else{
						htmlDom += '<div class="send_msg_text">'+
							'<div class="send_times">'+getLocalTime(data[i].add_time)+'</div>'+
							'<div class="imgBox"><img src="'+to_headimg+'" alt=""/></div>'+
							'<div class="send_main_msg_wrap">'+
								'<div class="usr_name">'+to_name+'</div>'+
								'<div class="send_main_msg">'+data[i].t_msg+'</div></div></div>';
				}
			}
			if(empty){
				$("#face_msgshow").empty();
			}
			$("#face_msgshow").append(htmlDom);
			var this_scrolTop= $("#face_msgshow").get(0).scrollHeight;
			$("#face_msgshow").scrollTop(this_scrolTop);
			setI = initSetI();
		}
		
	}
	
	function getLocalTime(nS) {     
       return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");      
    }   
	
    //查看结果
    function replace_em(str){
        str = str.replace(/\</g,'&lt;');
        str = str.replace(/\>/g,'&gt;');
        str = str.replace(/\n/g,'<br/>');
        str = str.replace(/\[em_([0-9]*)\]/g,'<img src="<?php echo SHOP_TEMPLATES_URL;?>/arclist/$1.gif" border="0" />');
        return str;
    }
//返回日期
function CurentTime(){
    var now = new Date();

    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日

    var hh = now.getHours();            //时
    var mm = now.getMinutes();          //分

    var clock = year + "-";

    if(month < 10)
        clock += "0";

    clock += month + "-";

    if(day < 10)
        clock += "0";

    clock += day + " ";

    if(hh < 10)
        clock += "0";

    clock += hh + ":";
    if (mm < 10) clock += '0';
    clock += mm;
    return(clock);
}

function ajaxUpload(){
	 $.ajaxFileUpload
	(
		{
			url: 'index.php?act=service&op=upload', //用于文件上传的服务器端请求地址
			secureuri: false, //是否需要安全协议，一般设置为false
			fileElementId: 'sendImg', //文件上传域的ID
			dataType: 'json', //返回值类型 一般设置为json
			success: function (data, status)  //服务器成功响应处理函数
			{
				send_img(data);
			},
			error: function (data, status, e)//服务器响应失败处理函数
			{
			}
		}
	)
	return false;
}
function faceMsgshowAutoHight(){
    face_msgshow_h=$(document).height()-44-$('.talk_send_box').height();
}
</script>
</body>