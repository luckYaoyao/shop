(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-activity-lottery-grids-index~pages-order_pay_status-index~pages-order_pay_status-payLottery~pa~4af4e820"],{"0992":function(t,e,i){"use strict";var r;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return n})),i.d(e,"a",(function(){return r}));var a=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-uni-view",{staticClass:"lottery_container"},[r("v-uni-view",{staticClass:"grid_wrap"},[r("v-uni-view",{staticClass:"lottery_wrap"},[r("ul",{staticClass:"lottery_grid"},t._l(t.prizeData,(function(e,a){return r("li",{key:a,class:{active:t.current_index==a&&8!=a},attrs:{"data-index":a},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.luck_draw.apply(void 0,arguments)}}},[r("v-uni-view",{staticClass:"lottery-msg",class:{in_line:8!=a}},[8!=a?r("v-uni-image",{staticClass:"grid_img",attrs:{mode:"aspectFit",src:e.image,alt:""}}):t._e(),8!=a?r("v-uni-text",{staticClass:"name"},[t._v(t._s(8==a?"抽奖":e.name))]):r("v-uni-image",{staticClass:"lottery-click",attrs:{src:i("d921"),mode:""}})],1)],1)})),0)])],1)],1)},n=[]},"11f3":function(t,e,i){"use strict";var r=i("9a3a"),a=i.n(r);a.a},"188b":function(t,e){function i(t,e){this.timer=null,this.startIndex=t.startIndex-1||0,this.count=0,this.winingIndex=t.winingIndex||0,this.totalCount=t.totalCount||6,this.speed=t.speed||100,this.domData=t.domData,this.rollFn(),this.callback=e}i.prototype={rollFn:function(){var t=this;this.startIndex++,this.startIndex>=this.domData.length-1&&(this.startIndex=0,this.count++),this.count>=this.totalCount&&this.startIndex===this.winingIndex?("function"===typeof this.callback&&setTimeout((function(){t.callback(t.startIndex,t.count)}),400),clearInterval(this.timer)):(this.count>=this.totalCount-1&&(this.speed+=30),this.timer=setTimeout((function(){t.callback(t.startIndex,t.count),t.rollFn()}),this.speed))}},t.exports=i},"3e13":function(t,e,i){"use strict";var r=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=r(i("5530")),n=r(i("188b")),o={data:function(){return{current_index:-1,lotteryBtn:!0}},props:{prizeData:{type:Array,default:function(){return[]}}},onLoad:function(){},methods:{luck_draw:function(t){if(this.lotteryBtn){this.lotteryBtn=!1;var e=t.currentTarget.dataset.index,i=this;8==e&&this.$emit("get_winingIndex",(function(t){var e=t;new n.default((0,a.default)({domData:i.prizeData},e),(function(t,r){i.current_index=t,e.winingIndex==t&&e.totalCount==r&&(i.lotteryBtn=!0,i.$emit("luck_draw_finish",i.prizeData[t]))}))}))}}}};e.default=o},"486b":function(t,e,i){"use strict";var r=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.getLotteryData=n,e.startLottery=o,e.receiveLottery=d,e.getLotteryList=l;var a=r(i("ac7c"));function n(t){return a.default.get("v2/lottery/info/".concat(t))}function o(t){return a.default.post("v2/lottery",t)}function d(t){return a.default.post("v2/lottery/receive",t)}function l(t){return a.default.get("v2/lottery/record",t)}},"4bf5":function(t,e,i){var r=i("7263");"string"===typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);var a=i("4f06").default;a("44fa5dbe",r,!0,{sourceMap:!1,shadowMode:!1})},7263:function(t,e,i){var r=i("24fb");e=r(!1),e.push([t.i,".pictrueBox[data-v-3f1aaa8c]{width:%?130?%;height:%?120?%}\n\n/*返回主页按钮*/.home[data-v-3f1aaa8c]{position:fixed;color:#fff;text-align:center;z-index:999;right:%?15?%;display:flex}.home .homeCon[data-v-3f1aaa8c]{border-radius:%?50?%;opacity:0;height:0;color:var(--view-theme);width:0}.home .homeCon.on[data-v-3f1aaa8c]{opacity:1;-webkit-animation:bounceInRight .5s cubic-bezier(.215,.61,.355,1);animation:bounceInRight .5s cubic-bezier(.215,.61,.355,1);width:%?300?%;height:%?86?%;margin-bottom:%?20?%;display:flex;justify-content:center;align-items:center;background:var(--view-theme)!important}.home .homeCon .iconfont[data-v-3f1aaa8c]{font-size:%?48?%;color:#fff;display:inline-block;margin:0 auto}.home .pictrue[data-v-3f1aaa8c]{width:%?86?%;height:%?86?%;border-radius:50%;margin:0 auto;background-color:var(--view-theme)}.home .pictrue .image[data-v-3f1aaa8c]{width:100%;height:100%;border-radius:50%;-webkit-transform:rotate(90deg);transform:rotate(90deg);ms-transform:rotate(90deg);moz-transform:rotate(90deg);webkit-transform:rotate(90deg);o-transform:rotate(90deg)}",""]),t.exports=e},"77f3":function(t,e,i){"use strict";i.r(e);var r=i("0992"),a=i("8506");for(var n in a)"default"!==n&&function(t){i.d(e,t,(function(){return a[t]}))}(n);i("11f3");var o,d=i("f0c5"),l=Object(d["a"])(a["default"],r["b"],r["c"],!1,null,"384682df",null,!1,r["a"],o);e["default"]=l.exports},"7b16":function(t,e,i){"use strict";var r=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i("2f62"),n=r(i("db17")),o={name:"Home",props:{},mixins:[n.default],data:function(){return{top:"545"}},computed:(0,a.mapGetters)(["homeActive"]),methods:{setTouchMove:function(t){var e=this;t.touches[0].clientY<545&&t.touches[0].clientY>66&&(e.top=t.touches[0].clientY)},open:function(){this.homeActive?this.$store.commit("CLOSE_HOME"):this.$store.commit("OPEN_HOME")}},created:function(){},beforeDestroy:function(){this.$store.commit("CLOSE_HOME")}};e.default=o},8506:function(t,e,i){"use strict";i.r(e);var r=i("3e13"),a=i.n(r);for(var n in r)"default"!==n&&function(t){i.d(e,t,(function(){return r[t]}))}(n);e["default"]=a.a},"8d9c":function(t,e,i){var r=i("24fb");e=r(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* crmeb颜色变量 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */li[data-v-384682df]{list-style:none}ul[data-v-384682df],\r\nli[data-v-384682df]{margin:0;padding:0}.lottery_container[data-v-384682df]{width:100%;height:100%}.grid_wrap[data-v-384682df]{width:100%;height:100%;position:relative}.grid_wrap .lottery_wrap_border[data-v-384682df]{position:absolute;top:0;left:0;width:100%;height:100%}.grid_wrap .lottery_wrap_border ul[data-v-384682df]{position:absolute;display:flex;align-items:center;justify-content:center}.grid_wrap .lottery_wrap_border ul li[data-v-384682df]{border-radius:50%;width:%?17?%;height:%?17?%;background-color:#bce0e9}.grid_wrap .lottery_wrap_border ul li[data-v-384682df]:nth-child(even){width:%?24?%;height:%?24?%;background-color:#f5fbc8}.grid_wrap .lottery_wrap_border ul[data-v-384682df]:nth-child(odd){width:100%;height:%?35?%;left:0;right:0;flex-direction:row}.grid_wrap .lottery_wrap_border ul:nth-child(odd) li[data-v-384682df]{margin:0 %?10?%}.grid_wrap .lottery_wrap_border ul[data-v-384682df]:nth-child(even){width:%?35?%;height:100%;top:0;bottom:0;flex-direction:column}.grid_wrap .lottery_wrap_border ul:nth-child(even) li[data-v-384682df]{margin:%?10?% 0}.grid_wrap .lottery_wrap_border ul[data-v-384682df]:nth-child(3){bottom:0}.grid_wrap .lottery_wrap_border ul[data-v-384682df]:nth-child(4){right:0}.grid_wrap .lottery_wrap_border ul:nth-child(1) li[data-v-384682df]:nth-child(odd),\r\n.grid_wrap .lottery_wrap_border ul:nth-child(4) li[data-v-384682df]:nth-child(odd){-webkit-animation:blink_large-data-v-384682df 1s linear infinite;animation:blink_large-data-v-384682df 1s linear infinite}.grid_wrap .lottery_wrap_border ul:nth-child(1) li[data-v-384682df]:nth-child(even),\r\n.grid_wrap .lottery_wrap_border ul:nth-child(4) li[data-v-384682df]:nth-child(even){-webkit-animation:blink_small-data-v-384682df 1s linear infinite;animation:blink_small-data-v-384682df 1s linear infinite}.grid_wrap .lottery_wrap_border ul:nth-child(3) li[data-v-384682df]:nth-child(even),\r\n.grid_wrap .lottery_wrap_border ul:nth-child(2) li[data-v-384682df]:nth-child(even){width:%?17?%;height:%?17?%;background-color:#bce0e9;-webkit-animation:blink_large-data-v-384682df 1s linear infinite;animation:blink_large-data-v-384682df 1s linear infinite}.grid_wrap .lottery_wrap_border ul:nth-child(3) li[data-v-384682df]:nth-child(odd),\r\n.grid_wrap .lottery_wrap_border ul:nth-child(2) li[data-v-384682df]:nth-child(odd){width:%?24?%;height:%?24?%;background-color:#f5fbc8;-webkit-animation:blink_small-data-v-384682df 1s linear infinite;animation:blink_small-data-v-384682df 1s linear infinite}.grid_wrap .lottery_wrap[data-v-384682df]{width:100%;height:100%;font-size:%?14?%;position:absolute;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);z-index:1}.grid_wrap .lottery_wrap .lottery_grid[data-v-384682df]{width:100%;height:100%;position:relative}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]{width:30%;height:30%;display:flex;flex-direction:column;align-items:center;justify-content:center;float:left;position:absolute;border-radius:%?12?%;color:#e74435;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:%?20?%;margin:4px;padding:4px}.grid_wrap .lottery_wrap .lottery_grid li .in_line[data-v-384682df]{border:1px dashed #ff7f5f;border-radius:%?12?%;width:100%;height:100%;display:flex;flex-direction:column;justify-content:center;align-items:center;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;padding:0 %?4?%}.grid_wrap .lottery_wrap .lottery_grid li .grid_img[data-v-384682df]{width:50%;height:50%}.grid_wrap .lottery_wrap .lottery_grid .active[data-v-384682df]{background:#ffd18d!important;box-shadow:%?0?% %?8?% %?0?% %?0?% #ffd18d;color:#0e62ff}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(1){left:1%;top:1%;background:linear-gradient(0deg,#fff2f2,#fff)}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(2){left:34%;top:1%;background:linear-gradient(0deg,#fff2f2,#fff)}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(3){left:67%;top:1%;background:linear-gradient(0deg,#fff2f2,#fff)}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(4){left:67%;top:34%;background:linear-gradient(0deg,#fff2f2,#fff)}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(5){left:67%;top:67%;background:linear-gradient(0deg,#fff2f2,#fff)}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(6){left:34%;top:67%;background:linear-gradient(0deg,#fff2f2,#fff)}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(7){left:1%;top:67%;background:linear-gradient(0deg,#fff2f2,#fff)}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(8){left:1%;top:34%;background:linear-gradient(0deg,#fff2f2,#fff)}.grid_wrap .lottery_wrap .lottery_grid li[data-v-384682df]:nth-of-type(9){left:34%;top:34%;cursor:pointer;color:#fff;font-size:%?40?%;font-weight:bolder}@-webkit-keyframes blink_large-data-v-384682df{to{width:%?24?%;height:%?24?%;background-color:#f5fbc8}}@keyframes blink_large-data-v-384682df{to{width:%?24?%;height:%?24?%;background-color:#f5fbc8}}@-webkit-keyframes blink_small-data-v-384682df{to{width:%?17?%;height:%?17?%;background-color:#bce0e9}}@keyframes blink_small-data-v-384682df{to{width:%?17?%;height:%?17?%;background-color:#bce0e9}}.lottery-msg[data-v-384682df]{width:100%;height:100%;padding:0 %?4?%}.lottery-click[data-v-384682df]{width:100%;height:100%}',""]),t.exports=e},"9a3a":function(t,e,i){var r=i("8d9c");"string"===typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);var a=i("4f06").default;a("777ccf4c",r,!0,{sourceMap:!1,shadowMode:!1})},"9d3e":function(t,e,i){"use strict";var r=i("4bf5"),a=i.n(r);a.a},af77:function(t,e,i){"use strict";i.r(e);var r=i("7b16"),a=i.n(r);for(var n in r)"default"!==n&&function(t){i.d(e,t,(function(){return r[t]}))}(n);e["default"]=a.a},b886:function(t,e,i){"use strict";var r;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return n})),i.d(e,"a",(function(){return r}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{style:t.colorStyle},[i("v-uni-view",{staticStyle:{"touch-action":"none"}},[i("v-uni-view",{staticClass:"home",staticStyle:{position:"fixed"},style:{top:t.top+"px"},attrs:{id:"right-nav"},on:{touchmove:function(e){e.stopPropagation(),e.preventDefault(),arguments[0]=e=t.$handleEvent(e),t.setTouchMove.apply(void 0,arguments)}}},[t.homeActive?i("v-uni-view",{staticClass:"homeCon bg-color",class:!0===t.homeActive?"on":""},[i("v-uni-navigator",{staticClass:"iconfont icon-shouye-xianxing",attrs:{"hover-class":"none","open-type":"switchTab",url:"/pages/index/index"}}),i("v-uni-navigator",{staticClass:"iconfont icon-caigou-xianxing",attrs:{"hover-class":"none","open-type":"switchTab",url:"/pages/order_addcart/order_addcart"}}),i("v-uni-navigator",{staticClass:"iconfont icon-yonghu1",attrs:{"hover-class":"none","open-type":"switchTab",url:"/pages/user/index"}})],1):t._e(),i("v-uni-view",{staticClass:"pictrueBox",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.open.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"pictrue"},[i("v-uni-image",{staticClass:"image",attrs:{src:!0===t.homeActive?"/static/images/close.gif":"/static/images/open.gif"}})],1)],1)],1)],1)],1)},n=[]},cc0f:function(t,e,i){"use strict";i.r(e);var r=i("b886"),a=i("af77");for(var n in a)"default"!==n&&function(t){i.d(e,t,(function(){return a[t]}))}(n);i("9d3e");var o,d=i("f0c5"),l=Object(d["a"])(a["default"],r["b"],r["c"],!1,null,"3f1aaa8c",null,!1,r["a"],o);e["default"]=l.exports},d921:function(t,e,i){t.exports=i.p+"static/img/lottery-click.4e1ddd26.png"},dc84:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAABLCAYAAAAPgLXeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3ZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDoxMmU5NDE3MS1jZGVmLWZjNDgtOTgwOS00ODlmMTQ2YTIwNzgiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6Qjg4MEU5RkZGOTA5MTFFOEIwNkM4N0JBODI3MDBBRkMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6Qjg4MEU5RkVGOTA5MTFFOEIwNkM4N0JBODI3MDBBRkMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTggKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ODQyM2UwYzQtNTFiNi01MjRiLTk5ZGItM2I2YjMxMjMyZDQzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjEyZTk0MTcxLWNkZWYtZmM0OC05ODA5LTQ4OWYxNDZhMjA3OCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pj8UPccAAARySURBVHja7JlXaFRBFIb3JhHbGiM2LKCosYEYe0ui0YhGBCsi0YgVRAIi6IMN8UFEQWwvIgoaFPXJgL6oJDYUEdEIQWKJDQvGGCwxajRe/wPnysl1Nzv37uxuhDnwsbtTzvw7M3fKuVZAo9m2nYqP/iANBDn5M6gBjyzLqtPVlhWl0E74mA1yQSboEaHKU3ANXALn8Ee+BOJpEDwJnAU/bf/2FRSBjJj3OBoZgY89YKIrqwyUgLugAlSBWs5L5ZGgKTQKTOXv0orBeoxApe4ebgUOgAbRY2/BFtDbh7/BYDf4KPx9B5tBsi7R6aBMNFAD1oCWGny3B1tBnfBfArpE63g0eC+cnuQHUveI9galop0noK9fZ2P5ASKrB6ti/MAng+1C/GvP4lFhEKhmBzSM0+K4Yi0TzxL1fFfVikFQIXo6NxBnY/FyzierVDomKhUEEmRoe5vQsSVS4RxR+EgggYb2k8BFsVSmN1WwXDwY7QIJNmjoJRaI8+EKzRO9nR9oJsabkmPDQhW4xZkPqPcVHNK0OgT6+BAzHBwGQxQXixrWdirU8ufYCsXGb3L5V6CfB9FjwCeue0Kxzk4u/412W5mxXWSkKjpbINZbJfEu0bQ/TFBsa4Do2CUy4wYnFnsc8kWq4kOInuyxrfJGo4QvbcAvTlzrY75GFB+taPaxj+u/dBIyxDCM8fnkhxWvQzT7yRc6U5256liHKJatf8TrEu1cYoTOkZRQyD9qNay5UnwV+KJDNPvuKoTnpYjbeNTCcfWi8zp9LQKdOfk7mIm80ijdS31B2mick1eDps3uCfjhavClBr9SX3KS+CdBDVOFHu6LoDWopyRAN6YrXjapMCb11ZLwauc2DudtNYimDewbyAN0LP7Nt/xoxXcT32uc5Sr8Icb7Nt7oQfSySUVoQx4CO1FCmkhYqVO0TvGos4PrV8vE+14OPX42l2jFi9NrsUzcy4kfVOMlHLrwtLmEEN9Xsa1uol6hzBgnpstcRWfX/WwuLvHHFets4PJUr7s78zFnXlV0tpKHb5KP+UrHjNtghkLZFDpYsbYLoQqsFr2e04yubsuFrunhAptvuAA9rCnNQDTFFt+xptuR5p9jm5qB8KNCz/imClocObL5cpGdQNEFnmI89NTykdTmSO3ABIjO5vuvzeHAoGrFKRw3dNbbAXEUnSn2B/oc7NXBQjFU1PNZcRA9XwT563y3yeLrxZzfGIvVBj5bg/2ioz752R/cTnNFrNzm1ypZmgTTYjAHPBP+K8FQXT3SE1x2ve6j8/Us0MKHPwqJLAb3XD5PN4pUaeydJfy2zXbNf4qpL6VYYKhDGl1Q6KzPu/MZcYl27HHIXTEG87GQhzSUNXCQ8jl44Xol6Lb7vOmlxHOttXjpOijCY5GM/tQdsMvvG+W/EQWNf6QjPgYBenNAgaU9nLUOfAAPQYVlWZ91tGdxHCQmg6K7c6QlBf5TM8KNcCPcCDfCjXAj3Ag3wo1wI9wIN8KNcCPcCDfCjXAjPKb2R4ABAP1fyHzQ0oCyAAAAAElFTkSuQmCC"}}]);