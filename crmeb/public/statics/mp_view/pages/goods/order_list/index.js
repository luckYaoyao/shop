require('../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods/order_list/index"],{"2bb2":function(t,s,e){"use strict";(function(t,_){var n=e("4ea4");Object.defineProperty(s,"__esModule",{value:!0}),s.default=void 0;var a=e("39ed"),u=e("5d9d"),i=e("86eb"),p=e("26cb"),r=n(e("19b6")),o={components:{home:function(){Promise.all([e.e("common/vendor"),e.e("components/home/index")]).then(function(){return resolve(e("3b71"))}.bind(null,e)).catch(e.oe)},emptyPage:function(){e.e("components/emptyPage").then(function(){return resolve(e("42ff"))}.bind(null,e)).catch(e.oe)},authorize:function(){e.e("components/Authorize").then(function(){return resolve(e("ad23"))}.bind(null,e)).catch(e.oe)}},mixins:[r.default],data:function(){return{loading:!1,loadend:!1,loadTitle:this.$t("加载更多"),orderList:[],orderData:{},orderStatus:9,page:1,limit:20,pay_close:!1,pay_order_id:"",totalPrice:"0",initIn:!1,isAuto:!1,isShowAuth:!1}},computed:(0,p.mapGetters)(["isLogin"]),onLoad:function(s){s.status&&(this.orderStatus=s.status);var e=t.getEnterOptionsSync();if("1038"==e.scene&&"wxef277996acc166c3"==e.referrerInfo.appId&&this.initIn){var _=e.referrerInfo.extraData;this.initIn=!1,_?"success"==_.code?this.getOrderList():_.code:this.getOrderList()}},onShow:function(){this.isLogin?(this.page=1,this.orderList=[],this.loadend=!1,this.pay_close=!1,this.onLoadFun(),this.getOrderList()):(0,i.toLogin)()},methods:{onLoadFun:function(){this.getOrderData()},authColse:function(t){this.isShowAuth=t},onChangeFun:function(t){var s=t,e=s.action||null,_=void 0!=s.value?s.value:null;e&&this[e]&&this[e](_)},payClose:function(){this.pay_close=!1},getOrderData:function(){var t=this;(0,a.orderData)().then((function(s){t.$set(t,"orderData",s.data)}))},cancelOrder:function(t,s){var e=this;if(!s)return e.$util.Tips({title:e.$t("缺少订单号无法取消订单")});_.showModal({title:this.$t("提示"),content:this.$t("确认取消该订单"),success:function(_){_.confirm?(0,a.orderCancel)(s).then((function(s){return e.$util.Tips({title:s.msg,icon:"success"},(function(){e.orderList.splice(t,1),e.$set(e,"orderList",e.orderList),e.$set(e.orderData,"unpaid_count",e.orderData.unpaid_count-1),e.getOrderData()}))})).catch((function(t){return e.$util.Tips({title:t})})):_.cancel}})},goPay:function(t,s){_.navigateTo({url:"/pages/goods/cashier/index?order_id=".concat(s,"&from_type=order")})},goOrderDetails:function(t){if(!t)return this.$util.Tips({title:this.$t("缺少订单号无法查看订单详情")});_.showLoading({title:this.$t("正在加载中")}),(0,u.openOrderSubscribe)().then((function(){_.hideLoading(),_.navigateTo({url:"/pages/goods/order_details/index?order_id="+t})})).catch((function(t){_.hideLoading()}))},statusClick:function(t){t!=this.orderStatus&&(this.orderStatus=t,this.loadend=!1,this.page=1,this.$set(this,"orderList",[]),this.getOrderList())},getOrderList:function(){var t=this;t.loadend||t.loading||(t.loading=!0,t.loadTitle=t.$t("加载更多"),(0,a.getOrderList)({type:t.orderStatus,page:t.page,limit:t.limit}).then((function(s){var e=s.data||[],_=e.length<t.limit;t.orderList=t.$util.SplitArray(e,t.orderList),t.$set(t,"orderList",t.orderList),t.loadend=_,t.loading=!1,t.loadTitle=_?t.$t("没有更多内容啦~"):t.$t("加载更多"),t.page=t.page+1})).catch((function(s){t.loading=!1,t.loadTitle=t.$t("加载更多")})))},delOrder:function(t,s){var e=this;_.showModal({title:e.$t("删除订单"),content:e.$t("确定删除该订单"),success:function(_){if(_.confirm)(0,a.orderDel)(t).then((function(t){return e.orderList.splice(s,1),e.$set(e,"orderList",e.orderList),e.$set(e.orderData,"unpaid_count",e.orderData.unpaid_count-1),e.getOrderData(),e.$util.Tips({title:e.$t("删除成功"),icon:"success"})})).catch((function(t){return e.$util.Tips({title:t})}));else if(_.cancel)return e.$util.Tips({title:e.$t("已取消")})}})}},onReachBottom:function(){this.getOrderList()},onPageScroll:function(t){_.$emit("scroll")}};s.default=o}).call(this,e("bc2e")["default"],e("543d")["default"])},"4ba3":function(t,s,e){"use strict";e.r(s);var _=e("2bb2"),n=e.n(_);for(var a in _)["default"].indexOf(a)<0&&function(t){e.d(s,t,(function(){return _[t]}))}(a);s["default"]=n.a},"5a02":function(t,s,e){},"9fd4":function(t,s,e){"use strict";(function(t,s){var _=e("4ea4");e("4789");_(e("66fd"));var n=_(e("ad0a"));t.__webpack_require_UNI_MP_PLUGIN__=e,s(n.default)}).call(this,e("bc2e")["default"],e("543d")["createPage"])},ad0a:function(t,s,e){"use strict";e.r(s);var _=e("e443"),n=e("4ba3");for(var a in n)["default"].indexOf(a)<0&&function(t){e.d(s,t,(function(){return n[t]}))}(a);e("dd5b");var u=e("f0c5"),i=Object(u["a"])(n["default"],_["b"],_["c"],!1,null,"dc39c3c2",null,!1,_["a"],void 0);s["default"]=i.exports},dd5b:function(t,s,e){"use strict";var _=e("5a02"),n=e.n(_);n.a},e443:function(t,s,e){"use strict";e.d(s,"b",(function(){return n})),e.d(s,"c",(function(){return a})),e.d(s,"a",(function(){return _}));var _={easyLoadimage:function(){return Promise.all([e.e("common/vendor"),e.e("components/easy-loadimage/easy-loadimage")]).then(e.bind(null,"cad3"))}},n=function(){var t=this,s=t.$createElement,e=(t._self._c,t.$t("订单信息")),_=t.$t("消费订单"),n=t.$t("总消费"),a=t.$t("￥"),u=t.$t("全部"),i=t.$t("待付款"),p=t.$t("待发货"),r=t.$t("待收货"),o=t.$t("待评价"),l=t.$t("共"),y=t.$t("件商品，总金额"),d=t.$t("￥"),c=t.$t("查看详情"),h=t.__map(t.orderList,(function(s,e){var _=t.__get_orig(s),n=2==s.type&&t.$permission("bargain"),a=n?t.$t("砍价"):null,u=n?null:3==s.type&&t.$permission("combination"),i=!n&&u?t.$t("拼团"):null,p=n||u?null:1==s.type&&t.$permission("seckill"),r=n||u||!p?null:t.$t("秒杀"),o=n||u||p||4!=s.type?null:t.$t("预售"),l=9==s._status._type?t.$t("线下付款,未支付"):null,y=9!=s._status._type&&0==s._status._type?t.$t("待付款"):null,d=9!=s._status._type&&0!=s._status._type&&1==s._status._type&&1==s.shipping_type?t.$t("待发货"):null,c=9!=s._status._type&&0!=s._status._type&&1==s._status._type&&1==s.shipping_type?s.refund.length:null,h=9!=s._status._type&&0!=s._status._type&&1==s._status._type&&1==s.shipping_type&&c&&s.is_all_refund?t.$t("退款中"):null,g=9!=s._status._type&&0!=s._status._type&&1==s._status._type&&1==s.shipping_type&&c&&!s.is_all_refund?t.$t("部分退款中"):null,f=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1!=s._status._type||2!=s.shipping_type?null:t.$t("待核销"),m=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1!=s._status._type||2!=s.shipping_type?null:s.refund.length,$=9!=s._status._type&&0!=s._status._type&&(1!=s._status._type||1!=s.shipping_type)&&1==s._status._type&&2==s.shipping_type&&m&&s.is_all_refund?t.$t("退款中"):null,v=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1!=s._status._type||2!=s.shipping_type||!m||s.is_all_refund?null:t.$t("部分退款中"),L=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2!=s._status._type?null:t.$t("待收货"),b=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2!=s._status._type?null:s.refund.length,O=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2!=s._status._type||!b||!s.is_all_refund?null:t.$t("退款中"),D=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2!=s._status._type||!b||s.is_all_refund?null:t.$t("部分退款中"),I=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3!=s._status._type?null:t.$t("待评价"),T=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3!=s._status._type?null:s.refund.length,S=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3!=s._status._type||!T||!s.is_all_refund?null:t.$t("退款中"),P=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3!=s._status._type||!T||s.is_all_refund?null:t.$t("部分退款中"),w=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4!=s._status._type?null:t.$t("已完成"),x=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4!=s._status._type?null:s.refund.length,k=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4!=s._status._type||!x||!s.is_all_refund?null:t.$t("退款中"),A=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4!=s._status._type||!x||s.is_all_refund?null:t.$t("部分退款中"),C=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4==s._status._type||5!=s._status._type||0!=s.status?null:t.$t("未核销"),M=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4==s._status._type||5!=s._status._type||0!=s.status?null:s.refund.length,j=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4==s._status._type||5!=s._status._type||0!=s.status||!M||!s.is_all_refund?null:t.$t("退款中"),F=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4==s._status._type||5!=s._status._type||0!=s.status||!M||s.is_all_refund?null:t.$t("部分退款中"),z=9==s._status._type||0==s._status._type||1==s._status._type&&1==s.shipping_type||1==s._status._type&&2==s.shipping_type||2==s._status._type||3==s._status._type||4==s._status._type||5==s._status._type&&0==s.status||-2!=s._status._type?null:t.$t("已退款"),E=t.__map(s.cartInfo,(function(e,_){var n=t.__get_orig(e),a=e.productInfo.attrInfo?t.$t("￥"):null,u=e.productInfo.attrInfo?null:t.$t("￥"),i=e.refund_num&&-2!=s._status._type?t.$t("件退款中"):null;return{$orig:n,m37:a,m38:u,m39:i}})),G=0==s._status._type||9==s._status._type?t.$t("取消订单"):null,J=0==s._status._type?t.$t("立即付款"):null,N=4==s._status._type?t.$t("删除订单"):null;return{$orig:_,m9:n,m10:a,m11:u,m12:i,m13:p,m14:r,m15:o,m16:l,m17:y,m18:d,g0:c,m19:h,m20:g,m21:f,g1:m,m22:$,m23:v,m24:L,g2:b,m25:O,m26:D,m27:I,g3:T,m28:S,m29:P,m30:w,g4:x,m31:k,m32:A,m33:C,g5:M,m34:j,m35:F,m36:z,l0:E,m43:G,m44:J,m45:N}})),g=t.orderList.length,f=t.orderList.length,m=0!=f||t.loading?null:t.$t("暂无订单");t.$mp.data=Object.assign({},{$root:{m0:e,m1:_,m2:n,m3:a,m4:u,m5:i,m6:p,m7:r,m8:o,m40:l,m41:y,m42:d,m46:c,l1:h,g6:g,g7:f,m47:m}})},a=[]}},[["9fd4","common/runtime","common/vendor"]]]);