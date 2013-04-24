// JavaScript Document
/*!
 * http://www.liluokj.com
 * 立络科技 - 联系电话0771-3839314
 */
jQuery(document).ready(function(){
	jQuery(".nav ul li").hover(
		function(){
			jQuery(this).children(".drop_dwon").slideDown(200);
			jQuery(this).children(".nav ul li a").attr("id","currlayout")
		},
		function(){
			jQuery(this).children(".drop_dwon").slideUp(100);
			jQuery(this).children(".nav ul li a").attr("id","")
		}
	);
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
		
		return false;
   });
   
		   
   //公共关闭
  $(".close").click(function(){
  	$("#faqbg").css("display","none");
	var name = $(this).attr("rel");
  	$("div[name='"+name+"']").css("display","none");
 });
 
//弹出结束
});

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
	$.jqtab("#hydlt","#hydlx","click");
	$.jqtab("#pxdj","#pxxz","click");
	$.jqtab("#dmycdt","#dmycdn","click");
	$.jqtab("#qhcst","#qhcsb","click");
}
/*设为首页*/
function SetHome(obj){
    try{
        obj.style.behavior='url(#default#homepage)';
        obj.setHomePage("http://" + window.location.host);
    }
    catch(e){
        alert("抱歉!您的浏览器不支持直接设为首页。您可通过浏览器 工具->选项->使用当前页->确定，完成设为首页。");
    }
}
/*添加收藏夹*/
function AddUrl(){
    try{
        window.external.addFavorite(document.location.href,document.title);
    }
    catch(e){
        try{
            window.sidebar.addPanel(document.title,document.location.href,"");
        }
        catch(e){
            alert("抱歉!您的浏览器不支持直接添加收藏。您可使用 Ctrl+D 进行添加收藏");
        }
    }
}
/*回到顶部*/
$(function() {
	$.fn.manhuatoTop = function(options) {
		var defaults = {			
			showHeight : 150,
			speed : 1000
		};
		var options = $.extend(defaults,options);
		$("body").prepend("<div id='totop'><a>返回</a></div>");
		var $toTop = $(this);
		var $top = $("#totop");
		var $ta = $("#totop a");
		$toTop.scroll(function(){
			var scrolltop=$(this).scrollTop();		
			if(scrolltop>=options.showHeight){				
				$top.show();
			}
			else{
				$top.hide();
			}
		});	
		$ta.hover(function(){ 		
			$(this).addClass("cur");	
		},function(){			
			$(this).removeClass("cur");		
		});	
		$top.click(function(){
			$("html,body").animate({scrollTop: 0}, options.speed);	
		});
	}
});
/*小图看大图*/
$(document).ready(function(){
			$(".group1").colorbox({rel:'group1'});			
			$("#click").click(function(){ 
				$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
				return false;
			});
		});		
/*回到顶部*/	
$(function (){
	$(window).manhuatoTop({
		showHeight : 100,//设置滚动高度时显示
		speed : 500 //返回顶部的速度以毫秒为单位
	});
	//alert($("#hxtdk").width());
});
/*移入弹出*/
$(function(){
    $(".fytjsp li:odd").addClass("even");
    $(".fytjsp li").each(function(){
        $this = $(this);
        $this.mouseover(function(){
          if($(this).hasClass("cur")){return true;}
         $(this).siblings("li").removeClass("cur");
         $(this).siblings("li").find("h4").css("display", "none");
		 $(this).siblings("li").find("h1").css("display", "none");
		 $(this).siblings("li").find("dl").css("display", "none");
         $(this).siblings("li").find("span").css("display", "none");
         $(this).addClass("cur");
         $(this).find("h4").css("display", "block");
		 $(this).find("h1").css("display", "block");
         $(this).find("span").css("display", "block");
          return false;
        });
    });
    $(".fytjsp li h4,.fytjsp li h4 h1,.fytjsp li h4 dl,.fytjsp li span").css("display", "none");
    $(".fytjsp li.cur h4,.fytjsp li.cur h4 h1,.fytjsp li.cur h4 dl,.fytjsp li.cur span").css("display", "block");
    
    $(".tabtitle li").each(function(){
        $this = $(this);
        $this.mouseover(function(){
            $(this).siblings("li").removeClass("cur");
            $(this).addClass("cur");
            var $cur_id_num = $(this).attr("id").slice(-1);
            $(".fytjsp").removeClass("cur_fytjsp");
            $(".fytjsp" + $cur_id_num).addClass("cur_fytjsp");
        });
    });
});