(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/customer_list/chat"],{1219:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n("3023"),o=n("a8b0"),a=n("d790"),r=(c(n("9630")),c(n("cf52"))),s=c(n("cd99"));function c(t){return t&&t.__esModule?t:{default:t}}function d(t){return h(t)||l(t)||f(t)||u()}function u(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function f(t,e){if(t){if("string"===typeof t)return p(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?p(t,e):void 0}}function l(t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}function h(t){if(Array.isArray(t))return p(t)}function p(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,i=new Array(e);n<e;n++)i[n]=t[n];return i}var g=getApp(),m=t.getSystemInfoSync().statusBarHeight+"px",v=function(t,e){e=1*e||1;var n=[];return t.forEach((function(t,i){i%e===0&&n.push([]),n[n.length-1].push(t)})),n},_=function(){n.e("components/Loading/index").then(function(){return resolve(n("a48f"))}.bind(null,n)).catch(n.oe)},I={name:"adminChat_index",data:function(){return{status:!1,loading:!1,sysHead:m,isTool:!1,isSwiper:!1,isWords:!1,autoplay:!1,circular:!0,interval:3e3,duration:500,emojiGroup:v(r.default,21),wordsList:[],con:"",toUid:0,limit:15,upperId:0,chatList:[],kefuInfo:{},scrollTop:0,active:!0,isScroll:!0,oldHeight:0,myUid:0,productId:0,productInfo:{},orderId:0,page:1,orderInfo:{},uidTo:0,titleName:"",chatStatus:!1,userType:0,canvasWidth:"",canvasHeight:"",canvasStatus:!1}},mixins:[s.default],components:{Loading:_},computed:{isSend:function(){return 0!=this.con.length},records:function(){var t=this;return this.chatList.map((function(e,n){return n?e.add_time-t.chatList[n-1].add_time>=300?e.show=!0:e.show=!1:e.show=!0,e}))}},onLoad:function(e){var n;(t.showLoading({title:"客服连接中..."}),this.myUid=this.$store.state.app.uid,this.toUid=e.to_uid,this.productId=parseInt(e.productId)||0,this.orderId=e.orderId||0,this.userType=e.type,this.getproductInfo(),this.getOrderInfo(),g.globalData.isWsOpen)||(n=2,this.$socket.onStart(this.$store.state.app.token,n))},onUnload:function(){this.$socket.onClose(),t.$off()},onReady:function(){var e=this;g.globalData.isWsOpen&&(this.$socket.send({data:{token:this.$store.state.app.token,form_type:2},type:"login"}),this.getChatList()),t.$once("socketOpen",(function(){e.$socket.send({data:e.$store.state.app.token,form_type:2,type:"login"}),e.getChatList()})),t.$on("to_transfer",(function(t){e.toUid=t.toUid,e.$socket.send({data:{id:e.toUid},type:"to_chat"}),e.chatList.forEach((function(n){n.uid!=e.myUid&&(n.avatar=t.avatar)}))})),t.$once("success",(function(){e.$socket.init()})),t.$on(["reply","chat"],(function(t){1==t.msn_type&&(t.msn=e.replace_em(t.msn)),t._add_time=t._add_time.substring(0,t._add_time.length-3),e.chatList.push(t),e.$nextTick((function(){e.height()}))})),t.$on("socket_error",(function(){e.$util.Tips({title:"连接失败"})})),t.$on("online",(function(e){0==e.online&&t.showModal({title:"提示",content:"客服已下线，是否需要反馈？",success:function(e){e.confirm?t.redirectTo({url:"/pages/columnGoods/HotNewGoods/feedback"}):e.cancel}})})),this.$nextTick((function(){e.height()}))},methods:{previewImage:function(e){t.previewImage({urls:[e]})},goBack:function(){t.navigateBack()},getproductInfo:function(){var t=this;this.productId&&(0,o.getProductDetail)(this.productId).then((function(e){t.productInfo=e.data.storeInfo}))},goProduct:function(e){t.navigateTo({url:"/pages/goods_details/index?id=".concat(e.msn)})},goOrder:function(e){this.userType?t.navigateTo({url:"/pages/admin/orderDetail/index?id=".concat(e.msn)}):t.navigateTo({url:"/pages/users/order_details/index?order_id=".concat(e.msn)})},getOrderInfo:function(){var t=this;this.orderId&&(0,a.getOrderDetail)(this.orderId).then((function(e){t.orderInfo=e.data,t.orderInfo.add_time_h&&(t.orderInfo.add_time_h=t.orderInfo.add_time_h.substring(0,t.orderInfo.add_time_h.lastIndexOf(":"))),t.orderInfo.cartInfo.length&&(t.cartInfo=t.orderInfo.cartInfo[0])}))},addEmoji:function(t){var e="[".concat(t,"]");this.con+=e},replace_em:function(t){return t=t.replace(/\[em-([a-z_]*)\]/g,"<span class='em em-$1'/></span>"),t},getChatList:function(){var e=this;(0,i.getChatRecord)({limit:this.limit,uidTo:this.uidTo,toUid:this.toUid}).then((function(n){var i="";n.data.serviceList.length&&(i=0==e.uidTo?"#msg-".concat(n.data.serviceList[n.data.serviceList.length-1].id):"#msg-".concat(e.chatList[0].id));t.hideLoading(),t.setNavigationBarTitle({title:n.data.nickname}),e.titleName=n.data.nickname,e.toUid=n.data.uid,n.data.serviceList.forEach((function(t){t._add_time=t._add_time.substring(0,t._add_time.length-3),1!=t.msn_type&&2!=t.msn_type||(t.msn=e.replace_em(t.msn))})),e.loading=!1,e.chatList=[].concat(d(n.data.serviceList),d(e.chatList)),e.$nextTick((function(){e.setPageScrollTo(i),e.isScroll=n.data.serviceList.length>=e.limit})),e.$socket.send({data:{id:e.toUid},type:"to_chat"})})).catch((function(n){t.hideLoading(),e.$util.Tips({title:n}),e.loading=!1,e.isScroll=!1,t.redirectTo({url:"/pages/columnGoods/HotNewGoods/feedback"})}))},setPageScrollTo:function(e){var n=this,i=t.createSelectorQuery().in(this).select(e);i.boundingClientRect((function(t){n.scrollTop=parseFloat(t.top)-60})).exec()},sendText:function(){if(!this.isSend)return this.$util.Tips({title:"请输入内容"});this.sendMsg(this.con,1),this.con=""},sendMsg:function(t,e){this.$socket.send({data:{msn:t,type:e,to_uid:this.toUid},form_type:2,type:"chat"})},uploadImg:function(){var t=this,e=this;e.canvasStatus=!0,e.$util.uploadImageChange("upload/image",(function(t){200==t.status&&e.sendMsg(t.data.url,3)}),(function(e){t.canvasStatus=!1}),(function(e){t.canvasWidth=e.w,t.canvasHeight=e.h}))},sendProduct:function(){this.sendMsg(this.productId,5),this.productId=0,this.productInfo={}},sendOrder:function(){this.sendMsg(this.orderId,6),this.orderId=0,this.orderInfo={}},height:function(){var e=this,n=0,i=t.createSelectorQuery().select(".chat");setTimeout((function(t){i.boundingClientRect((function(t){n=t.height,e.active?e.scrollTop=parseInt(n)+500:e.scrollTop=parseInt(n)+100})).exec()}),200)},scrollToTop:function(){var t=this;this.isScroll&&(this.loading=!0,this.uidTo=this.chatList[0].id,this.isScroll=!1,setTimeout((function(e){t.getChatList()}),800))}}};e.default=I}).call(this,n("543d")["default"])},"186c":function(t,e,n){"use strict";var i=n("2ae0"),o=n.n(i);o.a},"267a":function(t,e,n){"use strict";var i=n("ceda"),o=n.n(i);o.a},"2ae0":function(t,e,n){},"36a0":function(t,e,n){},bd99:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return o})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return i}));var o=function(){var t=this,e=t.$createElement;t._self._c;t._isMounted||(t.e0=function(e){t.isSwiper=!t.isSwiper})},a=[]},ca81:function(t,e,n){"use strict";n.r(e);var i=n("1219"),o=n.n(i);for(var a in i)"default"!==a&&function(t){n.d(e,t,(function(){return i[t]}))}(a);e["default"]=o.a},ceda:function(t,e,n){},d9f1:function(t,e,n){"use strict";var i=n("36a0"),o=n.n(i);o.a},ea33:function(t,e,n){"use strict";n.r(e);var i=n("bd99"),o=n("ca81");for(var a in o)"default"!==a&&function(t){n.d(e,t,(function(){return o[t]}))}(a);n("267a"),n("186c"),n("d9f1");var r,s=n("f0c5"),c=Object(s["a"])(o["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],r);e["default"]=c.exports},f90c:function(t,e,n){"use strict";(function(t){n("cdba");i(n("66fd"));var e=i(n("ea33"));function i(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])}},[["f90c","common/runtime","common/vendor"]]]);