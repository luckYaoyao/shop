(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/payment/index"],{"37f5":function(t,e,n){"use strict";n.r(e);var i=n("5950"),a=n.n(i);for(var u in i)"default"!==u&&function(t){n.d(e,t,(function(){return i[t]}))}(u);e["default"]=a.a},"4aa8":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return u})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement;t._self._c},u=[]},5950:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n("d790"),a=u(n("cd99"));function u(t){return t&&t.__esModule?t:{default:t}}var o={props:{payMode:{type:Array,default:function(){return[]}},pay_close:{type:Boolean,default:!1},order_id:{type:String,default:""},totalPrice:{type:String,default:"0"},isCall:{type:Boolean,default:!1}},mixins:[a.default],data:function(){return{formContent:"",active:0,paytype:"",number:0}},watch:{payMode:{handler:function(t,e){var n=[];t.forEach((function(t,e){t.payStatus&&(t.index=e,n.push(t))})),this.active=n[0].index,this.paytype=n[0].value,this.number=n[0].number||0},immediate:!0,deep:!0}},methods:{payType:function(t,e,n){this.active=n,this.paytype=e,this.number=t,this.$emit("changePayType",e)},close:function(){this.$emit("onChangeFun",{action:"payClose"})},goPay:function(e,n){if(this.isCall)return this.$emit("onChangeFun",{action:"payCheck",value:n});var a=this;return a.order_id?"yue"==n&&parseFloat(e)<parseFloat(a.totalPrice)?a.$util.Tips({title:"余额不足！"}):(t.showLoading({title:"支付中"}),void(0,i.orderPay)({uni:a.order_id,paytype:n,from:"routine"}).then((function(e){var i=e.data.result.jsConfig;switch(n){case"weixin":if(void 0===e.data.result)return a.$util.Tips({title:"缺少支付参数"});t.requestPayment({timeStamp:i.timestamp,nonceStr:i.nonceStr,package:i.package,signType:i.signType,paySign:i.paySign,success:function(e){return t.hideLoading(),a.$util.Tips({title:e.msg,icon:"success"},(function(){a.$emit("onChangeFun",{action:"pay_complete"})}))},fail:function(e){return t.hideLoading(),a.$util.Tips({title:"取消支付"},(function(){a.$emit("onChangeFun",{action:"pay_fail"})}))},complete:function(e){if(t.hideLoading(),"requestPayment:cancel"==e.errMsg)return a.$util.Tips({title:"取消支付"},(function(){a.$emit("onChangeFun",{action:"pay_fail"})}))}});break;case"yue":return t.hideLoading(),a.$util.Tips({title:e.msg,icon:"success"},(function(){a.$emit("onChangeFun",{action:"pay_complete"})}));case"offline":return t.hideLoading(),a.$util.Tips({title:e.msg,icon:"success"},(function(){a.$emit("onChangeFun",{action:"pay_complete"})}));case"alipay":t.hideLoading(),t.navigateTo({url:"/pages/users/alipay_invoke/index?id=".concat(e.data.result.order_id,"&link=").concat(e.data.result.jsConfig.qrCode)});break}})).catch((function(e){return t.hideLoading(),a.$util.Tips({title:e},(function(){a.$emit("onChangeFun",{action:"pay_fail"})}))}))):a.$util.Tips({title:"请选择要支付的订单"})}}};e.default=o}).call(this,n("543d")["default"])},b49e:function(t,e,n){"use strict";var i=n("d8cd4"),a=n.n(i);a.a},c3d5:function(t,e,n){"use strict";n.r(e);var i=n("4aa8"),a=n("37f5");for(var u in a)"default"!==u&&function(t){n.d(e,t,(function(){return a[t]}))}(u);n("b49e");var o,c=n("f0c5"),r=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"09def753",null,!1,i["a"],o);e["default"]=r.exports},d8cd4:function(t,e,n){}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/payment/index-create-component',
    {
        'components/payment/index-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("c3d5"))
        })
    },
    [['components/payment/index-create-component']]
]);
