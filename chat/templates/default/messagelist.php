<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <meta content="IE=9" http-equiv="X-UA-Compatible"/>
            <meta content="text/html;charset=utf-8" http-equiv="content-type"/>
            <meta content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
            <title>
                IM
            </title>
            <link href="<?php echo CHAT_TEMPLATES_URL; ?>/lib/css/jx.css" rel="stylesheet" type="text/css">
                <link href="<?php echo CHAT_TEMPLATES_URL; ?>/css/message/messagelist.css" rel="stylesheet" type="text/css">
                    <script src="<?php echo CHAT_TEMPLATES_URL; ?>/lib/js/zepto.min.js" type="text/javascript">
                    </script>
                    <script src="/data/resource/js/jquery.js" type="text/javascript">
                    </script>
                </link>
            </link>
        </meta>
    </head>
    <body style="background-color: #fff;">
        <header class="header">
            <span>
                联系人
            </span>
            <i class="logout" onclick="logout()"><img src="<?php echo CHAT_TEMPLATES_URL; ?>/img/logout.png" style="width: 50%; margin-top: 0.6rem;"> </i>
        </header>
        <section>
            <div class="contactlist">
                <div class="inform activeinfor">
                    <div class="inform-left">
                        通知
                        <?php if ($output['store_msg_num'] > 0) {?>
                        <span class="customermessge inforleftmessge">
                            <?=$output['store_msg_num']?>
                        </span>
                        <?php }?>
                    </div>
                    <div class="inform-right">
                    </div>
                </div>
                <div class="informlist">
                    <ul>
                        <?php if (!empty($output['msg_list'])) {?>
                        <?php foreach ($output['msg_list'] as $val) {?>
                        <li class="information" id="notice<?=$val['sm_id']?>">
                            <div class="informlist-li">
                            <a href="index.php?act=web_chat&op=msg_info&sm_id=<?=$val['sm_id']?>">
                                <div class="icon">
                                    <div class="informlist-left">
                                        <?php echo $val['sm_content'] ?>
                                    </div>
                                    <div class="informlist-right">
                                        <?php echo date('y/m/d', $val['sm_addtime']); ?>
                                    </div>
                                </div>
                                </a>
                                <div class="btn_sm" onclick="delete_sm_msg(<?=$val['sm_id']?>)">
                                    删除
                                </div>
                            </div>
                        </li>
                        <?php }?>
                        <?php } else {?>
                        <li class="information">
                            <div class="informlist-inform">
                                <div class="icon">
                                    <div class="informlist-left" style="padding-left: 0rem;background: none;">
                                        <?php echo $lang['no_record']; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php }?>
                    </ul>
                </div>
                <div class="customer">
                    <div class="inform-left">
                        商家客服
                        <span class="customermessge" id="customermessge" style="display: none;">
                            9
                        </span>
                    </div>
                    <div class="inform-right">
                    </div>
                </div>
                <div class="customerlist">
                    <ul id="chat_sellers">
                    </ul>
                </div>
                <div class="recent-contacts">
                    <div class="inform-left">
                        最近联系人
                    </div>
                    <div class="inform-right">
                    </div>
                </div>
                <div class="contacts">
                    <ul>
                    <div id="chat_recent"></div>
                    </ul>
                </div>
            </div>
        </section>
    </body>
    <?php echo getChat($layout, 1); ?>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/layer-v2.1/layer/layer.js"></script>
</html>
<script>
function logout() {
    //询问框
    layer.confirm('您确定退出吗？', {
      btn: ['确定','取消'] //按钮
    }, function(){
        var ajaxurl = '/chat/index.php?act=web_chat&op=logout';
        $.ajax({
            type: "GET",
            url: ajaxurl,
            success: function(msg) {
                if (msg == 1) {
                   layer.msg('退出成功', {icon: 1});
                    window.location.href = '/chat/index.php?act=web_chat&op=login';
                } else {
                    layer.msg('退出失败，请稍后再试！')
                }
            }
        });
      // layer.msg('的确很重要', {icon: 1});
    });
}
function chat_online(uid) {
    location.href='index.php?act=web_chat&op=chat_online&goods_id=&uid='+uid;
}
function delete_sm_msg(msg_id) {
    var ajaxurl = '/shop/index.php?act=store_msg&op=mark_as_read&smids='+ msg_id+'&inajax=1&from=web_chat';
    $.ajax({
        type: "GET",
        url: ajaxurl,
        success: function(msg) {
           var data;
            eval('data='+msg);
            if (data.status == 1) {
                $('#notice'+msg_id).remove();
                $('.inforleftmessge').text(data.msg_num);
                // $(this).parents('.information').css('display', 'none');

                // $(this).parents('.information').remove();
                // window.location.reload();
            } else {
                layer.msg('删除失败，请稍后再试！')
            }
        }
    });
    // window.location.href = '/chat/index.php?act=web_chat&op=messagelist';
}
    $(function() {
        /*滑动删除*/

        /*禁止浏览器后退*/
        if (window.history && window.history.pushState) {
        　　$(window).on('popstate', function () {
        　　window.history.pushState('forward', null, '#');
        　　window.history.forward(1);
    　　});
    　　}
    　　window.history.pushState('forward', null, '#'); //在IE中必须得有这两行
    　　window.history.forward(1);
        $(".back").on('touchend',function () {
            alert("返回");
        });
        $(".inform").on("touchend",function() {
            $('.informlist').toggleClass("block");
            $(this).children('.inform-right').toggleClass('active');
            inittranslate()
        });
        // $(".btn_sm").on("touchend", function() {
        //     $(this).parents('.information').css('display', 'none');
        // });
        $(".customer").on("touchend",function() {
            $('.customerlist').toggleClass("block");
            $(this).children('.inform-right').toggleClass('active');
             inittranslate()
        });
        $(".recent-contacts").on("touchend",function() {
            $('.contacts').toggleClass("block");
            $(this).children('.inform-right').toggleClass('active');
            inittranslate()
        });

    });
</script>
