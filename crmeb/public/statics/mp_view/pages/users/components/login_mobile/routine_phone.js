require('../../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/components/login_mobile/routine_phone"],{"44eb":function(t,e,n){"use strict";var i=n("ba4a"),o=n.n(i);o.a},4966:function(t,e,n){"use strict";n.r(e);var i=n("7f5c"),o=n("8392");for(var a in o)["default"].indexOf(a)<0&&function(t){n.d(e,t,(function(){return o[t]}))}(a);n("44eb");var u=n("f0c5"),s=Object(u["a"])(o["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],void 0);e["default"]=s.exports},"7f5c":function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){}));var i=function(){var t=this.$createElement,e=(this._self._c,this.isPhoneBox?this.$t("获取授权"):null),n=this.isPhoneBox?this.$t("获取微信的手机号授权"):null,i=this.isPhoneBox?this.$t("获取微信手机号"):null;this.$mp.data=Object.assign({},{$root:{m0:e,m1:n,m2:i}})},o=[]},8392:function(t,e,n){"use strict";n.r(e);var i=n("c8f1"),o=n.n(i);for(var a in i)["default"].indexOf(a)<0&&function(t){n.d(e,t,(function(){return i[t]}))}(a);e["default"]=o.a},ba4a:function(t,e,n){},c8f1:function(t,e,n){"use strict";(function(t){var i=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=i(n("69b2")),a=n("d1ea"),u=n("4e39"),s=getApp(),c={name:"routine_phone",props:{isPhoneBox:{type:Boolean,default:!1},logoUrl:{type:String,default:""},authKey:{type:String,default:""}},data:function(){return{keyCode:"",account:"",codeNum:"",isStatus:!1}},mounted:function(){},methods:{getphonenumber:function(e){var n=this;t.showLoading({title:this.$t("正在登录中")}),o.default.getCode().then((function(t){n.getUserPhoneNumber(e.detail.encryptedData,e.detail.iv,t)})).catch((function(e){t.hideLoading()}))},getUserPhoneNumber:function(e,n,i){var o=this;(0,u.routineBindingPhone)({encryptedData:e,iv:n,code:i,spread_spid:s.globalData.spid,spread_code:s.globalData.code,key:this.authKey}).then((function(t){var e=t.data.expires_time-o.$Cache.time();o.$store.commit("LOGIN",{token:t.data.token,time:e}),o.$emit("loginSuccess",{isStatus:!0,new_user:t.data.userInfo.new_user})})).catch((function(e){t.hideLoading()}))},getUserInfo:function(){var e=this,n=this;(0,a.getUserInfo)().then((function(i){t.hideLoading(),n.userInfo=i.data,n.$store.commit("SETUID",i.data.uid),n.$store.commit("UPDATE_USERINFO",i.data),n.isStatus=!0,e.close(i.data.new_user||0)}))},close:function(t){this.$emit("close",{isStatus:this.isStatus,new_user:t})}}};e.default=c}).call(this,n("543d")["default"])}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/users/components/login_mobile/routine_phone-create-component',
    {
        'pages/users/components/login_mobile/routine_phone-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("4966"))
        })
    },
    [['pages/users/components/login_mobile/routine_phone-create-component']]
]);
