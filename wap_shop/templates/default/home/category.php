    <ul>
    <li><span></span><a href="<?php echo urlShopWAP('search','product_list');?>">全部商品</a></li>
    <?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])) { $i = 0; ?>
        <?php foreach ($output['show_goods_class'] as $key => $val) { ?>
            <li>
            <div class="title">
                <a href="<?php echo $val['gc_id']==1 ? '#':urlShopWAP('search','product_list',array('cate_id'=>$val['gc_id']));?>">
                    <?php echo $val['gc_name']; ?>
                </a>
            </div><i></i>
            <?php if(!empty($val['class2'])) {?>
            <ul class="twonav">
            <?php foreach($val['class2'] as $k2=>$v2) { ?>
                <li><div class="title"><a href="<?php echo menu($v2['gc_name'],$v2['gc_id']);?>"><?php echo $v2['gc_name'];?></a></div><i></i>
                <?php if(!empty($v2['class3'])) {?>
                <ul class="threenav">
                <?php foreach($v2['class3'] as $k3=>$v3) { ?>
                      <li>
                      <a href="<?php echo menu_1($v2['gc_name'],$v3['gc_id']) ;?>"><?php echo $v3['gc_name'];?></a>
                      </li>
                <?php } ?>
                </ul>
                <?php }?>
                </li>
            <?php } ?>
            </ul>
            <?php } ?>
            </li>
        <?php } ?>
    <?php } ?>
    </ul>