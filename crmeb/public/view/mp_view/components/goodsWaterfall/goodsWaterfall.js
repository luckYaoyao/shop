(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/goodsWaterfall/goodsWaterfall"],{"47b0":function(t,a,i){"use strict";i.r(a);var s=i("b5ae"),o=i("b6d8");for(var e in o)"default"!==e&&function(t){i.d(a,t,(function(){return o[t]}))}(e);i("4a79");var n,d=i("f0c5"),h=Object(d["a"])(o["default"],s["b"],s["c"],!1,null,"06b5baac",null,!1,s["a"],n);a["default"]=h.exports},"4a79":function(t,a,i){"use strict";var s=i("7937"),o=i.n(s);o.a},7937:function(t,a,i){},a74a:function(t,a,i){"use strict";(function(t){Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var s=i("7f94"),o={name:"goodsWaterfall",props:{dataLists:{default:[]}},data:function(){return{lists1:[],lists2:[],list1Height:0,list2Height:0,tmp_data:[],loaded:[],loadErr:[],showLoad:!1}},methods:{goGoodsDetail:function(a){var i=this;(0,s.goPage)().then((function(o){(0,s.goShopDetail)(a,i.uid).then((function(i){t.navigateTo({url:"/pages/goods_details/index?id=".concat(a.id)})}))}))},refresData:function(){if(this.hideLoadFlag(),!(this.loaded.length+this.loadErr.length<this.tmp_data.length)){var t=this;this.tmp_data.length||(t.list1Height=0,t.list2Height=0,t.lists1=[],t.lists2=[]),this.tmp_data.map((function(a,i){t.list1Height>t.list2Height?(t.list2Height+=a.img_height,t.lists2.push(a)):(t.list1Height+=a.img_height,t.lists1.push(a))}))}},imgLoad:function(t){this.loaded.push(t.target.id),this.tmp_data[t.target.id]["img_width"]=t.detail.width,this.tmp_data[t.target.id]["img_height"]=t.detail.height},imgError:function(t){this.loadErr.push(t.target.id)},showLoadFlag:function(){this.showLoad||(this.showLoad=!0)},hideLoadFlag:function(){this.showLoad&&(t.hideLoading(),this.showLoad=!1)}},mounted:function(){var t=this;t.tmp_data=t.dataLists,t.showLoadFlag()},watch:{dataLists:function(){this.loaded=[],this.loadErr=[],this.tmp_data=this.dataLists,this.showLoadFlag()},loaded:function(){this.refresData()},loadErr:function(){this.refresData()}}};a.default=o}).call(this,i("543d")["default"])},b5ae:function(t,a,i){"use strict";var s;i.d(a,"b",(function(){return o})),i.d(a,"c",(function(){return e})),i.d(a,"a",(function(){return s}));var o=function(){var t=this,a=t.$createElement;t._self._c},e=[]},b6d8:function(t,a,i){"use strict";i.r(a);var s=i("a74a"),o=i.n(s);for(var e in s)"default"!==e&&function(t){i.d(a,t,(function(){return s[t]}))}(e);a["default"]=o.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/goodsWaterfall/goodsWaterfall-create-component',
    {
        'components/goodsWaterfall/goodsWaterfall-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("47b0"))
        })
    },
    [['components/goodsWaterfall/goodsWaterfall-create-component']]
]);
