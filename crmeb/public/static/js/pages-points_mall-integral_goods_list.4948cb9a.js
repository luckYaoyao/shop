(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-points_mall-integral_goods_list"],{"168d":function(t,e,i){"use strict";i.r(e);var n=i("b323"),a=i.n(n);for(var r in n)"default"!==r&&function(t){i.d(e,t,(function(){return n[t]}))}(r);e["default"]=a.a},"709f":function(t,e,i){"use strict";var n=i("8132"),a=i.n(n);a.a},8132:function(t,e,i){var n=i("d1a5");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("bc3b9cd2",n,!0,{sourceMap:!1,shadowMode:!1})},b323:function(t,e,i){"use strict";var n=i("4ea4");i("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n(i("c872")),r=i("5019"),s=i("f4f09"),o=n(i("ecac")),c=i("26cb"),u=i("1754"),d=n(i("2d3a")),l={computed:(0,c.mapGetters)(["uid"]),components:{recommend:o.default,home:a.default},mixins:[d.default],data:function(){return{productList:[],is_switch:!0,where:{store_name:"",priceOrder:"",salesOrder:"",page:1,limit:20},price:0,stock:0,nows:!1,loadend:!1,loading:!1,loadTitle:"加载更多",title:"",hostProduct:[],hotPage:1,hotLimit:10,hotScroll:!1}},onLoad:function(t){this.where.cid=t.cid||0,this.$set(this.where,"sid",t.sid||0),this.title=t.title||"",this.$set(this.where,"store_name",t.searchValue||""),this.get_product_list(),this.get_host_product()},methods:{godDetail:function(t){(0,u.goShopDetail)(t,this.uid).then((function(e){uni.navigateTo({url:"/pages/points_mall/integral_goods_details?id=".concat(t.id)})}))},Changswitch:function(){var t=this;t.is_switch=!t.is_switch},searchSubmit:function(t){var e=this;e.$set(e.where,"store_name",t.detail.value),e.loadend=!1,e.$set(e.where,"page",1),this.get_product_list(!0)},get_host_product:function(){var t=this;t.hotScroll||(0,r.getProductHot)(t.hotPage,t.hotLimit).then((function(e){t.hotPage++,t.hotScroll=e.data.length<t.hotLimit,t.hostProduct=t.hostProduct.concat(e.data)}))},set_where:function(t){switch(t){case 1:this.where={store_name:"",priceOrder:"",salesOrder:"",page:1,limit:20},this.price=0,this.stock=0;break;case 2:0==this.price?this.price=1:1==this.price?this.price=2:2==this.price&&(this.price=0),this.stock=0;break;case 3:0==this.stock?this.stock=1:1==this.stock?this.stock=2:2==this.stock&&(this.stock=0),this.price=0;break;case 4:this.nows=!this.nows;break}this.loadend=!1,this.$set(this.where,"page",1),this.get_product_list(!0)},setWhere:function(){0==this.price?this.where.priceOrder="":1==this.price?this.where.priceOrder="asc":2==this.price&&(this.where.priceOrder="desc"),0==this.stock?this.where.salesOrder="":1==this.stock?this.where.salesOrder="asc":2==this.stock&&(this.where.salesOrder="desc"),this.where.news=this.nows?1:0},get_product_list:function(t){var e=this;e.setWhere(),e.loadend||e.loading||(!0===t&&e.$set(e,"productList",[]),e.loading=!0,e.loadTitle="",(0,s.getStoreIntegralList)(e.where).then((function(t){var i=t.data,n=e.$util.SplitArray(i,e.productList),a=i.length<e.where.limit;e.loadend=a,e.loading=!1,e.loadTitle=a?"已全部加载":"加载更多",e.$set(e,"productList",n),e.$set(e.where,"page",e.where.page+1)})).catch((function(t){e.loading=!1,e.loadTitle="加载更多"})))}},onPullDownRefresh:function(){},onReachBottom:function(){this.productList.length>0?this.get_product_list():this.get_host_product()}};e.default=l},d106:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return r})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{style:t.colorStyle},[n("v-uni-view",{staticClass:"productList"},[n("v-uni-view",{staticClass:"search bg-color acea-row row-between-wrapper"},[n("v-uni-view",{staticClass:"input acea-row row-between-wrapper"},[n("v-uni-text",{staticClass:"iconfont icon-sousuo"}),n("v-uni-input",{attrs:{placeholder:"搜索商品名称","placeholder-class":"placeholder","confirm-type":"search",name:"search",value:t.where.store_name},on:{confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.searchSubmit.apply(void 0,arguments)}}})],1),n("v-uni-view",{staticClass:"iconfont",class:1==t.is_switch?"icon-pailie":"icon-tupianpailie",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.Changswitch.apply(void 0,arguments)}}})],1),n("v-uni-view",{staticClass:"nav acea-row row-middle"},[n("v-uni-view",{staticClass:"item line1",class:t.title?"font-num":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.set_where(1)}}},[t._v("默认")]),n("v-uni-view",{staticClass:"item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.set_where(2)}}},[t._v("积分"),1==t.price?n("v-uni-image",{attrs:{src:i("cd32")}}):2==t.price?n("v-uni-image",{attrs:{src:i("3a00")}}):n("v-uni-image",{attrs:{src:i("d7843")}})],1),n("v-uni-view",{staticClass:"item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.set_where(3)}}},[t._v("销量"),1==t.stock?n("v-uni-image",{attrs:{src:i("cd32")}}):2==t.stock?n("v-uni-image",{attrs:{src:i("3a00")}}):n("v-uni-image",{attrs:{src:i("d7843")}})],1)],1),n("v-uni-view",{staticClass:"list acea-row row-between-wrapper",class:1==t.is_switch?"":"on"},[t._l(t.productList,(function(e,a){return n("v-uni-view",{key:a,staticClass:"item",class:1==t.is_switch?"":"on",attrs:{"hover-class":"none"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.godDetail(e)}}},[n("v-uni-view",{staticClass:"pictrue",class:1==t.is_switch?"":"on"},[n("v-uni-image",{class:1==t.is_switch?"":"on",attrs:{src:e.image}})],1),n("v-uni-view",{staticClass:"text",class:1==t.is_switch?"":"on"},[n("v-uni-view",{staticClass:"name line1"},[t._v(t._s(e.title))]),n("v-uni-view",{staticClass:"money font-color",class:1==t.is_switch?"":"on"},[n("v-uni-text",{staticClass:"num"},[t._v(t._s(e.price)+"积分")])],1),n("v-uni-view",{staticClass:"vip acea-row row-between-wrapper",class:1==t.is_switch?"":"on"},[e.vip_price&&e.vip_price>0?n("v-uni-view",{staticClass:"vip-money"},[t._v(t._s(e.vip_price)+"积分"),n("v-uni-image",{attrs:{src:i("7767")}})],1):t._e(),n("v-uni-view",{staticClass:"sales"},[n("v-uni-view",{},[t._v(t._s(e.sales)+"人兑换")]),n("v-uni-view",{staticClass:"exchange"},[t._v("兑换")])],1)],1)],1)],1)})),t.productList.length>0?n("v-uni-view",{staticClass:"loadingicon acea-row row-center-wrapper"},[n("v-uni-text",{staticClass:"loading iconfont icon-jiazai",attrs:{hidden:0==t.loading}}),t._v(t._s(t.loadTitle))],1):t._e()],2)],1),0==t.productList.length&&t.where.page>1?n("v-uni-view",{staticClass:"noCommodity"},[n("v-uni-view",{staticClass:"pictrue"},[n("v-uni-image",{attrs:{src:i("1caf")}})],1),n("recommend",{attrs:{hostProduct:t.hostProduct}})],1):t._e(),n("home")],1)},r=[]},d1a5:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.productList .search[data-v-032f58c1]{width:100%;height:%?86?%;padding-left:%?23?%;box-sizing:border-box;position:fixed;left:0;top:0;z-index:9}.productList .search .input[data-v-032f58c1]{width:%?640?%;height:%?60?%;background-color:#fff;border-radius:%?50?%;padding:0 %?20?%;box-sizing:border-box}.productList .search .input uni-input[data-v-032f58c1]{width:%?548?%;height:100%;font-size:%?26?%}.productList .search .input .placeholder[data-v-032f58c1]{color:#999}.productList .search .input .iconfont[data-v-032f58c1]{font-size:%?35?%;color:#555}.productList .search .icon-pailie[data-v-032f58c1],\n.productList .search .icon-tupianpailie[data-v-032f58c1]{color:#fff;width:%?62?%;font-size:%?40?%;height:%?86?%;line-height:%?86?%}.productList .nav[data-v-032f58c1]{height:%?86?%;color:#454545;position:fixed;left:0;width:100%;font-size:%?28?%;background-color:#fff;margin-top:%?86?%;top:0;z-index:9}.productList .nav .item[data-v-032f58c1]{width:33%;text-align:center}.productList .nav .item uni-image[data-v-032f58c1]{width:%?15?%;height:%?19?%;margin-left:%?10?%}.productList .list[data-v-032f58c1]{padding:0 %?20?%;margin-top:%?172?%}.productList .list.on[data-v-032f58c1]{background-color:#fff;border-top:1px solid #f6f6f6}.productList .list .item[data-v-032f58c1]{width:%?345?%;margin-top:%?20?%;background-color:#fff;border-radius:%?20?%}.productList .list .item.on[data-v-032f58c1]{width:100%;display:flex;border-bottom:%?1?% solid #f6f6f6;padding:%?30?% 0;margin:0}.productList .list .item .pictrue[data-v-032f58c1]{position:relative;width:100%;height:%?345?%}.productList .list .item .pictrue.on[data-v-032f58c1]{width:%?180?%;height:%?180?%}.productList .list .item .pictrue uni-image[data-v-032f58c1]{width:100%;height:100%;border-radius:%?20?% %?20?% 0 0}.productList .list .item .pictrue uni-image.on[data-v-032f58c1]{border-radius:%?6?%}.productList .list .item .text[data-v-032f58c1]{padding:%?20?% %?17?% %?26?% %?17?%;font-size:%?30?%;color:#222}.productList .list .item .text.on[data-v-032f58c1]{width:%?508?%;padding:0 0 0 %?22?%}.productList .list .item .text .money[data-v-032f58c1]{font-size:%?22?%;margin-top:%?8?%}.productList .list .item .text .money.on[data-v-032f58c1]{margin-top:%?50?%}.productList .list .item .text .money .num[data-v-032f58c1]{font-size:%?34?%}.productList .list .item .text .vip[data-v-032f58c1]{font-size:%?22?%;margin-top:%?7?%}.productList .list .item .text .vip .sales[data-v-032f58c1]{color:#aaa;width:100%;display:flex;justify-content:space-between}.productList .list .item .text .vip .sales .exchange[data-v-032f58c1]{border:1px solid var(--view-theme);border-radius:%?20?%;padding:0 %?12?%;color:var(--view-theme)}.productList .list .item .text .vip.on[data-v-032f58c1]{margin-top:%?12?%}.productList .list .item .text .vip .vip-money[data-v-032f58c1]{font-size:%?24?%;color:#282828;font-weight:700}.productList .list .item .text .vip .vip-money uni-image[data-v-032f58c1]{width:%?46?%;height:%?21?%;margin-left:%?4?%}.noCommodity[data-v-032f58c1]{background-color:#fff;padding-bottom:%?30?%}',""]),t.exports=e},f4f09:function(t,e,i){"use strict";var n=i("4ea4");i("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.getCombinationList=r,e.getCombinationDetail=s,e.getCombinationPink=o,e.postCombinationRemove=c,e.getBargainList=u,e.getCombinationBannerList=d,e.getPink=l,e.getBargainUserList=f,e.getBargainDetail=p,e.postBargainStartUser=h,e.postBargainStart=g,e.postBargainHelp=v,e.postBargainHelpPrice=m,e.postBargainHelpList=w,e.postBargainHelpCount=_,e.postBargainShare=b,e.getSeckillIndexTime=L,e.getSeckillList=k,e.getSeckillDetail=C,e.getBargainPoster=x,e.getCombinationPoster=y,e.getBargainUserCancel=P,e.seckillCode=O,e.scombinationCode=B,e.getCombinationPosterData=S,e.getBargainPosterData=$,e.integralOrderConfirm=z,e.integralOrderCreate=D,e.integralOrderDetails=A,e.getIntegralProductDetail=T,e.getStoreIntegralList=E,e.getIntegralOrderList=I,e.getLogisticsDetails=j,e.orderTake=H,e.orderDel=U,e.getPresellList=M;var a=n(i("0302"));function r(t){return a.default.get("combination/list",t,{noAuth:!0})}function s(t){return a.default.get("combination/detail/"+t)}function o(t){return a.default.get("combination/pink/"+t)}function c(t){return a.default.post("combination/remove",t)}function u(t){return a.default.get("bargain/list",t,{noAuth:!0})}function d(t){return a.default.get("combination/banner_list",t,{noAuth:!0})}function l(t){return a.default.get("pink",t,{noAuth:!0})}function f(t){return a.default.get("bargain/user/list",t)}function p(t,e){return a.default.get("bargain/detail/".concat(t,"?bargainUid=").concat(e))}function h(t){return a.default.post("bargain/start/user",t)}function g(t){return a.default.post("bargain/start",{bargainId:t})}function v(t){return a.default.post("bargain/help",t)}function m(t){return a.default.post("bargain/help/price",t)}function w(t){return a.default.post("bargain/help/list",t)}function _(t){return a.default.post("bargain/help/count",t)}function b(t){return a.default.post("bargain/share",{bargainId:t})}function L(){return a.default.get("seckill/index",{},{noAuth:!0})}function k(t,e){return a.default.get("seckill/list/"+t,e,{noAuth:!0})}function C(t,e){return a.default.get("seckill/detail/"+t,e)}function x(t){return a.default.post("bargain/poster",t)}function y(t){return a.default.post("combination/poster",t)}function P(t){return a.default.post("bargain/user/cancel",t)}function O(t,e){return a.default.get("seckill/code/"+t,e)}function B(t){return a.default.get("combination/code/"+t)}function S(t){return a.default.get("combination/poster_info/"+t)}function $(t){return a.default.get("bargain/poster_info/"+t)}function z(t){return a.default.post("store_integral/order/confirm",t)}function D(t){return a.default.post("store_integral/order/create",t)}function A(t){return a.default.get("store_integral/order/detail/".concat(t))}function T(t){return a.default.get("store_integral/detail/"+t,{},{noAuth:!0})}function E(t){return a.default.get("store_integral/list",t)}function I(t){return a.default.get("store_integral/order/list",t)}function j(t){return a.default.get("store_integral/order/express/".concat(t))}function H(t){return a.default.post("store_integral/order/take",t)}function U(t){return a.default.post("store_integral/order/del",t)}function M(t){return a.default.get("advance/list",t)}},fefd:function(t,e,i){"use strict";i.r(e);var n=i("d106"),a=i("168d");for(var r in a)"default"!==r&&function(t){i.d(e,t,(function(){return a[t]}))}(r);i("709f");var s,o=i("f0c5"),c=Object(o["a"])(a["default"],n["b"],n["c"],!1,null,"032f58c1",null,!1,n["a"],s);e["default"]=c.exports}}]);