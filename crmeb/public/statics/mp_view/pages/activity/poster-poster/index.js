(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/activity/poster-poster/index"],{"70e3":function(t,e,n){"use strict";var i=n("bf8b"),o=n.n(i);o.a},"8afb":function(t,e,n){"use strict";(function(t){n("6e38");i(n("66fd"));var e=i(n("a2f6"));function i(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},a154:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return o})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return i}));var o=function(){var t=this,e=t.$createElement;t._self._c},a=[]},a2f6:function(t,e,n){"use strict";n.r(e);var i=n("a154"),o=n("fa1d");for(var a in o)"default"!==a&&function(t){n.d(e,t,(function(){return o[t]}))}(a);n("70e3");var r,s=n("f0c5"),u=Object(s["a"])(o["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],r);e["default"]=u.exports},bf8b:function(t,e,n){},eb8a:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=s(n("a34a")),o=n("4f72"),a=n("3474"),r=s(n("c83f"));function s(t){return t&&t.__esModule?t:{default:t}}function u(t,e,n,i,o,a,r){try{var s=t[a](r),u=s.value}catch(c){return void n(c)}s.done?e(u):Promise.resolve(u).then(i,o)}function c(t){return function(){var e=this,n=arguments;return new Promise((function(i,o){var a=t.apply(e,n);function r(t){u(a,i,o,r,s,"next",t)}function s(t){u(a,i,o,r,s,"throw",t)}r(void 0)}))}}var l=function(){Promise.all([n.e("common/vendor"),n.e("components/zb-code/zb-code")]).then(function(){return resolve(n("0c6f"))}.bind(null,n)).catch(n.oe)},f={components:{zbCode:l},mixins:[r.default],data:function(){return{canvasStatus:!0,posterImage:"",parameter:{navbar:"1",return:"1",title:"拼团海报",color:!0,class:"0"},type:0,id:0,bargain:0,image:"",from:"",uid:"",codeShow:!1,cid:"1",ifShow:!0,val:"",size:200,unit:"upx",background:"#FFF",foreground:"#000",pdground:"#000",icon:"",iconsize:40,lv:3,onval:!0,loadMake:!0,src:"",codeSrc:"",wd:0,hg:0,posterBag:"../static/posterBag.png",mpUrl:""}},onLoad:function(e){this.from="routine";if(!e.hasOwnProperty("type")||!e.hasOwnProperty("id"))return app.Tips({title:"参数错误",icon:"none"},{tab:3,url:1});this.type=e.type,this.id=e.id,1==e.type?(this.bargain=e.bargain,t.setNavigationBarTitle({title:"砍价海报"})):t.setNavigationBarTitle({title:"拼团海报"})},onReady:function(){var e=this;setTimeout((function(t){e.getPosterInfo()}),200),this.$nextTick((function(){var e=this,n=t.createSelectorQuery().select(".pictrue");n.fields({size:!0},(function(t){e.wd=t.width,e.hg=t.height})).exec()})),this.routineCode()},methods:{getPosterInfo:function(){var e=this;return c(i.default.mark((function n(){var r,s,u,c,l;return i.default.wrap((function(n){while(1)switch(n.prev=n.next){case 0:return r=e,"",{id:r.id,from:r.from},n.next=4,(0,a.getUserInfo)();case 4:if(s=n.sent,e.uid=s.data.uid,t.showLoading({title:"海报生成中",mask:!0}),1!=r.type){n.next=12;break}return n.next=10,(0,o.getBargainPosterData)(r.id).then((function(t){u=t.data})).catch((function(t){r.$util.Tips({title:"海报图片获取失败"})}));case 10:n.next=14;break;case 12:return n.next=14,(0,o.getCombinationPosterData)(r.id).then((function(t){u=t.data})).catch((function(t){r.$util.Tips({title:"海报图片获取失败"})}));case 14:if(!u.url){n.next=20;break}return n.next=17,e.downloadFilestoreImage(u.url);case 17:n.t0=n.sent,n.next=23;break;case 20:return n.next=22,e.downloadFilestoreImage(e.mpUrl);case 22:n.t0=n.sent;case 23:return l=n.t0,n.t1=e.posterBag,n.next=27,e.downloadFilestoreImage(u.image);case 27:n.t2=n.sent,n.t3=l,c=[n.t1,n.t2,n.t3],e.$nextTick((function(t){r.$util.bargainPosterCanvas(c,u.title,u.label,u.msg,u.price,e.wd,e.hg,(function(t){e.posterImage=t}))}));case 31:case"end":return n.stop()}}),n)})))()},routineCode:function(){var t=this;return c(i.default.mark((function e(){var n;return i.default.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,(0,a.routineCode)();case 2:n=e.sent,t.mpUrl=n.data.url;case 4:case"end":return e.stop()}}),e)})))()},downloadFilestoreImage:function(e){var n=this;return e=this.setDomain(e),new Promise((function(i,o){var a=n;t.downloadFile({url:e,success:function(t){i(t.tempFilePath)},fail:function(){return a.$util.Tips({title:""})}})}))},setDomain:function(t){return t=t?t.toString():"",t.indexOf("https://")>-1?t:t.replace("http://","https://")},imgToBase:function(t){return c(i.default.mark((function e(){var n;return i.default.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,(0,a.imgToBase)({image:t});case 2:return n=e.sent,e.abrupt("return",n.data.image);case 4:case"end":return e.stop()}}),e)})))()},downloadImg:function(){},savePosterPathMp:function(e){var n=this;t.getSetting({success:function(i){i.authSetting["scope.writePhotosAlbum"]?t.saveImageToPhotosAlbum({filePath:e,success:function(t){n.$util.Tips({title:"保存成功",icon:"success"})},fail:function(t){n.$util.Tips({title:"保存失败"})}}):t.authorize({scope:"scope.writePhotosAlbum",success:function(){t.saveImageToPhotosAlbum({filePath:e,success:function(t){n.$util.Tips({title:"保存成功",icon:"success"})},fail:function(t){n.$util.Tips({title:"保存失败"})}})},fail:function(t){n.$util.Tips({title:"请先开启文件访问权限"})}})}})},qrR:function(t){this.codeSrc=t},savePosterPath:function(){var e=this;t.getSetting({success:function(n){n.authSetting["scope.writePhotosAlbum"]?t.saveImageToPhotosAlbum({filePath:e.posterImage,success:function(t){e.posterImageClose(),e.$util.Tips({title:"保存成功",icon:"success"})},fail:function(t){e.$util.Tips({title:"保存失败"})}}):t.authorize({scope:"scope.writePhotosAlbum",success:function(){t.saveImageToPhotosAlbum({filePath:e.posterImage,success:function(t){e.posterImageClose(),e.$util.Tips({title:"保存成功",icon:"success"})},fail:function(t){e.$util.Tips({title:"保存失败"})}})}})}})}}};e.default=f}).call(this,n("543d")["default"])},fa1d:function(t,e,n){"use strict";n.r(e);var i=n("eb8a"),o=n.n(i);for(var a in i)"default"!==a&&function(t){n.d(e,t,(function(){return i[t]}))}(a);e["default"]=o.a}},[["8afb","common/runtime","common/vendor"]]]);