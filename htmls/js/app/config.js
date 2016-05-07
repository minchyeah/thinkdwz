/**
 * Created by Administrator on 2015/12/31.
 */
var historyData;//初始化列表数据
var config = {
    "IMG_URL":"http://image.gigahome.cn/",
    //"TEST_IMG_URL":"http://test.gigahome.cn/",
    "HTML_URL":"html5/shucai",
    "GET_DATA_TIPS":"正在获取数据",
    "ADD_TO_CART":"加入购物车",
    "EIDT":"编辑",
    "DONE":"完成",
    "NEED_DESC":"还差¥",
    "GO_PAY":"去结算",
    "DELITEM_TITLE_TIPS":"提示",
    "DELITEM_COTENT_TIPS":"是否确认删除？",
    "CLOSE_SEHNHE_TIPS":"确定要取消本次退款申请删除？",
    "ADD_SUCCESS":"添加成功",
    "TO_SET_PAY":"设置支付密码",
    "HOLD_SET":"取消",
    "SET_PAY_SECRET":"设置",
    "PAY_CONTENT_TIPS":"您还未设置支付密码，不能使用余额支付！请设置支付密码",
    "WRITE_PWD":"请输入密码",
    "PWD_TIPS":"密码错误",
    "SELECT_ZT_TIP":"请选择常用自提点",
    "EMPTY_TIP":"内容不能为空"
};
if(window.location.host == "test.gigahome.cn"){
	config.IMG_URL = "http://test.gigahome.cn/";
}else{
	config.IMG_URL = "http://image.gigahome.cn/";
}
var url = {
	login:"/html5/shucai/login.php",
	index:"/html5/shucai/index.php",
    list:"/html5/shucai/list.php",
    details:"/html5/shucai/details.php",
    cart:"/html5/shucai/cart.php",
    ordercart:"/html5/shucai/ordercart.php",
	mycenter:"/html5/shucai/mycenter.php",
	question:"/html5/shucai/question.php",
	imgdetails:"/html5/shucai/imgdetails.php",
	position:"/html5/shucai/position.php",
	changeadres:"/html5/shucai/changeadres.php",
    refundDetails:"/html5/shucai/refunddetail.php",
    refundList:"/html5/shucai/refundlist.php",
    refundCon:"/html5/shucai/refundcon.php",
	order:"/html5/shucai/order.php",
    remainder:"/html5/shucai/remainder/remain.php",
	remainrecharge:"/html5/shucai/remainrecharge.php",
    search:"/html5/shucai/search.php",
    zt:"/html5/shucai/sysgoodslist.php",
	zdy:"/html5/shucai/zdy.php",
    set:"/html5/shucai/set.php",
	redpacketlist:"/html5/shucai/redpacket/redpacketlist.php",
	sharegift:"/html5/shucai/redpacket/sharegift.php"
}
if(localStorage.position)
{
    var pos = JSON.parse(localStorage.position),
        lng = pos.lng,
        lat = pos.lat;
}
var J = {
    version:'0.42',
    $:window.Zepto,
    settings:{
        transitionTime : 250,
        //自定义动画时的默认动画函数(非page转场动画函数)
        transitionTimingFunc : 'ease-in',
        //toast 持续时间,默认为3s
        toastDuration : 3000,
    }
};
var gj = J;
