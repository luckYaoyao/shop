(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-f1b3bf12"],{"55b4":function(t,e,n){"use strict";var r=n("9f90");n.n(r).a},"5b6a":function(t,e,n){"use strict";n.r(e);var r=n("0122"),o=n("c964"),c=n("f3f3"),u=(n("a434"),n("a4d3"),n("e01a"),n("d3b7"),n("d28b"),n("3ca3"),n("ddb0"),n("b636"),n("944a"),n("0c47"),n("23dc"),n("3410"),n("d9e2"),n("159b"),n("b0c0"),n("131a"),n("fb6a"),n("2f62")),a=n("90e7");function i(){/*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */i=function(){return t};var t={},e=Object.prototype,n=e.hasOwnProperty,o=Object.defineProperty||function(t,e,n){t[e]=n.value},c="function"==typeof Symbol?Symbol:{},u=c.iterator||"@@iterator",a=c.asyncIterator||"@@asyncIterator",s=c.toStringTag||"@@toStringTag";function f(t,e,n){return Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}),t[e]}try{f({},"")}catch(e){f=function(t,e,n){return t[e]=n}}function d(t,e,n,r){var c,u,a,i;e=e&&e.prototype instanceof m?e:m,e=Object.create(e.prototype),r=new _(r||[]);return o(e,"_invoke",{value:(c=t,u=n,a=r,i="suspendedStart",function(t,e){if("executing"===i)throw new Error("Generator is already running");if("completed"===i){if("throw"===t)throw e;return L()}for(a.method=t,a.arg=e;;){var n=a.delegate;if(n&&(n=function t(e,n){var r=n.method,o=e.iterator[r];return void 0===o?(n.delegate=null,"throw"===r&&e.iterator.return&&(n.method="return",n.arg=void 0,t(e,n),"throw"===n.method)||"return"!==r&&(n.method="throw",n.arg=new TypeError("The iterator does not provide a '"+r+"' method")),h):(r=l(o,e.iterator,n.arg),"throw"===r.type?(n.method="throw",n.arg=r.arg,n.delegate=null,h):(o=r.arg,o?o.done?(n[e.resultName]=o.value,n.next=e.nextLoc,"return"!==n.method&&(n.method="next",n.arg=void 0),n.delegate=null,h):o:(n.method="throw",n.arg=new TypeError("iterator result is not an object"),n.delegate=null,h)))}(n,a),n)){if(n===h)continue;return n}if("next"===a.method)a.sent=a._sent=a.arg;else if("throw"===a.method){if("suspendedStart"===i)throw i="completed",a.arg;a.dispatchException(a.arg)}else"return"===a.method&&a.abrupt("return",a.arg);if(i="executing",n=l(c,u,a),"normal"===n.type){if(i=a.done?"completed":"suspendedYield",n.arg===h)continue;return{value:n.arg,done:a.done}}"throw"===n.type&&(i="completed",a.method="throw",a.arg=n.arg)}})}),e}function l(t,e,n){try{return{type:"normal",arg:t.call(e,n)}}catch(t){return{type:"throw",arg:t}}}t.wrap=d;var h={};function m(){}function p(){}function g(){}c={};var b=(f(c,u,(function(){return this})),Object.getPrototypeOf),y=(b=b&&b(b(x([]))),b&&b!==e&&n.call(b,u)&&(c=b),g.prototype=m.prototype=Object.create(c));function O(t){["next","throw","return"].forEach((function(e){f(t,e,(function(t){return this._invoke(e,t)}))}))}function v(t,e){var c;o(this,"_invoke",{value:function(o,u){function a(){return new e((function(c,a){!function o(c,u,a,i){var s;c=l(t[c],t,u);if("throw"!==c.type)return(u=(s=c.arg).value)&&"object"==Object(r.a)(u)&&n.call(u,"__await")?e.resolve(u.__await).then((function(t){o("next",t,a,i)}),(function(t){o("throw",t,a,i)})):e.resolve(u).then((function(t){s.value=t,a(s)}),(function(t){return o("throw",t,a,i)}));i(c.arg)}(o,u,c,a)}))}return c=c?c.then(a,a):a()}})}function j(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function w(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function _(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(j,this),this.reset(!0)}function x(t){if(t){var e,r=t[u];if(r)return r.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length))return e=-1,(r=function r(){for(;++e<t.length;)if(n.call(t,e))return r.value=t[e],r.done=!1,r;return r.value=void 0,r.done=!0,r}).next=r}return{next:L}}function L(){return{value:void 0,done:!0}}return o(y,"constructor",{value:p.prototype=g,configurable:!0}),o(g,"constructor",{value:p,configurable:!0}),p.displayName=f(g,s,"GeneratorFunction"),t.isGeneratorFunction=function(t){return t="function"==typeof t&&t.constructor,!!t&&(t===p||"GeneratorFunction"===(t.displayName||t.name))},t.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,g):(t.__proto__=g,f(t,s,"GeneratorFunction")),t.prototype=Object.create(y),t},t.awrap=function(t){return{__await:t}},O(v.prototype),f(v.prototype,a,(function(){return this})),t.AsyncIterator=v,t.async=function(e,n,r,o,c){void 0===c&&(c=Promise);var u=new v(d(e,n,r,o),c);return t.isGeneratorFunction(n)?u:u.next().then((function(t){return t.done?t.value:u.next()}))},O(y),f(y,s,"Generator"),f(y,u,(function(){return this})),f(y,"toString",(function(){return"[object Generator]"})),t.keys=function(t){var e,n=Object(t),r=[];for(e in n)r.push(e);return r.reverse(),function t(){for(;r.length;){var e=r.pop();if(e in n)return t.value=e,t.done=!1,t}return t.done=!0,t}},t.values=x,_.prototype={constructor:_,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(w),!t)for(var e in this)"t"===e.charAt(0)&&n.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=void 0)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function r(n,r){return u.type="throw",u.arg=t,e.next=n,r&&(e.method="next",e.arg=void 0),!!r}for(var o=this.tryEntries.length-1;0<=o;--o){var c=this.tryEntries[o],u=c.completion;if("root"===c.tryLoc)return r("end");if(c.tryLoc<=this.prev){var a=n.call(c,"catchLoc"),i=n.call(c,"finallyLoc");if(a&&i){if(this.prev<c.catchLoc)return r(c.catchLoc,!0);if(this.prev<c.finallyLoc)return r(c.finallyLoc)}else if(a){if(this.prev<c.catchLoc)return r(c.catchLoc,!0)}else{if(!i)throw new Error("try statement without catch or finally");if(this.prev<c.finallyLoc)return r(c.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;0<=r;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var c=o;break}}var u=(c=c&&("break"===t||"continue"===t)&&c.tryLoc<=e&&e<=c.finallyLoc?null:c)?c.completion:{};return u.type=t,u.arg=e,c?(this.method="next",this.next=c.finallyLoc,h):this.complete(u)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),h},finish:function(t){for(var e=this.tryEntries.length-1;0<=e;--e){var n=this.tryEntries[e];if(n.finallyLoc===t)return this.complete(n.completion,n.afterLoc),w(n),h}},catch:function(t){for(var e=this.tryEntries.length-1;0<=e;--e){var n,r,o=this.tryEntries[e];if(o.tryLoc===t)return"throw"===(n=o.completion).type&&(r=n.arg,w(o)),r}throw new Error("illegal catch attempt")},delegateYield:function(t,e,n){return this.delegate={iterator:x(t),resultName:e,nextLoc:n},"next"===this.method&&(this.arg=void 0),h}},t}c={data:function(){return{grid:{xl:7,lg:7,md:12,sm:24,xs:24},formValidate:{keyword:"",page:1,limit:20},total:0,loading:!1,columns:[{title:"编号",key:"id",width:120},{title:"浏览器语言识别码",key:"code",minWidth:150},{title:"语言说明",key:"name",minWidth:180},{title:"关联语言",key:"link_lang",minWidth:180},{title:"操作",slot:"action",fixed:"right",width:100}],tabList:[],code:null}},computed:Object(c.a)(Object(c.a)({},Object(u.d)("media",["isMobile"])),{},{labelWidth:function(){return this.isMobile?void 0:80},labelPosition:function(){return this.isMobile?"top":"left"}}),mounted:function(){this.getList()},methods:{add:function(){var t=this;this.$modalForm(Object(a.M)(0)).then((function(){return t.getList()}))},edit:function(t){var e=this;this.$modalForm(Object(a.M)(t.id)).then((function(){return e.getList()}))},del:function(t,e,n){var r=this;e={title:e,num:n,url:"setting/lang_country/del/".concat(t.id),method:"DELETE",ids:""};this.$modalSure(e).then((function(t){r.$Message.success(t.msg),r.tabList.splice(n,1)})).catch((function(t){r.$Message.error(t.msg)}))},selChange:function(){this.formValidate.page=1,this.getList()},getList:function(){var t=this;this.loading=!0,Object(a.N)(this.formValidate).then(function(){var e=Object(o.a)(i().mark((function e(n){return i().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.loading=!1,t.tabList=n.data.list,t.total=n.data.count;case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.loading=!1,t.$Message.error(e.msg)}))},pageChange:function(t){this.formValidate.page=t,this.getList()}}},n("55b4"),u=n("2877"),n=Object(u.a)(c,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("Card",{staticClass:"ivu-mt mb10",attrs:{bordered:!1,"dis-hover":""}},[n("Form",{ref:"formValidate",attrs:{model:t.formValidate,"label-width":t.labelWidth,"label-position":t.labelPosition},nativeOn:{submit:function(t){t.preventDefault()}}},[n("Row",{attrs:{gutter:24,type:"flex"}},[n("Col",{attrs:{span:"24"}},[n("FormItem",{attrs:{label:"搜索："}},[n("div",{staticClass:"acea-row row-middle"},[n("Input",{staticStyle:{width:"30%"},attrs:{search:"","enter-button":"",placeholder:"请输入语言Code","element-id":"name"},on:{"on-search":t.selChange},model:{value:t.formValidate.keyword,callback:function(e){t.$set(t.formValidate,"keyword",e)},expression:"formValidate.keyword"}})],1)])],1)],1)],1)],1),n("Card",{attrs:{bordered:!1,"dis-hover":""}},[n("Row",{attrs:{type:"flex"}},[n("Col",t._b({},"Col",t.grid,!1),[n("Button",{attrs:{type:"primary",icon:"md-add"},on:{click:t.add}},[t._v("添加语言地区")])],1)],1),n("Table",{ref:"table",staticClass:"ivu-mt mt25",attrs:{columns:t.columns,data:t.tabList,loading:t.loading,"no-data-text":"暂无数据","no-filtered-data-text":"暂无筛选结果"},scopedSlots:t._u([{key:"action",fn:function(e){var r=e.row,o=e.index;return[n("a",{on:{click:function(e){return t.edit(r)}}},[t._v("编辑")]),n("Divider",{attrs:{type:"vertical"}}),n("a",{on:{click:function(e){return t.del(r,"删除地区语言",o)}}},[t._v("删除")])]}}])}),n("div",{staticClass:"acea-row row-right page"},[n("Page",{attrs:{total:t.total,current:t.formValidate.page,"show-elevator":"","show-total":"","page-size":t.formValidate.limit},on:{"on-change":t.pageChange}})],1)],1)],1)}),[],!1,null,"8280ab64",null),e.default=n.exports},"90e7":function(t,e,n){"use strict";n.d(e,"u",(function(){return o})),n.d(e,"k",(function(){return c})),n.d(e,"Jb",(function(){return u})),n.d(e,"Ib",(function(){return a})),n.d(e,"j",(function(){return i})),n.d(e,"fb",(function(){return s})),n.d(e,"Nb",(function(){return f})),n.d(e,"c",(function(){return d})),n.d(e,"d",(function(){return l})),n.d(e,"Y",(function(){return h})),n.d(e,"eb",(function(){return m})),n.d(e,"kb",(function(){return p})),n.d(e,"C",(function(){return g})),n.d(e,"Wb",(function(){return b})),n.d(e,"qb",(function(){return y})),n.d(e,"pb",(function(){return O})),n.d(e,"z",(function(){return v})),n.d(e,"A",(function(){return j})),n.d(e,"m",(function(){return w})),n.d(e,"gb",(function(){return _})),n.d(e,"n",(function(){return x})),n.d(e,"jb",(function(){return L})),n.d(e,"ib",(function(){return k})),n.d(e,"hb",(function(){return E})),n.d(e,"lb",(function(){return T})),n.d(e,"nb",(function(){return G})),n.d(e,"V",(function(){return P})),n.d(e,"ob",(function(){return C})),n.d(e,"Ab",(function(){return S})),n.d(e,"H",(function(){return V})),n.d(e,"zb",(function(){return F})),n.d(e,"q",(function(){return N})),n.d(e,"o",(function(){return M})),n.d(e,"p",(function(){return q})),n.d(e,"r",(function(){return $})),n.d(e,"s",(function(){return I})),n.d(e,"rb",(function(){return U})),n.d(e,"Vb",(function(){return W})),n.d(e,"sb",(function(){return D})),n.d(e,"Qb",(function(){return A})),n.d(e,"tb",(function(){return J})),n.d(e,"bb",(function(){return R})),n.d(e,"Sb",(function(){return z})),n.d(e,"cb",(function(){return B})),n.d(e,"Z",(function(){return Y})),n.d(e,"ab",(function(){return H})),n.d(e,"S",(function(){return K})),n.d(e,"B",(function(){return Q})),n.d(e,"F",(function(){return X})),n.d(e,"E",(function(){return Z})),n.d(e,"w",(function(){return tt})),n.d(e,"G",(function(){return et})),n.d(e,"Ub",(function(){return nt})),n.d(e,"t",(function(){return rt})),n.d(e,"Rb",(function(){return ot})),n.d(e,"Tb",(function(){return ct})),n.d(e,"y",(function(){return ut})),n.d(e,"D",(function(){return at})),n.d(e,"x",(function(){return it})),n.d(e,"v",(function(){return st})),n.d(e,"R",(function(){return ft})),n.d(e,"h",(function(){return dt})),n.d(e,"e",(function(){return lt})),n.d(e,"f",(function(){return ht})),n.d(e,"Kb",(function(){return mt})),n.d(e,"Lb",(function(){return pt})),n.d(e,"Mb",(function(){return gt})),n.d(e,"mb",(function(){return bt})),n.d(e,"Bb",(function(){return yt})),n.d(e,"T",(function(){return Ot})),n.d(e,"Db",(function(){return vt})),n.d(e,"Cb",(function(){return jt})),n.d(e,"Eb",(function(){return wt})),n.d(e,"Fb",(function(){return _t})),n.d(e,"Gb",(function(){return xt})),n.d(e,"Hb",(function(){return Lt})),n.d(e,"Ob",(function(){return kt})),n.d(e,"Pb",(function(){return Et})),n.d(e,"U",(function(){return Tt})),n.d(e,"g",(function(){return Gt})),n.d(e,"ub",(function(){return Pt})),n.d(e,"xb",(function(){return Ct})),n.d(e,"a",(function(){return St})),n.d(e,"b",(function(){return Vt})),n.d(e,"vb",(function(){return Ft})),n.d(e,"yb",(function(){return Nt})),n.d(e,"wb",(function(){return Mt})),n.d(e,"l",(function(){return qt})),n.d(e,"W",(function(){return $t})),n.d(e,"X",(function(){return It})),n.d(e,"db",(function(){return Ut})),n.d(e,"P",(function(){return Wt})),n.d(e,"O",(function(){return Dt})),n.d(e,"J",(function(){return At})),n.d(e,"I",(function(){return Jt})),n.d(e,"K",(function(){return Rt})),n.d(e,"N",(function(){return zt})),n.d(e,"M",(function(){return Bt})),n.d(e,"Q",(function(){return Yt})),n.d(e,"L",(function(){return Ht})),n.d(e,"i",(function(){return Kt})),n("99af");var r=n("6b6c");function o(t){return Object(r.a)({url:"setting/config/header_basics",method:"get",params:t})}function c(t,e){return Object(r.a)({url:e,method:"get",params:t})}function u(t){return Object(r.a)({url:t.url,method:"get",params:t.data})}function a(){return Object(r.a)({url:"notify/sms/temp/create",method:"get"})}function i(t){return Object(r.a)({url:"serve/login",method:"post",data:t})}function s(t){return Object(r.a)({url:"serve/modify",method:"post",data:t})}function f(t){return Object(r.a)({url:"serve/update_phone",method:"post",data:t})}function d(t){return Object(r.a)({url:"serve/captcha",method:"post",data:t})}function l(t){return Object(r.a)({url:"serve/checkCode",method:"post",data:t})}function h(t){return Object(r.a)({url:"serve/register",method:"post",data:t})}function m(){return Object(r.a)({url:"serve/info",method:"get"})}function p(t){return Object(r.a)({url:"serve/sms/sign",method:"PUT",data:t})}function g(t){return Object(r.a)({url:"app/wechat/kefu/login/".concat(t),method:"get"})}function b(t){return Object(r.a)({url:"app/wechat/speechcraft",method:"get",params:t})}function y(t){return Object(r.a)({url:"app/wechat/speechcraft/".concat(t,"/edit"),method:"get"})}function O(){return Object(r.a)({url:"app/wechat/speechcraft/create",method:"get"})}function v(t){return Object(r.a)({url:"app/feedback",method:"get",params:t})}function j(t){return Object(r.a)({url:"app/feedback/".concat(t,"/edit"),method:"get"})}function w(){return Object(r.a)({url:"serve/export_all",method:"get"})}function _(){return Object(r.a)({url:"serve/open",method:"get"})}function x(t){return Object(r.a)({url:"serve/export_temp",method:"get",params:t})}function L(t){return Object(r.a)({url:"serve/record",method:"get",params:t})}function k(t){return Object(r.a)({url:"serve/open",method:"get",params:t})}function E(t){return Object(r.a)({url:"serve/opn_express",method:"post",data:t})}function T(t){return Object(r.a)({url:"serve/sms/open",method:"get",params:t})}function G(t){return Object(r.a)({url:"serve/meal_list",method:"get",params:t})}function P(t){return Object(r.a)({url:"serve/pay_meal",method:"post",data:t})}function C(t){return Object(r.a)({url:"notify/sms/record",method:"get",params:t})}function S(){return Object(r.a)({url:"merchant/store",method:"GET"})}function V(){return Object(r.a)({url:"merchant/store/address",method:"GET"})}function F(t){return Object(r.a)({url:"merchant/store/".concat(t.id),method:"POST",data:t})}function N(t){return Object(r.a)({url:"freight/express",method:"get",params:t})}function M(){return Object(r.a)({url:"/freight/express/create",method:"get"})}function q(t){return Object(r.a)({url:"freight/express/".concat(t,"/edit"),method:"get"})}function $(t){return Object(r.a)({url:"freight/express/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function I(){return Object(r.a)({url:"freight/express/sync_express",method:"get"})}function U(){return Object(r.a)({url:"app/wechat/speechcraftcate",method:"get"})}function W(){return Object(r.a)({url:"app/wechat_qrcode/cate/list",method:"get"})}function D(){return Object(r.a)({url:"app/wechat/speechcraftcate/create",method:"get"})}function A(t){return Object(r.a)({url:"app/wechat_qrcode/cate/create/".concat(t),method:"get"})}function J(t){return Object(r.a)({url:"app/wechat/speechcraftcate/".concat(t,"/edit"),method:"get"})}function R(t){return Object(r.a)({url:"setting/role",method:"GET",params:t})}function z(t){return Object(r.a)({url:"app/wechat_qrcode/list",method:"GET",params:t})}function B(t){return Object(r.a)({url:"setting/role/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function Y(t){return Object(r.a)({url:"setting/role/".concat(t.id),method:"post",data:t})}function H(t){return Object(r.a)({url:"setting/role/".concat(t,"/edit"),method:"get"})}function K(){return Object(r.a)({url:"setting/role/create",method:"get"})}function Q(t){return Object(r.a)({url:"app/wechat/kefu",method:"get",params:t})}function X(t){return Object(r.a)({url:"app/wechat/kefu/create",method:"get",params:t})}function Z(){return Object(r.a)({url:"app/wechat/kefu/add",method:"get"})}function tt(t){return Object(r.a)({url:"app/wechat/kefu",method:"post",data:t})}function et(t){return Object(r.a)({url:"app/wechat/kefu/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function nt(t){return Object(r.a)({url:"app/wechat_qrcode/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function rt(t){return Object(r.a)({url:"app/wechat_qrcode/user_list/".concat(t.id),method:"get",params:t})}function ot(t){return Object(r.a)({url:"app/wechat_qrcode/info/".concat(t),method:"get"})}function ct(t,e){return Object(r.a)({url:"app/wechat_qrcode/save/".concat(t),method:"post",data:e})}function ut(t){return Object(r.a)({url:"app/wechat/kefu/".concat(t,"/edit"),method:"GET"})}function at(t,e){return Object(r.a)({url:"app/wechat/kefu/record/".concat(e),method:"GET",params:t})}function it(t){return Object(r.a)({url:"app/wechat/kefu/chat_list",method:"GET",params:t})}function st(){return Object(r.a)({url:"notify/sms/is_login",method:"GET"})}function ft(){return Object(r.a)({url:"notify/sms/logout",method:"GET"})}function dt(t){return Object(r.a)({url:"setting/city/list/".concat(t),method:"get"})}function lt(t){return Object(r.a)({url:"setting/city/add/".concat(t),method:"get"})}function ht(t){return Object(r.a)({url:"setting/city/".concat(t,"/edit"),method:"get"})}function mt(t){return Object(r.a)({url:"setting/shipping_templates/list",method:"get",params:t})}function pt(t){return Object(r.a)({url:"setting/shipping_templates/city_list",method:"get"})}function gt(t,e){return Object(r.a)({url:"setting/shipping_templates/save/".concat(t),method:"post",data:e})}function bt(t){return Object(r.a)({url:"setting/shipping_templates/".concat(t,"/edit"),method:"get"})}function yt(){return Object(r.a)({url:"merchant/store/get_header",method:"get"})}function Ot(t){return Object(r.a)({url:"merchant/store",method:"get",params:t})}function vt(t,e){return Object(r.a)({url:"merchant/store/set_show/".concat(t,"/").concat(e),method:"put"})}function jt(t){return Object(r.a)({url:"merchant/store/get_info/".concat(t),method:"get"})}function wt(t){return Object(r.a)({url:"merchant/store_staff",method:"get",params:t})}function _t(){return Object(r.a)({url:"merchant/store_staff/create",method:"get"})}function xt(t){return Object(r.a)({url:"merchant/store_staff/".concat(t,"/edit"),method:"get"})}function Lt(t,e){return Object(r.a)({url:"merchant/store_staff/set_show/".concat(t,"/").concat(e),method:"put"})}function kt(t){return Object(r.a)({url:"merchant/verify_order",method:"get",params:t})}function Et(t){return Object(r.a)({url:"merchant/verify/spread_info/".concat(t),method:"get"})}function Tt(){return Object(r.a)({url:"merchant/store_list",method:"get"})}function Gt(){return Object(r.a)({url:"setting/city/clean_cache",method:"get"})}function Pt(){return Object(r.a)({url:"system/config/storage/config",method:"get"})}function Ct(t){return Object(r.a)({url:"system/config/storage/config",method:"post",data:t})}function St(t){return Object(r.a)({url:"system/config/storage/form/".concat(t),method:"get"})}function Vt(t){return Object(r.a)({url:"system/config/storage/create/".concat(t),method:"get"})}function Ft(t){return Object(r.a)({url:"system/config/storage",method:"get",params:t})}function Nt(t){return Object(r.a)({url:"system/config/storage/synch/".concat(t),method:"put"})}function Mt(t){return Object(r.a)({url:"system/config/storage/status/".concat(t),method:"put"})}function qt(t){return Object(r.a)({url:"system/config/storage/domain/".concat(t),method:"get"})}function $t(){return Object(r.a)({url:"setting/config_list/31",method:"get"})}function It(t){return Object(r.a)({url:"setting/config/save_basics",method:"post",data:t})}function Ut(t){return Object(r.a)({url:"system/config/storage/save_type/".concat(t),method:"get"})}function Wt(t){return Object(r.a)({url:"setting/lang_type/list",method:"get",params:t})}function Dt(t){return Object(r.a)({url:"setting/lang_type/form/".concat(t),method:"get"})}function At(t){return Object(r.a)({url:"setting/lang_code/list",method:"get",params:t})}function Jt(t){return Object(r.a)({url:"setting/lang_code/info",method:"get",params:t})}function Rt(t){return Object(r.a)({url:"setting/lang_code/save",method:"post",data:t})}function zt(t){return Object(r.a)({url:"setting/lang_country/list",method:"get",params:t})}function Bt(t){return Object(r.a)({url:"setting/lang_country/form/".concat(t),method:"get"})}function Yt(t,e){return Object(r.a)({url:"setting/lang_type/status/".concat(t,"/").concat(e),method:"put"})}function Ht(t){return Object(r.a)({url:"setting/lang_code/translate",method:"post",data:t})}function Kt(t){return Object(r.a)({url:"system/crud",method:"post",data:t})}},"9f90":function(t,e,n){}}]);