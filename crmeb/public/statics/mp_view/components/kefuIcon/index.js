(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/kefuIcon/index"],{4325:function(t,e,n){"use strict";n.r(e);var u=n("8c84"),c=n("b212");for(var o in c)"default"!==o&&function(t){n.d(e,t,(function(){return c[t]}))}(o);n("5470");var r,a=n("f0c5"),f=Object(a["a"])(c["default"],u["b"],u["c"],!1,null,null,null,!1,u["a"],r);e["default"]=f.exports},5470:function(t,e,n){"use strict";var u=n("e787"),c=n.n(u);c.a},"8c84":function(t,e,n){"use strict";var u;n.d(e,"b",(function(){return c})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return u}));var c=function(){var t=this,e=t.$createElement;t._self._c},o=[]},b212:function(t,e,n){"use strict";n.r(e);var u=n("c653"),c=n.n(u);for(var o in u)"default"!==o&&function(t){n.d(e,t,(function(){return u[t]}))}(o);e["default"]=c.a},c653:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var u=n("c7fc"),c=(getApp(),{name:"kefuIcon",props:{ids:{type:Number,default:0},routineContact:{type:Number,default:0},storeInfo:{type:Object,default:function(){}},goodsCon:{type:Number,default:0}},data:function(){return{top:"480"}},mounted:function(){},methods:{goCustomer:function(){(0,u.getCustomer)("/pages/customer_list/chat?productId=".concat(this.ids))},setTouchMove:function(t){var e=this;t.touches[0].clientY<480&&t.touches[0].clientY>66&&(e.top=t.touches[0].clientY)}},created:function(){}});e.default=c},e787:function(t,e,n){}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/kefuIcon/index-create-component',
    {
        'components/kefuIcon/index-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("4325"))
        })
    },
    [['components/kefuIcon/index-create-component']]
]);
