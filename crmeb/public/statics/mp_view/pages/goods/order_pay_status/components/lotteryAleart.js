require('../../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods/order_pay_status/components/lotteryAleart"],{"0d4e":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{aleartData:{}}},props:{aleartType:{type:Number},alData:{type:Object},aleartStatus:{type:Boolean,default:!1}},watch:{aleartType:function(t){2===t&&(this.aleartData={title:"中奖记录",img:this.alData.image,msg:this.alData.prompt,btn:"我知道了",type:this.alData.type})},aleartStatus:function(t){t||(this.aleartData={})}},methods:{posterImageClose:function(){this.$emit("close",!1)}}};a.default=n},"0fb4":function(t,a,e){},1763:function(t,a,e){"use strict";var n;e.d(a,"b",(function(){return r})),e.d(a,"c",(function(){return u})),e.d(a,"a",(function(){return n}));var r=function(){var t=this,a=t.$createElement,e=(t._self._c,t.aleartStatus?t.$t(t.aleartData.title):null),n=t.aleartStatus?t.$t(t.aleartData.msg):null,r=t.aleartStatus?t.$t("我知道了"):null;t.$mp.data=Object.assign({},{$root:{m0:e,m1:n,m2:r}})},u=[]},b9b9:function(t,a,e){"use strict";var n=e("0fb4"),r=e.n(n);r.a},bd3d:function(t,a,e){"use strict";e.r(a);var n=e("1763"),r=e("d090");for(var u in r)"default"!==u&&function(t){e.d(a,t,(function(){return r[t]}))}(u);e("b9b9");var l,o=e("f0c5"),s=Object(o["a"])(r["default"],n["b"],n["c"],!1,null,"786f0764",null,!1,n["a"],l);a["default"]=s.exports},d090:function(t,a,e){"use strict";e.r(a);var n=e("0d4e"),r=e.n(n);for(var u in n)"default"!==u&&function(t){e.d(a,t,(function(){return n[t]}))}(u);a["default"]=r.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/goods/order_pay_status/components/lotteryAleart-create-component',
    {
        'pages/goods/order_pay_status/components/lotteryAleart-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("bd3d"))
        })
    },
    [['pages/goods/order_pay_status/components/lotteryAleart-create-component']]
]);
