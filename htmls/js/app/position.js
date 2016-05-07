;(function(){
	//initialize position
	var pos={
		"map":null,
		"geolocation":null,
		"lat":null,
		"lng":null,
		"cityName":null,
		sid:null,//自提点id
		idx:null,//索引值
		timeFunc:null
	};
	function init()
	{
		if(!J.Service.getCacheData("nopopup"))
		{

			api.posSet(openid,"","",function(data){
				if(data.status!=401)
				{
					var url = window.location.origin+"/html5/shucai/index.php/id="+data.shop_id;
					J.Service.saveCacheData("shopid",data.shop_id);
					window.location.href= url;
					return false;
				}
				initFunc()
			})
		}
		else
		{
			J.Service.remove("nopopup");
			initFunc()
		}
		//搜索按钮事件
		$(".pos-search").on("click",".btn-search",function(){
			var searchName = $(".input-search").val();
			if(searchName<=0)
			{
				J.showToast(config.EMPTY_TIP);
				return false;
			}
			$(".sel-list").empty();
			toSearchDt(searchName);
			// api.getShopAddress(pos.lat,pos.lng,searchName,function(dt){
			// 	$(".sel-list").empty();
			// 	if(parseInt(dt.status)==401){
			// 		$(".search-list").empty();
			// 		J.showToast(dt.msg);
			//
			// 		return false;
			// 	}
			// 	showCityList(dt,$(".search-list"));
			// 	fadeinDiv();
			// })
		});
			// var send;
			// $(".input-search").on("input",function(){
			// 	var searchName = $(this).val();
			// 	clearTimeout(send);
			// 	send = setTimeout(function(){
			// 		toSearchDt(searchName)
			// 	},1000)
			// });
	}
	function initFunc()
	{
		$(".btn-set").on("tap",function () {
			if(pos.sid!=null&&pos.idx!=null)
			{

				api.posSet(openid,"",pos.sid,function(data){
					if(data.status!=401)
					{
						var url = window.location.origin+"/html5/shucai/index.php/id="+pos.sid;
						window.location.href= url;
						J.Service.saveCacheData("shopid",pos.sid);
						J.Service.saveCacheData("adresParam",cityListArr[pos.idx]);
					}
				})
			}
			else
			{
				J.showToast(config.SELECT_ZT_TIP);
			}
		});
		var shopids = J.Service.getCacheData("shopid");
		if(shopids&&shopids.data)
		{
			pos.sid = shopids.data;
			var url = window.location.origin+"/html5/shucai/index.php/id="+pos.sid;
			//window.location.href= url;
		}
		//app第一次进入专享
		if(typeof(mid)!="undefined")
		{
			pos.lat = lat;
			pos.lng = lng;
			pos.cityName = city;
			fadeinDiv();
			getAreaData();
			return false;
		}
		//如果存在经度纬度
		if(localStorage.position&&J.Service.getCacheData("nopopup"))
		{
			var position = JSON.parse(localStorage.position);
			pos.lat = position.lat;
			pos.lng = position.lng;
			J.Service.remove("nopopup");
			getAreaData();
			return false;
		}

		getLocation();

		$(".btn-getpos").on("click",function(){
			position();
		});
		
	}
	function toSearchDt(searchName)
	{

		api.getShopAddress(pos.lat,pos.lng,searchName,function(dt){
			if(parseInt(dt.status)==401)
			{
				//$(".sel-list").empty().html('<p class="pos-resultdesc cor-9 displayhidden">定位失败或者该小区没有开通取货点</p>');
				//$(".search-list").empty();
				//$(".empty").show();
				//$("#position_section").css("background-color","#fff");
				//$(".btn-set").hide();
				//return false;
				$(".sel-list").empty().html('<p class="pos-resultdesc cor-9">搜索失败或者该小区没有开通取货点</p>')
				J.showToast(dt.msg);
				return false;
			}

			$("#position_section").css("background-color","");
			showCityList(dt,$(".search-list"));
			fadeinDiv();
		})
	}
	init();
	function position()
	{
		getLocation();
		//调用浏览器定位服务
		// var map = new AMap.Map('container', {
		// 	resizeEnable: true
		// });
        //
		// map.plugin('AMap.Geolocation', function() {
		// 	pos.geolocation = new AMap.Geolocation({
		// 		enableHighAccuracy: true,//是否使用高精度定位，默认:true
		// 		timeout: 10000,
		// 		buttonOffset: new AMap.Pixel(10, 20),
		// 		zoomToAccuracy: true,
		// 		buttonPosition:'RB'
		// 	});
		// 	map.addControl(pos.geolocation);
		// 	pos.geolocation.getCurrentPosition();
		// 	AMap.event.addListener(pos.geolocation, 'complete', onComplete);//返回定位信息
		// 	AMap.event.addListener(pos.geolocation, 'error', onError);      //返回定位出错信息
		// });
	}
	//解析定位结果
	function onComplete(data)
	{
		// pos.lng = data.position.getLng();//纬度
		// pos.lat = data.position.getLat();//经度
		// showCityInfo(pos.lng,pos.lat);
		// window.localStorage.position = JSON.stringify({lng:pos.lng,lat:pos.lat});
	}
	function getLocation(){
		//部分安卓手机无法定位
		// pos.timeFunc = setTimeout(function(){
		// 	fadeoutDiv();
		// },4000);
		if (navigator.geolocation){
			navigator.geolocation.getCurrentPosition(showPosition,showError,{enableHighAccuracy:true,maximumAge:1000,timeout:8000});
		}else{
			J.showToast("浏览器不支持地理定位。");
		}
	}
	function showPosition(position){
		clearTimeout(pos.timeFunc);
		pos.lat = position.coords.latitude; //纬度
		pos.lng = position.coords.longitude; //经度
		showCityInfo(pos.lng,pos.lat)
	}
	function showError(error){
		fadeinDiv();
		toSearchDt("取货");
		switch(error.code) {
			case 1:
			//	J.showToast("定位失败,用户拒绝请求地理定位");
				break;
			case 2:
			//	J.showToast("定位失败,位置信息是不可用");
				break;
			case 3:
			//	J.showToast("定位失败,请求获取用户位置超时");
				break;
			default:
			//	J.showToast("定位失败,定位系统失效");
				break;
		}
	}
	//获取用户所在城市信息
	function showCityInfo(lng,lat) {
		//var map = new AMap.Map("container", {
		//	resizeEnable: true,
		//	center: [lng,lat],
		//	zoom: 13
		//});
		////实例化城市查询类
		//var citysearch = new AMap.CitySearch();
		////自动获取用户IP，返回当前城市
		//citysearch.getLocalCity(function(status, result) {
		//	if (status === 'complete' && result.info === 'OK') {
		//		if (result && result.city && result.bounds) {
		//			//pos.cityName = result.city;
		//			pos.cityName = "深圳市";
		//		}
		//
		//	}
		//});
		setTimeout(showMap(pos.lng,pos.lat),500);
	}
	//解析定位错误信息
	function onError(data) {
		fadeinDiv();
	}
	/**
	 * get lat and log
	 */
	function showMap(lng,lat)
	{
		getAreaData();
		$(".citylist>li").text(pos.cityName);
		$(".citylist").on("click",function(){
			$("#city-loading").addClass("active");
			$("#position_section").removeClass("active");
		});
		$(".city-list>li").on("click",function(){
			var me = $(this),
				idx = me.index();
			pos.cityName = me.eq(idx).text();
			$("#city-loading").removeClass("active");
			$(".sel-list").empty();
			getAreaData();
		});
	}
	/**
	 * area data
	 * @return {[type]} [description]
	 */
	function getAreaData()
	{
		api.getAdress(pos.lat,pos.lng,pos.cityName,function(dt){
			//第一个自提点出现直接跳转
			//J.Service.remove("shopid");
			toSearchDt("取货");
			if(parseInt(dt.status)==401)
			{
				fadeoutDiv();
				return false;
			}
			if(dt)
			{
				showCityList(dt);
			}

			//取消自动跳转
			// if(dt.data[0]&& dt.data[0].id)
			// {
			// 	J.Service.remove("nopopup");
			// 	J.Service.remove("nppopup");
			// 	var url = window.location.origin+"/html5/shucai/index.php/id="+dt.data[0].id;
			// 	window.location.href= url;
			// 	return false;
			// }
			fadeinDiv();
			//showCityList(dt);
		});
	}

	/***
	 *hidden
	 */
	function fadeoutDiv()
	{
		//$(".pos-resultdesc").removeClass("displayhidden");
		$("#pos-loading").removeClass("active");
		$("#position_section").addClass("active").css("background-color","#fff");
		$(".empty").hide();
		$(".sel-list").empty().html('<p class="pos-resultdesc cor-9">定位失败或者该小区没有开通取货点</p>');
		$(".btn-set").hide();
	}
	/**
	 * show
	 * @return {[type]} [description]
	 */
	function fadeinDiv()
	{
		$("#pos-loading").removeClass("active");
		$("#position_section").addClass("active");
		$(".empty").hide();
		$(".btn-set").show();
	}
	/**
	 * city list to show
	 * @return {[type]} [description]
	 */

	function showCityList(dt,el)
	{
		var list= el||$(".sel-list");
		list.empty();
		var data = dt.data;
		/*var arr = [{"name":"xinhuhuayuan","id":"1","address":"高尔夫大厦1001","pic":""},{"name":"xinhuhuayuan","address":"高尔夫大厦1001","pic":""}]*/
		var arr = [];
		cityListArr = data;
		//获取城市名
		if(data[0].city)
		{
			pos.cityName = data[0].city;
			$(".citylist>li").text(pos.cityName);
		}
		for(var j=0;j<data.length;j++)
		{
			arr[j] = {};
			arr[j].name = data[j].name;
			arr[j].address = data[j].city+data[j].area+data[j].address;
			arr[j].id = data[j].id;
		}
		var len = arr.length;
		var str = "";
		if(el)
		{
			str +="<li class='fn-color'>"+pos.cityName+"取货点</li>";
		}
		else
		{
			str +="<li class='fn-color'>已定位到您附近取货点</li>";
		}

		for(var i=0;i<len;i++)
		{
			var t = '<p class="area-desc"><span>'+arr[i].name+'</span><span class="fn-color">'+arr[i].address+'</span></p><div data-checkbox="checked" data-type="1" class="search-ckd"> <i class="icon checkbox-unchecked"></i></div>'
			if(pos.sid==arr[i].id && !el)
			{
				pos.sid==arr[i].id;
				pos.idx = i;
				var txt ='<li class="item" data-idx="'+i+'" data-id="'+arr[i].id+'"><p class="area-desc"><span>'+arr[i].name+'</span><span class="fn-color">'+arr[i].address+'</span></p><div data-checkbox="checked" data-type="1" class="search-ckd"> <i class="icon checkbox-checked"></i></div></li>';
				str = str.concat(txt);
			}
			else
			{
				str+='<li class="item" data-idx="'+i+'" data-id="'+arr[i].id+'">'+t+'</li>';
			}
		}
		if(el&&len>=3)
		{
			str+='<li class="item h80"></li>'
		}
		list.html(str);
		$(".item").each(function(){
			$(this).on("click",domainOnClick)
		});
	}
	/**
	 * search data to show
	 * @return {[type]} [description]
	 */
	function searchData()
	{
		showCityList();
	}
	/**
	 * click area
	 */
	function domainOnClick()
	{
		var me = $(this);
		pos.sid = me.data("id");
		pos.idx = me.data("idx");
		if(me.find("i").hasClass("checkbox-unchecked"))
		{
			$(".item").find("i").removeClass("checkbox-checked").addClass("checkbox-unchecked");
			me.find("i").addClass("checkbox-checked").removeClass("checkbox-unchecked");
		}
	}
})()