(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/order_pay_status/payLottery"],{"087a":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var r=function(){var t=this,e=t.$createElement;t._self._c;t._isMounted||(t.e0=function(){t.addressModel=!1})},o=[]},"2d4c":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;n("f57c");var i,r=n("e018"),o=n("f9f5"),a=n("7f1d"),d=n("26cb"),s=u(n("5411"));function u(t){return t&&t.__esModule?t:{default:t}}function c(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var l=function(){Promise.all([n.e("common/vendor"),n.e("components/lottery/index")]).then(function(){return resolve(n("a766"))}.bind(null,n)).catch(n.oe)},f=function(){n.e("pages/order_pay_status/components/lotteryAleart").then(function(){return resolve(n("d6ea"))}.bind(null,n)).catch(n.oe)},h=function(){Promise.all([n.e("common/vendor"),n.e("pages/order_pay_status/components/userAddress")]).then(function(){return resolve(n("7e9c"))}.bind(null,n)).catch(n.oe)},p=function(){n.e("components/Authorize").then(function(){return resolve(n("97f9"))}.bind(null,n)).catch(n.oe)},y={components:{authorize:p,gridsLottery:l,lotteryAleart:f,userAddress:h},mixins:[s.default],props:{options:{type:Object}},data:function(){return{lotteryShow:!1,addressModel:!1,lottery_num:0,aleartType:0,aleartStatus:!1,lottery_draw_param:{startIndex:3,totalCount:3,winingIndex:1,speed:100},alData:{},type:"",prize:[],orderId:"",order_pay_info:{paid:1,_status:{}},isAuto:!1,isShowAuth:!1,couponsHidden:!0,couponList:[],totalPrice:0}},computed:(0,d.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,e){},deep:!0},options:{handler:function(t,e){t&&(this.orderId=t.order_id,this.totalPrice=t.totalPrice,this.type=t.type,this.getLotteryData(t.type))},deep:!0}},created:function(t){this.isLogin?this.getLotteryData(this.type):(0,o.toLogin)()},methods:(i={openTap:function(){this.$set(this,"couponsHidden",!this.couponsHidden)},orderDetails:function(){this.$emit("orderDetails")},getWiningIndex:function(t){var e=this;this.aleartType=0,(0,a.startLottery)({id:this.id}).then((function(n){e.prize.forEach((function(i,r){n.data.id===i.id&&(e.alData=n.data,e.lottery_draw_param.winingIndex=r,t(e.lottery_draw_param))}))})).catch((function(t){e.$util.Tips({title:t})}))},goIndex:function(e){t.switchTab({url:"/pages/index/index"})},goOrderDetails:function(e){var n=this;t.showLoading({title:"正在加载"}),(0,r.openOrderSubscribe)().then((function(e){t.hideLoading(),t.navigateTo({url:"/pages/users/order_details/index?order_id="+n.orderId})})).catch((function(){nui.hideLoading()}))},getLotteryData:function(t){var e=this;(0,a.getLotteryData)(t).then((function(t){e.factor_num=t.data.lottery.factor_num,e.id=t.data.lottery.id,e.prize=t.data.lottery.prize,e.lottery_num=t.data.lottery_num,e.prize.push({a:1}),e.$emit("lotteryShow",!0),e.lotteryShow=!0})).catch((function(t){e.$emit("lotteryShow",!1),e.lotteryShow=!1}))},closeLottery:function(t){this.aleartStatus=!1,this.getLotteryData(this.type),6===this.alData.type&&(this.addressModel=!0)},getAddress:function(t){var e=this,n=t;n.id=this.alData.lottery_record_id,n.address=t.address.province+t.address.city+t.address.district+t.detail,(0,a.receiveLottery)(n).then((function(t){e.$util.Tips({title:"领取成功"}),e.addressModel=!1})).catch((function(t){e.$util.Tips({title:t})}))}},c(i,"getWiningIndex",(function(t){var e=this;this.aleartType=0,(0,a.startLottery)({id:this.id}).then((function(n){e.prize.forEach((function(i,r){n.data.id===i.id&&(e.alData=n.data,e.lottery_draw_param.winingIndex=r,t(e.lottery_draw_param))}))})).catch((function(t){e.$util.Tips({title:t})}))})),c(i,"luck_draw_finish",(function(t){this.aleartType=2,this.aleartStatus=!0})),i)};e.default=y}).call(this,n("543d")["default"])},5579:function(t,e,n){"use strict";n.r(e);var i=n("087a"),r=n("c5b9");for(var o in r)"default"!==o&&function(t){n.d(e,t,(function(){return r[t]}))}(o);n("5b69");var a,d=n("f0c5"),s=Object(d["a"])(r["default"],i["b"],i["c"],!1,null,"423177de",null,!1,i["a"],a);e["default"]=s.exports},"5b69":function(t,e,n){"use strict";var i=n("be82"),r=n.n(i);r.a},bb6d:function(t,e,n){"use strict";(function(t){n("ebf5");i(n("66fd"));var e=i(n("5579"));function i(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},be82:function(t,e,n){},c5b9:function(t,e,n){"use strict";n.r(e);var i=n("2d4c"),r=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=r.a}},[["bb6d","common/runtime","common/vendor"]]]);