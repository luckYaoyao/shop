(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/index"],{"32bd":function(e,n,t){"use strict";(function(e){t("6e38");o(t("66fd"));var n=o(t("b626"));function o(e){return e&&e.__esModule?e:{default:e}}e(n.default)}).call(this,t("543d")["createPage"])},"33f2":function(e,n,t){"use strict";t.d(n,"b",(function(){return i})),t.d(n,"c",(function(){return c})),t.d(n,"a",(function(){return o}));var o={goodsWaterfall:function(){return t.e("components/goodsWaterfall/goodsWaterfall").then(t.bind(null,"cd73"))}},i=function(){var e=this,n=e.$createElement;e._self._c},c=[]},"361b":function(e,n,t){},"6e5c":function(e,n,t){"use strict";(function(e){Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var o=t("1346"),i=t("a1ea"),c=t("7fe6"),a=t("26cb"),s=t("f15c"),r=t("4729"),u=(t("c6cd"),l(t("c83f")));function l(e){return e&&e.__esModule?e:{default:e}}var d=function(){t.e("components/couponWindow/index").then(function(){return resolve(t("7841"))}.bind(null,t)).catch(t.oe)},h=function(){t.e("pages/index/components/headerSerch").then(function(){return resolve(t("2073"))}.bind(null,t)).catch(t.oe)},f=function(){t.e("pages/index/components/swiperBg").then(function(){return resolve(t("c9e5"))}.bind(null,t)).catch(t.oe)},g=function(){t.e("pages/index/components/menus").then(function(){return resolve(t("b3df"))}.bind(null,t)).catch(t.oe)},p=function(){t.e("pages/index/components/news").then(function(){return resolve(t("eb17"))}.bind(null,t)).catch(t.oe)},m=function(){t.e("pages/index/components/activity").then(function(){return resolve(t("9be2"))}.bind(null,t)).catch(t.oe)},b=function(){t.e("pages/index/components/scrollBox").then(function(){return resolve(t("b953"))}.bind(null,t)).catch(t.oe)},v=function(){t.e("pages/index/components/recommend").then(function(){return resolve(t("5088"))}.bind(null,t)).catch(t.oe)},S=function(){t.e("pages/index/components/popular").then(function(){return resolve(t("c275"))}.bind(null,t)).catch(t.oe)},x=function(){t.e("pages/index/components/mBanner").then(function(){return resolve(t("0afe"))}.bind(null,t)).catch(t.oe)},w=function(){t.e("pages/index/components/newGoods").then(function(){return resolve(t("7cdc"))}.bind(null,t)).catch(t.oe)},T=function(){t.e("pages/index/components/promotion").then(function(){return resolve(t("d4c4"))}.bind(null,t)).catch(t.oe)},y=function(){t.e("pages/index/components/alive").then(function(){return resolve(t("4f30"))}.bind(null,t)).catch(t.oe)},B=function(){t.e("pages/index/components/adsRecommend").then(function(){return resolve(t("e1ad"))}.bind(null,t)).catch(t.oe)},L=function(){t.e("pages/index/components/coupon").then(function(){return resolve(t("94e7"))}.bind(null,t)).catch(t.oe)},C=function(){t.e("pages/index/components/seckill").then(function(){return resolve(t("0030"))}.bind(null,t)).catch(t.oe)},I=function(){t.e("pages/index/components/combination").then(function(){return resolve(t("c908"))}.bind(null,t)).catch(t.oe)},P=function(){t.e("pages/index/components/bargain").then(function(){return resolve(t("563d"))}.bind(null,t)).catch(t.oe)},_=function(){t.e("pages/index/components/goodList").then(function(){return resolve(t("526f"))}.bind(null,t)).catch(t.oe)},D=function(){t.e("pages/index/components/picTxt").then(function(){return resolve(t("e6d3"))}.bind(null,t)).catch(t.oe)},N=function(){t.e("pages/index/components/titles").then(function(){return resolve(t("40af"))}.bind(null,t)).catch(t.oe)},k=function(){t.e("pages/index/components/customerService").then(function(){return resolve(t("ede5"))}.bind(null,t)).catch(t.oe)},O=function(){Promise.all([t.e("common/vendor"),t.e("pages/index/components/tabBar")]).then(function(){return resolve(t("94b3"))}.bind(null,t)).catch(t.oe)},R=function(){t.e("pages/index/components/tabNav").then(function(){return resolve(t("89a6"))}.bind(null,t)).catch(t.oe)},U=function(){t.e("pages/index/update/app-update").then(function(){return resolve(t("7366"))}.bind(null,t)).catch(t.oe)},E=function(){t.e("components/Loading/index").then(function(){return resolve(t("48b9"))}.bind(null,t)).catch(t.oe)},M=function(){t.e("components/goodsWaterfall/goodsWaterfall").then(function(){return resolve(t("cd73"))}.bind(null,t)).catch(t.oe)},F=function(){t.e("pages/index/components/skeleton").then(function(){return resolve(t("d655"))}.bind(null,t)).catch(t.oe)},G=getApp(),H=e.getSystemInfoSync().statusBarHeight,W={computed:(0,a.mapGetters)(["isLogin","uid"]),components:{couponWindow:d,headerSerch:h,swiperBg:f,menus:g,news:p,activity:m,scrollBox:b,recommend:v,popular:S,mBanner:x,newGoods:w,promotion:T,alive:y,adsRecommend:B,coupon:L,seckill:C,combination:I,bargain:P,goodList:_,picTxt:D,titles:N,customerService:k,tabBar:O,tabNav:R,Loading:E,goodsWaterfall:M,skeletons:F,appUpdate:U},mixins:[u.default],data:function(){return{showSkeleton:!0,isNodes:0,isSortType:0,sortList:{},sortAll:[],goodPage:1,goodLists:[],curSort:0,sortMpTop:0,loaded:!1,hostProduct:[],hotScroll:!1,hotPage:1,hotLimit:10,followHid:!0,followUrl:"",followCode:!1,navH:H,subscribe:!1,iShidden:!1,goodType:3,loading:!1,loadend:!1,loadTitle:"下拉加载更多",page:1,limit:this.$config.LIMIT,numConfig:0,couponObj:{},isCouponShow:!1,shareInfo:{},site_config:"",isIframe:G.globalData.isIframe,headerSerch:{},swiperBg:{},menus:{},news:{},activity:{},alive:{},scrollBox:{},titles:{},goodList:{},tabBar:{},customerService:{},picTxt:{},bargain:{},combination:{},adsRecommend:{},seckill:{},coupon:{},tabNav:{},isBorader:"",domOffsetTop:50,isTop:0,privacyStatus:!1,isFixed:!1}},onLoad:function(n){var t=this;e.hideTabBar();e.getLocation({type:"wgs84",success:function(n){try{e.setStorageSync("user_latitude",n.latitude),e.setStorageSync("user_longitude",n.longitude)}catch(t){}}}),this.diyData(),this.getIndexData(),this.setOpenShare(),this.$Cache.get(i.TIPS_KEY)&&(this.iShidden=!0),this.getTemlIds(),(0,c.siteConfig)().then((function(e){t.site_config=e.data.record_No})).catch((function(e){return t.$util.Tips({title:e.msg})}));var o=e.createSelectorQuery().select(".mp-header");o.boundingClientRect((function(e){t.isTop=e.top})).exec()},onPullDownRefresh:function(){this.diyData(),this.getIndexData()},onShareAppMessage:function(){return{title:this.shareInfo.title,path:"/pages/index/index",imageUrl:this.shareInfo.img}},onShareTimeline:function(){return{title:this.shareInfo.title,imageUrl:this.shareInfo.img}},onShow:function(){G.globalData.isIframe||this.isLogin&&this.getCoupon()},methods:{bindEdit:function(e,n){G.globalData.isIframe&&window.parent.postMessage({name:e,dataName:n,params:{}},"*")},getFollow:function(){var e=this;(0,o.follow)().then((function(n){e.followUrl=n.data.path})).catch((function(n){return e.$util.Tips({title:n.msg})}))},followTap:function(){this.followCode=!0,this.followHid=!1},closeFollow:function(){this.followHid=!1},closeFollowCode:function(){this.followCode=!1,this.followHid=!0},closeTip:function(){this.$Cache.set(i.TIPS_KEY,!0),this.iShidden=!0},bindHeighta:function(e){this.domOffsetTop=e.top-110},getCoupon:function(){var n=this,t=e.getStorageSync("tagDate")||"",o=(new Date).toLocaleDateString();t===o?this.getNewCoupon():(0,c.getCouponV2)().then((function(t){var o=t.data;o.list.length?(n.isCouponShow=!0,n.couponObj=o,e.setStorageSync("tagDate",(new Date).toLocaleDateString())):n.getNewCoupon()}))},getNewCoupon:function(){var n=this,t=e.getStorageSync("oldUser")||0;t||(0,c.getCouponNewUser)().then((function(t){var o=t.data;o.show?o.list.length&&(n.isCouponShow=!0,n.couponObj=o,e.setStorageSync("oldUser",1)):e.setStorageSync("oldUser",1)}))},couponClose:function(){this.isCouponShow=!1,e.getStorageSync("oldUser")||this.getNewCoupon()},getTemlIds:function(){var e=wx.getStorageSync(i.SUBSCRIBE_MESSAGE);e||(0,c.getTemlIds)().then((function(e){e.data&&wx.setStorageSync(i.SUBSCRIBE_MESSAGE,JSON.stringify(e.data))}))},goICP:function(){e.navigateTo({url:"/pages/annex/web_view/index?url=https://beian.miit.gov.cn/"})},onLoadFun:function(){},diyData:function(){var n=this,t=this;(0,c.getDiy)().then((function(o){var i=o.data;t.headerSerch=i.headerSerch,t.swiperBg=i.swiperBg,t.menus=i.menus,t.news=i.news,t.activity=i.activity,t.alive=i.alive,t.scrollBox=i.scrollBox,t.titles=i.titles,t.goodList=i.goodList,t.tabNav=i.tabNav,t.tabBar=i.tabBar,t.customerService=i.customerService,t.picTxt=i.picTxt,t.bargain=i.bargain,t.combination=i.combination,t.adsRecommend=i.adsRecommend,t.seckill=i.seckill,t.coupon=i.coupon,n.$Cache.set("TAB_BAR",i.tabBar.default.tabBarList),setTimeout((function(){n.showSkeleton=!1}),1500),e.stopPullDownRefresh({success:function(e){}})}))},getIndexData:function(){var n=this;(0,c.getIndexData)().then((function(t){n.subscribe=t.data.subscribe,e.setNavigationBarTitle({title:t.data.site_name})}))},get_host_product:function(){var e=this;e.hotScroll||(0,s.getProductHot)(e.hotPage,e.hotLimit).then((function(n){e.hotPage++,e.hotScroll=n.data.length<e.hotLimit,e.hostProduct=e.hostProduct.concat(n.data)}))},bindSortId:function(e){this.isSortType=-99==e?0:1,this.goodLists=[],this.getProductList(e)},getProductList:function(e){this.curSort=0,this.loaded=!1,this.goodPage=1,this.getGoodsList(e)},getGoodsList:function(e){var n=this;this.loading||this.loaded||(this.loading=!0,(0,s.getProductslist)({keyword:"",priceOrder:"",salesOrder:"",news:0,page:this.goodPage,limit:10,cid:e}).then((function(e){n.goodLists=n.goodLists.concat(e.data),n.loading=!1,n.loaded=e.data.length<10,n.goodPage++})))},goGoodsDetail:function(n){var t=this;(0,r.goPage)().then((function(o){(0,r.goShopDetail)(n,t.uid).then((function(t){e.navigateTo({url:"/pages/goods_details/index?id=".concat(n.id)})}))}))},setOpenShare:function(){var e=this;(0,o.getShare)().then((function(n){var t=n.data;e.shareInfo=t}))}},onReachBottom:function(){},onPageScroll:function(e){var n=this;this.headerSerch.default.isShow.val?(e.scrollTop>this.isTop&&(this.isFixed=!0),e.scrollTop<this.isTop&&this.$nextTick((function(){n.isFixed=!1}))):this.isFixed=!1}};n.default=W}).call(this,t("543d")["default"])},b626:function(e,n,t){"use strict";t.r(n);var o=t("33f2"),i=t("b831");for(var c in i)"default"!==c&&function(e){t.d(n,e,(function(){return i[e]}))}(c);t("b7cd");var a,s=t("f0c5"),r=Object(s["a"])(i["default"],o["b"],o["c"],!1,null,"23edb7d9",null,!1,o["a"],a);n["default"]=r.exports},b7cd:function(e,n,t){"use strict";var o=t("361b"),i=t.n(o);i.a},b831:function(e,n,t){"use strict";t.r(n);var o=t("6e5c"),i=t.n(o);for(var c in o)"default"!==c&&function(e){t.d(n,e,(function(){return o[e]}))}(c);n["default"]=i.a}},[["32bd","common/runtime","common/vendor"]]]);