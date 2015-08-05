// JavaScript Document
/*2级导航*/
$(function(){
	$(".nav ul li").hover(function(){
		$(this).children('.drop_dwon').stop(true, true).slideDown(200);
        $(this).children('.nav ul li a').addClass("currlayout");
    }, function () {
        $(this).children('.drop_dwon').stop(true, true).slideUp(200);
        $(this).children('.nav ul li a').removeClass(); 
	})
	//首页内容判断高度
	$(".synr").css("height", $(window).height() + "px");
})
 jQuery(function(){
	$(".CategoryTree > ul > li").hover(function(){
		$(this).addClass("selected");
		$(this).children("a:eq(0)").addClass("h2-tit");
		$(this).children("ul").show();
	},function(){
		$(this).removeClass("selected");
		$(this).children(".tit").removeClass("h2-tit");
		$(this).children("ul").hide();
	})
	showTab();
	//点击弹出
	$(".but").click(function(){
		var name = $(this).attr("href");
    	$("#faqbg").css({display:"block",height:$(document).height()});
    	var yscroll =document.documentElement.scrollTop;
    	$("div[name='"+name+"']").css("top","17%");
    	var $Object = $(this).attr("rel");
		if($Object==0 || $Object==1){
        	if ($Object) {
            	$.jqtab("#hydlt", "#hydlx", "click", $Object);
            	$(this).attr("checked", false);
        	}
		}
   		$("div[name='"+name+"']").css("display","block");
   		document.documentElement.scrollTop=0;
		document.body.scrollTop=0;
		return false;
   });
   
		   
   //公共关闭
  $(".close").click(function(){
  	$("#faqbg").css("display","none");
	var name = $(this).attr("rel");
  	$("div[name='"+name+"']").css("display","none");
 });
 
//弹出结束
})
//区块切换
function showTab(){
	jQuery.jqtab = function(tabtit,tab_conbox,shijian,index) {
		$(tab_conbox).find("ol").hide();
		$(tabtit).find("li:first").show(); 
		$(tab_conbox).find("ol:first").show();
	
		$(tabtit).find("li").bind(shijian,function(){// addClass   removeClass
		  $(this).removeClass("ztwdj").siblings("li").addClass("ztwdj"); 
			var activeindex = $(tabtit).find("li").index(this);
			$(tab_conbox).children().eq(activeindex).show().siblings().hide();
			return false;
		});
		if(index){
			$(tabtit).find("li").eq(index).trigger(shijian);
		}
	};
	/*调用方法如下：*/
	$.jqtab("#cpnrx","#cpnrd","click");
}
/*幻灯片*/
$(function(){
		var curr = 0;
		var len = $(".box01 span").length; //获取焦点图个数
		$(".jsNav .trigger").each(function(i){
			$(this).click(function(){
				curr = i;
				$(".box01 span").eq(i).fadeIn("slow").siblings("span").css("display","none");
				$(this).siblings(".trigger").removeClass("imgSelected").end().addClass("imgSelected");
				return false;
			});
		});
		
		var pg = function(flag){
			if (flag) {
				if (curr == 0) {
					todo = len-1;
				} else {

					todo = (curr - 1) % len;
				}
			} else {
				todo = (curr + 1) % len;

			}
			$(".jsNav .trigger").eq(todo).click();
		};
		$("#prev").click(function(){
			pg(true);
			return false;
		});
		$("#next").click(function(){
			pg(false);
			return false;
		});
		var timer = setInterval(function(){
			todo = (curr + 1) % len;
			$(".jsNav .trigger").eq(todo).click();
		},8000);
		$(".box01,.jsNav span,#prev,#next").hover(function(){
				clearInterval(timer);
			},
			function(){
				timer = setInterval(function(){
					todo = (curr + 1) % len;
					$(".jsNav .trigger").eq(todo).click();
				},8000);
			}
		);
});
/*小图看大图*/
$(document).ready(function(){
	$(".group1").colorbox({rel:'group1'});
	$(".group2").colorbox({rel:'group2'});
	$(".group3").colorbox({rel:'group3'});
	$("#click").click(function(){ 
		$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
		return false;
	});
});