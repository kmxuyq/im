/*
 * @Author: Evua
 * @Date:   2016-1-4 12:00:00
 * @Last Modified by:   Evua
 * @Last Modified time: 2016-1-4 12:00:00
*/

var LoadPage = {
	box: document.getElementById("LoadPage"),
	Init: function(){		
		new imgLazyLoad("#imgPackage",LoadPage.preSuccess);
		},
	preSuccess: function(progress,src){
		LoadPage.box.querySelector(".box").innerHTML = progress+"%";
		if(progress==100){
			Html.fn();
			setTimeout(function(){
				LoadPage.box.style["display"] = "none";	
				News.Init();						
				}, 1000);
			}//if end
		}
	};//Load{}end

var Html = {
	newsEl: document.getElementById("news"),
	main1: document.getElementById("Time"),
	main2: document.getElementById("main2"),
	main3: document.getElementById("main3"),
	main4: document.getElementById("main4"),
	main5: document.getElementById("main5"),
	main6: document.getElementById("main6"),
	fn: function(){
		var newsHtml = template("newsHtml", {}),
			main1Html = template("TimeHtml", {}),
			main2Html = template("main2Html", {}),
			main3Html = template("main3Html", {}),
			main4Html = template("main4Html", {}),
			main5Html = template("main5Html", {}),
			main6Html = template("main6Html", {});			
		this.newsEl.innerHTML = newsHtml,
		this.main1.innerHTML = main1Html,
		this.main2.innerHTML = main2Html,
		this.main3.innerHTML = main3Html,
		this.main4.innerHTML = main4Html,
		this.main5.innerHTML = main5Html,
		this.main6.innerHTML = main6Html;	
		new FullScreen({
			class: "videoBox",
			origin: "center center"
			});
		new FullScreen({
			class: "Time",
			origin: "center center",
			});
		new FullScreen({
			class: "main",
			origin: "center center",
			});
		}
	};//Html{} end

var News = {
	box: document.getElementById("news"),
	video: document.getElementById("video"),
	videoBox: document.getElementById("videoBox"),
	Init: function(){
		var box = this.box;
		new IScroll(box);		
		$("#news").on("tap", function(){
			box.style["display"] = "none";
			News.videoBox.style["opacity"] = 1;
			News.playVideo();
			});
		},
	playVideo: function(){
		Music.play(), Music.pause();  //因为iphone6以下音乐audio不会自动播放，需要触发才会播放，所以这里一开始触发播放又暂停			
		News.video.play();			
		News.videoPlayEnd();
		},
	videoPlayEnd: function(){
		Global.eventUtil.addHandler(News.video, "ended", function(){			
			News.videoBox.outerHTML = "";	//因为iphone6以下要点击视频的完成，视频才会消失，所以这里直接清空
			Time.Init();
			});
		}
	};//News{} end
		
var Music = {
	deg: 0,
	isopen: !0,  //!0==true,!1==false
	speed: 3,
	audio1: document.getElementById("audio1"),
	audio2: document.getElementById("audio2"),
	btnEl: document.getElementById("musicBtn"),
	play: function(){
		Music.audio1.play(),
		Music.audio2.play();
		},
	pause: function(){
		Music.audio1.pause(),
		Music.audio2.pause();
		},
	hide: function(){
		Music.audio1.pause(),
		Music.btnEl.style["display"] = "none";
		},
	timer: function(){
		return setInterval(function(){//每隔80毫秒旋转3deg
			Music.deg += Music.speed, 
			Music.btnEl.style["-webkit-transform"] = "rotate("+Music.deg+"deg)";
			}, 80)
		},
	Init: function(){
		var timer = Music.timer();
		Music.btnEl.style["display"] = "block";
		Music.audio1.play();
		Music.btnEl.onclick = function(){
			Music.isopen ? (clearInterval(timer), Music.isopen = !1, Music.audio1.pause()) : (Music.isopen = !0, timer = Music.timer(), Music.audio1.play());//条件操作符的极致、优雅用法
			};
		}
	};//Music{} end	


