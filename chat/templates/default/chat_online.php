<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>对话中</title>
    <link rel="stylesheet" href="<?php echo CHAT_TEMPLATES_URL; ?>/lib/css/jx.css"/>
    <link rel="stylesheet" href="<?php echo CHAT_TEMPLATES_URL; ?>/css/message/chat.css"/>
    <script src="/data/resource/js/jquery-1.11.0.min.js" type="text/javascript">
    </script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/layer-v2.1/layer/layer.js">
    </script>

    <script type="text/javascript" src="<?php echo CHAT_TEMPLATES_URL; ?>/lib/js/zepto.min.js">
    </script>
    <script>
    $(function(){
      more1();
    });
    //执行键盘按键命令
        function keyDown(e){
         var keycode = 0;
         //IE浏览器
         if(CheckBrowserIsIE()){
          keycode = event.keyCode;
         }else{
         //火狐浏览器
         keycode = e.which;
         }
         if (keycode == 13 ) //回车键是13
         {
          send_msg()
         }
        }
        //判断访问者的浏览器是否是IE
        function CheckBrowserIsIE(){
         var result = false;
         var browser = navigator.appName;
         if(browser == "Microsoft Internet Explorer"){
          result = true;
         }
         return result;
        }
        $(function () {
        $('input[type="text"],textarea').on('click', function () {
          var target = this;
          setTimeout(function(){
              $('.chat-faces').css('display','none');
              $('#anchor-bottom')[0].scrollIntoView();
                target.scrollIntoViewIfNeeded();
              },400);

          // setTimeout(function(){
                // $('#anchor-bottom').scrollIntoView();
              // },400);
        });
            $(".back").on('touchend',function () {
                history.go(-1)
            });
        });
    </script>
</head>

<body onkeydown="keyDown(event);">
<header class="header">
    <span id="header_info">对1号客服..对话中</span>
    <i class="back"></i>
    <a href="index.php?act=web_chat&op=chat_log&goods_id=<?=$_GET['goods_id']?>&uid=<?=$_GET['uid']?>" class="header-btn__right">消息记录</a>
</header>
<?php if ($output['is_goods'] == 1) {?>
<div class="product clearfix" id="product_details">
</div>
<?php }?>

<div class="chat-model clearfix" <?php if ($output['is_goods'] == 0) {?>style="margin-top: 3rem;"<?php }?>>
    <div id="msg_list" class="msg-contnet ps-container" style="<?php if ($output['is_goods'] == 0) {?>margin-bottom: 3.2rem;<?php }?>" >
     <div id="user_msg_list">

     </div>
    </div>
    <input type="hidden" id="dialog_show" value="1"></input>
</div>
<a href="javascript:void(0);" id="anchor-bottom">
        </a>
<footer class="chat-cansole">
    <div class="chat-form">
        <div class="chat-face"><img src="<?php echo CHAT_TEMPLATES_URL; ?>/img/messgelist/face.png" alt="选择表情" style="width: 100%;" /></div>
        <div class="chat-input"><input type="text" id="send_message" placeholder="我想说..."></input>
        </div>
        <div class="chat-btn" onclick="send_msg()">发送</div>
    </div>
    <div class="chat-faces">
        <ul class="clearfix"></ul>
    </div>
</footer>

