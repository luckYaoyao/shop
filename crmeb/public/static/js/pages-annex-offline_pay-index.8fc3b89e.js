(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-annex-offline_pay-index"],{"22c1":function(t,i,n){var e=n("ebdb");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var a=n("4f06").default;a("65d7be61",e,!0,{sourceMap:!1,shadowMode:!1})},"2dea":function(t,i,n){"use strict";var e;n.d(i,"b",(function(){return a})),n.d(i,"c",(function(){return o})),n.d(i,"a",(function(){return e}));var a=function(){var t=this,i=t.$createElement,n=t._self._c||i;return n("v-uni-form",{staticClass:"form",on:{submit:function(i){arguments[0]=i=t.$handleEvent(i),t.checkForm.apply(void 0,arguments)}}},[n("v-uni-view",{staticClass:"input-section"},[n("v-uni-view",{staticClass:"section-hd"},[t._v(t._s(t.$t("支付金额")))]),n("v-uni-view",{staticClass:"section-bd"},[n("v-uni-view",{staticClass:"input-group"},[t._v(t._s(t.$t("￥"))),n("v-uni-input",{staticClass:"input",attrs:{name:"money",type:"digit",placeholder:"0.00"},on:{input:function(i){arguments[0]=i=t.$handleEvent(i),t.inputChange.apply(void 0,arguments)}},model:{value:t.money,callback:function(i){t.money=t._n(i)},expression:"money"}})],1),t.payPrice?n("v-uni-view",{staticClass:"discount"},[t._v(t._s(t.$t("会员优惠价"))+"："+t._s(t.$t("￥"))+t._s(t.payPrice))]):t._e()],1)],1),n("v-uni-view",{staticClass:"radio-section"},[n("v-uni-view",{staticClass:"section-hd"},[t._v(t._s(t.$t("支付方式")))]),n("v-uni-radio-group",{staticClass:"section-bd",attrs:{name:"method"}},[t.yuePay?n("v-uni-label",{staticClass:"item"},[n("v-uni-text",{staticClass:"iconfont icon-yue"}),n("v-uni-text",{staticClass:"name"},[t._v(t._s(t.$t("余额支付"))),n("v-uni-text",{staticClass:"money"},[t._v(t._s(t.$t("可用余额"))+":"+t._s(t.$t("￥"))+t._s(t.now_money||0))])],1),n("v-uni-radio",{attrs:{value:"yue",checked:"yue"===t.payType}})],1):t._e(),t.wxpay?n("v-uni-label",{staticClass:"item"},[n("v-uni-text",{staticClass:"iconfont icon-weixinzhifu"}),n("v-uni-text",{staticClass:"name"},[t._v(t._s(t.$t("微信支付")))]),n("v-uni-radio",{attrs:{value:"weixin",checked:"weixin"===t.payType}})],1):t._e()],1)],1),n("v-uni-button",{staticClass:"button",attrs:{"form-type":"submit"}},[t._v(t._s(t.$t("确认")))]),n("v-uni-view",{staticClass:"alipay",domProps:{innerHTML:t._s(t.alipayHtml)}})],1)},o=[]},"7dab":function(t,i,n){"use strict";n.r(i);var e=n("adbc"),a=n.n(e);for(var o in e)"default"!==o&&function(t){n.d(i,t,(function(){return e[t]}))}(o);i["default"]=a.a},"87c6":function(t,i,n){var e=n("b467");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var a=n("4f06").default;a("2c09acb1",e,!0,{sourceMap:!1,shadowMode:!1})},"9de8":function(t,i,n){"use strict";var e=n("87c6"),a=n.n(e);a.a},adbc:function(t,i,n){"use strict";n("99af"),n("ac1f"),n("466d"),Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var e=n("a398"),a=n("1118"),o=n("26cb"),s=getApp(),c={data:function(){return{money:"",payPrice:"",payType:"weixin",alipayHtml:"",alipay:!1,wxpay:!1,yuePay:!1,paying:!1,now_money:0,isWeixin:!1,site_name:"",isCommitted:!1}},watch:{money:function(t,i){t&&"number"===typeof t?this.checkPrice():this.payPrice=""}},computed:(0,o.mapGetters)(["isLogin"]),onLoad:function(t){if(this.isLogin||(0,a.toLogin)(),t.code){var i=s.globalData.spid?s.globalData.spid:"";wechatAuthV2(t.code,i).then((function(i){location.href=decodeURIComponent(decodeURIComponent(t.back_url))}))}},onShow:function(){this.isLogin&&this.getPayType(),this.isWeixin=this.$wechat.isWeixin()},methods:{inputChange:function(t){var i=this;t.target.value=t.target.value.match(/^\d*(.?\d{0,2})/g)[0]||"",this.$nextTick((function(){i.money=t.target.value,i.checkPrice()}))},getPayType:function(){var t=this;(0,e.orderOfflinePayType)().then((function(i){var n=i.data,e=n.ali_pay_status,a=n.pay_weixin_open,o=n.yue_pay_status,s=n.offline_pay_status,c=n.site_name,u=n.now_money;t.alipay="1"===e,t.wxpay=1===a,t.yuePay=1===o,t.now_money=u,t.site_name=c,s||uni.showModal({title:t.$t("支付提醒"),content:t.$t("线下支付已关闭，请点击确认按钮返回主页"),showCancel:!1,success:function(){uni.switchTab({url:"/pages/index/index"})}}),c&&uni.setNavigationBarTitle({title:c})})).catch((function(t){uni.showToast({title:t,icon:"none"})}))},checkForm:function(t){var i=t.detail.value,n=i.money,e=i.method;n?this.combData(e):uni.showToast({title:this.$t("请输入支付金额"),icon:"none"})},checkPrice:function(){var t=this;(0,e.offlineCheckPrice)({pay_price:this.money}).then((function(i){t.payPrice=i.data.pay_price})).catch((function(t){uni.showToast({title:t,icon:"none"})}))},combData:function(t){var i=this,n={type:3,pay_type:t,from:"weixinh5",price:this.payPrice||this.money,money:this.money};this.isWeixin&&(n.from="weixin"),this.paying||(this.paying=!0,uni.showLoading({title:this.$t("正在确认")}),(0,e.offlineCreate)(n).then((function(t){uni.hideLoading(),i.callPay(t)})).catch((function(t){i.paying=!1,uni.showToast({title:t,icon:"none"})})))},callPay:function(t){var i=this,n=t.data,e=n.status,a=n.result,o=a.orderId,s=a.jsConfig,c="/pages/annex/offline_result/index?site_name="+this.site_name;switch(e){case"ORDER_EXIST":case"EXTEND_ORDER":case"PAY_ERROR":this.paying=!1,this.$util.Tips({title:t.msg},{tab:5,url:c});break;case"SUCCESS":this.paying=!1,this.money="",this.$util.Tips({title:t.msg,icon:"success"},{tab:5,url:c});break;case"WECHAT_PAY":this.$wechat.pay(a.jsConfig).then((function(t){i.paying=!1,i.$util.Tips({title:i.$t("支付成功"),icon:"success"},{tab:5,url:"/pages/annex/offline_result/index"})})).catch((function(t){i.paying=!1,"chooseWXPay:cancel"==t.errMsg&&uni.showToast({title:i.$t("取消支付"),icon:"none"})}));break;case"PAY_DEFICIENCY":this.paying=!1,this.$util.Tips({title:t.msg});break;case"WECHAT_H5_PAY":this.paying=!1,uni.showToast({title:t.msg,success:function(){location.href=s.mweb_url}});break;case"ALIPAY_PAY":this.paying=!1,this.$wechat.isWeixin()?uni.navigateTo({url:"/pages/users/alipay_invoke/index?id=".concat(o,"&link=").concat(s.qrCode)}):(this.alipayHtml=s,this.$nextTick((function(){document.getElementById("alipaysubmit").submit()})));break}}}};i.default=c},b467:function(t,i,n){var e=n("24fb");i=e(!1),i.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */[data-v-7f12464b] uni-radio .uni-radio-input.uni-radio-input-checked{border:1px solid #fdc383!important;background-color:#fdc383!important}.input-section .section-hd[data-v-7f12464b]{padding:%?30?%;font-size:%?28?%;color:#666}.input-section .section-bd[data-v-7f12464b]{padding-right:%?30?%;padding-left:%?30?%}.input-section .input-group[data-v-7f12464b]{display:flex;align-items:flex-end;padding:%?45?% %?20?% %?47?%;font-size:%?80?%;color:#999}.input-section .input[data-v-7f12464b]{flex:1;height:%?110?%;margin-left:%?15?%;font-size:%?100?%;color:#282828}.input-section .discount[data-v-7f12464b]{padding:%?27?% %?20?%;border-top:%?1?% solid #eee;font-size:%?28?%;color:#e93323}.radio-section[data-v-7f12464b]{border-top:%?20?% solid #f5f5f5}.radio-section .section-hd[data-v-7f12464b]{padding:%?30?%;font-size:%?28?%;color:#666}.radio-section .section-bd[data-v-7f12464b]{padding-left:%?50?%}.radio-section .item[data-v-7f12464b]{display:flex;align-items:center;padding-top:%?30?%;padding-right:%?30?%;padding-bottom:%?30?%;border-bottom:%?1?% solid #f5f5f5}.radio-section .iconfont[data-v-7f12464b]{font-size:%?44?%}.radio-section .icon-yue[data-v-7f12464b]{color:#fe960f}.radio-section .icon-weixinzhifu[data-v-7f12464b]{color:#41b035}.radio-section .icon-zhifubao[data-v-7f12464b]{color:#099bdf}.radio-section .name[data-v-7f12464b]{flex:1;margin-left:%?30?%;font-size:%?30?%;color:#333}.radio-section .money[data-v-7f12464b]{float:right;padding-right:%?20?%;font-size:%?20?%}.button[data-v-7f12464b]{height:%?86?%;border-radius:%?43?%;margin:%?114?% %?30?% %?30?%;background:linear-gradient(90deg,#fee2b7,#fdc383);font-size:%?30?%;line-height:%?86?%;color:#5d3324}.alipay[data-v-7f12464b]{display:none}',""]),t.exports=i},ca1e:function(t,i,n){"use strict";n.r(i);var e=n("2dea"),a=n("7dab");for(var o in a)"default"!==o&&function(t){n.d(i,t,(function(){return a[t]}))}(o);n("eca2d"),n("9de8");var s,c=n("f0c5"),u=Object(c["a"])(a["default"],e["b"],e["c"],!1,null,"7f12464b",null,!1,e["a"],s);i["default"]=u.exports},ebdb:function(t,i,n){var e=n("24fb");i=e(!1),i.push([t.i,"uni-page-body[data-v-7f12464b]{background-color:#fff}body.?%PAGE?%[data-v-7f12464b]{background-color:#fff}",""]),t.exports=i},eca2d:function(t,i,n){"use strict";var e=n("22c1"),a=n.n(e);a.a}}]);