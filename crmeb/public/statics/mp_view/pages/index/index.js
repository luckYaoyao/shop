(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/index"],{"4c54":function(e,n,t){"use strict";t.d(n,"b",(function(){return i})),t.d(n,"c",(function(){return o})),t.d(n,"a",(function(){}));var i=function(){var e=this.$createElement;this._self._c},o=[]},"62fc":function(e,n,t){"use strict";(function(e,n){var i=t("4ea4");t("4789");i(t("66fd"));var o=i(t("c17f"));e.__webpack_require_UNI_MP_PLUGIN__=t,n(o.default)}).call(this,t("bc2e")["default"],t("543d")["createPage"])},"721c":function(e,n,t){"use strict";t.r(n);var i=t("ecca"),o=t.n(i);for(var a in i)["default"].indexOf(a)<0&&function(e){t.d(n,e,(function(){return i[e]}))}(a);n["default"]=o.a},c17f:function(e,n,t){"use strict";t.r(n);var i=t("4c54"),o=t("721c");for(var a in o)["default"].indexOf(a)<0&&function(e){t.d(n,e,(function(){return o[e]}))}(a);var r=t("f0c5"),c=Object(r["a"])(o["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],void 0);n["default"]=c.exports},ecca:function(e,n,t){"use strict";(function(e){var i=t("4ea4");Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;i(t("00e9"));var o=t("4e39"),a=(getApp(),{data:function(){return{isDiy:e.getStorageSync("is_diy"),shareInfo:{},loading:!1}},components:{diy:function(){Promise.all([t.e("common/vendor"),t.e("pages/index/diy/index")]).then(function(){return resolve(t("837a"))}.bind(null,t)).catch(t.oe)},visualization:function(){Promise.all([t.e("common/vendor"),t.e("pages/index/visualization/index")]).then(function(){return resolve(t("e089"))}.bind(null,t)).catch(t.oe)}},onLoad:function(){e.hideTabBar(),this.setOpenShare()},onShow:function(){this.getVersion(0)},onHide:function(){},methods:{getVersion:function(n){var t=this;e.$emit("uploadFooter"),(0,o.getVersion)(n).then((function(n){t.version=n.data.version,t.isDiy=n.data.is_diy,t.loading=!0,e.setStorageSync("is_diy",n.data.is_diy),e.getStorageSync("DIY_VERSION")&&n.data.version==e.getStorageSync("DIY_VERSION")||(e.getStorageSync("DIY_VERSION")&&(e.setStorageSync("DIY_VERSION",n.data.version),t.isDiy?t.$refs.diy.reconnect():t.$refs.vis.reconnect()),e.setStorageSync("DIY_VERSION",n.data.version))})).catch((function(e){t.$util.Tips({title:e})}))},setOpenShare:function(){var e=this;(0,o.getShare)().then((function(n){var t=n.data;e.shareInfo=t}))}},onReachBottom:function(){this.isDiy&&this.$refs.diy.onsollBotton()},onPageScroll:function(n){e.$emit("scroll")},onShareAppMessage:function(e){return{title:this.shareInfo.title,path:"/pages/index/index?spid="+this.$store.state.app.uid||!1,imageUrl:this.shareInfo.img}},onShareTimeline:function(){return{title:this.shareInfo.title,query:{spid:this.$store.state.app.uid||0},imageUrl:this.shareInfo.img}}});n.default=a}).call(this,t("543d")["default"])}},[["62fc","common/runtime","common/vendor"]]]);