(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods_search/index"],{"02e3":function(t,e,n){},1857:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return o})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return i}));var o=function(){var t=this,e=t.$createElement;t._self._c},a=[]},"1f9a":function(t,e,n){"use strict";(function(t){n("cdba");i(n("66fd"));var e=i(n("8e99"));function i(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])},"325f":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n("a8b0"),o=n("45d9"),a=s(n("cd99"));function s(t){return t&&t.__esModule?t:{default:t}}var c=function(){Promise.all([n.e("common/vendor"),n.e("components/goodList/index")]).then(function(){return resolve(n("b0b2"))}.bind(null,n)).catch(n.oe)},r=function(){Promise.all([n.e("common/vendor"),n.e("components/recommend/index")]).then(function(){return resolve(n("fe46"))}.bind(null,n)).catch(n.oe)},u={components:{goodList:c,recommend:r},mixins:[a.default],data:function(){return{hostProduct:[],searchValue:"",focus:!0,bastList:[],hotSearchList:[],first:0,limit:8,page:1,loading:!1,loadend:!1,loadTitle:"加载更多",hotPage:1,isScroll:!0,history:[]}},onShow:function(){this.getHostProduct(),this.searchList();try{this.hotSearchList=t.getStorageSync("hotList")}catch(e){}},onReachBottom:function(){this.bastList.length>0?this.getProductList():this.getHostProduct()},methods:{searchList:function(){var t=this;(0,o.searchList)({page:1,limit:10}).then((function(e){t.history=e.data}))},clear:function(){var e=this;(0,o.clearSearch)().then((function(n){t.showToast({title:n.msg,success:function(){e.history=[]}})}))},inputConfirm:function(e){e.detail.value&&(t.hideKeyboard(),this.setHotSearchValue(e.detail.value))},getRoutineHotSearch:function(){var t=this;(0,i.getSearchKeyword)().then((function(e){t.$set(t,"hotSearchList",e.data)}))},getProductList:function(){var t=this;t.loadend||t.loading||(t.loading=!0,t.loadTitle="",(0,i.getProductslist)({keyword:t.searchValue,page:t.page,limit:t.limit}).then((function(e){var n=e.data,i=n.length<t.limit;t.bastList=t.$util.SplitArray(n,t.bastList),t.$set(t,"bastList",t.bastList),t.loading=!1,t.loadend=i,t.loadTitle=i?"人家是有底线的~":"加载更多",t.page=t.page+1})).catch((function(e){t.loading=!1,t.loadTitle="加载更多"})))},getHostProduct:function(){var t=this;this.isScroll&&(0,i.getProductHot)(t.hotPage,t.limit).then((function(e){t.isScroll=e.data.length>=t.limit,t.hostProduct=t.hostProduct.concat(e.data),t.hotPage+=1}))},setHotSearchValue:function(t){this.$set(this,"searchValue",t),this.page=1,this.loadend=!1,this.$set(this,"bastList",[]),this.getProductList()},setValue:function(t){this.$set(this,"searchValue",t.detail.value)},searchBut:function(){var e=this;e.focus=!1,e.page=1,e.loadend=!1,e.$set(e,"bastList",[]),t.showLoading({title:"正在搜索中"}),e.getProductList(),t.hideLoading()}}};e.default=u}).call(this,n("543d")["default"])},"89bd":function(t,e,n){"use strict";n.r(e);var i=n("325f"),o=n.n(i);for(var a in i)"default"!==a&&function(t){n.d(e,t,(function(){return i[t]}))}(a);e["default"]=o.a},"8e99":function(t,e,n){"use strict";n.r(e);var i=n("1857"),o=n("89bd");for(var a in o)"default"!==a&&function(t){n.d(e,t,(function(){return o[t]}))}(a);n("9d8c");var s,c=n("f0c5"),r=Object(c["a"])(o["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],s);e["default"]=r.exports},"9d8c":function(t,e,n){"use strict";var i=n("02e3"),o=n.n(i);o.a}},[["1f9a","common/runtime","common/vendor"]]]);