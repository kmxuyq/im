﻿/*** 公共js*****/


$(function(){

	$('#refund_pic1').change(function(){
	var url = getImgUrl(this.files[0]);
	console.log('url='+url);
			if(url){
				$('#img_0').attr('src',url);
				}
			});
		$('#refund_pic2').change(function(){
			var url = getImgUrl(this.files[0]);
			console.log('url='+url);
			if(url){
				$('#img_1').attr('src',url);
				}
			});
		$('#refund_pic3').change(function(){
			var url = getImgUrl(this.files[0]);
			console.log('url='+url);
			if(url){
				$('#img_2').attr('src',url);
				}
			});
		//建立一个客存到该file的url
		function getImgUrl(file){
		var url = null;
		if(window.createObjectURL != undefined){
			url = window.createImgUrl(file);
			}if(window.URL != undefined){
				url = window.URL.createObjectURL(file);
			}if(window.webkitURL!=undefined){
				url =  window.webkitURL.createObjectURL(file);
			}
			return url;
		}

		
	$('#state_f').on('click',function(){
			if(confirm('确认提交')){
				$.post('index.php?act=active_chick',$(this).parent().parent().serialize(),function(){
					layer.alert("审核通过！");
					window.location.reload();
				});
	        }else {
	        	window.location.reload();
	        }
			});
		
		$('#state_s').on('click',function(){
			if(confirm('确认提交')){
				$.post('index.php?act=active_chick',$(this).parent().parent().serialize(),function(){
					layer.alert("审核通过！");
					window.location.reload();
				});
	        }else {
	        	window.location.reload();
	        }
	});
	$('.state_f').on('click',function(){
		if(confirm('确认提交')){
			$.post('index.php?act=appaly_chick',$(this).parent().parent().serialize(),function(){
				layer.msg("审核通过！");
			window.location.reload();
			});
	       }else {
	        window.location.reload();
	      }
		});
		
	$('.state_s').on('click',function(){
		if(confirm('确认提交')){
			$.post('index.php?act=appaly_chick',$(this).parent().parent().serialize(),function(data){
				layer.msg("审核通过！");
				window.location.reload();
			});
	       }else {
	    	   layer.msg("审核通过！");
	        window.location.reload();
	      }
	});
	
	function del(url){
		layer.confirm('确定删除吗?',
			function(){
				$.get(url,function(data){
		 			layer.msg(data);
				window.location.reload();
			});
		});
    	}
	
    $("[nc_type='exchangebtn']").on('click',function(){
    	alert("eeee");
        $.get("index.php?act=pointvoucher&op=voucherexchange&vid="+$(this).attr('data-param'),function(data){
          layer.open({
              title:['积分兑换','background-color:#8DCE16; color:#fff;'],
              anim:true,
              type: 1,
              shadeClose: true, //点击遮罩关闭
              content: data,
              cancel:function(index){return true;}
      		});
        });
    });
    
   
    $('.adv_list').each(function() {
        if ($(this).find('.item').length < 2) {
            return;
        }

        Swipe(this, {
            startSlide: 2,
            speed: 400,
            auto: 3000,
            continuous: true,
            disableScroll: false,
            stopPropagation: false,
            callback: function(index, elem) {},
            transitionEnd: function(index, elem) {}
        });
    });
    

	var x = navigator;
	var w=window.innerWidth|| document.documentElement.clientWidth|| document.body.clientWidth;
	var h=window.innerHeight|| document.documentElement.clientHeight|| document.body.clientHeight;
	
	if(w>455){
		$('#az_1').show();
		$('#az_2').hide();
	}else if(w>280) {
		$('#az_1').hide();
		$('#az_2').show();
	}else if(w<280){
		$('#az_1').hide();
		$('.l_balanceT1').append('这种事情……so！！fk！！！！');
		}
})(jQuery)