(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-users-user_goods_collection-index"],{"0831":function(t,e,o){"use strict";o.r(e);var i=o("173d"),n=o.n(i);for(var a in i)"default"!==a&&function(t){o.d(e,t,(function(){return i[t]}))}(a);e["default"]=n.a},"0b86":function(t,e,o){var i=o("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.recommend[data-v-2f7414c2]{background-color:#fff}.recommend .title[data-v-2f7414c2]{height:%?135?%;font-size:%?28?%;color:#282828}.recommend .title .name[data-v-2f7414c2]{margin:0 %?28?%}.recommend .title .iconfont[data-v-2f7414c2]{font-size:%?170?%;color:#454545}.recommend .title .iconfont.lefticon[data-v-2f7414c2]{-webkit-transform:rotate(180deg);transform:rotate(180deg)}.recommend .recommendList[data-v-2f7414c2]{padding:0 %?30?%}.recommend .recommendList .item[data-v-2f7414c2]{width:%?335?%;margin-bottom:%?30?%;border-radius:%?20?% %?20?% 0 0;box-shadow:%?0?% %?3?% %?10?% %?2?% rgba(0,0,0,.03)}.recommend .recommendList .item .pictrue[data-v-2f7414c2]{position:relative;width:100%;height:%?335?%}.recommend .recommendList .item .pictrue uni-image[data-v-2f7414c2]{width:100%;height:100%;border-radius:%?20?%}.recommend .recommendList .item .name[data-v-2f7414c2]{font-size:%?28?%;color:#282828;margin-top:%?20?%;padding:0 %?10?%}.recommend .recommendList .item .money[data-v-2f7414c2]{font-size:%?20?%;margin-top:%?8?%;padding:0 %?10?% %?10?% %?10?%}.recommend .recommendList .item .money .num[data-v-2f7414c2]{font-size:%?28?%}',""]),t.exports=e},1170:function(t,e,o){var i=o("6db6");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=o("4f06").default;n("51df4214",i,!0,{sourceMap:!1,shadowMode:!1})},1224:function(t,e,o){"use strict";var i=o("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.getProductDetail=a,e.getProductCode=c,e.collectAdd=r,e.collectDel=s,e.postCartAdd=u,e.getCategoryList=d,e.getProductslist=l,e.getProductHot=f,e.collectAll=v,e.getGroomList=p,e.getCollectUserList=g,e.getReplyList=h,e.getReplyConfig=m,e.getSearchKeyword=_,e.storeListApi=w,e.storeDiscountsList=b,e.postCartNum=y,e.getAttr=C,e.getHomeProducts=x,e.getPresellProductDetail=P;var n=i(o("f319"));function a(t){return n.default.get("product/detail/"+t,{},{noAuth:!0})}function c(t){return n.default.get("product/code/"+t,{})}function r(t,e){return n.default.post("collect/add",{id:t,product:void 0===e?"product":e})}function s(t,e){return n.default.post("collect/del",{id:t,category:void 0===e?"product":e})}function u(t){return n.default.post("cart/add",t)}function d(){return n.default.get("category",{},{noAuth:!0})}function l(t){return n.default.get("products",t,{noAuth:!0})}function f(t,e){return n.default.get("product/hot",{page:void 0===t?1:t,limit:void 0===e?4:e},{noAuth:!0})}function v(t,e){return n.default.post("collect/all",{id:t,category:void 0===e?"product":e})}function p(t,e){return n.default.get("groom/list/"+t,e,{noAuth:!0})}function g(t){return n.default.get("collect/user",t)}function h(t,e){return n.default.get("reply/list/"+t,e)}function m(t){return n.default.get("reply/config/"+t)}function _(){return n.default.get("search/keyword",{},{noAuth:!0})}function w(t){return n.default.get("store_list",t)}function b(t){return n.default.get("store_discounts/list/"+t,{},{noAuth:!0})}function y(t){return n.default.post("v2/set_cart_num",t)}function C(t,e){return n.default.get("v2/get_attr/"+t+"/"+e)}function x(t){return n.default.get("home/products",t,{noAuth:!0})}function P(t){return n.default.get("advance/detail/"+t)}},"173d":function(t,e,o){"use strict";var i=o("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=o("2f62"),a=i(o("d13a")),c={name:"Home",props:{},mixins:[a.default],data:function(){return{top:"545"}},computed:(0,n.mapGetters)(["homeActive"]),methods:{setTouchMove:function(t){var e=this;t.touches[0].clientY<545&&t.touches[0].clientY>66&&(e.top=t.touches[0].clientY)},open:function(){this.homeActive?this.$store.commit("CLOSE_HOME"):this.$store.commit("OPEN_HOME")}},created:function(){},beforeDestroy:function(){this.$store.commit("CLOSE_HOME")}};e.default=c},"1a23":function(t,e,o){"use strict";var i;o.d(e,"b",(function(){return n})),o.d(e,"c",(function(){return a})),o.d(e,"a",(function(){return i}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[t.collectProductList.length?i("v-uni-view",{staticClass:"collectionGoods"},[t._l(t.collectProductList,(function(e,o){return i("v-uni-navigator",{key:o,staticClass:"item acea-row row-between-wrapper",attrs:{url:"/pages/goods_details/index?id="+e.pid,"hover-class":"none"}},[i("v-uni-view",{staticClass:"pictrue"},[i("v-uni-image",{attrs:{src:e.image}})],1),i("v-uni-view",{staticClass:"text acea-row row-column-between"},[i("v-uni-view",{staticClass:"name line1"},[t._v(t._s(e.store_name))]),i("v-uni-view",{staticClass:"acea-row row-between-wrapper"},[i("v-uni-view",{staticClass:"money font-color"},[t._v("￥"+t._s(e.price))]),i("v-uni-view",{staticClass:"delete",on:{click:function(i){i.stopPropagation(),arguments[0]=i=t.$handleEvent(i),t.delCollection(e.pid,o)}}},[t._v("删除")])],1)],1)],1)})),i("v-uni-view",{staticClass:"loadingicon acea-row row-center-wrapper"},[i("v-uni-text",{staticClass:"loading iconfont icon-jiazai",attrs:{hidden:0==t.loading}}),t._v(t._s(t.loadTitle))],1)],2):!t.collectProductList.length&&t.page>1?i("v-uni-view",{staticClass:"noCommodity"},[i("v-uni-view",{staticClass:"pictrue"},[i("v-uni-image",{attrs:{src:o("4b92")}})],1),i("recommend",{attrs:{hostProduct:t.hostProduct}})],1):t._e(),i("home")],1)},a=[]},"3d5d":function(t,e,o){"use strict";var i=o("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=o("2f62"),a=o("f519"),c=i(o("d13a")),r={computed:(0,n.mapGetters)(["uid"]),props:{hostProduct:{type:Array,default:function(){return[]}}},mixins:[c.default],data:function(){return{}},methods:{goDetail:function(t){(0,a.goShopDetail)(t,this.uid).then((function(e){uni.navigateTo({url:"/pages/goods_details/index?id=".concat(t.id)})}))}}};e.default=r},"400f":function(t,e,o){"use strict";o.r(e);var i=o("4d38"),n=o("cad3");for(var a in n)"default"!==a&&function(t){o.d(e,t,(function(){return n[t]}))}(a);o("9585");var c,r=o("f0c5"),s=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"2f7414c2",null,!1,i["a"],c);e["default"]=s.exports},4150:function(t,e,o){"use strict";var i=o("1170"),n=o.n(i);n.a},"4b92":function(t,e,o){t.exports=o.p+"static/img/noCollection.ad7c1108.png"},"4d38":function(t,e,o){"use strict";var i;o.d(e,"b",(function(){return n})),o.d(e,"c",(function(){return a})),o.d(e,"a",(function(){return i}));var n=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("v-uni-view",{staticClass:"recommend",style:t.colorStyle},[o("v-uni-view",{staticClass:"title acea-row row-center-wrapper"},[o("v-uni-text",{staticClass:"iconfont icon-zhuangshixian"}),o("v-uni-text",{staticClass:"name"},[t._v("热门推荐")]),o("v-uni-text",{staticClass:"iconfont icon-zhuangshixian lefticon"})],1),o("v-uni-view",{staticClass:"recommendList acea-row row-between-wrapper"},t._l(t.hostProduct,(function(e,i){return o("v-uni-view",{key:i,staticClass:"item",attrs:{"hover-class":"none"},on:{click:function(o){arguments[0]=o=t.$handleEvent(o),t.goDetail(e)}}},[o("v-uni-view",{staticClass:"pictrue"},[o("v-uni-image",{attrs:{src:e.image}}),e.activity&&"1"===e.activity.type?o("span",{staticClass:"pictrue_log_big pictrue_log_class"},[t._v("秒杀")]):t._e(),e.activity&&"2"===e.activity.type?o("span",{staticClass:"pictrue_log_big pictrue_log_class"},[t._v("砍价")]):t._e(),e.activity&&"3"===e.activity.type?o("span",{staticClass:"pictrue_log_big pictrue_log_class"},[t._v("拼团")]):t._e()],1),o("v-uni-view",{staticClass:"name line1"},[t._v(t._s(e.store_name))]),o("v-uni-view",{staticClass:"money font-color"},[t._v("￥"),o("v-uni-text",{staticClass:"num"},[t._v(t._s(e.price))])],1)],1)})),1)],1)},a=[]},"6db6":function(t,e,o){var i=o("24fb");e=i(!1),e.push([t.i,".pictrueBox[data-v-a68dde36]{width:%?130?%;height:%?120?%}\n\n/*返回主页按钮*/.home[data-v-a68dde36]{position:fixed;color:#fff;text-align:center;z-index:999;right:%?15?%;display:flex}.home .homeCon[data-v-a68dde36]{border-radius:%?50?%;opacity:0;height:0;color:var(--view-theme);width:0}.home .homeCon.on[data-v-a68dde36]{opacity:1;-webkit-animation:bounceInRight .5s cubic-bezier(.215,.61,.355,1);animation:bounceInRight .5s cubic-bezier(.215,.61,.355,1);width:%?300?%;height:%?86?%;margin-bottom:%?20?%;display:flex;justify-content:center;align-items:center;background:var(--view-theme)!important}.home .homeCon .iconfont[data-v-a68dde36]{font-size:%?48?%;color:#fff;display:inline-block;margin:0 auto}.home .pictrue[data-v-a68dde36]{width:%?86?%;height:%?86?%;border-radius:50%;margin:0 auto;background-color:var(--view-theme)}.home .pictrue .image[data-v-a68dde36]{width:100%;height:100%;border-radius:50%;-webkit-transform:rotate(90deg);transform:rotate(90deg);ms-transform:rotate(90deg);moz-transform:rotate(90deg);webkit-transform:rotate(90deg);o-transform:rotate(90deg)}",""]),t.exports=e},"7f24":function(t,e,o){var i=o("bd59");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=o("4f06").default;n("32ffcc88",i,!0,{sourceMap:!1,shadowMode:!1})},"851e":function(t,e,o){"use strict";var i=o("4ea4");o("99af"),o("a434"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(o("ade3")),a=o("1224"),c=o("2f62"),r=o("5d2c"),s=i(o("400f")),u=i(o("cdd5")),d=(0,n.default)({components:{recommend:s.default,home:u.default},data:function(){return{hostProduct:[],loadTitle:"加载更多",loading:!1,loadend:!1,collectProductList:[],limit:8,page:1,isAuto:!1,isShowAuth:!1,hotScroll:!1,hotPage:1,hotLimit:10}},computed:(0,c.mapGetters)(["isLogin"]),onLoad:function(){this.isLogin?(this.loadend=!1,this.page=1,this.collectProductList=[],this.get_user_collect_product(),this.get_host_product()):(0,r.toLogin)()},onShow:function(){this.loadend=!1,this.page=1,this.$set(this,"collectProductList",[]),this.get_user_collect_product()},onReachBottom:function(){this.get_user_collect_product()},methods:{onLoadFun:function(){this.loadend=!1,this.page=1,this.$set(this,"collectProductList",[]),this.get_user_collect_product(),this.get_host_product()},authColse:function(t){this.isShowAuth=t},get_user_collect_product:function(){var t=this;this.loading||this.loadend||(t.loading=!0,t.loadTitle="",(0,a.getCollectUserList)({page:t.page,limit:t.limit}).then((function(e){var o=e.data,i=o.length<t.limit;t.collectProductList=t.$util.SplitArray(o,t.collectProductList),t.$set(t,"collectProductList",t.collectProductList),t.loadend=i,t.loadTitle=i?"我也是有底线的":"加载更多",t.page=t.page+1,t.loading=!1})).catch((function(e){t.loading=!1,t.loadTitle="加载更多"})))},delCollection:function(t,e){var o=this;(0,a.collectDel)(t).then((function(t){return o.$util.Tips({title:"取消收藏成功",icon:"success"},(function(){o.collectProductList.splice(e,1),o.$set(o,"collectProductList",o.collectProductList)}))}))},get_host_product:function(){var t=this;t.hotScroll||(0,a.getProductHot)(t.hotPage,t.hotLimit).then((function(e){t.hotPage++,t.hotScroll=e.data.length<t.hotLimit,t.hostProduct=t.hostProduct.concat(e.data)}))}}},"onReachBottom",(function(){this.get_host_product()}));e.default=d},"932f":function(t,e,o){var i=o("0b86");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=o("4f06").default;n("5f96317a",i,!0,{sourceMap:!1,shadowMode:!1})},9585:function(t,e,o){"use strict";var i=o("932f"),n=o.n(i);n.a},a4ce:function(t,e,o){"use strict";var i;o.d(e,"b",(function(){return n})),o.d(e,"c",(function(){return a})),o.d(e,"a",(function(){return i}));var n=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("v-uni-view",{style:t.colorStyle},[o("v-uni-view",{staticStyle:{"touch-action":"none"}},[o("v-uni-view",{staticClass:"home",staticStyle:{position:"fixed"},style:{top:t.top+"px"},attrs:{id:"right-nav"},on:{touchmove:function(e){e.stopPropagation(),e.preventDefault(),arguments[0]=e=t.$handleEvent(e),t.setTouchMove.apply(void 0,arguments)}}},[t.homeActive?o("v-uni-view",{staticClass:"homeCon bg-color",class:!0===t.homeActive?"on":""},[o("v-uni-navigator",{staticClass:"iconfont icon-shouye-xianxing",attrs:{"hover-class":"none","open-type":"switchTab",url:"/pages/index/index"}}),o("v-uni-navigator",{staticClass:"iconfont icon-caigou-xianxing",attrs:{"hover-class":"none","open-type":"switchTab",url:"/pages/order_addcart/order_addcart"}}),o("v-uni-navigator",{staticClass:"iconfont icon-yonghu1",attrs:{"hover-class":"none","open-type":"switchTab",url:"/pages/user/index"}})],1):t._e(),o("v-uni-view",{staticClass:"pictrueBox",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.open.apply(void 0,arguments)}}},[o("v-uni-view",{staticClass:"pictrue"},[o("v-uni-image",{staticClass:"image",attrs:{src:!0===t.homeActive?"/static/images/close.gif":"/static/images/open.gif"}})],1)],1)],1)],1)],1)},a=[]},a628:function(t,e,o){"use strict";o.r(e);var i=o("851e"),n=o.n(i);for(var a in i)"default"!==a&&function(t){o.d(e,t,(function(){return i[t]}))}(a);e["default"]=n.a},bd59:function(t,e,o){var i=o("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.collectionGoods[data-v-77610112]{background-color:#fff;border-top:%?1?% solid #eee}.collectionGoods .item[data-v-77610112]{margin-left:%?30?%;padding-right:%?30?%;border-bottom:%?1?% solid #eee;height:%?180?%}.collectionGoods .item .pictrue[data-v-77610112]{width:%?130?%;height:%?130?%}.collectionGoods .item .pictrue uni-image[data-v-77610112]{width:100%;height:100%;border-radius:%?6?%}.collectionGoods .item .text[data-v-77610112]{width:%?535?%;height:%?130?%;font-size:%?28?%;color:#282828}.collectionGoods .item .text .name[data-v-77610112]{width:100%}.collectionGoods .item .text .money[data-v-77610112]{font-size:%?26?%}.collectionGoods .item .text .delete[data-v-77610112]{font-size:%?26?%;color:#282828;width:%?144?%;height:%?46?%;border:1px solid #bbb;border-radius:%?4?%;text-align:center;line-height:%?46?%}.noCommodity[data-v-77610112]{background-color:#fff;padding-top:%?1?%;border-top:0}',""]),t.exports=e},c0e5:function(t,e,o){"use strict";o.r(e);var i=o("1a23"),n=o("a628");for(var a in n)"default"!==a&&function(t){o.d(e,t,(function(){return n[t]}))}(a);o("fe6f");var c,r=o("f0c5"),s=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"77610112",null,!1,i["a"],c);e["default"]=s.exports},cad3:function(t,e,o){"use strict";o.r(e);var i=o("3d5d"),n=o.n(i);for(var a in i)"default"!==a&&function(t){o.d(e,t,(function(){return i[t]}))}(a);e["default"]=n.a},cdd5:function(t,e,o){"use strict";o.r(e);var i=o("a4ce"),n=o("0831");for(var a in n)"default"!==a&&function(t){o.d(e,t,(function(){return n[t]}))}(a);o("4150");var c,r=o("f0c5"),s=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"a68dde36",null,!1,i["a"],c);e["default"]=s.exports},f519:function(t,e,o){"use strict";o("99af"),o("d3b7"),Object.defineProperty(e,"__esModule",{value:!0}),e.goShopDetail=n,e.goPage=a;var i=getApp();function n(t,e){return new Promise((function(o){t.activity&&"1"===t.activity.type?uni.navigateTo({url:"/pages/activity/goods_seckill_details/index?id=".concat(t.activity.id,"&time=").concat(t.activity.time,"&status=1")}):t.activity&&"2"===t.activity.type?uni.navigateTo({url:"/pages/activity/goods_bargain_details/index?id=".concat(t.activity.id,"&bargain=").concat(e)}):t.activity&&"3"===t.activity.type?uni.navigateTo({url:"/pages/activity/goods_combination_details/index?id=".concat(t.activity.id)}):o(t)}))}function a(){return new Promise((function(t){if(0!=i.globalData.isIframe)return!1;t(!0)}))}},fe6f:function(t,e,o){"use strict";var i=o("7f24"),n=o.n(i);n.a}}]);