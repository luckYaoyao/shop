(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/diy/components/combination"],{"35c7":function(t,n,i){"use strict";var o;i.d(n,"b",(function(){return a})),i.d(n,"c",(function(){return e})),i.d(n,"a",(function(){return o}));var a=function(){var t=this,n=t.$createElement;t._self._c},e=[]},4759:function(t,n,i){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var o=i("c159"),a=i("0e8f"),e={name:"combination",props:{dataConfig:{type:Object,default:function(){}},isSortType:{type:String|Number,default:0}},data:function(){return{pinkInfo:"",combinationList:[],numConfig:this.dataConfig.numConfig.val,txtColor:this.dataConfig.txtColor.color[0].item,themeColor:this.dataConfig.themeColor.color[0].item,mbConfig:this.dataConfig.mbConfig.val,lrConfig:this.dataConfig.lrConfig.val,imgUrl:this.dataConfig.imgConfig.url,priceShow:this.dataConfig.priceShow.val,bntShow:this.dataConfig.bntShow.val,titleShow:this.dataConfig.titleShow.val,pinkShow:this.dataConfig.pinkShow.val,joinShow:this.dataConfig.joinShow.val,prConfig:this.dataConfig.prConfig.val,bgColor:this.dataConfig.bgColor.color[0].item,conStyle:this.dataConfig.conStyle.type}},created:function(){},mounted:function(){this.pink(),this.getCombinationList()},methods:{getCombinationList:function(){var t=this,n=t.$config.LIMIT,i={page:1,limit:t.numConfig>=n?n:t.numConfig};(0,a.getCombinationList)(i).then((function(n){t.combinationList=n.data})).catch((function(n){return t.$util.Tips({title:n})}))},pink:function(){var t=this;(0,o.pink)().then((function(n){t.pinkInfo=n.data}))}}};n.default=e},6250:function(t,n,i){"use strict";i.r(n);var o=i("4759"),a=i.n(o);for(var e in o)"default"!==e&&function(t){i.d(n,t,(function(){return o[t]}))}(e);n["default"]=a.a},"9257e":function(t,n,i){},ce0e:function(t,n,i){"use strict";var o=i("9257e"),a=i.n(o);a.a},e694:function(t,n,i){"use strict";i.r(n);var o=i("35c7"),a=i("6250");for(var e in a)"default"!==e&&function(t){i.d(n,t,(function(){return a[t]}))}(e);i("ce0e");var f,r=i("f0c5"),c=Object(r["a"])(a["default"],o["b"],o["c"],!1,null,null,null,!1,o["a"],f);n["default"]=c.exports}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/diy/components/combination-create-component',
    {
        'pages/index/diy/components/combination-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("e694"))
        })
    },
    [['pages/index/diy/components/combination-create-component']]
]);
