(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/components/menus"],{"0a5d":function(t,n,e){},4527:function(t,n,e){"use strict";(function(t){Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var a=e("7f94"),i=getApp(),u={name:"menus",props:{dataConfig:{type:Object,default:function(){}}},watch:{dataConfig:{immediate:!0,handler:function(t,n){t&&(this.menus=t.imgList.list,this.isShow=t.isShow.val)}}},data:function(){return{menus:[],name:this.$options.name,isIframe:!1,isShow:!0}},created:function(){this.isIframe=i.globalData.isIframe},mounted:function(){},methods:{menusTap:function(n){(0,a.goPage)().then((function(e){-1!=n.indexOf("http")||(-1==["/pages/goods_cate/goods_cate","/pages/order_addcart/order_addcart","/pages/user/index"].indexOf(n)?t.navigateTo({url:n}):t.switchTab({url:n}))}))}}};n.default=u}).call(this,e("543d")["default"])},"94c5":function(t,n,e){"use strict";e.r(n);var a=e("4527"),i=e.n(a);for(var u in a)"default"!==u&&function(t){e.d(n,t,(function(){return a[t]}))}(u);n["default"]=i.a},ad57:function(t,n,e){"use strict";var a=e("0a5d"),i=e.n(a);i.a},c4ed:function(t,n,e){"use strict";var a;e.d(n,"b",(function(){return i})),e.d(n,"c",(function(){return u})),e.d(n,"a",(function(){return a}));var i=function(){var t=this,n=t.$createElement;t._self._c},u=[]},eed8:function(t,n,e){"use strict";e.r(n);var a=e("c4ed"),i=e("94c5");for(var u in i)"default"!==u&&function(t){e.d(n,t,(function(){return i[t]}))}(u);e("ad57");var o,c=e("f0c5"),r=Object(c["a"])(i["default"],a["b"],a["c"],!1,null,null,null,!1,a["a"],o);n["default"]=r.exports}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/components/menus-create-component',
    {
        'pages/index/components/menus-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("eed8"))
        })
    },
    [['pages/index/components/menus-create-component']]
]);
