(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-points_mall-integral_goods_details"],{"075d":function(t,e,i){"use strict";var a=i("86bd"),r=i.n(a);r.a},"0c7b":function(t,e,i){"use strict";i.d(e,"b",(function(){return r})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var a={cusPreviewImg:i("d010").default},r=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{style:t.colorStyle},[i("v-uni-view",{staticClass:"product-con"},[i("v-uni-scroll-view",{style:"height:"+t.height+"px;",attrs:{"scroll-top":t.scrollTop,"scroll-y":"true","scroll-with-animation":"true"},on:{scroll:function(e){arguments[0]=e=t.$handleEvent(e),t.scroll.apply(void 0,arguments)}}},[i("v-uni-view",{attrs:{id:"past0"}},[i("productConSwiper",{attrs:{imgUrls:t.imgUrls}}),i("v-uni-view",{staticClass:"nav acea-row row-between-wrapper"},[i("v-uni-view",{staticClass:"share acea-row row-between row-bottom"},[i("v-uni-view",{staticClass:"money font-color"},[i("v-uni-image",{attrs:{src:"/static/images/my-point.png",mode:""}}),i("v-uni-text",{staticClass:"num",domProps:{textContent:t._s(t.storeInfo.price||0)}}),t._v("积分")],1),i("v-uni-view")],1)],1),i("v-uni-view",{staticClass:"wrapper"},[i("v-uni-view",{staticClass:"introduce acea-row row-between"},[i("v-uni-view",{staticClass:"infor"},[t._v(t._s(t.storeInfo.title))])],1),i("v-uni-view",{staticClass:"label acea-row row-middle"},[i("v-uni-view",{staticClass:"stock"},[t._v("原价："+t._s(t.storeInfo.product_price))]),i("v-uni-view",{staticClass:"stock"},[t._v("限量:\n\t\t\t\t\t\t\t"+t._s(t.storeInfo.quota_show))]),i("v-uni-view",{staticClass:"stock"},[t._v("已兑换："+t._s(t.storeInfo.sales))])],1)],1),t.attribute.productAttr.length?i("v-uni-view",{staticClass:"attribute acea-row row-between-wrapper",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.selecAttr.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"flex"},[i("v-uni-view",{staticStyle:{display:"flex","align-items":"center",width:"90%"}},[i("v-uni-view",{staticClass:"attr-txt"},[t._v(t._s(t.attr)+"：")]),i("v-uni-view",{staticClass:"atterTxt line1",staticStyle:{width:"82%"}},[t._v(t._s(t.attrValue))])],1),i("v-uni-view",{staticClass:"iconfont icon-jiantou"})],1),t.skuArr.length>1?i("v-uni-view",{staticClass:"acea-row row-between-wrapper",staticStyle:{"margin-top":"7px","padding-left":"70px"}},[i("v-uni-view",{staticClass:"flexs"},t._l(t.skuArr.slice(0,4),(function(t,e){return i("v-uni-image",{key:e,staticClass:"attrImg",attrs:{src:t.image}})})),1),i("v-uni-view",{staticClass:"switchTxt"},[t._v("共"+t._s(t.skuArr.length)+"种规格可选")])],1):t._e()],1):t._e()],1),i("v-uni-view",{staticClass:"product-intro",attrs:{id:"past2"}},[i("v-uni-view",{staticClass:"title"},[t._v("产品介绍")]),i("v-uni-view",{staticClass:"conter"},[i("v-uni-view",{domProps:{innerHTML:t._s(t.storeInfo.description)}})],1)],1)],1),i("v-uni-view",{staticClass:"footer acea-row row-between-wrapper"},[i("v-uni-navigator",{staticClass:"item",attrs:{"hover-class":"none","open-type":"switchTab",url:"/pages/index/index"}},[i("v-uni-view",{staticClass:"iconfont icon-shouye6"}),i("v-uni-view",{staticClass:"p_center"},[t._v("首页")])],1),t.attribute.productSelect.quota>0&&t.attribute.productSelect.product_stock>0?i("v-uni-view",{staticClass:"bnt acea-row"},[i("v-uni-view",{staticClass:"buy bnts",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goCat.apply(void 0,arguments)}}},[t._v("立即兑换")])],1):i("v-uni-view",{staticClass:"bnt acea-row"},[i("v-uni-view",{staticClass:"bnts no-goods"},[t._v("无法兑换")])],1)],1)],1),i("product-window",{attrs:{attr:t.attribute,limitNum:1},on:{myevent:function(e){arguments[0]=e=t.$handleEvent(e),t.onMyEvent.apply(void 0,arguments)},ChangeAttr:function(e){arguments[0]=e=t.$handleEvent(e),t.ChangeAttr.apply(void 0,arguments)},ChangeCartNum:function(e){arguments[0]=e=t.$handleEvent(e),t.ChangeCartNum.apply(void 0,arguments)},attrVal:function(e){arguments[0]=e=t.$handleEvent(e),t.attrVal.apply(void 0,arguments)},iptCartNum:function(e){arguments[0]=e=t.$handleEvent(e),t.iptCartNum.apply(void 0,arguments)},getImg:function(e){arguments[0]=e=t.$handleEvent(e),t.showImg.apply(void 0,arguments)}}}),i("cus-previewImg",{ref:"cusPreviewImg",attrs:{list:t.skuArr},on:{changeSwitch:function(e){arguments[0]=e=t.$handleEvent(e),t.changeSwitch.apply(void 0,arguments)},shareFriend:function(e){arguments[0]=e=t.$handleEvent(e),t.listenerActionSheet.apply(void 0,arguments)}}}),i("kefuIcon",{attrs:{ids:t.storeInfo.product_id,routineContact:t.routineContact}})],1)},o=[]},2527:function(t,e,i){"use strict";var a=i("e1331"),r=i.n(a);r.a},3589:function(t,e,i){"use strict";var a=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.getCombinationList=o,e.getCombinationDetail=n,e.getCombinationPink=s,e.postCombinationRemove=c,e.getBargainList=u,e.getCombinationBannerList=d,e.getPink=l,e.getBargainUserList=p,e.getBargainDetail=f,e.postBargainStartUser=h,e.postBargainStart=b,e.postBargainHelp=v,e.postBargainHelpPrice=g,e.postBargainHelpList=m,e.postBargainHelpCount=w,e.postBargainShare=x,e.getSeckillIndexTime=_,e.getSeckillList=C,e.getSeckillDetail=S,e.getBargainPoster=k,e.getCombinationPoster=y,e.getBargainUserCancel=$,e.seckillCode=A,e.scombinationCode=I,e.getCombinationPosterData=z,e.getBargainPosterData=T,e.integralOrderConfirm=q,e.integralOrderCreate=D,e.integralOrderDetails=P,e.getIntegralProductDetail=V,e.getStoreIntegralList=L,e.getIntegralOrderList=j,e.getLogisticsDetails=E,e.orderTake=N,e.orderDel=B,e.getPresellList=M;var r=a(i("ac7c"));function o(t){return r.default.get("combination/list",t,{noAuth:!0})}function n(t){return r.default.get("combination/detail/"+t)}function s(t){return r.default.get("combination/pink/"+t)}function c(t){return r.default.post("combination/remove",t)}function u(t){return r.default.get("bargain/list",t,{noAuth:!0})}function d(t){return r.default.get("combination/banner_list",t,{noAuth:!0})}function l(t){return r.default.get("pink",t,{noAuth:!0})}function p(t){return r.default.get("bargain/user/list",t)}function f(t){return r.default.get("bargain/detail/"+t)}function h(t){return r.default.post("bargain/start/user",t)}function b(t){return r.default.post("bargain/start",{bargainId:t})}function v(t){return r.default.post("bargain/help",t)}function g(t){return r.default.post("bargain/help/price",t)}function m(t){return r.default.post("bargain/help/list",t)}function w(t){return r.default.post("bargain/help/count",t)}function x(t){return r.default.post("bargain/share",{bargainId:t})}function _(){return r.default.get("seckill/index",{},{noAuth:!0})}function C(t,e){return r.default.get("seckill/list/"+t,e,{noAuth:!0})}function S(t,e){return r.default.get("seckill/detail/"+t,e)}function k(t){return r.default.post("bargain/poster",t)}function y(t){return r.default.post("combination/poster",t)}function $(t){return r.default.post("bargain/user/cancel",t)}function A(t,e){return r.default.get("seckill/code/"+t,e)}function I(t){return r.default.get("combination/code/"+t)}function z(t){return r.default.get("combination/poster_info/"+t)}function T(t){return r.default.get("bargain/poster_info/"+t)}function q(t){return r.default.post("store_integral/order/confirm",t)}function D(t){return r.default.post("store_integral/order/create",t)}function P(t){return r.default.get("store_integral/order/detail/".concat(t))}function V(t){return r.default.get("store_integral/detail/"+t,{},{noAuth:!0})}function L(t){return r.default.get("store_integral/list",t)}function j(t){return r.default.get("store_integral/order/list",t)}function E(t){return r.default.get("store_integral/order/express/".concat(t))}function N(t){return r.default.post("store_integral/order/take",t)}function B(t){return r.default.post("store_integral/order/del",t)}function M(t){return r.default.get("advance/list",t)}},"3b04":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* crmeb颜色变量 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.vip-money[data-v-0ec34deb]{color:#282828;font-size:%?28?%;font-weight:700;margin-left:%?6?%}.vipImg[data-v-0ec34deb]{width:%?68?%;height:%?27?%}.vipImg uni-image[data-v-0ec34deb]{width:100%;height:100%}.product-window[data-v-0ec34deb]{position:fixed;bottom:0;width:100%;left:0;background-color:#fff;z-index:100;border-radius:%?16?% %?16?% 0 0;-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0);transition:all .3s cubic-bezier(.25,.5,.5,.9);padding-bottom:%?140?%;padding-bottom:calc(140rpx+ constant(safe-area-inset-bottom));padding-bottom:calc(%?140?% + env(safe-area-inset-bottom))}.product-window.on[data-v-0ec34deb]{-webkit-transform:translateZ(0);transform:translateZ(0)}.product-window.join[data-v-0ec34deb]{padding-bottom:%?30?%}.product-window.joinCart[data-v-0ec34deb]{padding-bottom:%?30?%;z-index:10000}.product-window .textpic[data-v-0ec34deb]{padding:0 %?130?% 0 %?30?%;margin-top:%?29?%;position:relative}.product-window .textpic .pictrue[data-v-0ec34deb]{width:%?150?%;height:%?150?%}.product-window .textpic .pictrue uni-image[data-v-0ec34deb]{width:100%;height:100%;border-radius:%?10?%}.product-window .textpic .text[data-v-0ec34deb]{width:%?410?%;font-size:%?32?%;color:#202020}.product-window .textpic .text .money[data-v-0ec34deb]{font-size:%?24?%;margin-top:%?40?%}.product-window .textpic .text .money .num[data-v-0ec34deb]{font-size:%?36?%}.product-window .textpic .text .money .stock[data-v-0ec34deb]{color:#999;margin-left:%?6?%}.product-window .textpic .iconfont[data-v-0ec34deb]{position:absolute;right:%?30?%;top:%?-5?%;font-size:%?35?%;color:#8a8a8a}.product-window .rollTop[data-v-0ec34deb]{max-height:%?520?%;overflow:auto;margin-top:%?36?%}.product-window .productWinList .item ~ .item[data-v-0ec34deb]{margin-top:%?36?%}.product-window .productWinList .item .title[data-v-0ec34deb]{font-size:%?30?%;color:#999;padding:0 %?30?%}.product-window .productWinList .item .listn[data-v-0ec34deb]{padding:0 %?30?% 0 %?16?%}.product-window .productWinList .item .listn .itemn[data-v-0ec34deb]{border:1px solid #f2f2f2;font-size:%?26?%;color:#282828;padding:%?7?% %?33?%;border-radius:%?25?%;margin:%?20?% 0 0 %?14?%;background-color:#f2f2f2}.product-window .productWinList .item .listn .itemn.on[data-v-0ec34deb]{color:var(--view-theme);background:var(--view-minorColorT);border-color:var(--view-theme)}.product-window .productWinList .item .listn .itemn.limit[data-v-0ec34deb]{color:#999;text-decoration:line-through}.product-window .cart[data-v-0ec34deb]{margin-top:%?36?%;padding:0 %?30?%}.product-window .cart .title[data-v-0ec34deb]{font-size:%?30?%;color:#999}.product-window .cart .carnum[data-v-0ec34deb]{height:%?54?%;margin-top:%?24?%}.product-window .cart .carnum .iconfont[data-v-0ec34deb]{font-size:%?25?%}.product-window .cart .carnum uni-view[data-v-0ec34deb]{width:%?84?%;text-align:center;height:100%;line-height:%?54?%;color:#282828;font-size:%?45?%}.product-window .cart .carnum .reduce[data-v-0ec34deb]{border-right:0;border-radius:%?6?% 0 0 %?6?%;line-height:%?48?%;font-size:%?60?%}.product-window .cart .carnum .reduce.on[data-v-0ec34deb]{color:#dedede}.product-window .cart .carnum .plus[data-v-0ec34deb]{border-left:0;border-radius:0 %?6?% %?6?% 0;line-height:%?46?%;color:#dedede}.product-window .cart .carnum .plus.on[data-v-0ec34deb]{color:#282828}.product-window .cart .carnum .num[data-v-0ec34deb]{background:#f2f2f2;color:#282828;font-size:%?28?%}.product-window .joinBnt[data-v-0ec34deb]{font-size:%?30?%;width:%?620?%;height:%?86?%;border-radius:%?50?%;text-align:center;line-height:%?86?%;color:#fff;margin:%?21?% auto 0 auto}.product-window .joinBnt.on[data-v-0ec34deb]{background-color:#bbb;color:#fff}',""]),t.exports=e},"3dd4":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* crmeb颜色变量 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.generate-posters[data-v-60b5b360]{width:100%;height:%?170?%;background-color:#fff;position:fixed;left:0;bottom:0;z-index:300;-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0);transition:all .3s cubic-bezier(.25,.5,.5,.9);border-top:%?1?% solid #eee}.generate-posters.on[data-v-60b5b360]{-webkit-transform:translateZ(0);transform:translateZ(0)}.generate-posters .item[data-v-60b5b360]{flex:1;text-align:center;font-size:%?30?%}.generate-posters .item .iconfont[data-v-60b5b360]{font-size:%?80?%;color:#5eae72}.generate-posters .item .iconfont.icon-haibao[data-v-60b5b360]{color:#5391f1}.navbar .header[data-v-60b5b360]{height:%?96?%;font-size:%?30?%;color:#050505;background-color:#fff}.icon-xiangzuo[data-v-60b5b360]{top:%?30?%!important}.navbar .header .item[data-v-60b5b360]{position:relative;margin:0 %?25?%}.navbar .header .item.on[data-v-60b5b360]:before{position:absolute;width:%?60?%;height:%?5?%;background-repeat:no-repeat;content:"";background-image:linear-gradient(90deg,#f36 0,#ff6533);bottom:%?-10?%;left:50%;margin-left:%?-28?%}.navbar[data-v-60b5b360]{position:fixed;background-color:#fff;top:0;left:0;z-index:99;width:100%}.navbar .navbarH[data-v-60b5b360]{position:relative}.navbar .navbarH .navbarCon[data-v-60b5b360]{position:absolute;bottom:0;height:%?100?%;width:100%}.icon-xiangzuo[data-v-60b5b360]{\r\n  /* color: #000;\r\n\t\tposition: fixed;\r\n\t\tfont-size: 40rpx;\r\n\t\twidth: 100rpx;\r\n\t\theight: 56rpx;\r\n\t\tline-height: 54rpx;\r\n\t\tz-index: 1000;\r\n\t\tleft: 33rpx; */}.product-con .nav[data-v-60b5b360]{width:100%;height:%?100?%;padding:0 %?30?%;box-sizing:border-box;background-color:#fff}.product-con .nav .money[data-v-60b5b360]{font-size:%?28?%;color:#e93323;font-weight:700}.product-con .nav .money uni-image[data-v-60b5b360]{width:%?34?%;height:%?34?%}.product-con .nav .money .num[data-v-60b5b360]{font-size:%?48?%;padding-left:%?16?%}.product-con .nav .money .y-money[data-v-60b5b360]{font-size:%?26?%;margin-left:%?10?%;text-decoration:line-through}.product-con .nav .timeItem[data-v-60b5b360]{font-size:%?20?%;color:#fff;text-align:center}.product-con .nav .timeItem .timeCon[data-v-60b5b360]{margin-top:%?10?%}.product-con .nav .timeItem .timeCon .num[data-v-60b5b360]{padding:0 %?7?%;font-size:%?22?%;color:#ff3d3d;background-color:#fff;border-radius:%?2?%}.product-con .nav .timeState[data-v-60b5b360]{font-size:%?28?%;color:#fff}.product-con .nav .iconfont[data-v-60b5b360]{color:#fff;font-size:%?30?%;margin-left:%?20?%}.product-con .wrapper[data-v-60b5b360]{padding:0 %?32?% %?32?% %?32?%;width:100%;box-sizing:border-box}.product-con .wrapper .introduce[data-v-60b5b360]{margin:0}.product-con .wrapper .introduce .iconfont[data-v-60b5b360]{font-size:%?37?%;color:#515151}.product-con .wrapper .label[data-v-60b5b360]{display:flex;justify-content:space-between;margin:%?18?% 0 0 0;font-size:%?24?%;color:#82848f}.product-con .footer[data-v-60b5b360]{padding:0 %?20?% 0 %?30?%;position:fixed;bottom:0;width:100%;box-sizing:border-box;background-color:#fff;z-index:277;border-top:%?1?% solid #f0f0f0;height:%?100?%;display:flex;align-items:center;flex-wrap:nowrap;height:calc(100rpx+ constant(safe-area-inset-bottom));height:calc(%?100?% + env(safe-area-inset-bottom))}.product-con .footer .item[data-v-60b5b360]{width:%?100?%;font-size:%?18?%;color:#666}.product-con .footer .item .iconfont[data-v-60b5b360]{text-align:center;font-size:%?40?%}.product-con .footer .item .iconfont.icon-shoucang1[data-v-60b5b360]{color:var(--view-theme)}.product-con .footer .item .iconfont.icon-gouwuche1[data-v-60b5b360]{font-size:%?40?%;position:relative}.product-con .footer .item .iconfont.icon-gouwuche1 .num[data-v-60b5b360]{color:#fff;position:absolute;font-size:%?18?%;padding:%?2?% %?8?% %?3?%;border-radius:%?200?%;top:%?-10?%;right:%?-10?%}.product-con .footer .bnt[data-v-60b5b360]{width:100%;height:%?76?%}.product-con .footer .bnt .bnts[data-v-60b5b360]{width:100%;text-align:center;line-height:%?76?%;color:#fff;font-size:%?28?%}.product-con .footer .bnt .joinCart[data-v-60b5b360]{border-radius:%?50?% 0 0 %?50?%;background-image:linear-gradient(90deg,#fea10f 0,#fa8013)}.product-con .footer .bnt .buy[data-v-60b5b360]{border-radius:%?50?%;background-color:var(--view-theme)}.product-con .footer .bnt .no-goods[data-v-60b5b360]{border-radius:%?50?%;background-color:#ccc}.product-con .conter[data-v-60b5b360]{display:block;padding-bottom:%?100?%}.product-con .conter img[data-v-60b5b360]{display:block}.bg-color-hui[data-v-60b5b360]{background:#bbb!important}.canvas[data-v-60b5b360]{width:750px;height:1190px}.poster-pop[data-v-60b5b360]{width:%?450?%;height:%?714?%;position:fixed;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%);z-index:300;top:50%;margin-top:%?-377?%}.poster-pop uni-image[data-v-60b5b360]{width:100%;height:100%;display:block}.poster-pop .close[data-v-60b5b360]{width:%?46?%;height:%?75?%;position:fixed;right:0;top:%?-73?%;display:block}.poster-pop .save-poster[data-v-60b5b360]{background-color:#df2d0a;font-size:：22rpx;color:#fff;text-align:center;height:%?76?%;line-height:%?76?%;width:100%}.poster-pop .keep[data-v-60b5b360]{color:#fff;text-align:center;font-size:%?25?%;margin-top:%?10?%}[data-v-60b5b360] .mask{z-index:99!important}.mask1[data-v-60b5b360]{position:fixed;top:0;left:0;right:0;bottom:0;background-color:#000;opacity:.5;z-index:288}.home-nav[data-v-60b5b360]{top:%?20?%!important}.home-nav[data-v-60b5b360]{color:#fff;position:fixed;font-size:%?33?%;width:%?56?%;height:%?56?%;z-index:99;left:%?33?%;background:hsla(0,0%,74.5%,.5);border-radius:50%}.home-nav.on[data-v-60b5b360]{background:unset;color:#333}.home-nav .line[data-v-60b5b360]{width:%?1?%;height:%?24?%;background:hsla(0,0%,100%,.25)}.home-nav .icon-xiangzuo[data-v-60b5b360]{width:auto;font-size:%?28?%}.share-box[data-v-60b5b360]{z-index:1000;position:fixed;left:0;top:0;width:100%;height:100%}.share-box uni-image[data-v-60b5b360]{width:100%;height:100%}.df[data-v-60b5b360]{display:flex;align-items:center;flex-wrap:nowrap;width:100%}.attrImg[data-v-60b5b360]{width:%?66?%;height:%?66?%;border-radius:%?6?%;display:block;margin-right:%?14?%}.switchTxt[data-v-60b5b360]{height:%?60?%;flex:1;line-height:%?60?%;box-sizing:border-box;background:#eee;padding:0 %?10?%;border-radius:%?8?%;text-align:center}.attribute[data-v-60b5b360]{padding:%?10?% %?30?%}.attribute .line1[data-v-60b5b360]{width:%?600?%}.flex[data-v-60b5b360]{display:flex;justify-content:space-between;width:100%}.flexs[data-v-60b5b360]{display:flex}.attr-txt[data-v-60b5b360]{display:flex;flex-wrap:nowrap;width:%?130?%}',""]),t.exports=e},"473a":function(t,e,i){"use strict";i.r(e);var a=i("aecf2"),r=i("f4bf");for(var o in r)"default"!==o&&function(t){i.d(e,t,(function(){return r[t]}))}(o);i("b5c1");var n,s=i("f0c5"),c=Object(s["a"])(r["default"],a["b"],a["c"],!1,null,"0ec34deb",null,!1,a["a"],n);e["default"]=c.exports},"4bb8":function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return r})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var r=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"time",style:t.justifyLeft},[t.tipText?i("v-uni-text",{staticClass:"red"},[t._v(t._s(t.tipText))]):t._e(),!0===t.isDay?i("v-uni-text",{staticClass:"styleAll",style:"background-color:"+t.bgColor+";color:"+t.colors+";"},[t._v(t._s(t.day))]):t._e(),t.dayText?i("v-uni-text",{staticClass:"timeTxt red"},[t._v(t._s(t.dayText))]):t._e(),i("v-uni-text",{staticClass:"styleAll",style:"background-color:"+t.bgColor+";color:"+t.colors+";"},[t._v(t._s(t.hour))]),t.hourText?i("v-uni-text",{staticClass:"timeTxt red"},[t._v(t._s(t.hourText))]):t._e(),i("v-uni-text",{staticClass:"styleAll",style:"background-color:"+t.bgColor+";color:"+t.colors+";"},[t._v(t._s(t.minute))]),t.minuteText?i("v-uni-text",{staticClass:"timeTxt red"},[t._v(t._s(t.minuteText))]):t._e(),i("v-uni-text",{staticClass:"styleAll",style:"background-color:"+t.bgColor+";color:"+t.colors+";"},[t._v(t._s(t.second))]),t.secondText?i("v-uni-text",{staticClass:"timeTxt red"},[t._v(t._s(t.secondText))]):t._e()],1)},o=[]},"4c56":function(t,e,i){"use strict";i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={name:"countDown",props:{justifyLeft:{type:String,default:""},tipText:{type:String,default:"倒计时"},dayText:{type:String,default:"天"},hourText:{type:String,default:"时"},minuteText:{type:String,default:"分"},secondText:{type:String,default:"秒"},datatime:{type:Number,default:0},isDay:{type:Boolean,default:!0},bgColor:{type:String,default:""},colors:{type:String,default:""}},data:function(){return{day:"00",hour:"00",minute:"00",second:"00"}},created:function(){this.show_time()},mounted:function(){},methods:{show_time:function(){var t=this;function e(){var e=t.datatime-Date.parse(new Date)/1e3,i=0,a=0,r=0,o=0;e>0?(i=!0===t.isDay?Math.floor(e/86400):0,a=Math.floor(e/3600)-24*i,r=Math.floor(e/60)-24*i*60-60*a,o=Math.floor(e)-24*i*60*60-60*a*60-60*r,a<=9&&(a="0"+a),r<=9&&(r="0"+r),o<=9&&(o="0"+o),t.day=i,t.hour=a,t.minute=r,t.second=o):(t.day="00",t.hour="00",t.minute="00",t.second="00")}e(),setInterval(e,1e3)}}};e.default=a},5909:function(t,e,i){"use strict";i.r(e);var a=i("4c56"),r=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=r.a},"62f4":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,".time[data-v-37a4f2d4]{display:flex;justify-content:center}.red[data-v-37a4f2d4]{color:var(--view-theme);margin:0 %?4?%}",""]),t.exports=e},"83fd":function(t,e,i){"use strict";i.r(e);var a=i("0c7b"),r=i("db73");for(var o in r)"default"!==o&&function(t){i.d(e,t,(function(){return r[t]}))}(o);i("2527");var n,s=i("f0c5"),c=Object(s["a"])(r["default"],a["b"],a["c"],!1,null,"60b5b360",null,!1,a["a"],n);e["default"]=c.exports},"86bd":function(t,e,i){var a=i("62f4");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var r=i("4f06").default;r("69eee700",a,!0,{sourceMap:!1,shadowMode:!1})},a69e:function(t,e,i){var a=i("3b04");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var r=i("4f06").default;r("43432edb",a,!0,{sourceMap:!1,shadowMode:!1})},aecf2:function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return r})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var r=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"product-window",class:(!0===t.attr.cartAttr?"on":"")+" "+(t.iSbnt?"join":"")+" "+(t.iScart?"joinCart":"")},[i("v-uni-view",{staticClass:"textpic acea-row row-between-wrapper"},[i("v-uni-view",{staticClass:"pictrue",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.showImg()}}},[i("v-uni-image",{attrs:{src:t.attr.productSelect.image}})],1),i("v-uni-view",{staticClass:"text"},[i("v-uni-view",{staticClass:"line1"},[t._v(t._s(t.attr.productSelect.store_name))]),i("v-uni-view",{staticClass:"money font-color"},[i("v-uni-view",{staticClass:"acea-row row-middle"},[i("v-uni-text",{staticClass:"num"},[t._v(t._s(t.attr.productSelect.price)+"积分")])],1),t.isShow?i("v-uni-text",{staticClass:"stock"},[t._v("库存: "+t._s(t.attr.productSelect.stock))]):t._e(),t.limitNum?i("v-uni-text",{staticClass:"stock"},[t._v("剩余: "+t._s(t.attr.productSelect.quota))]):t._e()],1)],1),i("v-uni-view",{staticClass:"iconfont icon-guanbi",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeAttr.apply(void 0,arguments)}}})],1),i("v-uni-view",{staticClass:"rollTop"},[i("v-uni-view",{staticClass:"productWinList"},t._l(t.attr.productAttr,(function(e,a){return i("v-uni-view",{key:a,staticClass:"item"},[i("v-uni-view",{staticClass:"title"},[t._v(t._s(e.attr_name))]),i("v-uni-view",{staticClass:"listn acea-row row-middle"},t._l(e.attr_value,(function(r,o){return i("v-uni-view",{key:o,staticClass:"itemn",class:e.index===r.attr?"on":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.tapAttr(a,o)}}},[t._v(t._s(r.attr))])})),1)],1)})),1),i("v-uni-view",{staticClass:"cart acea-row row-between-wrapper"},[i("v-uni-view",{staticClass:"title"},[t._v("数量")]),i("v-uni-view",{staticClass:"carnum acea-row row-left"},[i("v-uni-view",{staticClass:"item reduce acea-row row-center-wrapper",class:t.attr.productSelect.cart_num<=1?"on":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.CartNumDes.apply(void 0,arguments)}}},[i("v-uni-text",{staticClass:"iconfont icon-shangpinshuliang-jian"})],1),i("v-uni-view",{staticClass:"item num acea-row row-middle"},[i("v-uni-input",{attrs:{type:"number","data-name":"productSelect.cart_num"},on:{input:function(e){arguments[0]=e=t.$handleEvent(e),t.bindCode(t.attr.productSelect.cart_num)}},model:{value:t.attr.productSelect.cart_num,callback:function(e){t.$set(t.attr.productSelect,"cart_num",e)},expression:"attr.productSelect.cart_num"}})],1),t.attr.productSelect.stock>t.attr.productSelect.cart_num?i("v-uni-view",{staticClass:"item plus",class:t.attr.productSelect.stock>=t.attr.productSelect.cart_num?"on":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.CartNumAdd.apply(void 0,arguments)}}},[t._v("+")]):t._e()],1)],1)],1)],1),i("v-uni-view",{staticClass:"mask",attrs:{hidden:!1===t.attr.cartAttr},on:{touchmove:function(e){e.preventDefault(),arguments[0]=e=t.$handleEvent(e)},click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeAttr.apply(void 0,arguments)}}})],1)},o=[]},b5c1:function(t,e,i){"use strict";var a=i("a69e"),r=i.n(a);r.a},db73:function(t,e,i){"use strict";i.r(e);var a=i("f193"),r=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=r.a},e1331:function(t,e,i){var a=i("3dd4");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var r=i("4f06").default;r("2793f404",a,!0,{sourceMap:!1,shadowMode:!1})},e145:function(t,e,i){"use strict";i("a9e3"),i("ac1f"),i("1276"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={props:{attr:{type:Object,default:function(){}},limitNum:{type:Number,value:0},isShow:{type:Number,value:0},iSbnt:{type:Number,value:0},iSplus:{type:Number,value:0},iScart:{type:Number,value:0},is_vip:{type:Number,value:0}},data:function(){return{}},mounted:function(){},methods:{getpreviewImage:function(){uni.previewImage({urls:this.attr.productSelect.image.split(","),current:this.attr.productSelect.image})},goCat:function(){this.$emit("goCat")},bindCode:function(t){this.$emit("iptCartNum",this.attr.productSelect.cart_num)},closeAttr:function(){this.$emit("myevent")},CartNumDes:function(){this.$emit("ChangeCartNum",!1)},CartNumAdd:function(){this.$emit("ChangeCartNum",!0)},tapAttr:function(t,e){var i=this;i.$emit("attrVal",{indexw:t,indexn:e}),this.$set(this.attr.productAttr[t],"index",this.attr.productAttr[t].attr_values[e]);var a=i.getCheckedValue().join(",");i.$emit("ChangeAttr",a)},showImg:function(){this.$emit("getImg")},getCheckedValue:function(){for(var t=this.attr.productAttr,e=[],i=0;i<t.length;i++)for(var a=0;a<t[i].attr_values.length;a++)t[i].index===t[i].attr_values[a]&&e.push(t[i].attr_values[a]);return e}}};e.default=a},eeab:function(t,e,i){"use strict";i.r(e);var a=i("4bb8"),r=i("5909");for(var o in r)"default"!==o&&function(t){i.d(e,t,(function(){return r[t]}))}(o);i("075d");var n,s=i("f0c5"),c=Object(s["a"])(r["default"],a["b"],a["c"],!1,null,"37a4f2d4",null,!1,a["a"],n);e["default"]=c.exports},f193:function(t,e,i){"use strict";(function(t){var a=i("4ea4");i("a4d3"),i("e01a"),i("99af"),i("a9e3"),i("ac1f"),i("5319"),i("1276"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r,o=a(i("ade3")),n=i("2f62"),s=i("3589"),c=a(i("b2d5")),u=a(i("473a")),d=a(i("a169")),l=a(i("714b")),p=a(i("4a6b")),f=a(i("eeab")),h=(i("c611"),i("2995")),b=i("1799"),v=(i("01aa"),a(i("db17"))),g=a(i("d010")),m=getApp(),w=(r={computed:(0,n.mapGetters)(["isLogin"]),mixins:[v.default],data:function(){return{dataShow:0,id:0,time:0,countDownHour:"00",countDownMinute:"00",countDownSecond:"00",storeInfo:[],imgUrls:[],parameter:{navbar:"1",return:"1",title:"抢购详情页",color:!1},attribute:{cartAttr:!1,productAttr:[],productSelect:{}},productValue:[],isOpen:!1,attr:"请选择",attrValue:"",status:1,isAuto:!1,isShowAuth:!1,iShidden:!1,limitNum:1,iSplus:!1,replyCount:0,reply:[],replyChance:0,navH:"",navList:["商品","详情"],opacity:0,scrollY:0,topArr:[],toView:"",height:0,heightArr:[],lock:!1,scrollTop:0,tagStyle:{img:"width:100%;display:block;",table:"width:100%",video:"width:100%"},datatime:"",navActive:0,meunHeight:0,backH:"",posters:!1,weixinStatus:!1,posterImageStatus:!1,storeImage:"",PromotionCode:"",posterImage:"",actionSheetHidden:!1,cart_num:1,homeTop:20,returnShow:!0,H5ShareBox:!1,routineContact:0,skuArr:[],selectSku:{}}},components:{productConSwiper:c.default,productWindow:u.default,userEvaluation:d.default,kefuIcon:l.default,"jyf-parser":p.default,countDown:f.default,cusPreviewImg:g.default}},(0,o.default)(r,"computed",(0,n.mapGetters)(["isLogin"])),(0,o.default)(r,"watch",{isLogin:{handler:function(t,e){t&&this.getIntegralProductDetail()},deep:!0}}),(0,o.default)(r,"onLoad",(function(t){var e=this,i=getCurrentPages();e.returnShow=1!==i.length,uni.getSystemInfo({success:function(t){e.height=t.windowHeight,t.statusBarHeight}}),this.isLogin&&(0,b.silenceBindingSpread)(),this.navH=m.globalData.navHeight,t.id&&(this.id=t.id,this.datatime=Number(t.time),this.status=t.status),this.isLogin?this.getIntegralProductDetail():(0,h.toLogin)(),this.$nextTick((function(){}))})),(0,o.default)(r,"methods",{iptCartNum:function(t){this.$set(this.attribute.productSelect,"cart_num",t),this.$set(this,"cart_num",t)},returns:function(){uni.navigateBack()},onLoadFun:function(t){this.isAuto&&(this.isAuto=!1,this.isShowAuth=!1,this.getIntegralProductDetail())},getIntegralProductDetail:function(){var t=this,e=this;(0,s.getIntegralProductDetail)(e.id).then((function(i){t.dataShow=1;var a=i.data.storeInfo.title;for(var r in t.storeInfo=i.data.storeInfo,t.imgUrls=i.data.storeInfo.images,t.storeInfo.description=t.storeInfo.description.replace(/<img/gi,'<img style="max-width:100%;height:auto;float:left;display:block" '),t.attribute.productAttr=i.data.productAttr,t.productValue=i.data.productValue,t.attribute.productSelect.num=i.data.storeInfo.num,t.replyCount=i.data.replyCount,t.reply=i.data.reply?[i.data.reply]:[],t.replyChance=i.data.replyChance,e.routineContact=i.data.routine_contact_type,i.data.productValue){var o=i.data.productValue[r];e.skuArr.push(o)}t.$set(t,"selectSku",e.skuArr[0]),uni.setNavigationBarTitle({title:a.substring(0,7)+"..."}),t.PromotionCode=i.data.storeInfo.code_base,e.storeImage=e.storeInfo.image,e.DefaultSelect(),m.globalData.openPages="/pages/activity/goods_seckill_details/index?id="+e.id+"&time="+e.time+"&status="+e.status+"&scene="+e.storeInfo.uid})).catch((function(t){e.$util.Tips({title:t},{tab:3})}))},setShare:function(){this.$wechat.isWeixin()&&this.$wechat.wechatEvevt(["updateAppMessageShareData","updateTimelineShareData","onMenuShareAppMessage","onMenuShareTimeline"],{desc:this.storeInfo.info,title:this.storeInfo.title,link:location.href,imgUrl:this.storeInfo.image}).then((function(t){})).catch((function(t){}))},DefaultSelect:function(){var t=this,e=t.attribute.productAttr,i=[];for(var a in this.productValue)if(this.productValue[a].quota>0){i=this.attribute.productAttr.length?a.split(","):[];break}for(var r=0;r<e.length;r++)this.$set(e[r],"index",i[r]);var o=this.productValue[i.join(",")];o&&e.length?(t.$set(t.attribute.productSelect,"store_name",t.storeInfo.title),t.$set(t.attribute.productSelect,"image",o.image),t.$set(t.attribute.productSelect,"price",o.price),t.$set(t.attribute.productSelect,"stock",o.stock),t.$set(t.attribute.productSelect,"unique",o.unique),t.$set(t.attribute.productSelect,"quota",o.quota),t.$set(t.attribute.productSelect,"quota_show",o.quota_show),t.$set(t.attribute.productSelect,"product_stock",o.product_stock),t.$set(t.attribute.productSelect,"cart_num",1),t.$set(t,"attrValue",i.join(",")),t.attrValue=i.join(",")):!o&&e.length?(t.$set(t.attribute.productSelect,"store_name",t.storeInfo.title),t.$set(t.attribute.productSelect,"image",t.storeInfo.image),t.$set(t.attribute.productSelect,"price",t.storeInfo.price),t.$set(t.attribute.productSelect,"quota",0),t.$set(t.attribute.productSelect,"quota_show",0),t.$set(t.attribute.productSelect,"product_stock",0),t.$set(t.attribute.productSelect,"stock",0),t.$set(t.attribute.productSelect,"unique",""),t.$set(t.attribute.productSelect,"cart_num",0),t.$set(t,"attrValue",""),t.$set(t,"attrTxt","请选择")):o||e.length||(t.$set(t.attribute.productSelect,"store_name",t.storeInfo.title),t.$set(t.attribute.productSelect,"image",t.storeInfo.image),t.$set(t.attribute.productSelect,"price",t.storeInfo.price),t.$set(t.attribute.productSelect,"stock",t.storeInfo.stock),t.$set(t.attribute.productSelect,"quota",t.storeInfo.quota),t.$set(t.attribute.productSelect,"product_stock",t.storeInfo.product_stock),t.$set(t.attribute.productSelect,"unique",t.storeInfo.unique||""),t.$set(t.attribute.productSelect,"cart_num",1),t.$set(t.attribute.productSelect,"quota",o.quota),t.$set(t.attribute.productSelect,"product_stock",o.product_stock),t.$set(t,"attrValue",""),t.$set(t,"attrTxt","请选择"))},selecAttr:function(){this.attribute.cartAttr=!0},onMyEvent:function(){this.$set(this.attribute,"cartAttr",!1),this.$set(this,"isOpen",!1)},ChangeCartNum:function(t){var e=this.productValue[this.attrValue];if(this.cart_num&&(e.cart_num=this.cart_num,this.attribute.productSelect.cart_num=this.cart_num),void 0!==e||this.attribute.productAttr.length||(e=this.attribute.productSelect),void 0!==e){e.stock,e.quota_show,e.quota,e.product_stock;var i=this.attribute.productSelect;this.storeInfo.num;if(void 0==e.cart_num&&(e.cart_num=1),t)i.cart_num<this.attribute.productSelect.quota&&(i.cart_num++,this.$set(this.attribute.productSelect,"cart_num",i.cart_num),this.$set(this,"cart_num",i.cart_num),this.$set(this.attribute.productSelect,"cart_num",i.cart_num));else{if(1==i.cart_num)return;i.cart_num--,this.$set(this,"cart_num",i.cart_num),this.$set(this.attribute.productSelect,"cart_num",i.cart_num)}}},attrVal:function(t){this.attribute.productAttr[t.indexw].index=this.attribute.productAttr[t.indexw].attr_values[t.indexn]},ChangeAttr:function(t){this.$set(this,"cart_num",1);var e=this.productValue[t];this.$set(this,"selectSku",e),e?(this.$set(this.attribute.productSelect,"image",e.image),this.$set(this.attribute.productSelect,"price",e.price),this.$set(this.attribute.productSelect,"stock",e.stock),this.$set(this.attribute.productSelect,"unique",e.unique),this.$set(this.attribute.productSelect,"cart_num",1),this.$set(this.attribute.productSelect,"quota",e.quota),this.$set(this.attribute.productSelect,"quota_show",e.quota_show),this.$set(this,"attrValue",t),this.attrTxt="已选择"):(this.$set(this.attribute.productSelect,"image",this.storeInfo.image),this.$set(this.attribute.productSelect,"price",this.storeInfo.price),this.$set(this.attribute.productSelect,"stock",0),this.$set(this.attribute.productSelect,"unique",""),this.$set(this.attribute.productSelect,"cart_num",0),this.$set(this.attribute.productSelect,"quota",0),this.$set(this.attribute.productSelect,"quota_show",0),this.$set(this,"attrValue",""),this.attrTxt="已选择")},scroll:function(t){var e=this,i=t.detail.scrollTop,a=i/200;if(a=a>1?1:a,e.opacity=a,e.scrollY=i,e.lock)e.lock=!1;else for(var r=0;r<e.topArr.length;r++)if(i<e.topArr[r]-m.globalData.navHeight/2+e.heightArr[r]){e.navActive=r;break}},tap:function(t,e){var i=t.id,a=(e=e,this);this.toView=i,this.navActive=e,this.lock=!0,this.scrollTop=e>0?a.topArr[e]-m.globalData.navHeight/2:a.topArr[e]},showImg:function(t){this.$refs.cusPreviewImg.open(this.selectSku.suk)},changeSwitch:function(t){this.skuArr[t];var e=this.skuArr[t];this.$set(this,"selectSku",e);var i=e.suk.split(",");this.attribute.productAttr,this.$set(this.attribute.productAttr[0],"index",i[0]),2==i.length?(this.$set(this.attribute.productAttr[0],"index",i[0]),this.$set(this.attribute.productAttr[1],"index",i[1])):3==i.length?(this.$set(this.attribute.productAttr[0],"index",i[0]),this.$set(this.attribute.productAttr[1],"index",i[1]),this.$set(this.attribute.productAttr[2],"index",i[2])):4==i.length&&(this.$set(this.attribute.productAttr[0],"index",i[0]),this.$set(this.attribute.productAttr[1],"index",i[1]),this.$set(this.attribute.productAttr[2],"index",i[2]),this.$set(this.attribute.productAttr[3],"index",i[3])),e&&(this.$set(this.attribute.productSelect,"image",e.image),this.$set(this.attribute.productSelect,"price",e.price),this.$set(this.attribute.productSelect,"stock",e.stock),this.$set(this.attribute.productSelect,"unique",e.unique),this.$set(this.attribute.productSelect,"vipPrice",e.vipPrice),this.$set(this,"attrTxt","已选择"),this.$set(this,"attrValue",e.suk))},goCat:function(){var t=this.productValue[this.attrValue];return this.isOpen?this.attribute.cartAttr=!0:this.attribute.cartAttr=!this.attribute.cartAttr,!0===this.attribute.cartAttr&&0==this.isOpen?this.isOpen=!0:this.attribute.productAttr.length&&void 0===t&&1==this.isOpen?m.$util.Tips({title:"请选择属性"}):this.cart_num<=0?m.$util.Tips({title:"请选择数量"}):(this.isOpen=!1,void uni.navigateTo({url:"/pages/points_mall/integral_order?unique=".concat(t.unique,"&num=").concat(this.cart_num||1)}))}}),r);e.default=w}).call(this,i("5a52")["default"])},f4bf:function(t,e,i){"use strict";i.r(e);var a=i("e145"),r=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=r.a}}]);