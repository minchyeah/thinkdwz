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
	showInTop();//向上滚动
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
	$.jqtab("#wzdt1t","#wzdt1n","mouseover");
	$.jqtab("#wzdt2t","#wzdt2n","mouseover");
	$.jqtab("#wzdt3t","#wzdt3n","mouseover");
	$.jqtab("#wzdt4t","#wzdt4n","mouseover");
	$.jqtab("#wzdt5t","#wzdt5n","mouseover");
	$.jqtab("#wzdt6t","#wzdt6n","mouseover");
	$.jqtab("#wzdt7t","#wzdt7n","mouseover");
	$.jqtab("#wzdt8t","#wzdt8n","mouseover");
	$.jqtab("#kcnrnts","#kcnrntx","click");
}
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
/*移入向上渐出*/
$(function(){
		move("dl.pic");
	});
	function move(name){
	$(name).hover(function(){
			var $lefty =$(this);
			var move = $lefty.outerHeight()-$lefty.find("dd").height();
			$lefty.find("dd[name='title']").stop(true,false).animate({ top:parseInt(move)},200);//children
		},function(){
			var $lefty =$(this);
			var move = $lefty.outerHeight();
			$lefty.find("dd[name='title']").stop(true,false).animate({ top: parseInt(move) },200);
		});
}
function showInTop(){
}
/*点击切换*/
function _gel(objName){
  if(document.getElementById){
    return eval('document.getElementById("'+objName+'")')
  }else{
    return eval('document.all.'+objName)
  }
}
var Roll = function(dom,pw){
  this.dom = dom;
  this.Speed = 10; //速度(毫秒) 
  this.Space = 5; //每次移动(px) 
  this.PageWidth = pw; //翻页宽度 
  this.fill = 0; //整体移位 
  this.MoveLock = false; 
  this.MoveTimeObj; 
  this.Comp = 0; 
  this.AutoPlayObj = null;
}
Roll.prototype = {
  init: function(){
    var me = this;
    _gel(this.dom + 'List2').innerHTML = _gel(this.dom + 'List1').innerHTML; 
    _gel(this.dom).scrollLeft = this.fill; 
    _gel(this.dom).onmouseover = function(){clearInterval(me.AutoPlayObj);} 
    _gel(this.dom).onmouseout = function(){me.AutoPlay();} 
    this.AutoPlay();
  },
  AutoPlay: function(){
    var me = this;
    clearInterval(this.AutoPlayObj); 
    this.AutoPlayObj = setInterval(function(){
      me.ISL_ScrUp()
      ,me.ISL_StopUp()
    },3000);
  },
  ISL_GoUp: function(){
    var me= this;
    if(this.MoveLock) return; 
    clearInterval(this.AutoPlayObj); 
    this.MoveLock = true; 
    this.MoveTimeObj = setInterval(function(){me.ISL_ScrUp()},this.Speed); 
  },
  ISL_StopUp: function(){
    clearInterval(this.MoveTimeObj); 
    if(_gel(this.dom).scrollLeft % this.PageWidth - this.fill != 0){ 
      this.Comp = this.fill - (_gel(this.dom).scrollLeft % this.PageWidth); 
      this.CompScr();
    }else{ 
      this.MoveLock = false; 
    } 
    this.AutoPlay(); 
  },
  ISL_ScrUp: function(){
    if(_gel(this.dom).scrollLeft <= 0){
      _gel(this.dom).scrollLeft = _gel(this.dom).scrollLeft + _gel(this.dom + 'List1').offsetWidth
    } 
    _gel(this.dom).scrollLeft -= this.Space ; 
  },
  ISL_GoDown: function(){
    var me = this;
    clearInterval(this.MoveTimeObj); 
    if(this.MoveLock) return; 
    clearInterval(this.AutoPlayObj); 
    this.MoveLock = true;
    this.ISL_ScrDown();
    this.MoveTimeObj = setInterval(function(){me.ISL_ScrDown()},this.Speed); 
  },
  ISL_StopDown: function(){
    clearInterval(this.MoveTimeObj); 
    if(_gel(this.dom).scrollLeft % this.PageWidth - this.fill != 0 ){ 
      this.Comp = this.PageWidth - _gel(this.dom).scrollLeft % this.PageWidth + this.fill;
      this.CompScr(); 
    }else{
      this.MoveLock = false; 
    }
    this.AutoPlay(); 
  },
  ISL_ScrDown: function(){
    if(_gel(this.dom).scrollLeft >= _gel(this.dom+'List1').scrollWidth){
      _gel(this.dom).scrollLeft = _gel(this.dom).scrollLeft - _gel(this.dom+'List1').scrollWidth;
    } 
    _gel(this.dom).scrollLeft += this.Space ; 
  },
  CompScr: function(){
    var me = this;
    var num; 
    if(this.Comp == 0){this.MoveLock = false;return;} 
    if(this.Comp < 0){ //上翻 
      if(this.Comp < -this.Space){ 
        this.Comp += this.Space; 
        num = this.Space; 
      }else{ 
        num = -this.Comp; 
        this.Comp = 0; 
      } 
      _gel(this.dom).scrollLeft -= num; 
      setTimeout(function(){me.CompScr()},this.Speed); 
    }else{ //下翻 
      if(this.Comp > this.Space){ 
        this.Comp -= this.Space; 
        num = this.Space; 
      }else{ 
        num = this.Comp; 
        this.Comp = 0; 
      } 
      _gel(this.dom).scrollLeft += num; 
      setTimeout(function(){me.CompScr()},this.Speed); 
    } 
  }
}