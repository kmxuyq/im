<?php
//$imagetool=new imagetool();
//$imageresize=new ImageResize();
//echo $imagetool->water('2.jpg','water.png',null,'2',25);
//echo $imagetool->water('8.jpg','water.png','home11.jpg','2',30);
class imagetool {
	/**
	 * print_r(ImageTool::imageInfo('11.jpg'));//显示图片信息
	 * return array()
	 */
	public static function imageInfo($image) {
		// 判断图片是否存在
		if (!file_exists($image)) {
			return false;
		}	
		$info = getimagesize($image);
	
		if ($info == false) {
			return false;
		}	
		// 此时info分析出来,是一个数组
		$img['width'] = $info[0];
		$img['height'] = $info[1];
		$img['ext'] = substr($info['mime'], strpos($info['mime'], '/') + 1);
	
		return $img;
	}
	/**
	 * png 24位水印图片,
	 * @param unknown_type $img 图片
	 * @param unknown_type $water  水印图片
	 */
	function water($img,$water){
		//原始图像
		$dst = $img;//"12.jpg"; //注意图片路径要正确
		//得到原始图片信息
		$dst_info = getimagesize($dst);
		$imgquality = 100; //图片质量0-100，值最大图片质量愈好
		switch ($dst_info[2])
		{
			case 1:
				$dst_im =imagecreatefromgif($dst);break;
			case 2:
				$dst_im =imagecreatefromjpeg($dst);break;
			case 3:
				$dst_im =imagecreatefrompng($dst);break;
			case 6:
				$dst_im =imagecreatefromwbmp($dst);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		//水印图像
		$src = $water;//"water2.png"; //注意路径要写对
		$src_info = getimagesize($src);
		switch ($src_info[2])
		{
			case 1:
				$src_im =imagecreatefromgif($src);break;
			case 2:
				$src_im =imagecreatefromjpeg($src);break;
			case 3:
				$src_im =imagecreatefrompng($src);break;
			case 6:
				$src_im =imagecreatefromwbmp($src);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		if($dst_info[2]=='1'||$dst_info[2]=='2'||$dst_info[2]=='3'||$dst_info[2]=='6'){
			//半透明格式水印
			//$alpha = 50;//水印透明度
			//imagecopymerge($dst_im,$src_im,$dst_info[0]-$src_info[0]-10,$dst_info[1]-$src_info[1]-10,0,0,$src_info[0],$src_info[1],$alpha);
			//支持png本身透明度的方式$src_info[0]-2//2为水印图片边距
			imagecopy($dst_im,$src_im,$dst_info[0]-$src_info[0],$dst_info[1]-$src_info[1],0,0,$src_info[0],$src_info[1]);
			//保存图片
			switch ($dst_info[2]){
				case 1:
					imagegif($dst_im,$dst);break;
				case 2:
					imagejpeg($dst_im,$dst,$imgquality);break;
				case 3:
					imagepng($dst_im,$dst,$imgquality);break;
				case 6:
					imagewbmp($dst_im,$dst,$imgquality);break;
	
			}
			imagedestroy($dst_im);
			imagedestroy($src_im);
		}
	}
	/**
	 * png 24位水印图片,
	 * @param unknown_type $img 图片
	 * @param unknown_type $water  水印图片
	 * @param unknown_type $num  水印图片数量1/2
	 */
	function water_center($img,$water,$num){
		//原始图像
		$dst = $img;//"12.jpg"; //注意图片路径要正确
		//得到原始图片信息
		$dst_info = getimagesize($dst);
		$imgquality = 98; //图片质量0-100，值最大图片质量愈好
		switch ($dst_info[2])
		{
			case 1:
				$dst_im =imagecreatefromgif($dst);break;
			case 2:
				$dst_im =imagecreatefromjpeg($dst);break;
			case 3:
				$dst_im =imagecreatefrompng($dst);break;
			case 6:
				$dst_im =imagecreatefromwbmp($dst);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		//水印图像
		$src = $water;//"water2.png"; //注意路径要写对
		$src_info = getimagesize($src);
		switch ($src_info[2])
		{
			case 1:
				$src_im =imagecreatefromgif($src);break;
			case 2:
				$src_im =imagecreatefromjpeg($src);break;
			case 3:
				$src_im =imagecreatefrompng($src);break;
			case 6:
				$src_im =imagecreatefromwbmp($src);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		if($dst_info[2]=='1'||$dst_info[2]=='2'||$dst_info[2]=='3'||$dst_info[2]=='6'){
			//半透明格式水印
			//$alpha = 50;//水印透明度
			//imagecopymerge($dst_im,$src_im,$dst_info[0]-$src_info[0]-10,$dst_info[1]-$src_info[1]-10,0,0,$src_info[0],$src_info[1],$alpha);
			//支持png本身透明度的方式$src_info[0]-2//2为水印图片边距
			//-------------------------
			//-------------------
			$arry_img=getimagesize($img);
			$width_img=$arry_img[0];
			$height_img=$arry_img[1];
			//-------------
			$arry_water=getimagesize($water);
			$width_w=$arry_water[0];
			$height_w=$arry_water[1];
			$bl=$height_img/$width_img;
			$new_w=round(($width_img/2-$width_w/2),0);
			$new_h=round(($height_img/2-$height_w/2),0);
			$new_h2_01=round(($height_img/4-$height_w/2),0);
			$new_h2_02=round(($height_img/4*3-$height_w/2),0);
			if($num=='1'){
				imagecopy($dst_im,$src_im,$new_w,$new_h,0,0,$src_info[0],$src_info[1]);//居中
			}elseif($num>=2){
				imagecopy($dst_im,$src_im,$new_w,$new_h2_01,0,0,$src_info[0],$src_info[1]);
				imagecopy($dst_im,$src_im,$new_w,$new_h2_02,0,0,$src_info[0],$src_info[1]);
			}
			//保存图片
			switch ($dst_info[2]){
				case 1:
					imagejpeg($dst_im,$dst,$imgquality);break;
					//imagegif($dst_im,$dst);break;
				case 2:
					imagejpeg($dst_im,$dst,$imgquality);break;
				case 3:
					imagepng($dst_im,$dst,$imgquality);break;
				case 6:
					imagewbmp($dst_im,$dst,$imgquality);break;
			}
			imagedestroy($dst_im);
			imagedestroy($src_im);
		}
	}
	/**
	 * 微信png 24位水印图片,$face_offiset_x头像水平偏移,$face_offiset_y头像Y偏移
	 * @param unknown_type $img源图
	 * @param unknown_type $water_face头像水印图
	 * @param unknown_type $water_code二维码水印图
	 * @param unknown_type $waterstring水印文本
	 * @param unknown_type $num
	 */
	function wx_water($img,$water_face,$water_code,$face_offiset_x=80,$face_offiset_y=30,$code_offiset_x=0,$code_offiset_y=112,$num=2){
		//原始图像
		$dst = $img;//"12.jpg"; //注意图片路径要正确
		//得到原始图片信息
		$dst_info = getimagesize($dst);
		$imgquality = 98; //图片质量0-100，值最大图片质量愈好
		switch ($dst_info[2])
		{
			case 1:
				$dst_im =imagecreatefromgif($dst);break;
			case 2:
				$dst_im =imagecreatefromjpeg($dst);break;
			case 3:
				$dst_im =imagecreatefrompng($dst);break;
			case 6:
				$dst_im =imagecreatefromwbmp($dst);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		//水印图像
		$image_size =$dst_info;   //上传图片的大小
		$src_code = $water_code;//"water2.png"; //注意路径要写对
		$src_face = $water_face;//"water2.png"; //注意路径要写对
		$src_info_code = getimagesize($src_code);
		$src_info_face = getimagesize($src_face);

		switch ($src_info_code[2])
		{
			case 1:
				$src_im_code =imagecreatefromgif($src_code);break;
			case 2:
				$src_im_code =imagecreatefromjpeg($src_code);break;
			case 3:
				$src_im_code =imagecreatefrompng($src_code);break;
			case 6:
				$src_im_code =imagecreatefromwbmp($src_code);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		switch ($src_info_face[2])
		{
			case 1:
				$src_im_face =imagecreatefromgif($src_face);break;
			case 2:
				$src_im_face =imagecreatefromjpeg($src_face);break;
			case 3:
				$src_im_face =imagecreatefrompng($src_face);break;
			case 6:
				$src_im_face =imagecreatefromwbmp($src_face);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		if($dst_info[2]=='1'||$dst_info[2]=='2'||$dst_info[2]=='3'||$dst_info[2]=='6'){
			//半透明格式水印
			//$alpha = 50;//水印透明度
			//imagecopymerge($dst_im,$src_im,$dst_info[0]-$src_info_code[0]-10,$dst_info[1]-$src_info_code[1]-10,0,0,$src_info_code[0],$src_info_code[1],$alpha);
			//支持png本身透明度的方式$src_info_code[0]-2//2为水印图片边距
			//-------------------------
			//-------------------
			$arry_img=getimagesize($img);
			$width_img=$arry_img[0];
			$height_img=$arry_img[1];
			//-------------
			$arry_water=getimagesize($water_code);
			$width_w=$arry_water[0];
			$height_w=$arry_water[1];
			$bl=$height_img/$width_img;
			$new_w=round(($width_img/2-$width_w/2),0);
			$new_h=round(($height_img/2-$height_w/2),0);
			$new_h2_01=round(($height_img/4-$height_w/2),0);
			$new_h2_02=round(($height_img/4*3-$height_w/2),0);
			if($num=='1'){
				imagecopy($dst_im,$src_im_code,$new_w,$new_h,0,0,$src_info_code[0],$src_info_code[1]);//居中
			}elseif($num>=2){
				imagecopy($dst_im,$src_im_code,$new_w,$new_h+$code_offiset_y,0,0,$src_info_code[0],$src_info_code[1]);//二维码
				imagecopy($dst_im,$src_im_face,$face_offiset_x,$face_offiset_y,0,0,$src_info_face[0],$src_info_face[1]);//头像
			}
			//保存图片
			switch ($dst_info[2]){
				case 1:
					imagejpeg($dst_im,$dst,$imgquality);break;
					//imagegif($dst_im,$dst);break;
				case 2:
					imagejpeg($dst_im,$dst,$imgquality);break;
				case 3:
					imagepng($dst_im,$dst,$imgquality);break;
				case 6:
					imagewbmp($dst_im,$dst,$imgquality);break;
			}
		
			imagedestroy($dst_im);
			
			imagedestroy($src_im_code);
			imagedestroy($src_im_face);
		}
	}
	/**
	 * 微信png 24位水印图片,$face_offiset_x头像水平偏移,$face_offiset_y头像Y偏移
	 * @param unknown_type $img源图
	 * @param unknown_type $water_face头像水印图
	 * @param unknown_type $water_code二维码水印图
	 * @param unknown_type $waterstring水印文本
	 * @param unknown_type $num
	 */
	function wx_water_face($img,$water,$x=80,$y=30){
	//原始图像
		$dst = $img;//"12.jpg"; //注意图片路径要正确
		//得到原始图片信息
		$dst_info = getimagesize($dst);
		$imgquality = 98; //图片质量0-100，值最大图片质量愈好
		switch ($dst_info[2])
		{
			case 1:
				$dst_im =imagecreatefromgif($dst);break;
			case 2:
				$dst_im =imagecreatefromjpeg($dst);break;
			case 3:
				$dst_im =imagecreatefrompng($dst);break;
			case 6:
				$dst_im =imagecreatefromwbmp($dst);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		//水印图像
		$image_size =$dst_info;   //上传图片的大小
		$src_code = $water;//"water2.png"; //注意路径要写对

		$src_info = getimagesize($src_code);
	
		switch ($src_info[2])
		{
			case 1:
				$src_im =imagecreatefromgif($src_code);break;
			case 2:
				$src_im =imagecreatefromjpeg($src_code);break;
			case 3:
				$src_im =imagecreatefrompng($src_code);break;
			case 6:
				$src_im =imagecreatefromwbmp($src_code);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		
		if($dst_info[2]=='1'||$dst_info[2]=='2'||$dst_info[2]=='3'||$dst_info[2]=='6'){
			//半透明格式水印
			//$alpha = 50;//水印透明度
			//imagecopymerge($dst_im,$src_im,$dst_info[0]-$src_info[0]-10,$dst_info[1]-$src_info[1]-10,0,0,$src_info[0],$src_info[1],$alpha);
			//支持png本身透明度的方式$src_info[0]-2//2为水印图片边距
			//-------------------------
			//-------------------

			imagecopy($dst_im,$src_im,$x,$y,0,0,$src_info[0],$src_info[1]);//二维码
			//保存图片
			switch ($dst_info[2]){
				case 1:
					imagejpeg($dst_im,$dst,$imgquality);break;
					//imagegif($dst_im,$dst);break;
				case 2:
					imagejpeg($dst_im,$dst,$imgquality);break;
				case 3:
					imagepng($dst_im,$dst,$imgquality);break;
				case 6:
					imagewbmp($dst_im,$dst,$imgquality);break;
			}	
			imagedestroy($dst_im);				
			imagedestroy($src_im);
		}
	}
	/**
	 * 微信png 24位水印图片,$face_offiset_x头像水平偏移,$face_offiset_y头像Y偏移
	 * @param unknown_type $img源图
	 * @param unknown_type $water_face头像水印图
	 * @param unknown_type $water_code二维码水印图
	 * @param unknown_type $waterstring水印文本
	 * @param unknown_type $num
	 */
	function wx_water_code($img,$water,$x=0,$y=112){
		//原始图像
		$dst = $img;//"12.jpg"; //注意图片路径要正确
		//得到原始图片信息
		$dst_info = getimagesize($dst);
		$imgquality = 98; //图片质量0-100，值最大图片质量愈好
		switch ($dst_info[2])
		{
			case 1:
				$dst_im =imagecreatefromgif($dst);break;
			case 2:
				$dst_im =imagecreatefromjpeg($dst);break;
			case 3:
				$dst_im =imagecreatefrompng($dst);break;
			case 6:
				$dst_im =imagecreatefromwbmp($dst);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		//水印图像
		$image_size =$dst_info;   //上传图片的大小
		$src_code = $water;//"water2.png"; //注意路径要写对

		$src_info = getimagesize($src_code);
	
		switch ($src_info[2])
		{
			case 1:
				$src_im =imagecreatefromgif($src_code);break;
			case 2:
				$src_im =imagecreatefromjpeg($src_code);break;
			case 3:
				$src_im =imagecreatefrompng($src_code);break;
			case 6:
				$src_im =imagecreatefromwbmp($src_code);break;
			default:
				echo("不支持的文件类型1");//exit;
		}
		
		if($dst_info[2]=='1'||$dst_info[2]=='2'||$dst_info[2]=='3'||$dst_info[2]=='6'){
			//半透明格式水印
			//$alpha = 50;//水印透明度
			//imagecopymerge($dst_im,$src_im,$dst_info[0]-$src_info[0]-10,$dst_info[1]-$src_info[1]-10,0,0,$src_info[0],$src_info[1],$alpha);
			//支持png本身透明度的方式$src_info[0]-2//2为水印图片边距
			//-------------------------
			//-------------------
			$arry_img=getimagesize($img);
			$width_img=$arry_img[0];
			$height_img=$arry_img[1];
			//-------------
			$arry_water=getimagesize($water);
			$width_w=$arry_water[0];
			$height_w=$arry_water[1];
			$bl=$height_img/$width_img;
			$new_w=round(($width_img/2-$width_w/2),0);
			$new_h=round(($height_img/2-$height_w/2),0);
			imagecopy($dst_im,$src_im,$new_w,$new_h+$y,0,0,$src_info[0],$src_info[1]);//二维码
			//保存图片
			switch ($dst_info[2]){
				case 1:
					imagejpeg($dst_im,$dst,$imgquality);break;
					//imagegif($dst_im,$dst);break;
				case 2:
					imagejpeg($dst_im,$dst,$imgquality);break;
				case 3:
					imagepng($dst_im,$dst,$imgquality);break;
				case 6:
					imagewbmp($dst_im,$dst,$imgquality);break;
			}	
			imagedestroy($dst_im);				
			imagedestroy($src_im);
		}
	}
	//--------------------------
	/**
	 * 微信水印文字，$x_offiset,$y_offiset左上角X和Y的偏移,63,103
	 * @param  $img
	 * @param  $waterimg
	 * @param  $watertype 水印类型(1为文字,2为图片
	 */
	function wx_water_text($img, $waterstring,$font_size=12,$fontfile='',$x_offiset=63,$y_offiset=103){
		
		$dst_info = getimagesize($img);

		$imgquality = 98; //图片质量0-100，值最大图片质量愈好
		//Header("Content-type: image/png"); //通知浏览器,要输出图像
		$im = imagecreatefromjpeg($img);//载入图片   //imagecreate(400 , 300); //定义图像的大小
	
		$color = ImageColorAllocate($im, 82 ,76 , 83);
		if($fontfile==''){
			$fontfile = $_SERVER["DOCUMENT_ROOT"]."/weixin/font/MSYH.TTC";
		}
		
		ImageTTFText($im, $font_size, 0, $x_offiset, $y_offiset, $color , $fontfile , $waterstring);
		// 加入中文水印 
		//保存图片
		switch ($dst_info[2]){
			case 1:
				imagejpeg($im,$img,$imgquality);break;
				//imagegif($dst_im,$dst);break;
			case 2:
				imagejpeg($im,$img,$imgquality);break;
			case 3:
				imagepng($im,$img,$imgquality);break;
			case 6:
				imagewbmp($im,$img,$imgquality);break;
		}
		ImageDestroy($im);
	}
}
//-------------------------------------------------------------------------------------------------------------------------------

?>