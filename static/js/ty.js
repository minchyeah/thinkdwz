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
	sygd();//公告向上滚动
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
/*向上滚动*/
function showInTop(){
	var $obj = $(".syzxbm");  
	var scrollTimer; 
	$obj.hover(function(){ 
		clearInterval(scrollTimer); 
	},function(){ 
		scrollTimer = setInterval(function(){ 
		scrollNews($obj); 
		}, 2000 ); 
	}).trigger("mouseout"); 
}
function scrollNews(obj){ 
	var $self = obj.find("ul:first"); 
	var lineHeight = $self.find("li:first").height(); 
	$self.animate({ "margin-top" : -lineHeight +"px" },600 , function(){ 
	$self.css({"margin-top":"0px"}).find("li:first").appendTo($self); 
	}) 
} 
function sygd(){
	var $obj = $(".sygd");  
	var scrollTimer; 
	$obj.hover(function(){ 
		clearInterval(scrollTimer); 
	},function(){ 
		scrollTimer = setInterval(function(){ 
		scrollNews($obj); 
		}, 5000 ); 
	}).trigger("mouseout"); 
}
function scrollNews(obj){ 
	var $self = obj.find("ul:first"); 
	var lineHeight = $self.find("li:first").height(); 
	$self.animate({ "margin-top" : -lineHeight +"px" },600 , function(){ 
	$self.css({"margin-top":"0px"}).find("li:first").appendTo($self); 
	}) 
} 
/*移入向上渐出*/
$(function(){
		move(".cplb dl");
	});
	function move(name){
	$(name).hover(function(){
			var $lefty =$(this);
			var move = $lefty.outerHeight()-$lefty.find("dd").height();
			$lefty.find("dd[name='title']").stop(true,false).animate({ top:parseInt(move)},200);//children
		},function(){
			var $lefty =$(this);
			var move = 139;
			$lefty.find("dd[name='title']").stop(true,false).animate({ top: parseInt(move) },200);
		});
}
/*鼠标横向滑动*/
jQuery(function ($) {
    // -----------------------------------------------------------------------------------
    //   Examples
    // -----------------------------------------------------------------------------------
    // Function for populating lists with placeholder items
    function populate(container, count, offset) {
        var output = '';
        offset = isNaN(offset) ? 0 : offset;
        return $(output).appendTo(container);
    }
    // Populate list items
    $('ul[data-items]').each(function (i, e) {
        var items = parseInt($(e).data('items'), 10);
        populate(e, items);
    });
    // Activate section (it misbehaves when sly is called on hidden sections)
    $(document).on('activated', function (event, sectionId) {
        var $section = $('#' + sectionId);
        if ($section.data('examplesLoaded')) {
            return;
        }
        switch (sectionId) {
            case 'infinite':
                var $frame = $section.find('.frame'),
					$ul = $frame.find('ul').eq(0),
					$scrollbar = $section.find('.scrollbar'),
					$buttons = $section.find('.controlbar [data-action]');
                populate($ul, 10);
                $frame.on('sly:move', function (e, pos) {
                    if (pos.cur > pos.max - 100) {
                        populate($ul, 10, $ul.children().length - 1);
                        $frame.sly('reload');
                    }
                }).sly({ itemNav: 'basic', scrollBy: 1, scrollBar: $scrollbar });
                // Controls
                $buttons.on('click', function (e) {
                    var action = $(this).data('action');
                    switch (action) {
                        case 'reset':
                            $frame.sly('toStart');
                            setTimeout(function () {
                                $ul.find('li').slice(10).remove();
                                $frame.sly('reload');
                            }, 200);
                            break;
                        default:
                            $frame.sly(action);
                    }
                });
                break;
            default:
                // Call sly instances
                $section.find(".slyWrap").each(function (i, e) {
                    //if( i != 3 ) return;
                    var cont = $(this),
						frame = cont.find(".sly"),
						slidee = frame.find("ul"),
						scrollbar = cont.find(".scrollbar"),
						pagesbar = cont.find(".pages"),
						options = frame.data("options"),
						controls = cont.find(".controls"),
						prevButton = controls.find(".prev"),
						nextButton = controls.find(".next"),
						prevPageButton = controls.find(".prevPage"),
						nextPageButton = controls.find(".nextPage");
                    options = $.extend({}, options, {
                        scrollBar: scrollbar,
                        pagesBar: pagesbar,
                        prev: prevButton,
                        next: nextButton,
                        prevPage: prevPageButton,
                        nextPage: nextPageButton,
                        disabledClass: 'btn-disabled'
                    });
                    // Call sly
                    frame.sly(options);
                    // Bind controls
                    cont.find("button").click(function () {

                        var action = $(this).data('action'),
							arg = $(this).data('arg');
                        switch (action) {
                            case 'add':
                                slidee.append(slidee.children().slice(-1).clone().removeClass().text(function (i, text) { return text / 1 + 1; }));
                                frame.sly('reload');
                                break;
                            case 'remove':
                                slidee.find("li").slice(-1).remove();
                                frame.sly('reload');
                                break;
                            default:
                                frame.sly(action, arg);
                        }
                    });
                });
        }
        $section.data('examplesLoaded', true);
    });
    // -----------------------------------------------------------------------------------
    //   Page navigation
    // -----------------------------------------------------------------------------------
    // Navigation
    var $nav = $('#nav'),
		$sections = $('#sections').children(),
		activeClass = 'active';
    // Tabs
    $nav.on('click', 'a', function (e) {
        e.preventDefault();
        activate($(this).attr('href').substr(1));
    });
    // Back to top button
    $('a[href="#top"]').on('click', function (e) {
        e.preventDefault();
        $(document).scrollTop(0);
    });
    // Activate a section
    function activate(sectionID, initial) {
        sectionID = sectionID && $sections.filter('#' + sectionID).length ? sectionID : $sections.eq(0).attr('id');
        $nav.find('a').removeClass(activeClass).filter('[href=#' + sectionID + ']').addClass(activeClass);
        $sections.hide().filter('#' + sectionID).show();
        if (!initial) {
            window.location.hash = '!' + sectionID;
        }
        $(document).trigger('activated', [sectionID]);
    }
    // Activate initial section
    activate(window.location.hash.match(/^#!/) ? window.location.hash.substr(2) : 0, 1);
    // -----------------------------------------------------------------------------------
    //   Additional plugins
    // -----------------------------------------------------------------------------------
    // Trigger prettyPrint
});
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
/*小幻灯片*/
$(function () {
    initmixSlideshow();
    function initmixSlideshow() {
        var picCount = $("#mixSlideshow").children("li").length;
        $("#mixSlideshowIndex").html("1&nbsp;/&nbsp;" + picCount)
    }
})
function leftScroll() {
    var left = $("#mixSlideshow").css("margin-left");
    left = left.replace("px", "");
    var leftNum = parseInt(left);
    if (leftNum < 0) {
        var picCount = $("#mixSlideshow").children("li").length;
        var picIndex = Math.abs(leftNum) / 233;
        $("#mixSlideshow").animate({ "margin-left": (leftNum + 233) + "px" }, 300, function () { 
	})
        $("#mixSlideshowIndex").html(picIndex + "&nbsp;/&nbsp;" + picCount)
    }
}
function rightScroll() {
    var right = $("#mixSlideshow").css("margin-left");
    right = right.replace("px", "");
    var rightNum = parseInt(right);
    var picCount = $("#mixSlideshow").children("li").length;
    if ((rightNum-233) > -(picCount * 233)) {
        var picIndex;
        picIndex = Math.abs(rightNum) / 233 + 2;
        $("#mixSlideshow").animate({ "margin-left": (rightNum - 233) + "px" }, 300, function () {
        })
        $("#mixSlideshowIndex").html(picIndex + "&nbsp;/&nbsp;" + picCount)
    }
}
/*点击切换*/
function GetObj(objName){if(document.getElementById){return eval('document.getElementById("'+objName+'")')}else{return eval('document.all.'+objName)}} 
function AutoPlay(){
 clearInterval(AutoPlayObj); 
 AutoPlayObj = setInterval('ISL_GoDown();ISL_StopDown();',4000); //间隔时间 
} 
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
 AutoPlay(); 
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
 AutoPlay(); 
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