<template>
  <div class="side-menu-wrapper">
    <slot></slot>
    <div class="side-menu-box" v-show="!collapsed">
      <div class="parent-menu">
        <Menu
          ref="menu"
          :active-name="activeMenuPath"
          :open-names="openedNames"
          :accordion="accordion"
          :theme="theme"
          width="75px"
          @on-open-change="openNameData"
          @on-select="handleSelect"
        >
          <template v-for="item in menuList">
            <template>
              <menu-item :name="item.path" :key="`menu${item.path}`"
                ><common-icon :type="item.icon || ''" /><span class="title">{{ item.title }}</span></menu-item
              >
            </template>
          </template>
        </Menu>
      </div>

      <div class="child-menu" v-if="childList.length">
        <div class="cat-name">{{ catName }}</div>
        <Menu
          ref="childMenu"
          :active-name="activePath"
          :open-names="openMenus"
          :accordion="accordion"
          :theme="theme"
          width="145px"
          @on-open-change="openChildNameData"
          @on-select="handleChildSelect"
        >
          <template v-for="item in childList">
            <template v-if="item.auth === undefined">
              <template v-if="item.children && item.children.length >= 1">
                <side-menu-item
                  v-if="showChildren(item)"
                  :key="`menu${item.path}`"
                  :parent-item="item"
                ></side-menu-item>
                <menu-item v-else :name="item.path" :key="`menu${item.path}`"
                  ><common-icon :type="item.children[0].icon || ''" /><span class="title">{{
                    item.children[0].title
                  }}</span></menu-item
                >
              </template>
              <template v-else>
                <side-menu-item
                  v-if="showChildren(item)"
                  :key="`menu${item.path}`"
                  :parent-item="item"
                ></side-menu-item>
                <menu-item v-else :name="item.path" :key="`menu${item.path}`"
                  ><common-icon :type="item.icon || ''" /><span class="title">{{ item.title }}</span></menu-item
                >
              </template>
            </template>
          </template>
        </Menu>
      </div>
    </div>

    <div class="menu-collapsed" v-show="collapsed" :list="menuList">
      <template v-for="item in menuList">
        <collapsed-menu
          v-if="item.children && item.children.length > 0"
          @on-click="collHandleSelect"
          :hide-title="true"
          :activeMenuPath="activeMenuPath"
          :root-icon-size="rootIconSize"
          :icon-size="iconSize"
          :theme="theme"
          :parent-item="item"
          :key="`drop-menu-${item.path}`"
        ></collapsed-menu>
        <Tooltip transfer v-else :content="item.title" placement="right" :key="`drop-menu-${item.path}`">
          <a
            @click="collHandleSelect(item)"
            class="drop-menu-a"
            :class="{ on: item.path == activeMenuPath }"
            :style="{ textAlign: 'center' }"
            ><common-icon :color="textColor" :type="item.icon || (item.children && item.children[0].icon)" />
            <span class="title">{{ item.title }}</span>
          </a>
        </Tooltip>
      </template>
    </div>
  </div>
</template>
<script>
import SideMenuItem from './side-menu-item.vue';
import CollapsedMenu from './collapsed-menu.vue';
import { getUnion } from '@/libs/tools';
import { mapState } from 'vuex';
import mixin from './mixin';
import itemMixin from './item-mixin';

