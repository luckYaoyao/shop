(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3296d4b0"],{"154e":function(t,e,a){"use strict";var r=a("49901"),i=a.n(r);i.a},"3d87":function(t,e,a){var r=a("4930");t.exports=r&&!!Symbol["for"]&&!!Symbol.keyFor},"428f":function(t,e,a){var r=a("da84");t.exports=r},49901:function(t,e,a){},"57b9":function(t,e,a){var r=a("c65b"),i=a("d066"),s=a("b622"),o=a("cb2d");t.exports=function(){var t=i("Symbol"),e=t&&t.prototype,a=e&&e.valueOf,n=s("toPrimitive");e&&!e[n]&&o(e,n,(function(t){return r(a,this)}),{arity:1})}},"5a47":function(t,e,a){var r=a("23e7"),i=a("4930"),s=a("d039"),o=a("7418"),n=a("7b0b"),l=!i||s((function(){o.f(1)}));r({target:"Object",stat:!0,forced:l},{getOwnPropertySymbols:function(t){var e=o.f;return e?e(n(t)):[]}})},"746f":function(t,e,a){var r=a("428f"),i=a("1a2d"),s=a("e538"),o=a("9bf2").f;t.exports=function(t){var e=r.Symbol||(r.Symbol={});i(e,t)||o(e,t,{value:s.f(t)})}},a4d3:function(t,e,a){a("d9f5"),a("b4f8"),a("c513"),a("e9c4"),a("5a47")},b4f8:function(t,e,a){var r=a("23e7"),i=a("d066"),s=a("1a2d"),o=a("577e"),n=a("5692"),l=a("3d87"),c=n("string-to-symbol-registry"),d=n("symbol-to-string-registry");r({target:"Symbol",stat:!0,forced:!l},{for:function(t){var e=o(t);if(s(c,e))return c[e];var a=i("Symbol")(e);return c[e]=a,d[a]=e,a}})},c513:function(t,e,a){var r=a("23e7"),i=a("1a2d"),s=a("d9b5"),o=a("0d51"),n=a("5692"),l=a("3d87"),c=n("symbol-to-string-registry");r({target:"Symbol",stat:!0,forced:!l},{keyFor:function(t){if(!s(t))throw TypeError(o(t)+" is not a symbol");if(i(c,t))return c[t]}})},d9f5:function(t,e,a){"use strict";var r=a("23e7"),i=a("da84"),s=a("c65b"),o=a("e330"),n=a("c430"),l=a("83ab"),c=a("4930"),d=a("d039"),u=a("1a2d"),m=a("3a9b"),f=a("825a"),p=a("fc6a"),g=a("a04b"),h=a("577e"),b=a("5c6c"),v=a("7c73"),y=a("df75"),w=a("241c"),V=a("057f"),C=a("7418"),_=a("06cf"),x=a("9bf2"),k=a("37e8"),O=a("d1e7"),$=a("cb2d"),I=a("5692"),P=a("f772"),S=a("d012"),D=a("90e3"),E=a("b622"),F=a("e538"),T=a("746f"),j=a("57b9"),q=a("d44e"),M=a("69f3"),R=a("b727").forEach,z=P("hidden"),U="Symbol",W="prototype",N=M.set,L=M.getterFor(U),B=Object[W],H=i.Symbol,G=H&&H[W],A=i.TypeError,J=i.QObject,Q=_.f,K=x.f,X=V.f,Y=O.f,Z=o([].push),tt=I("symbols"),et=I("op-symbols"),at=I("wks"),rt=!J||!J[W]||!J[W].findChild,it=l&&d((function(){return 7!=v(K({},"a",{get:function(){return K(this,"a",{value:7}).a}})).a}))?function(t,e,a){var r=Q(B,e);r&&delete B[e],K(t,e,a),r&&t!==B&&K(B,e,r)}:K,st=function(t,e){var a=tt[t]=v(G);return N(a,{type:U,tag:t,description:e}),l||(a.description=e),a},ot=function(t,e,a){t===B&&ot(et,e,a),f(t);var r=g(e);return f(a),u(tt,r)?(a.enumerable?(u(t,z)&&t[z][r]&&(t[z][r]=!1),a=v(a,{enumerable:b(0,!1)})):(u(t,z)||K(t,z,b(1,{})),t[z][r]=!0),it(t,r,a)):K(t,r,a)},nt=function(t,e){f(t);var a=p(e),r=y(a).concat(mt(a));return R(r,(function(e){l&&!s(ct,a,e)||ot(t,e,a[e])})),t},lt=function(t,e){return void 0===e?v(t):nt(v(t),e)},ct=function(t){var e=g(t),a=s(Y,this,e);return!(this===B&&u(tt,e)&&!u(et,e))&&(!(a||!u(this,e)||!u(tt,e)||u(this,z)&&this[z][e])||a)},dt=function(t,e){var a=p(t),r=g(e);if(a!==B||!u(tt,r)||u(et,r)){var i=Q(a,r);return!i||!u(tt,r)||u(a,z)&&a[z][r]||(i.enumerable=!0),i}},ut=function(t){var e=X(p(t)),a=[];return R(e,(function(t){u(tt,t)||u(S,t)||Z(a,t)})),a},mt=function(t){var e=t===B,a=X(e?et:p(t)),r=[];return R(a,(function(t){!u(tt,t)||e&&!u(B,t)||Z(r,tt[t])})),r};c||(H=function(){if(m(G,this))throw A("Symbol is not a constructor");var t=arguments.length&&void 0!==arguments[0]?h(arguments[0]):void 0,e=D(t),a=function(t){this===B&&s(a,et,t),u(this,z)&&u(this[z],e)&&(this[z][e]=!1),it(this,e,b(1,t))};return l&&rt&&it(B,e,{configurable:!0,set:a}),st(e,t)},G=H[W],$(G,"toString",(function(){return L(this).tag})),$(H,"withoutSetter",(function(t){return st(D(t),t)})),O.f=ct,x.f=ot,k.f=nt,_.f=dt,w.f=V.f=ut,C.f=mt,F.f=function(t){return st(E(t),t)},l&&(K(G,"description",{configurable:!0,get:function(){return L(this).description}}),n||$(B,"propertyIsEnumerable",ct,{unsafe:!0}))),r({global:!0,constructor:!0,wrap:!0,forced:!c,sham:!c},{Symbol:H}),R(y(at),(function(t){T(t)})),r({target:U,stat:!0,forced:!c},{useSetter:function(){rt=!0},useSimple:function(){rt=!1}}),r({target:"Object",stat:!0,forced:!c,sham:!l},{create:lt,defineProperty:ot,defineProperties:nt,getOwnPropertyDescriptor:dt}),r({target:"Object",stat:!0,forced:!c},{getOwnPropertyNames:ut}),j(),q(H,U),S[z]=!0},e01a:function(t,e,a){"use strict";var r=a("23e7"),i=a("83ab"),s=a("da84"),o=a("e330"),n=a("1a2d"),l=a("1626"),c=a("3a9b"),d=a("577e"),u=a("9bf2").f,m=a("e893"),f=s.Symbol,p=f&&f.prototype;if(i&&l(f)&&(!("description"in p)||void 0!==f().description)){var g={},h=function(){var t=arguments.length<1||void 0===arguments[0]?void 0:d(arguments[0]),e=c(p,this)?new f(t):void 0===t?f():f(t);return""===t&&(g[e]=!0),e};m(h,f),h.prototype=p,p.constructor=h;var b="Symbol(test)"==String(f("test")),v=o(p.toString),y=o(p.valueOf),w=/^Symbol\((.*)\)[^)]+$/,V=o("".replace),C=o("".slice);u(p,"description",{configurable:!0,get:function(){var t=y(this),e=v(t);if(n(g,t))return"";var a=b?C(e,7,-1):V(e,w,"$1");return""===a?void 0:a}}),r({global:!0,constructor:!0,forced:!0},{Symbol:h})}},e538:function(t,e,a){var r=a("b622");e.f=r},ef0d:function(t,e,a){"use strict";var r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("vue-ueditor-wrap",{staticStyle:{width:"90%"},attrs:{config:t.myConfig},on:{beforeInit:t.addCustomDialog},model:{value:t.contents,callback:function(e){t.contents=e},expression:"contents"}})],1)},i=[],s=a("6625"),o=a.n(s),n={name:"index",components:{VueUeditorWrap:o.a},props:{content:""},watch:{content:{handler:function(t){},deep:!0}},data:function(){return{contents:this.content,myConfig:{autoHeightEnabled:!1,initialFrameHeight:200,initialFrameWidth:"100%",UEDITOR_HOME_URL:"/admin/UEditor/",serverUrl:""}}},methods:{addCustomDialog:function(t){window.UE.registerUI("test-dialog",(function(t,e){var a=new window.UE.ui.Dialog({iframeUrl:"/admin/widget.images/index.html?fodder=dialog",editor:t,name:e,title:"上传图片",cssRules:"width:960px;height:550px;padding:20px;"});this.dialog=a;var r=new window.UE.ui.Button({name:"dialog-button",title:"上传图片",cssRules:"background-image: url(../../../assets/images/icons.png);background-position: -726px -77px;",onclick:function(){a.render(),a.open()}});return r}),37,t)}},created:function(){}},l=n,c=a("2877"),d=Object(c["a"])(l,r,i,!1,null,"0e8ff217",null);e["a"]=d.exports},fac5f:function(t,e,a){"use strict";a.r(e);var r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"i-layout-page-header"},[a("div",{staticClass:"i-layout-page-header"},[a("router-link",{attrs:{to:{path:"/admin/marketing/presell/index"}}},[a("Button",{staticClass:"mr20",attrs:{icon:"ios-arrow-back",size:"small"}},[t._v("返回 ")])],1),a("span",{staticClass:"ivu-page-header-title mr20",domProps:{textContent:t._s(0!=t.$route.params.id?"编辑预售商品":"添加预售商品")}})],1)]),a("Card",{staticClass:"ivu-mt",attrs:{bordered:!1,"dis-hover":""}},[a("Row",{staticClass:"mt30 acea-row row-middle row-center",attrs:{type:"flex"}},[a("Col",{attrs:{span:"20"}},[a("Steps",{attrs:{current:t.current}},[a("Step",{attrs:{title:"选择预售商品"}}),a("Step",{attrs:{title:"填写基础信息"}}),a("Step",{attrs:{title:"修改商品详情"}})],1)],1),a("Col",{attrs:{span:"23"}},[a("Form",{ref:"formValidate",staticClass:"form mt30",attrs:{model:t.formValidate,rules:t.ruleValidate,"label-width":t.labelWidth,"label-position":t.labelPosition},on:{"on-validate":t.validate},nativeOn:{submit:function(t){t.preventDefault()}}},[0===t.current?a("FormItem",{attrs:{label:"选择商品：",prop:"image_input"}},[a("div",{staticClass:"picBox",on:{click:t.changeGoods}},[t.formValidate.image?a("div",{staticClass:"pictrue"},[a("img",{directives:[{name:"lazy",rawName:"v-lazy",value:t.formValidate.image,expression:"formValidate.image"}]})]):a("div",{staticClass:"upLoad acea-row row-center-wrapper"},[a("Icon",{attrs:{type:"ios-camera-outline",size:"26"}})],1)])]):t._e(),a("Row",{directives:[{name:"show",rawName:"v-show",value:1===t.current,expression:"current === 1"}],attrs:{type:"flex"}},[a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"商品主图：",prop:"image"}},[a("div",{staticClass:"picBox",on:{click:function(e){return t.modalPicTap("dan","danFrom")}}},[t.formValidate.image?a("div",{staticClass:"pictrue"},[a("img",{directives:[{name:"lazy",rawName:"v-lazy",value:t.formValidate.image,expression:"formValidate.image"}]})]):a("div",{staticClass:"upLoad acea-row row-center-wrapper"},[a("Icon",{attrs:{type:"ios-camera-outline",size:"26"}})],1)])])],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"商品轮播图：",prop:"images"}},[a("div",{staticClass:"acea-row"},[t._l(t.formValidate.images,(function(e,r){return a("div",{key:r,staticClass:"pictrue",attrs:{draggable:"true"},on:{dragstart:function(a){return t.handleDragStart(a,e)},dragover:function(a){return a.preventDefault(),t.handleDragOver(a,e)},dragenter:function(a){return t.handleDragEnter(a,e)},dragend:function(a){return t.handleDragEnd(a,e)}}},[a("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e,expression:"item"}]}),a("Button",{staticClass:"btndel",attrs:{shape:"circle",icon:"md-close"},nativeOn:{click:function(e){return t.handleRemove(r)}}})],1)})),t.formValidate.images.length<10?a("div",{staticClass:"upLoad acea-row row-center-wrapper",on:{click:function(e){return t.modalPicTap("duo")}}},[a("Icon",{attrs:{type:"ios-camera-outline",size:"26"}})],1):t._e()],2)])],1),a("Col",{attrs:{span:"24"}},[a("Col",t._b({},"Col",t.grid,!1),[a("FormItem",{attrs:{label:"预售名称：",prop:"title","label-for":"title"}},[a("Input",{attrs:{placeholder:"请输入预售名称","element-id":"title"},model:{value:t.formValidate.title,callback:function(e){t.$set(t.formValidate,"title",e)},expression:"formValidate.title"}})],1)],1)],1),a("Col",{attrs:{span:"24"}},[a("Col",t._b({},"Col",t.grid,!1),[a("FormItem",{attrs:{label:"预售简介：",prop:"info","label-for":"info"}},[a("Input",{attrs:{placeholder:"请输入预售简介",type:"textarea",rows:4,"element-id":"info"},model:{value:t.formValidate.info,callback:function(e){t.$set(t.formValidate,"info",e)},expression:"formValidate.info"}})],1)],1)],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"预售活动时间：",prop:"section_time"}},[a("div",{staticClass:"acea-row row-middle"},[a("DatePicker",{staticClass:"perW20",attrs:{editable:!1,type:"datetimerange",format:"yyyy-MM-dd HH:mm",placeholder:"请选择活动时间",value:t.formValidate.section_time},on:{"on-change":t.onchangeTime},model:{value:t.formValidate.section_time,callback:function(e){t.$set(t.formValidate,"section_time",e)},expression:"formValidate.section_time"}}),a("div",{staticClass:"ml10 grey"},[t._v("设置活动开启结束时间，用户可以在设置时间内发起参与预售")])],1)])],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"发货时间：",prop:"deliver_time"}},[a("div",{staticClass:"acea-row row-middle"},[a("span",{staticClass:"mr10"},[t._v("预售活动结束后")]),a("InputNumber",{attrs:{placeholder:"请输入发货时间",precision:0,min:1},model:{value:t.formValidate.deliver_time,callback:function(e){t.$set(t.formValidate,"deliver_time",e)},expression:"formValidate.deliver_time"}}),a("span",{staticClass:"ml10"},[t._v(" 天之内 ")]),a("div",{staticClass:"ml10 grey"})],1)])],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"运费模板：",prop:"temp_id"}},[a("div",{staticClass:"acea-row row-middle"},[a("Select",{staticClass:"perW20",model:{value:t.formValidate.temp_id,callback:function(e){t.$set(t.formValidate,"temp_id",e)},expression:"formValidate.temp_id"}},t._l(t.templateList,(function(e){return a("Option",{key:e.id,attrs:{value:e.id}},[t._v(t._s(e.name)+" ")])})),1),a("div",{staticClass:"ml10 col",on:{click:t.freight}},[t._v("添加运费模板")])],1)])],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"总购买数量限制：",prop:"num"}},[a("div",{staticClass:"acea-row row-middle"},[a("InputNumber",{staticClass:"perW20",attrs:{min:1,placeholder:"请输入总数量限制",precision:0,"element-id":"num"},model:{value:t.formValidate.num,callback:function(e){t.$set(t.formValidate,"num",e)},expression:"formValidate.num"}}),a("div",{staticClass:"ml10 grey"},[t._v("\n                    该商品活动期间内，用户可购买的最大数量。例如设置为4，表示本次活动有效期内，每个用户最多可购买4件\n                  ")])],1)])],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"单位：",prop:"unit_name","label-for":"unit_name"}},[a("Input",{staticClass:"perW20",attrs:{placeholder:"请输入单位","element-id":"unit_name"},model:{value:t.formValidate.unit_name,callback:function(e){t.$set(t.formValidate,"unit_name",e)},expression:"formValidate.unit_name"}})],1)],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"排序："}},[a("InputNumber",{staticClass:"perW10",attrs:{placeholder:"请输入排序","element-id":"sort",precision:0},model:{value:t.formValidate.sort,callback:function(e){t.$set(t.formValidate,"sort",e)},expression:"formValidate.sort"}})],1)],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"活动状态：",props:"status","label-for":"status"}},[a("RadioGroup",{attrs:{"element-id":"status"},model:{value:t.formValidate.status,callback:function(e){t.$set(t.formValidate,"status",e)},expression:"formValidate.status"}},[a("Radio",{staticClass:"radio",attrs:{label:1}},[t._v("上架")]),a("Radio",{attrs:{label:0}},[t._v("下架")])],1)],1)],1),a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"规格选择："}},[a("Table",{attrs:{data:t.specsData,columns:t.columns,border:""},on:{"on-selection-change":t.changeCheckbox},scopedSlots:t._u([{key:"pic",fn:function(e){var r=e.row,i=e.index;return[a("div",{staticClass:"acea-row row-middle row-center-wrapper",on:{click:function(e){return t.modalPicTap("dan","danTable",i)}}},[r.pic?a("div",{staticClass:"pictrue pictrueTab"},[a("img",{directives:[{name:"lazy",rawName:"v-lazy",value:r.pic,expression:"row.pic"}]})]):a("div",{staticClass:"upLoad pictrueTab acea-row row-center-wrapper"},[a("Icon",{attrs:{type:"ios-camera-outline",size:"21"}})],1)])]}}])})],1)],1)],1),a("Row",{directives:[{name:"show",rawName:"v-show",value:2===t.current,expression:"current === 2"}]},[a("Col",{attrs:{span:"24"}},[a("FormItem",{attrs:{label:"内容："}},[a("WangEditor",{staticStyle:{width:"90%"},attrs:{content:t.formValidate.description},on:{editorContent:t.getEditorContent}})],1)],1)],1),a("FormItem",[a("Button",{directives:[{name:"show",rawName:"v-show",value:0!==t.current,expression:"current !== 0"}],staticClass:"submission mr15",attrs:{disabled:t.$route.params.id&&1===t.current},on:{click:t.step}},[t._v("上一步\n            ")]),a("Button",{staticClass:"submission",attrs:{type:"primary",disabled:t.submitOpen&&2===t.current},domProps:{textContent:t._s(2===t.current?"提交":"下一步")},on:{click:function(e){return t.next("formValidate")}}})],1)],1),t.spinShow?a("Spin",{attrs:{size:"large",fix:""}}):t._e()],1)],1)],1),a("Modal",{staticClass:"paymentFooter",attrs:{title:"商品列表",footerHide:"",scrollable:"",width:"900"},on:{"on-cancel":t.cancel},model:{value:t.modals,callback:function(e){t.modals=e},expression:"modals"}},[a("goods-list",{ref:"goodslist",on:{getProductId:t.getProductId}})],1),a("Modal",{attrs:{width:"950px",scrollable:"","footer-hide":"",closable:"",title:"上传商品图","mask-closable":!1,"z-index":888},model:{value:t.modalPic,callback:function(e){t.modalPic=e},expression:"modalPic"}},[t.modalPic?a("uploadPictures",{attrs:{isChoice:t.isChoice,gridBtn:t.gridBtn,gridPic:t.gridPic},on:{getPic:t.getPic,getPicD:t.getPicD}}):t._e()],1),a("freight-template",{ref:"template",on:{addSuccess:t.productGetTemplate}})],1)},i=[],s=a("d0ff"),o=a("c964"),n=a("fc11"),l=a("f3f3"),c=(a("96cf"),a("a4d3"),a("e01a"),a("d3b7"),a("159b"),a("a434"),a("4e82"),a("a9e3"),a("d81d"),a("99af"),a("2f62")),d=a("c4ad"),u=a("ef0d"),m=a("a069"),f=a("b0e7"),p=a("5334"),g=a("b7be"),h=a("c4c8"),b={name:"storePersellCreate",components:{UeditorWrap:u["a"],goodsList:d["default"],uploadPictures:f["a"],WangEditor:m["a"],freightTemplate:p["a"]},data:function(){return{submitOpen:!1,spinShow:!1,isChoice:"",current:0,modalPic:!1,grid:{xl:12,lg:20,md:24,sm:24,xs:24},grid2:{xl:8,lg:8,md:12,sm:24,xs:24},gridPic:{xl:6,lg:8,md:12,sm:12,xs:12},gridBtn:{xl:4,lg:8,md:8,sm:8,xs:8},myConfig:{autoHeightEnabled:!1,initialFrameHeight:500,initialFrameWidth:"100%",UEDITOR_HOME_URL:"/admin/UEditor/",serverUrl:""},modals:!1,modal_loading:!1,images:[],templateList:[],columns:[],specsData:[],picTit:"",tableIndex:0,formValidate:{images:[],info:"",title:"",image:"",unit_name:"",stock:1,sales:0,deliver_time:3,sort:0,status:1,section_time:[],description:"",id:0,product_id:0,num:1,deposit:1,temp_id:"",attrs:[],items:[]},ruleValidate:{image:[{required:!0,message:"请选择主图",trigger:"change"}],images:[{required:!0,type:"array",message:"请选择主图",trigger:"change"},{type:"array",min:1,message:"Choose two hobbies at best",trigger:"change"}],title:[{required:!0,message:"请输入预售名称",trigger:"blur"}],info:[{required:!0,message:"请输入预售简介",trigger:"blur"}],section_time:[{required:!0,type:"array",message:"请选择活动时间",trigger:"change"}],unit_name:[{required:!0,message:"请输入单位",trigger:"blur"}],price:[{required:!0,type:"number",message:"请输入预售价",trigger:"blur"}],cost:[{required:!0,type:"number",message:"请输入成本价",trigger:"blur"}],stock:[{required:!0,type:"number",message:"请输入库存",trigger:"blur"}],give_integral:[{required:!0,type:"number",message:"请输入赠送积分",trigger:"blur"}],effective_time:[{required:!0,type:"number",message:"请输入预售时效(单位 小时)",trigger:"blur"}],people:[{required:!0,type:"number",message:"请输入预售人数",trigger:"blur"}],num:[{required:!0,type:"number",message:"请输入购买数量限制",trigger:"blur"}],deposit:[{required:!0,type:"number",message:"请输入定金金额",trigger:"blur"}],once_num:[{required:!0,type:"number",message:"请输入单次购买数量限制",trigger:"blur"}],virtualPeople:[{required:!0,type:"number",message:"请输入虚拟成团补齐人数",trigger:"blur"}],temp_id:[{required:!0,message:"请选择运费模板",trigger:"change",type:"number"}]},copy:0}},computed:Object(l["a"])(Object(l["a"])({},Object(c["e"])("media",["isMobile"])),{},{labelWidth:function(){return this.isMobile?void 0:155},labelPosition:function(){return this.isMobile?"top":"right"}}),mounted:function(){0!=this.$route.params.id&&(this.copy=this.$route.params.copy,this.current=1,this.getInfo()),this.productGetTemplate()},methods:{getEditorContent:function(t){this.formValidate.description=t},freight:function(){this.$refs.template.id=0,this.$refs.template.isTemplate=!0},productAttrs:function(t){var e=this;Object(g["fb"])(t.id,6).then((function(t){var a=t.data.info,r={type:"selection",width:60,align:"center"};e.specsData=a.attrs,e.specsData.forEach((function(t,a){e.$set(e.specsData[a],"id",a)})),e.formValidate.items=a.items,e.columns=a.header,e.columns.unshift(r),e.inputChange(a)})).catch((function(t){e.$Message.error(t.msg)}))},inputChange:function(t){var e=this,a=[];t.header.forEach((function(t,e){1===t.type&&a.push({index:e,key:t.key,title:t.title})})),a.forEach((function(t,a){var r=t.title,i=t.key,s={title:r,key:i,align:"center",minWidth:100,render:function(t,a){return t("div",[t("InputNumber",{props:{min:1,max:"price"===i?1e7:a.row.stock,value:"price"===i?a.row.price:a.row.quota},on:{"on-change":function(t){"price"===i?a.row.price=t:a.row.quota=t<a.row.stock?t:a.row.stock,e.specsData[a.index]=a.row,e.formValidate.attrs&&e.formValidate.attrs.length&&e.formValidate.attrs.forEach((function(t,r){t.id===a.row.id&&e.formValidate.attrs.splice(r,1,a.row)}))}}})])}};e.columns.splice(t.index,1,s)}))},changeCheckbox:function(t){this.formValidate.attrs=t},productGetTemplate:function(){var t=this;Object(h["t"])().then((function(e){t.templateList=e.data}))},validate:function(t,e,a){!1===e&&this.$Message.error(a)},getProductId:function(t){var e=this;this.modal_loading=!1,this.modals=!1,setTimeout((function(){var a;e.formValidate=(a={images:t.slider_image,info:t.store_info,title:t.store_name,image:t.image,unit_name:t.unit_name,stock:t.stock,sales:t.sales,sort:t.sort,section_time:[],deliver_time:3,num:1,deposit:1,description:t.description,id:0},Object(n["a"])(a,"num",1),Object(n["a"])(a,"status",1),Object(n["a"])(a,"product_id",t.id),Object(n["a"])(a,"temp_id",t.temp_id),a),e.productAttrs(t)}),500)},cancel:function(){this.modals=!1},onchangeTime:function(t){this.formValidate.section_time=t},getInfo:function(){var t=this;this.spinShow=!0,Object(g["db"])(this.$route.params.id).then(function(){var e=Object(o["a"])(regeneratorRuntime.mark((function e(a){var r,i,s,o,n,l;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:for(l in r=t,i=a.data.info,s={type:"selection",width:60,align:"center"},t.formValidate=i,t.formValidate.virtualPeople=parseInt(t.formValidate.people-t.formValidate.people*(t.formValidate.virtual/100)),t.$set(t.formValidate,"items",i.attrs.items),t.columns=i.attrs.header,t.columns.unshift(s),t.specsData=i.attrs.value,r.specsData.forEach((function(t,e){r.$set(r.specsData[e],"id",e)})),o=i.attrs,n=[],i.attrs.value)i.attrs.value[l]._checked&&n.push(i.attrs.value[l]);r.formValidate.attrs=n,r.inputChange(o),t.spinShow=!1;case 16:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.spinShow=!1,t.$Message.error(e)}))},next:function(t){var e=this,a=this;2===this.current?this.$refs[t].validate((function(t){if(!t)return!1;1==e.copy&&(e.formValidate.copy=1),e.formValidate.id=Number(e.$route.params.id)||0,e.submitOpen=!0,e.formValidate.virtual=parseInt((e.formValidate.people-e.formValidate.virtualPeople)/e.formValidate.people*100),Object(g["cb"])(e.formValidate).then(function(){var t=Object(o["a"])(regeneratorRuntime.mark((function t(a){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.submitOpen=!1,e.$Message.success(a.msg),setTimeout((function(){e.$router.push({path:"/admin/marketing/presell/index"})}),500);case 3:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.submitOpen=!1,e.$Message.error(t.msg)}))})):1===this.current?this.$refs[t].validate((function(t){if(!t)return e.$Message.warning("请完善您的信息");if(a.formValidate.people<2)return a.$Message.error("预售人数必须大于2");if(a.formValidate.num<0)return a.$Message.error("购买数量限制必须大于0");if(!a.formValidate.attrs)return a.$Message.error("请选择属性规格");for(var r in a.formValidate.attrs){if(a.formValidate.attrs[r].quota<=0)return a.$Message.error("预售限量必须大于0");if(e.formValidate.attrs[r].quota>e.formValidate.attrs[r]["stock"])return e.$Message.error("预售限量不能超过规格库存")}e.current+=1})):this.formValidate.image?this.current+=1:this.$Message.warning("请选择商品")},step:function(){this.current--},getContent:function(t){this.formValidate.description=t},modalPicTap:function(t,e,a){this.modalPic=!0,this.isChoice="dan"===t?"单选":"多选",this.picTit=e,this.tableIndex=a},getPic:function(t){switch(this.picTit){case"danFrom":this.formValidate.image=t.att_dir;break;default:this.formValidate.attrs&&this.formValidate.attrs.length&&this.$set(this.specsData[this.tableIndex],"_checked",!0),this.specsData[this.tableIndex].pic=t.att_dir}this.modalPic=!1},getPicD:function(t){var e=this;this.images=t,this.images.map((function(t){e.formValidate.images.push(t.att_dir),e.formValidate.images=e.formValidate.images.splice(0,10)})),this.modalPic=!1},handleRemove:function(t){this.images.splice(t,1),this.formValidate.images.splice(t,1)},changeGoods:function(){this.modals=!0,this.$refs.goodslist.formValidate.is_virtual=0,this.$refs.goodslist.getList(),this.$refs.goodslist.goodsCategory()},handleDragStart:function(t,e){this.dragging=e},handleDragEnd:function(t,e){this.dragging=null},handleDragOver:function(t){t.dataTransfer.dropEffect="move"},handleDragEnter:function(t,e){if(t.dataTransfer.effectAllowed="move",e!==this.dragging){var a=Object(s["a"])(this.formValidate.images),r=a.indexOf(this.dragging),i=a.indexOf(e);a.splice.apply(a,[i,0].concat(Object(s["a"])(a.splice(r,1)))),this.formValidate.images=a}}}},v=b,y=(a("154e"),a("2877")),w=Object(y["a"])(v,r,i,!1,null,"dd49f956",null);e["default"]=w.exports}}]);