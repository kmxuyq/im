<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<style>
.submit{
	  display: block;
  border: 1px solid #10b0e6;
  border-radius: 6px;
  line-height: 30px;
  text-align: center;
  width: 120px;
  position: relative;
  margin: 5px 0;
}
.nav_touch{
	display:none;
}
</style>
 
<div class="wrap" style="margin-left:75px;">
  <form action="index.php?act=member_information&op=cut" id="form_cut" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" id="x1" name="x1" />
    <input type="hidden" id="x2" name="x2" />
    <input type="hidden" id="w" name="w" />
    <input type="hidden" id="y1" name="y1" />
    <input type="hidden" id="y2" name="y2" />
    <input type="hidden" id="h" name="h" />
    <input type="hidden" id="newfile" name="newfile" value="<?php echo $output['newfile'];?>" />
    <div class="pic-cut-120">
      <div class="work-layer">
        <p><img  id="nccropbox" src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.$output['newfile'].'?'.microtime(); ?>"/></p>
		<p style="text-align:center"><input type="button" id="ncsubmit" class="submit" value="<?php echo $lang['nc_submit'];?>" /></p>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	function showPreview(coords)
	{
		if (parseInt(coords.w) > 0){
			var rx = 120 / coords.w;
			var ry = 120 / coords.h;
			$('#preview').css({
				width: Math.round(rx * <?php echo $output['width'];?>) + 'px',
				height: Math.round(ry * <?php echo $output['height'];?>) + 'px',
				marginLeft: '-' + Math.round(rx * coords.x) + 'px',
				marginTop: '-' + Math.round(ry * coords.y) + 'px'
			});
		}
		$('#x1').val(coords.x);
		$('#y1').val(coords.y);
		$('#x2').val(coords.x2);
		$('#y2').val(coords.y2);
		$('#w').val(coords.w);
		$('#h').val(coords.h);
	}
    $('#nccropbox').Jcrop({
	aspectRatio:1,
	setSelect: [ 0, 0, 120, 120 ],
	minSize:[120, 120],
	allowSelect:0,
	onChange: showPreview,
	onSelect: showPreview
    });
	$('#ncsubmit').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("您必须先选定一个区域");
			return false;
		}else{
			$('#form_cut').submit();
		}
	});
});
</script>