(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-37c962e4"],{"277f":function(t,e,n){"use strict";n.d(e,"b",(function(){return r})),n.d(e,"a",(function(){return c})),n.d(e,"c",(function(){return i})),n.d(e,"d",(function(){return o})),n.d(e,"e",(function(){return u})),n("99af");var a=n("6b6c");function r(t){return Object(a.a)({url:"setting/notification/index?type=".concat(t),method:"get"})}function c(t,e){return Object(a.a)({url:"setting/notification/info?id=".concat(t,"&type=").concat(e),method:"get"})}function i(t){return Object(a.a)({url:"setting/notification/save",method:"post",data:t})}function o(t,e,n){return Object(a.a)({url:"setting/notification/set_status/".concat(t,"/").concat(e,"/").concat(n),method:"put"})}function u(t){return Object(a.a)({url:"setting/notification/not_form/".concat(t),method:"get"})}},"4fe3":function(t,e,n){"use strict";n("58c1")},"58c1":function(t,e,n){},aa0a:function(t,e,n){"use strict";n.r(e),n("d3b7"),n("25f0"),n("b0c0"),n("14d9"),n("d81d"),n("a434");var a=n("277f"),r=n("b562"),c={data:function(){return{modalTitle:"",notificationModal:!1,headerList:[{label:"会员通知",value:"1"},{label:"平台通知",value:"2"},{label:"自定义通知",value:"3"}],levelLists:[],currentTab:"1",loading:!1,formData:{}}},created:function(){this.changeTab(this.currentTab)},methods:{changeSwitch:function(t,e,n){var r=this;Object(a.d)(n,e[n],e.id).then((function(t){r.$message.success(t.msg)})).catch((function(t){r.$message.error(t.msg)}))},notificationForm:function(t){var e=this;this.$modalForm(Object(a.e)(t)).then((function(){return e.changeTab()}))},changeTab:function(){var t=this;Object(a.b)(this.currentTab).then((function(e){t.levelLists=e.data}))},routineTemplate:function(){var t=this;Object(r.n)().then((function(e){t.$message.success(e.msg),t.changeTab(t.currentTab)})).catch((function(e){t.$message.error(e.msg)}))},wechatTemplate:function(){var t=this;Object(r.w)().then((function(e){t.$message.success(e.msg),t.changeTab(t.currentTab)})).catch((function(e){t.$message.error(e.msg)}))},changeStatus:function(){},notice:function(){},setting:function(t){this.$router.push({path:this.$routeProStr+"/setting/notification/notificationEdit?id="+t.id})},getData:function(t,e,n){var r=this;this.formData={},Object(a.a)(e.id,n).then((function(e){t.map((function(t,n){r.formData[t]=e.data[t]})),r.formData.type=n,r.notificationModal=!0}))},del:function(t,e,n){var a=this;e={title:e,num:n,url:"setting/notification/del_not/".concat(t.id),method:"DELETE",ids:""};this.$modalSure(e).then((function(t){a.$message.success(t.msg),a.levelLists.splice(n,1)})).catch((function(t){a.$message.error(t.msg)}))}}};n("4fe3"),n=n("2877"),n=Object(n.a)(c,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"message"},[e("el-card",{staticClass:"ivu-mt",attrs:{bordered:!1,shadow:"never","body-style":{padding:"0 20px 20px"}}},[e("div",[e("el-tabs",{on:{"tab-click":t.changeTab},model:{value:t.currentTab,callback:function(e){t.currentTab=e},expression:"currentTab"}},t._l(t.headerList,(function(t,n){return e("el-tab-pane",{key:n,attrs:{label:t.label,name:t.value.toString()}})})),1)],1),1==t.currentTab?e("el-row",{staticClass:"mb14"},[e("el-col",[e("el-button",{directives:[{name:"auth",rawName:"v-auth",value:["app-wechat-template-sync"],expression:"['app-wechat-template-sync']"},{name:"db-click",rawName:"v-db-click"}],attrs:{type:"primary"},on:{click:t.routineTemplate}},[t._v("同步小程序订阅消息")]),e("el-button",{directives:[{name:"auth",rawName:"v-auth",value:["app-wechat-template-sync"],expression:"['app-wechat-template-sync']"},{name:"db-click",rawName:"v-db-click"}],attrs:{type:"primary"},on:{click:t.wechatTemplate}},[t._v("同步微信模版消息")])],1)],1):t._e(),3==t.currentTab?e("el-row",{staticClass:"mb14"},[e("el-col",[e("el-button",{directives:[{name:"db-click",rawName:"v-db-click"}],attrs:{type:"primary"},on:{click:function(e){return t.notificationForm(0)}}},[t._v("添加通知")])],1)],1):t._e(),1==t.currentTab?e("el-alert",{attrs:{type:"warning",closable:!1}},[e("template",{slot:"title"},[e("p",{staticClass:"alert_title"},[t._v("小程序订阅消息")]),t._v("\n        登录微信小程序后台，基本设置，服务类目增加《生活服务 > 百货/超市/便利店》 (否则同步小程序订阅消息会报错)"),e("br"),t._v("\n        同步小程序订阅消息，是在小程序后台未添加订阅消息模板的前提下使用的，会新增一个模板消息并把信息同步过来，并新本项目数据库。"),e("br"),e("br"),e("p",{staticClass:"alert_title"},[t._v("微信模板消息")]),t._v("\n        登录微信公众号后台，选择模板消息，在账号详情下的服务类目中手动设置服务类目，《生活服务 >\n        百货/超市/便利店》(否则同步模板消息不成功)"),e("br"),t._v("\n        同步公众号模板消息，同步公众号模板会删除公众号后台现有的模板，并重新添加新的模板，然后同步信息到数据库，如果多个项目使用同一个公众号的模板，请谨慎操作。\n      ")])],2):t._e(),e("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],ref:"table",staticClass:"mt14",attrs:{data:t.levelLists,"highlight-current-row":"","no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果"}},[e("el-table-column",{attrs:{label:"ID",width:"80"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.id))])]}}])}),e("el-table-column",{attrs:{label:"通知类型","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.name))])]}}])}),e("el-table-column",{attrs:{label:"通知场景说明","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.title))])]}}])}),e("el-table-column",{attrs:{label:"站内信","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[0!==n.row.is_system?e("el-switch",{attrs:{"active-value":1,"inactive-value":2,value:n.row.is_system,size:"large",disabled:0==n.row.is_system},on:{change:function(e){return t.changeSwitch(e,n.row,"is_system")}},model:{value:n.row.is_system,callback:function(e){t.$set(n.row,"is_system",e)},expression:"scope.row.is_system"}}):e("div",[t._v("-")])]}}])}),e("el-table-column",{attrs:{label:"公众号模板","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[0!==n.row.is_wechat?e("el-switch",{attrs:{"active-value":1,"inactive-value":2,value:n.row.is_wechat,size:"large",disabled:0==n.row.is_wechat},on:{change:function(e){return t.changeSwitch(e,n.row,"is_wechat")}},model:{value:n.row.is_wechat,callback:function(e){t.$set(n.row,"is_wechat",e)},expression:"scope.row.is_wechat"}}):e("div",[t._v("-")])]}}])}),e("el-table-column",{attrs:{label:"发送短信","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[0!==n.row.is_sms?e("el-switch",{attrs:{"active-value":1,"inactive-value":2,value:n.row.is_sms,size:"large"},on:{change:function(e){return t.changeSwitch(e,n.row,"is_sms")}},model:{value:n.row.is_sms,callback:function(e){t.$set(n.row,"is_sms",e)},expression:"scope.row.is_sms"}}):e("div",[t._v("-")])]}}])}),1!=t.currentTab?e("el-table-column",{attrs:{label:"企业微信","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[0!==n.row.is_ent_wechat?e("el-switch",{attrs:{"active-value":1,"inactive-value":2,value:n.row.is_ent_wechat,size:"large"},on:{change:function(e){return t.changeSwitch(e,n.row,"is_ent_wechat")}},model:{value:n.row.is_ent_wechat,callback:function(e){t.$set(n.row,"is_ent_wechat",e)},expression:"scope.row.is_ent_wechat"}}):e("div",[t._v("-")])]}}],null,!1,965402819)}):t._e(),1==t.currentTab||3==t.currentTab?e("el-table-column",{attrs:{label:"小程序订阅","min-width":"130"},scopedSlots:t._u([{key:"default",fn:function(n){return[0!==n.row.is_routine?e("el-switch",{attrs:{"active-value":1,"inactive-value":2,value:n.row.is_routine,size:"large",disabled:0==n.row.is_routine},on:{change:function(e){return t.changeSwitch(e,n.row,"is_routine")}},model:{value:n.row.is_routine,callback:function(e){t.$set(n.row,"is_routine",e)},expression:"scope.row.is_routine"}}):e("div",[t._v("-")])]}}],null,!1,2258607342)}):t._e(),e("el-table-column",{attrs:{label:"操作",fixed:"right",width:3==t.currentTab?130:70},scopedSlots:t._u([{key:"default",fn:function(n){return[e("a",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"setting btn",on:{click:function(e){return t.setting(n.row)}}},[t._v("设置")]),3==t.currentTab?[e("el-divider",{attrs:{direction:"vertical"}}),e("a",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"setting btn",on:{click:function(e){return t.notificationForm(n.row.id)}}},[t._v("编辑")]),e("el-divider",{attrs:{direction:"vertical"}}),e("a",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"setting btn",on:{click:function(e){return t.del(n.row,"删除",n.$index)}}},[t._v("删除")])]:t._e()]}}])})],1)],1)],1)}),[],!1,null,"6c39d62e",null);e.default=n.exports},b562:function(t,e,n){"use strict";n.d(e,"n",(function(){return r})),n.d(e,"w",(function(){return c})),n.d(e,"s",(function(){return i})),n.d(e,"a",(function(){return o})),n.d(e,"i",(function(){return u})),n.d(e,"j",(function(){return s})),n.d(e,"k",(function(){return l})),n.d(e,"f",(function(){return d})),n.d(e,"g",(function(){return f})),n.d(e,"h",(function(){return h})),n.d(e,"t",(function(){return m})),n.d(e,"v",(function(){return b})),n.d(e,"u",(function(){return p})),n.d(e,"z",(function(){return w})),n.d(e,"x",(function(){return v})),n.d(e,"y",(function(){return _})),n.d(e,"r",(function(){return g})),n.d(e,"p",(function(){return k})),n.d(e,"q",(function(){return y})),n.d(e,"o",(function(){return T})),n.d(e,"c",(function(){return j})),n.d(e,"b",(function(){return O})),n.d(e,"e",(function(){return S})),n.d(e,"d",(function(){return x})),n.d(e,"m",(function(){return $})),n.d(e,"l",(function(){return E})),n("99af");var a=n("6b6c");function r(){return Object(a.a)({url:"app/routine/syncSubscribe",method:"GET"})}function c(){return Object(a.a)({url:"app/wechat/syncSubscribe",method:"GET"})}function i(t){return Object(a.a)({url:"app/wechat/menu",method:"get"})}function o(t){return Object(a.a)({url:"app/wechat/menu",method:"post",data:t})}function u(t){return Object(a.a)({url:t.url,method:"post",data:t.key})}function s(t){return Object(a.a)({url:"app/routine/download",method:"post",data:t})}function l(){return Object(a.a)({url:"app/routine/info",method:"get"})}function d(t){return Object(a.a)({url:"app/wechat/keyword",method:"get",params:t})}function f(t){return Object(a.a)({url:"app/wechat/keyword/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function h(t,e){return Object(a.a)({url:t,method:"get",params:e.key})}function m(t){return Object(a.a)({url:"/app/wechat/news",method:"POST",data:t})}function b(t){return Object(a.a)({url:"app/wechat/news",method:"GET",params:t})}function p(t){return Object(a.a)({url:"app/wechat/news/".concat(t),method:"GET"})}function w(){return Object(a.a)({url:"app/wechat/tag",method:"GET"})}function v(){return Object(a.a)({url:"app/wechat/tag/create",method:"GET"})}function _(t){return Object(a.a)({url:"app/wechat/tag/".concat(t,"/edit"),method:"GET"})}function g(){return Object(a.a)({url:"app/wechat/group",method:"GET"})}function k(){return Object(a.a)({url:"app/wechat/group/create",method:"GET"})}function y(t){return Object(a.a)({url:"app/wechat/group/".concat(t,"/edit"),method:"GET"})}function T(t){return Object(a.a)({url:"app/wechat/action",method:"GET",params:t})}function j(t){return Object(a.a)({url:"app/wechat/code_reply/".concat(t),method:"GET"})}function O(){return Object(a.a)({url:"setting/city/full_list",method:"GET"})}function S(t){return Object(a.a)({url:"app/kefu/auto_reply/list",method:"get",params:t})}function x(t){return Object(a.a)({url:"app/kefu/auto_reply/form/"+t,method:"get"})}function $(t){return Object(a.a)({url:"app/routine/scheme_list",method:"get",params:t})}function E(t){return Object(a.a)({url:"app/routine/scheme_form/".concat(t),method:"get"})}}}]);