(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/goodList/index"],{"24da":function(t,i,n){"use strict";n.r(i);var e=n("2663"),u=n("f952");for(var a in u)"default"!==a&&function(t){n.d(i,t,(function(){return u[t]}))}(a);n("8189");var r,c=n("f0c5"),o=Object(c["a"])(u["default"],e["b"],e["c"],!1,null,"fa1004b4",null,!1,e["a"],r);i["default"]=o.exports},2663:function(t,i,n){"use strict";var e;n.d(i,"b",(function(){return u})),n.d(i,"c",(function(){return a})),n.d(i,"a",(function(){return e}));var u=function(){var t=this,i=t.$createElement,n=(t._self._c,t.$t("￥")),e=t.__map(t.bastList,(function(i,n){var e=t.__get_orig(i),u=i.activity&&"1"===i.activity.type?t.$t("秒杀"):null,a=i.activity&&"2"===i.activity.type?t.$t("砍价"):null,r=i.activity&&"3"===i.activity.type?t.$t("拼团"):null,c=i.is_vip&&i.vip_price&&i.vip_price>0?t.$t("￥"):null,o=i.is_vip&&i.vip_price&&i.vip_price>0?t.$t("已售"):null,p=i.is_vip&&i.vip_price&&i.vip_price>0?t.$t(i.unit_name):null,l=i.is_vip&&i.vip_price&&i.vip_price>0?null:t.$t("已售"),f=i.is_vip&&i.vip_price&&i.vip_price>0?null:t.$t(i.unit_name);return{$orig:e,m0:u,m1:a,m2:r,m4:c,m5:o,m6:p,m7:l,m8:f}}));t.$mp.data=Object.assign({},{$root:{m3:n,l0:e}})},a=[]},8189:function(t,i,n){"use strict";var e=n("ff50"),u=n.n(e);u.a},d0e0:function(t,i,n){"use strict";(function(t){Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var e=n("26cb"),u=n("1df3"),a={computed:(0,e.mapGetters)(["uid"]),props:{status:{type:Number,default:0},bastList:{type:Array,default:function(){return[]}}},data:function(){return{}},methods:{goDetail:function(i){var n=this;(0,u.goPage)().then((function(e){(0,u.goShopDetail)(i,n.uid).then((function(n){t.navigateTo({url:"/pages/goods_details/index?id=".concat(i.id)})}))}))}}};i.default=a}).call(this,n("543d")["default"])},f952:function(t,i,n){"use strict";n.r(i);var e=n("d0e0"),u=n.n(e);for(var a in e)"default"!==a&&function(t){n.d(i,t,(function(){return e[t]}))}(a);i["default"]=u.a},ff50:function(t,i,n){}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/goodList/index-create-component',
    {
        'components/goodList/index-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("24da"))
        })
    },
    [['components/goodList/index-create-component']]
]);
