(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-7453b76c"],{9406:function(e,t,i){"use strict";i.r(t),i("d3b7"),i("159b"),i("caad"),i("e9c4");var r=i("c24f"),a={name:"list",data:function(){return{thead:[{title:"ID",key:"id"},{title:"会员名",key:"title"},{title:"有限期（天）",key:"vip_day",render:function(e,t){return e("span",-1===t.row.vip_day?"永久":t.row.vip_day)}},{title:"原价",key:"price"},{title:"优惠价",key:"pre_price"},{title:"排序",key:"sort"},{title:"是否开启",slot:"is_del"},{title:"操作",slot:"action"}],tbody:[],loading:!1,modal:!1,rowEdit:{},rowModelType:"编辑",rule:[{type:"hidden",field:"id",value:""},{type:"hidden",field:"type",value:""},{type:"input",field:"title",title:"会员名",value:"",props:{disabled:!1,placeholder:"输入会员名"},validate:[{type:"string",max:10,min:1,message:"请输入长度为1-10的名称",requred:!0}]},{type:"InputNumber",field:"vip_day",title:"有限期（天）",value:null,props:{precision:0,disabled:!1,type:"text",placeholder:"输入有限期"},validate:[{type:"number",max:1e6,min:0,message:"最大只能输入1000000,最小为0",requred:!0}]},{type:"InputNumber",field:"price",title:"原价",value:null,props:{min:0,disabled:!1,placeholder:"输入原价"},validate:[{type:"number",max:1e6,min:0,message:"最大只能输入1000000,最小为0",requred:!0}]},{type:"InputNumber",field:"pre_price",title:"优惠价",value:null,props:{min:0,disabled:!1,placeholder:"输入优惠价"},validate:[{type:"number",max:1e6,min:0,message:"最大只能输入1000000,最小为0",requred:!0}]},{type:"InputNumber",field:"sort",title:"排序",value:0,props:{min:1,max:1e6,disabled:!1,placeholder:"请输入排序"},validate:[{type:"number",max:1e6,min:0,message:"最大只能输入1000000,最小为0",requred:!0}]}],fapi:{id:"",pre_price:null,price:null,sort:null,title:"",type:"owner",vip_day:null}}},created:function(){this.getMemberShip()},mounted:function(){},methods:{onchangeIsShow:function(e){var t=this;e={id:e.id,is_del:e.is_del};Object(r.u)(e).then((function(e){t.$Message.success(e.msg),t.getMemberShip()})).catch((function(e){t.$Message.error(e.msg)}))},cancel:function(){this.fapi.resetFields()},getMemberShip:function(){var e=this;this.loading=!0,Object(r.V)().then((function(t){e.loading=!1;t=t.data;var i=t.count;t=t.list;e.total=i,e.tbody=t})).catch((function(t){e.loading=!1,e.$Message.error(t.msg)}))},addType:function(){this.rowEdit.id=0,this.rowModelType="新增",this.rule[1].value="owner",this.rule[3].props.disabled=!1,this.rule[5].props.disabled=!1,this.rowEdit.title="",this.modal=!0},del:function(e,t,i){var r=this;t={title:t,num:i,url:"user/member_ship/delete/".concat(e.id),method:"DELETE",ids:""};this.$modalSure(t).then((function(e){r.$Message.success(e.msg),r.getMemberShip()})).catch((function(e){r.$Message.error(e.msg)}))},editType:function(e){this.rule.forEach((function(t){for(var i in e)e.hasOwnProperty(i)&&t.field===i&&("vip_day"===i&&(-1===e[i]||"永久"==e[i]?(t.type="input",t.props.disabled=!0,e[i]="永久",t.validate=[{type:"string",message:"",requred:!0}]):(t.props.disabled=!1,t.props.min=1,t.validate=[{type:"number",max:1e6,min:0,message:"最大只能输入1000000,最小为0",requred:!0}])),["price"].includes(i)&&(e[i]=parseFloat(e[i])),["pre_price"].includes(i)&&(e[i]=parseFloat(e[i]),e[i]?t.props.disabled=!1:t.props.disabled=!0),t.value=e[i])})),this.rowModelType="编辑",this.rowEdit=JSON.parse(JSON.stringify(e)),this.modal=!0},onSubmit:function(e){var t=this;Object(r.z)(this.rowEdit.id,e).then((function(e){t.modal=!1,t.$Message.success(e.msg),t.getMemberShip(),t.cancel()})).catch((function(e){t.$Message.error(e.msg)}))}}};i("ab7e"),i=i("2877"),i=Object(i.a)(a,(function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",[i("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[i("Button",{attrs:{type:"primary"},on:{click:e.addType}},[e._v("添加类型")]),i("Table",{staticClass:"mt25",attrs:{columns:e.thead,data:e.tbody,loading:e.loading,"highlight-row":"","no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果"},scopedSlots:e._u([{key:"is_del",fn:function(t){var r=t.row;return t.index,[i("i-switch",{attrs:{value:r.is_del,"true-value":0,"false-value":1,size:"large"},on:{"on-change":function(t){return e.onchangeIsShow(r)}},model:{value:r.is_del,callback:function(t){e.$set(r,"is_del",t)},expression:"row.is_del"}},[i("span",{attrs:{slot:"open"},slot:"open"},[e._v("启用")]),i("span",{attrs:{slot:"close"},slot:"close"},[e._v("禁用")])])]}},{key:"action",fn:function(t){var r=t.row,a=t.index;return[i("a",{attrs:{href:"javascript:"},on:{click:function(t){return e.editType(r)}}},[e._v("编辑")]),"free"!==r.type&&"ever"!==r.type?i("Divider",{attrs:{type:"vertical"}}):e._e(),"free"!==r.type&&"ever"!==r.type?i("a",{attrs:{href:"javascript:"},on:{click:function(t){return e.del(r,"删除类型",a)}}},[e._v("删除")]):e._e()]}}])})],1),i("Modal",{attrs:{title:""+e.rowModelType+(e.rowEdit&&e.rowEdit.title)+"会员","footer-hide":""},on:{"on-cancel":e.cancel},model:{value:e.modal,callback:function(t){e.modal=t},expression:"modal"}},[i("form-create",{attrs:{rule:e.rule},on:{submit:e.onSubmit},model:{value:e.fapi,callback:function(t){e.fapi=t},expression:"fapi"}})],1)],1)}),[],!1,null,"3f8a53f6",null);t.default=i.exports},ab7e:function(e,t,i){"use strict";var r=i("e5cd");i.n(r).a},e5cd:function(e,t,i){}}]);