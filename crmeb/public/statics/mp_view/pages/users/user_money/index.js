(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/user_money/index"],{4224:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n("5019"),i=n("08aa"),c=n("56ec"),u=n("858e"),a=n("26cb"),r=s(n("2d3a"));function s(t){return t&&t.__esModule?t:{default:t}}var h=function(){Promise.all([n.e("common/vendor"),n.e("components/recommend/index")]).then(function(){return resolve(n("ecac"))}.bind(null,n)).catch(n.oe)},d=function(){n.e("components/Authorize").then(function(){return resolve(n("0cda"))}.bind(null,n)).catch(n.oe)},f=function(){Promise.all([n.e("common/vendor"),n.e("components/home/index")]).then(function(){return resolve(n("c872"))}.bind(null,n)).catch(n.oe)},l={components:{recommend:h,authorize:d,home:f},mixins:[r.default],data:function(){return{userInfo:{},hostProduct:[],isClose:!1,recharge_switch:0,activity:{},isAuto:!1,isShowAuth:!1,hotScroll:!1,hotPage:1,hotLimit:10}},computed:(0,a.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,e){t&&(this.getUserInfo(),this.get_host_product(),this.get_activity())},deep:!0}},onLoad:function(){this.isLogin?(this.getUserInfo(),this.get_host_product(),this.get_activity()):(0,u.toLogin)()},methods:{onLoadFun:function(){this.getUserInfo(),this.get_host_product(),this.get_activity()},authColse:function(t){this.isShowAuth=t},openSubscribe:function(e){t.showLoading({title:"正在加载"}),(0,i.openRechargeSubscribe)().then((function(n){t.hideLoading(),t.navigateTo({url:e})})).catch((function(){t.hideLoading()}))},getUserInfo:function(){var t=this;(0,c.getUserInfo)().then((function(e){t.$set(t,"userInfo",e.data),t.recharge_switch=e.data.recharge_switch}))},get_activity:function(){var t=this;(0,c.userActivity)().then((function(e){t.$set(t,"activity",e.data)}))},get_host_product:function(){var t=this;t.hotScroll||(0,o.getProductHot)(t.hotPage,t.hotLimit).then((function(e){t.hotPage++,t.hotScroll=e.data.length<t.hotLimit,t.hostProduct=t.hostProduct.concat(e.data)}))}},onReachBottom:function(){this.get_host_product()}};e.default=l}).call(this,n("543d")["default"])},"479e":function(t,e,n){"use strict";n.r(e);var o=n("4224"),i=n.n(o);for(var c in o)"default"!==c&&function(t){n.d(e,t,(function(){return o[t]}))}(c);e["default"]=i.a},"6f20":function(t,e,n){"use strict";(function(t){n("62f9");o(n("66fd"));var e=o(n("cb7a"));function o(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},"813d":function(t,e,n){},b3aa:function(t,e,n){"use strict";var o=n("813d"),i=n.n(o);i.a},bdb5:function(t,e,n){"use strict";var o;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return c})),n.d(e,"a",(function(){return o}));var i=function(){var t=this,e=t.$createElement;t._self._c},c=[]},cb7a:function(t,e,n){"use strict";n.r(e);var o=n("bdb5"),i=n("479e");for(var c in i)"default"!==c&&function(t){n.d(e,t,(function(){return i[t]}))}(c);n("b3aa");var u,a=n("f0c5"),r=Object(a["a"])(i["default"],o["b"],o["c"],!1,null,"013b9cda",null,!1,o["a"],u);e["default"]=r.exports}},[["6f20","common/runtime","common/vendor"]]]);