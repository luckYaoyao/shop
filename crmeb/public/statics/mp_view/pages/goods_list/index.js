(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods_list/index"],{"425b":function(t,e,i){"use strict";var s;i.d(e,"b",(function(){return o})),i.d(e,"c",(function(){return n})),i.d(e,"a",(function(){return s}));var o=function(){var t=this,e=t.$createElement;t._self._c},n=[]},"5b76":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var s=i("b2cd"),o=i("26cb"),n=i("376f"),r=c(i("5411"));function c(t){return t&&t.__esModule?t:{default:t}}var d=function(){Promise.all([i.e("common/vendor"),i.e("components/home/index")]).then(function(){return resolve(i("b60d"))}.bind(null,i)).catch(i.oe)},h=function(){Promise.all([i.e("common/vendor"),i.e("components/recommend/index")]).then(function(){return resolve(i("1024"))}.bind(null,i)).catch(i.oe)},a={computed:(0,o.mapGetters)(["uid"]),components:{recommend:h,home:d},mixins:[r.default],data:function(){return{productList:[],is_switch:!0,where:{sid:0,keyword:"",priceOrder:"",salesOrder:"",news:0,page:1,limit:20,cid:0},price:0,stock:0,nows:!1,loadend:!1,loading:!1,loadTitle:"加载更多",title:"",hostProduct:[],hotPage:1,hotLimit:10,hotScroll:!1}},onLoad:function(t){this.where.cid=t.cid||0,this.$set(this.where,"sid",t.sid||0),this.title=t.title||"",this.$set(this.where,"keyword",t.searchValue||""),this.$set(this.where,"productId",t.productId||""),this.get_product_list()},methods:{godDetail:function(e){(0,n.goShopDetail)(e,this.uid).then((function(i){t.navigateTo({url:"/pages/goods_details/index?id=".concat(e.id)})}))},Changswitch:function(){var t=this;t.is_switch=!t.is_switch},searchSubmit:function(t){var e=this;e.$set(e.where,"keyword",t.detail.value),e.loadend=!1,e.$set(e.where,"page",1),this.get_product_list(!0)},get_host_product:function(){var t=this;t.hotScroll||(0,s.getProductHot)(t.hotPage,t.hotLimit).then((function(e){t.hotPage++,t.hotScroll=e.data.length<t.hotLimit,t.hostProduct=t.hostProduct.concat(e.data)}))},set_where:function(e){switch(e){case 1:return t.navigateBack({delta:1});case 2:0==this.price?this.price=1:1==this.price?this.price=2:2==this.price&&(this.price=0),this.stock=0;break;case 3:0==this.stock?this.stock=1:1==this.stock?this.stock=2:2==this.stock&&(this.stock=0),this.price=0;break;case 4:this.nows=!this.nows;break}this.loadend=!1,this.$set(this.where,"page",1),this.get_product_list(!0)},setWhere:function(){0==this.price?this.where.priceOrder="":1==this.price?this.where.priceOrder="asc":2==this.price&&(this.where.priceOrder="desc"),0==this.stock?this.where.salesOrder="":1==this.stock?this.where.salesOrder="asc":2==this.stock&&(this.where.salesOrder="desc"),this.where.news=this.nows?1:0},get_product_list:function(t){var e=this,i=this;i.setWhere(),i.loadend||i.loading||(!0===t&&i.$set(i,"productList",[]),i.loading=!0,i.loadTitle="",(0,s.getProductslist)(i.where).then((function(t){var s=t.data,o=i.$util.SplitArray(s,i.productList),n=s.length<i.where.limit;i.loadend=n,i.loading=!1,i.loadTitle=n?"已全部加载":"加载更多",i.$set(i,"productList",o),i.$set(i.where,"page",i.where.page+1),i.productList.length||e.get_host_product()})).catch((function(t){i.loading=!1,i.loadTitle="加载更多"})))}},onPullDownRefresh:function(){},onReachBottom:function(){this.productList.length>0?this.get_product_list():this.get_host_product()}};e.default=a}).call(this,i("543d")["default"])},9399:function(t,e,i){"use strict";i.r(e);var s=i("425b"),o=i("c2d9");for(var n in o)"default"!==n&&function(t){i.d(e,t,(function(){return o[t]}))}(n);i("c77e");var r,c=i("f0c5"),d=Object(c["a"])(o["default"],s["b"],s["c"],!1,null,"40653de5",null,!1,s["a"],r);e["default"]=d.exports},ad76:function(t,e,i){"use strict";(function(t){i("ebf5");s(i("66fd"));var e=s(i("9399"));function s(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=i,t(e.default)}).call(this,i("543d")["createPage"])},c2d9:function(t,e,i){"use strict";i.r(e);var s=i("5b76"),o=i.n(s);for(var n in s)"default"!==n&&function(t){i.d(e,t,(function(){return s[t]}))}(n);e["default"]=o.a},c77e:function(t,e,i){"use strict";var s=i("f896"),o=i.n(s);o.a},f896:function(t,e,i){}},[["ad76","common/runtime","common/vendor"]]]);