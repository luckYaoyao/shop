require('../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/extension/news_details/index"],{"06a5":function(t,e,n){"use strict";var i=n("eea1"),a=n.n(i);a.a},2794:function(t,e,n){"use strict";n.r(e);var i=n("7557"),a=n("648c");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("06a5");var r,s=n("f0c5"),c=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],r);e["default"]=c.exports},"648c":function(t,e,n){"use strict";n.r(e);var i=n("908c"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},7557:function(t,e,n){"use strict";n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var i={jyfParser:function(){return Promise.all([n.e("common/vendor"),n.e("components/jyf-parser/jyf-parser")]).then(n.bind(null,"dce2"))}},a=function(){var t=this,e=t.$createElement,n=(t._self._c,t.store_info.id?t.$t("￥"):null),i=t.store_info.id?t.$t("￥"):null,a=t.store_info.id?t.$t("查看商品"):null,o=t.$t("和好友一起分享");t.$mp.data=Object.assign({},{$root:{m0:n,m1:i,m2:a,m3:o}})},o=[]},"908c":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n("d322"),a=r(n("66ca")),o=n("8ba8");function r(t){return t&&t.__esModule?t:{default:t}}var s=function(){n.e("pages/extension/components/shareInfo/index").then(function(){return resolve(n("d67f"))}.bind(null,n)).catch(n.oe)},c=function(){Promise.all([n.e("common/vendor"),n.e("components/home/index")]).then(function(){return resolve(n("03ff"))}.bind(null,n)).catch(n.oe)},u=function(){Promise.all([n.e("common/vendor"),n.e("components/jyf-parser/jyf-parser")]).then(function(){return resolve(n("dce2"))}.bind(null,n)).catch(n.oe)},l={components:{shareInfo:s,home:c,"jyf-parser":u},mixins:[a.default],data:function(){return{id:0,articleInfo:[],store_info:{},content:"",shareInfoStatus:!1,tagStyle:{img:"width:100%;display:block;",table:"width:100%",video:"width:100%"}}},onLoad:function(e){e.hasOwnProperty("id")?this.id=e.id:t.navigateBack({delta:1})},onShow:function(){this.getArticleOne()},onShareAppMessage:function(){var t=this;return t.$set(t,"actionSheetHidden",!t.actionSheetHidden),(0,o.userShare)(),{title:this.articleInfo.title||"",imageUrl:this.articleInfo.image_input.length?this.articleInfo.image_input[0]:"",path:"/pages/extension/news_details/index?id="+t.id+"&spid="+this.$store.state.app.uid}},onShareTimeline:function(){var t=this;return(0,o.userShare)(),{title:this.articleInfo.title,query:{id:t.id,spid:this.$store.state.app.uid||0},imageUrl:this.articleInfo.image_input.length?this.articleInfo.image_input[0]:""}},methods:{getArticleOne:function(){var e=this;(0,i.getArticleDetails)(e.id).then((function(n){t.setNavigationBarTitle({title:n.data.title.substring(0,7)+"..."}),e.$set(e,"articleInfo",n.data),e.$set(e,"store_info",n.data.store_info?n.data.store_info:{}),e.content=n.data.content}))},listenerActionSheet:function(){this.shareInfoStatus=!0},setShareInfoStatus:function(){this.shareInfoStatus=!1},setShareInfo:function(){var t=location.href+"&spid="+this.$store.state.app.uid,e={title:this.articleInfo.title,desc:this.articleInfo.synopsis,link:t,imgUrl:this.articleInfo.image_input.length?this.articleInfo.image_input[0]:""};this.$wechat.wechatEvevt(["updateAppMessageShareData","updateTimelineShareData","onMenuShareAppMessage","onMenuShareTimeline"],e)}}};e.default=l}).call(this,n("543d")["default"])},e863:function(t,e,n){"use strict";(function(t){n("248d");i(n("66fd"));var e=i(n("2794"));function i(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},eea1:function(t,e,n){}},[["e863","common/runtime","common/vendor"]]]);