var Time = {
	box: document.getElementById("Time"),
	timeThrough: function(){
		var Timefun, Timing, thruBtn, Argument, timer;	
		
		Timefun = function(opt){
			this.data = ["year0.png", "year1.png", "year2.png", "year3.png", "year4.png", "year5.png", "year6.png", "year7.png", "year8.png","year9.png"];
			this.Init(opt);
			};
		Timefun.prototype = {
			Init: function(opt){
				var data = this.data, len = data.length-1,
					speed = opt.speed,
					year = opt.year.toString().split(""),
					box = document.getElementById(opt.id),
					box1 = box.querySelector(".item1"), box2 = box.querySelector(".item2"), box3 = box.querySelector(".item3"), box4 = box.querySelector(".item4"),
					i1 = box1.dataset.index, i2 = box2.dataset.index, i3 = box3.dataset.index, i4 = box4.dataset.index,
					quan=0,
					timer1,timer2,timer3,timer4;
					
				timer1 = setInterval(function(){
					if(i1<len){ i1++; }else{ i1 = 0;}
					box1.dataset.index = i1,
					box1.style["background-image"] = "url(http://m.gellefreres.com/wap_shop/templates/default/images/weixin/img/"+data[i1]+")";
					},speed);
				timer2 = setInterval(function(){
					if(i2<len){ i2++; }else{ i2 = 0;}
					box2.dataset.index = i2,
					box2.style["background-image"] = "url(http://m.gellefreres.com/wap_shop/templates/default/images/weixin/img/"+data[i2]+")";
					},speed);
				timer3 = setInterval(function(){
					if(i3<len){ i3++; }else{ i3 = 0;}
					box3.dataset.index = i3,
					box3.style["background-image"] = "url(http://m.gellefreres.com/wap_shop/templates/default/images/weixin/img/"+data[i3]+")";
					},speed);
				timer4 = setInterval(function(){
					if(i4<len){ i4++; }else{ i4 = 0; quan++; }
					box4.dataset.index = i4,
					box4.style["background-image"] = "url(http://m.gellefreres.com/wap_shop/templates/default/images/weixin/img/"+data[i4]+")";
					if(quan==opt.quan && i4==year[3]){		
						opt.fn();
						box1.dataset.index = year[0], box2.dataset.index = year[1], box3.dataset.index = year[2], box4.dataset.index = year[3];
						box1.style["background-image"] = "url(http://m.gellefreres.com/wap_shop/templates/default/images/weixin/img/"+data[year[0]]+")", box2.style["background-image"] = "url(http://m.gellefreres.com/wap_shop/templates/default/images/weixin/img/"+data[year[1]]+")", box3.style["background-image"] = "url(http://m.gellefreres.com/wap_shop/templates/default/images/weixin/img/"+data[year[2]]+")";
						clearInterval(timer1), clearInterval(timer2), clearInterval(timer3), clearInterval(timer4);
						return;		
						}
					},speed);
				
				}
			};//Timefun{} end
		Timing = {
			index: 0,
			fn: function(opt){			
				var len = opt.length-1,
					el = Time.box.querySelector(opt[Timing.index].class),
					i = el.querySelector("i");	
				Timing.index++,
				i.classList.add("star"),
				el.classList.add("bounceIn");	
				Global.eventUtil.addHandler(i, "webkitAnimationEnd", function(){								
					if(Timing.index>len){ thruBtn.fn(); }
					else{
						Argument.year = opt[Timing.index].year;
						Argument.fn = function(){
							Timing.fn(opt);
							};
						timer = new Timefun(Argument);						
						}				
					});			
				}
			};//Timing{} end	
		thruBtn = {
			box: document.getElementById("thruBtn"),
			fn: function(){		
				var flag = false,
					boxEl = thruBtn.box,
					item1 = boxEl.querySelector(".item1"),
					item3 = boxEl.querySelector(".item3");
				boxEl.classList.add("bounceIn");
				Global.eventUtil.addHandler(boxEl, "touchstart", function(){
					if(flag==true) return;
					flag = true;
					item1.style["-webkit-animation"] = "rotate .6s linear 2 both",
					item3.style["-webkit-animation"] = "flash .6s linear 2 both";			
					});
				Global.eventUtil.addHandler(item1, "webkitAnimationEnd", function(){
					setTimeout(function(){
						$("#Time").fadeOut(800);
						Main.fn2();
						},300);			
					});
				}
			};//thruBtn{} end		
		Argument = {
			id: "timeBox",
			speed: 75,
			quan: 2, //跑几圈
			year: 2016,  //目标年份
			fn: function(){
				Timing.fn([
					{class: ".timeFrame_1", year: 2016},
					{class: ".timeFrame_2", year: 1927},
					{class: ".timeFrame_3", year: 1878},
					{class: ".timeFrame_4", year: 1826}
					]);
				}
			};//Argument{} end
		timer = new Timefun(Argument);
		},
	Init: function(){
		$("#Time").fadeIn(800, function(){			
			Music.Init();
			Time.timeThrough()
			});
		}
	};//Time{} end


var Main = {
	fn2: function(){
		$("#main2").fadeIn(800, function(){
			new PinchFun({
				id: "main2",
				origin: "160px 185px",
				fn: function(){
					$("#main2").fadeOut(800);
					Main.fn3();
					}
				});
			});
		},
	fn3: function(){
		$("#main3").fadeIn(800, function(){
			new PinchFun({
				id: "main3",
				origin: "280px 400px",
				fn: function(){
					$("#main3").fadeOut(800);
					Main.fn4();
					}
				});
			});
		},
	fn4: function(){
		$("#main4").fadeIn(800, function(){
			new PinchFun({
				id: "main4",
				origin: "85px 362px",
				fn: function(){
					$("#main4").fadeOut(800);
					Main.fn5();
					}
				});
			});
		},
	fn5: function(){
		$("#main5").fadeIn(800, function(){
			new PinchFun({
				id: "main5",
				origin: "120px 130px",
				fn: function(){
					$("#main5").fadeOut(800);
					Main.fn6();
					Music.hide();//四个世纪都轮播完之后暂停轮回音乐，最后一页放唐嫣语音
					}
				});
			});
		},
	fn6: function(){
		$("#main6").fadeIn(800, function(){ Music.audio2.play();});
		}
	};//Main{} end


