<template>
  <div class="layout-navbars-breadcrumb" :style="{ display: isShowBreadcrumb }">
    <!-- {{[...breadCrumbList,...crumbPast]}} -->
    <i
      class="layout-navbars-breadcrumb-icon"
      :class="getThemeConfig.isCollapse ? 'el-icon-s-unfold' : 'el-icon-s-fold'"
      @click="onThemeConfigChange"
    ></i>
    <el-breadcrumb class="layout-navbars-breadcrumb-hide">
      <transition-group name="breadcrumb" mode="out-in">
        <el-breadcrumb-item v-for="(v, k) in [...breadCrumbList, ...crumbPast]" :key="v.path">
          <span v-if="k == 1" class="layout-navbars-breadcrumb-span">
            <Icon
              :type="v.icon"
              class="ivu-icon layout-navbars-breadcrumb-iconfont"
              v-if="getThemeConfig.isBreadcrumbIcon"
            />{{ $t(v.title) }}
          </span>
          <a v-else @click.prevent="onBreadcrumbClick(v)">
            <Icon
              :type="v.icon"
              class="ivu-icon layout-navbars-breadcrumb-iconfont"
              v-if="getThemeConfig.isBreadcrumbIcon"
            />{{ $t(v.title) }}
          </a>
        </el-breadcrumb-item>
      </transition-group>
    </el-breadcrumb>
  </div>
</template>

<script>
import { Local } from '@/utils/storage.js';
import { R } from '@/libs/util';
import { getMenuopen } from '@/libs/util';

export default {
  name: 'layoutBreadcrumb',
  data() {
    return {
      breadcrumbList: [],
      routeSplit: [],
      routeSplitFirst: '',
      routeSplitIndex: 1,
    };
  },
  computed: {
    breadCrumbList() {
      let menuList = this.$store.state.menus.menusName;
      let openMenus = getMenuopen(this.$route, menuList);
      let allMenuList = R(menuList, []);
      let selectMenu = [];
      if (allMenuList.length > 0) {
        openMenus.forEach((i) => {
          allMenuList.forEach((a) => {
            if (i === a.path) {
              selectMenu.push(a);
            }
          });
        });
      }
      return selectMenu;
    },
    crumbPast() {
      let that = this;
      let menuList = that.$store.state.menus.menusName;
      let allMenuList = R(menuList, []);
      let selectMenu = [];
      if (allMenuList.length > 0) {
        allMenuList.forEach((a) => {
          if (that.$route.path === a.path) {
            selectMenu.push(a);
          }
        });
      }
      return selectMenu;
    },
    // 获取布局配置信息
    getThemeConfig() {
      return this.$store.state.themeConfig.themeConfig;
    },
    // 动态设置经典、横向布局不显示
    isShowBreadcrumb() {
      const { layout, isBreadcrumb } = this.$store.state.themeConfig.themeConfig;
      if (layout === 'transverse') {
        return 'none';
      } else {
        return isBreadcrumb ? '' : 'none';
      }
    },
  },
  mounted() {
    this.initRouteSplit(this.$route.path);
  },
  methods: {
    // breadcrumb 当前项点击时
    onBreadcrumbClick(v) {
      const { redirect, path } = v;
      if (redirect) this.$router.push(redirect);
      else this.$router.push(path);
    },
    // breadcrumb icon 点击菜单展开与收起
    onThemeConfigChange() {
      this.$store.state.themeConfig.themeConfig.isCollapse = !this.$store.state.themeConfig.themeConfig.isCollapse;
      this.setLocalThemeConfig();
    },
    // 存储布局配置
    setLocalThemeConfig() {
      Local.remove('themeConfigPrev');
      Local.set('themeConfigPrev', this.$store.state.themeConfig.themeConfig);
    },
    // 递归设置 breadcrumb
    getBreadcrumbList(arr) {
      console.log(this.routeSplit, 'routeSplit');
      arr.map((item) => {
        this.routeSplit.map((v, k, arrs) => {
          if (this.routeSplitFirst === item.path) {
            this.routeSplitFirst += `/${arrs[this.routeSplitIndex]}`;
            this.breadcrumbList.push(item);
            this.routeSplitIndex++;
            if (item.children) this.getBreadcrumbList(item.children);
          }
        });
      });
      console.log(arr, 'arrarrarr');
    },
    // 当前路由分割处理
    initRouteSplit(path) {
      this.breadcrumbList = [
        {
          path: '/',
          meta: {
            title: this.$store.state.routesList.routesList[0].title,
            icon: this.$store.state.routesList.routesList[0].icon,
          },
        },
      ];
      //   this.routeSplit = path.split('/');
      console.log(path.split('/'), 'path');

      //   this.routeSplit.shift();
      this.routeSplitFirst = path;
      this.routeSplitIndex = 1;
      console.log(',,,,,');
      this.getBreadcrumbList(this.$store.state.routesList.routesList);
    },
  },
  // 监听路由的变化
  watch: {
    $route: {
      handler(newVal) {
        console.log(newVal, '123321`', this.$store.state.menus.menusName);
        // this.initRouteSplit(newVal.path);
        let menuList = this.$store.state.menus.menusName;
        let openMenus = getMenuopen(newVal, menuList);
        console.log('11', openMenus);
        let allMenuList = R(menuList, []);
        let selectMenu = [];
        if (allMenuList.length > 0) {
          openMenus.forEach((i) => {
            allMenuList.forEach((a) => {
              if (i === a.path) {
                selectMenu.push(a);
              }
            });
          });
        }
        console.log(openMenus, 'selectMenu');
      },
      deep: true,
    },
  },
};
</script>

<style scoped lang="scss">
.layout-navbars-breadcrumb {
  flex: 1;
  height: inherit;
  display: flex;
  align-items: center;
  padding-left: 15px;
  .layout-navbars-breadcrumb-icon {
    cursor: pointer;
    font-size: 18px;
    margin-right: 15px;
    color: var(--prev-bg-topBarColor);
    opacity: 0.8;
    &:hover {
      opacity: 1;
    }
  }
  .layout-navbars-breadcrumb-span {
    opacity: 0.7;
    color: var(--prev-bg-topBarColor);
  }
  .layout-navbars-breadcrumb-iconfont {
    font-size: 14px;
    margin-right: 5px;
  }
}
</style>
