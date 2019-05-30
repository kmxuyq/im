<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>评价商品</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/lightGallery.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" />
<!-- <link rel="stylesheet" type="text/css" href="http://fex.baidu.com/webuploader/css/webuploader.css" /> -->
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/lightGallery.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/webuploader.html5only.js" charset="utf-8"></script>
<style type="text/css">
.choice_updata_pic label {
    display: block;
    width: 52px;
    height: 52px;
    border: 1px #e3e3e3 solid;
    float: left;
}
.choice_updata_pic input{
    width: 50px;
}
</style>
</head>

<body class="bg_gray">
<form id="evalform" method="post" action="index.php?act=member_evaluate&op=<?php echo $_GET['op'];?>&order_id=<?php echo $_GET['order_id'];?>">
<!--评价商品-->
<div class="ev_goods">
    <!--评价循环体-->
<?php if(!empty($output['order_goods'])){?>
<?php foreach($output['order_goods'] as $goods){?>
    <div class="ev_lines">
        <!--下单时间-->
        <div class="order_time">下单时间 <?php echo date('Y-m-d H:i:s', $output['order_info']['add_time']); ?></div>
        <!--星评价-->
        <div class="goods_ev_score">
            <div class="ev_goods_pic"><div class="ov_width"><img src="<?php echo $goods['goods_image_url']; ?>"></div></div>
            <div class="ev_score">
                <div class="title"><?php echo $goods['goods_name'];?></div>
                <div class="dis">价格: ￥<?php echo $goods['goods_price'];?></div>
                <div class="p_score">
                    <div class="ps_title">商品评价</div>
                    <div class="scores">
                        <span class="sel">1星</span>
                        <span class="sel">2星</span>
                        <span class="sel">3星</span>
                        <span class="sel">4星</span>
                        <span class="sel">5星</span>
                    </div>
                    <input type="hidden" value="5" class="star-input" name="goods[<?php echo $goods['goods_id'];?>][score]" type="hidden">
                    <label style="float: right;"><input type="checkbox" class="checkbox vm" name="anony">匿名评价</label>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!--文字评价-->
        <textarea rows="2" name="goods[<?php echo $goods['goods_id'];?>][comment]" placeholder="评价下商品吧...." class="pingjia"></textarea>
        <!--选择图片-->
        <div class="choice_updata_pic">
            <div id="fileList_<?php echo $goods['goods_id'];?>"></div>
            <div id="filePicker_<?php echo $goods['goods_id'];?>"></div>
            <div class="clear"></div>
        </div>
    </div>
<script type="text/javascript">

// 初始化Web Uploader
var uploader_<?php echo $goods['goods_id'];?> = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // 文件接收服务端。
    server: 'index.php?act=sns_album&op=swfupload',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker_<?php echo $goods['goods_id'];?>',
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader_<?php echo $goods['goods_id'];?>.on( 'uploadSuccess', function( file , response) {
    if(response.state) {
        var $li = $(
            '<div class="single_pics"><div class="gd_widht">' +
                '<img src="'+response.file_url+'" />' +
                '<input type="hidden" name="goods[<?php echo $goods['goods_id'];?>][eval_image][]" value="' + response.file_name + '" />' +
            '</div>'
            );
        $("#fileList_<?php echo $goods['goods_id'];?>").append($li);
    } else {
        alert('error!');
    }

});
// 文件上传失败，显示上传出错。
uploader_<?php echo $goods['goods_id'];?>.on( 'uploadError', function( file ) {
    alert('error!');
});
</script>
<?php } ?>
<?php } ?>
<?php if (!$output['store_info']['is_own_shop'] && $_GET['op'] != 'add_vr') { ?>
    <div class="ev_lines">
        <!--星评价-->
        <div class="goods_ev_score">
            <div class="ev_score">
                <div class="p_score">
                    <div class="ps_title">描述相符</div>
                    <div class="scores">
                        <span class="sel">1星</span>
                        <span class="sel">2星</span>
                        <span class="sel">3星</span>
                        <span class="sel">4星</span>
                        <span class="sel">5星</span>
                    </div>
                    <input type="hidden" class="star-input" name="store_deliverycredit" value="5">
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="ev_lines">
        <!--星评价-->
        <div class="goods_ev_score">
            <div class="ev_score">
                <div class="p_score">
                    <div class="ps_title">服务态度</div>
                    <div class="scores">
                        <span class="sel">1星</span>
                        <span class="sel">2星</span>
                        <span class="sel">3星</span>
                        <span class="sel">4星</span>
                        <span class="sel">5星</span>
                    </div>
                    <input type="hidden" class="star-input" name="store_servicecredit" value="5">
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="ev_lines">
        <!--星评价-->
        <div class="goods_ev_score">
            <div class="ev_score">
                <div class="p_score">
                    <div class="ps_title">发货速度</div>
                    <div class="scores">
                        <span class="sel">1星</span>
                        <span class="sel">2星</span>
                        <span class="sel">3星</span>
                        <span class="sel">4星</span>
                        <span class="sel">5星</span>
                    </div>
                    <input type="hidden" class="star-input" name="store_deliverycredit" value="5">
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <?php } ?>
</div>
<div class="foothr"></div>
<div class="bottom_btnt"><a href="javascript:;" onclick="sub()">提交评价</a></div>
</form>
<script>
    function sub() {
        var t = true;
        var pingjia = $(".pingjia");
        $.each(pingjia,function(i,n){
            if($(n).val()=='') {
                t = false;
                alert('请您填写评价内容!');
                return false;
            }
        });
        if(t) {
            $('#evalform').submit()
        }
    }
$(function(){
    //星评价
    $(".p_score .scores span").click(function(){
        var index=$(this).index()+1;
        $(this).parent().find("span").addClass("sel");
        $(this).nextAll().removeClass("sel");
        $(this).parent().parent().find("input").val(index);
        console.log("您评价了"+index+"分！");
        })

    //文字评价获取和失去焦点事件
    $(".ev_goods .ev_lines textarea").focus(function(){
        var def_val=$(this).get(0).defaultValue;
        if($(this).val()==def_val){
            $(this).addClass("focus").val("");
            }
        }).blur(function(){
            var def_val=$(this).get(0).defaultValue;
            if($(this).val()==""){
                $(this).removeClass("focus").val(def_val);
                }
            })
    //点小图预览大图
    $(".choice_updata_pic .single_pics").on("click",function(){
        $("#auto-loop").remove();
        var index=$(this).index();
        var html1="<ul id='auto-loop' class='gallery'>";
        var html2='';
        var html3="</ul>";
        $(this).parent().find(".single_pics").each(function(){
            var src=$(this).find("img").attr("src");
            html2+='<li data-src="'+src+'"><img src="'+src+'" /></li>'
            })
        var html=html1+html2+html3;
        $("body").append(html);

        $("#auto-loop").lightGallery({
            loop:true, //是否循环播放
            auto:false,   //是否自动播放
            pause:3000,   //自动播放时，停留时间
            easing: 'ease-in-out',   //播放的动画类型
            speed : 500,    //图片滚动速度
            counter:true,   //是否显示图片的数量
            swipethreshold:50
        });
        $("#auto-loop li").eq(index).trigger("click");
        })
    })
</script>
</body>
</html>
