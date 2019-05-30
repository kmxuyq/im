<?php  if($output['goods']['calendar_type'] ==2){ //酒店价格日历?>

    <div class="detail_check_in_time">
        <div class="time hotel_in_date"><?php echo date("m月d",TIMESTAMP);?>日</div>
        <input type="hidden"   class="input_hotel_in_date"  value="<?php echo date("Y-m-d");?>" />
        <input type="hidden"   class="input_hotel_out_date"  value="<?php echo date("Y-m-d",(TIMESTAMP+86400));?>" />
        <div class="time_tips">入驻<br/>时间</div>
        <div class="count hotel_num">共<span>1</span>天</div>
        <input type="hidden"  class="input_hotel_num"  value="1">
        <div class="time hotel_out_date"><?php echo date("m月d",(TIMESTAMP+86400));?>日</div>
        <div class="time_tips">离店<br/>时间</div>
    </div>
    <div class="spec_val_list"></div>
    <script>
        hotel_x();
        $(".detail_check_in_time").click(function(){
            var url = "index.php?act=goods_stock_price&op=hotel_calendar&commonid=<?php echo $output['goods']['goods_commonid'];?> ";
            var data = data || {};
            $.get(url,data,function(result){
                $('#cal_cell').remove();
                var alertPop=$('<div id="cal_cell"></div>').appendTo($('body'));
                alertPop.css({position: 'fixed', top: 0, left:0, 'z-index':'999',display: 'block', width:'100%',  height:'100%',background:'#fff', 'overflow': 'hidden','overflow-y': 'auto','padding':'40px 0'});
                $('#cal_cell').html(result);
            })
        });
        function hotel_x(data){
            var data= data || '';
            if(data && data != ''){
                var price=data.price;
                var day=data.day;
                var stock=data.stock;
                $('.input_hotel_in_date').val(day[0]);
                $('.input_hotel_out_date').val(day[1]);
                $('.detail_check_in_time .hotel_price').text(price[1]);
                str1 = day[0].replace(/-/g,'/');
                str2 = day[1].replace(/-/g,'/');

                var date1 = new Date(str1);
                var date2 = new Date(str2);

                $('.detail_check_in_time .hotel_in_date').text(formatDate(date1));
                $('.detail_check_in_time .hotel_out_date').text(formatDate(date2));
                var hotel_num = (date2 -date1)/(60*60*24)/1000;
                $(".detail_check_in_time .hotel_num span").text(Math.abs(hotel_num));//住宿天数
                $(".detail_check_in_time .input_hotel_num").val(Math.abs(hotel_num));//住宿天数
            }else{
                var day=[];
                day[0] = '<?php echo date("Y-m-d",TIMESTAMP);?>';
                day[1] = '<?php echo date("Y-m-d",(TIMESTAMP+60*60*24));?>';
            }
            var goods_commonid = <?php  echo $output["goods"]["goods_commonid"]; ?>;
            $.post("index.php?act=goods&op=get_spec_price",{hotel_in_date:day[0],hotel_out_date:day[1],goods_commonid:goods_commonid},function(data){
                $(".spec_val_list").html(data);
            });
            //$('#ss_price').text( price_old * Math.abs(hotel_num)); 
        }

         function formatDate(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            m = m < 10 ? '0' + m : m;
            var d = date.getDate();
            d = d < 10 ? ('0' + d) : d;
            return m + '月' + d + '日';
        }
    </script>
<?php }?>
<?php // print_r($output['goods']['spec_name']);exit;?>
<div class="goods_types <?php if(!empty($output['goods']['spec_name'])) echo 'border_top'?>"> <?php if($output['goods']['goods_state'] != 10 && $output['goods']['goods_verify'] == 1){?>
        <?php if($output['goods']['calendar_type'] ==8){ //后面留住备选，暂时不走?>
            <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>
                <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
                    <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
                        <div class="detail_hotal_choose">
                            <span class="t1">房间类型</span>
                            <span class="t2"><?php echo $v;?>/每晚 <i>￥129</i></span>
                        </div>

                    <?php  }?>
                <?php  }?>

            <?php }?>
        <?php }else{ //酒店类型外的规格?>
            <?php if (is_array($output['goods']['spec_name']) && $output['goods']['calendar_type'] !=2) { ?>
                <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>

                    <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
                        <!--商品名称、价格、是否保佑-->
                        <section class="buy_choice">
                        <!--选择净含量-->
                        <dl class="buy_choice_content">
                        <dt title="<?php echo $val;?>"><?php echo $val;?><?php echo $lang['nc_colon'];?></dt>

                        <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
                            <dd>
                            <a><?php echo $v;?></a>
                            <span><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/choice_selected.png" /></span>
                            </dd><?php }?>

                        <i></i>
                        <div class="clear"></div>
                        </dl>
                        </section><?php }?>
                <?php }?>
            <?php }?>
        <?php } ?>
    <?php }?>
    <!--价格日历 -->

    <?php if($output['goods']['calendar_type'] ==1){//普通价格日历
        $stock_url = urlWapShop('goods_stock_price', 'index', array('commonid' => $output['goods']['goods_commonid'],'calendar_type'=>1));
        ?>

        <div  id="hotel_ceshi" style="clear: both; overflow: hidden" style="font-size: 14px">
            <span title="<?php echo $stock_url;?>" id="rili_span_stock">
                <a href="javascript:void(0)" style="display: block">
                    <span style="background: #3b84ed;color: #fff;padding: 6px 6px;border-radius: 3px; font-size: 14px; display: inline-block;">请选择出行时间</span><span class="title" style="padding: 2px 5px; font-size: 15px" >
                        <input style="background-color: white;" type="button"  class="putong_date"  value="" />
                    </span>
                </a>
            </span>
            <input type="hidden" value="-1" name="spec_name" id="spec_name_input" autocomplete="off"  />
            <div class="bill_btns" style="line-height: 30px;display: none" ><input   type="hidden"  class="putong_man_input"  value="-1" autocomplete="off" />成人票：<i>￥</i><span id="putong_man_price" ></span ></div>
            <div class="bill_btns" style="line-height: 30px;display: none" ><input   type="hidden"  class="putong_child_input"  value="-1" autocomplete="off"  />儿童票：<i>￥</i><span id="putong_child_price"></span ></div>
            <div class="bill_btns" style="line-height: 30px;display: none" ><input   type="hidden"  class="putong_diff_price"  value="-1" autocomplete="off" />单房差：<i>￥</i><span id="putong_diff_price"></span ></div>
        </div>
    <?php }else if($output['goods']['calendar_type'] ==3) {//高尔夫球价格日历
    $stock_url = urlWapShop('goods_stock_price', 'golf_calendar', array('commonid' => $output['goods']['goods_commonid'],'calendar_type'=>3));
    ?>
        <span title="<?php echo $stock_url;?>"  id="rili_span_stock">  <a style="background: #3b84ed;color: #fff;padding: 6px 6px;border-radius: 3px; font-size: 14px; display: inline-block;" href="javascript:void(0)" >请选择预定日期</a></span>
    <input type="button" style="background-color: white;" id="picktime" value="" autocomplete="off" />

        <div class="buy_quantity">

            <div style="padding: 3px 0; clear: both;    font-size: 16px;line-height: 40px; height: 34px;" >
                <span>预订时段(小时)</span>
                <select  id="calendar_hour" style="border: 1px #ccc solid;height: 30px;line-height: 30px; border: 1px #aaa solid; border-radius: 7px; padding: 0 3px">
                    <option selected="selected" value ="">请选择....</option>
                    <option value ="8">8点</option>
                    <option value="9">9点</option>
                    <option value="10">10点</option>
                    <option value ="11">11点</option>
                    <option value="12">12点</option>
                    <option value="13">13点</option>
                    <option value ="14">14点</option>
                    <option value="15">15点</option>
                    <option value="16">16点</option>
                    <option value="17">17点</option>
                    <option value="18">18点</option>
                </select>
                <input type="hidden"  id="calendar_hour_input" value="" autocomplete="off" />
            </div>
            <div style="padding: 3px 0; clear: both;    font-size: 16px;line-height: 40px;height: 34px;" >
                <span>预订时段(分钟)</span>
                <select id="calendar_min" style="border: 1px #ccc solid;height: 30px;line-height: 30px; border: 1px #aaa solid; border-radius: 7px;padding: 0 3px">
                    <option selected="selected" value ="">请选择....</option>
                    <option value ="00">00分</option>
                    <option value="10">10分</option>
                    <option value="20">20分</option>
                    <option value ="30">30分</option>
                    <option value="40">40分</option>
                    <option value="50">50分</option>
                </select>
                <input type="hidden"  id="calendar_min_input" value="" autocomplete="off"/>
            </div>
        </div>

        <script>
            function golf_x(data){
                var data=JSON.parse(data);
                var day=data.day;
                $('#picktime').val(day);
            }
        </script>


    <?php } ?>

    <script>
        $(document).ready(function () {
            $("#calendar_hour").bind("change",function(){
                $("#calendar_hour_input").val($(this).val());
            });
            $("#calendar_min").bind("change",function(){
                $("#calendar_min_input").val($(this).val());
            });
        });
    </script>

    <!-- 价格日历结束-->

    <script type="text/javascript">
    	var oldPrice;

	    $(function(){
	        $("#hotel_ceshi .bill_btns").click(function(){
	            var _ss_price=$('#ss_price');
	            if(_ss_price.hasClass('old_price')){
	                oldPrice = $('.old_price').text();
	                _ss_price.removeClass('old_price');
	                _ss_price.text(0)
	            }
	            if($(this).hasClass('on')){
	                var _thisVal = $(this).find('span').text();
	                $(this).removeClass('on').find('input[type="hidden"]').val('-1');
	                _ss_price.text(_ss_price.text()-_thisVal);
	                if($("#hotel_ceshi .bill_btns.on").length == 0){
	                    _ss_price.addClass('old_price');
	                    _ss_price.text(_thisVal*$('.goods_details .buy_choice .buy_quantity .choice_num .num').val());
	                }
	                price_old  = $(this).find('span').text();
	            }else{
	                $("#hotel_ceshi .bill_btns").removeClass('on');
	                $(this).addClass('on');
	                var _thisVal = $(this).find('span').text();
	                $(this).find("input[type='hidden']").val($(this).find('span').text());
	                _ss_price.text(_thisVal*$('.goods_details .buy_choice .buy_quantity .choice_num .num').val());
	                price_old  = $(this).find('span').text();
	            }
	            if($('.bill_btns.on').length == 0){
	                _ss_price.text(0)
	            }
	        });
	
	
	
	
	    });

        function putong_x(data){
            $('.bill_btns').css("display","block");
            var data=JSON.parse(data);
            var child_price=data.child_pirece;
            var man_price=data.man_pirece;
            var diff_price=data.diff_price;
            var day=data.day;
            if(child_price == "无" || child_price=='0'){
                $("#putong_child_price").prev('i').text('').parent("div").css("display",'none');
            }
            if(man_price == "无" || man_price=='0'){
                $("#putong_man_price").prev('i').text('').parent("div").css("display",'none');

            }
            if(diff_price == "无" || diff_price=='0'){
                $("#putong_diff_price").prev('i').text('').parent("div").css("display",'none');
            }
            $('.putong_date').val(day);
            $("#putong_child_price").text(child_price);//儿童价
            $("#putong_man_price").text(man_price);//儿童价
            $("#putong_diff_price").text(diff_price);//单房差
        }


        $('#rili_span_stock').click(function(){
            var url=$(this).attr('title');
            if(<?php echo $output['goods']['calendar_type'] ==1 ? 1:0 ?>){ //普通旅游门票价格日历弹出窗
                var common_id= <?php   echo $output['goods']['goods_commonid'];?>;
                var package = $("#spec_name_input").val();

                if(<?php echo !empty($output['goods']['spec_name']) ==1 ? 1:0 ?>){ //如果添加了商品规格及价格日历套餐
                    if(package =='-1'){
                        var title = $('.goods_types section:eq(0)').find('.buy_choice_content dt').attr("title");
                        layer.msg("请选择"+title);return;
                    }
                    url = '?act=goods_stock_price&op=index&commonid='+common_id+'&calendar_type=1&package='+package;
                }

            }
            var data = data || {};
            $.get(url,data,function(result){
                $('#cal_cell').remove();
                var alertPop=$('<div id="cal_cell"></div>').appendTo($('body'));
                alertPop.css({position: 'fixed', top: 0, left:0, 'z-index':'999',display: 'block', width:'100%',  height:'100%',background:'#fff', 'overflow': 'hidden','overflow-y': 'auto','padding':'40px 0'});
                $('#cal_cell').html(result);
            });
        });

    </script>
    <?php if($output['goods']['calendar_type'] ==2 || $output['goods']['calendar_type'] ==3){ ?>
        <!--   酒店和高尔夫单个存订单，数量为1-->
        <div class="buy_choice">
            <div class="kucun">
                <span>销售： <?php echo $output['goods']['goods_salenum']; ?></span>
                <input type="hidden" class="num" id="quantity" value="1" />

                <div class="mykucun">库存： <span><?php echo $output['goods']['goods_storage']; ?> </span></div><!--库存验证 -->
            </div>
        </div>
    <?php }else if($output['goods']['calendar_type'] == 4){ ?>
        <!--车票数据处理-->
        <div class="buy_choice">
            <?php if(isset($output['goods']['get_date'])){ ?>
                <div class="buy_quantity">
                    <div class="title">购票数量</div>
                    <div class="choice_num">
                        <a href="javascript:;" class="min"></a>
                        <input type="text" class="num" id="quantity" value="1" onkeyup="this.value=this.value.replace(/[^\d]/g,'1') " onafterpaste="this.value=this.value.replace(/[^\d]/g,'1')" />
                        <a href="javascript:;" class="plus"></a><span id="stock_msg"></span>
                    </div>
                </div>
                <div class="kucun">
                    <span>发车时间:</span>
                    <span style="margin-left: 10px" class="pw_start_date" ><?php echo $output['goods']['get_date'].' '.$output['goods']['departure_time'];?></span>
                </div>
                <div class="kucun">
                    <span>销售： <?php echo $output['goods']['goods_salenum'];?></span>
                    <span style="margin-left: 10px">库存：<?php echo $output['goods']['storage'];?></span>
                </div>
                <div class="mykucun">库存： <span><?php echo $output['goods']['storage']; ?></span></div>
            <?php }else{ ?>
                <div id="station_ticked_box" style="display:none">
                    <div class="buy_quantity">
                        <div class="title">购票数量</div>
                        <div class="choice_num">
                            <a href="javascript:;" class="min"></a>
                            <input type="text" class="num" id="quantity" value="1" onkeyup="this.value=this.value.replace(/[^\d]/g,'1') " onafterpaste="this.value=this.value.replace(/[^\d]/g,'1')" />
                            <a href="javascript:;" class="plus"></a><span id="stock_msg"></span>
                        </div>
                    </div>
                    <div class="kucun" >
                        <span>发车时间:</span>
                        <span style="margin-left: 10px" id="station_ticked_time" class="pw_start_date"></span>
                    </div>
                    <div class="kucun" >
                        <span id="station_ticked_sales"></span>
                        <span style="margin-left: 10px" id="station_ticked_storage"></span>
                    </div>
                    <div class="mykucun" ><span id="station_ticked_storage_h"></span></div>
                </div>
                <div class="kucun" id="getDataBtn">
                    <span>发车时间:</span>
                    <span style="margin-left: 10px">请选择日期</span>
                </div>

            <?php } ?>
        </div>

        <script>
            $("#getDataBtn").click(function(){
                var url = "index.php?act=goods_stock_price&op=golf_calendar";
                var data = data || {};
                $.get(url,data,function(result){
                    $('#cal_cell').remove();
                    var alertPop=$('<div id="cal_cell"></div>').appendTo($('body'));
                    alertPop.css({position: 'fixed', top: 0, left:0, 'z-index':'999',display: 'block', width:'100%',  height:'100%',background:'#fff', 'overflow': 'hidden','overflow-y': 'auto','padding':'40px 0'});
                    $('#cal_cell').html(result);
                });

            });
            function golf_x(date){
                var date=JSON.parse(date);
                var day=date.day;
                var goods_id = '<?php echo $output['goods']['goods_id'];?>';
                var departure_time = '<?php echo $output['goods']['departure_time'];?>';
                var goods_salenum = '<?php echo $output['goods']['goods_salenum'];?>';
                $.post('index.php',{'act':'goods','op':'get_cart_ticket','dates':day[0],'goods_id':goods_id},function(data){
                    var dataObj = JSON.parse(data);
                    if(dataObj.respond === '1'){
                        $('#getDataBtn').hide();
                        $('#station_ticked_box').show();
                        $('#station_ticked_time').text(dataObj.get_date+' '+departure_time);
                        $('#station_ticked_sales').text('销售：'+ goods_salenum);
                        $('#station_ticked_storage').text('库存： '+dataObj.date_storage);
                        $('#station_ticked_storage_h').text(dataObj.date_storage);
                        $('input[name="date"]').val(dataObj.get_date);
                    }else{
                        layer.msg(dataObj.msg);
                    }
                });
            }

        </script>
    <?php }else{ ?>
        <div class="buy_choice">
            <!--购买数量-->

            <div class="buy_quantity">
                <div class="title">购买数量</div>
                <div class="choice_num">
                    <a href="javascript:;" class="min"></a>
                    <input type="text" class="num" id="quantity" value="1" onkeyup="this.value=this.value.replace(/[^\d]/g,'1') " onafterpaste="this.value=this.value.replace(/[^\d]/g,'1')" />
                    <a href="javascript:;" class="plus"></a><span id="stock_msg"></span>
                </div>
            </div>

            <!--库存-->
            <div class="kucun">
                <span>销售： <?php echo $output['goods']['goods_salenum']; ?></span>

                    <span style="margin-left: 10px">库存：
                        <?php if($_SESSION['share_shop'] == 1 and $output['goods']['isshare'] == 1){ ?><!-- 分销商品 -->
                        <?php if($output['goods']['share_stock']){ ?>有货<?php } else { ?>无货<?php } ?>
                        <?php }else{?>
                            <?php if($output['goods']['goods_storage']>0){ ?>有货<?php } else { ?>无货<?php } ?>
                        <?php }?>
                    </span>
            </div>
            <?php if($_SESSION['share_shop'] == 1 and $output['goods']['isshare']==1): ?>
                <div class="mykucun">库存： <span><?php echo $output['goods']['share_stock']; ?></span></div><!--分销库存 -->
            <?php else:?>
                <div class="mykucun">库存： <span><?php echo $output['goods']['goods_storage']; ?></span></div>
            <?php endif;?>
        </div>

    <?php } ?>
