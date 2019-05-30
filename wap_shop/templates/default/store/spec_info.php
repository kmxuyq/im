
<?php if(!empty($output['spec_arr'])){ $i=1;?>
    <?php foreach ($output['spec_arr'] as $key => $val) {?>
        <?php  if($i<=1){ ?>
            <div class="detail_hotal_choose <?php echo "btn";?>">
                <span class="t1">房间类型</span>
                <span class="t2"><a><?php echo $val['package'];?></a>/每晚 <i>￥<?php echo $val['man_price']; ?></i></span>
                <input type="hidden" class="checked_input_name" value="<?php echo $val['package'];?>">
                <input type="hidden" class="checked_input_price" value="<?php echo $val['man_price']; ?>">
                <input type="hidden" class="hd_price" value="<?php echo $val['man_price']; ?>">
            </div>
        <?php } ?>
        <?php $i++ ?>
    <?php }?>
<?php  }else{ ?>
    <div>暂时没有信息</div>
<?php } ?>


<div class="hotal_list_choose">
    <div class="tt">选择房间</div>
    <ul class="hotal_list">
        <?php if(!empty($output['spec_arr'])){?>
            <?php foreach ($output['spec_arr'] as $key => $val) {?>
                <li>
                    <span class="name"><?php echo $val['package'];?>/每日</span>
                    <span class="price">￥<?php echo $val['man_price']; ?></span>
                    <input type="hidden" class="hd_name" value="<?php echo $val['package'];?>">
                    <input type="hidden" class="hd_price" value="<?php echo $val['man_price']; ?>">
                </li>
            <?php }?>
        <?php }?>
    </ul>
</div>
<div class="block_mak"></div>

<style>
.block_mak{ position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); z-index: 999; display: none}
.hotal_list_choose{ position: fixed; padding-bottom: 10px; border-radius:10px; width: 300px; background: #fff; z-index: 9999; left: 50%; top: 20%; display: none; margin:0 0 0 -150px }
.hotal_list_choose .tt{ font-size: 16px; font-family: "Microsoft YaHei"; padding-top: 6px; height: 40px; line-height: 40px; color: #333; text-align: center;}
.hotal_list_choose .hotal_list{overflow: hidden}
.hotal_list_choose .hotal_list li{ height: 40px; line-height: 40px; text-align: center; font-family: "Microsoft YaHei"; color: #3b84ed; font-size: 14px;}
.hotal_list_choose .hotal_list li .price{color: #f60}
</style>

<script>
    $(function(){
        var   _price=$(".hotal_list").find('.hd_price').val();
        var   _input_hotel_num =$(".input_hotel_num").val();//数量
        //初始化值
    	if( typeof($(".hotal_list").find('.hd_price')) == 'undefined'  ){
    		$("#ss_price").text(0);
        }else{
        	$("#ss_price").text(_price*_input_hotel_num);
        }
        //$("#ss_price").text(_price);
        $('.block_mak').click(function(){
            $('.block_mak,.hotal_list_choose').fadeOut('fast')
        });

        $('.detail_hotal_choose.btn').click(function(){
            $('.block_mak,.hotal_list_choose').fadeIn('fast')
        });

        $('.hotal_list li').click(function(){
            var   _name=$(this).find('.hd_name').val();
            var   _price=$(this).find('.hd_price').val();
            $(".checked_input_name").val(_name);
            $(".checked_input_price").val(_price);
            $(".detail_hotal_choose.btn").find(".t2 a").text(_name);
            $(".detail_hotal_choose.btn").find(".t2 i").text("￥"+_price);
            $("#ss_price").text(_price*_input_hotel_num);
            setTimeout(function(){
                $('.block_mak,.hotal_list_choose').fadeOut('fast')
            },300)
        })
    })
</script>