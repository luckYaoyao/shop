(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index/diy/components/promotionList"],{"024a":function(t,o,i){"use strict";i.r(o);var n=i("e976"),e=i("3108");for(var a in e)["default"].indexOf(a)<0&&function(t){i.d(o,t,(function(){return e[t]}))}(a);i("cd0c");var r=i("f0c5"),l=Object(r["a"])(e["default"],n["b"],n["c"],!1,null,null,null,!1,n["a"],void 0);o["default"]=l.exports},"276e0":function(t,o,i){"use strict";Object.defineProperty(o,"__esModule",{value:!0}),o.default=void 0;var n={name:"promotionList",props:{dataConfig:{type:Object,default:function(){}},tempArr:{type:Array,default:[]},iSshowH:{type:Boolean,default:!1},isSortType:{type:String|Number,default:0}},data:function(){return{ProductNavindex:0,explosiveMoney:this.dataConfig.tabConfig.list,numConfig:this.dataConfig.numConfig.val,mbConfig:this.dataConfig.mbConfig.val,themeColor:this.dataConfig.themeColor.color[0].item,titleShow:this.dataConfig.titleShow.val,opriceShow:this.dataConfig.opriceShow.val,priceShow:this.dataConfig.priceShow.val,couponShow:this.dataConfig.couponShow.val,titleConfig:this.dataConfig.titleConfig.type,fontColor:this.dataConfig.fontColor.color[0].item,labelColor:this.dataConfig.labelColor.color[0].item}},created:function(){},methods:{ProductNavTab:function(t,o){this.ProductNavindex=o,this.$emit("changeTab",t)},goDetail:function(t){this.$emit("detail",t)}}};o.default=n},3108:function(t,o,i){"use strict";i.r(o);var n=i("276e0"),e=i.n(n);for(var a in n)["default"].indexOf(a)<0&&function(t){i.d(o,t,(function(){return n[t]}))}(a);o["default"]=e.a},"70fa":function(t,o,i){},cd0c:function(t,o,i){"use strict";var n=i("70fa"),e=i.n(n);e.a},e976:function(t,o,i){"use strict";i.d(o,"b",(function(){return e})),i.d(o,"c",(function(){return a})),i.d(o,"a",(function(){return n}));var n={easyLoadimage:function(){return Promise.all([i.e("common/vendor"),i.e("components/easy-loadimage/easy-loadimage")]).then(i.bind(null,"cad3"))}},e=function(){var t=this,o=t.$createElement,i=(t._self._c,t.__map(t.explosiveMoney,(function(o,i){var n=t.__get_orig(o),e=t.$t(o.chiild[0].val),a=t.$t(o.chiild[1].val);return{$orig:n,m0:e,m1:a}}))),n=t.tempArr.length,e=t.opriceShow?t.$t("￥"):null,a=t.priceShow?t.$t("￥"):null,r=t.__map(t.tempArr,(function(o,i){var n=t.__get_orig(o),e=o.activity&&"1"===o.activity.type?t.$t("秒杀"):null,a=o.activity&&"2"===o.activity.type?t.$t("砍价"):null,r=o.activity&&"3"===o.activity.type?t.$t("拼团"):null,l=o.checkCoupon&&t.couponShow?t.$t("券"):null;return{$orig:n,m2:e,m3:a,m4:r,m7:l}}));t.$mp.data=Object.assign({},{$root:{l0:i,g0:n,m5:e,m6:a,l1:r}})},a=[]}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/index/diy/components/promotionList-create-component',
    {
        'pages/index/diy/components/promotionList-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("024a"))
        })
    },
    [['pages/index/diy/components/promotionList-create-component']]
]);
