(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-9b5c8d82"],{"2c3e":function(t,e,n){"use strict";var a=n("83ab"),i=n("9f7f").MISSED_STICKY,r=n("c6b6"),o=n("edd0"),c=n("69f3").get,s=RegExp.prototype,l=TypeError;a&&i&&o(s,"sticky",{configurable:!0,get:function(){if(this!==s){if("RegExp"===r(this))return!!c(this).sticky;throw new l("Incompatible receiver, RegExp required")}}})},"4d63":function(t,e,n){"use strict";var a=n("83ab"),i=n("da84"),r=n("e330"),o=n("94ca"),c=n("7156"),s=n("9112"),l=n("241c").f,u=n("3a9b"),d=n("44e7"),f=n("577e"),m=n("90d8"),g=n("9f7f"),p=n("aeb0"),v=n("cb2d"),b=n("d039"),h=n("1a2d"),w=n("69f3").enforce,_=n("2626"),k=n("b622"),x=n("fce3"),y=n("107c"),j=k("match"),O=i.RegExp,S=O.prototype,C=i.SyntaxError,E=r(S.exec),R=r("".charAt),$=r("".replace),D=r("".indexOf),L=r("".slice),V=/^\?<[^\s\d!#%&*+<=>@^][^\s!#%&*+<=>@^]*>/,N=/a/g,M=/a/g,I=(n=new O(N)!==N,g.MISSED_STICKY),q=g.UNSUPPORTED_Y;k=a&&(!n||I||x||y||b((function(){return M[j]=!1,O(N)!==N||O(M)===M||"/a/i"!==String(O(N,"i"))})));if(o("RegExp",k)){for(var A=function(t,e){var n,a,i=u(S,this),r=d(t),o=void 0===e,l=[],g=t;if(!i&&r&&o&&t.constructor===A)return t;if((r||u(S,t))&&(t=t.source,o)&&(e=m(g)),t=void 0===t?"":f(t),e=void 0===e?"":f(e),g=t,r=e=x&&"dotAll"in N&&(n=!!e&&-1<D(e,"s"))?$(e,/s/g,""):e,I&&"sticky"in N&&(a=!!e&&-1<D(e,"y"))&&q&&(e=$(e,/y/g,"")),y&&(t=(o=function(t){for(var e,n=t.length,a=0,i="",r=[],o={},c=!1,s=!1,l=0,u="";a<=n;a++){if("\\"===(e=R(t,a)))e+=R(t,++a);else if("]"===e)c=!1;else if(!c)switch(!0){case"["===e:c=!0;break;case"("===e:E(V,L(t,a+1))&&(a+=2,s=!0),i+=e,l++;continue;case">"===e&&s:if(""===u||h(o,u))throw new C("Invalid capture group name");o[u]=!0,s=!(r[r.length]=[u,l]),u="";continue}s?u+=e:i+=e}return[i,r]}(t))[0],l=o[1]),o=c(O(t,e),i?this:S,A),(n||a||l.length)&&(e=w(o),n&&(e.dotAll=!0,e.raw=A(function(t){for(var e,n=t.length,a=0,i="",r=!1;a<=n;a++)"\\"===(e=R(t,a))?i+=e+R(t,++a):r||"."!==e?("["===e?r=!0:"]"===e&&(r=!1),i+=e):i+="[\\s\\S]";return i}(t),r)),a&&(e.sticky=!0),l.length)&&(e.groups=l),t!==g)try{s(o,"source",""===g?"(?:)":g)}catch(t){}return o},z=l(O),P=0;z.length>P;)p(A,O,z[P++]);(S.constructor=A).prototype=S,v(i,"RegExp",A,{constructor:!0})}_("RegExp")},"61f7":function(t,e,n){"use strict";n.d(e,"a",(function(){return r})),n.d(e,"c",(function(){return s})),n.d(e,"b",(function(){return l}));var a=n("ade3"),i=n("5530");function r(t,e){/(y+)/.test(e)&&(e=e.replace(RegExp.$1,(t.getFullYear()+"").substr(4-RegExp.$1.length)));var n,a,i={"M+":t.getMonth()+1,"d+":t.getDate(),"h+":t.getHours(),"m+":t.getMinutes(),"s+":t.getSeconds()};for(n in i)new RegExp("(".concat(n,")")).test(e)&&(a=i[n]+"",e=e.replace(RegExp.$1,1===RegExp.$1.length?a:("00"+a).substr(a.length)));return e}n("ac1f"),n("00b4"),n("5319"),n("4d63"),n("c607"),n("2c3e"),n("25f0"),n("498a"),n("d3b7"),n("13d5"),n("b64b"),n("99af");var o={min:"%s最小长度为:min",max:"%s最大长度为:max",length:"%s长度必须为:length",range:"%s长度为:range",pattern:"$s格式错误"};function c(t,e){t.message=function(t){return e.replace("%s",t||"")}}function s(t){var e=1<arguments.length&&void 0!==arguments[1]?arguments[1]:{};return Object(i.a)({required:!0,message:t,type:"string"},e)}function l(t){return u.pattern(/(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/,t)}c(s,"请输入%s"),c(l,"%s格式不正确");var u=Object.keys(o).reduce((function(t,e){return t[e]=function(t){var n=1<arguments.length&&void 0!==arguments[1]?arguments[1]:"",r=2<arguments.length&&void 0!==arguments[2]?arguments[2]:{},o="range"===e?{min:t[0],max:t[1]}:Object(a.a)({},e,t);return Object(i.a)(Object(i.a)({message:n.replace(":".concat(e),"range"===e?"".concat(t[0],"-").concat(t[1]):t),type:"string"},o),r)},c(t[e],o[e]),t}),{})},aa0e:function(t,e,n){"use strict";n.r(e);var a=n("c7eb"),i=n("1da1"),r=n("5530"),o=(n("a434"),n("2f62")),c=n("bbbc"),s=n("61f7");r={name:"agent_extra",data:function(){return{grid:{xl:7,lg:7,md:12,sm:24,xs:24},total:0,total2:0,userLists:[],formInline:{uid:0,proportion:0,image:""},FromData:null,loading:!1,current:0,formValidate:{page:1,limit:15,keyword:""},staffModal:!1,clerkReqData:{uid:0,page:1,limit:15},clerkLists:[]}},filters:{formatDate:function(t){if(0!==t)return t=new Date(1e3*t),Object(s.a)(t,"yyyy-MM-dd hh:mm")}},computed:Object(r.a)(Object(r.a)({},Object(o.d)("media",["isMobile"])),{},{labelWidth:function(){return this.isMobile?void 0:"80px"},labelPosition:function(){return this.isMobile?"top":"right"}}),mounted:function(){this.getList()},methods:{userSearchs:function(){this.formValidate.page=1,this.getList()},jump:function(t){this.clerkReqData.uid=t,this.getClerkList()},getClerkList:function(){var t=this;this.clerkReqData.division_type=3,Object(c.d)(this.clerkReqData).then((function(e){t.clerkLists=e.data.list,t.total2=e.data.count,t.staffModal=!0}))},getList:function(){var t=this;this.loading=!0,this.formValidate.division_type=2,Object(c.l)(this.formValidate).then(function(){var e=Object(i.a)(Object(a.a)().mark((function e(n){var i;return Object(a.a)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:i=n.data,t.userLists=i.list,t.total=i.count,t.loading=!1;case 4:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.loading=!1,t.$message.error(e.msg)}))},groupAdd:function(t){var e=this;this.$modalForm(Object(c.a)(t)).then((function(t){e.getList()})).catch((function(t){}))},staffAdd:function(t){var e=this;this.$modalForm(Object(c.m)(t)).then((function(t){e.getList()})).catch((function(t){}))},onchangeIsShow:function(t){var e=this;t={id:t.uid,status:t.division_status};Object(c.g)(t).then(function(){var t=Object(i.a)(Object(a.a)().mark((function t(n){return Object(a.a)().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:e.$message.success(n.msg);case 1:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.$message.error(t.msg)}))},edit:function(t){},del:function(t,e,n){var a=this;e={title:e,method:"DELETE",uid:t.uid,url:"agent/division/del/2/".concat(t.uid)};this.$modalSure(e).then((function(t){a.$message.success(t.msg),a.userLists.splice(n,1)})).catch((function(t){a.$message.error(t.msg)}))}}},n("ed4b"),o=n("2877"),n=Object(o.a)(r,(function(){var t=this,e=t._self._c;return e("div",[e("el-card",{attrs:{bordered:!1,shadow:"never","body-style":{padding:0}}},[e("div",{staticClass:"padding-add"},[e("el-form",{ref:"formValidate",attrs:{model:t.formValidate,"label-width":t.labelWidth,"label-position":t.labelPosition,inline:""},nativeOn:{submit:function(t){t.preventDefault()}}},[e("el-form-item",{attrs:{label:"搜索："}},[e("el-input",{staticClass:"form_content_width",attrs:{clearable:"",placeholder:"请输入姓名、UID"},model:{value:t.formValidate.keyword,callback:function(e){t.$set(t.formValidate,"keyword",e)},expression:"formValidate.keyword"}})],1),e("el-form-item",[e("el-button",{directives:[{name:"db-click",rawName:"v-db-click"}],attrs:{type:"primary"},on:{click:t.userSearchs}},[t._v("查询")])],1)],1)],1)]),e("el-card",{staticClass:"ivu-mt mt16",attrs:{bordered:!1,shadow:"never"}},[e("el-row",{staticClass:"ivu-mt box-wrapper"},[e("el-col",{ref:"rightBox",attrs:{xs:24,sm:24}},[e("el-row",[e("el-col",t._b({},"el-col",t.grid,!1),[e("el-button",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"mr20",attrs:{type:"primary"},on:{click:function(e){return t.groupAdd("0")}}},[t._v("添加代理商")])],1)],1),e("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],ref:"table",staticClass:"mt14",attrs:{data:t.userLists,"highlight-current-row":"","no-formValidate-text":"暂无数据","no-filtered-formValidate-text":"暂无筛选结果"}},[e("el-table-column",{attrs:{label:"用户UID",width:"80"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.uid))])]}}])}),e("el-table-column",{attrs:{label:"头像","min-width":"90"},scopedSlots:t._u([{key:"default",fn:function(t){return[e("div",{directives:[{name:"viewer",rawName:"v-viewer"}],staticClass:"tabBox_img"},[e("img",{directives:[{name:"lazy",rawName:"v-lazy",value:t.row.avatar,expression:"scope.row.avatar"}]})])]}}])}),e("el-table-column",{attrs:{label:"名称","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("div",{staticClass:"acea-row"},[e("div",{staticClass:"ml10",domProps:{textContent:t._s(n.row.division_name)}})])]}}])}),e("el-table-column",{attrs:{label:"分销比例","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(" "+t._s(n.row.division_percent)+"%")])]}}])}),e("el-table-column",{attrs:{label:"员工数量","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.agent_count))])]}}])}),e("el-table-column",{attrs:{label:"截止时间","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.division_end_time))])]}}])}),e("el-table-column",{attrs:{label:"状态","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("el-switch",{attrs:{"active-value":1,"inactive-value":0,value:n.row.division_status,size:"large"},on:{change:function(e){return t.onchangeIsShow(n.row)}},model:{value:n.row.division_status,callback:function(e){t.$set(n.row,"division_status",e)},expression:"scope.row.division_status"}})]}}])}),e("el-table-column",{attrs:{label:"操作",fixed:"right",width:"220"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("a",{directives:[{name:"db-click",rawName:"v-db-click"}],on:{click:function(e){return t.staffAdd(n.row.uid)}}},[t._v("添加员工")]),e("el-divider",{attrs:{direction:"vertical"}}),e("a",{directives:[{name:"db-click",rawName:"v-db-click"}],on:{click:function(e){return t.jump(n.row.uid)}}},[t._v("查看员工")]),e("el-divider",{attrs:{direction:"vertical"}}),e("a",{directives:[{name:"db-click",rawName:"v-db-click"}],on:{click:function(e){return t.groupAdd(n.row.uid)}}},[t._v("编辑")]),e("el-divider",{attrs:{direction:"vertical"}}),e("a",{directives:[{name:"db-click",rawName:"v-db-click"}],on:{click:function(e){return t.del(n.row,"删除代理商",n.$index)}}},[t._v("删除")])]}}])})],1),e("div",{staticClass:"acea-row row-right page"},[t.total?e("pagination",{attrs:{total:t.total,page:t.formValidate.page,limit:t.formValidate.limit},on:{"update:page":function(e){return t.$set(t.formValidate,"page",e)},"update:limit":function(e){return t.$set(t.formValidate,"limit",e)},pagination:t.getList}}):t._e()],1)],1)],1)],1),e("el-dialog",{staticClass:"order_box",attrs:{visible:t.staffModal,title:"员工列表",width:"1000px"},on:{"update:visible":function(e){t.staffModal=e}}},[e("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],ref:"table",staticClass:"mt20",attrs:{data:t.clerkLists,"highlight-current-row":"","no-formValidate-text":"暂无数据","no-filtered-formValidate-text":"暂无筛选结果"}},[e("el-table-column",{attrs:{label:"用户UID",width:"80"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.uid))])]}}])}),e("el-table-column",{attrs:{label:"头像","min-width":"90"},scopedSlots:t._u([{key:"default",fn:function(t){return[e("div",{directives:[{name:"viewer",rawName:"v-viewer"}],staticClass:"tabBox_img"},[e("img",{directives:[{name:"lazy",rawName:"v-lazy",value:t.row.avatar,expression:"scope.row.avatar"}]})])]}}])}),e("el-table-column",{attrs:{label:"姓名","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("div",{staticClass:"acea-row"},[e("i",{directives:[{name:"show",rawName:"v-show",value:"男"===n.row.sex,expression:"scope.row.sex === '男'"}],staticClass:"el-icon-male mr10",staticStyle:{color:"#2db7f5","font-size":"15px"}}),e("i",{directives:[{name:"show",rawName:"v-show",value:"女"===n.row.sex,expression:"scope.row.sex === '女'"}],staticClass:"el-icon-female mr10",staticStyle:{color:"#ed4014","font-size":"15px"}}),e("div",{domProps:{textContent:t._s(n.row.nickname)}})])]}}])}),e("el-table-column",{attrs:{label:"分销比例","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(" "+t._s(n.row.division_percent)+"%")])]}}])})],1),e("div",{staticClass:"acea-row row-right page"},[t.total2?e("pagination",{attrs:{total:t.total2,page:t.clerkReqData.page,limit:t.clerkReqData.limit},on:{"update:page":function(e){return t.$set(t.clerkReqData,"page",e)},"update:limit":function(e){return t.$set(t.clerkReqData,"limit",e)},pagination:t.getClerkList}}):t._e()],1)],1)],1)}),[],!1,null,"34eefe08",null);e.default=n.exports},b592:function(t,e,n){},bbbc:function(t,e,n){"use strict";n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"o",(function(){return o})),n.d(e,"n",(function(){return c})),n.d(e,"h",(function(){return s})),n.d(e,"j",(function(){return l})),n.d(e,"i",(function(){return u})),n.d(e,"p",(function(){return d})),n.d(e,"l",(function(){return f})),n.d(e,"f",(function(){return m})),n.d(e,"a",(function(){return g})),n.d(e,"e",(function(){return p})),n.d(e,"k",(function(){return v})),n.d(e,"d",(function(){return b})),n.d(e,"g",(function(){return h})),n.d(e,"m",(function(){return w})),n("99af");var a=n("6b6c");function i(t){return Object(a.a)({url:"agent/index",method:"get",params:t})}function r(t){return Object(a.a)({url:"agent/spread",method:"PUT",data:t})}function o(t){return Object(a.a)({url:"agent/statistics",method:"get",params:t})}function c(t,e){return Object(a.a)({url:t,method:"get",params:e})}function s(t){return Object(a.a)({url:"agent/look_code",method:"get",params:t})}function l(t){return Object(a.a)({url:"agent/look_xcx_code",method:"get",params:t})}function u(t){return Object(a.a)({url:"agent/look_h5_code",method:"get",params:t})}function d(t){return Object(a.a)({url:"export/userAgent",method:"get",params:t})}function f(t){return Object(a.a)({url:"agent/division/list",method:"get",params:t})}function m(t){return Object(a.a)({url:"agent/division/agent_apply/list",method:"get",params:t})}function g(t){return Object(a.a)({url:"agent/division/agent/create/".concat(t),method:"get"})}function p(t,e){return Object(a.a)({url:"agent/division/examine_apply/".concat(t,"/").concat(e),method:"get"})}function v(t){return Object(a.a)({url:"agent/division/create/".concat(t),method:"get"})}function b(t){return Object(a.a)({url:"agent/division/down_list",method:"get",params:t})}function h(t){return Object(a.a)({url:"agent/division/set_status/".concat(t.status,"/").concat(t.id),method:"put"})}function w(t){return Object(a.a)({url:"agent/division/staff/create/".concat(t),method:"get"})}},c607:function(t,e,n){"use strict";var a=n("83ab"),i=n("fce3"),r=n("c6b6"),o=n("edd0"),c=n("69f3").get,s=RegExp.prototype,l=TypeError;a&&i&&o(s,"dotAll",{configurable:!0,get:function(){if(this!==s){if("RegExp"===r(this))return!!c(this).dotAll;throw new l("Incompatible receiver, RegExp required")}}})},ed4b:function(t,e,n){"use strict";n("b592")}}]);