// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

import request from '@/libs/request';

/**
 * @description 代码生成 - 菜单选择列表
 */
export function crudMenus() {
  return request({
    url: '/system/crud/menus',
    method: 'get',
  });
}
/**
 * @description 代码生成 - sql表选择列表
 */
export function crudColumnType() {
  return request({
    url: '/system/crud/column_type',
    method: 'get',
  });
}
/**
 * @description 代码生成 - 第一步提交 获取CRUD文件存放
 */
export function crudFilePath(data) {
  return request({
    url: '/system/crud/file_path',
    method: 'post',
    data,
  });
}

/**
 * @description 管理员添加表单
 */
export function adminFromApi() {
  return request({
    url: '/setting/admin/create',
    method: 'get',
  });
}

/**
 * @description 管理员编辑表单
 * @param {Number} param id {Number} 管理员id
 */
export function adminEditFromApi(id) {
  return request({
    url: `/setting/admin/${id}/edit`,
    method: 'get',
  });
}

/**
 * @description 管理员删除
 * @param {Number} param id {Number} 管理员id
 */
export function adminDelFromApi(id) {
  return request({
    url: `/setting/admin/${id}`,
    method: 'DELETE',
  });
}

/**
 * @description 管理员 修改状态
 * @param {Object} param data {Object} 传值
 */
export function setShowApi(data) {
  return request({
    url: `setting/set_status/${data.id}/${data.status}`,
    method: 'PUT',
  });
}
