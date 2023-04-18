// +---------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +---------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +---------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +---------------------------------------------------------------------

import LayoutMain from '@/layout';
import setting from '@/setting';
let routePre = setting.routePre;

// export default {
//   path: '/',
//   name: 'home',
//   redirect: '/admin/home',
//   component: LayoutMain,
//   meta: {
//     hideInMenu: true,
//     notCache: true,
//     auth: true
//   },
//   children: [
//     {
//       path: 'admin/home',
//       name: 'home',
//       meta: {
//         title: '首页',
//         auth: ['admin-index-index']
//       },
//       component: () => import('@/pages/index/index')
//     }
//   ]
// }

const meta = {
  auth: true,
};

const pre = 'home_';

export default {
  path: routePre + '/home',
  name: 'home',
  header: 'home',
  redirect: {
    name: `${pre}index`,
  },
  meta,
  component: LayoutMain,
  children: [
    {
      path: routePre + '/home/',
      name: `${pre}index`,
      header: 'home',
      meta: {
        auth: ['admin-index-index'],
        title: '主页',
        isAffix: true,
      },
      component: () => import('@/pages/index/index'),
    },
  ],
};
