/**
 * Created by Administrator on 2016/1/1.
 */

;(function(){
    var _baseUrl = window.location.origin+"/index.php/";
    //  var _baseUrl = "http://121.41.100.58/index.php/";
    var _get = function(url,param,success,error)
    {
        _ajax("GET",url,param,success,error);
    };
    var _post = function(url,param,success,error)
    {
        _ajax("POST",url,param,success,error);
    }
    var _ajax = function(type,url,param,success,error)
    {
        url = _baseUrl+url;
        if(location.protocol=="http:")
        {
            if(type=="GET")
            {
                if((window.location.origin).indexOf("api")>0||(window.location.origin).indexOf("test")>0)
                {
                    url = url+"?"+$.param(param);
                }
                else
                {
                    url = "/proxy?url="+url+"?"+$.param(param);
                }
            }
            else
            {
                if((window.location.origin).indexOf("api")>0||(window.location.origin).indexOf("test")>0)
                {
                    url = url;
                }
                else
                {
                    url = '/proxy?url='+url;
                }
            }
        }
        var options = {
            url:url,
            type:type||"GET",
            data:param,
            timeout:120000,
            success:success,
            cache:type=="GET"?false:true,
            error:function(xhr,type)
            {
                if(error)
                {
                    error(xhr,type);
                }
                else
                {
                    _parseError(xhr,type,url);
                }
            },
            dataType:"json"
        }
        $.ajax(options);
    }
    function _parseError(xhr,type,url)
    {
        if(J.hasPopupOpen)
        {
            J.hideMask();
        }
        if(type=="timeout")
        {
        //    J.showToast("连接超时","error");
        }
        else if(type=="error")
        {
            var data;
            try{
                data = JSON.parse(xhr.responseText);
                if(data.code && data.message)
                {
                    J.showToast(data.message,"error");
                }
            }
            catch(e)
            {
            //    J.showToast("连接失败","error");
            }
        }
        else
        {
        //    J.showToast("连接失败","error");
        }
    }
    var apiFlag = true;
    window.api = {
        "index":function(id,lng,lat,success,error){
            //首页数据
            _post("Vegetables/indexV1_2",{id:id,lng:lng,lat:lat},success,error);
        },
        "getGoodsList":function(openid,id,lng,lat,success,error)
        {
            //商品列表
            _post("Vegetables/shop_detailV1_2",{mid:openid,id:id,lng:lng,lat:lat},success,error);
        },
        "getGoodsDetail":function(sid,success,error)
        {
            //商品详情
            _post("Vegetables/goods_detailV1_2",{id:sid},success,error);
        },
        "getGoodsImgDetail":function(sid,success,error)
        {
            //图文详情
            _post("Vegetables/goods_detail_contentV1_2",{id:sid},success,error);
        },
        "getOrdercart":function(sid,openid,price,cartlist,flag,success,error)
        {
            //确认订单 shopid 商店id mid 用户id
            _post("Vegetables/order_confirm_appV1_2",{shop_id:sid,mid:openid,shop_total_price:price,cartlist:cartlist,is_check_goods:flag},success,error);
        },
        "submitOrder":function(sid,mid,phone,name,time,text,price,cartlist,fullReduce,fullgive,orderFirst,shopName,address,otype,time2,addressid,success,error)
        {
            /***
             * sid 商店id
             * mid 用户id
             * phone收货人手机号
             * name收货人名
             * ordertype支付类型
             * time提货时间类型
             * price支付价格
             * cartlist 购物车列表[{goods_id:1,num:1}]
             */
            if(!apiFlag)
            {
                return false;
            }
            _post("Vegetables/order_submitV1_2",{
                shop_id:sid,
                mid:mid,
                phone:phone,
                name:name,
                take_goods_time:time,
                remark:text,
                payprice:price,
                address_id:addressid,
                cartlist:JSON.stringify(cartlist),
                is_full_minus:fullReduce,
                is_full_give:fullgive,
                is_first_order:orderFirst,
                shop_name:shopName,
                address:address,
                ordertype:otype,
                take_goods_time2:time2
            },success,error);
        },

        "getShopAdress":function(lng,lat,success,error)
        {
            //确认订单 shopid 商店id mid 用户id 

            _post("Vegetables/shop_positionV1_2",{lng:lng,lat:lat},success,error);
        },
        "wxPay":function(pid,orderType,openid,price,success,error)
        {
            //微信支付
            //if(apiFlag)
            //{
            //    return false;
            //}
            _post("Weiapi_legou/unified_orderV3",{pid:pid,order_type:orderType,mid:openid,price:price},success,error);
        },
        "login":function(sid,openid,userid,pswd,phone,name,url,type,success,error){

            //openid  微信 userid手机号 phonecode验证码 nickname微信昵称 headimgurl微信头像地址login_type登录类型  1为手机快速登录，2为小区管家账号登录
             _post("Vegetables/personal_loginV1_2",{shop_id:sid,mid:openid,userid:userid,password:pswd,phonecode:phone,nickname:name,headimgurl:url,login_type:type},success,error)
        },
        "getCode":function(mid,userid,success,error){
            _post('Member/open_getPhoneCodeV5_0',{mid:mid,phone:userid},success,error);
        },
        "orderlist":function(page,openid,success){
            _post('Vegetables/orderQuery',{page:page,mid:openid},success,error);
        },
        "orderdetails":function(pid,mid,success){
            _post('Vegetables/order_detail',{pid:pid,mid:mid},success,error);
        },
        "orderdele":function(pid,mid,success){
            _post('Vegetables/delOrder',{pid:pid,mid:mid},success,error);
        },
        "getAdress":function(lat,lng,name,success,error){
			//自提点定位附近
            _post("Vegetables/shop_positionV1_2",{lng:lng,lat:lat},success,error);
           // _post("Vegetables/shop_position",{lng:log,lat:lat},success,error);
        },
		"getShopAddress":function(lat,lng,searchname,success,error){
			//搜索自提点
			_post("Vegetables/shop_searchV1_2",{lng:lng,lat:lat,name:searchname},success,error);
		//	_post("Vegetables/shop_search",{lng:log,lat:lat,name:searchname},success,error);
		},
		"search":function(sid,txt,success,error){
			//商品搜索
			_post("Vegetables/goods_searchV1_2",{shop_id:sid,name:txt},success,error);
		},
		"refund":function(id,mid,num,price,type,reason,success,error){
			//申请退款
			//id订单id，mid用户id，num退款数量，price退款金额，type退款类型1，reason退款原因
			_post("Vegetables/refund_applyV1_2",{id:id,mid:mid,refund_num:num,refund_price:price,refund_type:type,refund_reason:reason},success,error)
		},
		"refundList":function(mid,pagenum,success,error){
			//售后退款列表
			_post("Vegetables/refund_listV1_2",{mid:mid,page:pagenum},success,error);
		},
		"orderDetail":function(mid,pid,success,error)
		{
			_post("Vegetables/order_detailV1_2",{mid:mid,pid:pid},success,error);
		},
		"refundApply":function(id,mid,num,price,type,txt,success,error){
            //
			_post("Vegetables/refund_applyV1_2",{id:id,mid:mid,refund_num:num,refund_price:price,refund_type:type,refund_reason:txt},success,error);
		},
        "refundCancel":function(id,mid,success,error){
            _post("Vegetables/refund_cancelV1_2",{id:id,mid:mid},success,error);
        },
        "sysGoodsList":function(sid,id,type,success,error){
			//多商品列表广告
            _post("Vegetables/system_goods_listV1_2",{shop_id:sid,id:id,type:type},success,error);
        },
		"sysGoodsDetail":function(id,type,success,error){
			//单商品广告
			_post("Vegetables/system_goods_detailV1_2",{id:id,type:type},success,error)
		},
        "balance":function(uid,success,error)
        {
            //余额
            _post("Vegetables/personal_balanceV1_2",{mid:uid},success,error)
        },
        "setPwd":function(mid,pwd,userid,code,success,error)
        {
        	//设置手机密码
        	_post("Vegetables/paypwd_setV1_2",{mid:mid,paypwd:pwd,userid:userid,phonecode:code},success,error)
        },
        "orderPay":function(pid,shopid,uid,paytype,balance,pwd,success,error)
        {
            _post("Vegetables/order_payV1_2",{pid:pid,shop_id:shopid,mid:uid,paytype:paytype,balance:balance,paypwd:pwd},success,error)
        },
        "changePayPwd":function(mid,pwd,oldpwd,success,error)
        {
            _post("Vegetables/paypwd_modifyV1_2",{mid:mid,paypwd:pwd,old_paypwd:oldpwd},success,error)
        },
        "recharge":function(mid,type,price,success,error)
        {
            _post("Vegetables/personal_rechargeV1_2",{mid:mid,paytype:type,price:price},success,error);
        },
        "posSet":function(openid,mid,sid,success,error)
        {
            _post("Vegetables/member_shopV1_2",{open_id:mid,mid:openid,shop_id:sid},success,error);
        }
    }
})()
