(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3ebf11fb"],{"4b65":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"i-layout-page-header"},[n("div",{staticClass:"i-layout-page-header"},[n("span",{staticClass:"ivu-page-header-title"},[t._v(t._s(t.$route.meta.title))])])]),n("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[n("Form",{attrs:{"label-width":80},nativeOn:{submit:function(t){t.preventDefault()}}},[n("FormItem",{attrs:{label:"协议名称："}},[n("Input",{model:{value:t.agreement.title,callback:function(e){t.$set(t.agreement,"title",e)},expression:"agreement.title"}})],1),n("FormItem",{attrs:{label:"协议内容："}},[n("WangEditor",{attrs:{content:t.agreement.content},on:{editorContent:t.getEditorContent}})],1),n("FormItem",{attrs:{label:"开启状态："}},[n("i-switch",{attrs:{size:"large","true-value":1,"false-value":0},model:{value:t.agreement.status,callback:function(e){t.$set(t.agreement,"status",e)},expression:"agreement.status"}},[n("span",{attrs:{slot:"open"},slot:"open"},[t._v("开启")]),n("span",{attrs:{slot:"close"},slot:"close"},[t._v("关闭")])])],1),n("FormItem",[n("Button",{attrs:{type:"primary"},on:{click:t.memberAgreementSave}},[t._v("保存")])],1)],1),t.spinShow?n("Spin",{attrs:{fix:""}}):t._e()],1)],1)},s=[],i=n("a069"),r=n("c24f"),o={components:{WangEditor:i["a"]},data:function(){return{ueConfig:{autoHeightEnabled:!1,initialFrameHeight:500,initialFrameWidth:"100%",UEDITOR_HOME_URL:"/admin/UEditor/",serverUrl:""},id:0,agreement:{title:"",content:"",status:1},spinShow:!1}},created:function(){this.memberAgreement()},methods:{getEditorContent:function(t){this.agreement.content=t},memberAgreement:function(){var t=this;this.spinShow=!0,Object(r["r"])().then((function(e){t.spinShow=!1;var n=e.data,a=n.title,s=n.content,i=n.status,r=n.id;t.agreement.title=a,t.agreement.content=s,t.agreement.status=i,t.id=r})).catch((function(e){t.$Message.error(e),t.spinShow=!1}))},memberAgreementSave:function(){var t=this;this.$Spin.show(),Object(r["s"])(this.id,this.agreement).then((function(e){t.$Spin.hide(),t.$Message.success("保存成功"),t.memberAgreement()})).catch((function(e){t.$Spin.hide(),t.$Message.error(e)}))}}},c=o,l=(n("defb"),n("2877")),m=Object(l["a"])(c,a,s,!1,null,"67db297a",null);e["default"]=m.exports},"6f8d":function(t,e,n){},defb:function(t,e,n){"use strict";var a=n("6f8d"),s=n.n(a);s.a}}]);