(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/visualization/components/promotion"],{"5dbf":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return o})),n.d(e,"c",(function(){return s})),n.d(e,"a",(function(){return i}));var o=function(){var t=this,e=t.$createElement,n=(t._self._c,t.isShow&&t.benefit.length?t.$t(t.titleInfo[0].val):null),i=t.isShow&&t.benefit.length?t.$t(t.titleInfo[1].val):null,o=t.isShow&&t.benefit.length?t.$t("更多"):null,s=!t.isShow&&t.isIframe&&t.benefit.length?t.$t("更多"):null,a=t.isIframe&&!t.benefit.length?t.$t("更多"):null,l=t.isIframe&&!t.benefit.length?t.$t("促销单品，暂无数据"):null;t.$mp.data=Object.assign({},{$root:{m0:n,m1:i,m2:o,m3:s,m4:a,m5:l}})},s=[]},"7d6f":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;n("26cb");var i=n("c9cb"),o=n("dbf8"),s=getApp(),a=function(){n.e("components/promotionGood/index").then(function(){return resolve(n("3caf"))}.bind(null,n)).catch(n.oe)},l={name:"goodList",props:{dataConfig:{type:Object,default:function(){}}},components:{promotionGood:a},watch:{dataConfig:{immediate:!0,handler:function(t,e){t&&(this.isShow=t.isShow.val,this.selectType=t.tabConfig.tabVal,this.$set(this,"selectId",t.selectConfig.activeValue||""),this.$set(this,"type",t.selectSortConfig.activeValue),this.salesOrder=1==t.goodsSort.type?"desc":"",this.newsOrder=2==t.goodsSort.type?"news":"",this.ids=t.ids?t.ids.join(","):"",this.numConfig=t.numConfig.val,this.titleInfo=t.titleInfo.list,this.productslist())}}},created:function(){this.isIframe=s.globalData.isIframe},mounted:function(){},data:function(){return{benefit:[],salesInfo:this.$t("库存商品优惠促销活动"),name:this.$options.name,isShow:!0,isIframe:s.globalData.isIframe,selectType:0,selectId:"",salesOrder:"",newsOrder:"",ids:"",page:1,limit:this.$config.LIMIT,type:"",numConfig:0,titleInfo:[]}},methods:{productslist:function(){var t=this,e={};e=t.selectType?{page:t.page,limit:t.limit,type:t.type,ids:t.ids,selectType:t.selectType}:{page:t.page,limit:t.numConfig<=t.limit?t.numConfig:t.limit,type:t.type,newsOrder:t.newsOrder,salesOrder:t.salesOrder,selectId:t.selectId,selectType:t.selectType},(0,o.getHomeProducts)(e).then((function(e){t.benefit=e.data.list})).catch((function(e){t.$util.Tips({title:e})}))},gopage:function(e){(0,i.goPage)().then((function(n){t.navigateTo({url:e})}))}}};e.default=l}).call(this,n("543d")["default"])},8675:function(t,e,n){"use strict";n.r(e);var i=n("7d6f"),o=n.n(i);for(var s in i)"default"!==s&&function(t){n.d(e,t,(function(){return i[t]}))}(s);e["default"]=o.a},a6ed:function(t,e,n){"use strict";var i=n("f6c7"),o=n.n(i);o.a},eca2:function(t,e,n){"use strict";n.r(e);var i=n("5dbf"),o=n("8675");for(var s in o)"default"!==s&&function(t){n.d(e,t,(function(){return o[t]}))}(s);n("a6ed");var a,l=n("f0c5"),f=Object(l["a"])(o["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],a);e["default"]=f.exports},f6c7:function(t,e,n){}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/visualization/components/promotion-create-component',
    {
        'pages/index/visualization/components/promotion-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("eca2"))
        })
    },
    [['pages/index/visualization/components/promotion-create-component']]
]);
