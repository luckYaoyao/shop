(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-activity-goods_combination_details-index~pages-activity-goods_seckill_details-index"],{"2db8":function(t,e,i){function n(t){for(var e={},i=t.split(","),n=i.length;n--;)e[i[n]]=!0;return e}i("ac1f"),i("1276"),t.exports={filter:null,highlight:null,onText:null,blankChar:n(" , ,\t,\r,\n,\f"),blockTags:n("address,article,aside,body,caption,center,cite,footer,header,html,nav,section,pre"),ignoreTags:n("area,base,basefont,canvas,command,frame,input,isindex,keygen,link,map,meta,param,script,source,style,svg,textarea,title,track,use,wbr,embed,iframe"),richOnlyTags:n("a,colgroup,fieldset,legend,picture,table"),selfClosingTags:n("area,base,basefont,br,col,circle,ellipse,embed,frame,hr,img,input,isindex,keygen,line,link,meta,param,path,polygon,rect,source,track,use,wbr"),trustAttrs:n("align,alt,app-id,author,autoplay,border,cellpadding,cellspacing,class,color,colspan,controls,data-src,dir,face,height,href,id,ignore,loop,media,muted,name,path,poster,rowspan,size,span,src,start,style,type,unit-id,width,xmlns"),boolAttrs:n("autoplay,controls,ignore,loop,muted"),trustTags:n("a,abbr,ad,audio,b,blockquote,br,code,col,colgroup,dd,del,dl,dt,div,em,fieldset,h1,h2,h3,h4,h5,h6,hr,i,img,ins,label,legend,li,ol,p,q,source,span,strong,sub,sup,table,tbody,td,tfoot,th,thead,tr,title,ul,video"),userAgentStyles:{address:"font-style:italic",big:"display:inline;font-size:1.2em",blockquote:"background-color:#f6f6f6;border-left:3px solid #dbdbdb;color:#6c6c6c;padding:5px 0 5px 10px",caption:"display:table-caption;text-align:center",center:"text-align:center",cite:"font-style:italic",dd:"margin-left:40px",img:"max-width:100%",mark:"background-color:yellow",picture:"max-width:100%",pre:"font-family:monospace;white-space:pre;overflow:scroll",s:"text-decoration:line-through",small:"display:inline;font-size:0.8em",u:"text-decoration:underline"}}},"3a7d":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"home acea-row row-center-wrapper",class:[t.returnShow?"p10":"p20",t.text_opacity>=1?"opacity":""],style:{marginTop:t.menuButton.top+"px",width:t.menuButton.width+"px"},attrs:{id:"home"}},[t.returnShow?i("v-uni-view",{staticClass:"iconfont icon-xiangzuo",class:t.text_opacity>=1?"opacity":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.returns.apply(void 0,arguments)}}}):t._e(),t.returnShow?i("v-uni-view",{staticClass:"line"}):t._e(),i("v-uni-view",{staticClass:"animation-box"},[i("transition",{attrs:{name:"fade"}},[t.Active?t._e():i("v-uni-view",{staticClass:"iconfont icon-gengduo4",class:t.text_opacity>=1?"opacity":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.open.apply(void 0,arguments)}}})],1),i("transition",{attrs:{name:"fade",mode:"out-in"}},[t.Active?i("v-uni-view",{staticClass:"iconfont icon-guanbi5",class:t.text_opacity>=1?"opacity":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.open.apply(void 0,arguments)}}}):t._e()],1)],1),t.Active?i("v-uni-view",{staticClass:"homeCon bg-color",class:!0===t.Active?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.open.apply(void 0,arguments)}}},t._l(t.iconList,(function(e,n){return i("v-uni-view",{key:n,staticClass:"homeCon-box",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.jumpUrl(e.path,e.jumpType)}}},[i("v-uni-text",{staticClass:"iconfont",class:e.iconName}),i("v-uni-text",{staticClass:"text"},[t._v(t._s(e.name))])],1)})),1):t._e()],1)],1)},a=[]},"3c0d":function(t,e,i){"use strict";i.r(e);var n=i("3a7d"),o=i("c18c");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("9bb4");var r,s=i("f0c5"),c=Object(s["a"])(o["default"],n["b"],n["c"],!1,null,"c2405684",null,!1,n["a"],r);e["default"]=c.exports},"3d4f":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,"@-webkit-keyframes show-data-v-f0cc9922{0%{opacity:0}100%{opacity:1}}@keyframes show-data-v-f0cc9922{0%{opacity:0}100%{opacity:1}}\n\n\n\n",""]),t.exports=e},"47d1":function(t,e,i){"use strict";i.r(e);var n=i("7127"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},"4cb8":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return a})),i.d(e,"a",(function(){return n}));var o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[t.nodes.length?t._e():t._t("default"),i("v-uni-view",{style:t.showAm+(t.selectable?";user-select:text;-webkit-user-select:text":""),attrs:{id:"top",animation:t.scaleAm},on:{touchstart:function(e){arguments[0]=e=t.$handleEvent(e),t._touchstart.apply(void 0,arguments)},touchmove:function(e){arguments[0]=e=t.$handleEvent(e),t._touchmove.apply(void 0,arguments)},click:function(e){arguments[0]=e=t.$handleEvent(e),t._tap.apply(void 0,arguments)}}},[i("div",{attrs:{id:"rtf"+t.uid}})])],2)},a=[]},"5b32":function(t,e,i){var n=i("3d4f");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("60159ab3",n,!0,{sourceMap:!1,shadowMode:!1})},"6b92":function(t,e,i){var n=i("f605");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=i("4f06").default;o("1182826d",n,!0,{sourceMap:!1,shadowMode:!1})},"702a":function(t,e,i){"use strict";i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"menuIcon",data:function(){return{Active:!1,returnShow:!0,homeTop:20,text_opacity:0,menuButton:{},iconList:[{name:"首页",iconName:"icon-shouye8",path:"/pages/index/index",jumpType:1},{name:"购物车",iconName:"icon-gouwuche7",path:"/pages/order_addcart/order_addcart",jumpType:1},{name:"搜索",iconName:"icon-sousuo6",path:"/pages/goods_search/index",jumpType:0},{name:"收藏",iconName:"icon-shoucang3",path:"/pages/users/user_goods_collection/index",jumpType:0},{name:"我的",iconName:"icon-yonghu1",path:"/pages/user/index",jumpType:1}]}},props:{showMenuIcon:{type:Boolean,default:!1},opacity:{type:Number,default:1}},watch:{showMenuIcon:function(t){this.Active=t},opacity:function(t){this.text_opacity=t}},mounted:function(){var t=getCurrentPages();this.returnShow=1!==t.length,this.$nextTick((function(){}))},methods:{open:function(){this.Active=!this.Active,this.Active&&this.$emit("open",!0)},returns:function(){uni.navigateBack()},jumpUrl:function(t,e){(1===e?uni.switchTab:uni.navigateTo)({url:t})}}};e.default=n},7127:function(module,exports,__webpack_require__){"use strict";var _interopRequireDefault=__webpack_require__("4ea4");__webpack_require__("99af"),__webpack_require__("caad"),__webpack_require__("c975"),__webpack_require__("acd8"),__webpack_require__("ac1f"),__webpack_require__("2532"),__webpack_require__("466d"),__webpack_require__("5319"),__webpack_require__("1276"),Object.defineProperty(exports,"__esModule",{value:!0}),exports.default=void 0;var _createForOfIteratorHelper2=_interopRequireDefault(__webpack_require__("b85c")),rpx=uni.getSystemInfoSync().screenWidth/750,cfg=__webpack_require__("2db8"),_default={name:"parser",data:function(){return{uid:this._uid,scaleAm:"",showAm:"",imgs:[],nodes:[]}},props:{html:null,autopause:{type:Boolean,default:!0},autosetTitle:{type:Boolean,default:!0},domain:String,gestureZoom:Boolean,lazyLoad:Boolean,selectable:Boolean,tagStyle:Object,showWithAnimation:Boolean,useAnchor:Boolean},watch:{html:function(t){this.setContent(t)}},mounted:function(){this.imgList=[],this.imgList.each=function(t){for(var e=0,i=this.length;e<i;e++)this.setItem(e,t(this[e],e,this))},this.imgList.setItem=function(t,e){if(void 0!=t&&e){if(0==e.indexOf("http")&&this.includes(e)){for(var i,n="",o=0;i=e[o];o++){if("/"==i&&"/"!=e[o-1]&&"/"!=e[o+1])break;n+=Math.random()>.5?i.toUpperCase():i}return n+=e.substr(o),this[t]=n}if(this[t]=e,e.includes("data:image")){var a=e.match(/data:image\/(\S+?);(\S+?),(.+)/);if(!a)return}}},this.html&&this.setContent(this.html)},beforeDestroy:function(){this._observer&&this._observer.disconnect(),this.imgList.each((function(t){})),clearInterval(this._timer)},methods:{_Dom2Str:function(t){var e,i="",n=(0,_createForOfIteratorHelper2.default)(t);try{for(n.s();!(e=n.n()).done;){var o=e.value;if("text"==o.type)i+=o.text;else{for(var a in i+="<"+o.name,o.attrs||{})i+=" "+a+'="'+o.attrs[a]+'"';o.children&&o.children.length?i+=">"+this._Dom2Str(o.children)+"</"+o.name+">":i+=">"}}}catch(r){n.e(r)}finally{n.f()}return i},_handleHtml:function(t,e){if("string"!=typeof t&&(t=this._Dom2Str(t.nodes||t)),t.includes("rpx")&&(t=t.replace(/[0-9.]+\s*rpx/g,(function(t){return parseFloat(t)*rpx+"px"}))),!e){var i="<style scoped>@keyframes show{0%{opacity:0}100%{opacity:1}}";for(var n in cfg.userAgentStyles)i+="".concat(n,"{").concat(cfg.userAgentStyles[n],"}");for(n in this.tagStyle)i+="".concat(n,"{").concat(this.tagStyle[n],"}");i+="</style>",t=i+t}return t},setContent:function(t,e){var i=this;if(t){var n=document.createElement("div");e?this.rtf?this.rtf.appendChild(n):this.rtf=n:(this.rtf&&this.rtf.parentNode.removeChild(this.rtf),this.rtf=n),n.innerHTML=this._handleHtml(t,e);for(var o,a=this.rtf.getElementsByTagName("style"),r=0;o=a[r++];)o.innerHTML=o.innerHTML.replace(/body/g,"#rtf"+this._uid),o.setAttribute("scoped","true");!this._observer&&this.lazyLoad&&IntersectionObserver&&(this._observer=new IntersectionObserver((function(t){for(var e,n=0;e=t[n++];)e.isIntersecting&&(e.target.src=e.target.getAttribute("data-src"),e.target.removeAttribute("data-src"),i._observer.unobserve(e.target))}),{rootMargin:"900px 0px 900px 0px"}));var s=this,c=this.rtf.getElementsByTagName("title");c.length&&this.autosetTitle&&uni.setNavigationBarTitle({title:c[0].innerText}),this.imgList.length=0;for(var u,l=this.rtf.getElementsByTagName("img"),d=0,h=0;u=l[d];d++){u.style.maxWidth="100%";var f=u.getAttribute("src");this.domain&&f&&("/"==f[0]?"/"==f[1]?u.src=(this.domain.includes("://")?this.domain.split("://")[0]:"")+":"+f:u.src=this.domain+f:f.includes("://")||(u.src=this.domain+"/"+f)),u.hasAttribute("ignore")||"A"==u.parentElement.nodeName||(u.i=h++,s.imgList.push(u.src||u.getAttribute("data-src")),u.onclick=function(){var t=!0;this.ignore=function(){return t=!1},s.$emit("imgtap",this),t&&uni.previewImage({current:this.i,urls:s.imgList})}),u.onerror=function(){s.$emit("error",{source:"img",target:this})},s.lazyLoad&&this._observer&&u.src&&0!=u.i&&(u.setAttribute("data-src",u.src),u.removeAttribute("src"),this._observer.observe(u))}var p,m=this.rtf.getElementsByTagName("a"),g=(0,_createForOfIteratorHelper2.default)(m);try{for(g.s();!(p=g.n()).done;){var v=p.value;v.onclick=function(){var t=!0,e=this.getAttribute("href");if(s.$emit("linkpress",{href:e,ignore:function(){return t=!1}}),t&&e)if("#"==e[0])s.useAnchor&&s.navigateTo({id:e.substr(1)});else{if(0==e.indexOf("http")||0==e.indexOf("//"))return!0;uni.navigateTo({url:e})}return!1}}}catch(C){g.e(C)}finally{g.f()}var _=this.rtf.getElementsByTagName("video");s.videoContexts=_;for(var b,y=0;b=_[y++];)b.style.maxWidth="100%",b.onerror=function(){s.$emit("error",{source:"video",target:this})},b.onplay=function(){if(s.autopause)for(var t,e=0;t=s.videoContexts[e++];)t!=this&&t.pause()};var x,w,k=this.rtf.getElementsByTagName("audios"),T=(0,_createForOfIteratorHelper2.default)(k);try{for(T.s();!(x=T.n()).done;){var A=x.value;A.onerror=function(){s.$emit("error",{source:"audio",target:this})}}}catch(C){T.e(C)}finally{T.f()}this.document=this.rtf,e||document.getElementById("rtf"+this._uid).appendChild(this.rtf),this.$nextTick((function(){i.nodes=[1],i.$emit("load")})),setTimeout((function(){return i.showAm=""}),500),clearInterval(this._timer),this._timer=setInterval((function(){var t=[i.rtf.getBoundingClientRect()];i.width=t[0].width,t[0].height==w&&(i.$emit("ready",t[0]),clearInterval(i._timer)),w=t[0].height}),350),this.showWithAnimation&&!e&&(this.showAm="animation:show .5s")}else this.rtf&&!e&&this.rtf.parentNode.removeChild(this.rtf)},getText:function(){arguments.length>0&&void 0!==arguments[0]||this.nodes;return this.rtf.innerText},navigateTo:function(t){if(!this.useAnchor)return t.fail&&t.fail({errMsg:"Anchor is disabled"});if(!t.id)return window.scrollTo(0,this.rtf.offsetTop),t.success&&t.success({errMsg:"pageScrollTo:ok"});var e=document.getElementById(t.id);if(!e)return t.fail&&t.fail({errMsg:"Label not found"});t.scrollTop=this.rtf.offsetTop+e.offsetTop,uni.pageScrollTo(t)},getVideoContext:function(t){if(!t)return this.videoContexts;for(var e=this.videoContexts.length;e--;)if(this.videoContexts[e].id==t)return this.videoContexts[e]},preLoad:function preLoad(html,num){html.constructor==Array&&(html=this._Dom2Str(html));var script="var contain=document.createElement('div');contain.innerHTML='"+html.replace(/'/g,"\\'")+"';for(var imgs=contain.querySelectorAll('img'),i=imgs.length-1;i>="+num+";i--)imgs[i].removeAttribute('src');";eval(script)},_tap:function(t){if(this.gestureZoom&&t.timeStamp-this._lastT<300){var e=t.touches[0].pageY-t.currentTarget.offsetTop;if(this._zoom)this._scaleAm.translateX(0).scale(1).step(),uni.pageScrollTo({scrollTop:(e+this._initY)/2-t.touches[0].clientY,duration:400});else{var i=t.touches[0].pageX-t.currentTarget.offsetLeft;this._initY=e,this._scaleAm=uni.createAnimation({transformOrigin:"".concat(i,"px ").concat(this._initY,"px 0"),timingFunction:"ease-in-out"}),this._scaleAm.scale(2).step(),this._tMax=i/2,this._tMin=(i-this.width)/2,this._tX=0}this._zoom=!this._zoom,this.scaleAm=this._scaleAm.export()}this._lastT=t.timeStamp},_touchstart:function(t){1==t.touches.length&&(this._initX=this._lastX=t.touches[0].pageX)},_touchmove:function(t){var e=t.touches[0].pageX-this._lastX;if(this._zoom&&1==t.touches.length&&Math.abs(e)>20){if(this._lastX=t.touches[0].pageX,this._tX<=this._tMin&&e<0||this._tX>=this._tMax&&e>0)return;this._tX+=e*Math.abs(this._lastX-this._initX)*.05,this._tX<this._tMin&&(this._tX=this._tMin),this._tX>this._tMax&&(this._tX=this._tMax),this._scaleAm.translateX(this._tX).step(),this.scaleAm=this._scaleAm.export()}}}};exports.default=_default},"8c69":function(t,e,i){"use strict";var n=i("5b32"),o=i.n(n);o.a},"96d0":function(t,e,i){"use strict";i("c975"),i("d3b7"),i("ac1f"),i("25f0"),i("5319"),Object.defineProperty(e,"__esModule",{value:!0}),e.sharePoster=void 0;var n=i("6fe3"),o=(i("5019"),uni.getSystemInfoSync().statusBarHeight+"px"),a={data:function(){return{codeShow:!1,cid:"1",codeVal:"",size:200,unit:"upx",background:"#FFF",foreground:"#000",pdground:"#000",codeIcon:"",iconsize:40,lv:3,onval:!0,loadMake:!0,base64Show:0,shareQrcode:0,followCode:"",selectSku:{},currentPage:!1,sysHeight:o,isShow:0,storeImageBase64:""}},methods:{qrR:function(t){this.$wechat.isWeixin()&&"1"==this.shareQrcode||(this.PromotionCode=t,this.followCode="")},getImageBase64:function(){var t=this;(0,n.imageBase64)(t.storeImage,"").then((function(e){t.storeImageBase64=e.data.image})).catch((function(){}))},goPoster:function(){var t,e=this;if(e.posters=!1,e.$set(e,"canvasStatus",!0),t=[e.posterbackgd,e.storeImageBase64,e.PromotionCode],!e.storeImageBase64)return e.$util.Tips({title:"正在下载海报,请稍后再试"});uni.getImageInfo({src:e.PromotionCode,fail:function(t){return e.$util.Tips({title:t})},success:function(){""==t[2]?e.downloadFilePromotionCode((function(i){if(t[2]=i,""==t[2])return e.$util.Tips({title:"海报二维码生成失败！"});e.$util.PosterCanvas(t,e.storeInfo.store_name,e.storeInfo.price,e.storeInfo.ot_price,(function(t){e.$set(e,"posterImage",t),e.$set(e,"posterImageStatus",!0),e.$set(e,"canvasStatus",!1),e.$set(e,"actionSheetHidden",!e.actionSheetHidden)}))})):e.$nextTick((function(i){e.$util.PosterCanvas(t,e.storeInfo.store_name,e.storeInfo.price,e.storeInfo.ot_price,(function(t){e.$set(e,"posterImage",t),e.$set(e,"posterImageStatus",!0),e.$set(e,"canvasStatus",!1),e.$set(e,"actionSheetHidden",!e.actionSheetHidden)}))}))}})},setDomain:function(t){return t=t?t.toString():"",t.indexOf("https://")>-1?t:t.replace("http://","https://")},downloadFilestoreImage:function(){var t=this;uni.downloadFile({url:t.setDomain(t.storeInfo.image),success:function(e){t.storeImage=e.tempFilePath,t.storeImageBase64=e.tempFilePath},fail:function(){return t.$util.Tips({title:""})}})},downloadFilePromotionCode:function(t){}}};e.sharePoster=a},"9bb4":function(t,e,i){"use strict";var n=i("6b92"),o=i.n(n);o.a},b85c:function(t,e,i){"use strict";i("a4d3"),i("e01a"),i("d28b"),i("d3b7"),i("3ca3"),i("ddb0"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=a;var n=o(i("06c5"));function o(t){return t&&t.__esModule?t:{default:t}}function a(t,e){var i;if("undefined"===typeof Symbol||null==t[Symbol.iterator]){if(Array.isArray(t)||(i=(0,n.default)(t))||e&&t&&"number"===typeof t.length){i&&(t=i);var o=0,a=function(){};return{s:a,n:function(){return o>=t.length?{done:!0}:{done:!1,value:t[o++]}},e:function(t){throw t},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var r,s=!0,c=!1;return{s:function(){i=t[Symbol.iterator]()},n:function(){var t=i.next();return s=t.done,t},e:function(t){c=!0,r=t},f:function(){try{s||null==i["return"]||i["return"]()}finally{if(c)throw r}}}}},c18c:function(t,e,i){"use strict";i.r(e);var n=i("702a"),o=i.n(n);for(var a in n)"default"!==a&&function(t){i.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},dcee:function(t,e,i){"use strict";i.r(e);var n=i("4cb8"),o=i("47d1");for(var a in o)"default"!==a&&function(t){i.d(e,t,(function(){return o[t]}))}(a);i("8c69");var r,s=i("f0c5"),c=Object(s["a"])(o["default"],n["b"],n["c"],!1,null,"f0cc9922",null,!1,n["a"],r);e["default"]=c.exports},f605:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.home[data-v-c2405684]{top:%?20?%!important}.home.opacity[data-v-c2405684]{background:#fff;border:1px solid #d7d7d7}.home[data-v-c2405684]{color:#333;position:fixed;height:%?62?%;z-index:99;left:%?33?%;border:1px solid #d7d7d7;background:hsla(0,0%,100%,.8);border-radius:%?30?%;display:flex;align-items:center;justify-content:space-around}.home .opacity[data-v-c2405684]{color:#000}.home .icon-gengduo4[data-v-c2405684],\n.home .icon-guanbi5[data-v-c2405684]{position:absolute;width:%?40?%;font-weight:700}.home .icon-xiangzuo[data-v-c2405684]{font-weight:700;line-height:%?28?%}.home .icon-gengduo4[data-v-c2405684]{font-size:%?28?%;text-align:center}.home .animation-box[data-v-c2405684]{position:relative;width:%?40?%;height:%?28?%}.icon-guanbi5[data-v-c2405684]{font-size:%?28?%;text-align:center}.home .homeCon[data-v-c2405684]{display:flex;flex-direction:column;font-size:%?26?%;padding:%?4?%;opacity:0;border:1px solid #f2f2f2}.home .homeCon[data-v-c2405684]::before{content:"";width:0;height:0;border-left:%?15?% solid transparent;border-right:%?15?% solid transparent;border-bottom:%?17?% solid #fff;position:absolute;top:%?-15?%;right:%?66?%;border-bottom-color:#f2f2f2}.home .homeCon[data-v-c2405684]::after{content:"";width:0;height:0;border-left:%?15?% solid transparent;border-right:%?15?% solid transparent;border-bottom:%?17?% solid #fff;position:absolute;top:%?-13?%;right:%?66?%}.home .homeCon .homeCon-box[data-v-c2405684]{display:flex;align-items:center;flex-wrap:nowrap;width:100%;padding:%?15?% %?20?%}.home .homeCon .homeCon-box .text[data-v-c2405684]{display:flex;flex-wrap:nowrap}.home .homeCon .iconfont[data-v-c2405684]{display:flex;align-items:center;padding:%?5?% %?3?%;margin-right:%?5?%}.home .homeCon .homeCon-box[data-v-c2405684]:nth-child(even){border-top:1px solid #f2f2f2;border-bottom:1px solid #f2f2f2}.home .homeCon.active[data-v-c2405684]{position:absolute;opacity:1;top:%?75?%;-webkit-animation:bounceInLeft .5s cubic-bezier(.215,.31,.655,1);animation:bounceInLeft .5s cubic-bezier(.215,.31,.655,1);width:-webkit-max-content;width:max-content;display:flex;justify-content:center;align-items:center;border-radius:%?10?%;color:#333;background:#fff!important;box-shadow:%?0?% %?3?% %?10?% %?2?% rgba(0,0,0,.03)}.home .line[data-v-c2405684]{width:%?2?%;height:%?26?%;margin:0 %?18?% 0 %?16?%;background:#fff}.home .line.opacity[data-v-c2405684]{color:#050505;background-color:#050505}.home .icon-xiangzuo[data-v-c2405684]{font-size:%?28?%}.fade-enter-active[data-v-c2405684],\n.fade-leave-active[data-v-c2405684]{transition:opacity .3s}.fade-enter[data-v-c2405684],\n.fade-leave-to[data-v-c2405684]{opacity:0}.p10[data-v-c2405684]{padding:0 %?25?%}.p20[data-v-c2405684]{padding:0 %?40?%}',""]),t.exports=e}}]);