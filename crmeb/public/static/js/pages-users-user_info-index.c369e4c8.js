(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-users-user_info-index"],{"0417":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return r})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.popup_show?n("v-uni-view",{staticClass:"wrap"},[n("v-uni-view",{staticClass:"popup-bg",style:t.getHeight},[n("v-uni-view",{staticClass:"popup-content",class:{"popup-content-show":t.popup_show}},[n("v-uni-view",{staticClass:"update-wrap"},[n("v-uni-image",{staticClass:"top-img",attrs:{src:i("1bb9")}}),n("v-uni-view",{staticClass:"content"},[n("v-uni-text",{staticClass:"title"},[t._v("发现新版本"+t._s(t.update_info.version))]),n("v-uni-view",{staticClass:"title-sub",domProps:{innerHTML:t._s(t.update_info.info)}}),t.downstatus<1?n("v-uni-button",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.nowUpdate()}}},[t._v("立即升级")]):n("v-uni-view",{staticClass:"sche-wrap"},[n("v-uni-view",{staticClass:"sche-bg"},[n("v-uni-view",{staticClass:"sche-bg-jindu",style:t.lengthWidth})],1),n("v-uni-text",{staticClass:"down-text"},[t._v("下载进度:"+t._s((t.downSize/1024/1024).toFixed(2))+"M/"+t._s((t.fileSize/1024/1024).toFixed(2))+"M")])],1)],1)],1),n("v-uni-image",{staticClass:"close-ioc",attrs:{src:i("6fe8")},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeUpdate()}}})],1)],1)],1):t._e()},r=[]},"073c":function(t,e,i){"use strict";i.r(e);var n=i("3079"),a=i("a348");for(var r in a)"default"!==r&&function(t){i.d(e,t,(function(){return a[t]}))}(r);i("9751");var s,o=i("f0c5"),u=Object(o["a"])(a["default"],n["b"],n["c"],!1,null,"2259c954",null,!1,n["a"],s);e["default"]=u.exports},"1bb9":function(t,e,i){t.exports=i.p+"static/img/img.beebfe54.png"},"1de5":function(t,e,i){"use strict";t.exports=function(t,e){return e||(e={}),t=t&&t.__esModule?t.default:t,"string"!==typeof t?t:(/^['"].*['"]$/.test(t)&&(t=t.slice(1,-1)),e.hash&&(t+=e.hash),/["'() \t\n]/.test(t)||e.needQuotes?'"'.concat(t.replace(/"/g,'\\"').replace(/\n/g,"\\n"),'"'):t)}},3079:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return r})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-form",{on:{submit:function(e){arguments[0]=e=t.$handleEvent(e),t.formSubmit.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"personal-data",style:t.colorStyle},[i("v-uni-view",{staticClass:"list"},[i("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[i("v-uni-view",[t._v("头像")]),i("v-uni-view",{staticClass:"avatar-box",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.uploadpic.apply(void 0,arguments)}}},[i("v-uni-image",{attrs:{src:t.userInfo.avatar}})],1)],1),i("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[i("v-uni-view",[t._v("昵称")]),i("v-uni-view",{staticClass:"input"},[i("v-uni-input",{attrs:{type:"text",name:"nickname",value:t.userInfo.nickname}})],1)],1),i("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[i("v-uni-view",[t._v("手机号码")]),t.userInfo.phone?i("v-uni-view",{staticClass:"input acea-row row-between-wrapper"},[i("v-uni-input",{staticClass:"id",attrs:{type:"text",disabled:"true",name:"phone",value:t.userInfo.phone}}),i("v-uni-text",{staticClass:"iconfont icon-suozi"})],1):i("v-uni-navigator",{staticClass:"input",attrs:{url:"/pages/users/user_phone/index","hover-class":"none"}},[t._v("点击绑定手机号"),i("v-uni-text",{staticClass:"iconfont icon-xiangyou"})],1)],1),i("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[i("v-uni-view",[t._v("ID号")]),i("v-uni-view",{staticClass:"input acea-row row-between-wrapper"},[i("v-uni-input",{staticClass:"id",attrs:{type:"text",value:t.userInfo.uid,disabled:"true"}}),i("v-uni-text",{staticClass:"iconfont icon-suozi"})],1)],1),t.userInfo.phone&&!this.$wechat.isWeixin()?i("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[i("v-uni-view",[t._v("密码")]),i("v-uni-navigator",{staticClass:"input",attrs:{url:"/pages/users/user_pwd_edit/index","hover-class":"none"}},[t._v("点击修改密码"),i("v-uni-text",{staticClass:"iconfont icon-xiangyou"})],1)],1):t._e(),t.userInfo.phone?i("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[i("v-uni-view",[t._v("更换手机号码")]),i("v-uni-navigator",{staticClass:"input",attrs:{url:"/pages/users/user_phone/index?type=1","hover-class":"none"}},[t._v("点击更换手机号码"),i("v-uni-text",{staticClass:"iconfont icon-xiangyou"})],1)],1):t._e()],1),i("v-uni-button",{staticClass:"modifyBnt bg-color",attrs:{formType:"submit"}},[t._v("保存修改")]),i("v-uni-view",{staticClass:"logOut cartcolor acea-row row-center-wrapper",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.outLogin.apply(void 0,arguments)}}},[t._v("退出登录")])],1)],1),t.canvasStatus?i("v-uni-canvas",{style:{width:t.canvasWidth+"px",height:t.canvasHeight+"px",position:"absolute",left:"-100000px",top:"-100000px"},attrs:{"canvas-id":"canvas"}}):t._e()],1)},r=[]},"3aab":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.cartcolor[data-v-2259c954]{color:var(--view-theme);border:1px solid var(--view-theme)}.personal-data .wrapper[data-v-2259c954]{margin:%?10?% 0;background-color:#fff;padding:%?36?% %?30?% %?13?% %?30?%}.personal-data .wrapper .title[data-v-2259c954]{margin-bottom:%?30?%;font-size:%?32?%;color:#282828}.personal-data .wrapper .wrapList .item[data-v-2259c954]{width:%?690?%;height:%?160?%;background-color:#f8f8f8;border-radius:%?20?%;margin-bottom:%?22?%;padding:0 %?30?%;position:relative;border:%?2?% solid #f8f8f8;box-sizing:border-box}.personal-data .wrapper .wrapList .item.on[data-v-2259c954]{border-color:#e93323;border-radius:%?20?%;background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAArIAAACgCAYAAADw+I85AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQ1IDc5LjE2MzQ5OSwgMjAxOC8wOC8xMy0xNjo0MDoyMiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTkgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6M0QzNkY3NzlCNzJCMTFFOTgyNEU4QzhGQTRFRUY2REQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6M0QzNkY3N0FCNzJCMTFFOTgyNEU4QzhGQTRFRUY2REQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDozRDM2Rjc3N0I3MkIxMUU5ODI0RThDOEZBNEVFRjZERCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDozRDM2Rjc3OEI3MkIxMUU5ODI0RThDOEZBNEVFRjZERCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pn3rJMAAAArUSURBVHja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");background-size:100% 100%;background-color:#fff9f9;background-repeat:no-repeat}.personal-data .wrapper .wrapList .item .picTxt[data-v-2259c954]{width:%?445?%}.personal-data .wrapper .wrapList .item .picTxt .pictrue[data-v-2259c954]{width:%?96?%;height:%?96?%;position:relative}.personal-data .wrapper .wrapList .item .picTxt .pictrue uni-image[data-v-2259c954]{width:100%;height:100%;border-radius:50%}.personal-data .wrapper .wrapList .item .picTxt .pictrue .alter[data-v-2259c954]{width:%?30?%;height:%?30?%;border-radius:50%;position:absolute;bottom:0;right:0}.personal-data .wrapper .wrapList .item .picTxt .text[data-v-2259c954]{width:%?325?%}.personal-data .wrapper .wrapList .item .picTxt .text .name[data-v-2259c954]{width:100%;font-size:%?30?%;color:#282828}.personal-data .wrapper .wrapList .item .picTxt .text .phone[data-v-2259c954]{font-size:%?24?%;color:#999;margin-top:%?10?%}.personal-data .wrapper .wrapList .item .bnt[data-v-2259c954]{font-size:%?24?%;background-color:#fff;border-radius:%?27?%;width:%?140?%;height:%?54?%;border:%?2?% solid #e93323}.personal-data .wrapper .wrapList .item .currentBnt[data-v-2259c954]{position:absolute;right:0;top:0;font-size:%?26?%;background-color:rgba(233,51,35,.1);width:%?140?%;height:%?48?%;border-radius:0 %?20?% 0 %?20?%}.personal-data .list[data-v-2259c954]{margin-top:%?15?%;background-color:#fff}.personal-data .list .item[data-v-2259c954]{padding:%?30?% %?30?% %?30?% 0;border-bottom:%?1?% solid #f2f2f2;margin-left:%?30?%;font-size:%?32?%;color:#282828}.personal-data .list .item .phone[data-v-2259c954]{width:%?160?%;height:%?56?%;font-size:%?24?%;color:#fff;line-height:%?56?%;border-radius:%?32?%}.personal-data .list .item .pictrue[data-v-2259c954]{width:%?88?%;height:%?88?%}.personal-data .list .item .pictrue uni-image[data-v-2259c954]{width:100%;height:100%;border-radius:50%}.personal-data .list .item .input[data-v-2259c954]{width:%?415?%;text-align:right;color:#868686}.personal-data .list .item .input .id[data-v-2259c954]{width:%?365?%}.personal-data .list .item .input .iconfont[data-v-2259c954]{font-size:%?35?%}.personal-data .modifyBnt[data-v-2259c954]{font-size:%?32?%;color:#fff;width:%?690?%;height:%?90?%;border-radius:%?50?%;text-align:center;line-height:%?90?%;margin:%?76?% auto 0 auto}.personal-data .logOut[data-v-2259c954]{font-size:%?32?%;text-align:center;width:%?690?%;height:%?90?%;border-radius:%?45?%;margin:%?30?% auto 0 auto}.avatar-box[data-v-2259c954]{width:%?96?%;height:%?96?%}.avatar-box uni-image[data-v-2259c954]{width:100%;height:100%;border-radius:50%}',""]),t.exports=e},"3fed":function(t,e,i){"use strict";(function(t){i("c975"),i("e25e"),i("ac1f"),i("1276"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n,a=i("1346"),r={name:"appUpdate",props:{tabbar:{type:Boolean,default:!1},getVer:{type:Boolean,default:!1}},data:function(){return{popup_show:!1,platform:"",version:"1.0.0",need_update:!1,downing:!1,downstatus:0,update_info:{os:"",version:"",info:""},fileSize:0,downSize:0,viewObj:null}},created:function(){n=this,this.getVer||this.update()},computed:{lengthWidth:function(){var t=this.downSize/this.fileSize*100;return t=t?t.toFixed(2):0,{width:t+"%"}},getHeight:function(){var t=0;return this.tabbar&&(t=50),{bottom:t+"px",height:"auto"}}},methods:{update:function(){},getUpdateInfo:function(){var t=this;(0,a.getUpdateInfo)("ios"===this.platform?2:1).then((function(e){e.data;var i=uni.getStorageSync("app_update_time")||"",a=(new Date).toLocaleDateString();if(i===a||t.getVer){if(i!==a&&t.getVer){if(!e.data.is_force)return}else if(i==a&&!t.getVer&&!e.data.is_force)return}else uni.setStorageSync("app_update_time",(new Date).toLocaleDateString());var r=e.data;n.update_info=r,n.update_info.platform&&n.checkUpdate()})).catch((function(t){n.popup_show=!1}))},checkUpdate:function(){n.need_update=n.compareVersion(n.version,n.update_info.version),n.need_update?(n.popup_show=!0,n.tabbar&&(n.viewObj=new plus.nativeObj.View("viewObj",{bottom:"0px",left:"0px",height:"50px",width:"100%",backgroundColor:"rgba(0,0,0,.6)"}),n.viewObj.show())):this.$emit("isNew")},closeUpdate:function(){n.update_info.is_force?"android"==this.platform?plus.runtime.quit():plus.ios.import("UIApplication").sharedApplication().performSelector("exit"):(n.popup_show=!1,n.viewObj&&n.viewObj.hide())},nowUpdate:function(){if(n.downing)return!1;n.downing=!0,/\.apk$/.test(n.update_info.url)||/\.wgt$/.test(n.update_info.url)?n.download_wgt():plus.runtime.openURL(n.update_info.url,(function(){plus.nativeUI.toast("打开错误")}))},download_wgt:function(){plus.nativeUI.showWaiting("下载更新文件...");var t={method:"get"},e=plus.downloader.createDownload(n.update_info.url,t,(function(t,e){}));e.addEventListener("statechanged",(function(t,e){if(null===e);else if(200==e)switch(n.downstatus=t.state,t.state){case 3:n.downSize=t.downloadedSize,t.totalSize&&(n.fileSize=t.totalSize);break;case 4:n.installWgt(t.filename);break}else plus.nativeUI.closeWaiting(),plus.nativeUI.toast("下载出错"),n.downing=!1,n.downstatus=0})),e.start()},installWgt:function(t){plus.nativeUI.showWaiting("安装更新文件..."),plus.runtime.install(t,{},(function(){plus.nativeUI.closeWaiting(),plus.nativeUI.alert("应用资源下载完成！",(function(){plus.runtime.restart()}))}),(function(t){plus.nativeUI.closeWaiting(),plus.nativeUI.alert("安装更新文件失败["+t.code+"]："+t.message)}))},compareVersion:function(t,e){if(!t||!e||""==t||""==e)return!1;for(var i=t.split(".",4),n=e.split(".",4),a=0;a<i.length&&a<n.length;a++){var r=i[a],s=parseInt(r),o=n[a],u=parseInt(o);if(u>s||o.length>r.length)return!0;if(u<s)return!1}return n.length>i.length&&0==e.indexOf(t)}}};e.default=r}).call(this,i("5a52")["default"])},4600:function(t,e,i){"use strict";i.r(e);var n=i("0417"),a=i("9430");for(var r in a)"default"!==r&&function(t){i.d(e,t,(function(){return a[t]}))}(r);i("59ff");var s,o=i("f0c5"),u=Object(o["a"])(a["default"],n["b"],n["c"],!1,null,"afb19496",null,!1,n["a"],s);e["default"]=u.exports},"52ec":function(t,e,i){i("fb6a"),i("f4b3"),i("a9e3"),i("d3b7"),i("ac1f"),i("25f0"),i("466d"),i("5319"),i("1276"),i("bf19"),function(e,i){t.exports=i()}(0,(function(){"use strict";var t="millisecond",e="second",i="minute",n="hour",a="day",r="week",s="month",o="quarter",u="year",c=/^(\d{4})-?(\d{1,2})-?(\d{0,2})[^0-9]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?.?(\d{1,3})?$/,d=/\[([^\]]+)]|Y{2,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,l=function(t,e,i){var n=String(t);return!n||n.length>=e?t:""+Array(e+1-n.length).join(i)+t},f={s:l,z:function(t){var e=-t.utcOffset(),i=Math.abs(e),n=Math.floor(i/60),a=i%60;return(e<=0?"+":"-")+l(n,2,"0")+":"+l(a,2,"0")},m:function(t,e){var i=12*(e.year()-t.year())+(e.month()-t.month()),n=t.clone().add(i,s),a=e-n<0,r=t.clone().add(i+(a?-1:1),s);return Number(-(i+(e-n)/(a?n-r:r-n))||0)},a:function(t){return t<0?Math.ceil(t)||0:Math.floor(t)},p:function(c){return{M:s,y:u,w:r,d:a,D:"date",h:n,m:i,s:e,ms:t,Q:o}[c]||String(c||"").toLowerCase().replace(/s$/,"")},u:function(t){return void 0===t}},p={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_")},h="en",A={};A[h]=p;var v=function(t){return t instanceof m},g=function(t,e,i){var n;if(!t)return h;if("string"==typeof t)A[t]&&(n=t),e&&(A[t]=e,n=t);else{var a=t.name;A[a]=t,n=a}return!i&&n&&(h=n),n||!i&&h},w=function(t,e){if(v(t))return t.clone();var i="object"==typeof e?e:{};return i.date=t,i.args=arguments,new m(i)},b=f;b.l=g,b.i=v,b.w=function(t,e){return w(t,{locale:e.$L,utc:e.$u,$offset:e.$offset})};var m=function(){function l(t){this.$L=this.$L||g(t.locale,null,!0),this.parse(t)}var f=l.prototype;return f.parse=function(t){this.$d=function(t){var e=t.date,i=t.utc;if(null===e)return new Date(NaN);if(b.u(e))return new Date;if(e instanceof Date)return new Date(e);if("string"==typeof e&&!/Z$/i.test(e)){var n=e.match(c);if(n)return i?new Date(Date.UTC(n[1],n[2]-1,n[3]||1,n[4]||0,n[5]||0,n[6]||0,n[7]||0)):new Date(n[1],n[2]-1,n[3]||1,n[4]||0,n[5]||0,n[6]||0,n[7]||0)}return new Date(e)}(t),this.init()},f.init=function(){var t=this.$d;this.$y=t.getFullYear(),this.$M=t.getMonth(),this.$D=t.getDate(),this.$W=t.getDay(),this.$H=t.getHours(),this.$m=t.getMinutes(),this.$s=t.getSeconds(),this.$ms=t.getMilliseconds()},f.$utils=function(){return b},f.isValid=function(){return!("Invalid Date"===this.$d.toString())},f.isSame=function(t,e){var i=w(t);return this.startOf(e)<=i&&i<=this.endOf(e)},f.isAfter=function(t,e){return w(t)<this.startOf(e)},f.isBefore=function(t,e){return this.endOf(e)<w(t)},f.$g=function(t,e,i){return b.u(t)?this[e]:this.set(i,t)},f.year=function(t){return this.$g(t,"$y",u)},f.month=function(t){return this.$g(t,"$M",s)},f.day=function(t){return this.$g(t,"$W",a)},f.date=function(t){return this.$g(t,"$D","date")},f.hour=function(t){return this.$g(t,"$H",n)},f.minute=function(t){return this.$g(t,"$m",i)},f.second=function(t){return this.$g(t,"$s",e)},f.millisecond=function(e){return this.$g(e,"$ms",t)},f.unix=function(){return Math.floor(this.valueOf()/1e3)},f.valueOf=function(){return this.$d.getTime()},f.startOf=function(t,o){var c=this,d=!!b.u(o)||o,l=b.p(t),f=function(t,e){var i=b.w(c.$u?Date.UTC(c.$y,e,t):new Date(c.$y,e,t),c);return d?i:i.endOf(a)},p=function(t,e){return b.w(c.toDate()[t].apply(c.toDate("s"),(d?[0,0,0,0]:[23,59,59,999]).slice(e)),c)},h=this.$W,A=this.$M,v=this.$D,g="set"+(this.$u?"UTC":"");switch(l){case u:return d?f(1,0):f(31,11);case s:return d?f(1,A):f(0,A+1);case r:var w=this.$locale().weekStart||0,m=(h<w?h+7:h)-w;return f(d?v-m:v+(6-m),A);case a:case"date":return p(g+"Hours",0);case n:return p(g+"Minutes",1);case i:return p(g+"Seconds",2);case e:return p(g+"Milliseconds",3);default:return this.clone()}},f.endOf=function(t){return this.startOf(t,!1)},f.$set=function(r,o){var c,d=b.p(r),l="set"+(this.$u?"UTC":""),f=(c={},c[a]=l+"Date",c.date=l+"Date",c[s]=l+"Month",c[u]=l+"FullYear",c[n]=l+"Hours",c[i]=l+"Minutes",c[e]=l+"Seconds",c[t]=l+"Milliseconds",c)[d],p=d===a?this.$D+(o-this.$W):o;if(d===s||d===u){var h=this.clone().set("date",1);h.$d[f](p),h.init(),this.$d=h.set("date",Math.min(this.$D,h.daysInMonth())).toDate()}else f&&this.$d[f](p);return this.init(),this},f.set=function(t,e){return this.clone().$set(t,e)},f.get=function(t){return this[b.p(t)]()},f.add=function(t,o){var c,d=this;t=Number(t);var l=b.p(o),f=function(e){var i=w(d);return b.w(i.date(i.date()+Math.round(e*t)),d)};if(l===s)return this.set(s,this.$M+t);if(l===u)return this.set(u,this.$y+t);if(l===a)return f(1);if(l===r)return f(7);var p=(c={},c[i]=6e4,c[n]=36e5,c[e]=1e3,c)[l]||1,h=this.$d.getTime()+t*p;return b.w(h,this)},f.subtract=function(t,e){return this.add(-1*t,e)},f.format=function(t){var e=this;if(!this.isValid())return"Invalid Date";var i=t||"YYYY-MM-DDTHH:mm:ssZ",n=b.z(this),a=this.$locale(),r=this.$H,s=this.$m,o=this.$M,u=a.weekdays,c=a.months,l=function(t,n,a,r){return t&&(t[n]||t(e,i))||a[n].substr(0,r)},f=function(t){return b.s(r%12||12,t,"0")},p=a.meridiem||function(t,e,i){var n=t<12?"AM":"PM";return i?n.toLowerCase():n},h={YY:String(this.$y).slice(-2),YYYY:this.$y,M:o+1,MM:b.s(o+1,2,"0"),MMM:l(a.monthsShort,o,c,3),MMMM:c[o]||c(this,i),D:this.$D,DD:b.s(this.$D,2,"0"),d:String(this.$W),dd:l(a.weekdaysMin,this.$W,u,2),ddd:l(a.weekdaysShort,this.$W,u,3),dddd:u[this.$W],H:String(r),HH:b.s(r,2,"0"),h:f(1),hh:f(2),a:p(r,s,!0),A:p(r,s,!1),m:String(s),mm:b.s(s,2,"0"),s:String(this.$s),ss:b.s(this.$s,2,"0"),SSS:b.s(this.$ms,3,"0"),Z:n};return i.replace(d,(function(t,e){return e||h[t]||n.replace(":","")}))},f.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},f.diff=function(t,c,d){var l,f=b.p(c),p=w(t),h=6e4*(p.utcOffset()-this.utcOffset()),A=this-p,v=b.m(this,p);return v=(l={},l[u]=v/12,l[s]=v,l[o]=v/3,l[r]=(A-h)/6048e5,l[a]=(A-h)/864e5,l[n]=A/36e5,l[i]=A/6e4,l[e]=A/1e3,l)[f]||A,d?v:b.a(v)},f.daysInMonth=function(){return this.endOf(s).$D},f.$locale=function(){return A[this.$L]},f.locale=function(t,e){if(!t)return this.$L;var i=this.clone(),n=g(t,e,!0);return n&&(i.$L=n),i},f.clone=function(){return b.w(this.$d,this)},f.toDate=function(){return new Date(this.valueOf())},f.toJSON=function(){return this.isValid()?this.toISOString():null},f.toISOString=function(){return this.$d.toISOString()},f.toString=function(){return this.$d.toUTCString()},l}();return w.prototype=m.prototype,w.extend=function(t,e){return t(e,m,w),w},w.locale=g,w.isDayjs=v,w.unix=function(t){return w(1e3*t)},w.en=A[h],w.Ls=A,w}))},"54bc":function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpGNkZENjdDNERGODkxMUVCQjk2NEZDQkE2OUQyMzFCQSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpGNkZENjdDNURGODkxMUVCQjk2NEZDQkE2OUQyMzFCQSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkY2RkQ2N0MyREY4OTExRUJCOTY0RkNCQTY5RDIzMUJBIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkY2RkQ2N0MzREY4OTExRUJCOTY0RkNCQTY5RDIzMUJBIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+PexfhgAABUJJREFUeNq0WEtL9FYYzjnJZCYZ/bzNjFY/C3XjDSrShZaCfqWoRWppkQofIvojXCkoulG3IohLcenKhStFF142ulBXCiriprXWeh3nmqTvSd8jL+PozOcl8JDMJDl58l6e85wwJfuNIThABWh4LP+3AQ7AAiRx7yCyfkA218iH+wAG7r2EkIJkBIkYIAqI4HGCEHsxGRkFD8AE5ALyAB/wtyTD8EGCSByJhBF3uI9kQ4pliIaBBAKAInFcXFzs7ejosJuamhJ1dXXJ8vLyZEFBQfLi4sI+Ozuzt7e3ndXVVWdxcdE+Pz+/h3tuALdILIqknWzJMIyGH0kUA4K1tbXG4OBgsqurK6KqqkwFTYNNxhMvoszOzvLx8XFlf3+fkoriPU4mMpKISEkQUKbremB0dJT19/ffA4kwect7QsYm92s4hgdJsZGREXVsbMyJxWK3hNCjCLE0qcnFaHyElAQWFhYSDQ0NgsQl4gaJxHFAmwzKSbcJMjqCr6ysKJ2dnZ6rqytZT/HnakgSqQB8Kikp+ePw8PA3x3F+AtQBygC5AA/g2S4U5wEcrzUB+YCizc3NwpycnBDWoZdIw6P0iJOlgAZIze9bW1u/wgCfALWAEMCHD2DZ6gaSUgFeJGXOzMyYSMYn05guKvmAGsDPExMTgsiPSCSIgzHlBRuJkoaR4pi6R5GR6qlj53wNXRPY2dkJa5om6uMvrJMYY8xRXriRF2E4ji6VWpyTY3NCRoTPHBgYiAARWbCi8uOvIYIM5P2ObJRAIGCIaIljGnUNBe3bUCjUkkgkfoCTNaLgMLRMeaMNx+KoYaHd3V0/1qJGW9Ftwfb29gRERcq52FuvjcoTEXJ/Tk9P+6UeCaIa0Re1ubk5Sia55FsSoQFC8L29PZ1ojcVpRdfX18dQjKTEv9fmvuTJyYmc/UV0GCcXWDDpxYmyWu9IxM3G9fW1jkRcK8JJ2KzCwsI4ken32qScqOFwWJLhMjLSnSXAAjw4tHeqFyqyumEYGnWMkoyokdjp6ekDmbds6TREBAE9GAxq0m6IZ3KcdV0y6+vr9junSJIRRWtUVVWpxLa6ZCwkE11eXray9auvqBcNHaQBbvGhedzSICpswvTuu4UNu0nojPKGxBiZkMuEZzo6OrqvqKi4gOMz4ZM4YRa7u7uLgQrbKYLH3pCMDw19QUtLiwZEktQt0gdx0mbUElpUqF5BRNRJoXCQYF9LNjY2IuAgRVT+BPwrJEUjN9ioMZz4WAsHeg0h6QpERITLC/b09HAgEiPLGDcTPMUEuT0NSw0rLy9Prpd8VJi+kATHiAhnVwL4qqamxj81NXWP9uQGydjpHBlD2+CFNs8tKioShfYRjVeONNdZkKKrDHH/d4BfSktLPx8fH3fA+N8DvkFPraZdqhC/4YoSGHJfW1tbPgygYAojZN0jVwZ2SiQ0JO3HiIglT7CystJYWlq6hflPOshzXPI82BT2hAFScUAjEon4ent78+bn571kLR1LWarQBvAhEbdroFg/9PX1KZOTkzemaV7Bf38D/kEiCTrtsGccmYb5FnVjrK2t+YeHh3NhryeTSSmOFp2F8XpBxgQSZmtrKxsaGoo0NjaKQr3CaFxi4SZS5z+WwSJq1B+LN4b5y5ibm/NDa/oODg50WE9roJMqCCYHX8urq6sVUFa7u7s7DimRHXOJZK4x1WkdZMbFWJrPIWbKZxFPyqcR+X0miqmgy+H/xe0JR5CxVcnsTZesMh06ElFTvtHEyXcaua7O6Ke/SOqR2INnRtBWd5CQRdfh2Xqj/wQYAIEPjut4Tiq/AAAAAElFTkSuQmCC"},"59ff":function(t,e,i){"use strict";var n=i("d1bb"),a=i.n(n);a.a},"6ad5":function(t,e,i){var n=i("24fb"),a=i("1de5"),r=i("54bc");e=n(!1);var s=a(r);e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.popup-bg[data-v-afb19496]{display:flex;flex-direction:column;align-items:center;justify-content:center;position:fixed;top:0;left:%?0?%;right:0;bottom:0;width:%?750?%;background-color:rgba(0,0,0,.6);z-index:10000}.popup-content[data-v-afb19496]{display:flex;flex-direction:column;align-items:center}.popup-content-show[data-v-afb19496]{-webkit-animation:mymove-data-v-afb19496 .5s;animation:mymove-data-v-afb19496 .5s;-webkit-transform:scale(1);transform:scale(1)}@-webkit-keyframes mymove-data-v-afb19496{0%{-webkit-transform:scale(0);transform:scale(0)\n    /*开始为原始大小*/}100%{-webkit-transform:scale(1);transform:scale(1)}}@keyframes mymove-data-v-afb19496{0%{-webkit-transform:scale(0);transform:scale(0)\n    /*开始为原始大小*/}100%{-webkit-transform:scale(1);transform:scale(1)}}.update-wrap[data-v-afb19496]{width:%?580?%;border-radius:%?18?%;position:relative;display:flex;flex-direction:column;background-color:#fff;padding:%?170?% %?30?% 0}.update-wrap .top-img[data-v-afb19496]{position:absolute;left:0;width:100%;height:%?256?%;top:%?-128?%}.update-wrap .content[data-v-afb19496]{display:flex;flex-direction:column;align-items:center;padding-bottom:%?40?%}.update-wrap .content .title[data-v-afb19496]{font-size:%?32?%;font-weight:700;color:#6526f3}.update-wrap .content .title-sub[data-v-afb19496]{text-align:center;font-size:%?24?%;color:#666;padding:%?30?% 0}.update-wrap .content .btn[data-v-afb19496]{width:%?460?%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:%?30?%;height:%?80?%;line-height:%?80?%;border-radius:100px;background-color:#6526f3;margin-top:%?20?%}.close-ioc[data-v-afb19496]{width:%?70?%;height:%?70?%;margin-top:%?30?%}.sche-wrap[data-v-afb19496]{display:flex;flex-direction:column;align-items:center;justify-content:flex-end;padding:%?10?% %?50?% 0}.sche-wrap .sche-wrap-text[data-v-afb19496]{font-size:%?24?%;color:#666;margin-bottom:%?20?%}.sche-wrap .sche-bg[data-v-afb19496]{position:relative;background-color:#ccc;height:%?30?%;border-radius:100px;width:%?480?%;display:flex;align-items:center}.sche-wrap .sche-bg .sche-bg-jindu[data-v-afb19496]{position:absolute;left:0;top:0;height:%?30?%;min-width:%?40?%;border-radius:100px;background:url('+s+") #5775e7 100% %?4?% no-repeat;background-size:%?26?% %?26?%}.sche-wrap .down-text[data-v-afb19496]{font-size:%?24?%;color:#5674e5;margin-top:%?16?%}",""]),t.exports=e},"6fe8":function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADsAAAA7CAYAAADFJfKzAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozOEQxNDdCNERFRDIxMUVCODY4OEU0MjZFMjZGRTNENCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozOEQxNDdCNURFRDIxMUVCODY4OEU0MjZFMjZGRTNENCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjM4RDE0N0IyREVEMjExRUI4Njg4RTQyNkUyNkZFM0Q0IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjM4RDE0N0IzREVEMjExRUI4Njg4RTQyNkUyNkZFM0Q0Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/3JaFAAAA9pJREFUeNrsm11IFUEUx0f7gBIqioggKvShrlGUZd1eVHoJoRc1ECqQW0I3CnwIlR7qMdekRIks+nirp75ewoIgspc+LEWwD9DIbggaRgkWZNftP9xz7bbM9e7uzGy7Nw/8XvY6Z8/fmd0zZ2Y2xzRNpskKQRiEwDqwCiwBC8EC8AN8B1/BJ/AOvAFPwWsdAeUoFrsbVIBSsF7Cz1vwGNwBD/wkdi6oJbZq6JCX4DK4Cn5JeeJiJagHQ6Y3NkT3cx2v24a7QL/5b6yf7u+J2CbTH9bkNHYnz+xycBOUMP9YF9gLPqt8QW0BnWAF85+NgHLQo0JsEeW+ecy/Nkk5/ZWM2E3gBZjP/G8/QTHocyN2KXgPFrPg2DjIB2OiH3NnaPgwYEK5LaK4mROx5+ilFETbTPHbGsbbwTMWfNsBnmcS+wGsyQKxQ2DtTMM4miVCGemIpuvZOeALPeTZYuOUVeLWnq2VEHqJXmjHFAZ6AGwDdyXfzrWiEm/A5YS81zLhrlQwyS+1+ByW8DWQ9JPs2Z2gwOV/z/rc3waVEr1RTqsUqqyA9E0HWiPhbKMgr/HllCoXvnib+5Zr18FKScE1qcM4pmDotQhqSCdDep+gfYei2jeWLN5DCgtqkeAKG+0OCdq1Ki72Q9xpRLFTp4KPCv7+tIaVjQh33KbBsV3BogW0U5qWcdq4805Nzs9keIZPCn4/rnHNqpNRnjQ9FFwHzguuH9G8QNer6k3sVLCViKnfYjzP5mmen9ZTHs5LMyE5CK55ME/O44XAN48m/3vAPcs1PlkY9qooyE1WBJqtBTxKswy63yOxcS52QvNNDNDAEtuTVpsCN1xOLZ3aRG66lTiFQk9YrtWBC4LioUKz2DGdebYpQ55tdjm1lMqz7RocGzaFNEsWD06sXUeOMxwKMDzq4Yjqqsdw2VOGBz0cUlnPigKuctBe5zMcS92Mli2SzyrqmXQ9HJeMryNVbFjCUZ/iISjqYdkyNJy64Mb3Xwdd5q8pwTrSLYl8yCcgzRnu4cQGSd9fS6lRif/cRVCouEzjQ28DOAwmJfxERWcq/qsdAX6hkWWXNaYWOqJdPL77tToLhH60btKJNqOrs6RXq+3svPM3V2vAhbZOv4EzbEYnrYe27INmPO4iu8cMkraMclSQDpHwJaZ8yirMzjD+U+wyVhawXi1LJzSTWG69LHFW0QyA0BKKl7kVy+0JS5ygGfGpSB5XMcXJZMVy62aJo31dPhPaRXF1u9k1n8lGWeLsv+EToQbFM2q7xexJcvs0ePyNQINMvEH5+uMKIfX1x+x3PYrMd19s/RZgAJ9yv76mYttEAAAAAElFTkSuQmCC"},"8f64":function(t,e,i){var n=i("3aab");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("100e52f0",n,!0,{sourceMap:!1,shadowMode:!1})},9430:function(t,e,i){"use strict";i.r(e);var n=i("3fed"),a=i.n(n);for(var r in n)"default"!==r&&function(t){i.d(e,t,(function(){return n[t]}))}(r);e["default"]=a.a},9751:function(t,e,i){"use strict";var n=i("8f64"),a=i.n(n);a.a},"9a77":function(t,e,i){"use strict";(function(t){var n=i("4ea4");i("a434"),i("e25e"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i("3474"),r=i("7fe6"),s=i("c6cd3"),o=i("26cb"),u=(n(i("52ec")),n(i("c83f"))),c=(n(i("4600")),{components:{},mixins:[u.default],data:function(){return{userInfo:{},loginType:"h5",userIndex:0,switchUserInfo:[],isAuto:!1,isShowAuth:!1,canvasWidth:"",canvasHeight:"",canvasStatus:!1,fileSizeString:"",version:""}},computed:(0,o.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,e){t&&this.getUserInfo()},deep:!0}},onLoad:function(){this.isLogin?this.getUserInfo():(0,s.toLogin)()},methods:{isNew:function(){that.$util.Tips({title:"当前为最新版本"})},updateApp:function(){this.$refs.appUpdate.update()},formatSize:function(){var t=this;plus.cache.calculate((function(e){var i=parseInt(e);t.fileSizeString=0==i?"0B":i<1024?i+"B":i<1048576?(i/1024).toFixed(2)+"KB":i<1073741824?(i/1048576).toFixed(2)+"MB":(i/1073741824).toFixed(2)+"GB"}))},initData:function(){var t=this;uni.showModal({title:"清楚缓存",content:"确定清楚本地缓存数据吗?",success:function(e){if(e.confirm)t.clearCache(),t.formatSize();else if(e.cancel)return that.$util.Tips({title:"已取消"})}})},clearCache:function(){var t=this,e=plus.os.name;if("Android"==e)for(var i=plus.android.runtimeMainActivity(),n=i.getCacheDir(),a=plus.android.invoke(n,"listFiles"),r=a.length,s=0;s<r;s++){var o=""+a[s];plus.io.resolveLocalFileSystemURL(o,(function(e){e.isDirectory?e.removeRecursively((function(e){uni.showToast({title:"缓存清理完成",duration:2e3}),t.formatSize()}),(function(t){t.message})):e.remove()}),(function(t){}))}else plus.cache.clear((function(){uni.showToast({title:"缓存清理完成",duration:2e3}),t.formatSize()}))},onLoadFun:function(){this.getUserInfo()},authColse:function(t){this.isShowAuth=t},Setting:function(){uni.openSetting({success:function(t){}})},switchAccounts:function(t){var e=this,i=this.switchUserInfo[t],n=this;return n.userIndex=t,n.switchUserInfo.length<=1||(void 0===i?n.$util.Tips({title:"切换的账号不存在"}):void("h5"===i.user_type?(uni.showLoading({title:"正在切换中"}),(0,r.switchH5Login)().then((function(t){uni.hideLoading(),n.$store.commit("LOGIN",{token:t.data.token,time:e.$Cache.strTotime(t.data.expires_time)-e.$Cache.time()}),n.getUserInfo()})).catch((function(t){return uni.hideLoading(),n.$util.Tips({title:t})}))):(n.$store.commit("LOGOUT"),uni.showLoading({title:"正在切换中"}),(0,s.toLogin)())))},outLogin:function(){var t=this;"h5"==t.loginType&&uni.showModal({title:"提示",content:"确认退出登录?",success:function(e){e.confirm?(0,a.getLogout)().then((function(e){t.$store.commit("LOGOUT"),uni.reLaunch({url:"/pages/index/index"})})).catch((function(t){})):e.cancel}})},getUserInfo:function(){var t=this;(0,a.getUserInfo)().then((function(e){t.$set(t,"userInfo",e.data);for(var i=e.data.switchUserInfo||[],n=0;n<i.length;n++)i[n].uid==t.userInfo.uid&&(t.userIndex=n),t.$wechat.isWeixin()||"h5"==i[n].user_type||""!==i[n].phone||i.splice(n,1);t.$set(t,"switchUserInfo",i)}))},uploadpic:function(){var t=this,e=this;this.canvasStatus=!0,e.$util.uploadImageChange("upload/image",(function(i){var n=e.switchUserInfo[e.userIndex];void 0!==n&&(e.userInfo.avatar=i.data.url),e.switchUserInfo[e.userIndex]=n,e.$set(e,"switchUserInfo",e.switchUserInfo),t.canvasStatus=!1}),(function(e){t.canvasStatus=!1}),(function(e){t.canvasWidth=e.w,t.canvasHeight=e.h}))},formSubmit:function(t){var e=this,i=t.detail.value;e.switchUserInfo[e.userIndex];if(!i.nickname)return e.$util.Tips({title:"用户姓名不能为空"});i.avatar=this.userInfo.avatar,(0,a.userEdit)(i).then((function(t){return e.$util.Tips({title:t.msg,icon:"success"},{tab:3,url:1})})).catch((function(t){return e.$util.Tips({title:t||"保存失败，您并没有修改"},{tab:3,url:1})}))}}});e.default=c}).call(this,i("5a52")["default"])},a348:function(t,e,i){"use strict";i.r(e);var n=i("9a77"),a=i.n(n);for(var r in n)"default"!==r&&function(t){i.d(e,t,(function(){return n[t]}))}(r);e["default"]=a.a},bf19:function(t,e,i){"use strict";var n=i("23e7");n({target:"URL",proto:!0,enumerable:!0},{toJSON:function(){return URL.prototype.toString.call(this)}})},d1bb:function(t,e,i){var n=i("6ad5");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("00282f7a",n,!0,{sourceMap:!1,shadowMode:!1})},f4b3:function(t,e,i){"use strict";var n=i("23e7"),a=i("d039"),r=i("7b0b"),s=i("c04e"),o=a((function(){return null!==new Date(NaN).toJSON()||1!==Date.prototype.toJSON.call({toISOString:function(){return 1}})}));n({target:"Date",proto:!0,forced:o},{toJSON:function(t){var e=r(this),i=s(e);return"number"!=typeof i||isFinite(i)?e.toISOString():null}})}}]);