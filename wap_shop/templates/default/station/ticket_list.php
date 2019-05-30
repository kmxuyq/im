<div class="train_position_top">
    <div class="arrow" onclick="history.go(-1);"></div>
    <div class="text">车票</div>
</div>
<dl class="train_list top">
<?php if(!empty($output['data'])){ ?>
	<?php foreach($output['data'] as $v){ ?>
    <dd class="rows">
        <div class="lt">
            <div class="start_block">
                <div class="tt">
                    <span class="icon">始</span>
                    <span class="text"><?php echo $v['start_station'];?></span>
                </div>
                <div class="price">
                    ￥<?php echo $v['goods_price'];?>
                </div>
            </div>
            <div class="start_time">
                <div class="time"><?php echo $v['date'];?></div>
                <div class="text">发车时间:<?php echo $v['departure_time']?></div>
            </div>
            <div class="terminus">
                <div class="tt">
                    <span class="icon">终</span>
                    <span class="text"><?php echo $v['end_station'];?></span>
                </div>
                <div class="price">
                    已售 <span><?php echo $v['goods_salenum'];?></span>
                </div>
            </div>
        </div>
        <div class="rt">
            <?php
                $start_time = strtotime($v['date']." ".$v['departure_time']);
            ?>
            <?php if(TIMESTAMP > $start_time){ ?>
                <button type="button" style="background-color: #807271">已发车</button>
            <?php }else{?>
                <button type="button" onclick="is_booking('<?php echo $v['goods_id'];?>','<?php echo $v['date'];?>')">预定</button>
            <?php } ?>
        </div>
    </dd>
	<?php } ?>
<?php }else{ ?>
   <dd class="rows">
		找不到相关数据！
   </dd>
<?php } ?>
</dl>
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
</script>

