<?php if(!$_GET['full']):?><body class="pd_wrap"><div id="container"><?php endif;?>
<style type="text/css">
#cal_body_form .ceil input {width:0}
.calendar {margin-top: 40px;}
</style>
<div class="hotel">
    <span class="arrow" style="font-family: Arial;line-height: 30px; font-size: 26px; font-weight: 300" onclick="$('#cal_cell').remove()">×</span>
    <div>请选择出行时间</div>
</div>

<div class="cal_tt">
    <?php  foreach($output['th_array'] as $v){ ?>
        <p class=<?php if($v=='日' || $v=='六') echo 'red'?>><?php echo $v ?></p>
    <?php } ?>
</div>

<div class="calendar">
    <div class="div1" id="month_menu01">
        <ul>
            <li class="left"><span onclick="return next_month()" class="bt_pre"><</span></li>
            <li class="center"><span><?php echo $output['rl']['date_str']?></span></li>
            <li class="right"><span onclick="return pre_month()" class="bt_next">></li>
        </ul>
    </div>
    <ul class="cal_body">
        <form id="cal_body_form" method="post">
            <input type="hidden" class="package" name="package_input" value="<?php echo $output["rl"]["package"] ?>" />
            <?php foreach($output['first_array'] as $key=>$val) {//这一层是一行,注意这里是从0开始循环?>
                <?php  if($key < $output['rowspan']){ ?>
                    <li>
                        <?php foreach ($val as $key=> $value) {
                            ?>
                            <p class='<?php if($_GET['is_cx']!=1 && (!intval($value['child_stock']) && $value['no_use'] !=1 && !intval($value['man_stock']) && $value['no_use'] !=1) || (strtotime($value['day']) < strtotime(date('Y-m-d')) && $value['no_use'] !=1) ){echo "ceil off";}else{echo "ceil";}?>'>
                                <span class="day" ><?php echo substr($value["day"],-2);?></span>
                                <em class="pirece">
                                   <?php if($_GET['is_cx'] and $value['xs_stock'] > 0){ ?>
                                      <?php echo "￥".$value['xs_price']."起"; ?>
                                   <?php }elseif(($value['man_price'] && intval($value['man_stock']))){
                                        echo "￥".$value['man_price']."起";
                                    } elseif(empty(intval($value['man_stock'])) && !empty(intval($value['child_stock']))){
                                        echo "￥".$value['child_price']."起";
                                    }else{
                                        echo $value['man_stock'];
                                    }?>
                                </em>
                                <input type="checkbox" name="child_pirece[]" value="<?php if(($value['child_price'] && intval($value['child_stock']))){ echo $value['child_price'];}else{echo $value['child_stock'];}?>" />

                                <input type="checkbox" name="man_pirece[]"   value="<?php if(($value['man_price'] &&  intval($value['man_stock']))){ echo $value['man_price'];}else{echo $value['man_stock'];}?>" />
                                <input type="checkbox" name="diff_price[]"   value="<?php echo $value['diff_price'] ?>" />
                                <input type="checkbox" name="xs_price[]" value="<?php echo $value['xs_price'];?>"/>
                                <input type="checkbox" name="day[]" value="<?php echo $value['day']?>">
                            </p>
                        <?php }?>
                    </li>
                <?php } ?>
            <?php }?>
            <input class="subDays" type="button" value="提交日期" />
        </form>
    </ul>
</div>

<!--酒店-->
<script>

    $(function(){
        $('#container').delegate('.calendar .ceil', 'click', function(){
            if($(this).find('.day').text()=="" || $(this).hasClass('off') || $(this).find('.pirece').text()=="") return;
            if(!$(this).hasClass('on')){
               $('.ceil.on').removeClass('on').find('input').removeProp('checked');
               $(this).addClass('on').find('input').prop('checked', true);
            }
        });

        $('#container').delegate('.subDays', 'click', function(){
            var formObj = $('#cal_body_form');
            var dayCheck = formObj.find('input[name="day[]"]:checked').length;
            if(dayCheck == 1) {
               var url =  '<?php echo  urlWapShop('goods_stock_price', 'getData');?>';
                $.post(url,formObj.serialize(), function (result) {
                    if(result){
                        $('#cal_cell').remove();
                        window.parent.putong_x(result);
                    }
                });
            }else {
               layer.msg('请确定出行日期!');
            }

        })
    });
    var curent_date='<?php echo str_replace('-', '', $output['rl']['date'])?>';
    function next_month(){//上一月
        var url='<?php echo $output["rl"]['url']."&date={$output["rl"]['next_month']}"."&package={$output["rl"]["package"]}"."&is_cx={$output['is_cx']}";?>';
        var next_month='<?php echo date("Ym")?>';
        if(parseInt(curent_date)>parseInt(next_month)){
            $.get(url,{full: 1}, function(res){
               $('#container').html(res);
            })
        }
    }
    function pre_month(){//下个月
        var url='<?php echo $output["rl"]['url']."&date={$output["rl"]['pre_month']}"."&package={$output["rl"]["package"]}"."&is_cx={$output['is_cx']}";?>';
        var pre_month='<?php echo date("Ym",strtotime(date('Ym')." + {$output['count_stock']}  month"))?>';
        if(parseInt(curent_date)< parseInt(pre_month)){
           $.get(url,{full: 1}, function(res){
             $('#container').html(res);
          });
        }
    }
</script>
<?php if(!$_GET['full']):?></div></body><?php endif;?>
