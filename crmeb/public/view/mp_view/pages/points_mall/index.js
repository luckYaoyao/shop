(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/points_mall/index"],{"017a":function(t,e,n){"use strict";(function(t){n("cdba");a(n("66fd"));var e=a(n("bcea"));function a(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])},"1b9f":function(t,e,n){"use strict";var a;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return a}));var i=function(){var t=this,e=t.$createElement;t._self._c},r=[]},"1d5b":function(t,e,n){"use strict";var a=n("9045"),i=n.n(a);i.a},9045:function(t,e,n){},bcea:function(t,e,n){"use strict";n.r(e);var a=n("1b9f"),i=n("d6fc");for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);n("1d5b");var o,u=n("f0c5"),s=Object(u["a"])(i["default"],a["b"],a["c"],!1,null,"d535f160",null,!1,a["a"],o);e["default"]=s.exports},c8f4:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n("26cb"),i=n("b73b"),r=n("7f94"),o=u(n("cd99"));function u(t){return t&&t.__esModule?t:{default:t}}var s={components:{},mixins:[o.default],data:function(){return{autoplay:!0,circular:!0,interval:3e3,duration:500,imgUrls:[],goodList:[],modelList:[{title:"我的积分",imgUrl:"../../static/images/my-point.png",url:"/pages/users/user_integral/index"},{title:"每日签到",imgUrl:"../../static/images/sign-in.png",url:"/pages/users/user_sgin/index"},{title:"积分抽奖",imgUrl:"../../static/images/points-lottery.png",url:"/pages/activity/lottery/grids/index?type=1"},{title:"兑换记录",imgUrl:"../../static/images/exchange.png",url:"/pages/points_mall/exchange_record"}]}},computed:(0,a.mapGetters)(["isLogin"]),onLoad:function(){this.getStoreIntegral()},watch:{isLogin:{handler:function(t,e){1==t&&this.getStoreIntegral()},deep:!0}},methods:{getStoreIntegral:function(){var t=this;(0,i.getStoreIntegral)().then((function(e){t.imgUrls=e.data.banner,t.goodList=e.data.list}))},goGoodsDetail:function(e){(0,r.goShopDetail)(e).then((function(n){t.navigateTo({url:"/pages/points_mall/integral_goods_details?id=".concat(e.id)})}))},jumpMore:function(){t.navigateTo({url:"/pages/points_mall/integral_goods_list"})},goPages:function(e){var n=e.link;-1!=n.indexOf("http")||(-1==["/pages/goods_cate/goods_cate","/pages/order_addcart/order_addcart","/pages/user/index","/pages/index/index"].indexOf(n)?t.navigateTo({url:n}):t.switchTab({url:n}))},jump:function(e){t.navigateTo({url:e})}}};e.default=s}).call(this,n("543d")["default"])},d6fc:function(t,e,n){"use strict";n.r(e);var a=n("c8f4"),i=n.n(a);for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);e["default"]=i.a}},[["017a","common/runtime","common/vendor"]]]);