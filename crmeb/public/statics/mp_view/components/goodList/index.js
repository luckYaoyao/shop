(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/goodList/index"],{"1d67":function(t,i,e){"use strict";var n=e("ede0"),a=e.n(n);a.a},"6dda":function(t,i,e){"use strict";e.d(i,"b",(function(){return n})),e.d(i,"c",(function(){return a})),e.d(i,"a",(function(){}));var n=function(){var t=this,i=t.$createElement,e=(t._self._c,t.$t("￥")),n=t.__map(t.bastList,(function(i,e){var n=t.__get_orig(i),a=i.activity&&"1"===i.activity.type?t.$t("秒杀"):null,u=i.activity&&"2"===i.activity.type?t.$t("砍价"):null,c=i.activity&&"3"===i.activity.type?t.$t("拼团"):null,r=i.is_vip&&i.vip_price&&i.vip_price>0?t.$t("￥"):null,o=i.is_vip&&i.vip_price&&i.vip_price>0?t.$t("已售"):null,d=i.is_vip&&i.vip_price&&i.vip_price>0?t.$t(i.unit_name):null,p=i.is_vip&&i.vip_price&&i.vip_price>0?null:t.$t("已售"),l=i.is_vip&&i.vip_price&&i.vip_price>0?null:t.$t(i.unit_name);return{$orig:n,m0:a,m1:u,m2:c,m4:r,m5:o,m6:d,m7:p,m8:l}}));t.$mp.data=Object.assign({},{$root:{m3:e,l0:n}})},a=[]},"6ea4":function(t,i,e){"use strict";(function(t){Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var n=e("26cb"),a=e("6557"),u={computed:(0,n.mapGetters)(["uid"]),props:{status:{type:Number,default:0},bastList:{type:Array,default:function(){return[]}}},data:function(){return{}},methods:{goDetail:function(i){var e=this;(0,a.goPage)().then((function(n){(0,a.goShopDetail)(i,e.uid).then((function(e){t.navigateTo({url:"/pages/goods_details/index?id=".concat(i.id)})}))}))}}};i.default=u}).call(this,e("543d")["default"])},d47f:function(t,i,e){"use strict";e.r(i);var n=e("6dda"),a=e("ed1d");for(var u in a)["default"].indexOf(u)<0&&function(t){e.d(i,t,(function(){return a[t]}))}(u);e("1d67");var c=e("f0c5"),r=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"6786e958",null,!1,n["a"],void 0);i["default"]=r.exports},ed1d:function(t,i,e){"use strict";e.r(i);var n=e("6ea4"),a=e.n(n);for(var u in n)["default"].indexOf(u)<0&&function(t){e.d(i,t,(function(){return n[t]}))}(u);i["default"]=a.a},ede0:function(t,i,e){}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/goodList/index-create-component',
    {
        'components/goodList/index-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("d47f"))
        })
    },
    [['components/goodList/index-create-component']]
]);
