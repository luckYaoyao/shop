require('../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods/goods_comment_con/lottery_comment"],{"0866":function(t,e,n){"use strict";(function(t){n("248d");o(n("66fd"));var e=o(n("c390"));function o(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},"22ea":function(t,e,n){},"53f9":function(t,e,n){"use strict";var o=n("22ea"),a=n.n(o);a.a},"790d":function(t,e,n){"use strict";var o;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return o}));var a=function(){var t=this,e=t.$createElement,n=(t._self._c,t.$t("评价完成")),o=t.$util.getNowTime(),a=t.$t("返回首页"),r=t.$t("恭喜您"),i=t.$t("获得"),d=t.$t("机会");t._isMounted||(t.e0=function(){t.addressModel=!1}),t.$mp.data=Object.assign({},{$root:{m0:n,g0:o,m1:a,m2:r,m3:i,m4:d}})},r=[]},c390:function(t,e,n){"use strict";n.r(e);var o=n("790d"),a=n("eb5c");for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);n("53f9");var i,d=n("f0c5"),s=Object(d["a"])(a["default"],o["b"],o["c"],!1,null,"280c52d2",null,!1,o["a"],i);e["default"]=s.exports},ca30:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;n("f984"),n("d96f");var o,a,r=n("666f"),i=n("0bad"),d=n("26cb"),s=c(n("66ca"));function c(t){return t&&t.__esModule?t:{default:t}}function u(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var l=function(){Promise.all([n.e("pages/goods/common/vendor"),n.e("pages/goods/components/lottery/index")]).then(function(){return resolve(n("3760"))}.bind(null,n)).catch(n.oe)},f=function(){n.e("pages/goods/goods_comment_con/components/lotteryAleart").then(function(){return resolve(n("f1a7"))}.bind(null,n)).catch(n.oe)},p=function(){Promise.all([n.e("common/vendor"),n.e("pages/goods/goods_comment_con/components/userAddress")]).then(function(){return resolve(n("f7e2"))}.bind(null,n)).catch(n.oe)},h=function(){n.e("components/Authorize").then(function(){return resolve(n("b29f"))}.bind(null,n)).catch(n.oe)},m=(a={mixins:[s.default],components:{authorize:h,gridsLottery:l,lotteryAleart:f,userAddress:p},computed:(0,d.mapGetters)(["isLogin"]),data:function(){return{lotteryShow:!1,addressModel:!1,lottery_num:0,aleartType:0,aleartStatus:!1,lottery_draw_param:{startIndex:3,totalCount:3,winingIndex:1,speed:100},alData:{},type:"",date:"",prize:[],orderId:"",order_pay_info:{paid:1,_status:{}},isAuto:!1,isShowAuth:!1,couponsHidden:!0,couponList:[]}}},u(a,"computed",(0,d.mapGetters)(["isLogin"])),u(a,"watch",{isLogin:{handler:function(t,e){},deep:!0}}),u(a,"onLoad",(function(t){this.orderId=t.order_id,this.type=t.type,this.isLogin?this.getLotteryData(this.type):(0,r.toLogin)()})),u(a,"methods",(o={set_time:function(t){var e=parseInt(t),n=new Date(e),o=n.getFullYear(),a=n.getMonth()+1;a=a<10?"0"+a:a;var r=n.getDate();r=r<10?"0"+r:r;var i=n.getHours();i=i<10?"0"+i:i;var d=n.getMinutes();d=d<10?"0"+d:d;var s=o+"-"+a+"-"+r+" "+i+":"+d;return s},openTap:function(){this.$set(this,"couponsHidden",!this.couponsHidden)},getWiningIndex:function(t){var e=this;(0,i.startLottery)({id:this.id}).then((function(n){e.prize.forEach((function(o,a){n.data.id===o.id&&(e.alData=n.data,e.lottery_draw_param.winingIndex=a,t(e.lottery_draw_param))})),e.aleartType=0})).catch((function(t){e.$util.Tips({title:t})}))},goIndex:function(e){t.switchTab({url:"/pages/index/index"})},getLotteryData:function(e){var n=this;(0,i.getLotteryData)(e).then((function(t){n.lotteryShow=!0,n.factor_num=t.data.lottery.factor_num,n.id=t.data.lottery.id,n.prize=t.data.lottery.prize,n.lottery_num=t.data.lottery_num,n.prize.push({a:1})})).catch((function(e){t.redirectTo({url:"/pages/goods/order_details/index?order_id="+n.orderId})}))},closeLottery:function(t){this.aleartStatus=!1,this.getLotteryData(this.type),6===this.alData.type&&(this.addressModel=!0)},getAddress:function(t){var e=this,n=t;n.id=this.alData.lottery_record_id,n.address=t.address.province+t.address.city+t.address.district+t.detail,(0,i.receiveLottery)(n).then((function(t){e.$util.Tips({title:e.$t("领取成功")}),e.addressModel=!1})).catch((function(t){e.$util.Tips({title:t})}))}},u(o,"getWiningIndex",(function(t){var e=this;this.aleartType=0,(0,i.startLottery)({id:this.id}).then((function(n){e.prize.forEach((function(o,a){n.data.id===o.id&&(e.alData=n.data,e.lottery_draw_param.winingIndex=a,t(e.lottery_draw_param))}))})).catch((function(t){e.$util.Tips({title:t})}))})),u(o,"luck_draw_finish",(function(t){this.aleartType=2,this.aleartStatus=!0})),o)),a);e.default=m}).call(this,n("543d")["default"])},eb5c:function(t,e,n){"use strict";n.r(e);var o=n("ca30"),a=n.n(o);for(var r in o)"default"!==r&&function(t){n.d(e,t,(function(){return o[t]}))}(r);e["default"]=a.a}},[["0866","common/runtime","common/vendor","pages/goods/common/vendor"]]]);