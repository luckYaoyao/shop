(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/users/user_distribution_level/index"],{"0306":function(t,e,n){"use strict";var o=n("33fb"),i=n.n(o);i.a},"081c":function(t,e,n){"use strict";n.r(e);var o=n("360e"),i=n.n(o);for(var r in o)"default"!==r&&function(t){n.d(e,t,(function(){return o[t]}))}(r);e["default"]=i.a},"33fb":function(t,e,n){},"360e":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n("3023"),i=n("a8b0"),r=n("12f4"),a=n("26cb"),u=l(n("cd99"));function l(t){return t&&t.__esModule?t:{default:t}}var s=function(){Promise.all([n.e("common/vendor"),n.e("components/recommend/index")]).then(function(){return resolve(n("fe46"))}.bind(null,n)).catch(n.oe)},c={components:{recommend:s},mixins:[u.default],data:function(){return{reach_count:0,distributionLevel:[],swiperIndex:0,growthValue:!0,task:[],illustrate:"",level_id:0,hostProduct:[],grade:0,hotScroll:!1,hotPage:1,hotLimit:10,level_title:"",level_discount:"",levelInfo:{},userInfo:{},taskInfo:{},taskNum:0}},computed:(0,a.mapGetters)(["isLogin"]),watch:{distributionLevel:function(){var t=this;t.distributionLevel.length>0&&t.distributionLevel.forEach((function(e,n){!1===e.is_clear&&(t.activeIndex=n,t.grade=e.grade)}))},isLogin:{handler:function(t,e){t&&this.get_host_product()},deep:!0}},onLoad:function(){this.isLogin?this.agentLevelList():(0,r.toLogin)(),this.get_host_product()},methods:{agentLevelList:function(){var t=this;(0,o.agentLevelList)().then((function(e){var n=e.data,o=n.level_info,i=n.level_list,r=n.task,a=n.user;t.levelInfo=o,t.distributionLevel=i,t.userInfo=a,t.taskInfo=r,t.levelInfo.exp=parseFloat(t.levelInfo.exp),t.levelInfo.rate=Math.floor(t.levelInfo.exp/t.levelInfo.exp_num*100),t.levelInfo.rate>100&&(t.levelInfo.rate=100);var u=i.findIndex((function(t,e){return t.id===a.agent_level}));-1!==u&&(t.swiperIndex=-1===u?0:u),t.level_id=t.distributionLevel[-1===u?0:u].id||0,t.getTask()}))},get_host_product:function(){var t=this;t.hotScroll||(0,i.getProductHot)(t.hotPage,t.hotLimit).then((function(e){t.hotPage++,t.hotScroll=e.data.length<t.hotLimit,t.hostProduct=t.hostProduct.concat(e.data)}))},swiperChange:function(t){var e=t.detail.current;this.swiperIndex=e,this.level_id=this.distributionLevel[e].id||0,this.level_title=this.distributionLevel[e].name||"",this.level_discount=this.distributionLevel[e].discount||"",this.getTask()},growthValueClose:function(){this.growthValue=!0},opHelp:function(t){this.growthValue=!1,this.illustrate=this.task[t].desc},getTask:function(){var t=this;t.taskNum=0,(0,o.agentLevelTaskList)(t.level_id).then((function(e){t.task=e.data;for(var n=0;n<t.task.length;n++)t.task[n].finish&&(t.taskNum+=1)}))}},onReachBottom:function(){that.hotScroll||this.get_host_product()}};e.default=c},4140:function(t,e,n){"use strict";var o;n.d(e,"b",(function(){return i})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return o}));var i=function(){var t=this,e=t.$createElement,n=(t._self._c,t.__map(t.task,(function(e,n){var o=t.__get_orig(e),i=Math.floor(e.new_number/e.number>1?100:e.new_number/e.number*100);return{$orig:o,g0:i}})));t._isMounted||(t.e0=function(e){t.growthValue=!0}),t.$mp.data=Object.assign({},{$root:{l0:n}})},r=[]},bf4c:function(t,e,n){"use strict";(function(t){n("cdba");o(n("66fd"));var e=o(n("cce4"));function o(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])},cce4:function(t,e,n){"use strict";n.r(e);var o=n("4140"),i=n("081c");for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);n("0306");var a,u=n("f0c5"),l=Object(u["a"])(i["default"],o["b"],o["c"],!1,null,"a37a8afa",null,!1,o["a"],a);e["default"]=l.exports}},[["bf4c","common/runtime","common/vendor"]]]);