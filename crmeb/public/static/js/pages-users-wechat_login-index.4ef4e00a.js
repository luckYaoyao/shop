(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-users-wechat_login-index"],{"180a":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.isUp?n("v-uni-view",[n("v-uni-view",{staticClass:"mobile-bg",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.close.apply(void 0,arguments)}}}),n("v-uni-view",{staticClass:"mobile-mask animated",class:{slideInUp:t.isUp}},[n("v-uni-view",{staticClass:"input-item"},[n("v-uni-input",{attrs:{type:"text",placeholder:t.$t("输入手机号"),maxlength:"11"},model:{value:t.account,callback:function(e){t.account=e},expression:"account"}})],1),n("v-uni-view",{staticClass:"input-item"},[n("v-uni-input",{attrs:{type:"text",placeholder:t.$t("输入验证码"),maxlength:"6"},model:{value:t.codeNum,callback:function(e){t.codeNum=e},expression:"codeNum"}}),n("v-uni-button",{staticClass:"code",attrs:{disabled:t.disabled},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.code.apply(void 0,arguments)}}},[t._v(t._s(t.text))])],1),n("v-uni-view",{staticClass:"sub_btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.loginBtn.apply(void 0,arguments)}}},[t._v(t._s(t.$t("立即登录")))])],1),n("Verify",{ref:"verify",attrs:{captchaType:"blockPuzzle",imgSize:{width:"330px",height:"155px"}},on:{success:function(e){arguments[0]=e=t.$handleEvent(e),t.success.apply(void 0,arguments)}}})],1):t._e()},o=[]},"1d6d":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */uni-page-body[data-v-4a9da8dc]{background:#fff}.wechat_login[data-v-4a9da8dc]{padding:%?72?% %?34?%}.wechat_login .img uni-image[data-v-4a9da8dc]{width:100%}.wechat_login .btn-wrapper[data-v-4a9da8dc]{margin-top:%?86?%;padding:0 %?66?%}.wechat_login .btn-wrapper uni-button[data-v-4a9da8dc]{width:100%;height:%?86?%;line-height:%?86?%;margin-bottom:%?40?%;border-radius:%?120?%;font-size:%?30?%}.wechat_login .btn-wrapper uni-button.btn1[data-v-4a9da8dc]{color:#fff}.wechat_login .btn-wrapper uni-button.btn2[data-v-4a9da8dc]{color:#666;border:1px solid #666}.title-bar[data-v-4a9da8dc]{position:relative;display:flex;align-items:center;justify-content:center;font-size:%?36?%}.icon[data-v-4a9da8dc]{position:absolute;left:%?30?%;top:0;display:flex;align-items:center;justify-content:center;width:%?86?%;height:%?86?%}.icon uni-image[data-v-4a9da8dc]{width:%?50?%;height:%?50?%}body.?%PAGE?%[data-v-4a9da8dc]{background:#fff}',""]),t.exports=e},"2e1a":function(t,e,n){"use strict";(function(t){var i=n("4ea4");n("c975"),n("4d63"),n("ac1f"),n("25f0"),n("466d"),n("5319"),n("841c"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(n("d0c2")),o=i(n("a8cb")),s=n("18f5"),c=(n("b2de"),n("816a"),i(n("8394"))),u=i(n("e7d9")),d=i(n("9ad2")),r=i(n("e7d9")),l=getApp(),f=uni.getSystemInfoSync().statusBarHeight+"px",h={mixins:[d.default],data:function(){return{isUp:!1,phone:"",statusBarHeight:f,isHome:!1,isPhoneBox:!1,logoUrl:"",code:"",authKey:"",options:"",userInfo:{},codeNum:0,canUseGetUserProfile:!1,mp_is_new:this.$Cache.get("MP_VERSION_ISNEW")||!1}},components:{mobileLogin:a.default,routinePhone:o.default},onLoad:function(t){var e=this;uni.getUserProfile&&(this.canUseGetUserProfile=!0),(0,s.getLogo)().then((function(t){e.logoUrl=t.data.logo_url}));var n=this;document.body.addEventListener("focusout",(function(){setTimeout((function(){var t=document.documentElement.scrollTop||document.body.scrollTop||0;window.scrollTo(0,Math.max(t-1,0))}),100)}));var i=t.code,a=t.state;t.scope;if(this.options=t,this.code=i||"",i&&"snsapi_base"!==this.options.scope){l.globalData.spid&&l.globalData.spid;u.default.auth(i,a).then((function(t){if(void 0!==t.key&&t.key)n.authKey=t.key,n.isUp=!0;else{var e=t.expires_time-n.$Cache.time();n.$store.commit("LOGIN",{token:t.token,time:e}),n.userInfo=t.userInfo,n.$store.commit("SETUID",t.userInfo.uid),n.$store.commit("UPDATE_USERINFO",t.userInfo),n.wechatPhone()}})).catch((function(t){}))}else if(i&&"snsapi_base"==this.options.scope&&!this.$Cache.has("snsapiKey")){var o,c="snsapi_base",d=location.pathname+location.search;if(t.back_url&&uni.setStorageSync("snRouter",t.back_url),!n.$store.getters.isLogin&&r.default.isWeixin())o=t.code instanceof Array?t.code[t.code.length-1]:t.code,o&&o!=uni.getStorageSync("snsapiCode")&&!this.$Cache.has("snsapiKey")?(uni.setStorageSync("snsapiCode",o),uni.setStorageSync("authIng",!0),(0,s.silenceAuth)({code:o,spread:n.$Cache.get("spread"),spid:n.$Cache.get("spread")}).then((function(t){void 0!==t.data.key&&t.data.key&&(e.$Cache.set("snsapiKey",t.data.key),uni.navigateTo({url:"/pages/users/wechat_login/index"}))})).catch((function(e){uni.setStorageSync("authIng",!1);var i="";i=t.back_url instanceof Array?t.back_url[t.back_url.length-1]:t.back_url,n.$Cache.has("snsapiKey")||r.default.oAuth(c,i)}))):r.default.oAuth(c,d);else t.query.back_url&&location.replace(uni.getStorageSync("snRouter"))}else if(!this.$Cache.has("snsapiKey")){var f=location.pathname+location.search;r.default.oAuth("snsapi_base",f)}var h=getCurrentPages(),p=h[h.length-2];p&&"pages/order_addcart/order_addcart"==p.route?this.isHome=!0:this.isHome=!1},methods:{userLogin:function(){var t=this;c.default.getCode().then((function(e){uni.showLoading({title:t.$t("正在登录中")}),(0,s.authLogin)({code:e,spread_spid:l.globalData.spid,spread_code:l.globalData.code}).then((function(e){if(void 0!==e.data.key&&e.data.key)uni.hideLoading(),t.authKey=e.data.key,t.isPhoneBox=!0;else{uni.hideLoading();var n=e.data.expires_time-t.$Cache.time();t.$store.commit("LOGIN",{token:e.data.token,time:n}),t.getUserInfo()}}))})).catch((function(t){}))},back:function(){uni.navigateBack()},home:function(){uni.switchTab({url:"/pages/index/index"})},maskClose:function(){this.isUp=!1},bindPhoneClose:function(t){t.isStatus?(this.isPhoneBox=!1,this.$util.Tips({title:this.$t("登录成功"),icon:"success"},{tab:3})):this.isPhoneBox=!1},getQueryString:function(t){var e=new RegExp("(^|&)"+t+"=([^&]*)(&|$)","i"),n=new RegExp("(^|/)"+t+"/([^/]*)(/|$)","i"),i=window.location.search.substr(1).match(e),a=window.location.pathname.substr(1).match(n);return null!=i?unescape(i[2]):null!=a?unescape(a[2]):null},wechatLogin:function(){this.code&&"snsapi_base"===this.options.scope?this.authKey&&(this.isUp=!0):this.$wechat.oAuth("snsapi_userinfo","/pages/users/wechat_login/index")},wechatPhone:function(){if(this.$Cache.clear("snsapiKey"),this.options.back_url){var t=uni.getStorageSync("snRouter");t=-1!=t.indexOf("/pages/index/index")?"/":t,-1!==t.indexOf("/pages/users/wechat_login/index")&&(t="/"),t||(t="/pages/index/index"),this.isUp=!1,uni.showToast({title:this.$t("登录成功"),icon:"none"}),setTimeout((function(e){location.href=t}),800)}else this.isUp=!1,uni.showToast({title:this.$t("登录成功"),icon:"none"}),setTimeout((function(t){location.href="/pages/index/index"}),800)}}};e.default=h}).call(this,n("5a52")["default"])},"47e1":function(t,e,n){"use strict";var i=n("ce6f"),a=n.n(i);a.a},4952:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.isPhoneBox?n("v-uni-view",[n("v-uni-view",{staticClass:"mobile-bg",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.close.apply(void 0,arguments)}}}),n("v-uni-view",{staticClass:"mobile-mask animated",class:{slideInUp:t.isUp}},[n("v-uni-view",{staticClass:"info-box"},[n("v-uni-image",{attrs:{src:t.logoUrl}}),n("v-uni-view",{staticClass:"title"},[t._v(t._s(t.$t("获取授权")))]),n("v-uni-view",{staticClass:"txt"},[t._v(t._s(t.$t("获取微信的手机号授权")))])],1),n("v-uni-button",{staticClass:"sub_btn",attrs:{"open-type":"getPhoneNumber"},on:{getphonenumber:function(e){arguments[0]=e=t.$handleEvent(e),t.getphonenumber.apply(void 0,arguments)}}},[t._v(t._s(t.$t("获取微信手机号")))])],1)],1):t._e()},o=[]},"4ea1":function(t,e,n){"use strict";n.r(e);var i=n("7da7"),a=n("7170");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("47e1");var s,c=n("f0c5"),u=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"4a9da8dc",null,!1,i["a"],s);e["default"]=u.exports},"5ec3":function(t,e,n){"use strict";var i=n("a329"),a=n.n(i);a.a},"6a0e":function(t,e,n){"use strict";var i=n("d983"),a=n.n(i);a.a},7170:function(t,e,n){"use strict";n.r(e);var i=n("2e1a"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},"71d1":function(t,e,n){"use strict";var i=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(n("5645")),o=(i(n("8394")),i(n("234b"))),s=(i(n("c6e1")),n("816a")),c=(n("a656"),n("18f5"),getApp()),u={name:"login_mobile",components:{Verify:o.default},props:{isUp:{type:Boolean,default:!1},authKey:{type:String,default:""}},data:function(){return{keyCode:"",account:"",codeNum:""}},mixins:[a.default],mounted:function(){this.getCode()},methods:{success:function(t){var e=this;this.$refs.verify.hide(),(0,s.getCodeApi)().then((function(n){(0,s.registerVerify)({phone:e.account,key:n.data.key,captchaType:"blockPuzzle",captchaVerification:t.captchaVerification}).then((function(t){e.$util.Tips({title:t.msg}),e.sendCode()})).catch((function(t){return e.$util.Tips({title:t})}))}))},code:function(){var t=this;return t.account?/^1(3|4|5|7|8|9|6)\d{9}$/i.test(t.account)?void this.$refs.verify.show():t.$util.Tips({title:t.$t("请输入正确的手机号码")}):t.$util.Tips({title:t.$t("请填写手机号码")})},getCode:function(){var t=this;(0,s.getCodeApi)().then((function(e){t.keyCode=e.data.key})).catch((function(e){t.$util.Tips({title:e})}))},close:function(){this.$emit("close",!1)},loginBtn:function(){var t=this;if(!t.account)return t.$util.Tips({title:t.$t("请填写手机号码")});if(!/^1(3|4|5|7|8|9|6)\d{9}$/i.test(t.account))return t.$util.Tips({title:t.$t("请输入正确的手机号码")});if(!t.codeNum)return t.$util.Tips({title:t.$t("请填写验证码")});if(!/^[\w\d]+$/i.test(t.codeNum))return t.$util.Tips({title:t.$t("请输入正确的验证码")});if(uni.showLoading({title:t.$t("正在登录中")}),this.authKey)this.phoneAuth(this.authKey);else{var e=this.$Cache.get("snsapiKey");this.phoneAuth(e)}},phoneAuth:function(t){var e=this;(0,s.phoneWxSilenceAuth)({spid:c.globalData.spid,spread:c.globalData.code,phone:this.account,captcha:this.codeNum,key:t}).then((function(t){var n=t.data.expires_time-e.$Cache.time();e.$store.commit("LOGIN",{token:t.data.token,time:n}),e.getUserInfo()})).catch((function(t){uni.hideLoading(),e.$util.Tips({title:t})}))},getUserInfo:function(){var t=this;(0,s.getUserInfo)().then((function(e){uni.hideLoading(),t.userInfo=e.data,t.$store.commit("SETUID",e.data.uid),t.$store.commit("UPDATE_USERINFO",e.data),t.$emit("wechatPhone",!0)}))}}};e.default=u},"7da7":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{style:t.colorStyle},[i("v-uni-view",{staticClass:"system-height",style:{height:t.statusBarHeight}}),i("v-uni-view",{staticClass:"wechat_login"},[i("v-uni-view",{staticClass:"img"},[i("v-uni-image",{attrs:{src:n("d26d"),mode:"widthFix"}})],1),i("v-uni-view",{staticClass:"btn-wrapper"},[i("v-uni-button",{staticClass:"bg-green btn1",attrs:{"hover-class":"none"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.wechatLogin.apply(void 0,arguments)}}},[t._v(t._s(t.$t("微信登录")))]),i("v-uni-button",{staticClass:"btn2",attrs:{"hover-class":"none"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.isUp=!0}}},[t._v(t._s(t.$t("手机号登录")))])],1)],1),t.isUp?[i("mobileLogin",{attrs:{isUp:t.isUp,authKey:t.authKey},on:{close:function(e){arguments[0]=e=t.$handleEvent(e),t.maskClose.apply(void 0,arguments)},wechatPhone:function(e){arguments[0]=e=t.$handleEvent(e),t.wechatPhone.apply(void 0,arguments)}}})]:t._e(),t.isPhoneBox?[i("routinePhone",{attrs:{logoUrl:t.logoUrl,isPhoneBox:t.isPhoneBox,authKey:t.authKey},on:{close:function(e){arguments[0]=e=t.$handleEvent(e),t.bindPhoneClose.apply(void 0,arguments)}}})]:t._e()],2)},o=[]},"9ab5":function(t,e,n){"use strict";var i=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;i(n("8394")),n("816a"),n("18f5"),getApp();var a={name:"routine_phone",props:{isPhoneBox:{type:Boolean,default:!1},logoUrl:{type:String,default:""},authKey:{type:String,default:""}},data:function(){return{keyCode:"",account:"",codeNum:"",isStatus:!1}},mounted:function(){},methods:{close:function(){this.$emit("close",{isStatus:this.isStatus})}}};e.default=a},a329:function(t,e,n){var i=n("bd07");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("f4bbfe4c",i,!0,{sourceMap:!1,shadowMode:!1})},a8cb:function(t,e,n){"use strict";n.r(e);var i=n("4952"),a=n("abc3");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("5ec3");var s,c=n("f0c5"),u=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"bd7adbc2",null,!1,i["a"],s);e["default"]=u.exports},abc3:function(t,e,n){"use strict";n.r(e);var i=n("9ab5"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},b8ec:function(t,e,n){"use strict";n.r(e);var i=n("71d1"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},bd07:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.mobile-bg[data-v-bd7adbc2]{position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5)}.mobile-mask[data-v-bd7adbc2]{z-index:20;position:fixed;left:0;bottom:0;width:100%;padding:%?67?% %?30?%;background:#fff}.mobile-mask .info-box[data-v-bd7adbc2]{display:flex;flex-direction:column;align-items:center;justify-content:center}.mobile-mask .info-box uni-image[data-v-bd7adbc2]{width:%?150?%;height:%?150?%;border-radius:%?10?%}.mobile-mask .info-box .title[data-v-bd7adbc2]{margin-top:%?30?%;margin-bottom:%?20?%;font-size:%?36?%}.mobile-mask .info-box .txt[data-v-bd7adbc2]{font-size:%?30?%;color:#868686}.mobile-mask .sub_btn[data-v-bd7adbc2]{width:%?690?%;height:%?86?%;line-height:%?86?%;margin-top:%?60?%;background:var(--view-theme);border-radius:%?43?%;color:#fff;font-size:%?28?%;text-align:center}.animated[data-v-bd7adbc2]{-webkit-animation-duration:.4s;animation-duration:.4s}',""]),t.exports=e},ce6f:function(t,e,n){var i=n("1d6d");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("03962cfb",i,!0,{sourceMap:!1,shadowMode:!1})},d0c2:function(t,e,n){"use strict";n.r(e);var i=n("180a"),a=n("b8ec");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("6a0e");var s,c=n("f0c5"),u=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"6f1f6184",null,!1,i["a"],s);e["default"]=u.exports},d26d:function(t,e,n){t.exports=n.p+"static/img/wechat_login.b276d0e9.png"},d983:function(t,e,n){var i=n("f0e5");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("86157e8e",i,!0,{sourceMap:!1,shadowMode:!1})},f0e5:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,".mobile-bg[data-v-6f1f6184]{position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5)}.mobile-mask[data-v-6f1f6184]{z-index:20;position:fixed;left:0;bottom:0;width:100%;padding:%?67?% %?30?%;background:#fff}.mobile-mask .input-item[data-v-6f1f6184]{display:flex;justify-content:space-between;width:100%;height:%?86?%;margin-bottom:%?38?%}.mobile-mask .input-item uni-input[data-v-6f1f6184]{flex:1;display:block;height:100%;padding-left:%?40?%;border-radius:%?43?%;border:1px solid #dcdcdc}.mobile-mask .input-item .code[data-v-6f1f6184]{display:flex;align-items:center;justify-content:center;width:%?220?%;height:%?86?%;margin-left:%?30?%;background:var(--view-minorColorT);font-size:%?28?%;color:var(--view-theme);border-radius:%?43?%}.mobile-mask .input-item .code[disabled][data-v-6f1f6184]{background:rgba(0,0,0,.05);color:#999}.mobile-mask .sub_btn[data-v-6f1f6184]{width:%?690?%;height:%?86?%;line-height:%?86?%;margin-top:%?60?%;background:var(--view-theme);border-radius:%?43?%;color:#fff;font-size:%?28?%;text-align:center}.animated[data-v-6f1f6184]{-webkit-animation-duration:.4s;animation-duration:.4s}",""]),t.exports=e}}]);