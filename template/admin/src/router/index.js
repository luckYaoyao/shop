/*
 * @Author: From-wh from-wh@hotmail.com
 * @Date: 2023-03-04 11:49:55
 * @FilePath: /admin/src/router/index.js
 * @Description:
 *
 */
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import Vue from 'vue';
import Router from 'vue-router';
import routes from './routers';
import Setting from '@/setting';
import store from '@/store';
import iView from 'iview';
import { removeCookies, getCookies, setTitle } from '@/libs/util';
import { includeArray } from '@/libs/auth';
import { PrevLoading } from '@/utils/loading.js';

Vue.use(Router);
// 解决 `element ui` 导航栏重复点菜单报错问题
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch((err) => err);
};

const router = new Router({
  routes,
  mode: Setting.routerMode,
});

// 多级嵌套数组处理成一维数组
export function formatFlatteningRoutes(arr) {
  if (arr.length <= 0) return false;
  for (let i = 0; i < arr.length; i++) {
    if (arr[i].children) {
      arr = arr.slice(0, i + 1).concat(arr[i].children, arr.slice(i + 1));
    }
  }
  return arr;
}

// 处理 tagsViewList 数据，默认路由全部缓存
// isKeepAlive 处理 `name` 值，进行路由缓存
export function formatTwoStageRoutes(arr) {
  if (arr.length <= 0) return false;
  const newArr = [];
  const cacheList = [];
  arr.forEach((v) => {
    newArr.push({ ...v });
    cacheList.push(v.name);
    store.dispatch('keepAliveNames/setCacheKeepAlive', cacheList);
  });
  return newArr;
}

// 判断路由 meta.roles 中是否包含当前登录用户权限字段
export function hasAuth(roles, route) {
  if (route.meta && route.meta.auth) return roles.some((role) => route.meta.auth.includes(role));
  else return true;
}

// 递归过滤有权限的路由
export function setFilterMenuFun(routes, role) {
  const menu = [];
  routes.forEach((route) => {
    const item = { ...route };
    if (hasAuth(role, item)) {
      if (item.children) item.children = setFilterMenuFun(item.children, role);
      menu.push(item);
    }
  });
  return menu;
}

// 缓存多级嵌套数组处理后的一维数组(tagsView、菜单搜索中使用：未过滤隐藏的(isHide))
export function setCacheTagsViewRoutes(arr) {
  // 先处理有权限的路由，否则 tagsView、菜单搜索中无权限的路由也将显示
  let rolesRoutes = setFilterMenuFun(arr, store.state.userInfo.access);
  // 添加到 vuex setTagsViewRoutes 中
  store.dispatch('tagsViewRoutes/setTagsViewRoutes', formatTwoStageRoutes(formatFlatteningRoutes(rolesRoutes)));
}

// 递归处理多余的 layout : <router-view>，让需要访问的组件保持在第一层 layout 层。
// 因为 `keep-alive` 只能缓存二级路由
// 默认初始化时就执行
export function keepAliveSplice(to) {
  if (to.matched && to.matched.length > 2) {
    to.matched.map((v, k) => {
      if (v.components.default instanceof Function) {
        v.components.default().then((components) => {
          if (components.default.name === 'parent') {
            to.matched.splice(k, 1);
            router.push({ path: to.path, query: to.query });
            keepAliveSplice(to);
          }
        });
      } else {
        if (v.components.default.name === 'parent') {
          to.matched.splice(k, 1);
          keepAliveSplice(to);
        }
      }
    });
  }
}

// 处理后端返回的 `component` 路径，拼装实现懒加载
export function loadView(path) {
  /**
   * 打包成一个 js、一个 css
   */
  // if (path.indexOf('layout') > -1) return () => Promise.resolve(require(`@/${path}`));
  // else return () => Promise.resolve(require(`@/views/${path}`));

  /**
   * 打包成多个 js、多个 css
   */
  if (path.indexOf('layout') > -1) return () => import(`@/${path}`);
  else return () => import(`@/pages/${path}`);
}

// 递归处理每一项 `component` 中的路径
export function dynamicRouter(routes) {
  return routes.map((view) => {
    if (view.component) view.component = loadView(view.component);
    if (view.children) dynamicRouter(view.children);
    return view;
  });
}

