(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/commission_rank/index"],{"278a":function(t,n,e){"use strict";var i=e("f842"),a=e.n(i);a.a},"3a6e":function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i=e("3023"),a=e("12f4"),o=e("26cb"),s=r(e("cd99"));function r(t){return t&&t.__esModule?t:{default:t}}var u=function(){e.e("components/Authorize").then(function(){return resolve(e("0076"))}.bind(null,e)).catch(e.oe)},c={components:{authorize:u},mixins:[s.default],data:function(){return{navList:["周排行","月排行"],active:0,rankList:[],page:1,limit:20,loadend:!1,loading:!1,loadTitle:"加载更多",type:"week",position:0,isAuto:!1,isShowAuth:!1}},computed:(0,o.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,n){t&&this.getBrokerageRankList()},deep:!0}},onLoad:function(){this.isLogin?this.getBrokerageRankList():(0,a.toLogin)()},methods:{onLoadFun:function(){this.getBrokerageRankList()},authColse:function(t){this.isShowAuth=t},switchTap:function(t){this.active=t,this.type=t?"month":"week",this.page=1,this.loadend=!1,this.$set(this,"rankList",[]),this.getBrokerageRankList()},getBrokerageRankList:function(){var t=this;this.loadend||this.loading||(this.loading=!0,this.loadTitle="",(0,i.getBrokerageRank)({page:this.page,limit:this.limit,type:this.type}).then((function(n){var e=n.data.rank,i=e.length<t.limit;t.rankList.push.apply(t.rankList,e),t.loading=!1,t.loadend=i,t.loadTitle=i?"😕我也是有底线的":"加载更多",t.$set(t,"rankList",t.rankList),t.position=n.data.position,t.page+=1})).catch((function(n){t.loading=!1,t.loadTitle="加载更多"})))}},onReachBottom:function(){this.getBrokerageRankList()}};n.default=c},"88f8":function(t,n,e){"use strict";e.r(n);var i=e("3a6e"),a=e.n(i);for(var o in i)"default"!==o&&function(t){e.d(n,t,(function(){return i[t]}))}(o);n["default"]=a.a},b3fa:function(t,n,e){"use strict";(function(t){e("cdba");i(e("66fd"));var n=i(e("b55e"));function i(t){return t&&t.__esModule?t:{default:t}}t(n.default)}).call(this,e("543d")["createPage"])},b55e:function(t,n,e){"use strict";e.r(n);var i=e("de6b"),a=e("88f8");for(var o in a)"default"!==o&&function(t){e.d(n,t,(function(){return a[t]}))}(o);e("278a");var s,r=e("f0c5"),u=Object(r["a"])(a["default"],i["b"],i["c"],!1,null,"22ade8da",null,!1,i["a"],s);n["default"]=u.exports},de6b:function(t,n,e){"use strict";var i;e.d(n,"b",(function(){return a})),e.d(n,"c",(function(){return o})),e.d(n,"a",(function(){return i}));var a=function(){var t=this,n=t.$createElement;t._self._c},o=[]},f842:function(t,n,e){}},[["b3fa","common/runtime","common/vendor"]]]);