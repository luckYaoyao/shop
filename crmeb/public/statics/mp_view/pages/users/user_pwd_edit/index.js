(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/user_pwd_edit/index"],{"0e0a":function(t,e,n){},3836:function(t,e,n){"use strict";var r;n.d(e,"b",(function(){return u})),n.d(e,"c",(function(){return i})),n.d(e,"a",(function(){return r}));var u=function(){var t=this,e=t.$createElement;t._self._c},i=[]},6256:function(t,e,n){"use strict";var r=n("0e0a"),u=n.n(r);u.a},"7f31":function(t,e,n){"use strict";n.r(e);var r=n("af0f"),u=n.n(r);for(var i in r)"default"!==i&&function(t){n.d(e,t,(function(){return r[t]}))}(i);e["default"]=u.a},"85e4":function(t,e,n){"use strict";(function(t){n("6e38");r(n("66fd"));var e=r(n("c7a4"));function r(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},af0f:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r=f(n("a34a")),u=f(n("f875")),i=n("7fe6"),o=n("3474"),a=n("c6cd"),s=n("26cb"),c=f(n("c83f"));function f(t){return t&&t.__esModule?t:{default:t}}function l(t,e,n,r,u,i,o){try{var a=t[i](o),s=a.value}catch(c){return void n(c)}a.done?e(s):Promise.resolve(s).then(r,u)}function d(t){return function(){var e=this,n=arguments;return new Promise((function(r,u){var i=t.apply(e,n);function o(t){l(i,r,u,o,a,"next",t)}function a(t){l(i,r,u,o,a,"throw",t)}o(void 0)}))}}var h=function(){n.e("components/Authorize").then(function(){return resolve(n("420f"))}.bind(null,n)).catch(n.oe)},p={mixins:[u.default,c.default],components:{authorize:h},data:function(){return{userInfo:{},phone:"",password:"",captcha:"",qr_password:"",isAuto:!1,isShowAuth:!1,key:""}},computed:(0,s.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,e){t&&this.getUserInfo()},deep:!0}},onLoad:function(){var t=this;this.isLogin?(this.getUserInfo(),(0,i.verifyCode)().then((function(e){t.$set(t,"key",e.data.key)}))):(0,a.toLogin)()},methods:{onLoadFun:function(t){this.getUserInfo()},authColse:function(t){this.isShowAuth=t},getUserInfo:function(){var t=this;(0,o.getUserInfo)().then((function(e){var n=e.data.phone,r=n.substr(0,3)+"****"+n.substr(7);t.$set(t,"userInfo",e.data),t.phone=r}))},code:function(){var t=this;return d(r.default.mark((function e(){var n;return r.default.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(n=t,n.userInfo.phone){e.next=3;break}return e.abrupt("return",n.$util.Tips({title:"手机号码不存在,无法发送验证码！"}));case 3:return e.next=5,(0,i.registerVerify)(n.userInfo.phone,"reset",n.key).then((function(t){n.$util.Tips({title:t.msg}),n.sendCode()})).catch((function(t){return n.$util.Tips({title:t})}));case 5:case"end":return e.stop()}}),e)})))()},editPwd:function(t){var e=this,n=t.detail.value.password,r=t.detail.value.qr_password,u=t.detail.value.captcha;return n?r!=n?e.$util.Tips({title:"两次输入的密码不一致！"}):u?void(0,i.phoneRegisterReset)({account:e.userInfo.phone,captcha:u,password:n}).then((function(t){return e.$util.Tips({title:t.msg},{tab:3,url:1})})).catch((function(t){return e.$util.Tips({title:t})})):e.$util.Tips({title:"请输入验证码"}):e.$util.Tips({title:"请输入新密码"})}}};e.default=p},c7a4:function(t,e,n){"use strict";n.r(e);var r=n("3836"),u=n("7f31");for(var i in u)"default"!==i&&function(t){n.d(e,t,(function(){return u[t]}))}(i);n("6256");var o,a=n("f0c5"),s=Object(a["a"])(u["default"],r["b"],r["c"],!1,null,null,null,!1,r["a"],o);e["default"]=s.exports}},[["85e4","common/runtime","common/vendor"]]]);