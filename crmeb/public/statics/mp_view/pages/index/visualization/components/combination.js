(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/visualization/components/combination"],{"12e6":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i("4729"),s=i("f15c"),a=getApp(),o={name:"combination",props:{dataConfig:{type:Object,default:function(){}}},watch:{dataConfig:{immediate:!0,handler:function(t,e){t&&(this.isShow=t.isShow.val,this.selectType=t.tabConfig.tabVal,this.$set(this,"selectId",t.selectConfig.activeValue||""),this.$set(this,"type",t.titleInfo.type),this.salesOrder=1==t.goodsSort.type?"desc":"",this.newsOrder=2==t.goodsSort.type?"news":"",this.ids=t.ids?t.ids.join(","):"",this.numConfig=t.numConfig.val,this.productslist())}}},data:function(){return{combinationList:[],name:this.$options.name,isIframe:a.globalData.isIframe,isShow:!0,selectType:0,selectId:"",salesOrder:"",newsOrder:"",ids:"",page:1,limit:this.$config.LIMIT,type:"",numConfig:0}},created:function(){},mounted:function(){},methods:{productslist:function(){var t=this,e={};e=t.selectType?{page:t.page,limit:t.limit,type:t.type,ids:t.ids,selectType:t.selectType}:{page:t.page,limit:t.numConfig<=t.limit?t.numConfig:t.limit,type:t.type,newsOrder:t.newsOrder,salesOrder:t.salesOrder,selectId:t.selectId,selectType:t.selectType},(0,s.getHomeProducts)(e).then((function(e){t.combinationList=e.data.list})).catch((function(e){t.$util.Tips({title:e})}))},goDetail:function(e){(0,n.goPage)(e).then((function(i){t.navigateTo({url:"/pages/activity/goods_combination_details/index?id=".concat(e.id)})}))}}};e.default=o}).call(this,i("543d")["default"])},"5b78":function(t,e,i){"use strict";i.r(e);var n=i("cc86"),s=i("adcd");for(var a in s)"default"!==a&&function(t){i.d(e,t,(function(){return s[t]}))}(a);i("6d5e");var o,c=i("f0c5"),l=Object(c["a"])(s["default"],n["b"],n["c"],!1,null,null,null,!1,n["a"],o);e["default"]=l.exports},"6d5e":function(t,e,i){"use strict";var n=i("8804"),s=i.n(n);s.a},8804:function(t,e,i){},adcd:function(t,e,i){"use strict";i.r(e);var n=i("12e6"),s=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=s.a},cc86:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return s})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var s=function(){var t=this,e=t.$createElement;t._self._c},a=[]}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/visualization/components/combination-create-component',
    {
        'pages/index/visualization/components/combination-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("5b78"))
        })
    },
    [['pages/index/visualization/components/combination-create-component']]
]);
