(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/user_pwd_edit/index"],{"16c6":function(t,e,n){},5333:function(t,e,n){"use strict";n.r(e);var r=n("7248"),i=n("dfa7");for(var u in i)"default"!==u&&function(t){n.d(e,t,(function(){return i[t]}))}(u);n("a4b4");var o,a=n("f0c5"),c=Object(a["a"])(i["default"],r["b"],r["c"],!1,null,null,null,!1,r["a"],o);e["default"]=c.exports},7248:function(t,e,n){"use strict";var r;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return u})),n.d(e,"a",(function(){return r}));var i=function(){var t=this,e=t.$createElement,n=(t._self._c,t.$t("当前手机号")),r=t.$t("设置新密码"),i=t.$t("确认新密码"),u=t.$t("填写验证码"),o=t.$t("确认修改");t.$mp.data=Object.assign({},{$root:{m0:n,m1:r,m2:i,m3:u,m4:o}})},u=[]},a4b4:function(t,e,n){"use strict";var r=n("16c6"),i=n.n(r);i.a},a80c:function(t,e,n){"use strict";(function(t){n("d5c5");r(n("66fd"));var e=r(n("5333"));function r(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},c769:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r=f(n("a34a")),i=f(n("3f5d")),u=n("e5e8"),o=n("cff9"),a=n("1118"),c=n("26cb"),s=f(n("cf74"));function f(t){return t&&t.__esModule?t:{default:t}}function l(t,e,n,r,i,u,o){try{var a=t[u](o),c=a.value}catch(s){return void n(s)}a.done?e(c):Promise.resolve(c).then(r,i)}function d(t){return function(){var e=this,n=arguments;return new Promise((function(r,i){var u=t.apply(e,n);function o(t){l(u,r,i,o,a,"next",t)}function a(t){l(u,r,i,o,a,"throw",t)}o(void 0)}))}}var h=function(){n.e("components/Authorize").then(function(){return resolve(n("4a3a"))}.bind(null,n)).catch(n.oe)},p=function(){n.e("pages/users/components/verify/verify").then(function(){return resolve(n("2f7a"))}.bind(null,n)).catch(n.oe)},v={mixins:[i.default,s.default],components:{authorize:h,Verify:p},data:function(){return{userInfo:{},phone:"",password:"",captcha:"",qr_password:"",isAuto:!1,isShowAuth:!1,key:""}},computed:(0,c.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,e){t&&this.getUserInfo()},deep:!0}},onLoad:function(){var t=this;this.isLogin?(this.getUserInfo(),(0,u.verifyCode)().then((function(e){t.$set(t,"key",e.data.key)}))):(0,a.toLogin)()},methods:{onLoadFun:function(t){this.getUserInfo()},authColse:function(t){this.isShowAuth=t},getUserInfo:function(){var t=this;(0,o.getUserInfo)().then((function(e){var n=e.data.phone,r=n.substr(0,3)+"****"+n.substr(7);t.$set(t,"userInfo",e.data),t.phone=r}))},code:function(){var t=this;return d(r.default.mark((function e(){var n;return r.default.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(n=t,n.userInfo.phone){e.next=3;break}return e.abrupt("return",n.$util.Tips({title:n.$t("手机号码不存在,无法发送验证码！")}));case 3:t.$refs.verify.show();case 4:case"end":return e.stop()}}),e)})))()},success:function(t){var e=this;return d(r.default.mark((function n(){var i;return r.default.wrap((function(n){while(1)switch(n.prev=n.next){case 0:return i=e,e.$refs.verify.hide(),n.next=5,(0,o.registerVerify)({phone:i.userInfo.phone,type:"reset",key:i.key,captchaType:"blockPuzzle",captchaVerification:t.captchaVerification}).then((function(t){i.$util.Tips({title:t.msg})})).catch((function(t){return i.$util.Tips({title:t})}));case 5:case"end":return n.stop()}}),n)})))()},editPwd:function(t){var e=this,n=t.detail.value.password,r=t.detail.value.qr_password,i=t.detail.value.captcha;return n?r!=n?e.$util.Tips({title:e.$t("两次输入的密码不一致！")}):i?void(0,u.phoneRegisterReset)({account:e.userInfo.phone,captcha:i,password:n}).then((function(t){return e.$util.Tips({title:t.msg},{tab:3,url:1})})).catch((function(t){return e.$util.Tips({title:t})})):e.$util.Tips({title:e.$t("请输入验证码")}):e.$util.Tips({title:e.$t("请输入新密码")})}}};e.default=v},dfa7:function(t,e,n){"use strict";n.r(e);var r=n("c769"),i=n.n(r);for(var u in r)"default"!==u&&function(t){n.d(e,t,(function(){return r[t]}))}(u);e["default"]=i.a}},[["a80c","common/runtime","common/vendor"]]]);