</div>
<!--购买流程提示-->
<?php switch (intval($output['goods']['gc_id'])) {
    case '1129':
        $tip_type = 1;
        break;
    case '1126':
        $tip_type = 2;
        break;
    case '1130':
        $tip_type = 3;
        break;
    case '1127':
        $tip_type = 4;
        break;
    case '1128':
        $tip_type = 5;
        break;
} ?>

<?php if(isset($tip_type)){?>
    <div class="user_process_tips">
        <img src="<?php echo SHOP_TEMPLATES_URL; ?>/images/buy_tip/tip<?php echo $tip_type;?>.jpg">
    </div>
<?php } ?>


<style>
    .user_process_tips{padding:10px 0; overflow: hidden; clear:both }
    .user_process_tips img{width: 100%}
    /*酒店类型样式*/
    .pd_wrap .right span{
        font-family: "Mcirosoft Yahei";
        padding: 0 10px 0 0;
        position: relative;
        top: -18px;
        font-size: 22px;
        color: red;
    }
    .pd_wrap .left span{
        font-family: "Mcirosoft Yahei";
        padding: 0 10px 0 0;
        position: relative;
        top: -18px;
        font-size: 22px;
        color: red;
    }
    .center{
        position: relative;
        top: -8px;
    }
    .calendar .cal_body .off {
        float: left;
        width: 43px;
        height: 50px;
        line-height: 50px;
        box-sizing: border-box;
        text-align: center;
        border-left: 1px #ebebeb solid;
        position: relative;
        -webkit-box-flex: 1;
        -moz-box-flex: 1;
        -ms-flex: 1;
        -webkit-flex: 1;
        flex: 1;
    }

</style>