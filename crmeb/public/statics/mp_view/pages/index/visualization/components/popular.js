(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/visualization/components/popular"],{"4c76":function(t,e,i){"use strict";i.r(e);var n=i("9bc9"),s=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=s.a},"8dca":function(t,e,i){},"941d":function(t,e,i){"use strict";var n=i("8dca"),s=i.n(n);s.a},"9bc9":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=a(i("66ca")),s=(i("26cb"),i("2c78")),o=i("1579");function a(t){return t&&t.__esModule?t:{default:t}}var l=getApp(),c={name:"goodList",mixins:[n.default],props:{dataConfig:{type:Object,default:function(){}}},watch:{dataConfig:{immediate:!0,handler:function(t,e){t&&(this.isShow=t.isShow.val,this.selectType=t.tabConfig.tabVal,this.$set(this,"selectId",t.selectConfig&&t.selectConfig.activeValue?t.selectConfig.activeValue:""),this.$set(this,"type",t.selectSortConfig&&t.selectSortConfig.activeValue?t.selectSortConfig.activeValue:""),this.salesOrder=1==t.goodsSort.type?"desc":"",this.newsOrder=2==t.goodsSort.type?"news":"",this.ids=t.ids?t.ids.join(","):"",this.numConfig=t.numConfig.val,this.titleInfo=t.titleInfo.list,this.productslist())}}},created:function(){},mounted:function(){},data:function(){return{hotList:[],name:this.$options.name,isShow:!0,isIframe:l.globalData.isIframe,selectType:0,selectId:"",salesOrder:"",newsOrder:"",ids:"",page:1,limit:this.$config.LIMIT,type:"",numConfig:0,titleInfo:[]}},methods:{productslist:function(){var t=this,e={};e=t.selectType?{page:t.page,limit:t.limit,type:t.type,ids:t.ids,selectType:t.selectType}:{page:t.page,limit:t.numConfig<=t.limit?t.numConfig:t.limit,type:t.type,newsOrder:t.newsOrder,salesOrder:t.salesOrder,selectId:t.selectId,selectType:t.selectType},(0,o.getHomeProducts)(e).then((function(e){t.hotList=e.data.list})).catch((function(e){t.$util.Tips({title:e})}))},gopage:function(e){(0,s.goPage)().then((function(i){t.navigateTo({url:e})}))}}};e.default=c}).call(this,i("543d")["default"])},"9fe0":function(t,e,i){"use strict";i.d(e,"b",(function(){return s})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var n={easyLoadimage:function(){return Promise.all([i.e("common/vendor"),i.e("components/easy-loadimage/easy-loadimage")]).then(i.bind(null,"9362"))}},s=function(){var t=this,e=t.$createElement,i=(t._self._c,t.isShow&&t.hotList.length?t.$t(t.titleInfo[0].val):null),n=t.isShow&&t.hotList.length?t.$t(t.titleInfo[1].val):null,s=t.isShow&&t.hotList.length?t.$t("更多"):null,o=t.$t("热度 TOP"),a=!t.isShow&&t.isIframe&&t.hotList.length?t.$t("更多"):null,l=t.isIframe&&!t.hotList.length?t.$t("更多"):null,c=t.isIframe&&!t.hotList.length?t.$t("排行榜、暂无数据"):null;t.$mp.data=Object.assign({},{$root:{m0:i,m1:n,m2:s,m3:o,m4:a,m5:l,m6:c}})},o=[]},ae22:function(t,e,i){"use strict";i.r(e);var n=i("9fe0"),s=i("4c76");for(var o in s)"default"!==o&&function(t){i.d(e,t,(function(){return s[t]}))}(o);i("941d");var a,l=i("f0c5"),c=Object(l["a"])(s["default"],n["b"],n["c"],!1,null,"55d50139",null,!1,n["a"],a);e["default"]=c.exports}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/visualization/components/popular-create-component',
    {
        'pages/index/visualization/components/popular-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("ae22"))
        })
    },
    [['pages/index/visualization/components/popular-create-component']]
]);