export default {
  name: 'SideMenu',
  mixins: [mixin, itemMixin],
  components: {
    SideMenuItem,
    CollapsedMenu,
  },
  props: {
    menuList: {
      type: Array,
      default() {
        return [];
      },
    },
    collapsed: {
      type: Boolean,
    },
    theme: {
      type: String,
      default: 'light',
    },
    rootIconSize: {
      type: Number,
      default: 20,
    },
    iconSize: {
      type: Number,
      default: 16,
    },
    accordion: Boolean,
    openNames: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      openedNames: [],
      childList: [],
      activeChildName: '',
      childOptions: [],
      activePath: '',
      activeMenuPath: '',
      catName: '',
    };
  },
  computed: {
    ...mapState('menus', ['openMenus']),
    textColor() {
      return this.theme === 'dark' ? '#fff' : '#495060';
    },
  },
  watch: {
    activeName(name) {
      if (this.accordion) this.openedNames = this.getOpenedNamesByActiveName();
      else this.openedNames = getUnion(this.openedNames, this.getOpenedNamesByActiveName());
      // this.handleSelect(this.activeName);
    },
    openNames(newNames) {
      this.openedNames = newNames;
    },
    openedNames() {
      this.$nextTick(() => {
        this.$refs.menu.updateOpened();
      });
    },
    $route(newRoute) {},
    $route: {
      handler(newRoute) {
        console.log(newRoute, 'newRoutenewRoute');
        this.activePath = newRoute.path;
        // this.activeMenuPath = newRoute.matched[0].path;
        this.handleUpdateMenuState();
      },
      immediate: true,
    },
  },
  mounted() {
    console.log(this.menuList);
    this.openedNames = getUnion(this.openedNames, this.getOpenedNamesByActiveName());
    if (sessionStorage.getItem('menuActive')) {
      this.activeMenuPath = sessionStorage.getItem('menuActive');
      this.catName = sessionStorage.getItem('menuActiveTitle');
      this.getChildrenList(sessionStorage.getItem('menuActive'));
    } else {
      this.handleSelect(this.openedNames[0]);
    }
  },
  methods: {
    getChildrenList(path) {
      this.menuList.map((e) => {
        if (e.path === path) {
          this.childList = e.children || [];
        }
      });
    },

    handleSelect(name, type) {
      this.childOptions = [];
      this.menuList.map((e) => {
        if (e.path === name) {
          if (e.children && e.children.length) {
            this.jump(e.children);
            this.catName = e.title;
            // this.activeMenuPath = e.path;
            this.childList = e.children || [];
            sessionStorage.setItem('menuActive', e.path);
            sessionStorage.setItem('menuActiveTitle', e.title);
            this.activeMenuPath = e.path;
            // this.activeChildName = e.children[0].path;
            // this.childOptions = [e.children[0].path];
            this.$store.commit('menus/childMenuList', this.childList);
            // if (!type) {
            //   this.$emit('on-select', e.children[0].path);
            // }
          } else {
            if (!type) {
              this.$emit('on-select', name);
            }
            this.activeMenuPath = e.path;

            this.childList = [];
            this.$store.commit('menus/childMenuList', []);
          }
        }
      });
    },
    jump(data) {
      if (data[0].children && data[0].children.length) {
        this.jump(data[0].children);
      } else {
        this.catName = data[0].title;
        // this.activeMenuPath = data[0].path;
        this.activeChildName = data[0].path;
        this.childOptions = [data[0].path];
        this.$store.commit('menus/childMenuList', this.childList);
        this.$emit('on-select', data[0].path);
      }
    },
    handleChildSelect(name) {
      this.turnToPage(name);
    },
    collHandleSelect(name) {
      this.turnToPage(name);
    },
    turnToPage(route, all) {
      let { path, name, params, query } = {};
      if (typeof route === 'string' && !all) path = route;
      else if (typeof route === 'string' && all) name = route;
      else {
        path = route.path;
        name = route.name;
        params = route.params;
        query = route.query;
      }
      this.$router.push({
        path,
        name,
        params,
        query,
      });
    },
    getOpenedNamesByActiveName() {
      return this.$route.matched.map((item) => item.path).filter((item) => item !== name);
    },
    updateOpenName(name) {
      if (name === this.$config.homeName) this.openedNames = [];
      else this.openedNames = this.getOpenedNamesByActiveName();
    },
    openNameData(n) {
      // this.openedNames = n
      // this.$store.commit('menus/getopenMenus', n)
    },
    openChildNameData(e) {
      console.log(e);
    },
    handleUpdateMenuState() {
      this.$nextTick(() => {
        if (this.$refs.childMenu) {
          this.$refs.childMenu.updateActiveName();
          if (this.accordion) this.$refs.childMenu.updateOpened();
        }
      });
    },
  },
};
</script>
<style lang="less">
@import './side-menu.less';
.ivu-menu {
  .side-menu-wrapper a.drop-menu-a {
    padding: 1px !important;
  }

  .ivu-select-dropdown.ivu-dropdown-transfer {
    background: rgb(0, 21, 41) !important;
    width: 170px !important;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer .ivu-select-dropdown {
    background: rgb(0, 21, 41) !important;
    width: 170px !important;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer .ivu-dropdown-menu {
    min-width: unset !important;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer .ivu-dropdown-menu .ivu-dropdown-item {
    padding: 9px 0 9px 30px !important;
    font-size: 13px !important;
    text-align: left;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer .ivu-dropdown-menu .ivu-dropdown-item:hover {
    background-color: #2d8cf0 !important;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer .menu-title {
    padding-left: 0 !important;
    color: rgba(225, 225, 225, 0.7) !important;
    font-size: 13px !important;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer .ivu-dropdown-menu .ivu-dropdown-item:hover .menu-title {
    color: #fff !important;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer .collased-menu-dropdown {
    padding: 9px 0 9px 30px !important;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer .collased-menu-dropdown:hover {
    background-color: #2d8cf0 !important;
    color: #fff !important;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer
    .collased-menu-dropdown:hover
    > .ivu-dropdown-rel
    > .drop-menu-a
    > .menu-title {
    color: #fff !important;
    font-size: 14px;
    line-height: 14px;
  }
  .ivu-select-dropdown.ivu-dropdown-transfer
    .collased-menu-dropdown:hover
    > .ivu-dropdown-rel
    > .drop-menu-a
    > .ivu-icon {
    color: #fff !important;
  }
}
.side-menu-wrapper a.drop-menu-a {
  display: flex;
  align-items: center;
  justify-content: center;
}

.side-menu-box {
  display: flex;
  .drop-menu-a {
    font-size: 14px;
    line-height: 14px;
    display: flex;
    align-items: center;
    padding: 10px 10px;
    margin-bottom: 4px;
    .ivu-icon {
      font-size: 14px;
      margin-right: 8px;
    }
  }
  .parent-menu {
    z-index: 99;
    padding: 0 8px;
    box-shadow: 2px 0px 4px 0px rgba(0, 0, 0, 0.06);
    height: calc(~'100vh - 50px');
    .ivu-menu-item-selected {
      background-color: #1890ff !important;
      color: #fff !important;
      border-radius: 4px;
    }
    .ivu-menu-vertical .ivu-menu-item {
      padding: 10px 10px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      .title {
        font-size: 14px;
        line-height: 14px;
      }
    }
    .ivu-menu-vertical .ivu-menu-item:first-child {
      margin-top: 8px;
    }
  }
  .child-menu {
    z-index: 88;
    .ivu-menu-vertical .ivu-menu-item {
      padding: 13px 10px;
      display: flex;
      align-items: center;
      .title {
        font-size: 13px;
        line-height: 13px;
      }
    }
    .ivu-menu-submenu {
      .ivu-menu-item {
        padding-left: 16px !important;
      }
      .ivu-menu-submenu {
        .ivu-menu-submenu-title {
          padding-left: 16px !important;
        }
      }
    }
    .ivu-menu-vertical .ivu-menu-submenu-title {
      font-size: 13px;
      padding: 12px 10px;
      line-height: 16px;
    }
    .cat-name {
      font-size: 14px;
      line-height: 16px;
      font-weight: 600;
      color: #303133;
      padding: 18px;
      border-bottom: 1px solid #eee;
    }
  }
  > .ivu-menu {
    padding: 8px;
  }

  .ivu-menu-vertical.ivu-menu-light:after {
    width: 0px !important;
  }
  .ivu-menu-light.ivu-menu-vertical .ivu-menu-item-active:not(.ivu-menu-submenu):after {
    width: 0px;
  }
}
.menu-collapsed {
  padding: 0 8px;
  .drop-menu-a.on {
    background-color: #1890ff !important;
    color: #fff !important;
    border-radius: 4px;
    .ivu-icon {
      color: #fff !important;
    }
  }
  .drop-menu-a {
    font-size: 14px;
    line-height: 14px;
    display: flex;
    align-items: center;
    padding: 10px 11px;
    margin-bottom: 4px;
    flex-wrap: nowrap;
    .ivu-icon {
      font-size: 14px;
      margin-right: 8px;
    }
    .title {
      white-space: nowrap;
    }
  }
  .drop-menu-a {
    padding: 0;
  }
  .collased-menu-dropdown * {
    font-size: 14px;
  }
}
</style>
