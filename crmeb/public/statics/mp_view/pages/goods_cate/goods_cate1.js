(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods_cate/goods_cate1"],{"0264":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var r=function(){var t=this,e=t.$createElement;t._self._c},o=[]},"09c0":function(t,e,n){"use strict";n.r(e);var i=n("0264"),r=n("0c9d");for(var o in r)"default"!==o&&function(t){n.d(e,t,(function(){return r[t]}))}(o);n("9721"),n("a750");var a,c=n("f0c5"),u=Object(c["a"])(r["default"],i["b"],i["c"],!1,null,"fc4362f2",null,!1,i["a"],a);e["default"]=u.exports},"0c9d":function(t,e,n){"use strict";n.r(e);var i=n("d96d"),r=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=r.a},"26a3":function(t,e,n){},9721:function(t,e,n){"use strict";var i=n("e21c"),r=n.n(i);r.a},a750:function(t,e,n){"use strict";var i=n("26a3"),r=n.n(i);r.a},d96d:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n("5019"),r=n("26cb"),o=n("6fe3");function a(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,i)}return n}function c(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?a(Object(n),!0).forEach((function(e){u(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):a(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function u(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var s=t.getSystemInfoSync().statusBarHeight+"px",l=function(){Promise.all([n.e("common/vendor"),n.e("components/pageFooter/index")]).then(function(){return resolve(n("9123"))}.bind(null,n)).catch(n.oe)},f=function(){Promise.all([n.e("common/vendor"),n.e("pages/index/visualization/components/tabBar")]).then(function(){return resolve(n("e49f"))}.bind(null,n)).catch(n.oe)},h=(getApp(),{components:{pageFooter:l,tabBar:f},data:function(){return{navlist:[],productList:[],navActive:0,number:"",is_diy:t.getStorageSync("is_diy"),height:0,hightArr:[],toView:"",tabbarH:0,footH:0,windowHeight:0,newData:{},activeRouter:"",pageHeight:"100%",sysHeight:s,footerStatus:!1,lock:!1}},computed:c({},(0,r.mapState)({cartNum:function(t){return t.indexData.cartNum}})),mounted:function(){var t=getCurrentPages(),e=t[t.length-1].route;this.activeRouter="/"+e,this.getAllCategory()},methods:{getNav:function(){var e=this;(0,o.getNavigation)().then((function(n){e.newData=n.data,e.newData.status&&e.newData.status.status?t.hideTabBar():t.showTabBar()}))},goRouter:function(e){var n=getCurrentPages(),i=n[n.length-1].$page.fullPath;e.link!=i&&t.switchTab({url:e.link,fail:function(n){t.redirectTo({url:e.link})}})},footHeight:function(t){this.footH=t},infoScroll:function(){var e=this,n=e.productList.length;this.number=e.productList[n-1].children.length,t.getSystemInfo({success:function(t){e.height=t.windowHeight*(750/t.windowWidth)-98}});for(var i=[],r=0;r<n;r++){var o=t.createSelectorQuery().in(this),a="#b"+r;o.select(a).boundingClientRect(),o.exec((function(t){var n=t[0].top;i.push(n),e.hightArr=i}))}},tap:function(t,e){this.toView=e,this.navActive=t,this.$set(this,"lock",!0)},getAllCategory:function(){var t=this;(0,i.getCategoryList)().then((function(e){t.productList=e.data,t.$nextTick((function(e){t.infoScroll()}))}))},scroll:function(t){var e=t.detail.scrollTop,n=this.hightArr;if(this.lock)this.$set(this,"lock",!1);else for(var i=0;i<n.length;i++)e>=0&&e<n[1]-n[0]?this.navActive=0:e>=n[i]-n[0]&&e<n[i+1]-n[0]?this.navActive=i:e>=n[n.length-1]-n[0]&&(this.navActive=n.length-1)},searchSubmitValue:function(e){if(!(this.$util.trim(e.detail.value).length>0))return this.$util.Tips({title:"请填写要搜索的产品信息"});t.navigateTo({url:"/pages/goods_list/index?searchValue="+e.detail.value})}}});e.default=h}).call(this,n("543d")["default"])},e21c:function(t,e,n){}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/goods_cate/goods_cate1-create-component',
    {
        'pages/goods_cate/goods_cate1-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("09c0"))
        })
    },
    [['pages/goods_cate/goods_cate1-create-component']]
]);
