(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/admin/orderList/index"],{"1ca1":function(t,e,n){"use strict";n.r(e);var i=n("9e08"),r=n("5f66");for(var u in r)"default"!==u&&function(t){n.d(e,t,(function(){return r[t]}))}(u);n("9b11");var s,a=n("f0c5"),o=Object(a["a"])(r["default"],i["b"],i["c"],!1,null,"ad6aab2c",null,!1,i["a"],s);e["default"]=o.exports},"5f66":function(t,e,n){"use strict";n.r(e);var i=n("9d02"),r=n.n(i);for(var u in i)"default"!==u&&function(t){n.d(e,t,(function(){return i[t]}))}(u);e["default"]=r.a},"9b11":function(t,e,n){"use strict";var i=n("b285"),r=n.n(i);r.a},"9d02":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=a(n("a34a")),r=n("aeba"),u=n("d5d8"),s=n("93d6");function a(t){return t&&t.__esModule?t:{default:t}}function o(t,e,n,i,r,u,s){try{var a=t[u](s),o=a.value}catch(d){return void n(d)}a.done?e(o):Promise.resolve(o).then(i,r)}function d(t){return function(){var e=this,n=arguments;return new Promise((function(i,r){var u=t.apply(e,n);function s(t){o(u,i,r,s,a,"next",t)}function a(t){o(u,i,r,s,a,"throw",t)}s(void 0)}))}}var l=function(){n.e("components/Loading/index").then(function(){return resolve(n("3d84"))}.bind(null,n)).catch(n.oe)},c=function(){n.e("pages/admin/components/PriceChange/index").then(function(){return resolve(n("a276"))}.bind(null,n)).catch(n.oe)},f={name:"AdminOrderList",components:{Loading:l,PriceChange:c},data:function(){return{imgHost:u.HTTP_REQUEST_URL,current:"",change:!1,types:0,where:{keywords:"",page:1,limit:10,status:0},list:[],loaded:!1,loading:!1,focus:!1,orderInfo:{},status:"",isRefund:0}},onLoad:function(t){var e=t.types;this.where.status=e,this.init()},methods:{setValue:function(t){this.$set(this.where,"keywords",t.detail.value)},inputConfirm:function(e){e.detail.value&&(t.hideKeyboard(),this.getIndex())},searchBut:function(){var e=this;if(!e.where.keywords.trim())return this.$util.Tips({title:e.$t("请输入要搜索的商品")});e.focus=!1,e.where.page=1,e.loading=!1,e.loaded=!1,e.$set(e,"list",[]),t.showLoading({title:e.$t("正在搜索中")}),e.getIndex()},getIndex:function(){var e,n=this;n.loading||n.loaded||(n.loading=!0,e=-3==n.where.status?r.orderRefund_order:r.getAdminOrderList,e(n.where).then((function(e){n.loading=!1,n.loaded=e.data.length<n.where.limit,n.list.push.apply(n.list,e.data),n.where.page=n.where.page+1,t.hideLoading()}),(function(e){t.hideLoading(),n.$util.Tips({title:e})})))},init:function(){this.list=[],this.where.page=1,this.loaded=!1,this.loading=!1,this.getIndex(),this.current=""},changeStatus:function(t){this.where.status!=t&&(this.where.status=t,this.init())},modify:function(t,e){this.change=!0,this.status=e.toString(),this.orderInfo=t,2==e&&(this.isRefund=1)},changeclose:function(t){this.change=t},savePrice:function(t){var e=this;return d(i.default.mark((function n(){var u,a,o,d,l,c;return i.default.wrap((function(n){while(1)switch(n.prev=n.next){case 0:if(u=e,a={},o=t.price,d=t.refund_price,u.orderInfo.refund_status,l=t.remark,a.order_id=u.orderInfo.order_id,0!=u.status){n.next=9;break}if((0,s.isMoney)(o)){n.next=5;break}return n.abrupt("return",u.$util.Tips({title:u.$t("请输入正确的金额")}));case 5:a.price=o,(0,r.setAdminOrderPrice)(a).then((function(t){u.change=!1,u.$util.Tips({title:u.$t("改价成功"),icon:"success"}),u.init()}),(function(t){u.change=!1,u.$util.Tips({title:u.$t("改价失败"),icon:"none"})})),n.next=23;break;case 9:if(2!=u.status){n.next=17;break}if((0,s.isMoney)(d)){n.next=12;break}return n.abrupt("return",u.$util.Tips({title:u.$t("请输入正确的金额")}));case 12:a.price=d,a.type=t.type,(0,r.setOrderRefund)(a).then((function(t){u.change=!1,u.$util.Tips({title:t.msg}),u.init()}),(function(t){u.change=!1,u.$util.Tips({title:t})})),n.next=23;break;case 17:if(l){n.next=19;break}return n.abrupt("return",e.$util.Tips({title:u.$t("请输入备注")}));case 19:a.remark=l,c="",c=-3==u.where.status?(0,r.setAdminRefundRemark)(a):(0,r.setAdminOrderRemark)(a),c.then((function(t){u.change=!1,e.$util.Tips({title:t.msg,icon:"success"}),u.init()}),(function(t){u.change=!1,u.$util.Tips({title:t})}));case 23:case"end":return n.stop()}}),n)})))()},agreeExpress:function(t){var e=this;(0,r.agreeExpress)({id:t.id}).then((function(t){e.$util.Tips({title:t.msg}),e.init()})).catch((function(t){e.$util.Tips({title:t})}))},toDetail:function(e){t.navigateTo({url:"/pages/admin/orderDetail/index?id=".concat(e.order_id,"&types=").concat(this.where.status)})},offlinePay:function(t){var e=this;(0,r.setOfflinePay)({order_id:t.order_id}).then((function(t){e.$util.Tips({title:t.msg,icon:"success"}),e.init()}),(function(t){e.$util.Tips(t)}))}},onReachBottom:function(){this.getIndex()}};e.default=f}).call(this,n("543d")["default"])},"9e08":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return u})),n.d(e,"a",(function(){return i}));var r=function(){var t=this,e=t.$createElement,n=(t._self._c,t.$t("搜索用户名/订单号/电话")),i=t.$t("搜索"),r=t.$t("待付款"),u=t.$t("待发货"),s=t.$t("待收货"),a=t.$t("待评价"),o=t.$t("已完成"),d=t.$t("退款"),l=t.$t("订单号"),c=t.$t("下单时间"),f=t.$t("共"),h=t.$t("件商品，实付款"),p=t.$t("￥"),m=t.$t("邮费"),_=t.$t("￥"),g=0==t.where.status?t.$t("一键改价"):null,$=t.$t("订单备注"),v=t.list.length?t.__map(t.list,(function(e,n){var i=t.__get_orig(e),r=1==e.refund_status?t.$t("退款中"):null,u=1!=e.refund_status&&2==e.refund_status?t.$t("已退款"):null,s=1!=e.refund_status&&2!=e.refund_status&&3==e.refund_status?t.$t("拒绝退款"):null,a=1!=e.refund_status&&2!=e.refund_status&&3!=e.refund_status?t.$t(e.status_name.status_name):null,o=0==e.refund_status&&0!=t.where.status&&e.refund.length&&e.is_all_refund?t.$t("退款中"):null,d=0==e.refund_status&&0!=t.where.status&&e.refund.length&&!e.is_all_refund?t.$t("部分退款中"):null,l=t.$t("￥"),c=t.__map(e._info,(function(n,i){var r=t.__get_orig(n),u=n.cart_info.refund_num&&-2!=e._status._type?t.$t("件退款中"):null;return{$orig:r,m17:u}})),f=(0==e.refund_type||1==e.refund_type||5==e.refund_type)&&-3==t.where.status&&parseFloat(e.pay_price)>0,h=f?t.$t("立即退款"):null,p=-3==t.where.status&&2==e.refund_type?t.$t("同意退货"):null,m=-3==t.where.status&&4==e.refund_type?t.$t("待用户发货"):null,_="offline"===e.pay_type&&0===e.paid?t.$t("确认付款"):null,g=1!=t.where.status||1!==e.shipping_type||null!==e.pinkStatus&&2!==e.pinkStatus?null:t.$t("去发货");return{$orig:i,m10:r,m11:u,m12:s,m13:a,m14:o,m15:d,m16:l,l0:c,m25:f,m26:h,m27:p,m28:m,m29:_,m30:g}})):null,w=t.list.length||t.loading?null:t.$t("暂无记录");t.$mp.data=Object.assign({},{$root:{m0:n,m1:i,m2:r,m3:u,m4:s,m5:a,m6:o,m7:d,m8:l,m9:c,m18:f,m19:h,m20:p,m21:m,m22:_,m23:g,m24:$,l1:v,m31:w}})},u=[]},b285:function(t,e,n){},bae6:function(t,e,n){"use strict";(function(t){n("d5c5");i(n("66fd"));var e=i(n("1ca1"));function i(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])}},[["bae6","common/runtime","common/vendor"]]]);