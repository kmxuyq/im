<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />

<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="<?php echo CHAT_SITE_URL;?>/js/user.js"></script>


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/masonry.pkgd.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/base64.js"></script>

<style>
.hovered{
	background:#10B0E6; color:#FFF;}
	.goods_name_sel span {
	color: #F00
}
.goods_name_sel span{color:#f00}
.goods_sum_price{margin-top:10px;font-size:1.3em}
.goods_sum_price span{color:#eb6100}
section i {
	color: #f00;
	line-height: 2.5
}
.no_date_class{color:#ccc;pointer-events: none;}
.golf_site{ line-height:2.5; border-bottom:solid 1px #d9d9d9;height:25px;margin-top:10px}
.golf_site span{display:inline-block; line-height:1.5}
.golf_site .left{ float:left}
.golf_site .right{ float:right;padding-right:2%}
.golf_site .right i{color:#b2b2b2}
</style>


<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.go(-1)" ></i>
	<h1 class="qz-color">产品详情</h1>
    <i class="qz-head-rt qz-ico qz-ico-menu2 qz-fr"></i>
    <!--分享图标 qz-ico-share -->
</header>


<input type="hidden" id="lockcompare" value="unlock" />


<section class="ui-container1">
    <div class="qz-bk10"></div>
    <div id="qz-picScroll" class="qz-picScroll">
        <div class="hd">
            <ul></ul>
        </div>
                
        <div class="bd">
            <ul>
				<?php if (!empty($output['goods_image'])) {?>
					
	                <?php $imglist = $output['goods_image'];
						foreach($imglist as $key=>$img)
						{
							$img_one = explode(",",$img);
							
							$img_one_url_array = explode("'",$img_one[2]);
							
							$img_one_url = $img_one_url_array[1];
							//$img_one_url = str_replace("'","",$img_one_url);
							$imglist_new[] = $img_one_url;
							
						}
						
						for($i=0;$i<count($imglist_new);$i++)
						{
							?>
							 <li>
                    <a href="javascript:"><img src="<?php echo $imglist_new[$i];?>" class="qz-img-block"></a>
                    <div class="qz-loc-num qz-light"><?php echo ($i+1);?>/<?php echo (count($imglist_new)-1);?></div>
                    <div class="qz-loc-title">
                        <p class="ui-nowrap qz-light"><?php echo $output['goods']['goods_name']; /**/?></p>
                        <div class="qz-rt">
                            <i class="qz-ico2 qz-ico-allowl"></i>
                            <i class="qz-ico2 qz-ico-collection"></i>
                        </div>
						
                    </div>
                </li>
							<?php
						}
					?>
	             <?php }?>
               
            </ul>
        </div>
    </div>
    <script type="text/javascript">
        TouchSlide({ 
            slideCell:"#qz-picScroll",
            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
            mainCell:".bd ul", 
            effect:"left", 
            autoPlay:true,//自动播放
            autoPage:true, //自动分页
            interTime:6000,
        });
    </script>  


    <div class="qz-bk5"></div>
    <div class="qz-padding qz-background-white qz-bottom-b">
        <p><div><?php echo str_replace("\n", "<br>", $output['goods']['goods_jingle']);?></div></p>
    </div>
    <div class="qz-padding qz-background-white">
        <!--S 市场价参考价格-->
        <p class="qz-del">
		<?php echo $lang['goods_index_goods_cost_price'];?><?php echo $lang['nc_colon'];?>
		<?php echo $lang['currency'].$output['goods']['goods_marketprice'];?>
        </p>
        <!--E 市场价参考价格-->
        <!--S 实际售价-->
        <p class="qz-color2">
		<?php 
		    echo $lang['goods_index_goods_price'];
		    echo $lang['nc_colon'];
		    if (isset($output['goods']['title']) && $output['goods']['title'] != '') {
                echo $output['goods']['title'];
            }
            if (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {
                echo $lang['currency'].$output['goods']['promotion_price'];
				echo '(原售价';
			    echo $lang['nc_colon'];
				echo $lang['currency'].'<span id="goods_price">'.$output['goods']['goods_price'].'</span>';
				echo ')';
			} else {
			    echo ''.$lang['currency'].'<span id="goods_price">'.$output['goods']['goods_price'].'</span>'; 
			}
	    ?>
        </p>
        <!--E 实际售价-->
        <!--<div class="qz-bk5"></div>
        <a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=goods&op=salelog&goods_id=<?php echo $output['goods_id'];?>"><p>销　　量:<span class="qz-color3 qz-span">50</span>件</p></a>-->
        <div class="qz-bk5"></div>
        <p>库　　存:<em nctype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['nc_jian'];?></p>
        <div class="qz-bk5"></div>
        <p>快　　递：免运费</p> 
		<?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
        <div class="qz-bk5"></div>
		<p>销售时间:<?php echo date('Y-m-d H:i:s', $output['goods']['appoint_satedate']);?>
		</p>
        <?php }?>
		<div class="qz-bk5"></div>
		 <div class="qz-bk10"></div>
   <!-- <div class="qz-top-b qz-bottom-b qz-padding qz-background-white"></div>
     S选择产品类开始-->
	 
        <?php if($output['goods']['goods_state'] != 10 && $output['goods']['goods_verify'] == 1){?>
        <?php if (is_array($output['goods']['spec_name'])) { ?>
        <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>
         <section nctype="nc-spec">
        <p title='<?php echo $key?>' data='<?php echo $val;?>'><?php echo $val;?><?php echo $lang['nc_colon'];?></p>
            <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
            <div class="qz-tcxz clearfix" nctyle="ul_sign" title="<?php echo $val;?><?php echo $lang['nc_colon'];?>">
              <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
              <!-- 图片类型规格-->
              <span class="ui-btn qz-padding-20 <?php if (isset($output['goods']['goods_spec'][$k])) {echo '';/*hovered*/}?>" data-param="<?php echo $k;?>" style="float:left; margin:5px;" title="<?php echo $v;?>"><?php echo $v;?></span>
              
              <?php }?>
			  <i></i>
              </div>
              <input type="hidden" class="type" value="1">
            <?php }?>
			
             
         <?php }?>
         <?php }?>
         <?php }?>
<!-- --------------线路和门票－库存日历HOME---------------- -->
<?php
$type_id=$output["goods"]["type_id"];
//线路和门票
if($type_id=='40'||$type_id=='41'||$type_id=='42'||$type_id=='43'){
?>			<section nctype="nc-spec">
				<p>出游月份：</p>
				<div class="qz-tcxz clearfix" nctyle="ul_sign" title="出游月份：">
<?php 
foreach ($output["month"] as $month){
	$month_str=intval(substr($month["date"], -2));
	echo "<span class=\"ui-btn qz-padding-20 \"  style=\"float:left; margin:5px;\" title=\"{$month["date"]}\">{$month_str}月</span>";
}
?>
              <i></i>
				</div>
			</section>
			<section nctype="nc-spec">
				<p>出游日期：</p>
				<div class="qz-tcxz clearfix" nctyle="ul_sign" title="出游日期：">
<?php 
foreach (json_decode($output["month"][0]['stock_info'],true) as $days_key=>$days){
	$current_time=date('Y-m-d');
	$no_date_class='';
	$days_str=$days_key.'日';
	if($days["date"]<=$current_time||$days["stock"]=='0'){
		$no_date_class='no_date_class';
	}
	if($days["stock"]>0&&$days["stock"]<=5){
		$days_str='库存余'.$days["stock"];
	}
	echo "<span class=\"ui-btn qz-padding-20 {$no_date_class}\" value='{$days['man_price']}|{$days['child_price']}'  style=\"float:left; margin:5px;\"  title=\"-{$days_key}\">$days_str</span>";
}
?>
 <i></i>
				</div>

			</section>
			
			<?php }?>
<!-- --------------线路和门票－库存日历END---------------- -->
<!-- --------------------高尔夫库存日历HOME---------------------------- -->
<?php if($type_id=='46'){?>
<section nctype="nc-spec">
				<p>月份：</p>
				<div class="qz-tcxz clearfix" nctyle="ul_sign" title="月份：">
<?php 
foreach ($output["month"] as $month){
	$month_str=intval(substr($month["date_month"], -2));
	echo "<span class=\"ui-btn qz-padding-20 \"  style=\"float:left; margin:5px;\" title=\"{$month["date_month"]}\">{$month_str}月</span>";
}
?>
              <i></i>
				</div>
			</section>
			<!-- ---------------- -->
			<section nctype="nc-spec">
				<p>日期：</p>
				<div class="qz-tcxz clearfix" nctyle="ul_sign" title="日期：">
<?php 
foreach ($output["days"] as $days){
	$current_time=date('Y-m-d');
	$no_date_class='';
	$days_str=$days["day"].'日';
	
	$golf_date=date('Y-m-d',strtotime($days["date_month"].'-'.$days["day"]));
	if($golf_date<$current_time){
		$no_date_class='no_date_class';
	};
	echo "<span class=\"ui-btn qz-padding-20 {$no_date_class}\" style=\"float:left; margin:5px;\"  title=\"-{$days["day"]}\">$days_str</span>";
}
?>
 <i></i>
				</div>

			</section>
			<!-- -----------开球时间------------- -->
			<section nctype="nc-spec">
				<p>开球时间：</p>
				<div id="golf_hour"></div>

			</section>
			<section nctype="nc-spec" title="场次">
				<p>开球场次：</p><i></i>
				<div id="golf_minute"></div>

			</section>

<?php }?>
<!-- --------------------高尔夫库存日历END---------------------------- -->
       <script language="javascript" >
       	var commonid='<?php echo $output["goods"]["goods_commonid"]?>';
       	var type = '<?php echo $output["goods"]["type_id"]?>';
        $('section[nctype="nc-spec"]').find('span').each(function(){
        $(this).click(function(){
            if ($(this).hasClass('hovered')) {
                return false;
            }
			$(this).parents('div[nctyle="ul_sign"]:first').find('span').removeClass('hovered');
            $(this).addClass('hovered');
			//----------------------改动HOME-------------------------------
			var str=$(this).attr('title');

	    		var type_title=$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('p:first').text();
				
	    		//跟团游,自由行，周边游
	            if (type == '40' || type == '41' || type == '42') {
	    			if(type_title.indexOf('套餐')>=0){
	    				$('#tb_package').val(str);
	    				$('.tb_package').text(str);
						load_stock_info();
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						
	    			}else if(type_title.indexOf('出游人群')>=0){
	    				$('#tb_man_type').val(str);
	    				$('.tb_man_type').text(str);
						get_man_price();//成人价、儿童价显示更新
						
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						
	    			}else if(type_title.indexOf('月')>=0){
	    				$('#tb_month').val(str);
	    				$('.tb_month').text(str);
						load_stock_info();
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						
	    			}else if(type_title.indexOf('日期')>=0){
	    				$('#tb_day').val(str);
	    				$('.tb_day').text(str);
						
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
	    			}					
	    		}
	             //景区门票
	            if (type == '43') {
	    			
	    			if(type_title.indexOf('收费项目')>=0){
	    				//门票套餐
	    				$('#tb_package').val(str);
	    				$('.tb_package').text(str);
						load_stock_info();
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						
	    			}/*else if(type_title.indexOf('门票类型')>=0){
	    				//电子、实体票
	    				$('#tb_ticket_type').val(str);
	    				$('.tb_ticket_type').text(str);
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						load_stock_info()
	    			}*/else if(type_title.indexOf('门票种类')>=0){
	    				//成人、儿童 
	    				$('#tb_man_type').val(str);
	    				$('.tb_man_type').text(str);
						get_man_price();
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						
	    			}else if(type_title.indexOf('月')>=0){
	    				$('#tb_month').val(str);
	    				$('.tb_month').text(str);
						load_stock_info();
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						
	    			}else if(type_title.indexOf('日')>=0){
	    				$('#tb_day').val(str);
	    				$('.tb_day').text(str);
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
	    			}
					
	    		}
				//高尔夫
				
	            if (type == '46') {
	            	if(type_title.indexOf('套餐')>=0){
	    				$('#tb_package').val(str);
	    				$('.tb_package').text(str);
						load_golf_stock_info();
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						
	    			}else if(type_title.indexOf('月')>=0){
	    				$('#tb_month').val(str);
	    				$('.tb_month').text(str);
						load_golf_stock_info();
						$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						
	    			}else if(type_title.indexOf('日')>=0){
		    			
	    				$('#tb_day').val(str);
	    				$('.tb_day').text(str);
						
						//$(this).parent('div:first').parent('section[nctype="nc-spec"]:first').find('i:first').text('');
						//---------------------载入场地信息HOME

						load_golf_minute();
						//---------------------载入场地信息END
	    			}	
				}
			//----------------------改动END--------------------------------
          //  alert(checkSpec());
			//checkSpec();//商品页跳转
        });
    });
	//线路日历及价格调用
		function load_stock_info(){
			
			var line_str=$('#tb_package').val()+$('#tb_month').val();
			var type_id=$('#tb_type_id').val();//商品大类
			if($('#line_str').html()==line_str){
				return false;
			}else{
				$('#line_str').html(line_str);
				if($('#tb_package').val()==''||$('#tb_month').val()==''){
					return false;
				}
				//alert($('#line_str').html()+'------'+line_str);
				//载入日历
				var package_name = encodeURIComponent($('#tb_package').val());
				var load_url='/wap_shop/index.php?act=goods&op=stock_info&commonid='+commonid+'&package='+package_name+'&date='+$('#tb_month').val()+'&type_id='+type_id;
				//document.write(load_url);
				$('div[title="出游日期："]').load(load_url,function(data){
					$(this).find('span').click(function(){
						$(this).parent('div').find('span').removeClass('hovered');
						$(this).addClass('hovered');
						var title=$(this).attr('title');
						$('#tb_day').val(title);
	    				$('.tb_day').text(title);
	    				//得到价格
						
						var price=$(this).attr('value');
						var price_arr=price.split('|');
						var goods_price='';
						if(($('#tb_man_type').val()).indexOf('成人')>=0){
							goods_price=price_arr[0];
						}else{
							goods_price=price_arr[1];
						}
						$('#goods_price').text(goods_price);
					});
				});
			}
		}
	//-------------------------------------
	//高尔夫日期调用
	function load_golf_stock_info(){
		var line_str=$('#tb_package').val()+$('#tb_month').val();
		var type_id=$('#tb_type_id').val();//商品大类
		if($('#line_str').html()==line_str){
			return false;
		}else{
			$('#line_str').html(line_str);
			if($('#tb_package').val()==''||$('#tb_month').val()==''){
				return false;
			}
			//alert($('#line_str').html()+'------'+line_str);
			//载入日期日历
			var package_name = encodeURIComponent($('#tb_package').val());
			var load_url='/wap_shop/index.php?act=goods&op=stock_info&commonid='+commonid+'&package='+package_name+'&date='+$('#tb_month').val()+'&type_id='+type_id;
			//document.write(load_url);
			$('div[title="日期："]').load(load_url,function(data){
				$(this).find('span').click(function(){
					$(this).parent('div').find('span').removeClass('hovered');
					$(this).addClass('hovered');
					var title=$(this).attr('title');
					
					$('#tb_day').val(title);
    				$('.tb_day').text(title);
    				//高尔夫场地列表调用
    				load_golf_hour();
				});
			});
		}
	}
	//-------------------------------------
	//高尔夫小时段调用
	function load_golf_hour(){
		//
		var line_str=$('#tb_package').val()+$('#tb_month').val()+$('#tb_day').val();
		var type_id=$('#tb_type_id').val();//商品大类
		if($('#line_str').html()==line_str){
			return false;
		}else{
			$('#line_str').html(line_str);
			if($('#tb_package').val()==''||$('#tb_month').val()==''||$('#tb_day').val()==''){
				return false;
			}
			//alert($('#line_str').html()+'------'+line_str);
			//载入日期日历
			var package_name = encodeURIComponent($('#tb_package').val());
			var load_url='/wap_shop/index.php?act=goods&op=golf_hour&commonid='+commonid+'&package='+package_name+'&date='+$('#tb_month').val()+'&type_id='+type_id+'&day='+$('#tb_day').val();
			//document.write(load_url);
			$('#golf_hour').load(load_url,function(data){
				$(this).find('span').toggle(function(){
					var title=$(this).attr('title');
					$(this).addClass('hovered');
				
    				$(this).find('input').val(title);
					 //--------------------------
					 set_hour_str();
    				//高尔夫场地列表调用
    				load_golf_minute(title);
					sum_price();
					set_hour_info(title);
					
				},function(){
					$(this).removeClass('hovered');
					var title=$(this).attr('title');
    				$(this).find('input').val('');
					//-------------------------------
					set_hour_str();
					remove_golf_minute(title);
					sum_price();
					set_hour_info(title);
				});
			});
		$('#golf_minute').html('');//清空分钟段
		$('#sum_price').text('0');//重置总价
		}
	}
	function set_hour_str(){
		 //场次点击,设置默认值
		 var golf_hour='';
		$('#golf_hour').find('input').each(function(){
			if($(this).val()!=''){
				golf_hour+=$(this).val()+'点';
			}
		});
		
		$('#tb_golf_hour').val(golf_hour);
		$('.tb_golf_hour').text(golf_hour);
	}
	//高尔夫场次分钟段
	function load_golf_minute(hour){
		//alert($('div[hour="' + hour + '"]').length+'--'+hour);
		var type_id=$('#tb_type_id').val();//商品大类
		var day=$('#tb_day').val();
		var hour_str=$('#tb_golf_hour').val();
		if($('#tb_package').val()==''||$('#tb_month').val()==''||$('#tb_day').val()==''||hour==''){
			return false;
		}
		//载入日期日历
		//----------------分钟载入home	
				
		if (($('div[hour="' + hour + '"]').length) <= 0) {
			var package_name= encodeURIComponent($('#tb_package').val());
			var load_url = '/wap_shop/index.php?act=goods&op=golf_minute&commonid=' + commonid + '&package=' + package_name + '&date=' + $('#tb_month').val() + '&type_id=' + type_id + '&day=' + day + '&hour=' + hour;
			//document.write(load_url);
			$.get(load_url, function(data, textstatus){
				
				$('#golf_minute').append(data);
				//场次点击HOME//alert(data);
				
				$('#golf_minute').find('span').toggle(function(){
					var title = $(this).attr('title');
					//获取当前场地下所选择的场次
					//if ($(this).find('input').val() != '' && $(this).hasClass('hovered')) 
					$(this).addClass('hovered');
					$(this).find('input').val(title);
					set_hour_info(hour);
					sum_price();
				}, function(){
					//获取场地名称
					$(this).removeClass('hovered');
					$(this).find('input').val('');
					set_hour_info(hour);
					sum_price();
				});
				//场次点击END
			
			});
			//----------------分钟载入end
		}
	}
	//计算总价
	function sum_price(){
		var sum_price=0;
		var minut_count=0;//选择的分钟场次数
		//选择的分钟场次数及价格
		$('#golf_minute span input').each(function(){
			if($(this).val()!=''){
				sum_price+=+ parseInt($(this).attr('price'));
				minut_count+=+1;
			}
		});
		$('#tb_golf_minute_num').val(minut_count);	
		//$('#sum_price').text(sum_price);
		//人数大于0的价格
		if($('#tb_golf_num').val()>0&&$('#tb_package').val()!=''){
			var package_name=encodeURIComponent($('#tb_package').val());
			$.get('/wap_shop/index.php?act=goods&op=get_golf_single_price&commonid='+commonid+'&package='+ package_name ,function(data){
				//alert($('#tb_golf_num').val()+'--'+ parseInt(data)+'--'+minut_count);			
				var golf_man_price= parseInt($('#tb_golf_num').val())* parseInt(data)*minut_count;
				$('#sum_price').text(sum_price+ parseInt(golf_man_price));
			});
		}
		//如果人数为0的价格
		if($('#tb_golf_num').val()=='0'||$('#tb_golf_num').val()==''){
			$('#sum_price').text(sum_price);
		}
	}
//获取所选小时段及分钟段
	function set_hour_info(hour){
		var minute_info='';
		var sum_price='';
		var price='';
		var golf_minute='';
		$('#golf_minute').find('div[hour="'+hour+'"]').find('span input').each(function(){
			if($(this).val()!=''){
				minute_info+=$(this).val()+'分';
			}
		});
		var hour_info=hour+'点'+'('+minute_info+')';
		$('#tb_hour_info'+hour).val(hour_info);
		//获取所有分钟入小时段，放在最后
		$('#golf_minute').find('input[name="tb_hour_info"]').each(function(){
			if($(this).val()!=''){
				golf_minute+=$(this).val()+',';
			}
		});
		$('#tb_golf_minute').val(golf_minute);
	}
	function remove_golf_minute(title){
		$('div[hour="'+title+'"]').remove();
		
	}
	//选择成人/儿童和套餐后更新价格
	function get_man_price(){
			if($('#tb_day').val()!=''){
				var price=$('div[title="出游日期："]').find('span[title="'+$('#tb_day').val()+'"]').attr('value');
				
				var price_arr=price.split('|');
				var man_type=$('#tb_man_type').val();
				var goods_price='';
				if(man_type.indexOf('成人')>=0){
					 goods_price=price_arr[0];
				}else{
					 goods_price=price_arr[1];
				}
				$('#goods_price').text(goods_price);
			}
		}
//--------------------------------------
    function checkSpec() {
	    var spec_param = <?php echo $output['spec_list'];?>;
		
	    var spec = new Array();
	    $('div[nctyle="ul_sign"]').find('.hovered').each(function(){
	        data = $(this).attr('data-param');
	        spec.push(data);
	    });
	    spec1 = spec.sort(function(a,b){
	        return a-b;
	    });
	    var spec_sign = spec1.join('|');
		var url='';
	    $.each(spec_param, function(i, n){
	        if (n.sign == spec_sign) {
				/*var url_arr = n.url.split('?');
				url = url_arr[1];
	            window.location.href = '<?php echo WAP_SHOP_SITE_URL;?>/index.php?'+url;*/
				url = n.url;
				az=url.replace(/shop/,"wap_shop");
				window.location.href =az;
	        }
    });
}
       </script>
       <!--E选择产品类开始-->
        <!-- S 购买数量 -->
        <?php if ($output['goods']['goods_state'] != 0 && $output['goods']['goods_storage'] >= 0) {?>
          <p><?php echo $lang['goods_index_buy_amount'];?><?php echo $lang['nc_colon'];?></p>
          <div class="qz-number clearfix" <?php if($output["goods"]["type_id"]=='46') echo 'style="display:none"';?>>
              <?php if ($output['goods']['is_fcode'] == 1) {?>
                   <!--F购买数量-->
                   <input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="qz-txt4 qz-light qz-fl num" <?php if ($output['goods']['is_fcode'] == 1) {?>readonly<?php }?>>
                   <span style="margin-left: 5px;color:#F3C">（每个F码优先购买一件商品）</span>
              <?php } else {?>
                   <!--S常规商品-->
            <span class="qz-ico decrease qz-ico-reduction qz-border qz-radius-l-t qz-radius-l-b"></span>
            <input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="qz-txt4 qz-light qz-fl num" <?php if ($output['goods']['is_fcode'] == 1) {?>readonly<?php }?>>
            <span class="qz-ico increase qz-ico-plus qz-border qz-radius-r-t qz-radius-r-b"></span>
            <!-- 虚拟商品限购数 -->
            <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) { ?>(每人次限购<strong>
              <!-- 虚拟抢购 设置了虚拟抢购限购数 该数小于原商品限购数 -->
              <?php 
			  echo ($output['goods']['promotion_type'] == 'groupbuy' && $output['goods']['upper_limit'] > 0 && $output['goods']['upper_limit'] < $output['goods']['virtual_limit']) ? $output['goods']['upper_limit'] : $output['goods']['virtual_limit'];
			  ?>
              </strong>件)<?php } ?> 
            <?php } ?>
           <!--E常规商品-->
     <?php }?>
             </div>
			 <!-- ----------高尔夫人数HOME------------ -->
     <div <?php if($output["goods"]["type_id"]!='46') echo 'style="display:none"';?>>
     <p>套餐外人数:</p>
     <div class="qz-number clearfix">
            <span class="qz-ico decrease_golf qz-ico-reduction qz-border qz-radius-l-t qz-radius-l-b"></span>
            <input type="text" name="" id="golf_num" value="0" size="3"  class="qz-txt4 qz-light qz-fl num">
            <span class="qz-ico increase_golf qz-ico-plus qz-border qz-radius-r-t qz-radius-r-b"></span>
                  </div>
    </div>
	 <!-- ----------高尔夫人数END------------ -->
                     <!-- S 显示已选规格及库存不足无法购买 -->
            <?php if (!empty($output['goods']['goods_spec'])) {?>
            <div class="qz-bk5"></div>
			<!---------已选择HOME--------------->
						<p class="goods_name_sel">
				<?php
if($output['goods']['is_virtual']=='1')echo '<span>已选择：</span>';
?>	<span class="tb_package"></span>&nbsp; <span class="tb_man_type"></span>&nbsp;
				<span class="tb_month"></span><span class="tb_day"></span>
				<span class="tb_golf_minute"></span>
				<span class="tb_golf_hour"></span>
			</p>
			<!---------已选择END--------------->
			<p class="goods_sum_price" <?php if($output['goods']["type_id"]!='46') echo 'style="display:none"'?>><span>合计：</span>￥<span id='sum_price'>0</span></p>
            <span style="color:#F00;display:none">
            <?php echo $lang['goods_index_you_choose'];?>: 
			      <?php echo implode('/', $output['goods']['goods_spec']);?>
            <?php }?>
            <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
            &nbsp;<?php echo $lang['goods_index_understock_prompt'];?>
            </span>
            <?php }?>
          
         <!-- E 显示已选规格及库存不足无法购买-->  
    </div>
    <!-- E 购买数量 -->
    <!--S购买数量验证数量添加，减除JS开始-->
    <script language="javascript">
    $(function(){
		$('.increase').click(function(){
		num = parseInt($('#quantity').val());
	    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
	    max = <?php echo $output['goods']['virtual_limit'];?>;
	    if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }
	    <?php } ?>
	    <?php if (!empty($output['goods']['upper_limit'])) {?>
	    max = <?php echo $output['goods']['upper_limit'];?>;
	    if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }
	    <?php } ?>
		max = parseInt($('[nctype="goods_stock"]').text());
		if(num < max){
			$('#quantity').val(num+1);
		}
	});
	//减少
	$('.decrease').click(function(){
		num = parseInt($('#quantity').val());
		if(num > 1){
			$('#quantity').val(num-1);
		}
	});
		//---------------高尔夫数量
	$('.increase_golf').click(function(){
		var num = parseInt($('#golf_num').val());
	    var max = 10;
	    if(num >= max){
	        alert('最多'+max+'人');
	        return false;
	    }
		$('#golf_num').val(num+1);
		$('#tb_golf_num').val($('#golf_num').val());
		sum_price();
	});
	//减少
	$('.decrease_golf').click(function(){
		num = parseInt($('#golf_num').val());
		if(num >= 1){
			$('#golf_num').val(num-1);
			$('#tb_golf_num').val($('#golf_num').val());
			sum_price();
		}
	});
	//计算总价
	function sum_price(){
		//alert($('#tb_golf_num').val()+'--'+$('#tb_package').val());
		//如果人数大于0
		var sum_price=0;
		minut_count=0;
		//计算分钟场次数及价格
		$('#golf_minute span input').each(function(){
			if($(this).val()!=''){
				sum_price+=+ parseInt($(this).attr('price'));
				minut_count+=+1;
			}
		});
		$('#tb_golf_minute_num').val(minut_count);
		//$('#sum_price').text(sum_price);
		//如果人数大于0的价格
		if ($('#tb_golf_num').val() > 0 && $('#tb_package').val() != '') {
			var package_name= encodeURIComponent($('#tb_package').val());
			//获取分钟段价格
			var load_url='/wap_shop/index.php?act=goods&op=get_golf_single_price&commonid=' + $('#tb_commonid').val() + '&package=' + package_name;
			//document.write(load_url);
			$.get(load_url, function(data){		
				//alert(sum_price+'--'+$('#tb_golf_num').val()+'--'+parseInt(data)+'--'+minut_count);
				var golf_man_price= parseInt($('#tb_golf_num').val())*parseInt(data)*minut_count;
				$('#sum_price').text(sum_price + golf_man_price);
			});
		}
		//如果人数为0的价格
		if($('#tb_golf_num').val()== '0' ||$('#tb_golf_num').val()==''){
			$('#sum_price').text(sum_price);
		}
	}
	//------------------
	});
    </script>
    <!--E购买数量验证，数量添加，减除JS开始-->
     <div class="qz-bk10"></div>
    
    <!--<div class="ui-arrowlink qz-background-white qz-padding clearfix qz-light">
	
        <i class="qz-ico qz-ico-twxq qz-fl"></i><a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=goods&op=goods_type_info&goods_id=<?php echo $output['goods']['goods_id'];?>"><span class="qz-fl" style="color:#4F5F6F">&nbsp;&nbsp;选着套餐</span>
		 </a>
    </div>-->
   
   
	<div class=" qz-background-white qz-padding clearfix qz-light qz-bottom-b"></div>
	<div class="qz-bk10"></div>
    <!--<a href="<?php echo WAP_SHOP_SITE_URL?>/index.php?act=goods&op=az&goods_id=<?php echo $output['goods_id'];?>">-->
    <a href="<?php echo WAP_SITE_URL?>/tmpl/product_info.html?goods_id=<?php echo $output['goods_id'];?>"><div class="ui-arrowlink qz-background-white qz-padding clearfix qz-light">
        <i class="qz-ico qz-ico-twxq qz-fl"></i><span class="qz-fl">&nbsp;&nbsp;图文详情<?php echo $config['chat_site_url'];?></span>
    </div></a>
    <!--S 满就送 -->
    <?php if($output['mansong_info']) { ?>
        <div class="qz-bk10"></div>
          <div class="qz-padding qz-bottom-b" style="background:#FFF">
             店铺活动：
			<span style="color:#F00"><?php echo $output['mansong_info']['mansong_name'];?></span>
             <time>( 
			 <?php echo $lang['nc_promotion_time'];?>
			 <?php echo $lang['nc_colon'];?>
			 <?php echo date('Y-m-d',$output['mansong_info']['start_time']).'--'.date('Y-m-d',$output['mansong_info']['end_time']);?> )
             </time>
          </div>
             <ul class="ui-list qz-background-none" style="background:#FFF">
                <li class="ui-border-t">
                <?php foreach($output['mansong_info']['rules'] as $rule) { ?>
                    <div class="ui-list-thumb qz-list-thumb">
                       <?php if(!empty($rule['goods_id'])) { ?>
                       <a href="<?php   echo str_replace("shop","wap_shop",$rule['goods_url']);?>" title="<?php echo $rule['mansong_goods_name'];?>" target="_blank"> <img src="<?php echo cthumb($rule['goods_image'], 60);?>" class="qz-img-block" alt="<?php echo $rule['mansong_goods_name'];?>"> </a>
                       <?php } ?>
                    </div>
                    <div class="ui-list-info qz-light">
                        <h4>
                        <?php echo $lang['nc_man'];?><em><?php echo ncPriceFormat($rule['price']);?></em><?php echo $lang['nc_yuan'];?>
                       <?php if(!empty($rule['discount'])) { ?>
                          ， 
					      <?php echo $lang['nc_reduce'];?>
                          <i>
					      <?php echo ncPriceFormat($rule['discount']);?>
                          </i>
					      <?php echo $lang['nc_yuan'];?>
                      <?php } ?>
                       <?php if(!empty($rule['goods_id'])) { ?>
                      ，
                       <?php echo $lang['nc_gift'];?> &nbsp;
                       <?php } ?>
                        </h4>
                    </div>
                     <?php } ?>
                 </li>      
            </ul>
          </div>
          <?php } ?>

		<!--销售记录-->
		
		<div class="qz-background-white  clearfix qz-light qz-bottom-b"></div>
		<div class="qz-bk10"></div>
		<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=goods&op=salelog&goods_id=<?php echo $output['goods_id'];?>">
		<div class="ui-arrowlink qz-background-white qz-padding clearfix qz-light qz-bottom-b">
			<i class="qz-ico qz-ico-yhpj qz-fl"></i><span class="qz-fl">&nbsp;&nbsp;<?php echo $lang['goods_index_sold_record'];?>(<?php echo $output['goods']['goods_salenum']; ?>)</span>
    </div>
    </a>
	
	<!--读取销售记录-->
	<?php if(!empty($output['sales']) && is_array($output['sales'])){?>
        <table class="ui-table">
            <thead>
                <tr>
                    <th><?php echo $lang['goods_index_buyer'];?></th>
					<!--<th><?php echo $lang['goods_index_buy_price'];?></th>-->
				  <th><?php echo $lang['goods_index_buy_amount'];?></th>
				  <th><?php echo $lang['goods_index_buy_time'];?></th>
                </tr>
            </thead>
    
            <tbody>
			<?php foreach($output['sales'] as $key=>$sale){?>
			<?php if($key<3){?>
                <tr>
                    <td><a href="index.php?act=member_snshome&mid=<?php echo $sale['buyer_id'];?>" target="_blank" data-param="{'id':<?php echo $sale['buyer_id'];?>}" nctype="mcard"  style="color:#4F5F6F"><?php echo $sale['buyer_name'];?></a></td>
                    <!--<td><?php echo $lang['currency'].$sale['goods_price'];?><?php echo $output['order_type'][$sale['goods_type']];?></td>-->
					<td><?php echo $sale['goods_num'];?></td>
                    <td><?php echo date('Y-m-d H:i:s', $sale['add_time']);?></td>
                </tr>
				<?php }?>
				<?php }?>
            </tbody>
        </table>
   <?php }?>
   </div>

      <!--E 用户评价 -->   
    <div class="qz-bk10"></div>
	
	<a href="<?php  echo urlShopWap('goods', 'good_pj_list',array("goods_id"=>$output['goods']["goods_id"]));?>">
    <div class="ui-arrowlink qz-background-white qz-padding clearfix qz-light qz-bottom-b">
        <i class="qz-ico qz-ico-yhpj qz-fl"></i><span class="qz-fl">&nbsp;&nbsp;用户评价(<?php echo $output['goods_evaluate_info']['all'];?>)</span>
    </div>
    </a>
	<?php
	if(!empty($output['goodsevallist']) && is_array($output['goodsevallist'])){?>
      <?php foreach($output['goodsevallist'] as $k=>$v){?>
    <div class="qz-background-white qz-padding">
        <p><?php echo count($output['goodsevallist']);  echo $v['geval_content'];?></p>
		<p><span class="raty" data-score="<?php echo $v['geval_scores'];?>"></span></p>
        <div class="qz-bk10"></div>
        <div class="qz-bottom-b qz-light clearfix">
            <span class="qz-fl" style="line-height:40px;"><img src="<?php echo getMemberAvatarForID($v['geval_frommemberid']);?>" style="border-radius:20px;max-width:40px;max-height:40px;">&nbsp;&nbsp;<font class="qz-color" ><?php if($v['geval_isanonymous'] == 1){?>
                  <?php echo str_cut($v['geval_frommembername'],2).'***';?>
                  <?php }else{?>
                  <a href="index.php?act=member_snshome&mid=<?php echo $v['geval_frommemberid'];?>" target="_blank" data-param="{'id':<?php echo $v['geval_frommemberid'];?>}" nctype="mcard"><?php echo $v['geval_frommembername'];?></a>
                  <?php }?></font>
			</i>&nbsp;&nbsp;<font class="qz-color"></font>
			</span>
            <span class="qz-fr"><?php echo @date('Y-m-d',$v['geval_addtime']);?></span>
            <div class="qz-bk10"></div>
        </div>
    </div>
	<?php } }?>
    <div class="qz-bk10"></div>
    <a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=goods&op=consulting_list&goods_id=<?php echo $output['goods']['goods_id'];?>">
    <!--<div class="ui-arrowlink qz-background-white qz-padding qz-bottom-b">
        <i class="qz-ico qz-ico-yhpj qz-fl"></i>&nbsp;&nbsp;<?php echo $lang['goods_index_goods_consult'];?>(<?php echo count($output['consult_list'])?>)
    </div>-->
	<div class="ui-arrowlink qz-background-white qz-padding qz-bottom-b">
        <i class="qz-ico qz-ico-yhpj qz-fl"></i>&nbsp;&nbsp;常见问题
    </div>
    </a>
	<div class="qz-bk10"></div>
	 <a href="javascript:void();"><div class=" qz-background-white qz-padding qz-bottom-b">
        <i class="qz-ico  qz-fl"></i>&nbsp;&nbsp;购买咨询
    </div></a>
	<?php if(!empty($output['store_info']['store_qq']) || !empty($output['store_info']['store_ww'])|| !empty($output['store_info']['store_phone'])){?>
    <div class="qz-zxlist qz-background-white qz-padding clearfix">
        <!--<dl>
            <span class="qz-ico qz-ico-message"></span>
            <div class="ti">即时通讯</div>
        </dl>-->
        <?php if(!empty($output['store_info']['store_qq'])){?>
        <dl>
		<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq'];?>">
            <span class="qz-ico qz-ico-qq"></span>
            <div class="ti">企鹅QQ</div>
		</a>
        </dl>
        <?php }?>
		<?php if(!empty($output['store_info']['store_phone'])){?>
        <dl>
		<a href="tel:<?php echo $output['store_info']['store_phone']; ?>" mce_href="tel:<?php echo $output['store_info']['store_phone']; ?>"><span class="qz-ico qz-ico-tel"></span>
            <div class="ti">联系电话</div></a>
        </dl>
		<?php }?>
        <?php if(!empty($output['store_info']['store_ww'])){?>
        <dl>
		<a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww'];?>&site=cntaobao&s=1&charset=<?php echo CHARSET;?>" >
            <span class="qz-ico qz-ico-wangw"></span>
            <div class="ti">阿里旺旺</div>
		</a>
        </dl>
		<?php }?>
    </div>
    <?php } ?>
    <div class="qz-bk10"></div>
	<div class="qz-background-white qz-padding qz-bottom-b"></div>
	<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=show_store&op=index&store_id=<?php echo $output['store_info']['store_id']?>">
    <div class="ui-arrowlink qz-background-white qz-padding clearfix qz-light qz-bottom-b">
        <i class="qz-ico qz-ico-dianp qz-fl"></i><span class="qz-fl">&nbsp;&nbsp;进入店铺</span>
    </div>
	</a>
	<div class="qz-background-white qz-padding clearfix qz-light">
        <p class="ui-flex ui-flex-pack-center" style="color:#4F5F6F">
	<?php  foreach ($output['store_info']['store_credit'] as $value) {?>
     &nbsp<?php echo $value['text'];?> <span style="color:red"> <?php echo $value['credit'];?> </span>分&nbsp
    <?php } ?>
	</p>
    </div>
    <div class="qz-bk10"></div>
    <div class="qz-background-white qz-padding clearfix qz-light qz-bottom-b">
        <p class="ui-flex ui-flex-pack-center">商品推荐</p>
    </div>
