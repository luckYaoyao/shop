(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/components/recommend"],{"11fb":function(t,e,n){},2454:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return o})),n.d(e,"c",(function(){return s})),n.d(e,"a",(function(){return i}));var o=function(){var t=this,e=t.$createElement;t._self._c},s=[]},4705:function(t,e,n){"use strict";n.r(e);var i=n("2454"),o=n("4de9");for(var s in o)"default"!==s&&function(t){n.d(e,t,(function(){return o[t]}))}(s);n("c540");var a,c=n("f0c5"),l=Object(c["a"])(o["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],a);e["default"]=l.exports},"4de9":function(t,e,n){"use strict";n.r(e);var i=n("62e3"),o=n.n(i);for(var s in i)"default"!==s&&function(t){n.d(e,t,(function(){return i[t]}))}(s);e["default"]=o.a},"62e3":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;n("26cb");var i=n("7f94"),o=n("a8b0"),s=a(n("cd99"));function a(t){return t&&t.__esModule?t:{default:t}}var c=getApp(),l=function(){Promise.all([n.e("common/vendor"),n.e("components/goodList/index")]).then(function(){return resolve(n("b0b2"))}.bind(null,n)).catch(n.oe)},r={name:"goodList",props:{dataConfig:{type:Object,default:function(){}}},mixins:[s.default],components:{goodLists:l},created:function(){},mounted:function(){},watch:{dataConfig:{immediate:!0,handler:function(t,e){t&&(this.isShow=t.isShow.val,this.selectType=t.tabConfig.tabVal,this.$set(this,"selectId",t.selectConfig.activeValue||""),this.$set(this,"type",t.selectSortConfig.activeValue),this.salesOrder=1==t.goodsSort.type?"desc":"",this.newsOrder=2==t.goodsSort.type?"news":"",this.ids=t.ids?t.ids.join(","):"",this.numConfig=t.numConfig.val,this.titleInfo=t.titleInfo.list,this.productslist())}}},data:function(){return{circular:!0,interval:3e3,duration:500,bastList:[],name:this.$options.name,isShow:!0,isIframe:c.globalData.isIframe,selectType:0,selectId:"",salesOrder:"",newsOrder:"",ids:"",page:1,limit:this.$config.LIMIT,type:"",numConfig:0,titleInfo:[]}},methods:{productslist:function(){var t=this,e={};e=t.selectType?{page:t.page,limit:t.limit,type:t.type,ids:t.ids,selectType:t.selectType}:{page:t.page,limit:t.numConfig<=t.limit?t.numConfig:t.limit,type:t.type,newsOrder:t.newsOrder,salesOrder:t.salesOrder,selectId:t.selectId,selectType:t.selectType},(0,o.getHomeProducts)(e).then((function(e){t.bastList=e.data.list})).catch((function(e){t.$util.Tips({title:e})}))},gopage:function(e){t.navigateTo({url:e})},goDetail:function(e){var n=this;(0,i.goPage)().then((function(o){(0,i.goShopDetail)(e,n.uid).then((function(n){t.navigateTo({url:"/pages/goods_details/index?id=".concat(e.id)})}))}))}}};e.default=r}).call(this,n("543d")["default"])},c540:function(t,e,n){"use strict";var i=n("11fb"),o=n.n(i);o.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/components/recommend-create-component',
    {
        'pages/index/components/recommend-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("4705"))
        })
    },
    [['pages/index/components/recommend-create-component']]
]);
