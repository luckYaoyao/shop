require('../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods/goods_list/index"],{"0c42":function(t,e,i){"use strict";(function(t){i("4a5c");o(i("66fd"));var e=o(i("57a2"));function o(t){return t&&t.__esModule?t:{default:t}}wx.__webpack_require_UNI_MP_PLUGIN__=i,t(e.default)}).call(this,i("543d")["createPage"])},2644:function(t,e,i){"use strict";i.r(e);var o=i("5816"),s=i.n(o);for(var c in o)"default"!==c&&function(t){i.d(e,t,(function(){return o[t]}))}(c);e["default"]=s.a},"57a2":function(t,e,i){"use strict";i.r(e);var o=i("bbbc"),s=i("2644");for(var c in s)"default"!==c&&function(t){i.d(e,t,(function(){return s[t]}))}(c);i("8929");var n,r=i("f0c5"),a=Object(r["a"])(s["default"],o["b"],o["c"],!1,null,"343320c8",null,!1,o["a"],n);e["default"]=a.exports},5816:function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=i("62c7"),s=i("26cb"),c=i("1df3"),n=i("d458"),r=a(i("a672"));function a(t){return t&&t.__esModule?t:{default:t}}var h=function(){Promise.all([i.e("common/vendor"),i.e("components/home/index")]).then(function(){return resolve(i("2877"))}.bind(null,i)).catch(i.oe)},d=function(){Promise.all([i.e("common/vendor"),i.e("components/recommend/index")]).then(function(){return resolve(i("b272"))}.bind(null,i)).catch(i.oe)},u={computed:(0,s.mapGetters)(["uid"]),components:{recommend:d,home:h},mixins:[r.default],data:function(){return{imgHost:n.HTTP_REQUEST_URL,productList:[],is_switch:!0,where:{sid:0,keyword:"",priceOrder:"",salesOrder:"",news:0,page:1,limit:20,cid:0},price:0,stock:0,nows:!1,loadend:!1,loading:!1,loadTitle:this.$t("加载更多"),title:"",hostProduct:[],hotPage:1,hotLimit:10,hotScroll:!1}},onLoad:function(t){this.where.cid=t.cid||0,this.where.coupon_category_id=t.coupon_category_id||"",this.$set(this.where,"sid",t.sid||0),this.title=t.title||"",this.$set(this.where,"keyword",t.searchValue||""),this.$set(this.where,"productId",t.productId||""),this.get_product_list()},methods:{godDetail:function(e){(0,c.goShopDetail)(e,this.uid).then((function(i){t.navigateTo({url:"/pages/goods_details/index?id=".concat(e.id)})}))},Changswitch:function(){var t=this;t.is_switch=!t.is_switch},searchSubmit:function(t){var e=this;e.$set(e.where,"keyword",t.detail.value),e.loadend=!1,e.$set(e.where,"page",1),this.get_product_list(!0)},get_host_product:function(){var t=this;t.hotScroll||(0,o.getProductHot)(t.hotPage,t.hotLimit).then((function(e){t.hotPage++,t.hotScroll=e.data.length<t.hotLimit,t.hostProduct=t.hostProduct.concat(e.data)}))},set_where:function(e){switch(e){case 1:return t.navigateBack({delta:1});case 2:0==this.price?this.price=1:1==this.price?this.price=2:2==this.price&&(this.price=0),this.stock=0;break;case 3:0==this.stock?this.stock=1:1==this.stock?this.stock=2:2==this.stock&&(this.stock=0),this.price=0;break;case 4:this.nows=!this.nows;break}this.loadend=!1,this.$set(this.where,"page",1),this.get_product_list(!0)},setWhere:function(){0==this.price?this.where.priceOrder="":1==this.price?this.where.priceOrder="asc":2==this.price&&(this.where.priceOrder="desc"),0==this.stock?this.where.salesOrder="":1==this.stock?this.where.salesOrder="asc":2==this.stock&&(this.where.salesOrder="desc"),this.where.news=this.nows?1:0},get_product_list:function(t){var e=this,i=this;i.setWhere(),i.loadend||i.loading||(!0===t&&i.$set(i,"productList",[]),i.loading=!0,i.loadTitle="",(0,o.getProductslist)(i.where).then((function(t){var o=t.data,s=i.$util.SplitArray(o,i.productList),c=o.length<i.where.limit;i.loadend=c,i.loading=!1,i.loadTitle=c?i.$t("没有更多内容啦~"):i.$t("加载更多"),i.$set(i,"productList",s),i.$set(i.where,"page",i.where.page+1),i.productList.length||e.get_host_product()})).catch((function(t){i.loading=!1,i.loadTitle=i.$t("加载更多")})))}},onPullDownRefresh:function(){},onReachBottom:function(){this.productList.length>0?this.get_product_list():this.get_host_product()},onPageScroll:function(e){t.$emit("scroll")}};e.default=u}).call(this,i("543d")["default"])},8929:function(t,e,i){"use strict";var o=i("c89a"),s=i.n(o);s.a},bbbc:function(t,e,i){"use strict";var o;i.d(e,"b",(function(){return s})),i.d(e,"c",(function(){return c})),i.d(e,"a",(function(){return o}));var s=function(){var t=this,e=t.$createElement,i=(t._self._c,t.$t("搜索商品名称")),o=t.title?t.$t(t.title):null,s=t.title?null:t.$t("默认"),c=t.$t("价格"),n=t.$t("销量"),r=t.$t("新品"),a=t.$t("￥"),h=t.$t("已售"),d=t.__map(t.productList,(function(e,i){var o=t.__get_orig(e),s=e.activity&&"1"===e.activity.type?t.$t("秒杀"):null,c=e.activity&&"2"===e.activity.type?t.$t("砍价"):null,n=e.activity&&"3"===e.activity.type?t.$t("拼团"):null,r=e.vip_price&&e.vip_price>0?t.$t("￥"):null,a=t.$t(e.unit_name)||t.$t("件");return{$orig:o,m6:s,m7:c,m8:n,m10:r,m12:a}}));t.$mp.data=Object.assign({},{$root:{m0:i,m1:o,m2:s,m3:c,m4:n,m5:r,m9:a,m11:h,l0:d}})},c=[]},c89a:function(t,e,i){}},[["0c42","common/runtime","common/vendor"]]]);