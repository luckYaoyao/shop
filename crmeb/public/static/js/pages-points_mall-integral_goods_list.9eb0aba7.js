(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-points_mall-integral_goods_list"],{"54fa":function(t,i,e){"use strict";var a=e("7786"),n=e.n(a);n.a},"75c5":function(t,i,e){"use strict";e("7a82");var a=e("4ea4").default;Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0,e("99af");var n=a(e("25eb")),s=e("ce65"),o=e("f49f"),r=a(e("3022")),c=e("26cb"),d=e("d432"),u=a(e("32c8")),l=e("b608"),p={computed:(0,c.mapGetters)(["uid"]),components:{recommend:r.default,home:n.default},mixins:[u.default],data:function(){return{imgHost:l.HTTP_REQUEST_URL,productList:[],is_switch:!0,where:{store_name:"",priceOrder:"",salesOrder:"",page:1,limit:20},price:0,stock:0,nows:!1,loadend:!1,loading:!1,loadTitle:this.$t("加载更多"),title:"",hostProduct:[],hotPage:1,hotLimit:10,hotScroll:!1}},onPageScroll:function(t){uni.$emit("scroll")},onLoad:function(t){this.where.cid=t.cid||0,this.$set(this.where,"sid",t.sid||0),this.title=t.title||"",this.$set(this.where,"store_name",t.searchValue||""),this.get_product_list(),this.get_host_product()},methods:{godDetail:function(t){(0,d.goShopDetail)(t,this.uid).then((function(i){uni.navigateTo({url:"/pages/points_mall/integral_goods_details?id=".concat(t.id)})}))},Changswitch:function(){this.is_switch=!this.is_switch},searchSubmit:function(t){this.$set(this.where,"store_name",t.detail.value),this.loadend=!1,this.$set(this.where,"page",1),this.get_product_list(!0)},get_host_product:function(){var t=this;t.hotScroll||(0,s.getProductHot)(t.hotPage,t.hotLimit).then((function(i){t.hotPage++,t.hotScroll=i.data.length<t.hotLimit,t.hostProduct=t.hostProduct.concat(i.data)}))},set_where:function(t){switch(t){case 1:this.where={store_name:"",priceOrder:"",salesOrder:"",page:1,limit:20},this.price=0,this.stock=0;break;case 2:0==this.price?this.price=1:1==this.price?this.price=2:2==this.price&&(this.price=0),this.stock=0;break;case 3:0==this.stock?this.stock=1:1==this.stock?this.stock=2:2==this.stock&&(this.stock=0),this.price=0;break;case 4:this.nows=!this.nows;break}this.loadend=!1,this.$set(this.where,"page",1),this.get_product_list(!0)},setWhere:function(){0==this.price?this.where.priceOrder="":1==this.price?this.where.priceOrder="asc":2==this.price&&(this.where.priceOrder="desc"),0==this.stock?this.where.salesOrder="":1==this.stock?this.where.salesOrder="asc":2==this.stock&&(this.where.salesOrder="desc"),this.where.news=this.nows?1:0},get_product_list:function(t){var i=this;i.setWhere(),i.loadend||i.loading||(!0===t&&i.$set(i,"productList",[]),i.loading=!0,i.loadTitle="",(0,o.getStoreIntegralList)(i.where).then((function(t){var e=t.data,a=i.$util.SplitArray(e,i.productList),n=e.length<i.where.limit;i.loadend=n,i.loading=!1,i.loadTitle=n?i.$t("我也是有底线的"):i.$t("加载更多"),i.$set(i,"productList",a),i.$set(i.where,"page",i.where.page+1)})).catch((function(t){i.loading=!1,i.loadTitle=i.$t("加载更多")})))}},onPullDownRefresh:function(){},onReachBottom:function(){this.productList.length>0?this.get_product_list():this.get_host_product()}};i.default=p},7786:function(t,i,e){var a=e("ed17d");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=e("4f06").default;n("24be0a77",a,!0,{sourceMap:!1,shadowMode:!1})},a2fc:function(t,i,e){"use strict";e.r(i);var a=e("75c5"),n=e.n(a);for(var s in a)["default"].indexOf(s)<0&&function(t){e.d(i,t,(function(){return a[t]}))}(s);i["default"]=n.a},a992:function(t,i,e){"use strict";e.r(i);var a=e("ae02"),n=e("a2fc");for(var s in n)["default"].indexOf(s)<0&&function(t){e.d(i,t,(function(){return n[t]}))}(s);e("54fa");var o=e("f0c5"),r=Object(o["a"])(n["default"],a["b"],a["c"],!1,null,"6718d0a6",null,!1,a["a"],void 0);i["default"]=r.exports},ae02:function(t,i,e){"use strict";e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return n})),e.d(i,"a",(function(){}));var a=function(){var t=this,i=t.$createElement,a=t._self._c||i;return a("v-uni-view",{style:t.colorStyle},[a("v-uni-view",{staticClass:"productList"},[a("v-uni-view",{staticClass:"search bg-color acea-row row-between-wrapper"},[a("v-uni-view",{staticClass:"input acea-row row-between-wrapper"},[a("v-uni-text",{staticClass:"iconfont icon-sousuo"}),a("v-uni-input",{attrs:{placeholder:t.$t("搜索商品名称"),"placeholder-class":"placeholder","confirm-type":"search",name:"search",value:t.where.store_name},on:{confirm:function(i){arguments[0]=i=t.$handleEvent(i),t.searchSubmit.apply(void 0,arguments)}}})],1),a("v-uni-view",{staticClass:"iconfont",class:1==t.is_switch?"icon-pailie":"icon-tupianpailie",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.Changswitch.apply(void 0,arguments)}}})],1),a("v-uni-view",{staticClass:"nav acea-row row-middle"},[a("v-uni-view",{staticClass:"item line1",class:t.title?"font-num":"",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.set_where(1)}}},[t._v(t._s(t.$t("默认")))]),a("v-uni-view",{staticClass:"item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.set_where(2)}}},[t._v(t._s(t.$t("积分"))),1==t.price?a("v-uni-image",{attrs:{src:e("7a25")}}):2==t.price?a("v-uni-image",{attrs:{src:e("27c0")}}):a("v-uni-image",{attrs:{src:e("1fb7")}})],1),a("v-uni-view",{staticClass:"item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.set_where(3)}}},[t._v(t._s(t.$t("销量"))),1==t.stock?a("v-uni-image",{attrs:{src:e("7a25")}}):2==t.stock?a("v-uni-image",{attrs:{src:e("27c0")}}):a("v-uni-image",{attrs:{src:e("1fb7")}})],1)],1),a("v-uni-view",{staticClass:"list acea-row row-between-wrapper",class:1==t.is_switch?"":"on"},[t._l(t.productList,(function(i,n){return a("v-uni-view",{key:n,staticClass:"item",class:1==t.is_switch?"":"on",attrs:{"hover-class":"none"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.godDetail(i)}}},[a("v-uni-view",{staticClass:"pictrue",class:1==t.is_switch?"":"on"},[a("v-uni-image",{class:1==t.is_switch?"":"on",attrs:{src:i.image}})],1),a("v-uni-view",{staticClass:"text",class:1==t.is_switch?"":"on"},[a("v-uni-view",{staticClass:"name line1"},[t._v(t._s(i.title))]),a("v-uni-view",{staticClass:"money font-color",class:1==t.is_switch?"":"on"},[a("v-uni-text",{staticClass:"num"},[t._v(t._s(i.price)+t._s(t.$t("积分")))])],1),a("v-uni-view",{staticClass:"vip acea-row row-between-wrapper",class:1==t.is_switch?"":"on"},[i.vip_price&&i.vip_price>0?a("v-uni-view",{staticClass:"vip-money"},[t._v(t._s(i.vip_price)+"\n\t\t\t\t\t\t\t"+t._s(t.$t("积分"))),a("v-uni-image",{attrs:{src:e("f36b")}})],1):t._e(),a("v-uni-view",{staticClass:"sales"},[a("v-uni-view",{},[t._v(t._s(i.sales)+t._s(t.$t("人兑换")))]),a("v-uni-view",{staticClass:"exchange"},[t._v(t._s(t.$t("兑换")))])],1)],1)],1)],1)})),t.productList.length>0?a("v-uni-view",{staticClass:"loadingicon acea-row row-center-wrapper"},[a("v-uni-text",{staticClass:"loading iconfont icon-jiazai",attrs:{hidden:0==t.loading}}),t._v(t._s(t.loadTitle))],1):t._e()],2)],1),0==t.productList.length&&t.where.page>1?a("v-uni-view",{staticClass:"noCommodity"},[a("v-uni-view",{staticClass:"emptyBox"},[a("v-uni-image",{attrs:{src:t.imgHost+"/statics/images/no-thing.png"}})],1),a("recommend",{attrs:{hostProduct:t.hostProduct}})],1):t._e(),a("home")],1)},n=[]},ed17d:function(t,i,e){var a=e("24fb");i=a(!1),i.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.productList .search[data-v-6718d0a6]{width:100%;height:%?86?%;padding-left:%?23?%;box-sizing:border-box;position:fixed;left:0;top:0;z-index:9}.productList .search .input[data-v-6718d0a6]{width:%?640?%;height:%?60?%;background-color:#fff;border-radius:%?50?%;padding:0 %?20?%;box-sizing:border-box}.productList .search .input uni-input[data-v-6718d0a6]{width:%?548?%;height:100%;font-size:%?26?%}.productList .search .input .placeholder[data-v-6718d0a6]{color:#999}.productList .search .input .iconfont[data-v-6718d0a6]{font-size:%?35?%;color:#555}.productList .search .icon-pailie[data-v-6718d0a6],\n.productList .search .icon-tupianpailie[data-v-6718d0a6]{color:#fff;width:%?62?%;font-size:%?40?%;height:%?86?%;line-height:%?86?%}.productList .nav[data-v-6718d0a6]{height:%?86?%;color:#454545;position:fixed;left:0;width:100%;font-size:%?28?%;background-color:#fff;margin-top:%?86?%;top:0;z-index:9}.productList .nav .item[data-v-6718d0a6]{width:33%;text-align:center}.productList .nav .item uni-image[data-v-6718d0a6]{width:%?15?%;height:%?19?%;margin-left:%?10?%}.productList .list[data-v-6718d0a6]{padding:0 %?20?%;margin-top:%?172?%}.productList .list.on[data-v-6718d0a6]{background-color:#fff;border-top:1px solid #f6f6f6}.productList .list .item[data-v-6718d0a6]{width:%?345?%;margin-top:%?20?%;background-color:#fff;border-radius:%?20?%}.productList .list .item.on[data-v-6718d0a6]{width:100%;display:flex;border-bottom:%?1?% solid #f6f6f6;padding:%?30?% 0;margin:0}.productList .list .item .pictrue[data-v-6718d0a6]{position:relative;width:100%;height:%?345?%}.productList .list .item .pictrue.on[data-v-6718d0a6]{width:%?180?%;height:%?180?%}.productList .list .item .pictrue uni-image[data-v-6718d0a6]{width:100%;height:100%;border-radius:%?20?% %?20?% 0 0}.productList .list .item .pictrue uni-image.on[data-v-6718d0a6]{border-radius:%?6?%}.productList .list .item .text[data-v-6718d0a6]{padding:%?20?% %?17?% %?26?% %?17?%;font-size:%?30?%;color:#222}.productList .list .item .text.on[data-v-6718d0a6]{width:%?508?%;padding:0 0 0 %?22?%}.productList .list .item .text .money[data-v-6718d0a6]{font-size:%?22?%;margin-top:%?8?%}.productList .list .item .text .money.on[data-v-6718d0a6]{margin-top:%?50?%}.productList .list .item .text .money .num[data-v-6718d0a6]{font-size:%?34?%}.productList .list .item .text .vip[data-v-6718d0a6]{font-size:%?22?%;margin-top:%?7?%}.productList .list .item .text .vip .sales[data-v-6718d0a6]{color:#aaa;width:100%;display:flex;justify-content:space-between}.productList .list .item .text .vip .sales .exchange[data-v-6718d0a6]{border:1px solid var(--view-theme);border-radius:%?20?%;padding:0 %?12?%;color:var(--view-theme)}.productList .list .item .text .vip.on[data-v-6718d0a6]{margin-top:%?12?%}.productList .list .item .text .vip .vip-money[data-v-6718d0a6]{font-size:%?24?%;color:#282828;font-weight:700}.productList .list .item .text .vip .vip-money uni-image[data-v-6718d0a6]{width:%?64?%;height:%?26?%;margin-left:%?4?%}.noCommodity[data-v-6718d0a6]{background-color:#fff;padding-bottom:%?30?%}.noCommodity .emptyBox[data-v-6718d0a6]{text-align:center;padding-top:%?20?%}.noCommodity .emptyBox .tips[data-v-6718d0a6]{color:#aaa;font-size:%?26?%}.noCommodity .emptyBox uni-image[data-v-6718d0a6]{width:%?414?%;height:%?304?%}',""]),t.exports=i},f49f:function(t,i,e){"use strict";e("7a82");var a=e("4ea4").default;Object.defineProperty(i,"__esModule",{value:!0}),i.getBargainDetail=function(t,i){return n.default.get("bargain/detail/".concat(t,"?bargainUid=").concat(i))},i.getBargainList=function(t){return n.default.get("bargain/list",t,{noAuth:!0})},i.getBargainPoster=function(t){return n.default.post("bargain/poster",t)},i.getBargainPosterData=function(t){return n.default.get("bargain/poster_info/"+t)},i.getBargainUserCancel=function(t){return n.default.post("bargain/user/cancel",t)},i.getBargainUserList=function(t){return n.default.get("bargain/user/list",t)},i.getCombinationBannerList=function(t){return n.default.get("combination/banner_list",t,{noAuth:!0})},i.getCombinationDetail=function(t){return n.default.get("combination/detail/"+t)},i.getCombinationList=function(t){return n.default.get("combination/list",t,{noAuth:!0})},i.getCombinationPink=function(t){return n.default.get("combination/pink/"+t)},i.getCombinationPoster=function(t){return n.default.post("combination/poster",t)},i.getCombinationPosterData=function(t){return n.default.get("combination/poster_info/"+t)},i.getIntegralOrderList=function(t){return n.default.get("store_integral/order/list",t)},i.getIntegralProductDetail=function(t){return n.default.get("store_integral/detail/"+t,{},{noAuth:!0})},i.getLogisticsDetails=function(t){return n.default.get("store_integral/order/express/".concat(t))},i.getPink=function(t){return n.default.get("pink",t,{noAuth:!0})},i.getPresellList=function(t){return n.default.get("advance/list",t)},i.getSeckillDetail=function(t,i){return n.default.get("seckill/detail/"+t,i)},i.getSeckillIndexTime=function(){return n.default.get("seckill/index",{},{noAuth:!0})},i.getSeckillList=function(t,i){return n.default.get("seckill/list/"+t,i,{noAuth:!0})},i.getStoreIntegralList=function(t){return n.default.get("store_integral/list",t)},i.integralOrderConfirm=function(t){return n.default.post("store_integral/order/confirm",t)},i.integralOrderCreate=function(t){return n.default.post("store_integral/order/create",t)},i.integralOrderDetails=function(t){return n.default.get("store_integral/order/detail/".concat(t))},i.orderDel=function(t){return n.default.post("store_integral/order/del",t)},i.orderTake=function(t){return n.default.post("store_integral/order/take",t)},i.postBargainHelp=function(t){return n.default.post("bargain/help",t)},i.postBargainHelpCount=function(t){return n.default.post("bargain/help/count",t)},i.postBargainHelpList=function(t){return n.default.post("bargain/help/list",t)},i.postBargainHelpPrice=function(t){return n.default.post("bargain/help/price",t)},i.postBargainShare=function(t){return n.default.post("bargain/share",{bargainId:t})},i.postBargainStart=function(t){return n.default.post("bargain/start",{bargainId:t})},i.postBargainStartUser=function(t){return n.default.post("bargain/start/user",t)},i.postCombinationRemove=function(t){return n.default.post("combination/remove",t)},i.scombinationCode=function(t){return n.default.get("combination/code/"+t)},i.seckillCode=function(t,i){return n.default.get("seckill/code/"+t,i)},e("99af");var n=a(e("b7e7"))}}]);