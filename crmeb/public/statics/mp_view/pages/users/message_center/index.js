(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/message_center/index"],{"0217":function(t,e,n){"use strict";var i=n("077b"),s=n.n(i);s.a},"077b":function(t,e,n){},"3df3":function(t,e,n){"use strict";(function(t){n("d5c5");i(n("66fd"));var e=i(n("e336"));function i(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=n,t(e.default)}).call(this,n("543d")["createPage"])},"65c3":function(t,e,n){"use strict";n.r(e);var i=n("b50d"),s=n.n(i);for(var a in i)"default"!==a&&function(t){n.d(e,t,(function(){return i[t]}))}(a);e["default"]=s.a},ad42:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return s})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return i}));var s=function(){var t=this,e=t.$createElement,n=(t._self._c,t.__map(t.tabsList,(function(e,n){var i=t.__get_orig(e),s=t.$t(e.name);return{$orig:i,m0:s}}))),i=t.list.length&&1===t.type?t.__map(t.list,(function(e,n){var i=t.__get_orig(e),s=t.$t(e.nickname),a=3===e.message_type?t.$t("[图片]"):null,o=4===e.message_type?t.$t("[语音]"):null,c=5===e.message_type?t.$t("[商品]"):null,l=6===e.message_type?t.$t("[订单]"):null;return{$orig:i,m1:s,m2:a,m3:o,m4:c,m5:l}})):null,s=t.list.length&&0===t.type?t.__map(t.list,(function(e,n){var i=t.__get_orig(e),s={id:e.id},a=t.$t(e.title)||"--";return{$orig:i,a0:s,m6:a}})):null,a=t.list.length&&0===t.type||!t.finished||t.list.length?null:t.$t("亲、暂无消息记录哟！");t._isMounted||(t.e0=function(e,n){var i=[],s=arguments.length-2;while(s-- >0)i[s]=arguments[s+2];var a=i[i.length-1].currentTarget.dataset,o=a.eventParams||a["event-params"];n=o.item;return t.bindClick(e,n)}),t.$mp.data=Object.assign({},{$root:{l0:n,l1:i,l2:s,m7:a}})},a=[]},b50d:function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n("cff9"),s=o(n("cf74")),a=n("d5d8");function o(t){return t&&t.__esModule?t:{default:t}}var c=function(){Promise.all([n.e("common/vendor"),n.e("components/home/index")]).then(function(){return resolve(n("4f25"))}.bind(null,n)).catch(n.oe)},l=function(){n.e("components/tuiDrawer/index").then(function(){return resolve(n("b33f"))}.bind(null,n)).catch(n.oe)},u={mixins:[s.default],components:{home:c,tuiDrawer:l},data:function(){return{imgHost:a.HTTP_REQUEST_URL,list:[],page:1,type:0,limit:20,loading:!1,finished:!1,tabsList:[{key:0,name:"站内消息"},{key:1,name:"客服消息"}],startData:{clientX:0,clientY:0},actions:[{name:"删除",color:"#fff",fontsize:28,width:70,background:"#E6A23C"},{name:"已读",color:"#fff",fontsize:28,width:70,background:"#409EFF"}],actionsIsLook:[{name:"删除",color:"#fff",fontsize:28,width:70,background:"#E6A23C"}]}},onShow:function(){this.page=1,this.list=[],this.changeTabs(this.type)},onReachBottom:function(){1===this.type?this.getList():this.messageSystem()},onPullDownRefresh:function(){this.page=1,this.finished=!1,this.list=[],1===this.type?this.getList():this.messageSystem()},methods:{start:function(t){this.startData.clientX=t.changedTouches[0].clientX,this.startData.clientY=t.changedTouches[0].clientY},end:function(t){var e=t.changedTouches[0].clientX-this.startData.clientX,n=t.changedTouches[0].clientY-this.startData.clientY;n>50||n<-50||(e>50?1==this.type&&(this.type=0,this.changeTabs(this.type)):e<-50&&0==this.type&&(this.type=1,this.changeTabs(this.type)))},bindClick:function(e,n){var s=this;0==e.index?(0,i.msgLookDel)({id:n.id,key:"is_del",value:1}).then((function(t){var e=s.list.findIndex((function(t){return t.id===n.id}));s.list.splice(e,1)})).catch((function(e){t.showToast({title:e.msg,icon:"none"})})):(0,i.msgLookDel)({id:n.id,key:"look",value:1}).then((function(t){n.look=1})).catch((function(e){t.showToast({title:e.msg,icon:"none"})}))},allLook:function(){var e=this;(0,i.msgLookDel)({id:0,key:"look",value:1,all:1}).then((function(t){e.page=1,e.limit=20,e.list=[],e.finished=!1,1===e.type?e.getList():e.messageSystem()})).catch((function(e){t.showToast({title:e.msg,icon:"none"})}))},changeTabs:function(t){this.type=t,this.page=1,this.limit=20,this.list=[],this.finished=!1,1===t?this.getList():this.messageSystem()},messageSystem:function(){var e=this;this.loading||this.finished||(this.loading=!0,t.showLoading({title:this.$t("加载中")}),(0,i.messageSystem)({page:this.page,limit:this.limit}).then((function(n){var i=n.data;t.hideLoading(),e.loading=!1,e.list=e.list.concat(i.list),e.finished=i.list.length<e.limit,e.page+=1,t.stopPullDownRefresh()})).catch((function(e){t.showToast({title:e.msg,icon:"none"})})))},getList:function(){var e=this;this.loading||this.finished||(this.loading=!0,t.showLoading({title:"加载中"}),(0,i.serviceRecord)({page:this.page,limit:this.limit}).then((function(n){t.stopPullDownRefresh();var i=n.data;t.hideLoading(),e.loading=!1,i.forEach((function(t){1===t.message_type&&(t.message=e.replace_em(t.message)),2===t.message_type&&(t.message=e.replace_em(t.message))})),e.list=e.list.concat(i),e.finished=i.length<e.limit,e.page+=1})).catch((function(e){t.showToast({title:e.msg,icon:"none"})})))},replace_em:function(t){return t=t.replace(/\[em-([a-z_]*)\]/g,"<span class='em em-$1'/></span>"),t},goChat:function(e){t.navigateTo({url:"/pages/extension/customer_list/chat?to_uid="+e+"&type=1"})},goDetail:function(e){t.navigateTo({url:"/pages/users/message_center/messageDetail?id="+e})}}};e.default=u}).call(this,n("543d")["default"])},e336:function(t,e,n){"use strict";n.r(e);var i=n("ad42"),s=n("65c3");for(var a in s)"default"!==a&&function(t){n.d(e,t,(function(){return s[t]}))}(a);n("0217");var o,c=n("f0c5"),l=Object(c["a"])(s["default"],i["b"],i["c"],!1,null,"3d3f9288",null,!1,i["a"],o);e["default"]=l.exports}},[["3df3","common/runtime","common/vendor"]]]);