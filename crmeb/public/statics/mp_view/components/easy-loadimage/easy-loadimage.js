(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/easy-loadimage/easy-loadimage"],{"131c":function(t,n,o){},"4f09":function(t,n,o){"use strict";var e;o.d(n,"b",(function(){return r})),o.d(n,"c",(function(){return i})),o.d(n,"a",(function(){return e}));var r=function(){var t=this,n=t.$createElement;t._self._c},i=[]},6854:function(t,n,o){"use strict";var e=o("131c"),r=o.n(e);r.a},9362:function(t,n,o){"use strict";o.r(n);var e=o("4f09"),r=o("f1c3");for(var i in r)"default"!==i&&function(t){o.d(n,t,(function(){return r[t]}))}(i);o("6854");var a,c=o("f0c5"),u=Object(c["a"])(r["default"],e["b"],e["c"],!1,null,"11fad8c7",null,!1,e["a"],a);n["default"]=u.exports},ed6b:function(t,n,o){"use strict";(function(t){Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var e=o("77d3");function r(){return"xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g,(function(t){var n=16*Math.random()|0,o="x"==t?n:3&n|8;return o.toString(16)}))}var i={props:{imageSrc:{type:String,default:""},mode:{type:String,default:""},loadingMode:{type:String,default:"looming-gray"},openTransition:{type:Boolean,default:!0},viewHeight:{type:Number,default:function(){return t.getSystemInfoSync().windowHeight}}},data:function(){var n=this;return{uid:"uid-"+r(),loadImg:!1,showImg:!1,isLoadError:!1,showTransition:!1,scrollFn:(0,e.throttle)((function(){if(!n.loadImg&&!n.isLoadError){var o=n.uid,e=t.createSelectorQuery().in(n);e.select("#"+o).boundingClientRect((function(t){t&&t.top-n.viewHeight<0&&(n.loadImg=!!n.imageSrc,n.isLoadError=!n.loadImg)})).exec()}}),200)}},methods:{init:function(){this.$nextTick(this.onScroll)},handleImgLoad:function(t){var n=this;this.showImg=!0,setTimeout((function(){n.showTransition=!0}),50)},handleImgError:function(t){this.isLoadError=!0},onScroll:function(){this.scrollFn()}},mounted:function(){this.init(),t.$on("scroll",this.scrollFn),this.onScroll()},beforeDestroy:function(){t.$off("scroll",this.scrollFn)}};n.default=i}).call(this,o("543d")["default"])},f1c3:function(t,n,o){"use strict";o.r(n);var e=o("ed6b"),r=o.n(e);for(var i in e)"default"!==i&&function(t){o.d(n,t,(function(){return e[t]}))}(i);n["default"]=r.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/easy-loadimage/easy-loadimage-create-component',
    {
        'components/easy-loadimage/easy-loadimage-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("9362"))
        })
    },
    [['components/easy-loadimage/easy-loadimage-create-component']]
]);
