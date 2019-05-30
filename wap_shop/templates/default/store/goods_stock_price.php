<div class="div_tdbg01"><!--内容 home--->
    <!-- --------------------- -->

    <div class="div1" id="month_menu01"><ul>
            <li class="left"><span onclick="return next_month()" class="bt_pre"><?php echo intval(substr($output['rl']['next_month'], -2))?>月</span></li>
            <li class="center">
                <span><?php echo $output['rl']['date_str']?></span></li><li class="right"><span onclick="return pre_month()" class="bt_next"><?php echo intval(substr($output['rl']['pre_month'],-2))?>月</span></li></ul></div>

    <!-- ------------ -->
    <form name="form2" action="<?php echo urlShop('store_goods_stock','edit',array('commonid'=>$_GET["commonid"]))?>" method="post">
        <input type="hidden" name="tb_date" value="<?php echo $output['rl']['date']?>"/>
        <input type="hidden" name="tb_package" value="<?php echo $output['rl']['package']?>"/>
        <style>
            .td_week{
                width: 80px;display: inline-block;
                border:solid 1px #ccc;margin-left: 3px;margin-bottom: 3px;text-align: center;
            }
            .cal_tt {
                width: 75%;
                height: 30px;
                overflow: hidden;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-box;
                display: -webkit-flex;
                display: flex;
                position: fixed;
                top: 40px;
                left: 0;
                z-index: 99;
                background: #fff;
            }
            .cal_tt p{
                float: left;
                width: 30px;
                height: 30px;
                line-height: 30px;
                text-align: center;
                font-family: "Microsoft Yahei";
                -webkit-box-flex: 1;
                -moz-box-flex: 1;
                -ms-flex: 1;
                -webkit-flex: 1;
                flex: 1;
            }
        </style>

        <div class="cal_tt">
            <?php  foreach($output['th_array'] as $v){
                echo "<p class=''>{$v}</p>";
            } ?>

        </div>

        <div id="date_menu01">
            <ul>

                <?php foreach($output['first_array'] as $key=>$val) {//这一层是一行?>

                    <?php foreach ($val as $key=> $value) {
                        ?>
                        <li <?php echo $stock_class?>>
                            <a id='<?php echo "p".$value["day"]?>' href='<?php echo "{$output["rl"]['url']}&daysid={$value["day"]}"?>'>
                                <span class="days"><?php echo $value["day"];?></span>号<br/>
                                <font <?php if(empty($value["stock"])) echo "color='#ccc'"?>>库存:</font>
                                <?php  echo $value["stock"]?><br/>
                                <font <?php if(empty($value["man_price"])) echo "color='#ccc'"?>>成人:</font>
                                <?php  echo $value['man_price']?><br/>
                                <font <?php if(empty($value["child_price"])) echo "color='#ccc'"?>>儿童:</font>
                                <?php  echo $value["child_price"];?>
                                <br/><input type="checkbox" name="chk_sel[]" value="<?php echo $value["day"]?>">
                            </a>
                        </li>
                    <?php }
                    echo "<br/ style='clear:both'>";
                }
                ?>
            </ul>
        </div>


<script>
    var curent_date='<?php echo str_replace('-', '', $output['rl']['date'])?>';
    function next_month(){
        var url='<?php echo $output["rl"]['url']."&date={$output["rl"]['next_month']}"?>';

        var next_month='<?php echo date("Ym")?>';
        if(parseInt(curent_date)>parseInt(next_month)){
            window.location=url;
        }else{
            return(false);
        }
        return true;
    }
    function pre_month(){
        var url='<?php echo $output["rl"]['url']."&date={$output["rl"]['pre_month']}";?>';
        alert(url);
        var pre_month='<?php echo date("Ym",strtotime(date('Ym')." +5 month"))?>';
        if(parseInt(curent_date)< parseInt(pre_month)){
            window.location=url;
        }else{
            return(false);
        }
        return true;
    }
</script>