<?php print_r($output['goods_commend'])?>
<div class="qz-padding qz-padding-t">
        <div class="grid qz-background-white">
		<?php if(!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend'])>1){?> 
        <?php foreach($output['goods_commend'] as $goods_commend){?>
       <?php if($output['goods']['goods_id'] != $goods_commend['goods_id']){?> 
			<div class="grid-item">
                <!--<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=goods&op=index&goods_id=<?php echo $goods_commend['goods_id']?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>">-->
				<a href="<?php echo urlShopWAP('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>">
				<img src="<?php echo thumb($goods_commend, 240);?>" alt="<?php echo $goods_commend['goods_name'];?>" class="qz-img-block" /></a>
                <div class="ti" style="padding-top:10px;"><a class="qz-color4" style="height:20px;line-height:20px;" href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=goods&op=index&goods_id=<?php echo $goods_commend['goods_id']?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><?php echo (strlen($goods_commend['goods_name'])<13 ? $goods_commend['goods_name'] :  mb_substr($goods_commend['goods_name'],0,13,'utf-8').……);?></a></div>
                <div class="qz-price qz-color2"><?php echo $lang['currency'];?><?php echo $goods_commend['goods_price'];?></div>
            </div>
			<?php }}}?>
        </div>    
    </div>

   
</section>
<form id="buynow_form" method="post" action="<?php echo WAP_SHOP_SITE_URL;?>/index.php">
           <input id="act" name="act" type="hidden" value="buy" />
           <input id="op" name="op" type="hidden" value="buy_step1" />
           <input id="cart_id" name="cart_id[]" type="hidden"/>
           <input id="tb_commonid" name="tb_commonid" value='<?php echo $output["goods"]["goods_commonid"]?>' type="hidden"/>
		   	<input id="tb_package"name="tb_package" type="hidden" />
	<input id="tb_man_type" name="tb_man_type" type="hidden" />
	<input id="tb_month" name="tb_month" type="hidden" /> 
	<input id="tb_day" name="tb_day" type="hidden" /> 
	<input id="tb_type_id" name="tb_type_id" value='<?php echo $output["goods"]["type_id"]?>' type="hidden" /> 
	<input id="tb_golf_num" name="tb_golf_num" type="hidden" /> 
	<input id="tb_golf_minute_num" name="tb_golf_minute_num" type="hidden" /> 
	<input id="tb_golf_hour" name="tb_golf_hour" type="hidden" /> 
	<input id="tb_golf_minute" name="tb_golf_minute" type="hidden" /> 
	<span id="line_str"></span>
	<span id="golf_str"></span>
  </form>
