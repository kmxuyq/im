<div class="verification_title">
    <span class="tt"><a href="/wap_shop/index.php?act=checkoff&amp;op=index">返回核销</a></span>
    <p class="lt">
        <?php echo $output['member_name']; ?>，你好
        <a href="/wap_shop/index.php?act=checkoff&amp;op=logout">退出</a>
    </p>
</div>
<h2 class="verification_tt" style="padding-top: 15px">
   <a href="/wap_shop/index.php?act=checkoff&amp;op=index">核销兑换码</a>
</h2>
<div class="verification_history">
   <?php if(!empty($output['list']) and is_array($output['list'])): foreach($output['list'] as $key => $val):?>
    <div class="item">
        <p>商品：<?php echo $val['goods_name'];?></p>
        <?php if(!empty($val['order_sn'])): ?>
        <p>订单号：<?php echo $val['order_sn'];?></p>
        <?php endif; ?>
        <p>一维码：<?php echo $val['code'];?></p>
        <p>核销时间：<?php echo date('Y-m-d H:i:s', $val['addtime']);?></p>
        <p>核销人：<?php echo $val['member_name'];?></p>
    </div>
<?php endforeach; endif;?>
</div>
<style>
    body{background: #f5f5f5}
</style>
