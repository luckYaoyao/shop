(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-5be37669"],{"02b1":function(e,t,i){},"0b439":function(e,t,i){"use strict";i=i("04f8"),e.exports=i&&!!Symbol.for&&!!Symbol.keyFor},1764:function(e,t,i){},"57b9":function(e,t,i){"use strict";var a=i("c65b"),r=i("d066"),s=i("b622"),n=i("cb2d");e.exports=function(){var e=r("Symbol"),t=(e=e&&e.prototype,e&&e.valueOf),i=s("toPrimitive");e&&!e[i]&&n(e,i,(function(e){return a(t,this)}),{arity:1})}},"5a47":function(e,t,i){"use strict";var a=i("23e7"),r=i("04f8"),s=i("d039"),n=i("7418"),o=i("7b0b");a({target:"Object",stat:!0,forced:!r||s((function(){n.f(1)}))},{getOwnPropertySymbols:function(e){var t=n.f;return t?t(o(e)):[]}})},"6ea4":function(e,t,i){"use strict";i("a9e3");var a={name:"",props:{stepList:{type:Array,default:function(){return[]}},isActive:{type:Number,default:0}},data:function(){return{}},created:function(){},mounted:function(){},methods:{lineWidth:function(){var e;switch(this.stepList.length){case 3:e="wd160";case 4:e="wd120";default:e="wd100"}return e},stepActive:function(e){this.$emit("stepActive",e)}}};i("ff90"),i=i("2877"),i=Object(i.a)(a,(function(){var e=this,t=e._self._c;return t("div",{staticClass:"steps df-cc"},e._l(e.stepList,(function(i,a){return t("div",{directives:[{name:"db-click",rawName:"v-db-click"}],key:a,staticClass:"steps-item",class:a<=e.isActive?"active":"",on:{click:function(t){return e.stepActive(a)}}},[t("div",{staticClass:"dot df-cc"},[e._v(e._s(a+1))]),t("span",{staticClass:"title"},[e._v(e._s(i))]),a<e.stepList.length-1?t("div",{staticClass:"line",class:e.lineWidth()}):e._e()])})),0)}),[],!1,null,"0fa1e31c",null);t.a=i.exports},"923c":function(e,t,i){"use strict";i("02b1")},a4d3:function(e,t,i){"use strict";i("d9f5"),i("b4f8"),i("c513"),i("e9c4"),i("5a47")},b4f8:function(e,t,i){"use strict";var a=i("23e7"),r=i("d066"),s=i("1a2d"),n=i("577e"),o=i("5692"),l=(i=i("0b439"),o("string-to-symbol-registry")),c=o("symbol-to-string-registry");a({target:"Symbol",stat:!0,forced:!i},{for:function(e){var t;e=n(e);return s(l,e)?l[e]:(t=r("Symbol")(e),l[e]=t,c[t]=e,t)}})},c513:function(e,t,i){"use strict";var a=i("23e7"),r=i("1a2d"),s=i("d9b5"),n=i("0d51"),o=i("5692"),l=(i=i("0b439"),o("symbol-to-string-registry"));a({target:"Symbol",stat:!0,forced:!i},{keyFor:function(e){if(!s(e))throw new TypeError(n(e)+" is not a symbol");if(r(l,e))return l[e]}})},d9f5:function(e,t,i){"use strict";function a(e,t,i){var a=ie(Y,t);a&&delete Y[t],ae(e,t,i),a&&e!==Y&&ae(Y,t,a)}function r(e,t){var i=oe[e]=O(Z);return K(i,{type:Q,tag:e,description:t}),b||(i.description=t),i}function s(e,t,i){return e===Y&&s(le,t,i),_(e),t=k(t),_(i),(y(oe,t)?(i.enumerable?(y(e,J)&&e[J][t]&&(e[J][t]=!1),i=O(i,{enumerable:x(0,!1)})):(y(e,J)||ae(e,J,x(1,{})),e[J][t]=!0),de):ae)(e,t,i)}function n(e,t){_(e);var i=V(t);t=$(i).concat(d(i));return U(t,(function(t){b&&!f(o,i,t)||s(e,t,i[t])})),e}function o(e){e=k(e);var t=f(se,this,e);return!(this===Y&&y(oe,e)&&!y(le,e))&&(!(t||!y(this,e)||!y(oe,e)||y(this,J)&&this[J][e])||t)}function l(e,t){var i;e=V(e),t=k(t);if(e!==Y||!y(oe,t)||y(le,t))return!(i=ie(e,t))||!y(oe,t)||y(e,J)&&e[J][t]||(i.enumerable=!0),i}function c(e){e=re(V(e));var t=[];return U(e,(function(e){y(oe,e)||y(W,e)||ne(t,e)})),t}function d(e){var t=e===Y,i=(e=re(t?le:V(e)),[]);return U(e,(function(e){!y(oe,e)||t&&!y(Y,e)||ne(i,oe[e])})),i}var u=i("23e7"),m=i("da84"),f=i("c65b"),p=i("e330"),g=i("c430"),b=i("83ab"),h=i("04f8"),v=i("d039"),y=i("1a2d"),w=i("3a9b"),_=i("825a"),V=i("fc6a"),k=i("a04b"),C=i("577e"),x=i("5c6c"),O=i("7c73"),$=i("df75"),S=i("241c"),P=i("057f"),j=i("7418"),D=i("06cf"),T=i("9bf2"),E=i("37e8"),q=i("d1e7"),N=i("cb2d"),L=i("edd0"),z=i("5692"),I=i("f772"),W=i("d012"),A=i("90e3"),F=i("b622"),M=i("e538"),H=i("e065"),B=i("57b9"),G=i("d44e"),R=i("69f3"),U=i("b727").forEach,J=I("hidden"),Q="Symbol",K=(i="prototype",R.set),X=R.getterFor(Q),Y=Object[i],Z=(I=m.Symbol,I&&I[i]),ee=m.RangeError,te=m.TypeError,ie=(R=m.QObject,D.f),ae=T.f,re=P.f,se=q.f,ne=p([].push),oe=z("symbols"),le=z("op-symbols"),ce=(p=z("wks"),!R||!R[i]||!R[i].findChild),de=b&&v((function(){return 7!==O(ae({},"a",{get:function(){return ae(this,"a",{value:7}).a}})).a}))?a:ae;h||(N(Z=(I=function(){if(w(Z,this))throw new te("Symbol is not a constructor");var e=arguments.length&&void 0!==arguments[0]?C(arguments[0]):void 0,t=A(e),i=function(r){var s=void 0===this?m:this;s===Y&&f(i,le,r),y(s,J)&&y(s[J],t)&&(s[J][t]=!1),r=x(1,r);try{de(s,t,r)}catch(e){if(!(e instanceof ee))throw e;a(s,t,r)}};return b&&ce&&de(Y,t,{configurable:!0,set:i}),r(t,e)})[i],"toString",(function(){return X(this).tag})),N(I,"withoutSetter",(function(e){return r(A(e),e)})),q.f=o,T.f=s,E.f=n,D.f=l,S.f=P.f=c,j.f=d,M.f=function(e){return r(F(e),e)},b&&(L(Z,"description",{configurable:!0,get:function(){return X(this).description}}),g||N(Y,"propertyIsEnumerable",o,{unsafe:!0}))),u({global:!0,constructor:!0,wrap:!0,forced:!h,sham:!h},{Symbol:I}),U($(p),(function(e){H(e)})),u({target:Q,stat:!0,forced:!h},{useSetter:function(){ce=!0},useSimple:function(){ce=!1}}),u({target:"Object",stat:!0,forced:!h,sham:!b},{create:function(e,t){return void 0===t?O(e):n(O(e),t)},defineProperty:s,defineProperties:n,getOwnPropertyDescriptor:l}),u({target:"Object",stat:!0,forced:!h},{getOwnPropertyNames:c}),B(),G(I,Q),W[J]=!0},e01a:function(e,t,i){"use strict";var a,r,s,n,o,l,c,d=i("23e7"),u=i("83ab"),m=i("da84"),f=i("e330"),p=i("1a2d"),g=i("1626"),b=i("3a9b"),h=i("577e"),v=i("edd0"),y=(i=i("e893"),m.Symbol),w=y&&y.prototype;!u||!g(y)||"description"in w&&void 0===y().description||(a={},i(m=function(){var e=arguments.length<1||void 0===arguments[0]?void 0:h(arguments[0]),t=b(w,this)?new y(e):void 0===e?y():y(e);return""===e&&(a[t]=!0),t},y),(m.prototype=w).constructor=m,r="Symbol(description detection)"===String(y("description detection")),s=f(w.valueOf),n=f(w.toString),o=/^Symbol\((.*)\)[^)]+$/,l=f("".replace),c=f("".slice),v(w,"description",{configurable:!0,get:function(){var e=s(this);return p(a,e)?"":(e=n(e),""===(e=r?c(e,7,-1):l(e,o,"$1"))?void 0:e)}}),d({global:!0,constructor:!0,forced:!0},{Symbol:m}))},e065:function(e,t,i){"use strict";var a=i("428f"),r=i("1a2d"),s=i("e538"),n=i("9bf2").f;e.exports=function(e){var t=a.Symbol||(a.Symbol={});r(t,e)||n(t,e,{value:s.f(e)})}},e538:function(e,t,i){"use strict";i=i("b622"),t.f=i},fac5:function(e,t,i){"use strict";i.r(t),i("b0c0"),i("4e82"),i("a4d3"),i("e01a");var a=i("2909"),r=i("c7eb"),s=i("1da1"),n=i("ade3"),o=i("5530"),l=(i("d3b7"),i("159b"),i("14d9"),i("a9e3"),i("d81d"),i("a434"),i("99af"),i("2f62")),c=i("c4ad"),d=i("a069"),u=i("b0e7"),m=i("5334"),f=i("b7be"),p=i("c4c8"),g=i("6ea4");c={name:"storePersellCreate",components:{goodsList:c.default,uploadPictures:u.a,WangEditor:d.a,freightTemplate:m.a,steps:g.a},data:function(){return{stepList:["选择预售商品","填写基础信息","修改商品详情"],submitOpen:!1,spinShow:!1,isChoice:"",current:0,modalPic:!1,grid:{xl:12,lg:20,md:24,sm:24,xs:24},grid2:{xl:8,lg:8,md:12,sm:24,xs:24},gridPic:{xl:6,lg:8,md:12,sm:12,xs:12},gridBtn:{xl:4,lg:8,md:8,sm:8,xs:8},myConfig:{autoHeightEnabled:!1,initialFrameHeight:500,initialFrameWidth:"100%",UEDITOR_HOME_URL:"/UEditor/",serverUrl:""},modals:!1,modal_loading:!1,images:[],templateList:[],columns:[],specsData:[],picTit:"",tableIndex:0,formValidate:{images:[],info:"",title:"",image:"",unit_name:"",stock:1,sales:0,deliver_time:3,sort:0,status:1,section_time:[],description:"",id:0,product_id:0,num:1,deposit:1,temp_id:"",attrs:[],items:[]},ruleValidate:{image:[{required:!0,message:"请选择主图",trigger:"change"}],images:[{required:!0,type:"array",message:"请选择主图",trigger:"change"},{type:"array",min:1,message:"Choose two hobbies at best",trigger:"change"}],title:[{required:!0,message:"请输入预售名称",trigger:"blur"}],info:[{required:!0,message:"请输入预售简介",trigger:"blur"}],section_time:[{required:!0,type:"array",message:"请选择活动时间",trigger:"change"}],unit_name:[{required:!0,message:"请输入单位",trigger:"blur"}],price:[{required:!0,type:"number",message:"请输入预售价",trigger:"blur"}],cost:[{required:!0,type:"number",message:"请输入成本价",trigger:"blur"}],stock:[{required:!0,type:"number",message:"请输入库存",trigger:"blur"}],give_integral:[{required:!0,type:"number",message:"请输入赠送积分",trigger:"blur"}],effective_time:[{required:!0,type:"number",message:"请输入预售时效(单位 小时)",trigger:"blur"}],people:[{required:!0,type:"number",message:"请输入预售人数",trigger:"blur"}],num:[{required:!0,type:"number",message:"请输入购买数量限制",trigger:"blur"}],deposit:[{required:!0,type:"number",message:"请输入定金金额",trigger:"blur"}],once_num:[{required:!0,type:"number",message:"请输入单次购买数量限制",trigger:"blur"}],virtualPeople:[{required:!0,type:"number",message:"请输入虚拟成团补齐人数",trigger:"blur"}],temp_id:[{required:!0,message:"请选择运费模板",trigger:"change",type:"number"}]},copy:0}},computed:Object(o.a)(Object(o.a)({},Object(l.d)("media",["isMobile"])),{},{labelWidth:function(){return this.isMobile?void 0:"155px"},labelPosition:function(){return this.isMobile?"top":"right"}}),mounted:function(){0!=this.$route.params.id&&(this.copy=this.$route.params.copy,this.current=1,this.getInfo()),this.productGetTemplate()},methods:{getEditorContent:function(e){this.formValidate.description=e},freight:function(){this.$refs.template.id=0,this.$refs.template.isTemplate=!0},productAttrs:function(e){var t=this;Object(f.fb)(e.id,6).then((function(e){e=e.data.info,t.specsData=e.attrs,t.specsData.forEach((function(e,i){t.$set(t.specsData[i],"id",i)})),t.formValidate.items=e.items,t.columns=e.header})).catch((function(e){t.$message.error(e.msg)}))},changeCheckbox:function(e){this.formValidate.attrs=e},productGetTemplate:function(){var e=this;Object(p.u)().then((function(t){e.templateList=t.data}))},validate:function(e,t,i){!1===t&&this.$message.error(i)},getProductId:function(e){var t=this;this.modal_loading=!1,this.modals=!1,setTimeout((function(){var i;t.formValidate=(i={images:e.slider_image,info:e.store_info,title:e.store_name,image:e.image,unit_name:e.unit_name,stock:e.stock,sales:e.sales,sort:e.sort,section_time:[],deliver_time:3,num:1,deposit:1,description:e.description,id:0},Object(n.a)(i,"num",1),Object(n.a)(i,"status",1),Object(n.a)(i,"product_id",e.id),Object(n.a)(i,"temp_id",e.temp_id),i),t.productAttrs(e)}),500)},cancel:function(){this.modals=!1},onchangeTime:function(e){this.formValidate.section_time=e},getInfo:function(){var e=this;this.spinShow=!0,Object(f.db)(this.$route.params.id).then(function(){var t=Object(s.a)(Object(r.a)().mark((function t(i){var a,s,n,o;return Object(r.a)().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:for(o in a=e,s=i.data.info,e.formValidate=s,e.formValidate.virtualPeople=parseInt(e.formValidate.people-e.formValidate.people*(e.formValidate.virtual/100)),e.$set(e.formValidate,"items",s.attrs.items),e.columns=s.attrs.header,e.specsData=s.attrs.value,a.specsData.forEach((function(e,t){a.$set(a.specsData[t],"id",t)})),s.attrs,n=[],s.attrs.value)s.attrs.value[o]._checked&&n.push(s.attrs.value[o]);a.formValidate.attrs=n,e.spinShow=!1;case 14:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.spinShow=!1,e.$message.error(t)}))},next:function(e){var t=this,i=this;2===this.current?this.$refs[e].validate((function(e){if(!e)return!1;1==t.copy&&(t.formValidate.copy=1),t.formValidate.id=Number(t.$route.params.id)||0,t.submitOpen=!0,t.formValidate.virtual=parseInt((t.formValidate.people-t.formValidate.virtualPeople)/t.formValidate.people*100),Object(f.cb)(t.formValidate).then(function(){var e=Object(s.a)(Object(r.a)().mark((function e(i){return Object(r.a)().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.submitOpen=!1,t.$message.success(i.msg),setTimeout((function(){t.$router.push({path:t.$routeProStr+"/marketing/presell/index"})}),500);case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.submitOpen=!1,t.$message.error(e.msg)}))})):1===this.current?this.$refs[e].validate((function(e){if(!e)return t.$message.warning("请完善您的信息");if(i.formValidate.people<2)return i.$message.error("预售人数必须大于2");if(i.formValidate.num<0)return i.$message.error("购买数量限制必须大于0");if(!i.formValidate.attrs)return i.$message.error("请选择属性规格");for(var a in i.formValidate.attrs){if(i.formValidate.attrs[a].quota<=0)return i.$message.error("预售限量必须大于0");if(t.formValidate.attrs[a].quota>t.formValidate.attrs[a].stock)return t.$message.error("预售限量不能超过规格库存")}t.current+=1})):this.formValidate.image?this.current+=1:this.$message.warning("请选择商品")},step:function(){this.current--},getContent:function(e){this.formValidate.description=e},modalPicTap:function(e,t,i){this.modalPic=!0,this.isChoice="dan"===e?"单选":"多选",this.picTit=t,this.tableIndex=i},getPic:function(e){"danFrom"===this.picTit?this.formValidate.image=e.att_dir:(this.formValidate.attrs&&this.formValidate.attrs.length&&this.$set(this.specsData[this.tableIndex],"_checked",!0),this.specsData[this.tableIndex].pic=e.att_dir),this.modalPic=!1},getPicD:function(e){var t=this;this.images=e,this.images.map((function(e){t.formValidate.images.push(e.att_dir),t.formValidate.images=t.formValidate.images.splice(0,10)})),this.modalPic=!1},handleRemove:function(e){this.images.splice(e,1),this.formValidate.images.splice(e,1)},changeGoods:function(){var e=this;this.modals=!0,this.$nextTick((function(t){e.$refs.goodslist.getList(),e.$refs.goodslist.goodsCategory()}))},handleDragStart:function(e,t){this.dragging=t},handleDragEnd:function(e,t){this.dragging=null},handleDragOver:function(e){e.dataTransfer.dropEffect="move"},handleDragEnter:function(e,t){var i;e.dataTransfer.effectAllowed="move",t!==this.dragging&&(i=(e=Object(a.a)(this.formValidate.images)).indexOf(this.dragging),t=e.indexOf(t),e.splice.apply(e,[t,0].concat(Object(a.a)(e.splice(i,1)))),this.formValidate.images=e)}}},i("923c"),u=i("2877"),d=Object(u.a)(c,(function(){var e=this,t=e._self._c;return t("div",{directives:[{name:"loading",rawName:"v-loading",value:e.spinShow,expression:"spinShow"}]},[t("div",{staticClass:"i-layout-page-header"},[t("div",{staticClass:"i-layout-page-header"},[t("router-link",{attrs:{to:{path:e.$routeProStr+"/marketing/presell/index"}}},[t("el-button",{staticClass:"mr20",attrs:{icon:"ios-arrow-back",size:"small"}},[e._v("返回 ")])],1),t("span",{staticClass:"ivu-page-header-title mr20",domProps:{textContent:e._s(0!=e.$route.params.id?"编辑预售商品":"添加预售商品")}})],1)]),t("el-card",{staticClass:"ivu-mt",attrs:{bordered:!1,shadow:"never"}},[t("el-row",{staticClass:"mt30 acea-row row-middle row-center"},[t("el-col",{attrs:{span:20}},[t("steps",{attrs:{stepList:e.stepList,isActive:e.current}})],1),t("el-col",{attrs:{span:23}},[t("el-form",{ref:"formValidate",staticClass:"form mt30",attrs:{model:e.formValidate,rules:e.ruleValidate,"label-width":e.labelWidth,"label-position":e.labelPosition},on:{"on-validate":e.validate},nativeOn:{submit:function(e){e.preventDefault()}}},[0===e.current?t("el-form-item",{attrs:{label:"选择商品：",prop:"image_input"}},[t("div",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"picBox",on:{click:e.changeGoods}},[e.formValidate.image?t("div",{staticClass:"pictrue"},[t("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e.formValidate.image,expression:"formValidate.image"}]})]):t("div",{staticClass:"upLoad acea-row row-center-wrapper"},[t("i",{staticClass:"el-icon-goods",staticStyle:{"font-size":"24px"}})])])]):e._e(),t("el-row",{directives:[{name:"show",rawName:"v-show",value:1===e.current,expression:"current === 1"}]},[t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"商品主图：",prop:"image"}},[t("div",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"picBox",on:{click:function(t){return e.modalPicTap("dan","danFrom")}}},[e.formValidate.image?t("div",{staticClass:"pictrue"},[t("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e.formValidate.image,expression:"formValidate.image"}]})]):t("div",{staticClass:"upLoad acea-row row-center-wrapper"},[t("i",{staticClass:"el-icon-picture-outline",staticStyle:{"font-size":"24px"}})])])])],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"商品轮播图：",prop:"images"}},[t("div",{staticClass:"acea-row"},[e._l(e.formValidate.images,(function(i,a){return t("div",{key:a,staticClass:"pictrue",attrs:{draggable:"true"},on:{dragstart:function(t){return e.handleDragStart(t,i)},dragover:function(t){return t.preventDefault(),e.handleDragOver(t,i)},dragenter:function(t){return e.handleDragEnter(t,i)},dragend:function(t){return e.handleDragEnd(t,i)}}},[t("img",{directives:[{name:"lazy",rawName:"v-lazy",value:i,expression:"item"}]}),t("el-button",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"btndel",attrs:{shape:"circle",icon:"md-close"},nativeOn:{click:function(t){return e.handleRemove(a)}}})],1)})),e.formValidate.images.length<10?t("div",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"upLoad acea-row row-center-wrapper",on:{click:function(t){return e.modalPicTap("duo")}}},[t("i",{staticClass:"el-icon-picture-outline",staticStyle:{"font-size":"24px"}})]):e._e()],2)])],1),t("el-col",{attrs:{span:24}},[t("el-col",e._b({},"el-col",e.grid,!1),[t("el-form-item",{attrs:{label:"预售名称：",prop:"title","label-for":"title"}},[t("el-input",{attrs:{placeholder:"请输入预售名称","element-id":"title"},model:{value:e.formValidate.title,callback:function(t){e.$set(e.formValidate,"title",t)},expression:"formValidate.title"}})],1)],1)],1),t("el-col",{attrs:{span:24}},[t("el-col",e._b({},"el-col",e.grid,!1),[t("el-form-item",{attrs:{label:"预售简介：",prop:"info","label-for":"info"}},[t("el-input",{attrs:{placeholder:"请输入预售简介",type:"textarea",rows:4,"element-id":"info"},model:{value:e.formValidate.info,callback:function(t){e.$set(e.formValidate,"info",t)},expression:"formValidate.info"}})],1)],1)],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"预售活动时间：",prop:"section_time"}},[t("div",{staticClass:"acea-row row-middle"},[t("el-date-picker",{staticClass:"perW20",attrs:{clearable:"",editable:!1,type:"datetimerange",format:"yyyy-MM-dd HH:mm","value-format":"yyyy-MM-dd HH:mm","range-separator":"-","start-placeholder":"开始日期","end-placeholder":"结束日期"},on:{change:e.onchangeTime},model:{value:e.formValidate.section_time,callback:function(t){e.$set(e.formValidate,"section_time",t)},expression:"formValidate.section_time"}}),t("div",{staticClass:"ml10 grey"},[e._v("设置活动开启结束时间，用户可以在设置时间内发起参与预售")])],1)])],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"发货时间：",prop:"deliver_time"}},[t("div",{staticClass:"acea-row row-middle"},[t("span",{staticClass:"mr10"},[e._v("预售活动结束后")]),t("el-input-number",{attrs:{controls:!1,placeholder:"请输入发货时间",precision:0,min:1},model:{value:e.formValidate.deliver_time,callback:function(t){e.$set(e.formValidate,"deliver_time",t)},expression:"formValidate.deliver_time"}}),t("span",{staticClass:"ml10"},[e._v(" 天之内 ")]),t("div",{staticClass:"ml10 grey"})],1)])],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"运费模板：",prop:"temp_id"}},[t("div",{staticClass:"acea-row row-middle"},[t("el-select",{staticClass:"perW20",model:{value:e.formValidate.temp_id,callback:function(t){e.$set(e.formValidate,"temp_id",t)},expression:"formValidate.temp_id"}},e._l(e.templateList,(function(e){return t("el-option",{key:e.id,attrs:{value:e.id,label:e.name}})})),1),t("div",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"ml10 col",on:{click:e.freight}},[e._v("添加运费模板")])],1)])],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"总购买数量限制：",prop:"num"}},[t("div",{staticClass:"acea-row row-middle"},[t("el-input-number",{staticClass:"perW20",attrs:{controls:!1,min:1,placeholder:"请输入总数量限制",precision:0,"element-id":"num"},model:{value:e.formValidate.num,callback:function(t){e.$set(e.formValidate,"num",t)},expression:"formValidate.num"}}),t("div",{staticClass:"ml10 grey"},[e._v("\n                    该商品活动期间内，用户可购买的最大数量。例如设置为4，表示本次活动有效期内，每个用户最多可购买4件\n                  ")])],1)])],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"单位：",prop:"unit_name","label-for":"unit_name"}},[t("el-input",{staticClass:"perW20",attrs:{placeholder:"请输入单位","element-id":"unit_name"},model:{value:e.formValidate.unit_name,callback:function(t){e.$set(e.formValidate,"unit_name",t)},expression:"formValidate.unit_name"}})],1)],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"排序："}},[t("el-input-number",{staticClass:"perW10",attrs:{controls:!1,placeholder:"请输入排序","element-id":"sort",precision:0},model:{value:e.formValidate.sort,callback:function(t){e.$set(e.formValidate,"sort",t)},expression:"formValidate.sort"}})],1)],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"活动状态：",props:"status","label-for":"status"}},[t("el-switch",{staticClass:"defineSwitch",attrs:{"active-value":1,"inactive-value":0,size:"large","active-text":"上架","inactive-text":"下架"},model:{value:e.formValidate.status,callback:function(t){e.$set(e.formValidate,"status",t)},expression:"formValidate.status"}})],1)],1),t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"规格选择："}},[t("el-table",{attrs:{data:e.specsData},on:{"selection-change":e.changeCheckbox}},[t("el-table-column",{attrs:{type:"selection",width:"55"}}),e._l(e.columns,(function(i,a){return t("el-table-column",{key:a,attrs:{label:i.title,"min-width":i.minWidth},scopedSlots:e._u([{key:"default",fn:function(a){return[i.key?[t("div",[t("span",[e._v(e._s(a.row[i.key]))])])]:"pic"===i.slot?[a.row.pic?t("div",{staticClass:"pictrue pictrueTab"},[t("img",{directives:[{name:"lazy",rawName:"v-lazy",value:a.row.pic,expression:"scope.row.pic"}]})]):t("div",{staticClass:"upLoad pictrueTab acea-row row-center-wrapper"},[t("i",{staticClass:"el-icon-picture-outline",staticStyle:{"font-size":"24px"}})])]:e._e()]}}],null,!0)})}))],2)],1)],1)],1),t("el-row",{directives:[{name:"show",rawName:"v-show",value:2===e.current,expression:"current === 2"}]},[t("el-col",{attrs:{span:24}},[t("el-form-item",{attrs:{label:"内容："}},[t("WangEditor",{staticStyle:{width:"90%"},attrs:{content:e.formValidate.description},on:{editorContent:e.getEditorContent}})],1)],1)],1),t("el-form-item",[t("el-button",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"submission",attrs:{disabled:e.$route.params.id&&1===e.current||0===e.current},on:{click:e.step}},[e._v("上一步\n            ")]),t("el-button",{directives:[{name:"db-click",rawName:"v-db-click"}],staticClass:"submission",attrs:{type:"primary",disabled:e.submitOpen&&2===e.current},on:{click:function(t){return e.next("formValidate")}}},[e._v(e._s(2===e.current?"提交":"下一步"))])],1)],1)],1)],1)],1),t("el-dialog",{staticClass:"paymentFooter",attrs:{visible:e.modals,title:"商品列表",width:"1000px"},on:{"update:visible":function(t){e.modals=t}}},[t("goods-list",{ref:"goodslist",on:{getProductId:e.getProductId}})],1),t("el-dialog",{attrs:{visible:e.modalPic,width:"950px",title:"上传商品图","close-on-click-modal":!1},on:{"update:visible":function(t){e.modalPic=t}}},[e.modalPic?t("uploadPictures",{attrs:{isChoice:e.isChoice,gridBtn:e.gridBtn,gridPic:e.gridPic},on:{getPic:e.getPic,getPicD:e.getPicD}}):e._e()],1),t("freight-template",{ref:"template",on:{addSuccess:e.productGetTemplate}})],1)}),[],!1,null,"6b9a00a6",null);t.default=d.exports},ff90:function(e,t,i){"use strict";i("1764")}}]);