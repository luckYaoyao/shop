(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-customer_list-chat"],{1377:function(t,e,i){"use strict";i.r(e);var a=i("6e9d"),o=i("e87b");for(var n in o)"default"!==n&&function(t){i.d(e,t,(function(){return o[t]}))}(n);i("df1d"),i("8bdd"),i("a199");var r,s=i("f0c5"),c=Object(s["a"])(o["default"],a["b"],a["c"],!1,null,"12b55716",null,!1,a["a"],r);e["default"]=c.exports},"22bc":function(t,e,i){"use strict";var a=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.getProductDetail=n,e.getProductCode=r,e.collectAdd=s,e.collectDel=c,e.postCartAdd=d,e.getCategoryList=l,e.getProductslist=u,e.getProductHot=f,e.collectAll=h,e.getGroomList=v,e.getCollectUserList=p,e.getReplyList=m,e.getReplyConfig=g,e.getSearchKeyword=b,e.storeListApi=_,e.storeDiscountsList=x,e.postCartNum=w,e.getAttr=y,e.getHomeProducts=k,e.getPresellProductDetail=I;var o=a(i("ac7c"));function n(t){return o.default.get("product/detail/"+t,{},{noAuth:!0})}function r(t){return o.default.get("product/code/"+t,{})}function s(t,e){return o.default.post("collect/add",{id:t,product:void 0===e?"product":e})}function c(t,e){return o.default.post("collect/del",{id:t,category:void 0===e?"product":e})}function d(t){return o.default.post("cart/add",t)}function l(){return o.default.get("category",{},{noAuth:!0})}function u(t){return o.default.get("products",t,{noAuth:!0})}function f(t,e){return o.default.get("product/hot",{page:void 0===t?1:t,limit:void 0===e?4:e},{noAuth:!0})}function h(t,e){return o.default.post("collect/all",{id:t,category:void 0===e?"product":e})}function v(t,e){return o.default.get("groom/list/"+t,e,{noAuth:!0})}function p(t){return o.default.get("collect/user",t)}function m(t,e){return o.default.get("reply/list/"+t,e)}function g(t){return o.default.get("reply/config/"+t)}function b(){return o.default.get("search/keyword",{},{noAuth:!0})}function _(t){return o.default.get("store_list",t)}function x(t){return o.default.get("store_discounts/list/"+t,{},{noAuth:!0})}function w(t){return o.default.post("v2/set_cart_num",t)}function y(t,e){return o.default.get("v2/get_attr/"+t+"/"+e)}function k(t){return o.default.get("home/products",t,{noAuth:!0})}function I(t){return o.default.get("advance/detail/"+t)}},"31b9":function(t,e,i){var a=i("a93a");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var o=i("4f06").default;o("7648ce3a",a,!0,{sourceMap:!1,shadowMode:!1})},"46e3":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,"\nuni-page-body[data-v-12b55716],\nuni-page-body[data-v-12b55716],\nhtml[data-v-12b55716],\nbody[data-v-12b55716]{height:100%}\n",""]),t.exports=e},"4c6c":function(t,e,i){"use strict";(function(t){var a=i("4ea4");i("99af"),i("4160"),i("baa5"),i("d81d"),i("acd8"),i("e25e"),i("ac1f"),i("5319"),i("159b"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=a(i("2909")),n=i("01aa"),r=i("22bc"),s=i("5786"),c=(a(i("dbb6")),a(i("8d1b"))),d=a(i("f5bd")),l=a(i("db17")),u=getApp(),f=uni.getSystemInfoSync().statusBarHeight+"px",h=function(t,e){e=1*e||1;var i=[];return t.forEach((function(t,a){a%e===0&&i.push([]),i[i.length-1].push(t)})),i},v={name:"adminChat_index",data:function(){return{status:!1,loading:!1,sysHead:f,isTool:!1,isSwiper:!1,isWords:!1,autoplay:!1,circular:!0,interval:3e3,duration:500,emojiGroup:h(c.default,21),wordsList:[],con:"",toUid:0,limit:15,upperId:0,chatList:[],kefuInfo:{},scrollTop:0,active:!0,isScroll:!0,oldHeight:0,myUid:0,productId:0,productInfo:{},orderId:0,page:1,orderInfo:{},uidTo:0,titleName:"",chatStatus:!1,userType:0,canvasWidth:"",canvasHeight:"",canvasStatus:!1}},mixins:[l.default],components:{Loading:d.default},computed:{isSend:function(){return 0!=this.con.length},records:function(){var t=this;return this.chatList.map((function(e,i){return i?e.add_time-t.chatList[i-1].add_time>=300?e.show=!0:e.show=!1:e.show=!0,e}))}},onLoad:function(t){uni.showLoading({title:"客服连接中..."}),this.myUid=this.$store.state.app.uid,this.toUid=t.to_uid,this.productId=parseInt(t.productId)||0,this.orderId=t.orderId||0,this.userType=t.type,this.getproductInfo(),this.getOrderInfo()},onUnload:function(){this.$socket.onClose(),uni.$off()},onReady:function(){var t,e=this,i=document.querySelector(".chat-box");(i.style.height=window.innerHeight+"px",u.globalData.isWsOpen)?(this.$socket.send({data:{token:this.$store.state.app.token,form_type:this.$wechat.isWeixin()?1:3},type:"login"}),this.getChatList()):(t=this.$wechat.isWeixin()?1:3,this.$socket.onStart(this.$store.state.app.token,t));uni.$once("socketOpen",(function(){e.$socket.send({data:e.$store.state.app.token,form_type:e.$wechat.isWeixin()?1:3,type:"login"}),e.$nextTick((function(t){e.getChatList()}))})),uni.$on("to_transfer",(function(t){e.toUid=t.toUid,e.$socket.send({data:{id:e.toUid},type:"to_chat"}),e.chatList.forEach((function(i){i.uid!=e.myUid&&(i.avatar=t.avatar)}))})),uni.$once("success",(function(){e.$socket.init()})),uni.$on(["reply","chat"],(function(t){1==t.msn_type&&(t.msn=e.replace_em(t.msn)),t._add_time=t._add_time.substring(0,t._add_time.length-3),e.chatList.push(t),e.$nextTick((function(){e.height()}))})),uni.$on("socket_error",(function(){e.$util.Tips({title:"连接失败"})})),uni.$on("err_tip",(function(t){e.$util.Tips({title:t.msg})})),uni.$on("online",(function(t){0==t.online&&uni.showModal({title:"提示",content:"客服已下线，是否需要反馈？",success:function(t){t.confirm?uni.redirectTo({url:"/pages/columnGoods/HotNewGoods/feedback"}):t.cancel}})})),this.$nextTick((function(){e.height()}))},methods:{previewImage:function(t){uni.previewImage({urls:[t]})},goBack:function(){uni.navigateBack()},getproductInfo:function(){var t=this;this.productId&&(0,r.getProductDetail)(this.productId).then((function(e){t.productInfo=e.data.storeInfo}))},goProduct:function(t){uni.navigateTo({url:"/pages/goods_details/index?id=".concat(t.msn)})},goOrder:function(t){this.userType?uni.navigateTo({url:"/pages/admin/orderDetail/index?id=".concat(t.msn)}):uni.navigateTo({url:"/pages/users/order_details/index?order_id=".concat(t.msn)})},getOrderInfo:function(){var t=this;this.orderId&&(0,s.getOrderDetail)(this.orderId).then((function(e){t.orderInfo=e.data,t.orderInfo.add_time_h&&(t.orderInfo.add_time_h=t.orderInfo.add_time_h.substring(0,t.orderInfo.add_time_h.lastIndexOf(":"))),t.orderInfo.cartInfo.length&&(t.cartInfo=t.orderInfo.cartInfo[0])}))},addEmoji:function(t){var e="[".concat(t,"]");this.con+=e},replace_em:function(t){return t=t.replace(/\[em-([a-z_]*)\]/g,"<span class='em em-$1'/></span>"),t},getChatList:function(){var t=this;(0,n.getChatRecord)({limit:this.limit,uidTo:this.uidTo,toUid:this.toUid}).then((function(e){var i="";e.data.serviceList.length&&(i=0==t.uidTo?"#msg-".concat(e.data.serviceList[e.data.serviceList.length-1].id):"#msg-".concat(t.chatList[0].id));uni.hideLoading(),uni.setNavigationBarTitle({title:e.data.nickname}),t.titleName=e.data.nickname,t.toUid=e.data.uid,e.data.serviceList.forEach((function(e){e._add_time=e._add_time.substring(0,e._add_time.length-3),1!=e.msn_type&&2!=e.msn_type||(e.msn=t.replace_em(e.msn))})),t.loading=!1,t.chatList=[].concat((0,o.default)(e.data.serviceList),(0,o.default)(t.chatList)),t.$nextTick((function(){t.setPageScrollTo(i),t.isScroll=e.data.serviceList.length>=t.limit})),t.$socket.send({data:{id:t.toUid},type:"to_chat"})})).catch((function(e){uni.hideLoading(),t.$util.Tips({title:e}),t.loading=!1,t.isScroll=!1,uni.redirectTo({url:"/pages/columnGoods/HotNewGoods/feedback"})}))},setPageScrollTo:function(t){var e=this,i=uni.createSelectorQuery().in(this).select(t);i.boundingClientRect((function(t){e.scrollTop=t?parseFloat(t.top)-60:0})).exec()},sendText:function(){if(!this.isSend)return this.$util.Tips({title:"请输入内容"});this.sendMsg(this.con,1),this.con=""},sendMsg:function(t,e){this.$socket.send({data:{msn:t,type:e,to_uid:this.toUid},form_type:this.$wechat.isWeixin()?1:3,type:"chat"})},uploadImg:function(){var t=this,e=this;e.canvasStatus=!0,e.$util.uploadImageChange("upload/image",(function(t){200==t.status&&e.sendMsg(t.data.url,3)}),(function(e){t.canvasStatus=!1}),(function(e){t.canvasWidth=e.w,t.canvasHeight=e.h}))},sendProduct:function(){this.sendMsg(this.productId,5),this.productId=0,this.productInfo={}},sendOrder:function(){this.sendMsg(this.orderId,6),this.orderId=0,this.orderInfo={}},height:function(){var t=this,e=0,i=uni.createSelectorQuery().select(".chat");setTimeout((function(a){i.boundingClientRect((function(i){e=i.height,t.active?t.scrollTop=parseInt(e)+500:t.scrollTop=parseInt(e)+100})).exec()}),200)},scrollToTop:function(){var t=this;this.isScroll&&(this.loading=!0,this.uidTo=this.chatList[0].id,this.isScroll=!1,setTimeout((function(e){t.getChatList()}),800))}}};e.default=v}).call(this,i("5a52")["default"])},5320:function(t,e,i){var a=i("65ab");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var o=i("4f06").default;o("2e0dda5a",a,!0,{sourceMap:!1,shadowMode:!1})},"65ab":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* crmeb颜色变量 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.chat-box[data-v-12b55716]{display:flex;flex-direction:column;height:100%;background:#f0f1f2;height:100vh}.chat-box .head-box[data-v-12b55716]{height:%?86?%;background:linear-gradient(85deg,#3875ea,#1890fc)}.chat-box .head-box .title-hd[data-v-12b55716]{display:flex;align-items:center;justify-content:center;position:relative;height:43px;padding:0 %?30?%;color:#fff}.chat-box .head-box .title-hd .icon-fanhui[data-v-12b55716]{position:absolute;left:%?30?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.chat-box .scroll-box[data-v-12b55716]{flex:1}.chat-box .footer-box[data-v-12b55716]{display:flex;align-items:center;padding:0 %?30?%;color:rgba(0,0,0,.8);background:#f7f7f7;height:%?100?%;height:calc(100rpx+ constant(safe-area-inset-bottom));height:calc(%?100?% + env(safe-area-inset-bottom))}.chat-box .footer-box .words .icon-tupian[data-v-12b55716]{font-size:%?50?%}.chat-box .footer-box .input-box[data-v-12b55716]{display:flex;align-items:center;flex:1;height:%?64?%;padding-right:%?5?%;margin-left:%?18?%;background-color:#fff;border-radius:%?32?%}.chat-box .footer-box .input-box uni-input[data-v-12b55716]{flex:1;padding-left:%?20?%;height:100%;font-size:%?28?%;font-weight:400}.chat-box .footer-box .input-box .icon-fasong[data-v-12b55716]{font-size:%?50?%;color:#ccc;font-weight:400}.chat-box .footer-box .input-box .isSend[data-v-12b55716]{color:#3875ea}.chat-box .footer-box .emoji .icon-biaoqing[data-v-12b55716]{margin-left:%?18?%;font-size:%?50?%}.chat-box .footer-box .more .icon-gengduozhankai[data-v-12b55716]{margin-left:%?18?%;font-size:%?50?%}.tool-wrapper[data-v-12b55716]{display:flex;justify-content:space-between;padding:%?45?% %?60?%;background:#fff;font-size:%?24?%}.tool-wrapper .tool-item[data-v-12b55716]{text-align:center}.tool-wrapper .tool-item uni-image[data-v-12b55716]{width:%?104?%;height:%?104?%}.slider-banner[data-v-12b55716]{background:#fff}.words-mask[data-v-12b55716]{z-index:50;position:fixed;left:0;top:0;right:0;bottom:0;background:rgba(0,0,0,.5)}.words-mask .content[data-v-12b55716]{position:absolute;left:0;right:0;top:%?114?%;bottom:0;display:flex;flex-direction:column;padding:0 %?30?%;background:#fff;border-radius:%?6?% %?6?% 0 0}.words-mask .content .title-box[data-v-12b55716]{position:relative;height:%?125?%;line-height:%?125?%;text-align:center;font-size:%?32?%}.words-mask .content .title-box .icon-cha1[data-v-12b55716]{position:absolute;right:0;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.words-mask .content .scroll-box[data-v-12b55716]{flex:1;overflow:hidden}.words-mask .content .scroll-box .msg-item[data-v-12b55716]{padding:%?25?% 0;border-bottom:1px solid #eceff8}.chat-scroll-box[data-v-12b55716]{flex:1;padding:%?30?% %?30?% 0;overflow:hidden}.chat-scroll-box .chat-item[data-v-12b55716]{display:flex;margin-bottom:%?36?%}.chat-scroll-box .chat-item .avatar[data-v-12b55716]{width:%?80?%;height:%?80?%;border-radius:50%}.chat-scroll-box .chat-item .msg-box[data-v-12b55716]{display:flex;align-items:center;max-width:%?452?%;margin-left:%?22?%;padding:%?10?% %?24?%;background:#fff;border-radius:%?14?%;word-break:break-all}.chat-scroll-box .chat-item .img-box[data-v-12b55716]{width:%?270?%;margin-left:%?22?%}.chat-scroll-box .chat-item .img-box uni-image[data-v-12b55716]{width:%?270?%}.chat-scroll-box .chat-item .product-box[data-v-12b55716]{width:%?452?%;margin-left:%?22?%;background-color:#fff;border-radius:%?14?%;overflow:hidden}.chat-scroll-box .chat-item .product-box uni-image[data-v-12b55716]{width:%?452?%}.chat-scroll-box .chat-item .product-box .info[data-v-12b55716]{padding:%?16?% %?26?%}.chat-scroll-box .chat-item .product-box .info .price[data-v-12b55716]{font-size:%?36?%;color:var(--view-priceColor)}.chat-scroll-box .chat-item .product-box .info .price uni-text[data-v-12b55716]{font-size:%?28?%}.chat-scroll-box .chat-item .order-box[data-v-12b55716]{width:%?452?%;margin-left:%?22?%;background-color:#fff;border-radius:%?14?%}.chat-scroll-box .chat-item .order-box .title[data-v-12b55716]{padding:%?15?% %?20?%;font-size:%?26?%;color:#282828;border-bottom:1px solid #eceff8}.chat-scroll-box .chat-item .order-box .info[data-v-12b55716]{display:flex;padding:%?20?%}.chat-scroll-box .chat-item .order-box .info uni-image[data-v-12b55716]{width:%?124?%;height:%?124?%;border-radius:%?6?%}.chat-scroll-box .chat-item .order-box .info .product-info[data-v-12b55716]{flex:1;display:flex;flex-direction:column;justify-content:space-between;margin-left:%?16?%}.chat-scroll-box .chat-item .order-box .info .product-info .name[data-v-12b55716]{font-size:%?26?%}.chat-scroll-box .chat-item .order-box .info .product-info .price[data-v-12b55716]{font-size:%?30?%;color:var(--view-priceColor)}.chat-scroll-box .chat-item.right-box[data-v-12b55716]{flex-direction:row-reverse}.chat-scroll-box .chat-item.right-box .msg-box[data-v-12b55716]{margin-left:0;margin-right:%?22?%;background-color:#9cec60}.chat-scroll-box .chat-item.right-box .img-box[data-v-12b55716]{margin-left:0;margin-right:%?22?%}.chat-scroll-box .chat-item.right-box .product-box[data-v-12b55716]{margin-left:0;margin-right:%?22?%}.chat-scroll-box .chat-item.right-box .order-box[data-v-12b55716]{margin-left:0;margin-right:%?22?%}.chat-scroll-box .chat-item .em[data-v-12b55716]{margin:0}.broadcast-details_box[data-v-12b55716]{display:flex;background:#fff;border-radius:6px;padding:%?24?%}.broadcast_details_img[data-v-12b55716]{width:%?140?%;height:%?140?%;border-radius:8px;overflow:hidden;position:relative}.broadcast_details_img .goods-img[data-v-12b55716]{width:100%;height:100%}.broadcast_details_picBox[data-v-12b55716]{width:75%;margin-left:%?24?%}.broadcast_details_tit[data-v-12b55716]{font-size:%?28?%;color:#333;height:%?85?%;font-weight:800;overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;text-align:left!important}.broadcast_details_pic[data-v-12b55716]{font-size:%?36?%;color:var(--view-priceColor);text-align:left}.broadcast_details_pic_num[data-v-12b55716]{text-decoration:line-through;font-size:%?28?%;color:rgba(0,0,0,.5);margin-left:.1rem}.broadcast_details_btn[data-v-12b55716]{width:%?130?%;height:%?50?%;background:var(--view-theme);opacity:1;border-radius:%?125?%;color:#fff;font-size:%?24?%;text-align:center;line-height:%?50?%}.broadcast-details_num[data-v-12b55716]{width:100%;height:%?80?%;line-height:%?80?%;color:#000;font-size:%?26?%;display:flex;justify-content:space-between;background:#fff;border-bottom:1px dashed rgba(0,0,0,.2);padding:0 %?24?%}.day-box[data-v-12b55716]{font-size:%?24?%;color:#999;text-align:center;margin-bottom:%?36?%}',""]),t.exports=e},"6e9d":function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return n})),i.d(e,"a",(function(){return a}));var o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"chat-box",style:t.colorStyle},[i("v-uni-view",{staticClass:"broadcast-details_order"},[t.productId&&t.productInfo.id?i("v-uni-view",{staticClass:"broadcast-details_box"},[i("v-uni-view",{staticClass:"broadcast_details_img"},[i("v-uni-image",{staticClass:"goods-img",attrs:{src:t.productInfo.image}})],1),i("v-uni-view",{staticClass:"broadcast_details_picBox"},[i("v-uni-view",{staticClass:"broadcast_details_tit",domProps:{textContent:t._s(t.productInfo.store_name)}}),i("v-uni-view",{staticClass:"acea-row row-between"},[i("v-uni-view",{staticClass:"broadcast_details_pic"},[t._v("￥"+t._s(t.productInfo.price)),t.productInfo.ot_price?i("v-uni-text",{staticClass:"broadcast_details_pic_num"},[t._v("￥"+t._s(t.productInfo.ot_price))]):t._e()],1),i("v-uni-view",{staticClass:"broadcast_details_btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.sendProduct.apply(void 0,arguments)}}},[t._v("发送客服")])],1)],1)],1):t._e(),t.orderId&&t.orderInfo.id?i("v-uni-view",{staticClass:"broadcast_box"},[i("v-uni-view",{staticClass:"broadcast-details_num broadcast_num"},[i("v-uni-text",[t._v("订单号："+t._s(t.orderInfo.order_id))]),i("v-uni-text",[t._v(t._s(t.orderInfo.add_time_y)+" "+t._s(t.orderInfo.add_time_h))])],1),i("v-uni-view",{staticClass:"broadcast-details_box"},[i("v-uni-view",{staticClass:"broadcast_details_img"},[i("v-uni-image",{staticClass:"goods-img",attrs:{src:t.orderInfo.cartInfo[0].productInfo.image}}),i("v-uni-view",{staticClass:"broadcast_details_model"},[t._v(t._s(t.orderInfo.cartInfo?t.orderInfo.cartInfo.length:0)+"件商品")])],1),i("v-uni-view",{staticClass:"broadcast_details_picBox"},[i("v-uni-view",{staticClass:"broadcast_details_tit"},[t._v(t._s(t.orderInfo.cartInfo[0].productInfo.store_name))]),i("v-uni-view",{staticClass:"acea-row row-between"},[i("v-uni-view",{staticClass:"broadcast_details_pic"},[t._v("￥"+t._s(t.orderInfo.cartInfo[0].productInfo.price)),i("v-uni-text",{staticClass:"broadcast_details_pic_num"},[t._v("￥"+t._s(t.orderInfo.cartInfo[0].costPrice))])],1),i("v-uni-view",{staticClass:"broadcast_details_btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.sendOrder.apply(void 0,arguments)}}},[t._v("发送客服")])],1)],1)],1)],1):t._e()],1),i("v-uni-view",{staticClass:"chat-scroll-box"},[i("v-uni-scroll-view",{staticStyle:{height:"100%"},attrs:{"scroll-y":"true","scroll-top":t.scrollTop},on:{scrolltoupper:function(e){arguments[0]=e=t.$handleEvent(e),t.scrollToTop.apply(void 0,arguments)}}},[i("Loading",{attrs:{loaded:t.status,loading:t.loading}}),i("v-uni-view",{ref:"chat",staticClass:"chat",attrs:{id:"box"}},t._l(t.records,(function(e,a){return i("v-uni-view",{key:a,attrs:{id:"msg-"+e.id}},[e.show?i("v-uni-view",{staticClass:"day-box"},[t._v(t._s(e._add_time))]):t._e(),i("v-uni-view",{staticClass:"chat-item",class:{"right-box":e.uid==t.myUid}},[i("v-uni-image",{staticClass:"avatar",attrs:{src:e.avatar,mode:""}}),e.msn_type<=2?i("v-uni-view",{staticClass:"msg-box",domProps:{innerHTML:t._s(e.msn)}}):t._e(),3==e.msn_type?i("v-uni-view",{staticClass:"img-box"},[i("v-uni-image",{attrs:{src:e.msn,mode:"widthFix"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.previewImage(e.msn)}}})],1):t._e(),5==e.msn_type?i("v-uni-view",{staticClass:"product-box",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.goProduct(e)}}},[i("v-uni-image",{attrs:{src:e.productInfo.image,mode:"widthFix"}}),i("v-uni-view",{staticClass:"info"},[i("v-uni-view",{staticClass:"price"},[i("v-uni-text",[t._v("￥")]),t._v(t._s(e.productInfo.price))],1),i("v-uni-view",{staticClass:"name line2"},[t._v(t._s(e.productInfo.store_name))])],1)],1):t._e(),6==e.msn_type?i("v-uni-view",{staticClass:"order-box",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.goOrder(e)}}},[i("v-uni-view",{staticClass:"title"},[t._v("订单ID: "+t._s(e.orderInfo.order_id))]),i("v-uni-view",{staticClass:"info"},[i("v-uni-image",{attrs:{src:e.orderInfo.cartInfo[0].productInfo.image}}),i("v-uni-view",{staticClass:"product-info"},[i("v-uni-view",{staticClass:"name line2"},[t._v(t._s(e.orderInfo.cartInfo[0].productInfo.store_name))]),i("v-uni-view",{staticClass:"price"},[t._v("¥"+t._s(e.orderInfo.cartInfo[0].productInfo.price))])],1)],1)],1):t._e()],1)],1)})),1)],1)],1),i("v-uni-view",{staticClass:"footer-box"},[i("v-uni-view",{staticClass:"words",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.uploadImg.apply(void 0,arguments)}}},[i("v-uni-text",{staticClass:"iconfont icon-tupian"})],1),i("v-uni-view",{staticClass:"input-box"},[i("v-uni-input",{attrs:{type:"text",placeholder:"请输入内容","confirm-type":"send"},on:{confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.sendText.apply(void 0,arguments)}},model:{value:t.con,callback:function(e){t.con=e},expression:"con"}}),i("v-uni-text",{staticClass:"iconfont icon-fasong",class:{isSend:t.isSend},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.sendText.apply(void 0,arguments)}}})],1),i("v-uni-view",{staticClass:"emoji",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.isSwiper=!t.isSwiper}}},[i("span",{staticClass:"iconfont icon-biaoqing"})])],1),t.isSwiper?i("v-uni-view",{staticClass:"banner slider-banner"},[t.emojiGroup.length>0?i("v-uni-swiper",{staticClass:"swiper-wrapper",attrs:{autoplay:t.autoplay,circular:t.circular,interval:t.interval,duration:t.duration}},[t._l(t.emojiGroup,(function(e,a){return[i("v-uni-swiper-item",t._l(e,(function(e){return i("i",{key:e,staticClass:"em",class:e,on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.addEmoji(e)}}})})),0)]}))],2):t._e()],1):t._e(),t.canvasStatus?i("v-uni-canvas",{style:{width:t.canvasWidth+"px",height:t.canvasHeight+"px",position:"absolute",left:"-100000px",top:"-100000px"},attrs:{"canvas-id":"canvas"}}):t._e()],1)},n=[]},"89fc":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,".Loads[data-v-60697522]{height:%?80?%;font-size:%?25?%;color:#000}.Loads .iconfont[data-v-60697522]{font-size:%?30?%;margin-right:%?10?%;height:%?32?%;line-height:%?32?%}\n/*加载动画*/@-webkit-keyframes load-data-v-60697522{from{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes load-data-v-60697522{from{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}.loadingpic[data-v-60697522]{-webkit-animation:load-data-v-60697522 3s linear 1s infinite;animation:load-data-v-60697522 3s linear 1s infinite}.loading[data-v-60697522]{-webkit-animation:load-data-v-60697522 linear 1s infinite;animation:load-data-v-60697522 linear 1s infinite}",""]),t.exports=e},"8bdd":function(t,e,i){"use strict";var a=i("5320"),o=i.n(a);o.a},"8d1b":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=["em-smile","em-laughing","em-blush","em-smiley","em-relaxed","em-smirk","em-heart_eyes","em-kissing_heart","em-kissing_closed_eyes","em-flushed","em-relieved","em-satisfied","em-grin","em-wink","em-stuck_out_tongue_winking_eye","em-stuck_out_tongue_closed_eyes","em-grinning","em-kissing","em-kissing_smiling_eyes","em-stuck_out_tongue","em-sleeping","em-worried","em-frowning","em-anguished","em-open_mouth","em-grimacing","em-confused","em-hushed","em-expressionless","em-unamused","em-sweat_smile","em-sweat","em-disappointed_relieved","em-weary","em-pensive","em-disappointed","em-confounded","em-fearful","em-cold_sweat","em-persevere","em-cry","em-sob","em-joy","em-astonished","em-scream","em-tired_face","em-angry","em-rage","em-triumph","em-sleepy","em-yum","em-mask","em-sunglasses","em-dizzy_face","em-imp","em-smiling_imp","em-neutral_face","em-no_mouth","em-innocent","em-alien"];e.default=a},"9d6d":function(t,e,i){"use strict";var a=i("a907"),o=i.n(a);o.a},a199:function(t,e,i){"use strict";var a=i("31b9"),o=i.n(a);o.a},a907:function(t,e,i){var a=i("89fc");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var o=i("4f06").default;o("4269a5a3",a,!0,{sourceMap:!1,shadowMode:!1})},a93a:function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,".em[data-v-12b55716]{display:inline-block;width:%?50?%;height:%?50?%;margin:%?40?% 0 0 %?50?%}.emoji-outer[data-v-12b55716]{position:absolute;right:%?50?%;bottom:%?30?%;width:%?50?%;height:%?50?%}",""]),t.exports=e},baa5:function(t,e,i){var a=i("23e7"),o=i("e58c");a({target:"Array",proto:!0,forced:o!==[].lastIndexOf},{lastIndexOf:o})},bb29:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={name:"Loading",props:{loaded:{type:Boolean,default:!1},loading:{type:Boolean,default:!1}}};e.default=a},bde0:function(t,e,i){var a=i("46e3");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var o=i("4f06").default;o("912dcf08",a,!0,{sourceMap:!1,shadowMode:!1})},c7f0:function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return n})),i.d(e,"a",(function(){return a}));var o=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[t.loading&&!t.loaded?i("v-uni-view",{staticClass:"Loads acea-row row-center-wrapper",staticStyle:{"margin-top":".2rem"}},[t.loading?i("v-uni-view",[i("v-uni-view",{staticClass:"iconfont icon-jiazai loading acea-row row-center-wrapper"}),t._v("正在加载中")],1):i("v-uni-view",[t._v("上拉加载更多")])],1):t._e()],1)},n=[]},de48:function(t,e,i){"use strict";i.r(e);var a=i("bb29"),o=i.n(a);for(var n in a)"default"!==n&&function(t){i.d(e,t,(function(){return a[t]}))}(n);e["default"]=o.a},df1d:function(t,e,i){"use strict";var a=i("bde0"),o=i.n(a);o.a},e87b:function(t,e,i){"use strict";i.r(e);var a=i("4c6c"),o=i.n(a);for(var n in a)"default"!==n&&function(t){i.d(e,t,(function(){return a[t]}))}(n);e["default"]=o.a},f5bd:function(t,e,i){"use strict";i.r(e);var a=i("c7f0"),o=i("de48");for(var n in o)"default"!==n&&function(t){i.d(e,t,(function(){return o[t]}))}(n);i("9d6d");var r,s=i("f0c5"),c=Object(s["a"])(o["default"],a["b"],a["c"],!1,null,"60697522",null,!1,a["a"],r);e["default"]=c.exports}}]);