if (getQueryString("key") != "") {
    var key = getQueryString("key")
} else {
    var key = getCookie("key")
}
var nodeSiteUrl = "";
var memberInfo = {};
var userInfo = {};
var resourceSiteUrl = "/data/resource";
var ApiUrl = "/wap_shop";
var smilies_array = new Array;
var goods_list = new Array(); //所有商品信息
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
var t_id = getQueryString("t_id");
var chat_goods_id = getQueryString("goods_id");
$(function() {
    $.getJSON(ApiUrl + "/index.php?act=member_chat&op=get_node_info", {
        key: key,
        u_id: t_id,
        chat_goods_id: chat_goods_id
    }, function(t) {
        checkLogin(t.login);
        e(t.datas);
        if (!$.isEmptyObject(t.datas.chat_goods)) {
            var a = t.datas.chat_goods;
            var s = '<div class="talk_product"><div class="inner_img"><img src="' + a.pic + '" alt=""></div><div class="text"><h1 class="tt">' + a.goods_name + '</h1><div class="price"><span class="now_price"><i>¥</i>' + a.goods_promotion_price + '</span><span class="old_price">¥' + a.goods_marketprice + '</span></div><a href="javascript:void(0);" data="' + a.url + '" class="link" id="goods_url">发送产品链接</a></div></div>';
            if (chat_goods_id != 10086) {
                $("#goods_detail").append(s)
            }
        }
    });
    if (chat_goods_id) {
        $('#product_height').css({
            display: 'block',
            width: '100%',
            height: '6.2rem'
        });
        var ajaxurl = '/wap_shop/index.php?act=member_chat&op=get_goods_info&goods_id=' + chat_goods_id;
        $.ajax({
            type: "GET",
            url: ajaxurl,
            dataType: "jsonp",
            async: true,
            success: function(goods) {
                if (typeof goods['goods_id'] !== "undefined") {
                    goods_id = goods['goods_id'];
                    goods_list[goods_id] = goods;
                    // web_info['chat_goods_id'] = goods_id;
                    // var goods_html = '';
                    // goods_html += '<div class="product-img"> <img src="' + goods['pic'] + '" alt="' + goods['goods_name'] + '" style="width:100%;height: inherit;"/> </div> <dl class="product-info"> <dt>' + goods['goods_name'] + '</dt> <dd class="product-price"><em>￥</em><span class="price__new">' + goods['goods_promotion_price'] + '</span><span class="price__old">￥' + goods['goods_marketprice'] + '</span></dd> <dd class="product-btn"> <a href="javascript:void(0);" onclick="send_msg(\'goods_id@' + goods['goods_id'] + '\')">发送产品链接</a> </dd> </dl>';
                    // $('#product_details').html(goods_html);
                    // web_info['chat_goods_html'] = goods_html;
                }
            }
        });
    } else {
        $('#product_height').css({
            display: 'block',
            width: '100%',
            height: '3.2rem'
        });
    }
    var e = function(e) {
        nodeSiteUrl = e.node_site_url;
        memberInfo = e.member_info;
        userInfo = e.user_info;
        $("h1").html(userInfo.store_name != "" ? userInfo.store_name : userInfo.member_name);
        resourceSiteUrl = e.resource_site_url;
        if (!e.node_chat) {
            $.sDialog({
                skin: "red",
                content: "在线聊天系统暂时未启用",
                okBtn: false,
                cancelBtn: false
            });
            return false
        }
        var t = document.createElement("script");
        t.type = "text/javascript";
        t.src = nodeSiteUrl + "/socket.io/socket.io.js";
        document.body.appendChild(t);
        a();

        function a() {
            setTimeout(function() {
                if (typeof io === "function") {
                    s()
                } else {
                    a()
                }
            }, 500)
        }

        function s() {
            var e = nodeSiteUrl;
            var t = 0;
            var a = {};
            a["u_id"] = memberInfo.member_id;
            a["u_name"] = memberInfo.member_name;
            a["avatar"] = memberInfo.member_avatar;
            a["s_id"] = memberInfo.store_id;
            a["s_name"] = memberInfo.store_name;
            a["s_avatar"] = memberInfo.store_avatar;
            socket = io(e, {
                path: "/socket.io",
                reconnection: false
            });
            socket.on("connect", function() {
                t = 1;
                socket.emit("update_user", a);
                socket.on("get_msg", function(e) {
                    o(e)
                });
                socket.on("disconnect", function() {
                    t = 0
                })
            });

            function s(e) {
                if (t === 1) {
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=member_chat&op=send_msg",
                        data: e,
                        dataType: "json",
                        success: function(e) {
                            if (e.code == 200) {
                                var t = e.datas.msg;
                                socket.emit("send_msg", t);
                                t.avatar = memberInfo.member_avatar;
                                t.class = "send_msg_picking";
                                n(t)
                            } else {
                                layer.msg(e.datas.error)
                                return false
                            }
                        }
                    })
                }
            }

            function send_goods(e) {
                if (t === 1) {
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=member_chat&op=send_msg",
                        data: e,
                        dataType: "json",
                        success: function(e) {
                            if (e.code == 200) {
                                var t = e.datas.msg;
                                socket.emit("send_msg", t);
                                t.avatar = memberInfo.member_avatar;
                                t.class = "send_msg_picking";
                            } else {
                                layer.msg(e.datas.error)
                                return false
                            }
                        }
                    })
                }
            }

            function i(e, a) {
                if (t === 1) {
                    socket.emit("del_msg", {
                        max_id: e,
                        f_id: a
                    })
                }
            }

            function o(e) {
                var t;
                for (var a in e) {
                    var s = e[a];
                    if (e[a].f_id != t_id) {
                        continue
                    }
                    t = a;
                    s.avatar = !$.isEmptyObject(userInfo.store_id) ? userInfo.store_avatar : userInfo.member_avatar;
                    s.class = "send_msg_text";
                    n(s)
                }
                if (typeof t != "undefined") {
                    i(t, t_id)
                }
            }
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=member_chat&op=get_chat_log&page=50",
                data: {
                    key: key,
                    t_id: t_id,
                    goods_id: chat_goods_id,
                    t: 30
                },
                dataType: "json",
                success: function(e) {
                    if (e.code == 200) {
                        if (e.datas.list.length == 0) {
                            return false
                        }
                        e.datas.list.reverse();
                        $("#chat_msg_html").html("");
                        for (var t = 0; t < e.datas.list.length; t++) {
                            var a = e.datas.list[t];
                            if (a.f_id != t_id) {
                                var s = {};
                                s.class = "send_msg_picking";
                                s.avatar = memberInfo.member_avatar;
                                s.t_msg = a.t_msg;
                                s.add_time = a.add_time;
                                s.f_name = a.f_name;
                                n(s)
                            } else {
                                var s = {};
                                s.class = "send_msg_text";
                                if (userInfo.store_avatar == "") {
                                    s.avatar = userInfo.member_avatar;
                                } else {
                                    s.avatar = userInfo.store_avatar;
                                }
                                s.t_msg = a.t_msg;
                                s.add_time = a.add_time;
                                s.f_name = a.f_name;
                                n(s)
                            }
                        }
                    } else {
                        layer.msg(e.datas.error)
                        return false
                    }
                }
            });
            /*发送图片*/
            $('#uploadphoto').fileupload({
                dataType: 'json',
                url: '/wap_shop/index.php?act=member_chat&op=upload&type=uploadfile',
                done: function(e, result) {
                    var data = result.result;
                    if (data.status == 1) {
                        // var attstr = '<div style="padding-bottom:15px;width:' + data.thumb_width + 'px;height:' + data.thumb_height + 'px;"><img src="' + data.thumb_name + '" style="display: block; border-radius: .17067rem; width:100%;height:100%;" /><div>';
                        var attstr = 'img@' + data.thumb_width + '@' + data.thumb_height + '@' + data.thumb_name + '';
                        s({
                            key: key,
                            t_id: t_id,
                            t_name: userInfo.member_name,
                            t_msg: attstr,
                            chat_goods_id: chat_goods_id
                        });
                        return false;
                    } else {
                        layer.msg(data.msg)
                    }
                },
                fail: function(e, data) {
                    //错误提示
                    layer.msg('发送图片信息失败，请检查文件大小是否超过1M！')
                }
            });
            $("#goods_url").click(function() {
                $.getJSON(ApiUrl + "/index.php?act=member_chat&op=get_node_info", {
                    key: key,
                    u_id: t_id,
                    chat_goods_id: chat_goods_id
                }, function(t) {
                    checkLogin(t.login);
                    if (!$.isEmptyObject(t.datas.chat_goods)) {
                        var a = t.datas.chat_goods;
                        var date = new Date();
                        var year = date.getFullYear();
                        var month = date.getMonth() + 1;
                        var day = date.getDate();
                        var hour = date.getHours();
                        var minute = date.getMinutes();
                        var second = date.getSeconds();
                        var date = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
                        var msg = '<div class="send_msg_picking"><div class="send_times">' + date + '</div><div class="imgBox"><img alt="" src="' + t.datas.member_info.member_avatar + '"></div><div class="send_main_msg_wrap"><div class="usr_name">' + t.datas.member_info.member_name + '</div></div></div>';
                        $("#chat_msg_html").append(msg)
                        var msg = '<div class="talk_product"><div class="inner_img" style="float:left;padding-right:5px;width: 40%;"><img src="' + a.pic + '" alt="" style="width:100%;"></div><div class="text" style="float:right;width: 55%;overflow: hidden;"><span class="tt">' + a.goods_name.substring(0, 10) + '</span><div class="price"><span class="now_price" style="font-size: 12px;color: #f74828;font-family: Arial, "Microsoft Yahei"; "><i>¥</i>' + a.goods_promotion_price + '</span><span class="old_price" style="font-size: 12px; color: #999; text-decoration: line-through; padding-left: 10px;font-family: Arial, "Microsoft Yahei";">¥' + a.goods_marketprice + '</span></div><a href="/wap_shop/index.php?act=goods&op=index&goods_id=' + a.goods_id + '" class="link" id="goods_url">查看详情</a></div></div>';
                        send_goods({
                            key: key,
                            t_id: t_id,
                            t_name: userInfo.member_name,
                            t_msg: 'goods_id@' + chat_goods_id,
                            chat_goods_id: chat_goods_id
                        });
                        $("#chat_msg_html").append(msg)
                        $("#anchor-bottom")[0].scrollIntoView()
                        return false;
                    }
                });
            })
            $("#submit").click(function() {
                var e = $("#msg").val();
                $("#msg").val("");
                if (e == "") {
                    layer.msg('请填写内容');
                    return false
                }
                s({
                    key: key,
                    t_id: t_id,
                    t_name: userInfo.member_name,
                    t_msg: e,
                    chat_goods_id: chat_goods_id
                });
                $("#chat_smile").addClass("hide");
                $(".nctouch-chat-con").css("bottom", "2rem")
            })
        }
        for (var i in smilies_array[1]) {
            var o = smilies_array[1][i];
            var r = '<img title="' + o[6] + '" alt="' + o[6] + '" data-sign="' + o[1] + '" src="' + resourceSiteUrl + "/js/smilies/images/" + o[2] + '" style="max-width:28px;">';
            $("#chat_smile > ul").append("<li>" + r + "</li>")
        }
        $("#open_smile").click(function() {
            if ($("#chat_smile").hasClass("hide")) {
                $("#chat_smile").removeClass("hide");
                $(".nctouch-chat-con").css("bottom", "7rem")
            } else {
                $("#chat_smile").addClass("hide");
                $(".nctouch-chat-con").css("bottom", "2rem")
            }
        });
        $("#chat_smile").on("click", "img", function() {
            var e = $(this).attr("data-sign");
            var t = $("#msg")[0];
            var a = t.selectionStart;
            var s = t.selectionEnd;
            var i = t.scrollTop;
            t.value = t.value.substring(0, a) + e + t.value.substring(s, t.value.length);
            t.setSelectionRange(a + e.length, s + e.length)
        });

        function n(e) {
            e.t_msg = c(e.t_msg);
            var t = '<div class="' + e.class + '"><div class="send_times">' + e.add_time + '</div><div class="imgBox"><img alt="" src="' + e.avatar + '"/></div><div class="send_main_msg_wrap"><div class="usr_name">' + e.f_name + '</div><div class="send_main_msg">' + e.t_msg + '</div></div></div><div class="receive_msg_text"></div>';
            $("#chat_msg_html").append(t);
            $("#anchor-bottom")[0].scrollIntoView()
        }

        function c(e) {
            var arr = e.split('goods_id@');
            var img = e.split('@');
            if (typeof smilies_array !== "undefined") {
                e = "" + e;
                for (var t in smilies_array[1]) {
                    var a = smilies_array[1][t];
                    var s = new RegExp("" + a[1], "g");
                    var i = '<img title="' + a[6] + '" alt="' + a[6] + '" src="' + resourceSiteUrl + "/js/smilies/images/" + a[2] + '" style="max-width:28px;">';
                    e = e.replace(s, i)
                }
            }
            if (img[0] == "img") {
                if (img[1] < 3) {
                    var width = (img[1]) * 1.5;
                    var height = (img[2]) * 1.5;
                } else if (img[1] > 20) {
                    var width = (img[1]) / 8;
                    var height = (img[2]) / 8;
                } else if (img[1] > 10) {
                    var width = (img[1]) / 4;
                    var height = (img[2]) / 4;
                } else if (5 < img[1] < 10) {
                    var width = (img[1]) / 2;
                    var height = (img[2]) / 2;
                } else {
                    var width = img[1];
                    var height = img[2];
                }
                var e = '<div style="width:' + width + 'rem;height:' + height + 'rem;"><img src="' + img[3] + '" style="display: block;width:100%;height:100%;" /><div>';
                e = e.replace(s, e);
            } else if (img[0] == "goods_id") {
                var a = goods_list[goods_id];
                var e = '<a href="/wap_shop/index.php?act=goods&op=index&goods_id=' + a.goods_id + '"><div class="talk_product"><div class="inner_img" style="float:left;padding-right:5px;width: 40%;"><img src="' + a.pic + '" alt="" style="width:100%;"></div><div class="text" style="float:right;width: 50%;overflow: hidden;"><span class="tt">' + a.goods_name.substring(0, 12) + '</span><div class="price"><span class="now_price" style="font-size: 12px;color: #f74828;font-family: Arial, "Microsoft Yahei"; "><i>¥</i>' + a.goods_promotion_price + '</span><span class="old_price" style="font-size: 12px; color: #999; text-decoration: line-through; padding-left: 10px;font-family: Arial, "Microsoft Yahei";">¥' + a.goods_marketprice + '</span></div></div></div></a>';
                e = e.replace(s, e);
            }
            return e
        }
    }
});