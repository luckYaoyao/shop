(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/goods_cate/goods_cate"],{"5bd7":function(e,t,n){"use strict";n.d(t,"b",(function(){return a})),n.d(t,"c",(function(){return o})),n.d(t,"a",(function(){}));var a=function(){var e=this.$createElement;this._self._c},o=[]},8296:function(e,t,n){"use strict";n.r(t);var a=n("f2e5"),o=n.n(a);for(var s in a)["default"].indexOf(s)<0&&function(e){n.d(t,e,(function(){return a[e]}))}(s);t["default"]=o.a},"94a7":function(e,t,n){},bc85:function(e,t,n){"use strict";n.r(t);var a=n("5bd7"),o=n("8296");for(var s in o)["default"].indexOf(s)<0&&function(e){n.d(t,e,(function(){return o[e]}))}(s);n("ea4b");var c=n("f0c5"),r=Object(c["a"])(o["default"],a["b"],a["c"],!1,null,"ef55cc60",null,!1,a["a"],void 0);t["default"]=r.exports},e5c9:function(e,t,n){"use strict";(function(e,t){var a=n("4ea4");n("4789");a(n("66fd"));var o=a(n("bc85"));e.__webpack_require_UNI_MP_PLUGIN__=n,t(o.default)}).call(this,n("bc2e")["default"],n("543d")["createPage"])},ea4b:function(e,t,n){"use strict";var a=n("94a7"),o=n.n(a);o.a},f2e5:function(e,t,n){"use strict";(function(e){var a=n("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var o=a(n("19b6")),s=n("5743"),c=n("26cb"),r=n("4e39"),i={computed:(0,c.mapGetters)(["isLogin","uid"]),components:{goodsCate1:function(){Promise.all([n.e("common/vendor"),n.e("pages/goods_cate/goods_cate1")]).then(function(){return resolve(n("87a4"))}.bind(null,n)).catch(n.oe)},goodsCate2:function(){Promise.all([n.e("common/vendor"),n.e("pages/goods_cate/goods_cate2")]).then(function(){return resolve(n("b69c"))}.bind(null,n)).catch(n.oe)},goodsCate3:function(){Promise.all([n.e("common/vendor"),n.e("pages/goods_cate/goods_cate3")]).then(function(){return resolve(n("ada7"))}.bind(null,n)).catch(n.oe)},tabBar:function(){Promise.all([n.e("common/vendor"),n.e("pages/index/visualization/components/tabBar")]).then(function(){return resolve(n("8632"))}.bind(null,n)).catch(n.oe)}},mixins:[o.default],data:function(){return{category:"",is_diy:e.getStorageSync("is_diy"),status:0,version:"",isNew:!1}},onLoad:function(){},onReady:function(){},onShow:function(){this.getCategoryVersion()},methods:{getCategoryVersion:function(){var t=this;e.$emit("uploadFooter"),(0,r.getCategoryVersion)().then((function(n){e.getStorageSync("CAT_VERSION")&&n.data.version==e.getStorageSync("CAT_VERSION")||(t.isNew=!t.isNew,e.setStorageSync("CAT_VERSION",n.data.version)),t.classStyle()}))},jumpIndex:function(){},classStyle:function(){var t=this;(0,s.colorChange)("category").then((function(n){var a=n.data.status;t.category=a,e.setStorageSync("is_diy",n.data.is_diy),t.status=n.data.status,t.$nextTick((function(o){2==t.status||3==t.status?e.hideTabBar():1==t.status&&(t.$refs.classOne.is_diy=n.data.is_diy,t.$refs.classOne.getNav()),2==a&&t.isLogin&&(t.$refs.classTwo.getCartNum(),t.$refs.classTwo.getCartList(1)),3==a&&t.isLogin&&(t.$refs.classThree.getCartNum(),t.$refs.classThree.getCartList(1),t.$refs.classThree.tempArr=[],t.$refs.classThree.loadend=!1,t.$refs.classThree.page=1),2==a||3==a?e.hideTabBar():(t.is_diy||e.hideTabBar(),t.$refs.classOne.getNav())}))}))}},onReachBottom:function(){2==this.category&&this.$refs.classTwo.productslist(),3==this.category&&this.$refs.classThree.productslist()}};t.default=i}).call(this,n("543d")["default"])}},[["e5c9","common/runtime","common/vendor"]]]);