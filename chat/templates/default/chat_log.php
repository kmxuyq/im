<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>聊天记录</title>
    <link rel="stylesheet" href="<?php echo CHAT_TEMPLATES_URL; ?>/lib/css/jx.css" />
      <link rel="stylesheet" href="<?php echo CHAT_TEMPLATES_URL; ?>/css/message/chat.css"/>
    <link rel="stylesheet" href="<?php echo CHAT_TEMPLATES_URL; ?>/css/message/chatrecord.css" />
    <script type="text/javascript" src="<?php echo CHAT_TEMPLATES_URL; ?>/lib/js/zepto.min.js" ></script>
        <script src="/data/resource/js/jquery.js" type="text/javascript">
        </script>
        <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/layer-v2.1/layer/layer.js"></script>
        <script type="text/javascript" src="/chat/resource/js/user.js"></script>
    <script>
    $(function(){
      more1();
      more();
      $(".back").on("touchend",function(){
          history.go(-1)
      });
    });
    </script>
  </head>
  <body>
    <header class="header">
      <span>聊天记录</span>
      <i class="back"></i>
    </header>
    <div class="chat-model">
      <input type="hidden" class="more" data-page=0></input>
    </div>
  </body>
  <?php echo getChat($layout, 1); ?>
  <script type="text/javascript">
  function show_goods() {
      var msg = '<a href="/wap_shop/index.php?act=goods&op=index&goods_id=<?php echo $output['goods']['goods_id'] ?>"><div class="content-product clearfix"> <div class="product-img"> <img style="width:100%;height: inherit;" src="<?php echo $output['goods']['pic'] ?>" alt="<?php echo $output['goods']['goods_name'] ?>"> </div> <dl class="product-info"> <dt><?php echo $output['goods']['goods_name'] ?></dt> <dd class="product-price"><em>￥</em><span class="price__new"><?php echo $output['goods']['goods_promotion_price'] ?></span><span class="price__old">￥<?php echo $output['goods']['goods_marketprice'] ?></span></dd> </dl> </div></a>';
        return msg;
    }
  //资讯加载更多
function more(){
    var winH = $(window).height(); //页面可视区域高度
    $(window).scroll(function() {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight == scrollHeight) {
              var _this = $(".more");
              var curpages = _this.attr("data-page");
              curpages++;
              _this.attr("data-page",curpages);
              $.ajax({
                type: "GET",
                url: "index.php?act=web_chat&op=get_chat_log&goods_id=<?=$_GET['goods_id']?>&f_id=<?=$_GET['uid']?>&curpage="+curpages,
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
                      var arr = msg['t_msg'].split('@');
                      if (arr[0] == "img") {
                          if (arr[1] < 4) {
                              var width = (arr[1]) * 3;
                              var height = (arr[2]) * 3;
                          } else {
                              var width = arr[1];
                              var height = arr[2];
                          }
                          var new_msg = '图片信息';
                          var msg_details = '<div style="padding-bottom:10px;width:' + width + 'rem;height:' + height + 'rem;"><img src="' + arr[3] + '" style="display: block;width:100%;height:100%;" /><div>';
                      }else if (arr[0] == "goods_id") {
                        var msg_details = show_goods();
                      }else{
                        var msg_details = update_chat_msg_smiles(msg['t_msg']);
                      }
                      var msg_html = '';
                      msg_html += '<div class="chat-'+class_style+'-item"> <div class="chat-time">'+msg['time']+'</div> <div class="chat-info"> <div class="head-pic"> <img src="'+msg['avatar']+'"/> </div> <div class="content-info"> <div class="nick-name">'+msg['f_name']+'</div> <div class="content"> <i class="jiantou"></i> '+msg_details+' </div> </div> </div> </div>';
                      $('.more').before(msg_html);
                    }

                }else {
                  layer.msg('aOh，没有更多信息了~');
                    return false;
                }
              }
           });
        }
    });

}
function more1(){
  var _this = $(".more");
  var curpages = _this.attr("data-page");
  curpages++;
  _this.attr("data-page",curpages);
  $.ajax({
    type: "GET",
    url: "index.php?act=web_chat&op=get_chat_log&goods_id=<?=$_GET['goods_id']?>&f_id=<?=$_GET['uid']?>&curpage="+curpages,
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
          var msg_html = '';
          msg_html += '<div class="chat-'+class_style+'-item"> <div class="chat-time">'+msg['time']+'</div> <div class="chat-info"> <div class="head-pic"> <img src="'+msg['avatar']+'"/> </div> <div class="content-info"> <div class="nick-name">'+msg['f_name']+'</div> <div class="content"> <i class="jiantou"></i> '+msg_details+' </div> </div> </div> </div>';
          $('.more').before(msg_html);
        }

    }else {
      layer.msg('aOh，没有更多信息了~');
        return false;
    }
  }
});

}
</script>
</html>
