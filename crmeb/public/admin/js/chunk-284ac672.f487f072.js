(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-284ac672"],{"0022":function(t,e,s){},"3ef4":function(t,e,s){"use strict";s("fa8d")},6599:function(t,e,s){"use strict";s("0022")},7624:function(t,e,s){"use strict";e["a"]=["em-smile","em-laughing","em-blush","em-smiley","em-relaxed","em-smirk","em-heart_eyes","em-kissing_heart","em-kissing_closed_eyes","em-flushed","em-relieved","em-satisfied","em-grin","em-wink","em-stuck_out_tongue_winking_eye","em-stuck_out_tongue_closed_eyes","em-grinning","em-kissing","em-kissing_smiling_eyes","em-stuck_out_tongue","em-sleeping","em-worried","em-frowning","em-anguished","em-open_mouth","em-grimacing","em-confused","em-hushed","em-expressionless","em-unamused","em-sweat_smile","em-sweat","em-disappointed_relieved","em-weary","em-pensive","em-disappointed","em-confounded","em-fearful","em-cold_sweat","em-persevere","em-cry","em-sob","em-joy","em-astonished","em-scream","em-tired_face","em-angry","em-rage","em-triumph","em-sleepy","em-yum","em-mask","em-sunglasses","em-dizzy_face","em-imp","em-smiling_imp","em-neutral_face","em-no_mouth","em-innocent","em-alien"]},c168:function(t,e,s){"use strict";s.r(e);var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"chat-box"},[s("div",{staticClass:"head-box"},[s("div",{staticClass:"back",on:{click:t.goBack}},[s("span",{staticClass:"iconfont iconfanhui"})]),s("div",{staticClass:"title"},[t._v(t._s(t.nickname?t.nickname+"-":"")+"对话详情")])]),t.productId&&t.productInfo.id?s("div",{staticClass:"broadcast-details_box"},[s("div",{staticClass:"broadcast_details_img"},[s("img",{attrs:{src:t.productInfo.image}})]),s("div",{staticClass:"broadcast_details_picBox"},[s("div",{staticClass:"broadcast_details_tit",domProps:{textContent:t._s(t.productInfo.store_name)}}),s("div",{staticClass:"acea-row row-between"},[s("div",{staticClass:"broadcast_details_pic"},[t._v("\n                    ￥"+t._s(t.productInfo.price)),s("span",{staticClass:"broadcast_details_pic_num"},[t._v("￥"+t._s(t.productInfo.ot_price))])]),s("div",{staticClass:"broadcast_details_btn",on:{click:t.sendProduct}},[t._v("发送客服")])])])]):t._e(),t.orderId&&t.orderInfo.id?s("div",{staticClass:"broadcast_box"},[s("div",{staticClass:"broadcast-details_num broadcast_num"},[s("span",[t._v("订单号："+t._s(t.orderInfo.order_id))]),s("span",[t._v(t._s(t.orderInfo.add_time_y)+" "+t._s(t.orderInfo.add_time_h))])]),s("div",{staticClass:"broadcast-details_box"},[s("div",{staticClass:"broadcast_details_img"},[s("img",{attrs:{src:t.cartInfo.productInfo.image}}),s("div",{staticClass:"broadcast_details_model"},[t._v("\n                    "+t._s(t.orderInfo.cartInfo?t.orderInfo.cartInfo.length:0)+"件商品\n                ")])]),s("div",{staticClass:"broadcast_details_picBox"},[s("div",{staticClass:"broadcast_details_tit"},[t._v("\n                    "+t._s(t.cartInfo.productInfo.store_name)+"\n                ")]),s("div",{staticClass:"acea-row row-between"},[s("div",{staticClass:"broadcast_details_pic"},[t._v("\n                        ￥"+t._s(t.cartInfo.productInfo.price)),s("text",{staticClass:"broadcast_details_pic_num"},[t._v("￥"+t._s(t.cartInfo.productInfo.ot_price))])]),s("div",{staticClass:"broadcast_details_btn",on:{click:t.sendOrder}},[t._v("\n                        发送客服\n                    ")])])])])]):t._e(),s("div",{staticClass:"chat-scroll-box"},[s("vue-scroll",{ref:"scrollBox",attrs:{ops:t.ops},on:{"refresh-activate":t.handleActivate,"refresh-start":t.handleStart,"refresh-before-deactivate":t.handleBeforeDeactivate,"refresh-deactivate":t.handleDeactivate}},[s("div",{staticClass:"slot-refresh",attrs:{slot:"refresh-deactive"},slot:"refresh-deactive"}),s("div",{staticClass:"slot-refresh",attrs:{slot:"refresh-beforeDeactive"},slot:"refresh-beforeDeactive"}),s("div",{ref:"chat",staticClass:"chat",staticStyle:{padding:".3rem"},attrs:{id:"chatBox"}},t._l(t.records,(function(e,i){return s("div",{key:i,attrs:{id:"chat_"+e.id}},[e.show?s("div",{staticClass:"day-box"},[t._v(t._s(e.time))]):t._e(),s("div",{staticClass:"chat-item",class:{"right-box":e.to_uid==t.toUid}},[s("img",{staticClass:"avatar",attrs:{src:e.avatar,mode:""}}),1==e.msn_type?s("div",{staticClass:"msg-box",domProps:{innerHTML:t._s(e.msn)}}):t._e(),3==e.msn_type?s("div",{directives:[{name:"viewer",rawName:"v-viewer"}],staticClass:"img-box"},[s("img",{directives:[{name:"lazy",rawName:"v-lazy",value:e.msn,expression:"item.msn"}],attrs:{mode:"widthFix"}})]):t._e(),5==e.msn_type?s("div",{staticClass:"product-box",on:{click:function(s){return t.goProduct(e)}}},[s("img",{attrs:{src:e.productInfo.image}}),s("div",{staticClass:"info"},[s("div",{staticClass:"price"},[s("span",[t._v("￥")]),t._v(t._s(e.productInfo.price))]),s("div",{staticClass:"name line2"},[t._v(t._s(e.productInfo.store_name))])])]):t._e(),6==e.msn_type?s("div",{staticClass:"order-box",on:{click:function(s){return t.goOrderDetail(e)}}},[s("div",{staticClass:"title"},[t._v("订单ID: "+t._s(e.orderInfo.order_id))]),s("div",{staticClass:"info"},[s("img",{attrs:{src:e.orderInfo.cartInfo[0].productInfo.image}}),s("div",{staticClass:"product-info"},[s("div",{staticClass:"name line2"},[t._v(t._s(e.orderInfo.cartInfo[0].productInfo.store_name))]),s("div",{staticClass:"price"},[t._v("¥"+t._s(e.orderInfo.cartInfo[0].productInfo.price))])])])]):t._e()])])})),0)])],1),s("div",{staticClass:"footer-box"},[t.userToken?s("div",{staticClass:"words",on:{click:t.showWords}},[s("Upload",{staticStyle:{"margin-top":"1px",display:"inline-block"},attrs:{"show-upload-list":!1,action:t.fileUrl,"before-upload":t.beforeUpload,data:t.uploadData,headers:t.header,multiple:!0,"on-success":t.handleSuccess,format:["jpg","jpeg","png","gif"],"on-format-error":t.handleFormatError}},[s("span",{staticClass:"iconfont icontupian3"})])],1):t._e(),s("div",{staticClass:"input-box"},[s("Input",{attrs:{placeholder:"请输入内容"},model:{value:t.con,callback:function(e){t.con=e},expression:"con"}}),s("span",{staticClass:"iconfont iconfasong",class:{isSend:t.isSend},on:{click:t.sendText}})],1),s("div",{staticClass:"emoji",on:{click:function(e){return t.openBox(1)}}},[s("span",{staticClass:"iconfont iconbiaoqing2"})])]),s("div",{directives:[{name:"show",rawName:"v-show",value:t.isSwiper,expression:"isSwiper"}],staticClass:"banner slider-banner"},[s("swiper",{ref:"mySwiper",staticClass:"swiper-wrapper",attrs:{options:t.swiperOptions}},t._l(t.emojiGroup,(function(e,i){return s("swiper-slide",{key:i},t._l(e,(function(e){return s("i",{key:e,staticClass:"em",class:e,on:{click:function(s){return t.addEmoji(e)}}})})),0)})),1)],1)])},o=[],a=s("75fc"),n=(s("a481"),s("ac6a"),s("d708")),r=s("49ea"),c=s("c276"),d=s("7624"),l=s("42e3"),u=function(t,e){e=1*e||1;var s=[];return t.forEach((function(t,i){i%e===0&&s.push([]),s[s.length-1].push(t)})),s},f={name:"chat_mobile",data:function(){return{ops:{vuescroll:{mode:"slide",enable:!1,auto:!1,autoLoadDistance:0,pullRefresh:{enable:!0,auto:!1,autoLoadDistance:0,tips:{deactive:"",active:"上拉加载更多",start:"Loading...",beforeDeactive:" "}},pushLoad:{enable:!1}},bar:{background:"#393232",opacity:".5",size:"2px"}},swiperOptions:{},status:!1,loading:!1,isTool:!1,isSwiper:!1,isWords:!1,autoplay:!1,circular:!0,interval:3e3,duration:500,emojiGroup:u(d["a"],21),con:"",toUid:"",limit:15,upperId:0,chatList:[],kefuInfo:{},scrollTop:0,active:!0,isScroll:!0,oldHeight:0,selector:"",transferList:[],isTransfer:!1,uploadData:{},header:{},fileUrl:"",userToken:"",tourist_uid:"",orderId:"",orderInfo:"",cartInfo:"",productId:"",productInfo:"",tourist_avatar:""}},computed:{isSend:function(){return 0!=this.con.length},records:function(){var t=this;return this.chatList.map((function(e,s){return e.time=t.$moment(1e3*e.add_time).format("MMMDo h:mm"),s?e.add_time-t.chatList[s-1].add_time>=300?e.show=!0:e.show=!1:e.show=!0,e}))}},created:function(){var t=localStorage.getItem("LOGIN_STATUS_TOKEN")||"";this.fileUrl=n["a"].apiBaseURL.replace("adminapi","kefuapi")+"/tourist/upload",this.userToken=t,this.toUid=this.$route.query.toUid||"",this.nickname=this.$route.query.nickname||"",this.orderId=this.$route.query.orderId||"",this.productId=this.$route.query.product_id||""},mounted:function(){var t=this;this.$wechat._isMobile()||this.$router.replace("/kefu/appChat");var e=this;this.getServiceList(),this.userToken&&(this.getOrderInfo(),this.getGoodsInfo()),this.header["Authori-zation"]="Bearer "+Object(c["d"])("kefu_token"),r["a"].then((function(s){t.userToken&&s.send({type:"login",data:t.userToken}),s.$on(["reply","chat"],(function(e){1!=e.msn_type&&2!=e.msn_type||(e.msn=t.replace_em(e.msn)),t.chatList.push(e),t.$nextTick((function(){t.$refs["scrollBox"].refresh(),t.scrollBom()})),setTimeout((function(e){t.$refs["scrollBox"].refresh()}),300)})),s.$on("socket_error",(function(){t.$Message.error("连接失败")})),s.$on("error",(function(){t.$Message.error("连接失败")})),s.$on("to_transfer",(function(t){s.send({data:{id:t.toUid},type:"to_chat"})})),s.$on("online",(function(t){0==t.online&&t.uid==e.toUid&&e.$Modal.confirm({title:"提示",content:"客服已离线，是否需要反馈？",okText:"确定",cancelText:"取消",onOk:function(){e.$router.replace({path:"/kefu/mobile_feedback"})}})}))})),this.$nextTick((function(){}))},methods:{goBack:function(){this.$router.go(-1)},handleFormatError:function(t){this.$Message.error("上传图片只能是 jpg、jpg、jpeg、gif 格式!")},getGoodsInfo:function(){var t=this;this.productId&&Object(l["z"])(this.productId).then((function(e){t.productInfo=e.data})).catch((function(e){t.$Message.error(e.msg)}))},getOrderInfo:function(){var t=this;this.orderId&&Object(l["k"])(this.orderId,{token:this.userToken}).then((function(e){t.orderInfo=e.data,t.orderInfo.add_time_h&&(t.orderInfo.add_time_h=t.orderInfo.add_time_h.substring(0,t.orderInfo.add_time_h.lastIndexOf(":"))),t.orderInfo.cartInfo.length&&(t.cartInfo=t.orderInfo.cartInfo[0])}))},getServiceList:function(){var t=this;Object(l["K"])({token:this.userToken}).then((function(e){t.toUid=e.data.uid,t.tourist_uid=e.data.tourist_uid,document.title=e.data.nickname,t.tourist_avatar=e.data.tourist_avatar,t.userToken&&t.getChatList();var s={data:{id:e.data.uid,tourist_uid:t.tourist_uid},type:"to_chat"};r["a"].then((function(t){t.send(s)}))})).catch((function(e){t.$Message.error(e.msg),setTimeout((function(e){t.$router.replace({path:"/kefu/mobile_feedback"})}),2e3)}))},beforeUpload:function(t){var e=this,s="image/jpeg"===t.type||"image/png"===t.type;s||this.$Message.error("上传图片只能是 JPG、PNG 格式!"),this.uploadData={filename:t,token:this.userToken};var i=new Promise((function(t){e.$nextTick((function(){t(!0)}))}));return i},handleSuccess:function(t,e,s){200===t.status?(this.$Message.success(t.msg),this.sendMsg(t.data.url,3)):this.$Message.error(t.msg)},scrollBom:function(){var t=this;setTimeout((function(e){var s=parseFloat(document.getElementById("chatBox").offsetHeight);t.$refs["scrollBox"]&&t.$refs["scrollBox"].scrollTo({y:s},300)}),300)},goOrderDetail:function(t){this.$router.push({path:"/kefu/orderDetail/".concat(t.orderInfo.id)})},openBox:function(t){var e=this;1==t?(this.isTool=!1,this.isSwiper=!this.isSwiper):(this.isSwiper=!1,this.isTool=!this.isTool),this.$refs["scrollBox"].refresh(),this.$nextTick((function(){e.scrollBom()}))},showWords:function(){this.isWords=!0},goTransfer:function(){this.isTransfer=!0},closeTransfer:function(){this.transferList.forEach((function(t,e){t.isCheck=!1})),this.isTransfer=!1},goodsInfo:function(){this.$router.push({path:"/kefu/goods/list?toUid="+this.toUid})},addEmoji:function(t){var e="[".concat(t,"]");this.con+=e},replace_em:function(t){return t=t.replace(/\[em-([a-z_]*)\]/g,"<span class='em em-$1'/></span>"),t},getChatList:function(){var t=this;Object(l["e"])({limit:this.limit,uid:this.toUid,upperId:this.upperId,token:this.userToken}).then((function(e){e.data.forEach((function(e){1!=e.msn_type&&2!=e.msn_type||(e.msn=t.replace_em(e.msn))}));var s="";s=0==t.upperId?"chat_".concat(e.data[e.data.length-1].id):"chat_".concat(t.chatList[0].id),t.selector=s,t.chatList=[].concat(Object(a["a"])(e.data),Object(a["a"])(t.chatList)),t.loading=!1,t.isScroll=e.data.length>=t.limit,t.$refs["scrollBox"].refresh(),t.$nextTick((function(){t.$emit("change",!0),console.log(parseFloat(document.getElementById(s).offsetTop)-60,"getChatList");var e=parseFloat(document.getElementById(s).offsetTop)-60;t.$refs["scrollBox"].scrollTo({y:e},0)}))}))},sendOrder:function(){this.sendMsg(this.orderId,6),this.orderId=0,this.orderInfo={}},sendProduct:function(){this.sendMsg(this.productId,5),this.productId=0,this.productInfo={}},sendText:function(){this.isSend||this.$Message.error("请输入内容"),this.sendMsg(this.con,1),this.con=""},sendMsg:function(t,e){var s={type:"chat",data:{msn:t,type:e,is_tourist:this.userToken?0:1,to_uid:this.toUid,tourist_uid:this.tourist_uid,form_type:this.$wechat.isWeixin()?1:3,tourist_avatar:this.userToken?"":this.tourist_avatar}};r["a"].then((function(t){t.send(s)}))},uploadImg:function(){var t=this;t.$util.uploadImageOne("upload/image",(function(e){200==e.status&&t.sendMsg(e.data.url,3)}))},goProduct:function(t){var e=window.location.protocol+"//"+window.location.host+"/pages/goods_details/index?id="+t.msn;window.open(e,"_blank")},goAdminOrder:function(){var t=window.location.protocol+"//"+window.location.host+"/pages/users/order_details/index?order_id="+item.msn;window.open(t,"_blank")},height:function(){var t=this,e=0,s=uni.createSelectorQuery().select(".chat");setTimeout((function(i){s.boundingClientRect((function(s){console.log(s.height,"data.height"),e=s.height,t.active?t.scrollTop=parseInt(e)+500:t.scrollTop=parseInt(e)+100})).exec()}),1e3)},handleActivate:function(t,e){this.upperId=this.chatList[0].id},handleStart:function(t,e,s){setTimeout((function(){s()}),2e3)},handleBeforeDeactivate:function(t,e,s){this.userToken?(this.getChatList(),this.$on("change",(function(t){t&&s()}))):s()},handleDeactivate:function(t,e){var s=parseFloat(document.getElementById(this.selector).offsetTop)-60;console.log(s,"handleDeactivate"),this.$refs["scrollBox"].scrollTo({y:s},0)}}},h=f,m=(s("3ef4"),s("6599"),s("2877")),p=Object(m["a"])(h,i,o,!1,null,"10eb38e4",null);e["default"]=p.exports},fa8d:function(t,e,s){}}]);