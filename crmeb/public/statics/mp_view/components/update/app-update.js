(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/update/app-update"],{1395:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i,a=n("a9a5"),o={name:"appUpdate",props:{tabbar:{type:Boolean,default:!1},getVer:{type:Boolean,default:!1}},data:function(){return{popup_show:!1,platform:"",version:"1.0.0",need_update:!1,downing:!1,downstatus:0,update_info:{os:"",version:"",info:""},fileSize:0,downSize:0,viewObj:null}},created:function(){i=this,this.getVer||this.update()},computed:{lengthWidth:function(){var t=this.downSize/this.fileSize*100;return t=t?t.toFixed(2):0,{width:t+"%"}},getHeight:function(){var t=0;return this.tabbar&&(t=50),{bottom:t+"px",height:"auto"}}},methods:{update:function(){},getUpdateInfo:function(){var e=this;(0,a.getUpdateInfo)("ios"===this.platform?2:1).then((function(n){if(Array.isArray(n.data))return e.$emit("isNew");var a=t.getStorageSync("app_update_time")||"",o=(new Date).toLocaleDateString();if(a===o||e.getVer){if(a!==o&&e.getVer){if(!n.data.is_force)return}else if(a==o&&!e.getVer&&!n.data.is_force)return}else t.setStorageSync("app_update_time",(new Date).toLocaleDateString());var u=n.data;i.update_info=u,i.update_info.platform&&i.checkUpdate()})).catch((function(t){i.popup_show=!1}))},checkUpdate:function(){i.need_update=i.compareVersion(i.version,i.update_info.version),i.need_update?(i.popup_show=!0,i.tabbar&&(i.viewObj=new plus.nativeObj.View("viewObj",{bottom:"0px",left:"0px",height:"50px",width:"100%",backgroundColor:"rgba(0,0,0,.6)"}),i.viewObj.show())):this.$emit("isNew")},closeUpdate:function(){i.update_info.is_force?"android"==this.platform?plus.runtime.quit():plus.ios.import("UIApplication").sharedApplication().performSelector("exit"):(i.popup_show=!1,i.viewObj&&i.viewObj.hide())},nowUpdate:function(){if(i.downing)return!1;i.downing=!0,/\.apk$/.test(i.update_info.url)||/\.wgt$/.test(i.update_info.url)?i.download_wgt():plus.runtime.openURL(i.update_info.url,(function(){plus.nativeUI.toast("打开错误")}))},download_wgt:function(){plus.nativeUI.showWaiting("下载更新文件...");var t={method:"get"},e=plus.downloader.createDownload(i.update_info.url,t,(function(t,e){}));e.addEventListener("statechanged",(function(t,e){if(null===e);else if(200==e)switch(i.downstatus=t.state,t.state){case 3:i.downSize=t.downloadedSize,t.totalSize&&(i.fileSize=t.totalSize);break;case 4:i.installWgt(t.filename);break}else plus.nativeUI.closeWaiting(),plus.nativeUI.toast("下载出错"),i.downing=!1,i.downstatus=0})),e.start()},installWgt:function(t){plus.nativeUI.showWaiting("安装更新文件..."),plus.runtime.install(t,{},(function(){plus.nativeUI.closeWaiting(),plus.nativeUI.alert("应用资源下载完成！",(function(){plus.runtime.restart()}))}),(function(t){plus.nativeUI.closeWaiting(),plus.nativeUI.alert("安装更新文件失败["+t.code+"]："+t.message)}))},compareVersion:function(t,e){if(!t||!e||""==t||""==e)return!1;for(var n=t.split(".",4),i=e.split(".",4),a=0;a<n.length&&a<i.length;a++){var o=n[a],u=parseInt(o),r=i[a],s=parseInt(r);if(s>u||r.length>o.length)return!0;if(s<u)return!1}return i.length>n.length&&0==e.indexOf(t)}}};e.default=o}).call(this,n("543d")["default"])},4960:function(t,e,n){"use strict";n.r(e);var i=n("1395"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},"77a7":function(t,e,n){},b42b:function(t,e,n){"use strict";var i=n("77a7"),a=n.n(i);a.a},c2ad:function(t,e,n){"use strict";n.r(e);var i=n("fcd1"),a=n("4960");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("b42b");var u,r=n("f0c5"),s=Object(r["a"])(a["default"],i["b"],i["c"],!1,null,"44f416fc",null,!1,i["a"],u);e["default"]=s.exports},fcd1:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=(t._self._c,t.popup_show?t.$t("发现新版本"):null),i=t.popup_show&&t.downstatus<1?t.$t("立即升级"):null,a=!t.popup_show||t.downstatus<1?null:t.$t("下载进度"),o=!t.popup_show||t.downstatus<1?null:(t.downSize/1024/1024).toFixed(2),u=!t.popup_show||t.downstatus<1?null:(t.fileSize/1024/1024).toFixed(2);t.$mp.data=Object.assign({},{$root:{m0:n,m1:i,m2:a,g0:o,g1:u}})},o=[]}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/update/app-update-create-component',
    {
        'components/update/app-update-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("c2ad"))
        })
    },
    [['components/update/app-update-create-component']]
]);
