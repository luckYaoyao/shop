(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/points_mall/index"],{"09d0":function(t,e,n){},"207b":function(t,e,n){"use strict";n.r(e);var a=n("5d58"),i=n.n(a);for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);e["default"]=i.a},"31fb":function(t,e,n){"use strict";var a=n("09d0"),i=n.n(a);i.a},4339:function(t,e,n){"use strict";n.r(e);var a=n("6ad8"),i=n("207b");for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);n("31fb");var o,u=n("f0c5"),s=Object(u["a"])(i["default"],a["b"],a["c"],!1,null,"d535f160",null,!1,a["a"],o);e["default"]=s.exports},"5d58":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n("26cb"),i=n("b14e"),r=n("1754"),o=u(n("2d3a"));function u(t){return t&&t.__esModule?t:{default:t}}var s={components:{},mixins:[o.default],data:function(){return{autoplay:!0,circular:!0,interval:3e3,duration:500,imgUrls:[],goodList:[],modelList:[{title:"我的积分",imgUrl:"../../static/images/my-point.png",url:"/pages/users/user_integral/index"},{title:"每日签到",imgUrl:"../../static/images/sign-in.png",url:"/pages/users/user_sgin/index"},{title:"积分抽奖",imgUrl:"../../static/images/points-lottery.png",url:"/pages/activity/lottery/grids/index?type=1"},{title:"兑换记录",imgUrl:"../../static/images/exchange.png",url:"/pages/points_mall/exchange_record"}]}},computed:(0,a.mapGetters)(["isLogin"]),onLoad:function(){this.getStoreIntegral()},watch:{isLogin:{handler:function(t,e){1==t&&this.getStoreIntegral()},deep:!0}},methods:{getStoreIntegral:function(){var t=this;(0,i.getStoreIntegral)().then((function(e){t.imgUrls=e.data.banner,t.goodList=e.data.list}))},goGoodsDetail:function(e){(0,r.goShopDetail)(e).then((function(n){t.navigateTo({url:"/pages/points_mall/integral_goods_details?id=".concat(e.id)})}))},jumpMore:function(){t.navigateTo({url:"/pages/points_mall/integral_goods_list"})},goPages:function(e){var n=e.link;-1!=n.indexOf("http")||(-1==["/pages/goods_cate/goods_cate","/pages/order_addcart/order_addcart","/pages/user/index","/pages/index/index"].indexOf(n)?t.navigateTo({url:n}):t.switchTab({url:n}))},jump:function(e){t.navigateTo({url:e})}}};e.default=s}).call(this,n("543d")["default"])},6811:function(t,e,n){"use strict";(function(t){n("62f9");a(n("66fd"));var e=a(n("4339"));function a(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},"6ad8":function(t,e,n){"use strict";var a;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return a}));var i=function(){var t=this,e=t.$createElement;t._self._c},r=[]}},[["6811","common/runtime","common/vendor"]]]);