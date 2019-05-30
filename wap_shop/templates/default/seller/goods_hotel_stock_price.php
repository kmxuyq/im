<div class="hotel"> <span class="arrow" style="font-family: Arial;line-height: 30px; font-size: 26px; font-weight: 300" onclick="$('#cal_cell').remove()">×</span><div>请选择住宿时间</div>
</div><div class="cal_tt"><?php  foreach($output['th_array'] as $v){ ?><p class=<?php if($v=='日' || $v=='六') echo 'red'?>><?php echo $v ?></p><?php } ?></div>
<?php foreach($output['first_array'] as $a =>$b){ ?>
<form id="cal_body_form" method="post"><div class="calendar" style="margin-top: 40px;"><div class="div1" id="month_menu01"><ul><li class="center" style="width: 100%;"><?php echo str_replace("-","年",$a)."月";?></li></ul></div>
<ul class="cal_body">
<?php foreach($b as $key=>$val) {?>
<?php  if( $key < $output['rowspan'][$a]){ ?><li>
<?php foreach ($val as $key=> $value) {?>

<p class='<?php if( $_GET['is_cx'] !=1 && $value['no_use'] !=1 and (strtotime($value['day']) < strtotime(date("Y-m-d",TIMESTAMP)) or !intval($value['stock'])) ){echo "ceil off";}else{echo "ceil";}?>'>

    <input type="checkbox" style="display: none" class="price_val" name="price[]" value="<?php if($_GET['is_cx']==1 and $value['xs_stock'] > 0){echo $value['xs_price'];}elseif($value['man_price'] && intval($value['stock'])){echo $value['man_price'];}else{echo $value['stock'];}?>" />

    <span class="day" ><?php echo substr($value["day"],-2);?></span>

<em class="pirece"><?php if($_GET['is_cx']==1 and $value['xs_stock'] > 0){echo "￥".$value['xs_price'];}elseif(($value['man_price'] && intval($value['stock']))){ echo "￥".$value['man_price'];}else{echo $value['stock'];}?></em>

    <input type="checkbox" class="day_val" name="day[]" style="display: none" value="<?php echo $value['day']?>">
<input type="checkbox" class="stock_val" name="stock[]" style="display: none" value="<?php echo $value['stock']?>">
</p>
<?php }?>
</li>
<?php }}?>
</ul>
</div>
<input class="subDays" type="button" value="提交日期" />
</form>
<?php } ?>
<!--酒店-->
<script>
    $(function(){ var days=$('.calendar .ceil'), click_list=[]; days.click(function(){ if( $(this).find('.day').text()=="" || $(this).hasClass('off') || $(this).find('.pirece').text()=="" ) { return; } if($(this).hasClass('on')){ $(this).removeClass('on').find('input').removeAttr('checked'); }else{ $(this).addClass('on').find('input').addClass('checked'); } if($('.calendar .ceil.on').length>2){ var o = click_list.pop(); o.removeClass('on').find('input').removeAttr('checked'); } click_list.push($(this)); }); $('.subDays').click(function(){var selectedDaysObj = $('.calendar .ceil.on'); var dayCheck = selectedDaysObj.length; if(dayCheck ==2) {var data = {price:[], day:[], stock:[] }; $(selectedDaysObj).each(function(i, el){data.price.push($(el).find('.price_val').val()); data.day.push($(el).find('.day_val').val()); data.stock.push($(el).find('.stock_val').val()); }); hotel_x(data); $('#cal_cell').remove(); }else {layer.msg('请确定离店时间!'); } }); });
</script>