// 添加路由，模拟数据与方法，可自行进行修改 admin
// 添加动态路由，`{ path: '*', redirect: '/404' }` 防止页面刷新，静态路由丢失问题
// next({ ...to, replace: true }) 动态路由 addRoute 完毕后才放行，防止刷新时 NProgress 进度条加载2次
// 文档地址：https://router.vuejs.org/zh/api/#router-addroutes
export async function adminUser(router, to, next) {
  resetRouter();
  let menus = this.$store.state.menus.menusName;
  // 读取用户信息，获取对应权限进行判断
  store.dispatch('userInfos/setUserInfos');
  store.dispatch('routesList/setRoutesList', setFilterMenuFun(menus, store.state.userInfos.userInfos.roles));
  dynamicRoutes[0].children = menus;
  const awaitRoute = await dynamicRouter(dynamicRoutes);
  [...awaitRoute, { path: '*', redirect: '/404' }].forEach((route) => {
    router.addRoute({ ...route });
  });
  setCacheTagsViewRoutes(JSON.parse(JSON.stringify(menus)));
  next({ ...to, replace: true });
}

// 添加路由，模拟数据与方法，可自行进行修改 test
// 添加动态路由，`{ path: '*', redirect: '/404' }` 防止页面刷新，静态路由丢失问题
export async function testUser(router, to, next) {
  resetRouter();
  let menus = this.$store.state.menus.menusName;

  // 读取用户信息，获取对应权限进行判断
  store.dispatch('userInfos/setUserInfos');
  store.dispatch('routesList/setRoutesList', setFilterMenuFun(menus, store.state.userInfo.userInfo.access));
  dynamicRoutes[0].children = menus;
  const awaitRoute = await dynamicRouter(dynamicRoutes);
  [...awaitRoute, { path: '*', redirect: '/404' }].forEach((route) => {
    router.addRoute({ ...route });
  });
  setCacheTagsViewRoutes(JSON.parse(JSON.stringify(menus)));
  next({ ...to, replace: true });
}

// 重置路由
export function resetRouter() {
  router.matcher = router().matcher;
}

// 延迟关闭进度条
export function delayNProgressDone(time = 300) {
  setTimeout(() => {
    NProgress.done();
  }, time);
}

// 动态加载后端返回路由路由(模拟数据)
export function getRouterList(router, to, next) {
  if (!Session.get('userInfo')) return false;
  if (Session.get('userInfo').userName === 'admin') adminUser(router, to, next);
  else if (Session.get('userInfo').userName === 'test') testUser(router, to, next);
}

/**
 * 路由拦截
 * 权限验证
 */

router.beforeEach(async (to, from, next) => {
  // PrevLoading.start();
  keepAliveSplice(to);

  if (to.fullPath.indexOf('kefu') != -1) {
    return next();
  }
  // if (Setting.showProgressBar) iView.LoadingBar.start()
  // 判断是否需要登录才可以进入
  if (to.matched.some((_) => _.meta.auth)) {
    // 这里依据 token 判断是否登录，可视情况修改
    const token = getCookies('token');
    if (token && token !== 'undefined') {
      const access = store.state.userInfo.uniqueAuth;
      const isPermission = includeArray(to.meta.auth, access);
      if (isPermission) {
        next();
      } else {
        if (access.length == 0) {
          next({
            name: 'login',
            query: {
              redirect: to.fullPath,
            },
          });
          localStorage.clear();
          removeCookies('token');
          removeCookies('expires_time');
          removeCookies('uuid');
        } else {
          next({
            name: '403',
          });
        }
      }
      // next();
    } else {
      // 没有登录的时候跳转到登录界面
      // 携带上登陆成功之后需要跳转的页面完整路径
      next({
        name: 'login',
        query: {
          redirect: to.fullPath,
        },
      });
      localStorage.clear();
      removeCookies('token');
      removeCookies('expires_time');
      removeCookies('uuid');
    }
  } else {
    // 不需要身份校验 直接通过
    next();
  }
});
router.afterEach((to) => {
  // if (Setting.showProgressBar) iView.LoadingBar.finish()
  // 更改标题
  setTitle(to, router.app);
  // 返回页面顶端
  window.scrollTo(0, 0);
  PrevLoading.done();
});
export default router;
