(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/visualization/components/popular"],{"0e46":function(t,e,i){"use strict";i.r(e);var n=i("8047"),s=i("8b92");for(var o in s)"default"!==o&&function(t){i.d(e,t,(function(){return s[t]}))}(o);i("216d");var a,c=i("f0c5"),l=Object(c["a"])(s["default"],n["b"],n["c"],!1,null,null,null,!1,n["a"],a);e["default"]=l.exports},"216d":function(t,e,i){"use strict";var n=i("c6c7"),s=i.n(n);s.a},8047:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return s})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var s=function(){var t=this,e=t.$createElement;t._self._c},o=[]},"8b92":function(t,e,i){"use strict";i.r(e);var n=i("d647"),s=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=s.a},c6c7:function(t,e,i){},d647:function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=a(i("5411")),s=(i("26cb"),i("376f")),o=i("b2cd");function a(t){return t&&t.__esModule?t:{default:t}}var c=getApp(),l={name:"goodList",mixins:[n.default],props:{dataConfig:{type:Object,default:function(){}}},watch:{dataConfig:{immediate:!0,handler:function(t,e){t&&(this.isShow=t.isShow.val,this.selectType=t.tabConfig.tabVal,this.$set(this,"selectId",t.selectConfig&&t.selectConfig.activeValue?t.selectConfig.activeValue:""),this.$set(this,"type",t.selectSortConfig&&t.selectSortConfig.activeValue?t.selectSortConfig.activeValue:""),this.salesOrder=1==t.goodsSort.type?"desc":"",this.newsOrder=2==t.goodsSort.type?"news":"",this.ids=t.ids?t.ids.join(","):"",this.numConfig=t.numConfig.val,this.titleInfo=t.titleInfo.list,this.productslist())}}},created:function(){},mounted:function(){},data:function(){return{hotList:[],name:this.$options.name,isShow:!0,isIframe:c.globalData.isIframe,selectType:0,selectId:"",salesOrder:"",newsOrder:"",ids:"",page:1,limit:3,type:"",numConfig:0,titleInfo:[]}},methods:{productslist:function(){var t=this,e={};e=t.selectType?{page:t.page,limit:t.limit,type:t.type,ids:t.ids,selectType:t.selectType}:{page:t.page,limit:t.numConfig<=t.limit?t.numConfig:t.limit,type:t.type,newsOrder:t.newsOrder,salesOrder:t.salesOrder,selectId:t.selectId,selectType:t.selectType},(0,o.getHomeProducts)(e).then((function(e){t.hotList=e.data.list})).catch((function(e){t.$util.Tips({title:e})}))},gopage:function(e){(0,s.goPage)().then((function(i){t.navigateTo({url:e})}))}}};e.default=l}).call(this,i("543d")["default"])}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/visualization/components/popular-create-component',
    {
        'pages/index/visualization/components/popular-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("0e46"))
        })
    },
    [['pages/index/visualization/components/popular-create-component']]
]);
