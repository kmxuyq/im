<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>通知</title>
        <link rel="stylesheet" href="<?php echo CHAT_TEMPLATES_URL; ?>/lib/css/jx.css" />
        <link rel="stylesheet" href="<?php echo CHAT_TEMPLATES_URL; ?>/css/message/inform.css" />
        <script type="text/javascript" src="<?php echo CHAT_TEMPLATES_URL; ?>/lib/js/zepto.min.js"></script>
        <script>
            $(function() {
                // $(".back").on("touchend",function() {
                //     history.go(-1);
                // });
            });
        </script>
    </head>

    <body>
        <header class="header">
            <span>通知</span>
            <a href="/chat/index.php?act=web_chat&op=messagelist">
            <i class="back">
            </i>
            </a>
        </header>
        <div class="inform-model ">
            <div class="inform-item">
                <div class="inform-time"><?php echo date('H:i', $output['msg_list']['sm_addtime']); ?></div>
                <div class="inform-content">
                    <p><?php echo $output['msg_list']['sm_content']; ?></p>
                </div>
            </div>
        </div>
    </body>
</html>
