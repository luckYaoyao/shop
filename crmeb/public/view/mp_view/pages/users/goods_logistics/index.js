(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/goods_logistics/index"],{"5a90":function(t,e,n){"use strict";var o;n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return i})),n.d(e,"a",(function(){return o}));var r=function(){var t=this,e=t.$createElement;t._self._c},i=[]},"6bbe":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n("d790"),r=n("a8b0"),i=(s(n("af4e")),n("12f4")),u=n("26cb"),c=s(n("cd99"));function s(t){return t&&t.__esModule?t:{default:t}}var a=function(){Promise.all([n.e("common/vendor"),n.e("components/recommend/index")]).then(function(){return resolve(n("fe46"))}.bind(null,n)).catch(n.oe)},d=function(){n.e("components/Authorize").then(function(){return resolve(n("0076"))}.bind(null,n)).catch(n.oe)},f={components:{recommend:a,authorize:d},mixins:[c.default],data:function(){return{orderId:"",product:[],orderInfo:{},expressList:[],hostProduct:[]}},computed:(0,u.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,e){t&&(this.getExpress(),this.get_host_product())},deep:!0}},onLoad:function(t){if(!t.orderId)return this.$util.Tips({title:"缺少订单号"});this.orderId=t.orderId,this.isLogin?(this.getExpress(),this.get_host_product()):(0,i.toLogin)()},onReady:function(){},methods:{onLoadFun:function(){this.getExpress(),this.get_host_product()},copyOrderId:function(){t.setClipboardData({data:this.orderInfo.delivery_id})},getExpress:function(){var t=this,e=this;(0,o.express)(e.orderId).then((function(t){var n=t.data.express.result||{};e.$set(e,"product",t.data.order.cartInfo||[]),e.$set(e,"orderInfo",t.data.order),e.$set(e,"expressList",n.list||[])})).catch((function(e){t.$util.Tips({title:e})}))},get_host_product:function(){var t=this;(0,r.getProductHot)().then((function(e){t.$set(t,"hostProduct",e.data)}))}}};e.default=f}).call(this,n("543d")["default"])},a88f:function(t,e,n){"use strict";n.r(e);var o=n("5a90"),r=n("e8e7");for(var i in r)"default"!==i&&function(t){n.d(e,t,(function(){return r[t]}))}(i);n("c848");var u,c=n("f0c5"),s=Object(c["a"])(r["default"],o["b"],o["c"],!1,null,"7158eea9",null,!1,o["a"],u);e["default"]=s.exports},c188:function(t,e,n){"use strict";(function(t){n("cdba");o(n("66fd"));var e=o(n("a88f"));function o(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])},c848:function(t,e,n){"use strict";var o=n("fa24"),r=n.n(o);r.a},e8e7:function(t,e,n){"use strict";n.r(e);var o=n("6bbe"),r=n.n(o);for(var i in o)"default"!==i&&function(t){n.d(e,t,(function(){return o[t]}))}(i);e["default"]=r.a},fa24:function(t,e,n){}},[["c188","common/runtime","common/vendor"]]]);