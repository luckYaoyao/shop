(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-24bcd400"],{"5da5":function(e,t,s){"use strict";s("d81d"),s("4de4"),s("d3b7");var i=s("f3f3"),n=s("7686"),o=s("2f62");n={name:"navMenuVertical",components:{SubItem:n.a},props:{menuList:{type:Array,default:function(){return[]}}},data:function(){return{defaultActive:this.$route.path,onRoutes:""}},computed:Object(i.a)(Object(i.a)({},Object(o.d)("menu",["activePath"])),{},{getThemeConfig:function(){return this.$store.state.themeConfig.themeConfig},setIsCollapse:function(){return!(document.body.clientWidth<1e3)&&this.$store.state.themeConfig.themeConfig.isCollapse}}),watch:{$route:{handler:function(e){this.defaultActive=e.path,document.body.clientWidth<1e3&&(this.$store.state.themeConfig.themeConfig.isCollapse=!1)},deep:!0}},created:function(){}},s("603b"),i=s("2877"),o={name:"layoutAside",components:{Vertical:Object(i.a)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("el-menu",{attrs:{router:"","background-color":"transparent","default-active":e.activePath||e.defaultActive,collapse:e.setIsCollapse,"unique-opened":e.getThemeConfig.isUniqueOpened,"collapse-transition":!0}},[e._l(e.menuList,(function(t){return[!t.is_show_path&&t.children&&0<t.children.length?s("el-submenu",{key:t.path,attrs:{index:t.path}},[s("template",{slot:"title"},[s("Icon",{class:["defaults","classic"].includes(e.getThemeConfig.layout)&&e.getThemeConfig.isCollapse?"center":"mr10",attrs:{type:t.icon||""}}),s("span",[e._v(e._s(e.$t(t.title))+" ")])],1),s("SubItem",{attrs:{chil:t.children}})],2):t.is_show_path?e._e():[s("el-menu-item",{key:t.path,attrs:{index:t.path}},[s("Icon",{class:["defaults","classic"].includes(e.getThemeConfig.layout)&&e.getThemeConfig.isCollapse?"center":"mr10",attrs:{type:t.icon||""}}),!t.isLink||t.isLink&&t.isIframe?s("template",{slot:"title"},[s("span",[e._v(e._s(e.$t(t.title)))])]):s("template",{slot:"title"},[s("a",{attrs:{href:t.isLink,target:"_blank"}},[e._v(e._s(e.$t(t.title)))])])],2)]]}))],2)],1)}),[],!1,null,"3d16b9f1",null).exports,Logo:s("6376").a},data:function(){return{menuList:[],clientWidth:"",catName:""}},computed:{setCollapseWidth:function(){var e=this.$store.state.themeConfig.themeConfig,t=e.layout,s=(e=e.isCollapse,"classic"!==t&&"columns"!==t?"":"layout-el-aside-br-color");return"columns"===t?e?["layout-aside-width1",s]:["layout-aside-width-default",s]:e?["layout-aside-width64",s]:["layout-aside-width-default",s]},setShowLogo:function(){var e=this.$store.state.themeConfig.themeConfig,t=e.layout;e=e.isShowLogo;return e&&"defaults"===t||e&&"columns"===t},getThemeConfig:function(){return this.$store.state.themeConfig.themeConfig}},created:function(){var e=this;this.initMenuFixed(document.body.clientWidth),this.setFilterRoutes(),this.bus.$on("setSendColumnsChildren",(function(t){e.menuList=t||[],0<e.menuList.length?e.$store.state.themeConfig.themeConfig.isCollapse=!1:e.$store.state.themeConfig.themeConfig.isCollapse=!0})),this.bus.$on("layoutMobileResize",(function(t){e.initMenuFixed(t.clientWidth)})),this.bus.$on("oneCatName",(function(t){e.catName=t})),this.bus.$on("updateElScrollBar",(function(){setTimeout((function(){e.$refs.layoutAsideRef.update()}),300)})),"columns"!==this.$store.state.themeConfig.themeConfig.layout&&this.bus.$on("routesListChange",(function(){e.setFilterRoutes()}))},beforeDestroy:function(){this.bus.$off("routesListChange")},methods:{setFilterRoutes:function(){if("columns"===this.$store.state.themeConfig.themeConfig.layout)return!1;this.menuList=this.filterRoutesFun(this.$store.state.routesList.routesList)},filterRoutesFun:function(e){var t=this;return e.filter((function(e){return e.path})).map((function(e){return(e=Object.assign({},e)).children&&(e.children=t.filterRoutesFun(e.children)),e}))},initMenuFixed:function(e){this.clientWidth=e}},destroyed:function(){this.bus.$off("updateElScrollBar",(function(){}))}},n=Object(i.a)(o,(function(){var e=this,t=e.$createElement;t=e._self._c||t;return 1e3<e.clientWidth?t("el-aside",{staticClass:"layout-aside",class:e.setCollapseWidth},[e.setShowLogo&&e.menuList.length&&"columns"!==e.getThemeConfig.layout?t("Logo"):e._e(),e.menuList.length&&!e.getThemeConfig.isCollapse&&"columns"==e.getThemeConfig.layout?t("el-divider",{attrs:{"content-position":"center"}},[e._v(e._s(e.catName))]):e._e(),t("el-scrollbar",{ref:"layoutAsideRef",staticClass:"flex-auto"},[t("Vertical",{class:e.setCollapseWidth,attrs:{menuList:e.menuList}})],1)],1):t("el-drawer",{attrs:{visible:e.getThemeConfig.isCollapse,"with-header":!1,direction:"ltr",size:"180px"},on:{"update:visible":function(t){return e.$set(e.getThemeConfig,"isCollapse",t)}}},[t("el-aside",{staticClass:"layout-aside w100 h100"},[e.setShowLogo&&e.menuList.length?t("Logo"):e._e(),t("el-scrollbar",{ref:"layoutAsideRef",staticClass:"flex-auto"},[t("Vertical",{attrs:{menuList:e.menuList}})],1)],1)],1)}),[],!1,null,null,null);t.a=n.exports},"603b":function(e,t,s){"use strict";var i=s("c6f2");s.n(i).a},c6f2:function(e,t,s){},d5ca:function(e,t,s){"use strict";s.r(t);var i=s("5da5"),n=s("bb39"),o=s("3eeb");i={name:"layoutDefaults",components:{Asides:i.a,Headers:n.a,Mains:o.default},data:function(){return{}},computed:{isFixedHeader:function(){return this.$store.state.themeConfig.themeConfig.isFixedHeader}},watch:{$route:{handler:function(){this.$refs.layoutDefaultsScrollbarRef.wrap.scrollTop=0},deep:!0}}},n=s("2877"),o=Object(n.a)(i,(function(){var e=this,t=e.$createElement;t=e._self._c||t;return t("el-container",{staticClass:"layout-container"},[t("Asides"),t("el-container",{staticClass:"flex-center layout-backtop"},[e.isFixedHeader?t("Headers"):e._e(),t("el-scrollbar",{ref:"layoutDefaultsScrollbarRef"},[e.isFixedHeader?e._e():t("Headers"),t("Mains")],1)],1),t("el-backtop",{attrs:{target:".layout-backtop .el-scrollbar__wrap"}})],1)}),[],!1,null,null,null);t.default=o.exports}}]);