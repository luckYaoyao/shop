require('../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/commission_rank/index"],{"0546":function(t,n,e){"use strict";e.r(n);var i=e("dfd6"),a=e.n(i);for(var o in i)"default"!==o&&function(t){e.d(n,t,(function(){return i[t]}))}(o);n["default"]=a.a},2402:function(t,n,e){"use strict";e.r(n);var i=e("fec3"),a=e("0546");for(var o in a)"default"!==o&&function(t){e.d(n,t,(function(){return a[t]}))}(o);e("2d65");var r,s=e("f0c5"),u=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,"9b10a608",null,!1,i["a"],r);n["default"]=u.exports},"2d65":function(t,n,e){"use strict";var i=e("f601"),a=e.n(i);a.a},dfd6:function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i=e("5367"),a=e("f20a"),o=e("26cb"),r=s(e("a672"));function s(t){return t&&t.__esModule?t:{default:t}}var u=function(){e.e("components/emptyPage").then(function(){return resolve(e("ccbc"))}.bind(null,e)).catch(e.oe)},c=function(){e.e("components/Authorize").then(function(){return resolve(e("8dd2"))}.bind(null,e)).catch(e.oe)},l={components:{authorize:c,emptyPage:u},mixins:[r.default],data:function(){return{navList:[this.$t("周排行"),this.$t("月排行")],active:0,rankList:[],page:1,limit:20,loadend:!1,loading:!1,loadTitle:this.$t("加载更多"),type:"week",position:0,isAuto:!1,isShowAuth:!1}},computed:(0,o.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,n){t&&this.getBrokerageRankList()},deep:!0}},onLoad:function(){this.isLogin?this.getBrokerageRankList():(0,a.toLogin)()},methods:{onLoadFun:function(){this.getBrokerageRankList()},authColse:function(t){this.isShowAuth=t},switchTap:function(t){this.active=t,this.type=t?"month":"week",this.page=1,this.loadend=!1,this.$set(this,"rankList",[]),this.getBrokerageRankList()},getBrokerageRankList:function(){var t=this;this.loadend||this.loading||(this.loading=!0,this.loadTitle="",(0,i.getBrokerageRank)({page:this.page,limit:this.limit,type:this.type}).then((function(n){var e=n.data.rank,i=e.length<t.limit;t.rankList.push.apply(t.rankList,e),t.loading=!1,t.loadend=i,t.loadTitle=i?t.$t("我也是有底线的"):t.$t("加载更多"),t.$set(t,"rankList",t.rankList),t.position=n.data.position,t.page+=1})).catch((function(n){t.loading=!1,t.loadTitle=t.$t("加载更多")})))}},onReachBottom:function(){this.getBrokerageRankList()}};n.default=l},ed29:function(t,n,e){"use strict";(function(t){e("4a5c");i(e("66fd"));var n=i(e("2402"));function i(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=e,t(n.default)}).call(this,e("543d")["createPage"])},f601:function(t,n,e){},fec3:function(t,n,e){"use strict";var i;e.d(n,"b",(function(){return a})),e.d(n,"c",(function(){return o})),e.d(n,"a",(function(){return i}));var a=function(){var t=this,n=t.$createElement,e=(t._self._c,t.position?t.$t("您目前的排名"):null),i=t.position?null:t.$t("您目前暂无排名"),a=t.__map(t.navList,(function(n,e){var i=t.__get_orig(n),a=t.$t(n);return{$orig:i,m2:a}})),o=t.$t("￥"),r=t.__map(t.rankList,(function(n,e){var i=t.__get_orig(n),a=t.$t(n.nickname);return{$orig:i,m3:a}})),s=0!=t.rankList.length||t.loading?null:t.$t("暂无排名~");t.$mp.data=Object.assign({},{$root:{m0:e,m1:i,l0:a,m4:o,l1:r,m5:s}})},o=[]}},[["ed29","common/runtime","common/vendor"]]]);