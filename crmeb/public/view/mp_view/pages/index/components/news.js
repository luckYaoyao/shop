(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/components/news"],{"0a68a":function(t,e,n){},"3c9e":function(t,e,n){"use strict";var a=n("0a68a"),i=n.n(a);i.a},"3ef4":function(t,e,n){"use strict";n.r(e);var a=n("9416"),i=n.n(a);for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);e["default"]=i.a},"4fa7":function(t,e,n){"use strict";n.r(e);var a=n("de5a"),i=n("3ef4");for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);n("3c9e");var u,r=n("f0c5"),c=Object(r["a"])(i["default"],a["b"],a["c"],!1,null,null,null,!1,a["a"],u);e["default"]=c.exports},9416:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=o(n("cd99")),i=n("7f94");function o(t){return t&&t.__esModule?t:{default:t}}var u=getApp(),r={name:"news",props:{dataConfig:{type:Object,default:function(){}}},mixins:[a.default],watch:{dataConfig:{immediate:!0,handler:function(t,e){t&&(this.img=t.imgUrl.url,this.itemNew=t.newList.list,this.isShow=t.isShow.val)}}},data:function(){return{indicatorDots:!1,autoplay:!0,duration:500,img:"",itemNew:[],name:this.$options.name,isIframe:!1,isShow:!0}},created:function(){this.isIframe=u.globalData.isIframe},mounted:function(){},methods:{gopage:function(e){(0,i.goPage)().then((function(n){-1!=e.indexOf("http")||(["/pages/goods_cate/goods_cate","/pages/order_addcart/order_addcart","/pages/user/index"].indexOf(e),t.navigateTo({url:e}))}))}}};e.default=r}).call(this,n("543d")["default"])},de5a:function(t,e,n){"use strict";var a;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return a}));var i=function(){var t=this,e=t.$createElement;t._self._c},o=[]}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/components/news-create-component',
    {
        'pages/index/components/news-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("4fa7"))
        })
    },
    [['pages/index/components/news-create-component']]
]);
