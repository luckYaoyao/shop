(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-99bfdaa2"],{"0b9c":function(t,e,a){},"6f83":function(t,e,a){"use strict";var s=a("0b9c"),n=a.n(s);n.a},"6fd3":function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("Modal",{attrs:{scrollable:"",title:"商品规格",width:"950"},on:{"on-cancel":t.onCancel},model:{value:t.modal,callback:function(e){t.modal=e},expression:"modal"}},[a("Form",{ref:"formDynamic",staticClass:"attrFrom",attrs:{model:t.formDynamic,rules:t.rules,"label-width":110,"label-position":"right"},nativeOn:{submit:function(t){t.preventDefault()}}},[a("Row",{attrs:{gutter:24}},[a("Col",{attrs:{span:"24"}},[a("Col",{staticClass:"mb15",attrs:{span:"8"}},[a("FormItem",{attrs:{label:"规格模板名称：",prop:"rule_name"}},[a("Input",{attrs:{placeholder:"请输入标题名称",maxlength:20},model:{value:t.formDynamic.rule_name,callback:function(e){t.$set(t.formDynamic,"rule_name",e)},expression:"formDynamic.rule_name"}})],1)],1)],1),t._l(t.formDynamic.spec,(function(e,s){return a("Col",{key:s,staticClass:"noForm",attrs:{span:"23"}},[a("FormItem",[a("div",{staticClass:"acea-row row-middle"},[a("span",{staticClass:"mr5"},[t._v(t._s(e.value))]),a("Icon",{attrs:{type:"ios-close-circle"},on:{click:function(e){return t.handleRemove(s)}}})],1),a("div",{staticClass:"rulesBox"},[t._l(e.detail,(function(s,n){return a("Tag",{key:n,attrs:{type:"dot",closable:"",color:"primary",name:s},on:{"on-close":function(a){return t.handleRemove2(e.detail,n)}}},[t._v(t._s(s))])})),a("Input",{staticStyle:{width:"200px"},attrs:{search:"","enter-button":"添加",placeholder:"请输入属性名称"},on:{"on-search":function(a){return t.createAttr(e.detail.attrsVal,s)}},model:{value:e.detail.attrsVal,callback:function(a){t.$set(e.detail,"attrsVal",a)},expression:"item.detail.attrsVal"}})],2)])],1)})),t.isBtn?a("Col",{staticClass:"mt10",attrs:{span:"24"}},[a("Col",{staticClass:"mr15",attrs:{span:"8"}},[a("FormItem",{attrs:{label:"规格名称："}},[a("Input",{attrs:{placeholder:"请输入规格"},model:{value:t.attrsName,callback:function(e){t.attrsName=e},expression:"attrsName"}})],1)],1),a("Col",{staticClass:"mr20",attrs:{span:"8"}},[a("FormItem",{attrs:{label:"规格值："}},[a("Input",{attrs:{placeholder:"请输入规格值"},model:{value:t.attrsVal,callback:function(e){t.attrsVal=e},expression:"attrsVal"}})],1)],1),a("Col",{attrs:{span:"2"}},[a("Button",{attrs:{type:"primary"},on:{click:t.createAttrName}},[t._v("确定")])],1),a("Col",{attrs:{span:"2"}},[a("Button",{on:{click:t.offAttrName}},[t._v("取消")])],1)],1):t._e(),t.spinShow?a("Spin",{attrs:{size:"large",fix:""}}):t._e()],2),t.isBtn?t._e():a("Button",{staticClass:"ml95 mt10",attrs:{type:"primary",icon:"md-add"},on:{click:t.addBtn}},[t._v("添加新规格")])],1),a("div",{attrs:{slot:"footer"},slot:"footer"},[a("Button",{attrs:{type:"primary",loading:t.modal_loading},on:{click:function(e){return t.handleSubmit("formDynamic")}}},[t._v("确定")])],1)],1)},n=[],r=(a("7f7f"),a("c4c8")),i={name:"addAttr",data:function(){return{spinShow:!1,modal_loading:!1,grid:{xl:3,lg:3,md:12,sm:24,xs:24},modal:!1,index:1,rules:{rule_name:[{required:!0,message:"请输入规格名称",trigger:"blur"}]},formDynamic:{rule_name:"",spec:[]},attrsName:"",attrsVal:"",formDynamicNameData:[],isBtn:!1,formDynamicName:[],results:[],result:[],ids:0}},computed:{},methods:{onCancel:function(){this.ids=0,this.clear()},addBtn:function(){this.isBtn=!0},getIofo:function(t){var e=this;this.spinShow=!0,this.ids=t.id,Object(r["A"])(t.id).then((function(t){e.formDynamic=t.data.info,e.spinShow=!1})).catch((function(t){e.spinShow=!1,e.$Message.error(t.msg)}))},handleSubmit:function(t){var e=this;this.$refs[t].validate((function(t){return!!t&&(0===e.formDynamic.spec.length?e.$Message.warning("请至少添加一条商品规格！"):(e.modal_loading=!0,void setTimeout((function(){Object(r["z"])(e.formDynamic,e.ids).then((function(t){e.$Message.success(t.msg),setTimeout((function(){e.modal=!1,e.modal_loading=!1}),500),setTimeout((function(){e.$emit("getList"),e.clear()}),600)})).catch((function(t){e.modal_loading=!1,e.$Message.error(t.msg)}))}),1200)))}))},clear:function(){this.$refs["formDynamic"].resetFields(),this.formDynamic.spec=[],this.isBtn=!1,this.attrsName="",this.attrsVal="",this.ids=0},offAttrName:function(){this.isBtn=!1},handleRemove:function(t){this.formDynamic.spec.splice(t,1)},handleRemove2:function(t,e){t.splice(e,1)},createAttrName:function(){if(this.attrsName&&this.attrsVal){var t={value:this.attrsName,detail:[this.attrsVal]};this.formDynamic.spec.push(t);var e={};this.formDynamic.spec=this.formDynamic.spec.reduce((function(t,a){return!e[a.value]&&(e[a.value]=t.push(a)),t}),[]),this.attrsName="",this.attrsVal="",this.isBtn=!1}else this.$Message.warning("请添加规格名称或规格值")},createAttr:function(t,e){if(t){this.formDynamic.spec[e].detail.push(t);var a={};this.formDynamic.spec[e].detail=this.formDynamic.spec[e].detail.reduce((function(t,e){return!a[e]&&(a[e]=t.push(e)),t}),[])}else this.$Message.warning("请添加属性")}}},o=i,l=(a("6f83"),a("2877")),c=Object(l["a"])(o,s,n,!1,null,"921f4090",null);e["a"]=c.exports},"7f08":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[t._m(0),a("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[a("Form",{ref:"artFrom",staticClass:"tabform",attrs:{model:t.artFrom,"label-width":80,"label-position":"right"},nativeOn:{submit:function(t){t.preventDefault()}}},[a("Row",{attrs:{gutter:24,type:"flex",justify:"end"}},[a("Col",{staticClass:"ivu-text-left",attrs:{span:"24"}},[a("FormItem",{attrs:{label:"规格搜索："}},[a("Input",{staticStyle:{width:"30%"},attrs:{search:"","enter-button":"",placeholder:"请输入规格名称"},on:{"on-search":t.userSearchs},model:{value:t.artFrom.rule_name,callback:function(e){t.$set(t.artFrom,"rule_name",e)},expression:"artFrom.rule_name"}})],1)],1),a("Col",{attrs:{span:"24"}},[a("Button",{directives:[{name:"auth",rawName:"v-auth",value:["product-rule-save"],expression:"['product-rule-save']"}],staticClass:"mr20",attrs:{type:"primary",icon:"md-add"},on:{click:t.addAttr}},[t._v("添加商品规格")]),a("Button",{directives:[{name:"auth",rawName:"v-auth",value:["product-product-rule-delete"],expression:"['product-product-rule-delete']"}],on:{click:function(e){return t.del(null,"批量删除规格")}}},[t._v("批量删除")])],1)],1)],1),a("Table",{ref:"table",staticClass:"mt25",attrs:{columns:t.columns4,data:t.tableList,loading:t.loading,"highlight-row":"","no-data-text":"暂无数据","no-filtered-data-text":"暂无筛选结果"},on:{"on-select":t.handleSelectRow,"on-select-cancel":t.handleCancelRow,"on-select-all":t.handleSelectAll,"on-select-all-cancel":t.handleSelectAll},scopedSlots:t._u([{key:"attr_value",fn:function(e){var s=e.row;return t._l(s.attr_value,(function(e,s){return a("span",{key:s,staticStyle:{display:"block"},domProps:{textContent:t._s(e)}})}))}},{key:"action",fn:function(e){var s=e.row;return[a("a",{on:{click:function(e){return t.edit(s)}}},[t._v("编辑")]),a("Divider",{attrs:{type:"vertical"}}),a("a",{on:{click:function(e){return t.del(s,"删除规格")}}},[t._v("删除")])]}}])}),a("div",{staticClass:"acea-row row-right page"},[a("Page",{attrs:{total:t.total,current:t.artFrom.page,"show-elevator":"","show-total":"","page-size":t.artFrom.limit},on:{"on-change":t.pageChange}})],1)],1),a("add-attr",{ref:"addattr",on:{getList:t.userSearchs}})],1)},n=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"i-layout-page-header"},[a("div",{staticClass:"i-layout-page-header"},[a("span",{staticClass:"ivu-page-header-title"},[t._v("商品规格")])])])}],r=(a("8e6e"),a("456d"),a("75fc")),i=a("bd86"),o=(a("ac6a"),a("5df3"),a("4f7f"),a("2f62")),l=a("6fd3"),c=a("c4c8");function d(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function u(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?d(Object(a),!0).forEach((function(e){Object(i["a"])(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):d(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}var m={name:"productAttr",components:{addAttr:l["a"]},data:function(){return{loading:!1,artFrom:{page:1,limit:15,rule_name:""},columns4:[{type:"selection",width:60},{title:"ID",key:"id",width:80},{title:"规格名称",key:"rule_name",minWidth:150},{title:"商品规格",key:"attr_name",minWidth:250},{title:"商品属性",slot:"attr_value",minWidth:300},{title:"操作",slot:"action",fixed:"right",minWidth:120}],tableList:[],total:0,selectedIds:new Set,ids:""}},computed:u({},Object(o["e"])("admin/order",["orderChartType"])),created:function(){this.getDataList()},methods:{handleSelectAll:function(t){var e=this;if(0===t.length){var a=this.$refs.table.data;a.forEach((function(t){e.selectedIds.has(t.id)&&e.selectedIds.delete(t.id)}))}else t.forEach((function(t){e.selectedIds.add(t.id)}));this.$nextTick((function(){e.setChecked()}))},handleSelectRow:function(t,e){var a=this;this.selectedIds.add(e.id),this.$nextTick((function(){a.setChecked()}))},handleCancelRow:function(t,e){var a=this;this.selectedIds.delete(e.id),this.$nextTick((function(){a.setChecked()}))},setChecked:function(){this.ids=Object(r["a"])(this.selectedIds).join(",");var t=this.$refs.table.objData;for(var e in t)this.selectedIds.has(t[e].id)&&(t[e]._isChecked=!0)},del:function(t,e){var a=this,s={};if("批量删除规格"===e){if(0===this.selectedIds.size)return this.$Message.warning("请选择要删除的规格！");s={ids:this.ids}}else s={ids:t.id};var n={title:e,num:0,url:"product/product/rule/delete",method:"DELETE",ids:s};this.$modalSure(n).then((function(t){a.$Message.success(t.msg),a.getDataList()})).catch((function(t){a.$Message.error(t.msg)}))},addAttr:function(){this.$refs.addattr.modal=!0},edit:function(t){this.$refs.addattr.modal=!0,this.$refs.addattr.getIofo(t)},getDataList:function(){var t=this;this.loading=!0,Object(c["B"])(this.artFrom).then((function(e){var a=e.data;t.tableList=a.list,t.$nextTick((function(){t.setChecked()})),t.total=e.data.count,t.loading=!1})).catch((function(e){t.loading=!1,t.$Message.error(e.msg)}))},pageChange:function(t){this.artFrom.page=t,this.getDataList()},userSearchs:function(){this.artFrom.page=1,this.getDataList()}}},f=m,h=a("2877"),p=Object(h["a"])(f,s,n,!1,null,"a9093b42",null);e["default"]=p.exports}}]);