<script>
    function set_smiles(i) {
        var o = smilies_array[1][i][1];
        $('#send_message').val(o);
    }
    function show_goods() {
      var msg = '<a href="/wap_shop/index.php?act=goods&op=index&goods_id=<?php echo $output['goods']['goods_id'] ?>"><div class="content-product clearfix"> <div class="product-img"> <img style="width:100%;height: inherit;" src="<?php echo $output['goods']['pic'] ?>" alt="<?php echo $output['goods']['goods_name'] ?>"> </div> <dl class="product-info"> <dt><?php echo $output['goods']['goods_name'] ?></dt> <dd class="product-price"><em>￥</em><span class="price__new"><?php echo $output['goods']['goods_promotion_price'] ?></span><span class="price__old">￥<?php echo $output['goods']['goods_marketprice'] ?></span></dd> </dl> </div></a>';
        return msg;
    }
    function more1(){
    $.ajax({
      type: "GET",
      url: "index.php?act=web_chat&op=get_chat_log&goods_id=<?=$_GET['goods_id']?>&f_id=<?=$_GET['uid']?>&curpage=1&unread=<?=$_GET['unread']?>&is_chat=1",
      success: function (msg) {
        var data;
        eval('data='+msg);
        if (data != '') {
          for (var k in data) {
            var msg = data[k];
            if (msg['msg_from']=='member') {
              var class_style = 'left';
            }else{
              var class_style = 'right';
            }
            var msg_html = '';
            var arr = msg['t_msg'].split('@');
            if (arr[0] == "img") {
                if (arr[1] < 4) {
                    var width = (arr[1]) * 3;
                    var height = (arr[2]) * 3;
                }else if(arr[1] > 20){
                    var width = (arr[1]) / 2;
                    var height = (arr[2]) / 2;

                }else if(arr[1] > 30){
                    var width = (arr[1]) / 3;
                    var height = (arr[2]) / 3;

                } else {
                    var width = arr[1];
                    var height = arr[2];
                }
                var new_msg = '图片信息';
                var msg_details = '<div style="padding-bottom:15px;width:' + width + 'rem;height:' + height + 'rem;"><img src="' + arr[3] + '" style="display: block;width:100%;height:100%;" /><div>';
            }else if (arr[0] == "goods_id") {
              var msg_details = show_goods();
            }else{
              var msg_details = update_chat_msg_smiles(msg['t_msg']);
            }


            msg_html += '<div class="chat-'+class_style+'-item"> <div class="chat-time">'+msg['time']+'</div> <div class="chat-info"> <div class="head-pic"> <img src="'+msg['avatar']+'"/> </div> <div class="content-info"> <div class="nick-name">'+msg['f_name']+'</div> <div class="content"> <i class="jiantou"></i> '+msg_details+' </div> </div> </div> </div>';
            $('#msg_list').append(msg_html);
            $("#anchor-bottom")[0].scrollIntoView()
          }

      }else {
        // layer.msg('aOh，没有更多信息了~');
          return false;
      }
    }
  });

}
    (function () {
        var smilies_array = new Array;
        smilies_array[1] = [
    ["1", ":smile:", "14@2x.png", "28", "28", "28", "微笑"],
    ["2", ":sad:", "15@2x.png", "28", "28", "28", "难过"],
    ["3", ":biggrin:", "13@2x.png", "28", "28", "28", "呲牙"],
    ["4", ":cry:", "9@2x.png", "28", "28", "28", "大哭"],
    ["5", ":huffy:", "11@2x.png", "28", "28", "28", "发怒"],
    ["6", ":shocked:", "0@2x.png", "28", "28", "28", "惊讶"],
    ["7", ":tongue:", "12@2x.png", "28", "28", "28", "调皮"],
    ["8", ":shy:", "6@2x.png", "28", "28", "28", "害羞"],
    ["9", ":titter:", "20@2x.png", "28", "28", "28", "偷笑"],
    ["10", ":sweat:", "27@2x.png", "28", "28", "28", "流汗"],
    ["11", ":mad:", "18@2x.png", "28", "28", "28", "抓狂"],
    ["12", ":lol:", "108@2x.png", "28", "28", "28", "阴险"],
    ["13", ":loveliness:", "21@2x.png", "28", "28", "28", "可爱"],
    ["14", ":funk:", "26@2x.png", "28", "28", "28", "惊恐"],
    ["15", ":curse:", "31@2x.png", "28", "28", "28", "咒骂"],
    ["16", ":dizzy:", "34@2x.png", "28", "28", "28", "晕"],
    ["17", ":shutup:", "7@2x.png", "28", "28", "28", "闭嘴"],
    ["18", ":sleepy:", "25@2x.png", "28", "28", "28", "睡"],
    ["19", ":hug:", "49@2x.png", "28", "28", "28", "拥抱"],
    ["20", ":victory:", "79@2x.png", "28", "28", "28", "胜利"],
    ["21", ":sun:", "74@2x.png", "28", "28", "28", "太阳"],
    ["22", ":moon:", "75@2x.png", "28", "28", "28", "月亮"],
    ["23", ":kiss:", "109@2x.png", "28", "28", "28", "示爱"],
    ["24", ":handshake:", "78@2x.png", "28", "28", "28", "握手"]
];
        var htmlArr = [];
        for (var i in smilies_array[1]) {
            var o = smilies_array[1][i];
            htmlArr.push('<li><img data-sign="' + o[1] + '" src="<?php echo RESOURCE_SITE_URL; ?>/js/smilies/images/' + smilies_array[1][i][2] + '" style="width:28px;"/></li>')
        }
        $('.chat-faces ul').append(htmlArr.join(''))

        var $chatCansole = $('.chat-cansole');
        $('.chat-face').click(function() {
            if ($chatCansole.hasClass("show-faces")) {
                $chatCansole.removeClass("show-faces");
                $('.chat-faces').css('display','none');
                // $(".nctouch-chat-con").css("bottom", "7rem");
            } else {
                $chatCansole.addClass("show-faces");
                $('.chat-model').css("margin-bottom", $chatCansole.height() + 10);
                $('.chat-faces').css('display','block')
                // $(".nctouch-chat-con").css("bottom", "2rem")
            }
        })
        $(".chat-faces").on("click", "img", function() {
            var e = $(this).attr("data-sign");
            var t = $("#send_message")[0];
            var a = t.selectionStart;
            var s = t.selectionEnd;
            var i = t.scrollTop;
            t.value = t.value.substring(0, a) + e + t.value.substring(s, t.value.length);
            t.setSelectionRange(a + e.length, s + e.length)
        });
    })()
</script>
</body>
    <?php echo getChat($layout, 1); ?>

</html>