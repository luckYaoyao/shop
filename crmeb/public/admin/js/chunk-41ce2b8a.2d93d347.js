(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-41ce2b8a"],{"23a3":function(t,e,i){},"31b4":function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.FromData?i("div",[i("Modal",{attrs:{scrollable:"","footer-hide":"",closable:"",title:t.FromData.title,"z-index":1,width:"700"},on:{"on-cancel":t.cancel},model:{value:t.modals,callback:function(e){t.modals=e},expression:"modals"}},[["/marketing/coupon/save.html"===t.FromData.action?i("div",{staticClass:"radio acea-row row-middle"},[i("div",{staticClass:"name ivu-form-item-content"},[t._v("优惠券类型")]),i("Radio-group",{on:{"on-change":t.couponsType},model:{value:t.type,callback:function(e){t.type=e},expression:"type"}},[i("Radio",{attrs:{label:0}},[t._v("通用券")]),i("Radio",{attrs:{label:1}},[t._v("品类券")]),i("Radio",{attrs:{label:2}},[t._v("商品券")])],1)],1):t._e()],i("form-create",{ref:"fc",staticClass:"formBox",attrs:{option:t.config,rule:Array.from(t.FromData.rules),handleIcon:"false"},on:{"on-submit":t.onSubmit}})],2)],1):t._e()},s=[],a=(i("8e6e"),i("ac6a"),i("456d"),i("bd86")),r=i("9860"),o=i.n(r),c=i("6b6c"),l=i("2f62");function u(t,e){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),i.push.apply(i,n)}return i}function d(t){for(var e=1;e<arguments.length;e++){var i=null!=arguments[e]?arguments[e]:{};e%2?u(Object(i),!0).forEach((function(e){Object(a["a"])(t,e,i[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(i)):u(Object(i)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(i,e))}))}return t}var m={name:"edit",components:{formCreate:o.a.$form()},computed:d({},Object(l["e"])("userLevel",["taskId","levelId"])),props:{FromData:{type:Object,default:null}},data:function(){return{modals:!1,type:0,config:{global:{upload:{props:{onSuccess:function(t,e){200===t.status?e.url=t.data.src:this.Message.error(t.msg)}}}}}}},methods:{couponsType:function(){this.$parent.addType(this.type)},onSubmit:function(t){var e=this,i={};i=t,Object(c["a"])({url:this.FromData.action,method:this.FromData.method,data:i}).then((function(t){e.$parent.getList(),e.$Message.success(t.msg),e.modals=!1,setTimeout((function(){e.$emit("submitFail")}),1e3)})).catch((function(t){e.$Message.error(t.msg)}))},cancel:function(){this.type=0}}},g=m,f=(i("7577"),i("2877")),p=Object(f["a"])(g,n,s,!1,null,"345ab15a",null);e["a"]=p.exports},"4c74":function(t,e,i){},"590d":function(t,e,i){t.exports=i.p+"img/pclogin.3d27e2c2.png"},"713f":function(t,e,i){t.exports=i.p+"img/bluesgin.032bae4b.png"},7577:function(t,e,i){"use strict";var n=i("4c74"),s=i.n(n);s.a},8593:function(t,e,i){"use strict";i.d(e,"j",(function(){return s})),i.d(e,"h",(function(){return a})),i.d(e,"i",(function(){return r})),i.d(e,"N",(function(){return o})),i.d(e,"n",(function(){return c})),i.d(e,"l",(function(){return l})),i.d(e,"m",(function(){return u})),i.d(e,"k",(function(){return d})),i.d(e,"C",(function(){return m})),i.d(e,"u",(function(){return g})),i.d(e,"B",(function(){return f})),i.d(e,"z",(function(){return p})),i.d(e,"w",(function(){return b})),i.d(e,"x",(function(){return h})),i.d(e,"y",(function(){return v})),i.d(e,"A",(function(){return _})),i.d(e,"K",(function(){return x})),i.d(e,"O",(function(){return y})),i.d(e,"o",(function(){return O})),i.d(e,"d",(function(){return C})),i.d(e,"f",(function(){return w})),i.d(e,"c",(function(){return k})),i.d(e,"e",(function(){return j})),i.d(e,"g",(function(){return L})),i.d(e,"r",(function(){return P})),i.d(e,"p",(function(){return E})),i.d(e,"q",(function(){return $})),i.d(e,"E",(function(){return I})),i.d(e,"F",(function(){return F})),i.d(e,"J",(function(){return R})),i.d(e,"I",(function(){return D})),i.d(e,"a",(function(){return T})),i.d(e,"b",(function(){return U})),i.d(e,"t",(function(){return B})),i.d(e,"M",(function(){return M})),i.d(e,"v",(function(){return S})),i.d(e,"Q",(function(){return G})),i.d(e,"P",(function(){return A})),i.d(e,"D",(function(){return V})),i.d(e,"G",(function(){return H})),i.d(e,"H",(function(){return N})),i.d(e,"s",(function(){return z})),i.d(e,"L",(function(){return q}));var n=i("6b6c");function s(t){return Object(n["a"])({url:"setting/config_class",method:"get",params:t})}function a(t){return Object(n["a"])({url:"setting/config_class/create",method:"get"})}function r(t){return Object(n["a"])({url:"setting/config_class/".concat(t,"/edit"),method:"get"})}function o(t){return Object(n["a"])({url:"setting/config_class/set_status/".concat(t.id,"/").concat(t.status),method:"PUT"})}function c(t){return Object(n["a"])({url:"setting/config",method:"get",params:t})}function l(t){return Object(n["a"])({url:"setting/config/create",method:"get",params:t})}function u(t){return Object(n["a"])({url:"/setting/config/".concat(t,"/edit"),method:"get"})}function d(t,e){return Object(n["a"])({url:"setting/config/set_status/".concat(t,"/").concat(e),method:"PUT"})}function m(t){return Object(n["a"])({url:"setting/group",method:"get",params:t})}function g(t){return Object(n["a"])({url:t.url,method:t.method,data:t.datas})}function f(t){return Object(n["a"])({url:"setting/group/".concat(t),method:"get"})}function p(t,e){return Object(n["a"])({url:e,method:"get",params:t})}function b(t,e){return Object(n["a"])({url:e,method:"get",params:t})}function h(t,e){return Object(n["a"])({url:e,method:"get",params:t})}function v(t,e){return Object(n["a"])({url:e,method:"get",params:t})}function _(t){return Object(n["a"])({url:t,method:"PUT"})}function x(t){return Object(n["a"])({url:"system/log/search_admin",method:"GET"})}function y(t){return Object(n["a"])({url:"system/log",method:"GET",params:t})}function O(){return Object(n["a"])({url:"system/file",method:"GET"})}function C(){return Object(n["a"])({url:"system/backup",method:"GET"})}function w(t){return Object(n["a"])({url:"system/backup/read",method:"GET",params:t})}function k(t){return Object(n["a"])({url:"system/backup/backup",method:"put",data:t})}function j(t){return Object(n["a"])({url:"system/backup/optimize",method:"put",data:t})}function L(t){return Object(n["a"])({url:"system/backup/repair",method:"put",data:t})}function P(t){return Object(n["a"])({url:"system/backup/file_list",method:"GET"})}function E(t){return Object(n["a"])({url:"backup/download",method:"get",params:t})}function $(t){return Object(n["a"])({url:"system/backup/import",method:"POST",data:t})}function I(t){return Object(n["a"])({url:"system/file/opendir",method:"GET",params:t})}function F(t){return Object(n["a"])({url:"system/file/openfile?filepath=".concat(t),method:"GET"})}function R(t){return Object(n["a"])({url:"system/file/savefile",method:"post",data:t})}function D(t){return Object(n["a"])({url:"system/replace_site_url",method:"post",data:t})}function T(){return Object(n["a"])({url:"auth",method:"get"})}function U(t){return Object(n["a"])({url:"auth_apply",method:"post",data:t})}function B(){return Object(n["a"])({url:"setting/get_kf_adv",method:"get"})}function M(t){return Object(n["a"])({url:"setting/set_kf_adv",method:"post",data:t})}function S(){return Object(n["a"])({url:"setting/group_all",method:"get"})}function G(t){return Object(n["a"])({url:"system/version_list",method:"get",params:t})}function A(){return Object(n["a"])({url:"system/version_crate",method:"get"})}function V(t){return Object(n["a"])({url:"setting/group_data/save_all",method:"POST",data:t})}function H(t){return Object(n["a"])({url:"setting/config/get_system/".concat(t),method:"get"})}function N(t){return Object(n["a"])({url:"setting/config/save_basics",method:"POST",data:t})}function z(){return Object(n["a"])({url:"setting/get_user_agreement",method:"get"})}function q(t){return Object(n["a"])({url:"setting/set_user_agreement",method:"post",data:t})}},"89c2":function(t,e,i){t.exports=i.p+"img/oragesgin.00077d3a.png"},"90be":function(t,e,i){t.exports=i.p+"img/redsgin.d8b0c12e.png"},"943f":function(t,e,i){t.exports=i.p+"img/pinksgin.0e1c51b4.png"},bceb:function(t,e,i){t.exports=i.p+"img/greesgin.43ae54b5.png"},cb18:function(t,e,i){"use strict";i.r(e);var n=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{style:t.bgcolors},[n("div",{staticClass:"i-layout-page-header"},[n("span",{staticClass:"ivu-page-header-title mr20"},[t._v(t._s(t.$route.meta.title))]),n("div",[n("div",{staticStyle:{float:"right"}},[n("Button",{staticClass:"bnt",attrs:{type:"primary"},on:{click:t.save}},[t._v("保存")])],1)])]),n("div",{staticClass:"box-wrapper"},[!t.$route.params.id&&t.groupAll.length?n("div",{staticClass:"left-wrapper"},[n("Menu",{attrs:{theme:t.theme3,"active-name":t.sortName,width:"auto"}},[n("MenuGroup",[t._l(t.groupAll,(function(e,i){return n("MenuItem",{key:i,staticClass:"menu-item",attrs:{name:e.config_name},nativeOn:{click:function(i){return t.edits(e)}}},[t._v("\n\t\t\t\t\t\t\t    \t"+t._s(e.name)+"\n\t\t\t\t\t\t\t\t\t")])})),n("MenuItem",{staticClass:"menu-item",attrs:{name:t.a},nativeOn:{click:function(e){return t.edits(1)}}},[t._v("\n\t\t\t\t\t\t\t\t\t\tapp隐私协议\n\t\t\t\t\t\t\t\t\t")])],2)],1)],1):t._e(),"user_recharge_quota"==t.name?n("div",{staticClass:"iframe"},[n("div",{staticClass:"iframe-boxs"},[n("div",{staticClass:"moneyBox"},[t._m(0),n("div",{staticClass:"moneyBox_content"},[t._m(1),n("div",{staticClass:"box3"},[t._l(t.sginList.list,(function(e,i){return n("div",{directives:[{name:"show",rawName:"v-show",value:0!=e.status,expression:"item.status != 0"}],key:i,staticClass:"box3_box"},[n("div",[t._v(t._s(e.price)),n("i",{staticClass:"font"},[t._v("元")])]),n("div",{staticClass:"font"},[t._v("赠送:"+t._s(e.give_money)+"元")])])})),t._m(2)],2),t._m(3),n("div",{staticClass:"box5"},[t._v("立即充值")])])])])]):t._e(),"admin_login_slide"==t.name?n("div",{staticClass:"pciframe",attrs:{bordered:!1,"dis-hover":""}},[n("img",{staticClass:"pciframe-box",attrs:{src:i("590d")}}),n("div",{staticClass:"pcmoddile_goods"},[""==t.tabList.list?n("div",{staticClass:"nofont"},[t._v("暂无照片，请添加~")]):n("swiper",{staticClass:"pcswiperimg_goods",attrs:{options:t.swiperOption}},t._l(t.tabList.list,(function(t,e){return n("swiper-slide",{key:e,staticClass:"spcwiperimg_goods"},[n("img",{attrs:{src:t.slide}})])})),1)],1)]):t._e(),"integral_shop_banner"==t.name?n("div",{staticClass:"iframe",attrs:{bordered:!1}},[n("div",{staticClass:"iframe-box"},[n("img",{staticStyle:{width:"100%"},attrs:{src:i("d50d")}}),n("div",{staticClass:"moddile_goods"},[""==t.tabList.list?n("div",{staticClass:"nofonts"},[t._v("暂无照片，请添加~")]):n("swiper",{staticClass:"pcswiperimg_goods",attrs:{options:t.swiperOption}},t._l(t.tabList.list,(function(t,e){return n("swiper-slide",{key:e,staticClass:"swiperimg_goods"},[n("img",{attrs:{src:t.img}})])})),1)],1)])]):t._e(),"sign_day_num"!=t.name&&"admin_login_slide"!=t.name&&"user_recharge_quota"!=t.name&&"integral_shop_banner"!=t.name&&1!=t.a?n("div",{staticClass:"iframe",attrs:{bordered:!1,"dis-hover":""}},[n("iframe",{staticClass:"iframe-box",attrs:{src:t.url,frameborder:"0"}}),n("div",{staticClass:"moddile"}),"routine_home_bast_banner"==t.name||"routine_home_hot_banner"==t.name||"routine_home_new_banner"==t.name||"routine_home_benefit_banner"==t.name?n("div",{staticClass:"moddile_box"},[""==t.tabList.list?n("div",{staticClass:"nofonts"},[t._v("暂无照片，请添加~")]):n("swiper",{staticClass:"swiperimg",attrs:{options:t.swiperOption}},t._l(t.tabList.list,(function(t,e){return n("swiper-slide",{key:e,staticClass:"swiperimg"},[n("img",{attrs:{src:t.img}})])})),1)],1):t._e(),"combination_banner"==t.name?n("div",{staticClass:"moddile_goods"},[""==t.tabList.list?n("div",{staticClass:"nofonts"},[t._v("暂无照片，请添加~")]):n("swiper",{staticClass:"swiperimg_goods",attrs:{options:t.swiperOption}},t._l(t.tabList.list,(function(t,e){return n("swiper-slide",{key:e,staticClass:"swiperimg_goods"},[n("img",{attrs:{src:t.img}})])})),1)],1):t._e()]):t._e(),"sign_day_num"==t.name?n("div",{staticClass:"iframe",attrs:{bordered:!1}},[n("div",{staticClass:"iframe-box"},[1==t.bgimg?n("img",{attrs:{src:i("713f")}}):t._e(),2==t.bgimg?n("img",{attrs:{src:i("bceb")}}):t._e(),3==t.bgimg?n("img",{attrs:{src:i("90be")}}):t._e(),4==t.bgimg?n("img",{attrs:{src:i("943f")}}):t._e(),5==t.bgimg?n("img",{attrs:{src:i("89c2")}}):t._e()])]):t._e(),1==t.a?n("div",{staticClass:"iframe",attrs:{bordered:!1}},[n("div",{staticClass:"agreement-box"},[n("div",{staticClass:"template"}),n("div",{staticClass:"htmls_box"},[n("div",{staticClass:"htmls_top"},[t._v("服务协议与隐私政策")]),t._m(4),n("div",{staticClass:"htmls",domProps:{innerHTML:t._s(t.formValidate.content)}})])])]):t._e(),1==t.a?n("div",{staticStyle:{"margin-left":"40px"}},[n("div",{staticClass:"table_box"},[n("div",{attrs:{type:"flex"}},[n("div",t._b({},"div",t.grid,!1),[n("div",{staticClass:"title"},[t._v("隐私权限页面展示：")])])]),n("div",[n("Form",{ref:"formValidate",staticClass:"form",attrs:{model:t.formValidate,rules:t.ruleValidate,"label-width":t.labelWidth,"label-position":t.labelPosition},nativeOn:{submit:function(t){t.preventDefault()}}},[n("div",{staticClass:"goodsTitle acea-row"}),n("FormItem",{staticStyle:{margin:"0px"},attrs:{label:"",prop:"content"}},[n("vue-ueditor-wrap",{attrs:{config:t.myConfig},on:{beforeInit:t.addCustomDialog},model:{value:t.formValidate.content,callback:function(e){t.$set(t.formValidate,"content",e)},expression:"formValidate.content"}})],1)],1)],1)])]):t._e(),"sign_day_num"==t.name?n("div",{staticStyle:{"margin-left":"20px"}},[n("div",{staticClass:"table_box"},[n("div",{attrs:{type:"flex"}},[n("div",t._b({},"div",t.grid,!1),[n("div",{staticClass:"title"},[t._v("签到天数设置")]),n("Button",{staticStyle:{"margin-left":"14px","margin-top":"30px"},attrs:{type:"primary",icon:"md-add"},on:{click:function(e){return t.groupAdd("添加数据")}}},[t._v("添加数据")])],1)]),n("div",{staticClass:"table"},[n("Table",{ref:"table",staticClass:"mt25",attrs:{columns:t.columns1,data:t.cmsList,loading:t.loading,"highlight-row":"","no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果"},scopedSlots:t._u([{key:"status",fn:function(e){var i=e.row;e.index;return[n("i-switch",{attrs:{value:i.status,"true-value":1,"false-value":0,size:"large"},on:{"on-change":function(e){return t.onchangeIsShow(i)}},model:{value:i.status,callback:function(e){t.$set(i,"status",e)},expression:"row.status"}},[n("span",{attrs:{slot:"open"},slot:"open"},[t._v("显示")]),n("span",{attrs:{slot:"close"},slot:"close"},[t._v("隐藏")])])]}},{key:"action",fn:function(e){var i=e.row,s=e.index;return[n("a",{on:{click:function(e){return t.edit(i,"编辑")}}},[t._v("编辑")]),n("Divider",{attrs:{type:"vertical"}}),n("a",{on:{click:function(e){return t.del(i,"删除这条信息",s)}}},[t._v("删除")])]}}],null,!1,1790299090)})],1)])]):t._e(),"user_recharge_quota"==t.name?n("div",{staticStyle:{"margin-left":"20px"}},[n("div",{staticClass:"table_box"},[n("div",{attrs:{type:"flex"}},[n("div",t._b({},"div",t.grid,!1),[n("div",{staticClass:"title"},[t._v("充值金额设置")]),n("Button",{staticStyle:{"margin-left":"14px","margin-top":"30px"},attrs:{type:"primary",icon:"md-add"},on:{click:function(e){return t.groupAdd("添加数据")}}},[t._v("添加数据")])],1)]),n("div",{staticClass:"table"},[n("Table",{ref:"table",staticClass:"mt25",attrs:{columns:t.columns1,data:t.sginList.list,loading:t.loading,"highlight-row":"","no-userFrom-text":"暂无数据","no-filtered-userFrom-text":"暂无筛选结果"},scopedSlots:t._u([{key:"status",fn:function(e){var i=e.row;e.index;return[n("i-switch",{attrs:{value:i.status,"true-value":1,"false-value":0,size:"large"},on:{"on-change":function(e){return t.onchangeIsShow(i)}},model:{value:i.status,callback:function(e){t.$set(i,"status",e)},expression:"row.status"}},[n("span",{attrs:{slot:"open"},slot:"open"},[t._v("显示")]),n("span",{attrs:{slot:"close"},slot:"close"},[t._v("隐藏")])])]}},{key:"action",fn:function(e){var i=e.row,s=e.index;return[n("a",{on:{click:function(e){return t.edit(i,"编辑")}}},[t._v("编辑")]),n("Divider",{attrs:{type:"vertical"}}),n("a",{on:{click:function(e){return t.del(i,"删除这条信息",s)}}},[t._v("删除")])]}}],null,!1,1790299090)})],1)])]):n("div",["sign_day_num"!=t.name&&1!=t.a?n("div",{class:"admin_login_slide"!=t.name?"content":"contents"},[n("div",{staticClass:"right-box"},[n("div",{staticClass:"hot_imgs"},["admin_login_slide"==t.name?n("div",{staticClass:"title"},[t._v("幻灯片设置")]):n("div",{staticClass:"title"},[t._v("轮播图设置")]),n("div",{staticClass:"title-text"},[t._v("建议尺寸：690 * 240px，拖拽图片可调整图片顺序哦，最多添加五张")]),n("div",{staticClass:"list-box"},["admin_login_slide"==t.name?n("draggable",{staticClass:"dragArea list-group Bbox",attrs:{list:t.tabList.list,group:"peoples",handle:".move-icon"}},t._l(t.tabList.list,(function(e,i){return n("div",{key:i,staticClass:"items"},[n("div",{staticClass:"move-icon"},[n("span",{staticClass:"iconfont icondrag2"})]),n("div",{staticClass:"img-box",on:{click:function(e){return t.modalPicTap("单选",i)}}},[e.slide?n("img",{attrs:{src:e.slide,alt:""}}):n("div",{staticClass:"upload-box"},[n("Icon",{attrs:{type:"ios-camera-outline",size:"36"}})],1),n("div",{staticClass:"delect-btn",on:{click:function(n){return n.stopPropagation(),t.bindDelete(e,i)}}},[n("Icon",{attrs:{type:"md-close-circle",size:"26"}})],1)]),n("div",{staticClass:"info"})])})),0):n("draggable",{staticClass:"dragArea list-group",attrs:{list:t.tabList.list,group:"peoples",handle:".move-icon"}},t._l(t.tabList.list,(function(e,i){return n("div",{key:i,staticClass:"item"},[n("div",{staticClass:"move-icon"},[n("span",{staticClass:"iconfont icondrag2"})]),n("div",{staticClass:"img-box",on:{click:function(e){return t.modalPicTap("单选",i)}}},[e.img?n("img",{attrs:{src:e.img,alt:""}}):n("div",{staticClass:"upload-box"},[n("Icon",{attrs:{type:"ios-camera-outline",size:"36"}})],1),n("div",{staticClass:"delect-btn",on:{click:function(n){return n.stopPropagation(),t.bindDelete(e,i)}}},[n("Icon",{attrs:{type:"md-close-circle",size:"26"}})],1)]),n("div",{staticClass:"info"},[n("div",{staticClass:"info-item"},[n("span",[t._v("图片名称：")]),n("div",{staticClass:"input-box"},[n("Input",{attrs:{placeholder:"请填写名称"},model:{value:e.comment,callback:function(i){t.$set(e,"comment",i)},expression:"item.comment"}})],1)]),n("div",{staticClass:"info-item"},[n("span",[t._v("链接地址：")]),n("div",{staticClass:"input-box",on:{click:function(e){return t.link(i)}}},[n("Input",{attrs:{icon:"ios-arrow-forward",readonly:"",placeholder:"选择链接"},model:{value:e.link,callback:function(i){t.$set(e,"link",i)},expression:"item.link"}})],1)])])])})),0),n("div",[n("Modal",{attrs:{width:"950px",scrollable:"","footer-hide":"",closable:"",title:"上传商品图","mask-closable":!1,"z-index":999},model:{value:t.modalPic,callback:function(e){t.modalPic=e},expression:"modalPic"}},[t.modalPic?n("uploadPictures",{attrs:{isChoice:t.isChoice,gridBtn:t.gridBtn,gridPic:t.gridPic},on:{getPic:t.getPic}}):t._e()],1)],1)],1),[n("div",{staticClass:"add-btn"},[n("Button",{staticStyle:{width:"100px",height:"35px","background-color":"#1890FF",color:"#FFFFFF"},attrs:{type:"primary",ghost:""},on:{click:t.addBox}},[t._v("添加图片\n\t\t\t\t\t\t\t               ")])],1)]],2)])]):t._e()])]),n("linkaddress",{ref:"linkaddres",on:{linkUrl:t.linkUrl}})],1)},s=[function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"box1"},[i("div",{staticClass:"font1"},[t._v("我的余额")]),i("div",[t._v("￥ "),i("i",{staticClass:"font2"},[t._v("0.00")])])])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"box2"},[i("div",[t._v("账户充值")]),i("div",[t._v("佣金导入")])])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"box3_box"},[i("div",{staticClass:"other"},[t._v("其他")])])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"box4"},[i("div",{staticClass:"tips"},[t._v("注意事项：")]),i("div",{staticClass:"tips-samll"},[i("p",[t._v("充值后帐户的金额不能提现，可用于商城消费使用。")]),i("p",[t._v("佣金导入账户之后不能再次导出、不可提现。")]),i("p",[t._v("账户充值出现问题可联系商城客服，也可拨打商城客服热线：40088888889。")])])])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"htmls_font"},[i("div",{staticClass:"ok"},[t._v("我同意")]),i("div",[t._v("不同意")])])}],a=(i("8e6e"),i("456d"),i("ac6a"),i("96cf"),i("3b8d")),r=(i("7f7f"),i("a481"),i("bd86")),o=i("6625"),c=i.n(o),l=i("d708"),u=i("f478"),d=i("2f62"),m=i("31b4"),g=i("8593"),f=i("310e"),p=i.n(f),b=i("b0e7"),h=i("7af3");function v(t,e){var i=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),i.push.apply(i,n)}return i}function _(t){for(var e=1;e<arguments.length;e++){var i=null!=arguments[e]?arguments[e]:{};e%2?v(Object(i),!0).forEach((function(e){Object(r["a"])(t,e,i[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(i)):v(Object(i)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(i,e))}))}return t}var x={name:"list",components:{editFrom:m["a"],draggable:p.a,uploadPictures:b["a"],linkaddress:h["a"],VueUeditorWrap:c.a},computed:_({bgcolors:function(){return{"--color-theme":this.bgCol}},labelWidth:function(){return this.isMobile?void 0:120},labelPosition:function(){return this.isMobile?"top":"right"}},Object(d["e"])("admin/layout",["menuCollapse"])),data:function(){return{formValidate:{content:""},ruleValidate:{},myConfig:{autoHeightEnabled:!1,initialFrameHeight:500,initialFrameWidth:"100%",UEDITOR_HOME_URL:"/admin/UEditor/",serverUrl:""},a:0,bgimg:0,columns1:[],bgCol:"",name:"routine_home_bast_banner",grid:{xl:7,lg:7,md:12,sm:24,xs:24},loading:!1,sginList:[],swiperOption:{pagination:{el:".swiper-pagination"},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},autoplay:{delay:2e3,disableOnInteraction:!1},loop:!1},url:"",BaseURL:l["a"].apiBaseURL.replace(/adminapi/,""),pageId:0,theme3:"light",tabList:[],lastObj:{add_time:"",comment:"",gid:"",id:"",img:"",link:"",sort:"",status:1},isChoice:"单选",modalPic:!1,gridPic:{xl:6,lg:8,md:12,sm:12,xs:12},gridBtn:{xl:4,lg:8,md:8,sm:8,xs:8},groupAll:[],activeIndex:0,sortName:null,activeIndexs:0,cmsList:[],loadingExist:!1}},created:function(){this.color()},mounted:function(){this.getGroupAll(),this.info(),this.url=this.BaseURL+"pages/columnGoods/HotNewGoods/index?type=1"},methods:{linkUrl:function(t){this.tabList.list[this.activeIndexs].link=t},color:function(){var t=this;Object(u["g"])("color_change").then((function(e){switch(e.data.status){case 1:t.bgCol="#3875EA",t.bgimg=1;break;case 2:t.bgCol="#00C050",t.bgimg=2;break;case 3:t.bgCol="#E93323",t.bgimg=3;break;case 4:t.bgCol="#FF448F",t.bgimg=4;break;case 5:t.bgCol="#FE5C2D",t.bgimg=5;break}}))},groupAdd:function(){var t=this;this.$modalForm(Object(g["w"])({gid:this.pageId,config_name:this.name},"setting/group_data/create")).then((function(){t.url=t.BaseURL+"pages/users/user_sgin/index",t.info()}))},info:function(){var t=this;Object(g["z"])({config_name:this.name},"setting/group_data").then(function(){var e=Object(a["a"])(regeneratorRuntime.mark((function e(i){return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:t.tabList=i.data,"admin_login_slide"==t.name?t.tabList.list.forEach((function(t,e,i){"string"!=typeof t.slide&&"undefined"!=t.slide&&(t.slide=t.slide[0])})):"sign_day_num"==t.name?t.cmsList=i.data.list:"user_recharge_quota"==t.name?t.sginList=i.data:t.tabList.list.forEach((function(t,e,i){"string"!=typeof t.img&&"undefined"!=t.img&&(t.img=t.img[0])}));case 2:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.loading=!1,t.$Message.error(e.msg)}))},edits:function(t){if(this.pageId=t.id,this.name=t.config_name,1==t)this.a=1,this.getAgreement();else switch(this.info(),this.a=0,t.config_name){case"routine_home_bast_banner":this.url=this.BaseURL+"pages/columnGoods/HotNewGoods/index?type=1&name=精品推荐";break;case"sign_day_num":this.url="",this.getListHeader();break;case"combination_banner":this.url=this.BaseURL+"pages/activity/goods_combination/index";break;case"routine_home_hot_banner":this.url=this.BaseURL+"pages/columnGoods/HotNewGoods/index?type=2&name=热门榜单";break;case"routine_home_new_banner":this.url=this.BaseURL+"pages/columnGoods/HotNewGoods/index?type=3&name=首发新品";break;case"routine_home_benefit_banner":this.url=this.BaseURL+"pages/columnGoods/HotNewGoods/index?type=4&name=促销单品";break;case"user_recharge_quota":this.url="",this.getListHeader();break;case"admin_login_slide":this.url="";break;case"integral_shop_banner":this.url="";break}},addBox:function(){if(0==this.tabList.list.length)this.tabList.list.push(this.lastObj),this.lastObj={add_time:"",comment:"",gid:"",id:"",img:"",link:"",sort:"",status:1};else if(5==this.tabList.list.length)this.$Message.warning("最多添加五张呦");else{var t=JSON.parse(JSON.stringify(this.lastObj));this.tabList.list.push(t)}},bindDelete:function(t,e){this.tabList.list.splice(e,1)},modalPicTap:function(t,e){this.activeIndex=e,this.modalPic=!0},getPic:function(t){var e=this;this.$nextTick((function(){"admin_login_slide"==e.name?e.tabList.list[e.activeIndex].slide=t.att_dir:e.tabList.list[e.activeIndex].img=t.att_dir,e.modalPic=!1}))},save:function(){var t=this;1==this.a?this.onsubmit("formValidate"):(this.loadingExist=!0,Object(g["D"])({gid:this.pageId,config_name:this.name,data:this.tabList.list}).then((function(e){t.loadingExist=!1,t.$Message.success(e.msg)})).catch((function(e){t.loadingExist=!1,t.$Message.error(e.msg)})))},link:function(t){this.activeIndexs=t,this.$refs.linkaddres.modals=!0},getListHeader:function(){var t=this;this.loading=!0,Object(g["y"])({config_name:this.name},"setting/sign_data/header").then((function(e){var i=e.data,n=i.header;t.columns1=n,t.loading=!1})).catch((function(e){t.loading=!1,t.$Message.error(e.msg)}))},edit:function(t){var e=this;this.$modalForm(Object(g["x"])({gid:this.pageId,config_name:this.name},"setting/group_data/"+t.id+"/edit")).then((function(){e.info(),e.url=e.BaseURL+"pages/users/user_sgin/index"}))},del:function(t,e,i){var n=this,s={title:e,num:i,url:"setting/group_data/"+t.id,method:"DELETE",ids:""};this.$modalSure(s).then((function(t){n.info(),n.$Message.success(t.msg)})).catch((function(t){n.$Message.error(t.msg)}))},onchangeIsShow:function(t){var e=this;Object(g["A"])("setting/group_data/set_status/"+t.id+"/"+t.status).then(function(){var t=Object(a["a"])(regeneratorRuntime.mark((function t(i){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.url=e.BaseURL+"/pages/users/user_sgin/index",e.$Message.success(i.msg),e.info();case 3:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.url=e.BaseURL+"/pages/users/user_sgin/index",e.$Message.error(t.msg)}))},getGroupAll:function(){var t=this;Object(g["v"])().then(function(){var e=Object(a["a"])(regeneratorRuntime.mark((function e(i){return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:t.groupAll=i.data,t.sortName=i.data[0].config_name,t.pageId=i.data[0].id;case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.$Message.error(e.msg)}))},getContent:function(t){this.formValidate.content=t},onsubmit:function(t){var e=this;this.$refs[t].validate((function(t){if(!t)return!1;Object(g["L"])(e.formValidate).then(function(){var t=Object(a["a"])(regeneratorRuntime.mark((function t(i){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.$Message.success(i.msg);case 1:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}()).catch((function(t){e.$Message.error(t.msg)}))}))},getAgreement:function(){var t=this;Object(g["s"])().then(function(){var e=Object(a["a"])(regeneratorRuntime.mark((function e(i){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:n=i.data,t.formValidate={content:n.content};case 2:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){t.loading=!1,t.$Message.error(e.msg)}))},addCustomDialog:function(t){window.UE.registerUI("test-dialog",(function(t,e){var i=new window.UE.ui.Dialog({iframeUrl:"/admin/widget.images/index.html?fodder=dialog",editor:t,name:e,title:"上传图片",cssRules:"width:960px;height:550px;padding:20px;"});this.dialog=i;var n=new window.UE.ui.Button({name:"dialog-button",title:"上传图片",cssRules:"background-image: url(../../../assets/images/icons.png);background-position: -726px -77px;",onclick:function(){i.render(),i.open()}});return n}),37)}}},y=x,O=(i("d334"),i("2877")),C=Object(O["a"])(y,n,s,!1,null,"19282d3a",null);e["default"]=C.exports},d334:function(t,e,i){"use strict";var n=i("23a3"),s=i.n(n);s.a},d50d:function(t,e,i){t.exports=i.p+"img/integral.fcfc352b.png"}}]);