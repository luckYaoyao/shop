(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/live_list/index"],{"16a6":function(t,n,e){},"502d":function(t,n,e){"use strict";var i=e("16a6"),c=e.n(i);c.a},"8aff":function(t,n,e){"use strict";e.r(n);var i=e("c26f"),c=e("8d0c");for(var a in c)"default"!==a&&function(t){e.d(n,t,(function(){return c[t]}))}(a);e("502d");var u,o=e("f0c5"),f=Object(o["a"])(c["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],u);n["default"]=f.exports},"8d0c":function(t,n,e){"use strict";e.r(n);var i=e("ef12"),c=e.n(i);for(var a in i)"default"!==a&&function(t){e.d(n,t,(function(){return i[t]}))}(a);n["default"]=c.a},c26f:function(t,n,e){"use strict";var i;e.d(n,"b",(function(){return c})),e.d(n,"c",(function(){return a})),e.d(n,"a",(function(){return i}));var c=function(){var t=this,n=t.$createElement;t._self._c},a=[]},e94c:function(t,n,e){"use strict";(function(t){e("cdba");i(e("66fd"));var n=i(e("8aff"));function i(t){return t&&t.__esModule?t:{default:t}}t(n.default)}).call(this,e("543d")["createPage"])},ef12:function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i=e("45d9"),c={name:"liveBroadcast",props:{dataConfig:{type:Object,default:function(){}}},data:function(){return{page:1,limit:10,listStyle:1,isScroll:!0,liveList:[]}},created:function(){},mounted:function(){this.getLiveList()},methods:{getLiveList:function(){var t=this;this.$config.LIMIT;this.isScroll&&(0,i.getLiveList)(this.page,this.limit).then((function(n){t.isScroll=n.data.length>=t.limit,t.page++,t.liveList=t.liveList.concat(n.data)})).catch((function(t){}))}},onReachBottom:function(){this.getLiveList()}};n.default=c}},[["e94c","common/runtime","common/vendor"]]]);