<div class="qz-bk40"></div>
<div class="qz-bk40"></div>
<div class="qz-bk20"></div>
<div class="qz-bk10"></div>

<footer class="ui-footer" style="height:auto;"><div id="menu"></div>
    <ul class="qz-padding qz-background-white qz-bottom-b qz-top-b qz-light">
	<?php if ($_SESSION['is_login']) {?>
  
	<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=member_message&op=message"><?php echo $output['member_info']['member_name'];?></a>&nbsp;&nbsp;
	<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=login&op=logout">[退出]</a>
	<?php }else{ ?>
	<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=login&op=index">[登录]</a>
	<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=login&op=register">[注册]</a>
	<?php } ?>	
	
        <a class="qz-color4 top-btn" href="javascript:void(0);">
            <i class="qz-ico qz-ico-top qz-fr"></i>
            <span class="qz-fr">返回顶部&nbsp;</span>
        </a>
	
    </ul>
    
   
	 <div class="qz-padding qz-background-white clearfix"> 
        <div class="qz-ft-l qz-fl">
		<!--S 立即购买-->
		<?php if ($_SESSION['is_login']) {?>
           <dl class="qz-fl" >
		   <a href="javascript:void(0);" id="az" class="buynow <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0 || ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?> no-buynow <?php } ?>" title="<?php echo $output['goods']['buynow_text'];?>">
			 <input type="submit" value="<?php echo $output['goods']['buynow_text'];?>" class="ui-btn-lg ui-btn-primary" />  
			 </a> 
          </dl>
   
		<!--E 立即购买-->
		
		<!--S 加入购物车-->
		<?php if ($output['goods']['cart'] == true) {?>
		    <dl class="qz-fr" id="cart-add" >
			 <a href="javascript:void(0);" 
			 nctype="addcart_submit" 
			 class="addcart 
			 <?php if ($output['goods']['goods_state'] == 0 
			 || $output['goods']['goods_storage'] <= 0) {?>
			 no-addcart
			 <?php }?>" 
			 title="<?php echo $lang['goods_index_add_to_cart'];?>">
			 <i class="icon-shopping-cart"></i>
			   <input type="submit" value="<?php echo $lang['goods_index_add_to_cart'];?>" class="ui-btn-lg ui-btn-primary qz-background-yellow" />		 
			 </a>            
            </dl>
		<?php } ?>
		<!--E 加入购物车-->
		<!--S 我的购物车-->         
        </div>
		<a href="index.php?act=cart">
        <span class="qz-fr qz-ico qz-ico-shopping qz-relative" >
		   <div class="ui-badge-corner"><AZheng></div>
        </span>
		</a>
		<!--E 我的购物车-->
	<?php }else{?>
			<dl class="qz-fl" >
		   <a href="<?php echo urlShopWap('login', 'index');?>'" 
		     nctype="buynow_submit" 
			 class="buynow 
			 <?php if ($output['goods']['goods_state'] == 0 
			 || $output['goods']['goods_storage'] <= 0 
			 || ($output['goods']['is_virtual'] == 1 
			 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
			 no-buynow
			 <?php } ?>" 
			 title="<?php echo $output['goods']['buynow_text'];?>">
			 <input type="button" value="<?php echo $output['goods']['buynow_text'];?>" class="ui-btn-lg ui-btn-primary" />  
			 </a> 
          </dl>
   
		<!--E 立即购买-->
		
		<!--S 加入购物车-->
		<?php if ($output['goods']['cart'] == true) {?>
		    <dl class="qz-fr" id="" >
			 <a href="<?php echo urlShopWap('login', 'index');?>'" 
			 nctype="addcart_submit" 
			 class="addcart 
			 <?php if ($output['goods']['goods_state'] == 0 
			 || $output['goods']['goods_storage'] <= 0) {?>
			 no-addcart
			 <?php }?>" 
			 title="<?php echo $lang['goods_index_add_to_cart'];?>">
			 <i class="icon-shopping-cart"></i>
			   <input type="submit" value="<?php echo $lang['goods_index_add_to_cart'];?>" class="ui-btn-lg ui-btn-primary qz-background-yellow" />		 
			 </a>            
            </dl>
		<?php } ?>
		<!--E 加入购物车-->
		<!--S 我的购物车-->         
        </div>
		
	<?php }?>
    </div>
	
</footer>

<script language="javascript">
//立即购买处理
    <?php if ($output['goods']['goods_state'] == 1 && $output['goods']['goods_storage'] > 0 ) {?>
        <?php if (!($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
        $('#az').click(function(){
            buynow(<?php echo $output['goods']['goods_id']?>,checkQuantity());
        });
        <?php }?>
    <?php }?>
//登录判断级跳转函数
function buynow(goods_id,quantity){
<?php if ($_SESSION['is_login'] !== '1'){?>
	login_dialog();
<?php }else{?>
    if (!quantity) {
        return;
    }
    <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) { ?>
    alert('不能购买自己店铺的商品');return;
    <?php } ?>
    $("#cart_id").val(goods_id+'|'+quantity);
	//-----------验证是否选择HOME------------
	var type = '<?php echo $output["goods"]["type_id"]?>';
	//线路验证
	if(type=='40'||type=='41'||type=='42'){
		if($('#tb_package').val()==''){
			$('div[title="套餐类型："]').find('i').text('请选择套餐');
			return false;
		}
		if($('#tb_man_type').val()==''){
			$('div[title="出游人群："]').find('i').text('请选择出游人群');return false;
		}
		if($('#tb_month').val()==''){
			$('div[title="出游月份："]').find('i').text('请选择出游月份');return false;
		}
		if($('#tb_day').val()==''){
			$('div[title="出游日期："]').find('i').text('请选择出游日期');return false;
		}
	}
	//门票验证
	if(type=='43'){
		if($('#tb_package').val()==''){
			$('div[title="收费项目："]').find('i').text('请选择收费项目');
			return false;
		}
		if($('#tb_man_type').val()==''){
			$('div[title="门票种类："]').find('i').text('请选择门票种类');return false;
		}
		//电子票
		/*if($('#tb_ticket_type').val()==''){
			$('div[title="门票类型："]').find('i').text('请选择门票类型');return false;
		}*/
		if($('#tb_month').val()==''){
			$('div[title="出游月份："]').find('i').text('请选择出游月份');return false;
		}
		if($('#tb_day').val()==''){
			$('div[title="出游日期："]').find('i').text('请选择出游日期');return false;
		}
	}
	//高尔夫验证
	if(type=='46'){
		if($('#tb_package').val()==''){
			$('div[title="套餐类型："]').find('i').text('请选择收费项目');
			return false;
		}
		if($('#tb_month').val()==''){
			$('div[title="月份："]').find('i').text('请选择月份');return false;
		}
		if($('#tb_day').val()==''){
			$('div[title="日期："]').find('i').text('请选择日期');return false;
		}
		if($('#tb_golf_hour').val()==''){
			$('div[title="时间："]').find('i').text('请选择开球时间');return false;
		}
		var golf_minute='';
		$('#golf_minute span input').each(function(){
			golf_minute+=$(this).val();
		});
		if(golf_minute==''){
			$('section[title="场次"]').find('i').text('请选择开球场次');return false;
		}
	}
	//------------验证EDN-----------
    $("#buynow_form").submit();
<?php }?>
}
// 验证购买数量
function checkQuantity(){
    var quantity = parseInt($("#quantity").val());
    if (quantity < 1) {
        alert("<?php echo $lang['goods_index_pleaseaddnum'];?>");
        $("#quantity").val('1');
        return false;
    }
    max = parseInt($('[nctype="goods_stock"]').text());
    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
    max = <?php echo $output['goods']['virtual_limit'];?>;
    if(quantity > max){
        alert('最多限购'+max+'件');
        return false;
    }
    <?php } ?>
    <?php if (!empty($output['goods']['upper_limit'])) {?>
    max = <?php echo $output['goods']['upper_limit'];?>;
    if(quantity > max){
        alert('最多限购'+max+'件');
        return false;
    }
    <?php } ?>
    if(quantity > max){
        alert("<?php echo $lang['goods_index_add_too_much'];?>");
        return false;
    }
    return quantity;
}

