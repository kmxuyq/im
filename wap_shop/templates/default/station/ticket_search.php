<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/goods.css" />
<div class="train_position_top">
    <div class="arrow" onclick="location.href = 'index.php?act=show_store&op=<?php if ($output['share_shop']) {echo 'share';} else {echo 'index';}?>&store_id=<?php echo $output['route_store_id']; ?>'"></div>
    <div class="text">车票</div>
    <div class="help" onclick="showPop()">帮助</div>
</div>
<form method="get" id="search_form">
<input name="act" value="station" type="hidden" />
<input name="op" value="ticket_list" type="hidden" />
<div class="train_destination">
    <div class="cleaboth">
        <div class="start">
            <div class="tt">出发地</div>
            <div class="content" onclick="location.href = 'index.php?act=station&op=station_location&type=start'" id="show_start_station"><?php if (!empty($output['search_data']['start'])) {echo $output['search_data']['start'];} else {echo $output['default_data']['start'];}?></div>
            <input name="start_station" type="hidden" value="<?php echo $output['search_data']['start']; ?>" />
        </div>
        <div class="end">
            <div class="tt">目的地</div>
            <div class="content" onclick="location.href = 'index.php?act=station&op=station_location&type=end'" id="show_end_station"><?php if (!empty($output['search_data']['end'])) {echo $output['search_data']['end'];} else {echo $output['default_data']['end'];}?></div>
            <input name="end_station" type="hidden" value="<?php echo $output['search_data']['end']; ?>" />
        </div>
    </div>
    <div class="choose_time" ><?php if (!empty($output['search_data']['time'])) {echo $output['search_data']['time'];} else {echo $output['default_data']['time'];}?></div>
    <input name="search_time" type="hidden" value="<?php echo $output['search_data']['time']; ?>"/>
</div>
<div class="showPop">
        <h1>购票须知</h1>
        <p>1、高铁安检是检查身份证和车票，二者缺一不可(没取票的直接身份证就可以)。行李过安检，人过安检门之后，会有金属探测仪检查一遍就可以了。</p>
    　　<p>2、高铁检票大多数情况下是使用自助检票机，车票正面朝上(有身份证的那边在左边)，插入卡槽，等车票弹出，就可以通过。车票上会有黑色箭头就是检票后出现的。</p>
    　　<p>3、车票上的X车X号，车就是车厢号，号就是你进入后去找你的位置，在行李架边上都是有的，放置行李的时候一定要小心，不要砸到别人。</p>
    　　<p>4、到站之后，按照指示到出站口，可以选择自助检票出站，和进站差不多，也可以在人工检票出站。还有可以使用身份证刷卡出站，要注意的是，如果取票了就不可以刷身份证了。如果你觉得会弄丢票，就不要取票直接刷身份证。
    　　注意事项：</p>
    　　<p>如果取票进站的就不可以刷身份证出站了。如果你觉得会弄丢票，就不要取票直接刷身份证。</p>
    　　<p>取票一定要保存好，直到下车。如果弄丢票了，请保留你的短信或者网银截图，在出站的时候走人工检票通道。</p>
</div>
<div class="shade"></div>
<button class="train_sreach_btn" type="button">搜索</button>
    <a href="/wap_shop/tmpl/member/chat_info.html?goods_id=10086&t_id=<?php echo $output['kefu_id']; ?>" class="online_socket">在线客服</a>
</form>
<script src="<?php echo RESOURCE_SITE_URL . "/js/layer/layer.js" ?>" type="text/javascript"></script>
<script>
    $('.train_sreach_btn').click(function(){
        if($('input[name="start_station"]').val() == ''){
            var show_start = $('#show_start_station');
            show_start.empty();
            show_start.attr('style',"color:red;");
            show_start.html('请选择起始站!');
            return;
        }
        if($('input[name="end_station"]').val() == ''){
            var show_end = $('#show_end_station');
            show_end.empty();
            show_end.attr('style',"color:red;");
            show_end.html('请选择终点站!');
            return;
        }
        $("#search_form").submit();
    })
</script>
<script>
    function golf_x(data){
        var data=JSON.parse(data);
        var day=data.day;
        $.get("index.php?act=station&op=set_station_location&station_time="+day,day,function(){
            $('input[name="search_time"]').val(day);
            $('.choose_time').text(day);
            if($('input[name="start_station"]').val() != '' && $('input[name="end_station"]').val() != ''){
                window.location.reload();//刷新页面获取推荐信息
            }
        });
    }
    $('.choose_time').click(function(){
        var url = "index.php?act=goods_stock_price&op=golf_calendar";
        var data = data || {};
        $.get(url,data,function(result){
            $('#cal_cell').remove();
            var alertPop=$('<div id="cal_cell"></div>').appendTo($('body'));
            alertPop.css({position: 'fixed', top: 0, left:0, 'z-index':'999',display: 'block', width:'100%',  height:'100%',background:'#fff', 'overflow': 'hidden','overflow-y': 'auto','padding':'40px 0'});
            $('#cal_cell').html(result);
        });
    });
</script>
<?php if (!empty($output['recommended'])) {
    ?>
<div class="train_tt_tips">为您推荐以下相关车票信息</div>
<dl class="train_list">
    <?php foreach ($output['recommended'] as $v) {
        ?>
    <dd class="rows">
        <div class="lt">
            <div class="start_block">
                <div class="tt">
                    <span class="icon">始</span>
                    <span class="text"><?php echo $v['start_station']; ?></span>
                </div>
                <div class="price">
                    ￥<?php echo $v['goods_price']; ?>
                </div>
            </div>
            <div class="start_time">
                <div class="time"><?php echo $v['date']; ?></div>
                <div class="text">发车时间:<?php echo $v['departure_time'] ?></div>
            </div>
            <div class="terminus">
                <div class="tt">
                    <span class="icon">终</span>
                    <span class="text"><?php echo $v['end_station']; ?></span>
                </div>
                <div class="price">
                    已售 <span><?php echo $v['goods_salenum']; ?></span>
                </div>
            </div>
        </div>
        <div class="rt">
            <?php
$start_time = strtotime($v['date'] . " " . $v['departure_time']);
        ?>
            <?php if (TIMESTAMP > $start_time) {?>
                <button type="button" style="background-color: #807271">已发车</button>
            <?php } else {?>
                <button type="button" onclick="is_booking('<?php echo $v['goods_id']; ?>','<?php echo $v['date']; ?>')">预定</button>
            <?php }?>
        </div>
    </dd>
    <?php }?>
</dl>
<?php }?>
<style>
    body{background: #F4F4F4;}
</style>

<script>
      function  is_booking(goods_id,date){
          if(goods_id !='' && date!='' ){
              $.getJSON("index.php?act=station&op=BookingTickets",{'goods_id':goods_id, 'date':date},function(result){
                  if(result.status==1){
                      window.location.href=result.url;
                  }else{
                      alert(result.msg)
                  }
              })
          }
      }

    //购票须知
    function showPop(){
        $('.shade,.showPop').fadeIn()
    }
    $('.shade').click(function(){
        $('.shade,.showPop').fadeOut()
    })
</script>

