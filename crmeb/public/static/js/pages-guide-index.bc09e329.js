(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-guide-index"],{"136b":function(e,t,i){"use strict";i.r(t);var n=i("dec7"),a=i("cdfe");for(var c in a)"default"!==c&&function(e){i.d(t,e,(function(){return a[e]}))}(c);i("9070");var u,r=i("f0c5"),d=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"8c4093b2",null,!1,n["a"],u);t["default"]=d.exports},1800:function(e,t,i){var n=i("24fb");t=n(!1),t.push([e.i,"uni-page-body[data-v-8c4093b2],\n.main[data-v-8c4093b2]{width:100%;height:100%}",""]),e.exports=t},"24ca":function(e,t,i){"use strict";var n=i("5cb8"),a=i.n(n);a.a},"2f70":function(e,t,i){"use strict";(function(e){var n=i("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a=n(i("7382")),c=i("7fe6"),u={components:{guide:a.default},data:function(){return{guidePages:!1,advList:[]}},onLoad:function(){this.loadExecution()},methods:{loadExecution:function(){var e=this,t=uni.getStorageSync("guideDate")||"",i=(new Date).toLocaleDateString();t!==i?(0,c.getOpenAdv)().then((function(t){t.data.length?(e.advList=t.data,uni.setStorageSync("guideDate",(new Date).toLocaleDateString()),e.guidePages=!0):uni.switchTab({url:"/pages/index/index"})})).catch((function(e){})):uni.switchTab({url:"/pages/index/index"})}}};t.default=u}).call(this,i("5a52")["default"])},3413:function(e,t,i){"use strict";var n;i.d(t,"b",(function(){return a})),i.d(t,"c",(function(){return c})),i.d(t,"a",(function(){return n}));var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("v-uni-view",{staticClass:"content"},[i("v-uni-swiper",{staticClass:"swiper",attrs:{autoplay:e.autoplay,duration:e.duration}},e._l(e.advList,(function(e,t){return i("v-uni-swiper-item",{key:t},[i("v-uni-view",{staticClass:"swiper-item"},[i("v-uni-view",{staticClass:"swiper-item-img"},[i("v-uni-image",{attrs:{src:e,mode:"aspectFit"}})],1)],1)],1)})),1),i("v-uni-view",{staticClass:"jump-over",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.launchFlag()}}},[e._v("跳过 "+e._s(e.time))])],1)},c=[]},"5cb8":function(e,t,i){var n=i("de85");"string"===typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);var a=i("4f06").default;a("184369c9",n,!0,{sourceMap:!1,shadowMode:!1})},7382:function(e,t,i){"use strict";i.r(t);var n=i("3413"),a=i("9e06");for(var c in a)"default"!==c&&function(e){i.d(t,e,(function(){return a[e]}))}(c);i("24ca");var u,r=i("f0c5"),d=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"92ec6edc",null,!1,n["a"],u);t["default"]=d.exports},9070:function(e,t,i){"use strict";var n=i("fc4a"),a=i.n(n);a.a},"9e06":function(e,t,i){"use strict";i.r(t);var n=i("a7f7"),a=i.n(n);for(var c in n)"default"!==c&&function(e){i.d(t,e,(function(){return n[e]}))}(c);t["default"]=a.a},a7f7:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var n={data:function(){return{autoplay:!1,duration:500,jumpover:"跳过",experience:"立即体验",time:5,timecount:void 0}},props:{advList:{type:Array,default:function(){}}},mounted:function(){this.timer()},methods:{timer:function(){var e=this,t=5;this.timecount=setInterval((function(){t--,e.time=t,t<=0&&(clearInterval(e.timecount),e.launchFlag())}),1e3)},launchFlag:function(){clearInterval(this.timecount),uni.switchTab({url:"/pages/index/index"})}}};t.default=n},cdfe:function(e,t,i){"use strict";i.r(t);var n=i("2f70"),a=i.n(n);for(var c in n)"default"!==c&&function(e){i.d(t,e,(function(){return n[e]}))}(c);t["default"]=a.a},de85:function(e,t,i){var n=i("24fb");t=n(!1),t.push([e.i,"uni-page-body[data-v-92ec6edc],\n.content[data-v-92ec6edc]{width:100%;height:100%;background-size:100% auto;padding:0;z-index:999}.swiper[data-v-92ec6edc]{width:100%;height:100vh;background:#fff}.swiper-item[data-v-92ec6edc]{width:100%;height:100%;text-align:center;position:relative;display:flex;\n\t/* justify-content: center; */align-items:flex-end;flex-direction:column-reverse}.swiper-item-img[data-v-92ec6edc]{width:100%;height:100vh;margin:0 auto}.swiper-item-img uni-image[data-v-92ec6edc]{width:100%;height:100%}.uniapp-img[data-v-92ec6edc]{height:50%;background:#fff;display:flex;justify-content:center;align-items:center;overflow:hidden}.uniapp-img uni-image[data-v-92ec6edc]{width:40%}.jump-over[data-v-92ec6edc],\n.experience[data-v-92ec6edc]{position:absolute;height:%?45?%;line-height:%?45?%;padding:0 %?15?%;border-radius:%?30?%;font-size:%?24?%;color:#b09e9a;border:1px solid #b09e9a;z-index:999}.jump-over[data-v-92ec6edc]{right:%?30?%;top:%?80?%}.experience[data-v-92ec6edc]{right:50%;margin-right:%?-105?%;bottom:%?80?%}body.?%PAGE?%[data-v-92ec6edc]{background-size:100% auto}",""]),e.exports=t},dec7:function(e,t,i){"use strict";var n;i.d(t,"b",(function(){return a})),i.d(t,"c",(function(){return c})),i.d(t,"a",(function(){return n}));var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("v-uni-view",{staticClass:"main"},[e.guidePages?i("guide",{attrs:{advList:e.advList}}):e._e()],1)},c=[]},fc4a:function(e,t,i){var n=i("1800");"string"===typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);var a=i("4f06").default;a("7e7d9955",n,!0,{sourceMap:!1,shadowMode:!1})}}]);