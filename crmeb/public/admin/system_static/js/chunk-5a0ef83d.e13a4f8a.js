(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-5a0ef83d"],{"0353":function(e,t,i){"use strict";i("c605")},"3dda":function(e,t){},"433f":function(e,t,i){e.exports=i.p+"system_static/img/sw.3ef10e8b.jpg"},"59da":function(e,t,i){e.exports=i.p+"system_static/img/logo-dark.b9962944.png"},c605:function(e,t,i){},d2a8:function(e,t,i){"use strict";i("ee8e")},d666:function(e,t,i){"use strict";i.r(t);var n=i("c7eb"),a=i("1da1"),r=(i("b0c0"),i("99af"),i("ac1f"),i("5319"),i("42e3")),s=i("3dda"),o=(s=i.n(s),i("d708")),c=i("c276"),u=i("d044"),l=i.n(u);u={mixins:[s.a],data:function(){return{fullWidth:document.documentElement.clientWidth,swiperOption:{pagination:".swiper-pagination",autoplay:!0},modals:!1,autoLogin:!0,imgcode:"",formInline:{username:"",password:"",code:""},ruleInline:{username:[{required:!0,message:"请输入用户名",trigger:"blur"}],password:[{required:!0,message:"请输入密码",trigger:"blur"}],code:[{required:!0,message:"请输入验证码",trigger:"blur"}]},errorNum:0,jigsaw:null,login_logo:"",swiperList:[],defaultSwiperList:i("433f"),loginType:0,codeKey:"",scanTime:"",rxpired:!1,isMobile:!1,version:"",isScan:!1,timeNum:0,copyright:"",copyrightImg:i("59da")}},created:function(){var e=this,t=(Object(r.p)().then((function(t){e.version=t.data.version,e.copyright=t.data.copyright,t.data.site_name&&(document.title=t.data.site_name),t.data.copyrightImg&&(e.copyrightImg=t.data.copyrightImg)})),this.isMobile=this.$store.state.media.isMobile,this);top!=window&&(top.location.href=location.href),document.onkeydown=function(e){"login"===t.$route.name&&13===window.event.keyCode&&t.handleSubmit("formInline")},window.addEventListener("resize",this.handleResize)},watch:{fullWidth:function(e){var t;this.timer||(this.screenWidth=e,this.timer=!0,t=this,setTimeout((function(){t.timer=!1}),400))},$route:function(e){this.captchas()}},mounted:function(){this.$nextTick((function(){})),this.captchas()},methods:{bindScan:function(){this.isScan||(this.isScan=!0,this.getSanCodeKey()),this.loginType=1},creatQrCode:function(){var e="".concat(window.location.protocol,"//").concat(window.location.host,"/pages/users/scan_login/index?key=").concat(this.codeKey);new l.a(this.$refs.qrCodeUrl,{text:e,width:160,height:160,colorDark:"#000000",colorLight:"#ffffff",correctLevel:l.a.CorrectLevel.H})},closeModel:function(){var e=this;Object(r.a)({account:this.formInline.username,password:this.formInline.password,imgcode:this.formInline.code}).then(function(){var t=Object(a.a)(Object(n.a)().mark((function t(i){var a;return Object(n.a)().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(a=e.getExpiresTime(i.data.exp_time),Object(c.o)("kefu_uuid",i.data.kefuInfo.uid,a),Object(c.o)("kefu_token",i.data.token,a),Object(c.o)("kefu_expires_time",i.data.exp_time,a),Object(c.o)("kefuInfo",i.data.kefuInfo,a),e.$store.commit("kefu/setInfo",i.data.kefuInfo),e.$store.state.media.isMobile)return t.abrupt("return",e.$router.replace({path:e.$route.query.redirect||"/kefu/mobile_list"}));t.next=10;break;case 10:return t.abrupt("return",e.$router.replace({path:e.$route.query.redirect||"/kefu/pc_list"}));case 11:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){t=void 0===t?{}:t,e.errorNum++,e.captchas(),e.$message.error(t.msg||"登录失败"),e.jigsaw&&e.jigsaw.reset()}))},getExpiresTime:function(e){var t=Math.round(new Date/1e3);return parseFloat(parseFloat(parseFloat((e-t)/60)/60)/24)},closefail:function(){this.jigsaw&&this.jigsaw.reset(),this.$message.error("校验错误")},handleResize:function(e){this.fullWidth=document.documentElement.clientWidth},captchas:function(){this.imgcode=o.a.apiBaseURL+"/captcha_pro?"+Date.parse(new Date)},handleSubmit:function(e){var t=this;this.$refs[e].validate((function(e){e&&t.closeModel()}))},getSanCodeKey:function(){var e=this;Object(r.l)().then((function(t){e.codeKey=t.data.key,e.creatQrCode(),e.scanTime=setInterval((function(){e.timeNum++,60<=e.timeNum?(e.timeNum=0,window.clearInterval(e.scanTime),e.rxpired=!0):e.getScanStatus()}),1e3)})).catch((function(t){e.timeNum=0,window.clearInterval(e.scanTime),e.rxpired=!0,e.$message.error(t.msg)}))},getScanStatus:function(){var e=this;Object(r.H)(this.codeKey).then(function(){var t=Object(a.a)(Object(n.a)().mark((function t(i){var a;return Object(n.a)().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(0==i.data.status&&(e.timeNum=0,window.clearInterval(e.scanTime),e.rxpired=!0),i.data.status,3!=i.data.status)t.next=15;else{if(window.clearInterval(e.scanTime),a=e.getExpiresTime(i.data.exp_time),Object(c.o)("kefu_uuid",i.data.kefuInfo.uid,a),Object(c.o)("kefu_token",i.data.token,a),Object(c.o)("kefu_expires_time",i.data.exp_time,a),Object(c.o)("kefuInfo",i.data.kefuInfo,a),e.$store.commit("kefu/setInfo",i.data.kefuInfo),e.$store.state.media.isMobile)return t.abrupt("return",e.$router.replace({path:e.$route.query.redirect||"/kefu/mobile_list"}));t.next=14}break;case 14:return t.abrupt("return",e.$router.replace({path:e.$route.query.redirect||"/kefu/pc_list"}));case 15:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.$message.error(t.msg),e.timeNum=0,window.clearInterval(e.scanTime),e.rxpired=!0}))},bindRefresh:function(){this.$refs.qrCodeUrl.innerHTML="",this.rxpired=!1,this.getSanCodeKey()}},beforeCreate:function(){},beforeDestroy:function(){this.timeNum=0,this.$refs.qrCodeUrl.innerHTML="",window.clearInterval(this.scanTime),window.removeEventListener("resize",this.handleResize)}},i("d2a8"),i("0353"),s=i("2877"),s=Object(s.a)(u,(function(){var e=this,t=e._self._c;return t("div",{staticClass:"wrapper-box"},[t("div",{staticClass:"page-account kf"},[t("div",{staticClass:"content"},[t("img",{attrs:{src:e.copyrightImg,alt:""}}),e._m(0)]),t("div",{staticClass:"container",class:[768<e.fullWidth?"containerSamll":"containerBig"]},[t("div",{staticClass:"index_from page-account-container"},[t("div",{style:{display:e.loginType?"none":"block"}},[e._m(1),t("el-form",{ref:"formInline",attrs:{model:e.formInline,rules:e.ruleInline},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.handleSubmit("formInline")}}},[t("el-form-item",{staticClass:"mb20",attrs:{prop:"username"}},[t("el-input",{attrs:{type:"text",placeholder:"请输入用户名",size:"large"},model:{value:e.formInline.username,callback:function(t){e.$set(e.formInline,"username",t)},expression:"formInline.username"}})],1),t("el-form-item",{staticClass:"mb20",attrs:{prop:"password"}},[t("el-input",{attrs:{type:"password",placeholder:"请输入密码",size:"large"},model:{value:e.formInline.password,callback:function(t){e.$set(e.formInline,"password",t)},expression:"formInline.password"}})],1),t("el-form-item",[t("el-button",{staticClass:"btn",attrs:{type:"primary",size:"large"},on:{click:function(t){return e.handleSubmit("formInline")}}},[e._v("登录 ")])],1)],1),e.isMobile?e._e():t("div",{staticClass:"qh_box",on:{click:e.bindScan}},[t("span",{staticClass:"iconfont iconerweima2"})])],1),t("div",{style:{display:e.loginType?"block":"none"}},[e._m(2),t("div",{staticClass:"code-box"},[t("div",{ref:"qrCodeUrl",staticClass:"qrcode"}),t("div",{directives:[{name:"show",rawName:"v-show",value:e.rxpired,expression:"rxpired"}],staticClass:"rxpired-box"},[t("p",[e._v("已过期")]),t("el-button",{attrs:{type:"primary"},on:{click:e.bindRefresh}},[e._v("点击刷新")])],1)]),t("div",{staticClass:"qh_box",on:{click:function(t){e.loginType=0}}},[t("span",{staticClass:"iconfont iconzhanghaomima"})])])])])]),e.copyright?t("div",{staticClass:"foot-box"},[e._v(e._s(e.copyright))]):t("div",{staticClass:"foot-box"},[e._v("\n    Copyright © 2014-2023 "),t("a",{attrs:{href:"https://www.crmeb.com",target:"_blank"}},[e._v(e._s(e.version))])])])}),[function(){var e=this._self._c;return e("div",{staticClass:"desc"},[e("p",{staticClass:"tit"},[this._v("让客户服务如此简单")]),e("p",{staticClass:"kefu"},[this._v("专业客服系统"),e("br"),this._v("助力企业打造一流的服务体验")])])},function(){var e=this._self._c;return e("div",{staticClass:"page-account-top"},[e("div",{staticClass:"page-account-top-logo"},[this._v("客服登录")])])},function(){var e=this._self._c;return e("div",{staticClass:"page-account-top"},[e("div",{staticClass:"page-account-top-logo"},[this._v("微信扫码登录")])])}],!1,null,"0d315875",null);t.default=s.exports},ee8e:function(e,t,i){}}]);