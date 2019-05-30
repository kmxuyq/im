<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <div class="text-intro"> <?php echo $lang['store_activity_theme'].$lang['nc_colon'];//'活动主题';?> <?php echo $output['activity_info']['activity_title'];?></div>
</div>
<form method="GET">
  <input type="hidden" name="act" value="store"/>
  <input type="hidden" name="op" value="activity_apply"/>
  <input type="hidden" value="<?php echo intval($_GET['activity_id']);?>" name="activity_id"/>
  <table class="ncsc-default-table" >
    <thead>
      <tr>
        <th class="w50"></th>
        <th class="w300 tl"><?php echo $lang['store_activity_goods_name'];?></th>
        <th>售价</th>
        <th>抽奖活动中用户奖品描述<br/>"1：一等奖,2:二等奖 3：三等奖 ..依次类推"</th>
        <th class="w120"><?php echo $lang['store_activity_confirmstatus']; //审核状态?></th>
        <th class="w120"><?php echo $lang['store_order_handle_desc']; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['list']) and is_array($output['list'])){?>
      <?php foreach ($output['list'] as $k=>$v){ ?>
      <tr class="bd-line">
        <td><div class="pic-thumb">
            <a href="index.php?act=goods&goods_id=<?php echo $v['goods_id']; ?>" target="_blank">
              <img src="<?php echo cthumb($v['goods_image'], 60,$_SESSION['store_id']);?>"></a>
            </div>
        </td>
        <td class="tl"><dl class="goods-name">
            <dt><a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id']));?>"><?php echo $v['goods_name'];?></a></dt>
            <dd><?php echo $v['gc_name'];?></dd>
            </dl>
        </td>
        <td>￥<?php echo $v['goods_price'];?></td>
        <td><input type ="text" class="text w20" name="activity_detail_sort" alt="<?php echo $v['activity_detail_id']; ?>" title="<?php echo $v['activity_detail_sort'];?>" value="<?php echo $v['activity_detail_sort'];?>" /></td>
        <td><?php if($v['activity_detail_state']=='1'){
          			echo $lang['store_activity_pass'];
          		  }elseif(in_array($v['activity_detail_state'],array('0','3'))){
          		  	echo $lang['store_activity_audit'];
          		  }
          	?></td>
        <td class="nscs-table-handle">
            <span><a href="index.php?act=store_activity&op=activity_detail_del&activity_detail_id=<?php echo $v['activity_detail_id'];?>&activity_id=<?php echo $v['activity_id'];?>"  class="btn-green"><i class="icon-edit"></i>
        			<p><?php echo $lang['store_watermark_del'];?></p>
        		  </a>
        	</span>
        </td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="20"></td>
      </tr>
    </tfoot>
  </table>
</form>

  <form method="POST" id="rule_form"  action="index.php?act=store_activity&op=activity_rule_save">
      <input type="hidden" name="activity_id"   value="<?php echo $_GET['activity_id'];?>"/>
      <input type="hidden" name="activity_type" value="<?php echo $output['activity_info']['activity_type'];?>" />
      <input type="hidden" name="is_check"      value="<?php echo empty($output['activity_rule']['is_check'])?0:1;?>" />
      <input type="hidden" name="is_collect"    value="<?php echo empty($output['activity_rule']['is_collect'])?0:1;?>" />
      <input type="hidden" name="is_buy"        value="<?php echo empty($output['activity_rule']['is_buy'])?0:1;?>" />         
  <div class="ncsc-form-goods" <?php if(!in_array($output['activity_info']['activity_type'],array(3,'3'))){?>style="display:none;"<?php }?>>
     <h3 id="demo3">编写规则</h3>
     <dl class="special-01" >
          <dt><?php echo $lang['store_activity_rule_time'];?></dt>
          <dd>
           <ul>
             <li>
	         <input type="text" name="activity_time" id="dateinfo"
	             class="w130 text" value="<?php echo empty($output['activity_rule']['activity_time'])?date('Y-m-d H:i:s',TIMESTAMP):date('Y-m-d H:i:s',$output['activity_rule']['activity_time']); ?>" />
	         <em class="add-on"><i class="icon-calendar"></i></em>
	         <span></span>
	         </li>
	        </ul>
	         <p class="hint vital">*抽奖开始之前用户不能抽奖。</p>
         </dd>
      </dl>
      <dl class="special-01" >
          <dt><?php echo $lang['store_activity_intro'];?></dt>
          <dd>
           <ul>
             <li>
             <textarea rows=""  cols="" name="activity_desc" ><?php echo $output['activity_rule']['activity_desc'];?></textarea>
	         <span></span>
	         </li>
	        </ul>
	         <p class="hint vital">*活动的描述。</p>
         </dd>
      </dl>
     <dl class="special-01">
        <dt><?php echo $lang['store_activity_rule_check'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <input type="radio" name="is_check" value="1" id="is_check_1"
                  <?php if ($output['activity_rule']['is_check'] == 1) {?>checked<?php }?>>
              <label for="is_gv_1">是</label>
            </li>
            <li>
              <input type="radio" name="is_check" value="0" id="is_check_0" 
                 <?php if ($output['activity_rule']['is_check'] == 0) {?>checked<?php }?>>
              <label for="is_gv_0">否</label>
            </li>
          </ul>
          <p class="hint vital">*如果不校验，那么只要报名的人员均可参与抽奖。</p>
        </dd>
      </dl>
      <dl class="special-01" nctype="virtual_valid" <?php if ($output['activity_rule']['is_check'] == 0) {?>style="display:none;"<?php }?>>
        <dt><?php echo $lang['store_activity_rule_collect'];?></dt>
        <dd>
        <ul class="ncsc-form-radio-list">
            <li>
              <input type="radio" name="is_collect" value="1"  id="is_collect_1"
                  <?php if ($output['activity_rule']['is_collect'] == 1) {?>checked<?php }?>>
              <label for="is_gv_1">是</label>
            </li>
            <li>
              <input type="radio" name="is_collect" value="0" id="is_collect_0" 
                 <?php if ($output['activity_rule']['is_collect'] == 0) {?>checked<?php }?>>
              <label for="is_gv_0">否</label>
            </li>
          </ul>
         </dd>
      </dl>
      <dl class="special-01" nctype="virtual_valid" <?php if ($output['activity_rule']['is_check'] == 0) {?>style="display:none;"<?php }?>>
          <dt><?php echo $lang['store_activity_rule_buy'];?></dt>
          <dd>
	        <ul class="ncsc-form-radio-list">
	            <li>
	              <input type="radio" name="is_buy" value="1" id="is_buy_1" 
	                  <?php if ($output['activity_rule']['is_buy'] == 1) {?>checked<?php }?>>
	              <label for="is_gv_1">是</label>
	            </li>
	            <li>
	              <input type="radio" name="is_buy" value="0" id="is_buy_0" 
	                 <?php if ($output['activity_rule']['is_buy'] == 0) {?>checked<?php }?>>
	              <label for="is_gv_0">否</label>
	            </li>
	          </ul>
	           <p class="hint vital"></p>
         </dd>
      </dl>
  </div>
   <div class="bottom tc p10">
        <input type="submit" class="submit" style="display: inline; *display: inline; zoom: 1;" value="<?php echo $lang['store_goods_class_submit'];?>"/>
   </div>
  </form>
  
  <div class="div-goods-select">
    <form method="GET">
      <input type="hidden" name="act" value="store_activity"/>
      <input type="hidden" name="op" value="activity_apply"/>
      <input type="hidden" name="activity_id" value="<?php echo $_GET['activity_id'];?>"/>
      <table class="search-form">
        <tr>
          <th class="w250"><strong>选择参加活动的商品，勾选并提交平台审核</strong></th>
          <td class="w160"><input type="text" class="text w150" name="name" value="<?php echo $output['search']['name'];?>" placeholder="搜索商品名称"/></td>
          <td class="w70 tc"><label class="submit-border">
              <input type="submit" class="submit" value="<?php echo $lang['store_activity_search'];?>"/>
            </label></td><td></td>
        </tr>
      </table>
    </form>
    <form method="POST" id="apply_form" action="index.php?act=store_activity&op=activity_apply_save">
      <input type="hidden" name="activity_id"  value="<?php echo $_GET['activity_id'];?>" />
      <?php if(!empty($output['goods_list']) and is_array($output['goods_list'])){?>
      <div class="search-result">
        <ul class="goods-list">
          <?php foreach ($output['goods_list'] as $goods){?>
          <li>
            <div class="goods-thumb"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods['goods_id']));?>" target="_blank"><img alt="<?php echo $goods['goods_name'];?>" title="<?php echo $goods['goods_name'];?>" src="<?php echo cthumb($goods['goods_image'], 240, $_SESSION['store_id']);?>"/></a></div>
            <dl class="goods-info">
              <dt>
                <input type="checkbox" value="<?php echo $goods['goods_id'];?>" class="vm" name="item_id[]"/>
                <label><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods['goods_id']));?>" target="_blank"><?php echo $goods['goods_name'];?></a></label>
              </dt>
              <dd>销售价格：￥<?php echo $goods['goods_price'];?></dd>
            </dl>
          </li>
          <?php }?>
          <div class="clear"></div>
        </ul>
      </div>
      <div class="pagination"><?php echo $output['show_page'];?></div>
      <?php }else{?>
      <div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];//您尚未发布任何商品?></span></div>
      <?php }?>
      <?php if(!empty($output['goods_list']) and is_array($output['goods_list'])){?>
      <div class="bottom tc p10">
        <input type="submit" class="submit" style="display: inline; *display: inline; zoom: 1;" value="<?php echo $lang['store_activity_join_now'];?>"/>
      </div>
      <?php }?>
    </form>
  </div>
  <script type="text/javascript">
  $(document).ready(function(){
       //初始化日历信息
       jeDate.skin('gray');
       jeDate({
       dateCell:"#dateinfo",
       format:"YYYY-MM-DD hh:mm:ss",
       isinitVal:true,
       isTime:true, //isClear:false,
       minDate:"2014-09-19 00:00:00",
       okfun:function(val){}
       });
	    //描述
	    $('input[name="activity_detail_sort"]').change(function(){
	    	var detailmodel = $(this);
	    	$.post("index.php?act=store_activity&op=activity_detail_save", 
	    	       {activity_detail_id    :detailmodel.attr('alt'),
	    		    activity_detail_sort  :detailmodel.val()},
	    	   function(data) {
	            if(data.state) {
	            } else {
		            //展示错误
	                showError(data.msg);
	                //还原
	               detailmodel.val(detailmodel.attr('title')); 
	            }
	        },'json');
		});
  });
  </script>