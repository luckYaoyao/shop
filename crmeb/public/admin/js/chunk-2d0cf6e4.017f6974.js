(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0cf6e4"],{"644d":function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("div",{staticClass:"i-layout-page-header"},[i("div",{staticClass:"i-layout-page-header"},[i("span",{staticClass:"ivu-page-header-title"},[t._v(t._s(t.$route.meta.title))])])]),i("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[i("Row",{staticClass:"mb20",attrs:{type:"flex"}},[i("Col",{attrs:{span:"24"}},[i("Button",{staticClass:"mr10",attrs:{type:"primary",icon:"md-add"},on:{click:t.add}},[t._v("发布版本")])],1)],1),i("Table",{attrs:{columns:t.columns1,data:t.tableList,loading:t.loading,"highlight-row":"","no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果"},scopedSlots:t._u([{key:"version",fn:function(e){var a=e.row;return[a.is_new?i("Poptip",{attrs:{trigger:"hover",placement:"top-start",content:"当前为最新线上版本!"}},[i("Icon",{staticStyle:{"margin-right":"10px"},attrs:{size:"16",type:"ios-bookmark",color:"red"}})],1):i("Icon",{staticStyle:{"margin-right":"10px"},attrs:{size:"16",type:"ios-bookmark",color:"white"}}),i("span",[t._v(t._s(a.version)+" ")])]}},{key:"platform",fn:function(e){var a=e.row;return[i("span",[t._v(t._s(1===a.platform?"安卓":"苹果"))])]}},{key:"is_force",fn:function(e){var a=e.row;return[i("span",[t._v(t._s(1===a.is_force?"强制":"非强制"))])]}},{key:"action",fn:function(e){var a=e.row;e.index;return[i("a",{on:{click:function(e){return t.edit(a)}}},[t._v("编辑")])]}}])}),i("div",{staticClass:"acea-row row-right page"},[i("Page",{attrs:{total:t.total,"show-elevator":"","show-total":"","page-size":t.tableFrom.limit},on:{"on-change":t.pageChange}})],1)],1)],1)},s=[],n=i("f3f3"),o=(i("a434"),i("2f62")),r=i("8593"),l={name:"index",computed:Object(n["a"])(Object(n["a"])(Object(n["a"])({},Object(o["e"])("media",["isMobile"])),Object(o["e"])("userLevel",["categoryId"])),{},{labelWidth:function(){return this.isMobile?void 0:80},labelPosition:function(){return this.isMobile?"top":"left"}}),data:function(){return{verModal:!1,total:20,tableFrom:{page:1,limit:15},columns1:[{title:"版本号",slot:"version",width:80},{title:"平台类型",slot:"platform",align:"center",minWidth:120},{title:"升级信息",key:"info",minWidth:60},{title:"是否强制",slot:"is_force",minWidth:120},{title:"发布日期",key:"add_time",minWidth:120},{title:"下载地址",key:"url",minWidth:120},{title:"操作",slot:"action",align:"center",minWidth:50}],loading:!1,tableList:[]}},created:function(){this.getList()},methods:{submitFail:function(){this.getList()},record:function(t){this.rows=t,this.modals3=!0,this.isChat=!0,this.getListRecord()},add:function(){var t=this;this.$modalForm(Object(r["db"])(0)).then((function(e){t.getList()}))},getList:function(){var t=this;this.loading=!0,Object(r["eb"])().then((function(e){t.tableList=e.data.list,t.total=e.data.count,t.loading=!1})).catch((function(e){t.$Message.error(e),t.loading=!1}))},edit:function(t){var e=this;this.$modalForm(Object(r["db"])(t.id)).then((function(t){e.getList()}))},del:function(t,e,i){var a=this,s={title:e,num:i,url:"app/version/del/".concat(t.id),method:"DELETE",ids:""};this.$modalSure(s).then((function(t){a.$Message.success(t.msg),a.tableList.splice(i,1)})).catch((function(t){a.$Message.error(t.msg)}))},handleSubmit:function(t){var e=this;this.$refs[t].validate((function(t){t?e.$Message.success("成功!"):e.$Message.error("失败!")}))},handleReset:function(t){this.$refs[t].resetFields()},pageChange:function(){}}},c=l,d=i("2877"),u=Object(d["a"])(c,a,s,!1,null,"40872dd8",null);e["default"]=u.exports}}]);