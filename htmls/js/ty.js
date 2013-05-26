// JavaScript Document
/*!
 * http://www.liluokj.com
 * 立络科技 - 联系电话0771-3839314
 */
  /*2级导航*/
$(function(){
	$(".nav ul li").hover(function(){
		$(this).children('.drop_dwon').stop(true, true).slideDown(200);
        $(this).children('.nav ul li a').addClass("currlayout");
    }, function () {
        $(this).children('.drop_dwon').stop(true, true).slideUp(200);
        $(this).children('.nav ul li a').removeClass(); 
	})
	showTab();
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
	$.jqtab("#rczpl","#rczpr","click");
	$.jqtab("#cpnrx","#cpnrd","click");
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
/*点击切换*/
function GetObj(objName){if(document.getElementById){return eval('document.getElementById("'+objName+'")')}else{return eval('document.all.'+objName)}} 
function ISL_GoUp(){
 if(MoveLock) return; 
 clearInterval(AutoPlayObj); 
 MoveLock = true; 
 MoveTimeObj = setInterval('ISL_ScrUp();',Speed); 
} 
function ISL_StopUp(){
 clearInterval(MoveTimeObj); 
 if(GetObj('ISL_Cont').scrollLeft % PageWidth - fill != 0){ 
  Comp = fill - (GetObj('ISL_Cont').scrollLeft % PageWidth); 
  CompScr(); 
 }else{ 
  MoveLock = false; 
 } 
} 
function ISL_ScrUp(){
 if(GetObj('ISL_Cont').scrollLeft <= 0){GetObj('ISL_Cont').scrollLeft = GetObj('ISL_Cont').scrollLeft + GetObj('List1').offsetWidth} 
 GetObj('ISL_Cont').scrollLeft -= Space ; 
} 
function ISL_GoDown(){
 clearInterval(MoveTimeObj); 
 if(MoveLock) return; 
 clearInterval(AutoPlayObj); 
 MoveLock = true; 
 ISL_ScrDown(); 
 MoveTimeObj = setInterval('ISL_ScrDown()',Speed); 
} 
function ISL_StopDown(){
 clearInterval(MoveTimeObj); 
 if(GetObj('ISL_Cont').scrollLeft % PageWidth - fill != 0 ){ 
  Comp = PageWidth - GetObj('ISL_Cont').scrollLeft % PageWidth + fill; 
  CompScr(); 
 }else{ 
  MoveLock = false; 
 } 
} 
function ISL_ScrDown(){
 if(GetObj('ISL_Cont').scrollLeft >= GetObj('List1').scrollWidth){GetObj('ISL_Cont').scrollLeft = GetObj('ISL_Cont').scrollLeft - GetObj('List1').scrollWidth;} 
 GetObj('ISL_Cont').scrollLeft += Space ; 
} 
function CompScr(){ 
 var num; 
 if(Comp == 0){MoveLock = false;return;} 
 if(Comp < 0){
  if(Comp < -Space){ 
   Comp += Space; 
   num = Space; 
  }else{ 
   num = -Comp; 
   Comp = 0; 
  } 
  GetObj('ISL_Cont').scrollLeft -= num; 
  setTimeout('CompScr()',Speed); 
 }else{
  if(Comp > Space){ 
   Comp -= Space; 
   num = Space; 
  }else{ 
   num = Comp; 
   Comp = 0; 
  }
  GetObj('ISL_Cont').scrollLeft += num; 
  setTimeout('CompScr()',Speed); 
 } 
}
/*移入向上渐出*/
$(function(){
		move(".pic");
	});
	function move(name){
	$(name).hover(function(){
			var $lefty =$(this);
			var move = $lefty.outerWidth()-$lefty.find("div").height();
			$lefty.find("div[name='title']").stop(true,false).animate({ top:parseInt(move)},200);//children
		},function(){
			var $lefty =$(this);
			var move = $lefty.outerWidth();
			$lefty.find("div[name='title']").stop(true,false).animate({ top: parseInt(move) },200);
		});
}