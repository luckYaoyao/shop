require('../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods/order_confirm/index"],{1129:function(t,e,i){"use strict";i.r(e);var n=i("42be"),a=i.n(n);for(var s in n)"default"!==s&&function(t){i.d(e,t,(function(){return n[t]}))}(s);e["default"]=a.a},"14f1":function(t,e,i){"use strict";i.r(e);var n=i("c023"),a=i("1129");for(var s in a)"default"!==s&&function(t){i.d(e,t,(function(){return a[t]}))}(s);i("9da5");var o,r=i("f0c5"),c=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"c3a603d0",null,!1,n["a"],o);e["default"]=c.exports},"267f":function(t,e,i){"use strict";(function(t){i("4a5c");n(i("66fd"));var e=n(i("14f1"));function n(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=i,t(e.default)}).call(this,i("543d")["createPage"])},2907:function(t,e,i){},"42be":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i("b588"),a=i("5367"),s=i("5b5f"),o=i("62c7"),r=(i("7f29"),i("f20a")),c=i("26cb"),u=d(i("a672"));function d(t){return t&&t.__esModule?t:{default:t}}var l=function(){Promise.all([i.e("common/vendor"),i.e("components/couponListWindow/index")]).then(function(){return resolve(i("4501"))}.bind(null,i)).catch(i.oe)},p=function(){i.e("components/addressWindow/index").then(function(){return resolve(i("06d8"))}.bind(null,i)).catch(i.oe)},h=function(){i.e("components/orderGoods/index").then(function(){return resolve(i("6062"))}.bind(null,i)).catch(i.oe)},f=function(){Promise.all([i.e("common/vendor"),i.e("components/home/index")]).then(function(){return resolve(i("2877"))}.bind(null,i)).catch(i.oe)},g=function(){i.e("pages/goods/components/invoicePicker/index").then(function(){return resolve(i("1f89"))}.bind(null,i)).catch(i.oe)},m=function(){i.e("components/Authorize").then(function(){return resolve(i("8dd2"))}.bind(null,i)).catch(i.oe)},I=function(){Promise.all([i.e("common/vendor"),i.e("components/payment/index")]).then(function(){return resolve(i("731a"))}.bind(null,i)).catch(i.oe)},_={components:{payment:I,invoicePicker:g,couponListWindow:l,addressWindow:p,orderGoods:h,home:f,authorize:m},mixins:[u.default],data:function(){this.getDate({format:!0});return{confirm:"",date:this.$t("请选择"),time:this.$t("请选择"),canvasWidth:"",canvasHeight:"",canvasStatus:!1,newImg:[],textareaStatus:!0,cartArr:[{name:this.$t("微信支付"),icon:"icon-weixin2",value:"weixin",title:this.$t("使用微信快捷支付"),payStatus:1},{name:this.$t("支付宝支付"),icon:"icon-zhifubao",value:"alipay",title:this.$t("使用支付宝支付"),payStatus:1},{name:this.$t("余额支付"),icon:"icon-yuezhifu",value:"yue",title:this.$t("可用余额"),payStatus:1},{name:this.$t("线下支付"),icon:"icon-yuezhifu1",value:"offline",title:this.$t("使用线下付款"),payStatus:2},{name:this.$t("好友代付"),icon:"icon-haoyoudaizhifu",value:"friend",title:this.$t("找微信好友支付"),payStatus:1}],virtual_type:0,formContent:"",payType:"weixin",openType:1,active:0,coupon:{coupon:!1,list:[],statusTile:this.$t("立即使用")},address:{address:!1},addressInfo:{},pinkId:0,addressId:0,couponId:0,cartId:"",BargainId:0,combinationId:0,seckillId:0,discountId:0,userInfo:{},mark:"",couponTitle:this.$t("请选择"),coupon_price:0,useIntegral:!1,integral_price:0,integral:0,usable_integral:0,ChangePrice:0,formIds:[],status:0,is_address:!1,toPay:!1,shippingType:0,system_store:{},storePostage:0,advanceId:0,contacts:"",contactsTel:"",mydata:{},storeList:[],store_self_mention:0,cartInfo:[],priceGroup:{},animated:!1,totalPrice:0,integralRatio:"0",pagesUrl:"",orderKey:"",offlinePostage:"",isAuto:!1,isShowAuth:!1,from:"",news:1,invTitle:this.$t("不开发票"),special_invoice:!1,invoice_func:!1,header_type:"",invShow:!1,invList:[],invChecked:"",urlQuery:"",pay_close:!1,noCoupon:0,valid_count:0,discount_id:0,is_shipping:!0,inputTrip:!1,focus:!0,integral_open:!1,jumpData:{}}},computed:(0,c.mapGetters)(["isLogin"]),onLoad:function(t){if(this.from="routine",!t.cartId)return this.$util.Tips({title:this.$t("请选择要购买的商品")},{tab:3,url:1});switch(this.couponId=t.couponId||0,this.noCoupon=Number(t.noCoupon)||0,this.pinkId=t.pinkId?parseInt(t.pinkId):0,this.addressId=t.addressId||0,this.cartId=t.cartId,this.is_address=!!t.is_address,this.news=t.new&&"0"!==t.new?1:0,this.invChecked=t.invoice_id||"",this.header_type=t.header_type||"1",this.couponTitle=t.couponTitle||this.$t("请选择"),t.invoice_type){case"1":this.invTitle=this.$t("电子普通发票");break;case"2":this.invTitle=this.$t("电子专用发票");break}this.textareaStatus=!0,this.isLogin&&0==this.toPay?this.checkShipping():(0,r.toLogin)()},onShow:function(){var e=this,i=wx.getEnterOptionsSync();if("1038"==i.scene&&"wxef277996acc166c3"==i.referrerInfo.appId&&this.initIn){var n=i.referrerInfo.extraData;this.initIn=!1,n?"success"==n.code?this.$util.Tips({title:this.$t("支付成功"),icon:"success"},{tab:5,url:"/pages/goods/order_pay_status/index?order_id=".concat(this.jumpData.orderId,"&msg=").concat(this.jumpData.msg,"&type=3&totalPrice=").concat(this.totalPrice)}):"cancel"==n.code?this.$util.Tips({title:this.$t("取消支付")},{tab:5,url:"/pages/goods/order_pay_status/index?order_id=".concat(this.jumpData.orderId,"&msg=").concat(this.$t("取消支付"),"&type=3&totalPrice=").concat(this.totalPrice,"&status=2")}):t.reLaunch({url:"/pages/goods/order_pay_status/index?order_id=".concat(this.jumpData.orderId,"&msg=").concat(this.$t("支付失败"),"&totalPrice=").concat(this.totalPrice)}):this.$util.Tips({title:this.$t("取消支付")},{tab:5,url:"/pages/goods/order_pay_status/index?order_id=".concat(this.jumpData.orderId,"&msg=").concat(this.$t("取消支付"),"&type=3&totalPrice=").concat(this.totalPrice,"&status=2")})}t.$on("handClick",(function(i){i&&(e.system_store=i.address),t.$off("handClick")}))},methods:{checkShipping:function(){var e=this,i=this;(0,n.checkShipping)(i.cartId,i.news).then((function(t){0==t.data.type?(i.is_shipping=!0,i.shippingType=0,e.getaddressInfo(),e.getConfirm(),e.$nextTick((function(){this.$refs.addressWindow.getAddressList()}))):1==t.data.type?(i.is_shipping=!1,i.shippingType=0,e.getaddressInfo(),e.getConfirm(),e.$nextTick((function(){this.$refs.addressWindow.getAddressList()}))):2==t.data.type&&(i.is_shipping=!1,i.shippingType=1,e.getConfirm(),e.getList())})).catch((function(e){t.showToast({title:e,icon:"none"})}))},invCancel:function(){this.invChecked="",this.invTitle=this.$t("不开发票"),this.invShow=!1},invChange:function(t){this.invChecked=t,this.invShow=!1;var e=this.invList.find((function(e){return e.id===t})),i="";i+=1===e.header_type?this.$t("个人"):this.$t("企业"),i+=1===e.type?this.$t("普通"):this.$t("专用"),i+=this.$t("发票"),this.invTitle=i},invClose:function(){this.invShow=!1,this.getInvoiceList()},getInvoiceList:function(){var e=this;t.showLoading({title:this.$t("正在加载中")}),(0,a.invoiceList)().then((function(i){t.hideLoading(),e.invList=i.data.map((function(t){return t.id=t.id.toString(),t}));var n=e.invList.find((function(t){return t.id==e.invChecked}));if(n){var a="";a+=1===n.header_type?e.$t("个人"):e.$t("企业"),a+=1===n.type?e.$t("普通"):e.$t("专用"),a+=e.$t("发票"),e.invTitle=a}})).catch((function(e){t.showToast({title:e,icon:"none"})}))},goInvoice:function(){this.getInvoiceList(),this.invShow=!0,this.urlQuery="new=".concat(this.news,"&cartId=").concat(this.cartId,"&pinkId=").concat(this.pinkId,"&couponId=").concat(this.couponId,"&addressId=").concat(this.addressId,"&specialInvoice=").concat(this.special_invoice,"&couponTitle=").concat(this.couponTitle)},onLoadFun:function(){this.getaddressInfo(),this.getConfirm()},onChangeFun:function(t){var e=t,i=e.action||null,n=void 0!=e.value?e.value:null;i&&this[i]&&this[i](n)},payClose:function(){this.pay_close=!1},goPay:function(){this.pay_close=!0},payCheck:function(t){this.payType=t,this.SubOrder()},getList:function(){var e=this,i=t.getStorageSync("user_longitude"),n=t.getStorageSync("user_latitude"),a={latitude:n,longitude:i,page:1,limit:10};(0,o.storeListApi)(a).then((function(t){var i=t.data.list.list||[];e.$set(e,"storeList",i),e.$set(e,"system_store",i[0])})).catch((function(t){}))},changeClose:function(){this.$set(this.address,"address",!1)},showStoreList:function(){this.storeList.length>0&&t.navigateTo({url:"/pages/goods/goods_details_store/index"})},changePayType:function(t){this.payType=t,this.computedPrice()},computedPrice:function(){var t=this,e=this.shippingType;(0,n.postOrderComputed)(this.orderKey,{addressId:this.addressId,useIntegral:this.useIntegral?1:0,couponId:this.couponId,shipping_type:parseInt(e)+1,payType:this.payType}).then((function(i){var n=i.data.result;n&&(t.totalPrice=n.pay_price,t.integral_price=n.deduction_price,t.coupon_price=n.coupon_price,t.integral=t.useIntegral?n.SurplusIntegral:t.usable_integral,t.$set(t.priceGroup,"storePostage",1==e?0:n.pay_postage),t.$set(t.priceGroup,"storePostageDiscount",n.storePostageDiscount))}))},addressType:function(e){var i=this,n=e;this.shippingType!=parseInt(n)&&(this.shippingType=parseInt(n),1==n&&t.getLocation({type:"wgs84",success:function(e){t.setStorageSync("user_latitude",e.latitude),t.setStorageSync("user_longitude",e.longitude),i.getList()},complete:function(){i.getList()}}),this.$nextTick((function(t){i.getConfirm(),i.computedPrice()})))},bindPickerChange:function(t){var e=t.detail.value;this.shippingType=e,this.computedPrice()},ChangCouponsClone:function(){this.$set(this.coupon,"coupon",!1)},changeTextareaStatus:function(){for(var t=0,e=this.coupon.list.length;t<e;t++)this.coupon.list[t].use_title="",this.coupon.list[t].is_use=0;this.textareaStatus=!0,this.status=0,this.$set(this.coupon,"list",this.coupon.list)},ChangCoupons:function(t){for(var e=t,i=this.coupon.list,n=this.$t("请选择"),a=0,s=0,o=i.length;s<o;s++)s!=e&&(i[s].use_title="",i[s].is_use=0);i[e].is_use?(i[e].use_title="",i[e].is_use=0):(i[e].use_title=this.$t("不使用"),i[e].is_use=1,n=i[e].coupon_title,a=i[e].id),this.couponTitle=n,this.couponId=a,this.$set(this.coupon,"coupon",!1),this.$set(this.coupon,"list",i),this.computedPrice()},ChangeIntegral:function(){this.useIntegral=!this.useIntegral,this.computedPrice()},OnChangeAddress:function(t){this.textareaStatus=!0,this.addressId=t,this.address.address=!1,this.getConfirm(),this.getaddressInfo(),this.computedPrice()},bindHideKeyboard:function(t){this.mark=t.detail.value},getConfirm:function(){var e=this,i=this;t.showLoading({title:i.$t("正在加载中"),mask:!0}),(0,n.orderConfirm)(i.cartId,i.news,i.addressId,i.shippingType+1).then((function(n){i.$set(i,"userInfo",n.data.userInfo),i.$set(i,"confirm",n.data.custom_form||[]),e.confirm.map((function(t){"img"===t.label&&(t.value=[])})),i.$set(i,"integral",n.data.usable_integral),i.$set(i,"usable_integral",n.data.usable_integral),i.$set(i,"contacts",n.data.userInfo.real_name),i.$set(i,"contactsTel","0"===n.data.userInfo.record_phone?"":n.data.userInfo.record_phone),i.$set(i,"cartInfo",n.data.cartInfo),i.$set(i,"integralRatio",n.data.integralRatio),i.$set(i,"offlinePostage",n.data.offlinePostage),i.$set(i,"orderKey",n.data.orderKey),i.$set(i,"valid_count",n.data.valid_count),i.$set(i,"discount_id",n.data.discount_id),i.$set(i,"priceGroup",n.data.priceGroup),i.$set(i,"totalPrice",i.$util.$h.Add(parseFloat(n.data.priceGroup.totalPrice),parseFloat(n.data.priceGroup.storePostage))),i.$set(i,"seckillId",parseInt(n.data.seckill_id)),i.$set(i,"invoice_func",n.data.invoice_func),i.$set(i,"special_invoice",n.data.special_invoice),i.$set(i,"store_self_mention",n.data.store_self_mention),i.$set(i,"virtual_type",n.data.virtual_type||0),i.$set(i,"integral_open",n.data.integral_open),t.hideLoading(),i.cartArr[0].payStatus=n.data.pay_weixin_open||0,i.cartArr[1].payStatus=n.data.ali_pay_status||0,i.cartArr[1].payStatus=0,i.cartArr[2].number=n.data.userInfo.now_money,i.cartArr[2].payStatus=1==n.data.yue_pay_status?n.data.yue_pay_status:0,2==n.data.offline_pay_status||n.data.deduction?i.cartArr[3].payStatus=0:i.cartArr[3].payStatus=1,i.cartArr[4].payStatus=n.data.friend_pay_status||0,i.$set(i,"ChangePrice",i.totalPrice),i.getBargainId(),i.getCouponList(),e.addressId&&e.computedPrice()})).catch((function(i){return t.hideLoading(),e.$util.Tips({title:i})}))},getBargainId:function(){var t=this,e=t.cartInfo,i=0,n=0,a=0,s=0;e.forEach((function(t,e,o){i=o[e].bargain_id,n=o[e].combination_id,a=o[e].discount_id,s=o[e].advance_id})),t.$set(t,"BargainId",parseInt(i)),t.$set(t,"combinationId",parseInt(n)),t.$set(t,"discountId",parseInt(a)),t.$set(t,"advanceId",parseInt(s)),3==t.cartArr.length&&(i||n||t.seckillId||a)&&(t.cartArr[2].payStatus=0,t.$set(t,"cartArr",t.cartArr))},getCouponList:function(){var t=this.shippingType,e=this,i={cartId:this.cartId,new:this.news,shippingType:parseInt(t)+1};(0,n.getCouponsOrderPrice)(this.totalPrice,i).then((function(t){e.$set(e.coupon,"list",t.data),e.openType=1}))},getaddressInfo:function(){var t=this;t.addressId?(0,a.getAddressDetail)(t.addressId).then((function(e){e.data.is_default=parseInt(e.data.is_default),t.addressInfo=e.data||{},t.addressId=e.data.id||0,t.address.addressId=e.data.id||0})):(0,a.getAddressDefault)().then((function(e){e.data.is_default=parseInt(e.data.is_default),t.addressInfo=e.data||{},t.addressId=e.data.id||0,t.address.addressId=e.data.id||0}))},payItem:function(t){var e=this,i=t;e.active=i,e.animated=!0,e.payType=e.cartArr[i].value,e.computedPrice(),setTimeout((function(){e.car()}),500)},couponTap:function(){var t=this;this.coupon.coupon=!0,this.coupon.list.forEach((function(e,i){e.id==t.couponId?e.is_use=1:e.is_use=0})),this.$set(this.coupon,"list",this.coupon.list)},car:function(){var t=this;t.animated=!1},onAddress:function(){var e=this;this.addressInfo.real_name?(e.textareaStatus=!1,e.address.address=!0,e.pagesUrl="/pages/users/user_address_list/index?news="+this.news+"&cartId="+this.cartId+"&pinkId="+this.pinkId+"&couponId="+this.couponId):t.navigateTo({url:"/pages/users/user_address/index?cartId="+this.cartId+"&pinkId="+this.pinkId+"&couponId="+this.couponId+"&new="+this.news})},formpost:function(t,e){var i=document.createElement("form");for(var n in i.action=t,i.method="post",i.target="_self",i.style.display="none",e){var a=document.createElement("input");a.name=n,a.value=e[n],i.appendChild(a)}document.body.appendChild(i),this.$nextTick((function(t){i.submit()}))},payment:function(e){var i=this,a=this;(0,n.orderCreate)(a.orderKey,e).then((function(n){var s=n.data.status,o=n.data.result.orderId,r=n.data.result.jsConfig,c="/pages/goods/order_pay_status/index?order_id="+o+"&msg="+n.msg+"&type=3&totalPrice="+i.totalPrice,u="/pages/users/payment_on_behalf/index?order_id="+o+"&spread="+i.$store.state.app.uid;switch(s){case"ORDER_EXIST":case"EXTEND_ORDER":return t.hideLoading(),a.$util.Tips({title:n.msg},{tab:5,url:c});case"ALLINPAY_PAY":t.hideLoading(),i.initIn=!0,wx.openEmbeddedMiniProgram({appId:"wxef277996acc166c3",extraData:{cusid:r.cusid,appid:r.appid,version:r.version,trxamt:r.trxamt,reqsn:r.reqsn,notify_url:r.notify_url,body:r.body,remark:r.remark,validtime:r.validtime,randomstr:r.randomstr,paytype:r.paytype,sign:r.sign,signtype:r.signtype}}),i.jumpData={orderId:n.data.result.orderId,msg:n.msg};break;case"PAY_ERROR":return t.hideLoading(),a.$util.Tips({title:n.msg},{tab:5,url:c});case"SUCCESS":return t.hideLoading(),(a.BargainId||a.combinationId||a.pinkId||a.seckillId||a.discountId)&&"friend"!=e.payType?a.$util.Tips({title:n.msg,icon:"success"},{tab:4,url:c}):a.$util.Tips({title:n.msg,icon:"success"},{tab:4,url:"friend"==e.payType?u:c});case"WECHAT_PAY":a.toPay=!0;var d="";d=t.requestOrderPayment?"requestOrderPayment":"requestPayment",t[d]({timeStamp:r.timestamp,nonceStr:r.nonceStr,package:r.package,signType:r.signType,paySign:r.paySign,success:function(e){return t.hideLoading(),a.BargainId||a.combinationId||a.pinkId||a.seckillId||a.discountId?a.$util.Tips({title:a.$t("支付成功"),icon:"success"},{tab:4,url:c}):a.$util.Tips({title:a.$t("支付成功"),icon:"success"},{tab:5,url:c})},fail:function(e){return t.hideLoading(),a.$util.Tips({title:a.$t("取消支付")},{tab:5,url:c+"&status=2"})},complete:function(e){if(t.hideLoading(),"requestPayment:cancel"==n.errMsg||"requestOrderPayment:cancel"==e.errMsg)return a.$util.Tips({title:a.$t("取消支付")},{tab:5,url:c+"&status=2"})}});break;case"PAY_DEFICIENCY":return t.hideLoading(),a.$util.Tips({title:n.msg},{tab:5,url:c+"&status=1"});case"WECHAT_H5_PAY":t.hideLoading(),a.$util.Tips({title:a.$t("订单创建成功")},{tab:4,url:c+"&status=0"}),setTimeout((function(){location.href=n.data.result.jsConfig.mweb_url}),2e3);break;case"ALIPAY_PAY":t.navigateTo({url:"/pages/users/alipay_invoke/index?id=".concat(o,"&link=").concat(r.qrCode)});break}})).catch((function(e){return t.hideLoading(),a.$util.Tips({title:e})}))},clickTextArea:function(){this.$refs.textarea.focus()},SubOrder:function(e){var i=this,n={};if(!i.payType)return i.$util.Tips({title:i.$t("请选择支付方式")});if(!i.addressId&&!i.shippingType&&!i.virtual_type)return i.$util.Tips({title:i.$t("请选择收货地址")});if(1==i.shippingType){if(""==i.contacts||""==i.contactsTel)return i.$util.Tips({title:i.$t("请填写联系人或联系人电话")});if(!/^1(3|4|5|7|8|9|6)\d{9}$/.test(i.contactsTel))return i.$util.Tips({title:i.$t("请输入正确的手机号码")});if(!i.contacts)return i.$util.Tips({title:i.$t("请输入姓名")});if(0==i.storeList.length)return i.$util.Tips({title:i.$t("暂无门店,请选择其他方式")})}for(var a=0;a<i.confirm.length;a++){var o=i.confirm[a];if(o.status){if(("text"===o.label||"data"===o.label||"time"===o.label||"id"===o.label)&&!o.value.trim())return t.showToast({title:i.$t("请输入")+"".concat(o.title),icon:"none"});if("number"===o.label&&o.value<=0)return t.showToast({title:i.$t("请输入")+"".concat(o.title),icon:"none"});if("email"===o.label&&!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(o.value))return t.showToast({title:i.$t("请输入正确的")+"".concat(o.title),icon:"none"});if("phone"===o.label&&!/^1(3|4|5|7|8|9|6)\d{9}$/i.test(o.value))return t.showToast({title:i.$t("请输入正确的")+"".concat(o.title),icon:"none"});if("img"===o.label&&!o.value.length)return t.showToast({title:i.$t("请上传")+"".concat(o.title),icon:"none"})}}if(n={custom_form:i.confirm,real_name:i.contacts,phone:i.contactsTel,addressId:i.addressId,formId:"",couponId:i.couponId,payType:i.payType,useIntegral:i.useIntegral,bargainId:i.BargainId,combinationId:i.combinationId,discountId:i.discountId,pinkId:i.pinkId,advanceId:i.advanceId,seckill_id:i.seckillId,mark:i.mark,store_id:i.system_store?i.system_store.id:0,from:i.from,shipping_type:i.$util.$h.Add(i.shippingType,1),new:i.news,invoice_id:i.invChecked},"yue"==n.payType&&parseFloat(i.userInfo.now_money)<parseFloat(i.totalPrice))return i.$util.Tips({title:i.$t("余额不足")});t.showLoading({title:i.$t("订单支付中")}),(0,s.openPaySubscribe)().then((function(){i.payment(n)}))},bindDateChange:function(t,e){this.confirm[e].value=t.target.value},bindTimeChange:function(t,e){this.confirm[e].value=t.target.value},getDate:function(t){var e=new Date,i=e.getFullYear(),n=e.getMonth()+1,a=e.getDate();return"start"===t?i-=60:"end"===t&&(i+=2),n=n>9?n:"0"+n,a=a>9?a:"0"+a,"".concat(i,"-").concat(n,"-").concat(a)},uploadpic:function(t,e){var i=this,n=this;this.canvasStatus=!0,n.$util.uploadImageChange("upload/image",(function(t){e.value.push(t.data.url)}),(function(t){i.canvasStatus=!1}),(function(t){i.canvasWidth=t.w,i.canvasHeight=t.h}))},DelPic:function(t,e){var i=this;this.confirm[t].value;i.confirm[t].value.splice(e,1)},inputTripClick:function(){this.inputTrip=!0}}};e.default=_}).call(this,i("543d")["default"])},"9da5":function(t,e,i){"use strict";var n=i("2907"),a=i.n(n);a.a},c023:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return s})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=(t._self._c,!t.virtual_type&&0==t.shippingType&&t.addressInfo.real_name&&t.addressInfo.is_default?t.$t("默认"):null),n=t.virtual_type||0!=t.shippingType||t.addressInfo.real_name?null:t.$t("设置收货地址"),a=t.virtual_type||0==t.shippingType||t.storeList.length>0?null:t.$t("暂无门店信息"),s=t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.noCoupon||t.discountId||t.advanceId?null:t.$t("优惠券"),o=t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.advanceId||!t.integral_open?null:t.$t("积分抵扣"),r=t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.advanceId||!t.integral_open||!t.useIntegral?null:t.$t("剩余积分"),c=t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.advanceId||!t.integral_open||t.useIntegral?null:t.$t("当前积分"),u=t.invoice_func||t.special_invoice?t.$t("开具发票"):null,d=1==t.shippingType?t.$t("用户姓名"):null,l=1==t.shippingType?t.$t("请输入姓名"):null,p=1==t.shippingType?t.$t("联系电话"):null,h=1==t.shippingType?t.$t("请输入手机号"):null,f=t.textareaStatus?t.$t("备注说明"):null,g=!t.textareaStatus||t.coupon.coupon||t.inputTrip?null:t.mark||t.$t("填写备注信息，100字以内"),m=t.textareaStatus&&!t.coupon.coupon&&t.inputTrip?t.$t("填写备注信息，100字以内"):null,I=t.confirm.length?t.__map(t.confirm,(function(e,i){var n=t.__get_orig(e),a="text"==e.label?t.$t("请填写"):null,s="number"==e.label?t.$t("请填写"):null,o="email"==e.label?t.$t("请填写"):null,r="id"==e.label?t.$t("请填写"):null,c="phone"==e.label?t.$t("请填写"):null,u="img"==e.label&&e.value.length<8?t.$t("上传图片"):null;return{$orig:n,m15:a,m16:s,m17:o,m18:r,m19:c,m20:u}})):null,_=t.$t("商品总价"),v=t.$t("￥"),$=(parseFloat(t.priceGroup.totalPrice)+parseFloat(t.priceGroup.vipPrice)).toFixed(2),y=t.priceGroup.storePostage>0||t.priceGroup.storePostageDiscount>0?t.$t("配送运费"):null,T=t.priceGroup.storePostage>0||t.priceGroup.storePostageDiscount>0?t.$t("￥"):null,b=t.priceGroup.storePostage>0||t.priceGroup.storePostageDiscount>0?(parseFloat(t.priceGroup.storePostage)+parseFloat(t.priceGroup.storePostageDiscount)).toFixed(2):null,P=!(t.priceGroup.levelPrice>0&&t.userInfo.vip)||t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.discountId?null:t.$t("用户等级优惠"),k=!(t.priceGroup.levelPrice>0&&t.userInfo.vip)||t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.discountId?null:t.$t("￥"),w=!(t.priceGroup.levelPrice>0&&t.userInfo.vip)||t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.discountId?null:parseFloat(t.priceGroup.levelPrice).toFixed(2),S=!(t.priceGroup.memberPrice>0&&t.userInfo.vip)||t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.discountId?null:t.$t("付费会员优惠"),C=!(t.priceGroup.memberPrice>0&&t.userInfo.vip)||t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.discountId?null:t.$t("￥"),x=!(t.priceGroup.memberPrice>0&&t.userInfo.vip)||t.pinkId||t.BargainId||t.combinationId||t.seckillId||t.discountId?null:parseFloat(t.priceGroup.memberPrice).toFixed(2),L=t.priceGroup.storePostageDiscount>0?t.$t("会员运费优惠"):null,A=t.priceGroup.storePostageDiscount>0?t.$t("￥"):null,G=t.priceGroup.storePostageDiscount>0?parseFloat(t.priceGroup.storePostageDiscount).toFixed(2):null,D=t.coupon_price>0?t.$t("优惠券抵扣"):null,F=t.coupon_price>0?t.$t("￥"):null,E=t.coupon_price>0?parseFloat(t.coupon_price).toFixed(2):null,B=t.integral_price>0?t.$t("积分抵扣"):null,O=t.integral_price>0?t.$t("￥"):null,j=t.integral_price>0?parseFloat(t.integral_price).toFixed(2):null,R=t.$t("合计"),W=t.$t("￥"),Y=t.valid_count>0&&!t.discount_id||t.valid_count==t.cartInfo.length&&t.discount_id?t.$t("立即支付"):null,q=t.valid_count>0&&!t.discount_id||t.valid_count==t.cartInfo.length&&t.discount_id?null:t.$t("立即支付"),M=t.totalPrice.toString();t._isMounted||(t.e0=function(e){t.inputTrip=!1}),t.$mp.data=Object.assign({},{$root:{m0:i,m1:n,m2:a,m3:s,m4:o,m5:r,m6:c,m7:u,m8:d,m9:l,m10:p,m11:h,m12:f,m13:g,m14:m,l0:I,m21:_,m22:v,g0:$,m23:y,m24:T,g1:b,m25:P,m26:k,g2:w,m27:S,m28:C,g3:x,m29:L,m30:A,g4:G,m31:D,m32:F,g5:E,m33:B,m34:O,g6:j,m35:R,m36:W,m37:Y,m38:q,g7:M}})},s=[]}},[["267f","common/runtime","common/vendor"]]]);