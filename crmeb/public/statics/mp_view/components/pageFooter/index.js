(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/pageFooter/index"],{"083e":function(t,e,n){"use strict";var r;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return r}));var a=function(){var t=this,e=t.$createElement,n=(t._self._c,t.newData?t.__map(t.newData.menuList,(function(e,n){var r=t.__get_orig(e),a=e.link==t.activityTab?t.$t(e.name):null,o=e.link!=t.activityTab?t.$t(e.name):null;return{$orig:r,m0:a,m1:o}})):null);t.$mp.data=Object.assign({},{$root:{l0:n}})},o=[]},"0dab":function(t,e,n){"use strict";n.r(e);var r=n("2dbb"),a=n.n(r);for(var o in r)"default"!==o&&function(t){n.d(e,t,(function(){return r[t]}))}(o);e["default"]=a.a},"2dbb":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r,a=n("26cb"),o=n("a9a5"),i=n("451d");function u(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function c(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?u(Object(n),!0).forEach((function(e){f(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function f(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var s=(r={name:"pageFooter",props:{status:{type:Number|String,default:1},countNum:{type:Number|String,default:0}},data:function(){return{newData:void 0,footHeight:0,isShow:!1}},computed:c({},(0,a.mapState)({configData:function(t){return t.app.pageFooter}}))},f(r,"computed",(0,a.mapGetters)(["isLogin","cartNum","activityTab"])),f(r,"watch",{activityTab:{handler:function(t,e){},deep:!0},configData:{handler:function(e,n){var r=this,a=t.createSelectorQuery().in(this);this.newData=e,this.$nextTick((function(){a.select("#target").boundingClientRect((function(e){t.$emit("footHeight",e.height),e&&(r.footHeight=e.height+50)})).exec()}))},deep:!0}}),f(r,"created",(function(){var e=this,n=getCurrentPages(),r=n[n.length-1].route;this.$store.commit("ACTIVITYTAB","/"+r),t.$on("uploadFooter",(function(){var t=getCurrentPages(),n=t[t.length-1].route;e.$store.commit("ACTIVITYTAB","/"+n)}))})),f(r,"onShow",(function(){})),f(r,"mounted",(function(){var e=this;(0,o.getNavigation)().then((function(n){t.setStorageSync("pageFoot",n.data),e.$store.commit("FOOT_UPLOAD",n.data),e.newData=n.data}));t.hideTabBar(),this.newData=this.$store.state.app.pageFooter,this.isLogin&&this.getCartNum()})),f(r,"onHide",(function(){t.$off(["uploadFooter"])})),f(r,"methods",{goRouter:function(e){var n=getCurrentPages(),r=n[n.length-1].route;this.$store.commit("ACTIVITYTAB",e.link),e.link!="/"+r&&t.switchTab({url:e.link,fail:function(n){t.redirectTo({url:e.link})}})},getCartNum:function(){var t=this,e=this;(0,i.getCartCounts)().then((function(n){e.cartCount=n.data.count,t.$store.commit("indexData/setCartNum",n.data.count>99?"...":n.data.count)}))}}),r);e.default=s}).call(this,n("543d")["default"])},"61b6":function(t,e,n){"use strict";var r=n("a7e4"),a=n.n(r);a.a},a7e4:function(t,e,n){},d9b2:function(t,e,n){"use strict";n.r(e);var r=n("083e"),a=n("0dab");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("61b6");var i,u=n("f0c5"),c=Object(u["a"])(a["default"],r["b"],r["c"],!1,null,"70846ef5",null,!1,r["a"],i);e["default"]=c.exports}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/pageFooter/index-create-component',
    {
        'components/pageFooter/index-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("d9b2"))
        })
    },
    [['components/pageFooter/index-create-component']]
]);
