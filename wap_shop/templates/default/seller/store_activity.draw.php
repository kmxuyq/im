<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<style type="text/css">
	.wrap{ width:300px; margin:100px auto; font-family:"微软雅黑";}
	.show{ width:300px; height:300px; background-color:#ff3300; line-height:300px; text-align:center; color:#fff; font-size:28px; -moz-border-radius:150px; -webkit-border-radius:150px; border-radius:150px; background-image: -webkit-gradient(linear,0% 0%, 0% 100%, from(#FF9600), to(#F84000), color-stop(0.5,#fb6c00)); -moz-box-shadow:2px 2px 10px #BBBBBB; -webkit-box-shadow:2px 2px 10px #BBBBBB; box-shadow:2px 2px 10px #BBBBBB;}
	.btn a{ display:block; width:120px; height:50px; margin:30px auto; text-align:center; line-height:50px; text-decoration:none; color:#fff; -moz-border-radius:25px; -webkit-border-radius:25px; border-radius:25px;}
	.btn a.start{ background:#80b600;}
	.btn a.start:hover{ background:#75a700;}
	.btn a.stop{ background:#00a2ff;}
	.btn a.stop:hover{ background:#008bdb;}
</style>

<div class="ncsc-form-default">
<dl>
  <dt><em class="pngFix"></em>本次抽的是:</dt>
  <dd><a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id' =>  $output['detail_info']['goods_id']));?>">
      <?php echo $output['detail_info']['goods_name'] ?>
      </a>
  </dd>
</dl>
<dl>
  <dt><em class="pngFix"></em>图片:</dt>
  <dd><img style="width:50px; height:50px;"src ="<?php echo cthumb($output['detail_info']['goods_image'], 60,$_SESSION['store_id']);?>"/></dd>
</dl>
<dl>
  <dt><em class="pngFix"></em>抽奖人昵称:</dt>
  <dd><span id="show1"></span><input type="hidden" name="person_id"  value="" /></dd>
</dl>
<dl>
  <dd> 
     <div class="show" >
	    <img id="imgId" style="width:300px; height:300px;
	          border-radius:50%; overflow:hidden;" src="" />
	 </div>
  </dd>
</dl>
<div class="bottom btn"> 
<a href="javascript:void(0);" class="submit start"  id="btn">开始抽奖</a> 
</div>
</div>
<script type="text/javascript">
$(function(){
	var personiddata  = new Array();
	var namedata      = new Array();
	var avaterdata    = new Array();
	<?php foreach ($output['personList']  as $k => $v ) {?>
	     personiddata[<?php echo $k ?>]  = <?php echo "\"". $v['person_id']."\"" ?> ;
		 namedata    [<?php echo $k ?>]  = <?php echo "\"". $v['member_name']."\"" ?> ;
		 avaterdata  [<?php echo $k ?>]  = <?php echo "\"". getMemberAvatar($v['member_avatar'])."\"" ?> ;
    <?php }?>
	var num = personiddata.length - 1;
	//头像
	var imgId = $("#imgId");
    //名字
	var show1 = $("#show1");
	//personid
	var personid = $("input[name='person_id']");
	var btn = $("#btn");
	var open = false;
	function change(){
		var randomVal  = Math.round(Math.random()*num);
		var allName    = personiddata[randomVal];
		var nameName   = namedata[randomVal];
		var imgName    = avaterdata[randomVal];
		imgId.attr('src',imgName);
		show1.text(nameName);
		personid.val(allName);
	}
	//开始跑
	function run(){
		if(!open){
			timer=setInterval(change,50);
			btn.removeClass('start').addClass('stop').text('停止');
			open = true;
		}else{
			clearInterval(timer);
			btn.removeClass('stop').addClass('start').text('完成抽奖');
			open = false;
			//ajax传递到后台更新状态
			$.post('index.php?act=store_activity&op=drawprizeSave',
			    {'person_id':$("input[name='person_id']").val(),
                 'detail_id':<?php echo $output['detail_info']['activity_detail_id']; ?>
			    }, 
		    function(data){
		    	if(data.state){
		    		//展示成功
	                alert(data.msg);  
		    	}else{
			    	//失败
		    		alert('失败,具体原因:'+data.msg);		      
			    }
		    	 btn.css('display','none');  
		    },'json');
		}
	}
	btn.click(function(){
		run();
	});
})
</script>