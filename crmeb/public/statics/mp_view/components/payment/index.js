(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/payment/index"],{4231:function(t,e,n){},"470a":function(t,e,n){"use strict";n.r(e);var i=n("b181"),a=n("7c4f");for(var o in a)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("bc16");var r=n("f0c5"),u=Object(r["a"])(a["default"],i["b"],i["c"],!1,null,"0647f4fe",null,!1,i["a"],void 0);e["default"]=u.exports},"7c4f":function(t,e,n){"use strict";n.r(e);var i=n("89eb"),a=n.n(i);for(var o in i)["default"].indexOf(o)<0&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},"89eb":function(t,e,n){"use strict";(function(t,i){var a=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n("39ed"),r=a(n("19b6")),u={props:{payMode:{type:Array,default:function(){return[]}},pay_close:{type:Boolean,default:!1},order_id:{type:String,default:""},totalPrice:{type:String,default:"0"},isCall:{type:Boolean,default:!1},friendPay:{type:Boolean,default:!1}},mixins:[r.default],data:function(){return{formContent:"",active:0,paytype:"",number:0}},watch:{payMode:{handler:function(t,e){var n=[];t.forEach((function(t,e){t.payStatus&&(t.index=e,n.push(t))})),this.active=n[0].index,this.paytype=n[0].value,this.number=n[0].number||0},immediate:!0,deep:!0}},methods:{payType:function(t,e,n){this.active=n,this.paytype=e,this.number=t,this.$emit("changePayType",e)},formpost:function(t,e){var n=document.createElement("form");for(var i in n.action=t,n.method="post",n.target="_self",n.style.display="none",e){var a=document.createElement("input");a.name=i,a.value=e[i],n.appendChild(a)}document.body.appendChild(n),this.$nextTick((function(t){n.submit()}))},close:function(){this.$emit("onChangeFun",{action:"payClose"})},goPay:function(e,n){var a=this;if(this.isCall)return this.$emit("onChangeFun",{action:"payCheck",value:n});var r=this;return r.order_id?"yue"==n&&parseFloat(e)<parseFloat(r.totalPrice)?r.$util.Tips({title:r.$t("余额不足")}):(t.showLoading({title:r.$t("支付中")}),void(0,o.orderPay)({uni:r.order_id,paytype:n,type:r.friendPay?1:0,from:"routine"}).then((function(e){var o=e.data.result.jsConfig;if("ALLINPAY_PAY"==e.data.status)t.hideLoading(),i.openEmbeddedMiniProgram({appId:"wxef277996acc166c3",extraData:{cusid:o.cusid,appid:o.appid,version:o.version,trxamt:o.trxamt,reqsn:o.reqsn,notify_url:o.notify_url,body:o.body,remark:o.remark,validtime:o.validtime,randomstr:o.randomstr,paytype:o.paytype,sign:o.sign,signtype:o.signtype}}),a.jumpData={orderId:e.data.result.orderId,msg:e.msg};else switch(n){case"weixin":if(void 0===e.data.result)return r.$util.Tips({title:r.$t("缺少支付参数")});var u="";u=t.requestOrderPayment?"requestOrderPayment":"requestPayment",t[u]({timeStamp:o.timestamp,nonceStr:o.nonceStr,package:o.package,signType:o.signType,paySign:o.paySign,success:function(e){return t.hideLoading(),r.$util.Tips({title:e.msg,icon:"success"},(function(){r.$emit("onChangeFun",{action:"pay_complete"})}))},fail:function(e){return t.hideLoading(),r.$util.Tips({title:r.$t("取消支付")},(function(){r.$emit("onChangeFun",{action:"pay_fail"})}))},complete:function(e){if(t.hideLoading(),"requestPayment:cancel"==e.errMsg||"requestOrderPayment:cancel"==e.errMsg)return r.$util.Tips({title:r.$t("取消支付")},(function(){r.$emit("onChangeFun",{action:"pay_fail"})}))}});break;case"yue":return t.hideLoading(),r.$util.Tips({title:e.msg,icon:"success"},(function(){r.$emit("onChangeFun",{action:"pay_complete"})}));case"offline":return t.hideLoading(),r.$util.Tips({title:e.msg,icon:"success"},(function(){r.$emit("onChangeFun",{action:"pay_complete"})}));case"friend":return t.hideLoading(),r.$util.Tips({title:e.msg,icon:"success"},(function(){r.$emit("onChangeFun",{action:"pay_complete"})}));case"alipay":t.hideLoading(),t.navigateTo({url:"/pages/users/alipay_invoke/index?id=".concat(e.data.result.order_id,"&link=").concat(e.data.result.jsConfig.qrCode)});break}})).catch((function(e){return t.hideLoading(),r.$util.Tips({title:e},(function(){r.$emit("onChangeFun",{action:"pay_fail"})}))}))):r.$util.Tips({title:r.$t("请选择要支付的订单")})}}};e.default=u}).call(this,n("543d")["default"],n("bc2e")["default"])},b181:function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){}));var i=function(){var t=this,e=t.$createElement,n=(t._self._c,t.$t("选择付款方式")),i=t.__map(t.payMode,(function(e,n){var i=t.__get_orig(e),a="yue"==e.value?t.$t("￥"):null;return{$orig:i,m1:a}})),a=t.$t("支付"),o=t.$t("￥"),r=t.$t("去付款");t.$mp.data=Object.assign({},{$root:{m0:n,l0:i,m2:a,m3:o,m4:r}})},a=[]},bc16:function(t,e,n){"use strict";var i=n("4231"),a=n.n(i);a.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/payment/index-create-component',
    {
        'components/payment/index-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("470a"))
        })
    },
    [['components/payment/index-create-component']]
]);
