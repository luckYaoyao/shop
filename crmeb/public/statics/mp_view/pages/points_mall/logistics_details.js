(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/points_mall/logistics_details"],{"05c8":function(t,e,n){"use strict";var o=n("1e30"),r=n.n(o);r.a},"1d6c":function(t,e,n){"use strict";(function(t){n("6e38");o(n("66fd"));var e=o(n("a547"));function o(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},"1e30":function(t,e,n){},"521a":function(t,e,n){"use strict";var o;n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return i})),n.d(e,"a",(function(){return o}));var r=function(){var t=this,e=t.$createElement;t._self._c},i=[]},6558:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n("f15c"),r=n("4f72"),i=(s(n("fd65")),n("c6cd")),c=n("26cb"),u=s(n("c83f"));function s(t){return t&&t.__esModule?t:{default:t}}var d=function(){Promise.all([n.e("common/vendor"),n.e("components/recommend/index")]).then(function(){return resolve(n("22f5"))}.bind(null,n)).catch(n.oe)},a=function(){n.e("components/Authorize").then(function(){return resolve(n("420f"))}.bind(null,n)).catch(n.oe)},f={components:{recommend:d,authorize:a},mixins:[u.default],data:function(){return{orderId:"",product:{productInfo:{}},orderInfo:{},expressList:[],hostProduct:[]}},computed:(0,c.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,e){t&&(this.getExpress(),this.get_host_product())},deep:!0}},onLoad:function(t){if(!t.order_id)return this.$util.Tips({title:"缺少订单号"});this.orderId=t.order_id,this.isLogin?(this.getExpress(),this.get_host_product()):(0,i.toLogin)()},onReady:function(){},methods:{onLoadFun:function(){this.getExpress(),this.get_host_product()},copyOrderId:function(){t.setClipboardData({data:this.orderInfo.delivery_id})},getExpress:function(){var t=this,e=this;(0,r.getLogisticsDetails)(e.orderId).then((function(t){var n=t.data.express.result||{};e.$set(e,"orderInfo",t.data.order),e.$set(e,"expressList",n.list||[])})).catch((function(e){t.$util.Tips({title:e})}))},get_host_product:function(){var t=this;(0,o.getProductHot)().then((function(e){t.$set(t,"hostProduct",e.data)}))}}};e.default=f}).call(this,n("543d")["default"])},"701f":function(t,e,n){"use strict";n.r(e);var o=n("6558"),r=n.n(o);for(var i in o)"default"!==i&&function(t){n.d(e,t,(function(){return o[t]}))}(i);e["default"]=r.a},a547:function(t,e,n){"use strict";n.r(e);var o=n("521a"),r=n("701f");for(var i in r)"default"!==i&&function(t){n.d(e,t,(function(){return r[t]}))}(i);n("05c8");var c,u=n("f0c5"),s=Object(u["a"])(r["default"],o["b"],o["c"],!1,null,"08ae9fc2",null,!1,o["a"],c);e["default"]=s.exports}},[["1d6c","common/runtime","common/vendor"]]]);