require('../common/vendor.js');(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/points_mall/component/productWindow"],{"067b":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={props:{attr:{type:Object,default:function(){}},limitNum:{type:Number,value:0},isShow:{type:Number,value:0},iSbnt:{type:Number,value:0},iSplus:{type:Number,value:0},iScart:{type:Number,value:0},is_vip:{type:Number,value:0}},data:function(){return{}},mounted:function(){},methods:{getpreviewImage:function(){t.previewImage({urls:this.attr.productSelect.image.split(","),current:this.attr.productSelect.image})},goCat:function(){this.$emit("goCat")},bindCode:function(t){this.$emit("iptCartNum",this.attr.productSelect.cart_num)},closeAttr:function(){this.$emit("myevent")},CartNumDes:function(){this.$emit("ChangeCartNum",!1)},CartNumAdd:function(){this.$emit("ChangeCartNum",!0)},tapAttr:function(t,e){var n=this;n.$emit("attrVal",{indexw:t,indexn:e}),this.$set(this.attr.productAttr[t],"index",this.attr.productAttr[t].attr_values[e]);var u=n.getCheckedValue().join(",");n.$emit("ChangeAttr",u)},showImg:function(){this.$emit("getImg")},getCheckedValue:function(){for(var t=this.attr.productAttr,e=[],n=0;n<t.length;n++)for(var u=0;u<t[n].attr_values.length;u++)t[n].index===t[n].attr_values[u]&&e.push(t[n].attr_values[u]);return e}}};e.default=n}).call(this,n("543d")["default"])},1651:function(t,e,n){},"3c1a":function(t,e,n){"use strict";n.r(e);var u=n("067b"),a=n.n(u);for(var r in u)"default"!==r&&function(t){n.d(e,t,(function(){return u[t]}))}(r);e["default"]=a.a},"64aa":function(t,e,n){"use strict";n.r(e);var u=n("6a40"),a=n("3c1a");for(var r in a)"default"!==r&&function(t){n.d(e,t,(function(){return a[t]}))}(r);n("fc6b");var i,c=n("f0c5"),o=Object(c["a"])(a["default"],u["b"],u["c"],!1,null,"c2ecf99e",null,!1,u["a"],i);e["default"]=o.exports},"6a40":function(t,e,n){"use strict";var u;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return u}));var a=function(){var t=this,e=t.$createElement,n=(t._self._c,t.$t("积分")),u=t.isShow?t.$t("库存"):null,a=t.limitNum?t.$t("剩余"):null,r=t.$t("数量");t.$mp.data=Object.assign({},{$root:{m0:n,m1:u,m2:a,m3:r}})},r=[]},fc6b:function(t,e,n){"use strict";var u=n("1651"),a=n.n(u);a.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'pages/points_mall/component/productWindow-create-component',
    {
        'pages/points_mall/component/productWindow-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("64aa"))
        })
    },
    [['pages/points_mall/component/productWindow-create-component']]
]);
