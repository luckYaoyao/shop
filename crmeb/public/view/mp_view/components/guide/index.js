(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/guide/index"],{3235:function(t,n,e){"use strict";(function(t){Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var e={data:function(){return{autoplay:!1,duration:500,jumpover:"跳过",experience:"立即体验",time:5,timecount:void 0}},props:{advList:{type:Array,default:function(){}}},mounted:function(){this.timer()},methods:{timer:function(){var t=this,n=5;this.timecount=setInterval((function(){n--,t.time=n,n<=0&&(clearInterval(t.timecount),t.launchFlag())}),1e3)},launchFlag:function(){clearInterval(this.timecount),t.switchTab({url:"/pages/index/index"})}}};n.default=e}).call(this,e("543d")["default"])},"35a1":function(t,n,e){"use strict";var u;e.d(n,"b",(function(){return c})),e.d(n,"c",(function(){return i})),e.d(n,"a",(function(){return u}));var c=function(){var t=this,n=t.$createElement;t._self._c},i=[]},"3fcc":function(t,n,e){},7382:function(t,n,e){"use strict";e.r(n);var u=e("35a1"),c=e("9e06");for(var i in c)"default"!==i&&function(t){e.d(n,t,(function(){return c[t]}))}(i);e("dc71");var a,r=e("f0c5"),o=Object(r["a"])(c["default"],u["b"],u["c"],!1,null,null,null,!1,u["a"],a);n["default"]=o.exports},"9e06":function(t,n,e){"use strict";e.r(n);var u=e("3235"),c=e.n(u);for(var i in u)"default"!==i&&function(t){e.d(n,t,(function(){return u[t]}))}(i);n["default"]=c.a},dc71:function(t,n,e){"use strict";var u=e("3fcc"),c=e.n(u);c.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/guide/index-create-component',
    {
        'components/guide/index-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("7382"))
        })
    },
    [['components/guide/index-create-component']]
]);