//购物车操作
$('#cart-add').click(function(){
	var url = 'index.php?act=cart&op=add';
    quantity = parseInt(checkQuantity());
    $.getJSON(url, {'goods_id':<?php echo $output['goods']['goods_id']?>, 'quantity':quantity}, function(data) {
        if (data != null) {
            if (data.state) {
				//alert( data.state);
               resetCart();
            } else {
                alert(data.msg);
            }
        }
    });
});
resetCart();
//刷新购物车数量
function resetCart(){

	var num = parseInt($('AZheng').text());
	
	 $.getJSON('index.php?act=cart&op=ajax_load&callback=?', function(result){
		 
	                if(result){
	       	          if(result.cart_goods_num >0){
						  if( result.cart_goods_num !== num){
							$('AZheng').empty();
							$('AZheng').append(result.cart_goods_num);
						   }
	                     } else {
						  $('AZheng').empty();
                          $('AZheng').append(0);
	                     }
	                }
	            });
}


    $('.grid').masonry({
        itemSelector: '.grid-item'
    });
    
    $(".top-btn").click(function(){
        $('body,html').animate({scrollTop:0},500);
        return false;
    });
    
    $(".qz-rt .qz-ico-allowl").click(function(){
        if ($(this).hasClass("qz-ico-allowr")) { //展开收藏
            $(this).removeClass("qz-ico-allowr");
            $(this).parent().animate({width:"40px"});
            $(this).parent().parent().find("p").css("width","86%");
        } else {//收缩收藏
            $(this).addClass("qz-ico-allowr");
            $(this).parent().animate({width:"80px"});
            $(this).parent().parent().find("p").css("width","76%");
        }
    });
    
    $(".qz-rt .qz-ico-collection").click(function(){
        if ($(this).hasClass("qz-ico-collection-hov")) {//取消收藏
            $(this).removeClass("qz-ico-collection-hov");
        } else {//收藏
            $(this).addClass("qz-ico-collection-hov");
        }
    });
    $.get("index.php?act=goods&op=addbrowse",{gid:<?php echo $output['goods']['goods_id'];?>});

$(document).ready(function(){
   $('.raty').raty({
        path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
        readOnly: true,
        score: function() {
          return $(this).attr('data-score');
        }
    });

   $('a[nctype="nyroModal"]').nyroModal();
   //是否显示套餐
   var show_package='<?php echo $output["show_package"]?>';
   if(show_package=='no'){
		$('div[title="套餐类型："]').hide();
		$('p[data="套餐类型"]').hide();
		var goods_package='<?php echo $output["goods_package"]?>';
		$('#tb_package').val(goods_package);
		
   }
});
</script> 