(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-annex-web_view-index"],{6652:function(e,n,t){"use strict";t.r(n);var i=t("9eae"),r=t.n(i);for(var u in i)"default"!==u&&function(e){t.d(n,e,(function(){return i[e]}))}(u);n["default"]=r.a},"9eae":function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i={data:function(){return{windowH:0,windowW:0,webviewStyles:{progress:{color:"transparent"}},url:""}},onLoad:function(e){this.url=e.url;try{var n=uni.getSystemInfoSync();this.windowW=n.windowWidth,this.windowH=n.windowHeight}catch(t){}}};n.default=i},a160:function(e,n,t){"use strict";t.r(n);var i=t("d1ee"),r=t("6652");for(var u in r)"default"!==u&&function(e){t.d(n,e,(function(){return r[e]}))}(u);var a,o=t("f0c5"),w=Object(o["a"])(r["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],a);n["default"]=w.exports},d1ee:function(e,n,t){"use strict";var i;t.d(n,"b",(function(){return r})),t.d(n,"c",(function(){return u})),t.d(n,"a",(function(){return i}));var r=function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("v-uni-web-view",{staticClass:"web-view",style:{width:e.windowW+"px",height:e.windowH+"px"},attrs:{"webview-styles":e.webviewStyles,src:e.url}})},u=[]}}]);