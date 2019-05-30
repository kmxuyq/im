

<body class="pd_wrap">
<div class="hotel">
    <span class="arrow" style="font-family: Arial;line-height: 30px; font-size: 26px; font-weight: 300" onclick="$('#cal_cell').remove()">×</span>
    <div>请选择预定日期</div>
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
            <?php foreach($output['first_array'] as $key=>$val) {//这一层是一行,注意这里是从0开始循环?>
                    <li>
                        <?php foreach ($val as $key=> $value) {?>
                            <p class= '<?php if($value['no_use'] || (strtotime($value['day']) < strtotime(date('Y-m-d')))){echo "off";}else{echo "ceil";} ?>'>
                                <span class="perice" style='color:<?php if(strtotime($value['day']) < strtotime(date('Y-m-d'))){echo "#BBBBBB";}else{echo "#3b84ed";} ?> ;'><?php echo $value["date"];?></span>
                                <input type="checkbox"  name="day[]" style="display: none" value="<?php echo $value['day']?>">
                            </p>
                        <?php }?>
                    </li>
            <?php }?>
            <input class="subDays" type="button" value="提交日期" />
        </form>
    </ul>
</div>
</body>
<!--酒店-->
<script>

    $(function(){
        var days=$('.calendar .cal_body .ceil'),click_list=[];
        days.click(function(){
            if($(this).hasClass('on')){
                $(this).removeClass('on').find('input').removeAttr('checked');
            }else{
                $(this).addClass('on').find('input').attr('checked','checked');
            }
            if($('.calendar .cal_body .on').length >1){
                var o =click_list.pop();
                o.removeClass('on').find('input').removeAttr('checked');
            }
            click_list.push($(this));
        });


        $('.subDays').click(function(){
            var formObj = $('#cal_body_form');
            var dayCheck = formObj.find('input[name="day[]"]:checked').length;
            if(dayCheck == 1) {
                var url =  '<?php echo  urlWapShop('goods_stock_price', 'getData');?>';
                $.post(url,formObj.serialize(), function (result) {
                    if(result){
                        $('#cal_cell').remove();
                        window.parent.golf_x(result);//成功则调用上一层的hotel_x方法

                        /*var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                        parent.layer.close(index);*/
                    }
                });
            }else {
                layer.msg('请确定出行日期!');
            }

        })
    });
</script>
<script>
    var curent_date='<?php echo str_replace('-', '', $output['rl']['date'])?>';
    function next_month(){//上一月
        var url='<?php echo $output["rl"]['url']."&date={$output["rl"]['next_month']}"?>';

        var next_month='<?php echo date("Ym")?>';
        if(parseInt(curent_date)>parseInt(next_month)){
            window.location=url;
        }else{
            return(false);
        }
        return true;
    }
    function pre_month(){//下个月
        var url='<?php echo $output["rl"]['url']."&date={$output["rl"]['pre_month']}";?>';
        var pre_month='<?php echo date("Ym",strtotime(date('Ym')." + 5 month"))?>';
        if(parseInt(curent_date)< parseInt(pre_month)){
            window.location=url;
        }else{
            return(false);
        }
        return true;
    }
</script>
<style>
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