(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-goods-goods_return-index"],{"1b5e":function(t,e,n){"use strict";var i=n("c044"),a=n.n(i);a.a},"29cb":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* crmeb颜色变量 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.apply-return .list[data-v-3b3d9cb0]{background-color:#fff;margin-top:%?18?%}.apply-return .list .item[data-v-3b3d9cb0]{margin-left:%?30?%;padding-right:%?30?%;min-height:%?90?%;border-bottom:%?1?% solid #eee;font-size:%?30?%;color:#333}.apply-return .list .item .num[data-v-3b3d9cb0]{color:#282828;width:%?427?%;text-align:right}.apply-return .list .item .num .picker .reason[data-v-3b3d9cb0]{width:%?385?%}.apply-return .list .item .num .picker .iconfont[data-v-3b3d9cb0]{color:#666;font-size:%?30?%;margin-top:%?2?%}.apply-return .list .item.textarea[data-v-3b3d9cb0]{padding:%?30?% %?30?% %?30?% 0}.apply-return .list .item uni-textarea[data-v-3b3d9cb0]{height:%?100?%;font-size:%?30?%}.apply-return .list .item .placeholder[data-v-3b3d9cb0]{color:#bbb}.apply-return .list .item .title[data-v-3b3d9cb0]{height:%?95?%;width:100%}.apply-return .list .item .title .tip[data-v-3b3d9cb0]{font-size:%?30?%;color:#bbb}.apply-return .list .item .upload[data-v-3b3d9cb0]{padding-bottom:%?36?%}.apply-return .list .item .upload .pictrue[data-v-3b3d9cb0]{margin:%?22?% %?23?% 0 0;width:%?156?%;height:%?156?%;position:relative;font-size:%?24?%;color:#bbb}.apply-return .list .item .upload .pictrue[data-v-3b3d9cb0]:nth-of-type(4n){margin-right:0}.apply-return .list .item .upload .pictrue uni-image[data-v-3b3d9cb0]{width:100%;height:100%;border-radius:%?3?%}.apply-return .list .item .upload .pictrue .icon-guanbi1[data-v-3b3d9cb0]{position:absolute;font-size:%?45?%;top:%?-10?%;right:%?-10?%}.apply-return .list .item .upload .pictrue .icon-icon25201[data-v-3b3d9cb0]{color:#bfbfbf;font-size:%?50?%}.apply-return .list .item .upload .pictrue[data-v-3b3d9cb0]:nth-last-child(1){border:%?1?% solid #ddd;box-sizing:border-box}.apply-return .returnBnt[data-v-3b3d9cb0]{font-size:%?32?%;color:#fff;width:%?690?%;height:%?86?%;border-radius:%?50?%;text-align:center;line-height:%?86?%;margin:%?43?% auto}.goodsStyle .text .name[data-v-3b3d9cb0]{align-self:flex-start}.list[data-v-3b3d9cb0] .uni-input-input{text-align:right}.acea-row[data-v-3b3d9cb0]{flex-wrap:nowrap}.upload[data-v-3b3d9cb0]{flex-wrap:wrap}',""]),t.exports=e},"7df7":function(t,e,n){"use strict";var i=n("4ea4");n("cb29"),n("4160"),n("d81d"),n("a434"),n("a9e3"),n("159b"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n("451d"),r=n("666f"),s=n("26cb"),u=i(n("66ca")),o={components:{},mixins:[u.default],data:function(){return{id:0,cartIds:[],refund_reason_wap_img:[],status:{},RefundArray:[],refundCartInfo:[],returnGoodsData:[this.$t("仅退款"),this.$t("退货并退款")],refund_total_num:0,index:0,returnGoods:0,orderId:0,refundNumData:[],refund_num_index:0,isRes:!1}},computed:(0,s.mapGetters)(["isLogin"]),watch:{isLogin:{handler:function(t,e){t&&(this.refundGoodsInfo(),this.getRefundReason())},deep:!0}},onLoad:function(t){this.orderId=t.orderId,this.id=t.id,t.cartIds&&(this.cartIds=JSON.parse(t.cartIds)||[]),this.isLogin?(this.refundGoodsInfo(),this.getRefundReason()):(0,r.toLogin)()},methods:{refundGoodsInfo:function(){var t=this;(0,a.postRefundGoods)({id:this.id,cart_ids:this.cartIds}).then((function(e){var n=e.data;t.status=n._status,t.refundCartInfo=n.cartInfo,t.refundCartInfo.forEach((function(e){t.refund_total_num=t.$util.$h.Add(t.refund_total_num,e.cart_num)})),t.refundNumData=Array(t.refund_total_num).fill(0).map((function(t,e){return e+1})),1===t.refundCartInfo.length&&(t.refund_num_index=t.refundNumData.length-1)})).catch((function(e){return t.$util.Tips({title:e})}))},getRefundReason:function(){var t=this;(0,a.ordeRefundReason)().then((function(e){t.$set(t,"RefundArray",e.data)}))},DelPic:function(t){var e=t,n=this;this.refund_reason_wap_img[e];n.refund_reason_wap_img.splice(e,1),n.$set(n,"refund_reason_wap_img",n.refund_reason_wap_img)},uploadpic:function(){var t=this;this.$util.uploadImageOne("upload/image",(function(e){t.refund_reason_wap_img.push(e.data.url),t.$set(t,"refund_reason_wap_img",t.refund_reason_wap_img)}))},subRefund:function(t){var e=this;if(!this.isRes){uni.showLoading({title:this.$t("申请中")});var n=this,i=t.detail.value;if(!i.refund_reason_wap_explain)return this.$util.Tips({title:this.$t("请输入备注")});var r=this.refundCartInfo;1===r.length&&(this.cartIds=[{cart_id:r[0].id,cart_num:this.refund_num_index+1}]),this.isRes=!0,(0,a.returnGoodsSubmit)(this.id,{text:n.RefundArray[n.index]||"",refund_reason_wap_explain:i.refund_reason_wap_explain,refund_reason_wap_img:n.refund_reason_wap_img.join(","),refund_type:this.returnGoods?2:1,uni:n.orderId,cart_ids:this.cartIds}).then((function(t){return uni.hideLoading(),e.isRes=!1,e.$util.Tips({title:e.$t("申请成功"),icon:"success"},{tab:5,url:"/pages/users/user_return_list/index?isT=1"})})).catch((function(t){return uni.hideLoading(),e.isRes=!1,e.$util.Tips({title:t})}))}},bindPickerChange:function(t){this.$set(this,"index",t.detail.value)},returnGoodsChange:function(t){this.$set(this,"returnGoods",t.detail.value)},returnGoodsNum:function(t){this.$set(this,"refund_num_index",Number(t.detail.value))}}};e.default=o},c044:function(t,e,n){var i=n("29cb");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("112b4847",i,!0,{sourceMap:!1,shadowMode:!1})},cb29:function(t,e,n){var i=n("23e7"),a=n("81d5"),r=n("44d2");i({target:"Array",proto:!0},{fill:a}),r("fill")},d227:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{style:t.colorStyle},[n("v-uni-form",{on:{submit:function(e){arguments[0]=e=t.$handleEvent(e),t.subRefund.apply(void 0,arguments)}}},[n("v-uni-view",{staticClass:"apply-return"},[t._l(t.refundCartInfo,(function(e,i){return n("v-uni-view",{key:i,staticClass:"goodsStyle acea-row row-between"},[n("v-uni-view",{staticClass:"pictrue"},[n("v-uni-image",{attrs:{src:e.productInfo.attrInfo?e.productInfo.attrInfo.image:e.productInfo.image}})],1),n("v-uni-view",{staticClass:"text acea-row row-between"},[n("v-uni-view",{staticClass:"name line2"},[t._v(t._s(e.productInfo.store_name))]),n("v-uni-view",{staticClass:"money"},[n("v-uni-view",[t._v(t._s(t.$t("￥"))+t._s((parseFloat(e.truePrice)+parseFloat(e.postage_price/e.cart_num)).toFixed(2)))]),n("v-uni-view",{staticClass:"num"},[t._v("x"+t._s(e.cart_num))])],1)],1)],1)})),n("v-uni-view",{staticClass:"list"},[n("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[n("v-uni-view",[t._v(t._s(t.$t("退货件数")))]),1!==t.refundCartInfo.length||1==t.refund_total_num?n("v-uni-view",{staticClass:"num"},[t._v(t._s(t.refund_total_num))]):n("v-uni-picker",{staticClass:"num",attrs:{value:t.refund_num_index,range:t.refundNumData},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.returnGoodsNum.apply(void 0,arguments)}}},[n("v-uni-view",{staticClass:"picker acea-row row-between-wrapper"},[n("v-uni-view",{staticClass:"reason"},[t._v(t._s(t.refundNumData[t.refund_num_index]))]),n("v-uni-text",{staticClass:"iconfont icon-jiantou"})],1)],1)],1),t.status&&1!==t.status._type?n("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[n("v-uni-view",[t._v(t._s(t.$t("退款类型")))]),t.status._is_back?n("v-uni-picker",{staticClass:"num",attrs:{value:t.returnGoods,range:t.returnGoodsData},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.returnGoodsChange.apply(void 0,arguments)}}},[n("v-uni-view",{staticClass:"picker acea-row row-between-wrapper"},[n("v-uni-view",{staticClass:"reason"},[t._v(t._s(t.returnGoodsData[t.returnGoods]))]),n("v-uni-text",{staticClass:"iconfont icon-jiantou"})],1)],1):n("v-uni-view",{},[t._v("仅退款")])],1):t._e(),n("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[n("v-uni-view",[t._v(t._s(t.$t("退款原因")))]),n("v-uni-picker",{staticClass:"num",attrs:{value:t.index,range:t.RefundArray},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.bindPickerChange.apply(void 0,arguments)}}},[n("v-uni-view",{staticClass:"picker acea-row row-between-wrapper"},[n("v-uni-view",{staticClass:"reason"},[t._v(t._s(t.RefundArray[t.index]))]),n("v-uni-text",{staticClass:"iconfont icon-jiantou"})],1)],1)],1),n("v-uni-view",{staticClass:"item textarea acea-row row-between"},[n("v-uni-view",[t._v(t._s(t.$t("备注说明")))]),n("v-uni-textarea",{staticClass:"num",attrs:{placeholder:t.$t("填写备注信息，100字以内"),name:"refund_reason_wap_explain","placeholder-class":t.$t("填写备注信息，100字以内")}})],1),n("v-uni-view",{staticClass:"item acea-row row-between upload"},[n("v-uni-view",{staticClass:"title acea-row row-between-wrapper"},[n("v-uni-view",[t._v(t._s(t.$t("上传图片")))]),n("v-uni-view",{staticClass:"tip"})],1),n("v-uni-view",{staticClass:"upload acea-row row-middle"},[t._l(t.refund_reason_wap_img,(function(e,i){return n("v-uni-view",{key:i,staticClass:"pictrue"},[n("v-uni-image",{attrs:{src:e}}),n("v-uni-view",{staticClass:"iconfont icon-guanbi1 font-num",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.DelPic(i)}}})],1)})),t.refund_reason_wap_img.length<3?n("v-uni-view",{staticClass:"pictrue acea-row row-center-wrapper row-column",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.uploadpic.apply(void 0,arguments)}}},[n("v-uni-text",{staticClass:"iconfont icon-icon25201"}),n("v-uni-view",[t._v(t._s(t.$t("上传图片")))])],1):t._e()],2)],1)],1),n("v-uni-button",{staticClass:"returnBnt bg-color",attrs:{"form-type":"submit"}},[t._v(t._s(t.$t("申请退款")))])],2)],1)],1)},r=[]},d287:function(t,e,n){"use strict";n.r(e);var i=n("d227"),a=n("ffa3");for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);n("1b5e");var s,u=n("f0c5"),o=Object(u["a"])(a["default"],i["b"],i["c"],!1,null,"3b3d9cb0",null,!1,i["a"],s);e["default"]=o.exports},ffa3:function(t,e,n){"use strict";n.r(e);var i=n("7df7"),a=n.n(i);for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);e["default"]=a.a}}]);