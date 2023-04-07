<?php
/**
 *  +----------------------------------------------------------------------
 *  | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2016~2022 https://www.crmeb.com All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
 *  +----------------------------------------------------------------------
 *  | Author: CRMEB Team <admin@crmeb.com>
 *  +----------------------------------------------------------------------
 */

namespace app\services\system;


use app\dao\system\SystemRouteCateDao;
use app\services\BaseServices;
use crmeb\services\FormBuilder;

/**
 * Class SystemRouteCateServices
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\services\system
 */
class SystemRouteCateServices extends BaseServices
{

    /**
     * SystemRouteCateServices constructor.
     * @param SystemRouteCateDao $dao
     */
    public function __construct(SystemRouteCateDao $dao)
    {
        $this->dao = $dao;
    }

    public function getPathValue(int $pid)
    {
        if (!$pid) {
            return [];
        }
        $path = $this->dao->value($pid, 'path');
        $pathAttr = explode('/', $path);
        $pathData = [];
        foreach ($pathAttr as $item) {
            if (!$item) {
                $pathData[] = $item;
            }
        }
        return $pathAttr;
    }

    /**
     * @param array $path
     * @param int $id
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function setPathValue(array $path, int $id)
    {
        return '/' . implode('/', $path) . '/' . $id . '/';
    }

    /**
     * @param string $appName
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function getAllList(string $appName = 'outapi', string $field = '*')
    {
        $list = $this->dao->selectList(['app_name' => $appName], $field)->toArray();
        return get_tree_children($list);
    }

    /**
     * @param int $id
     * @param string $appName
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function getFrom(int $id = 0, string $appName = 'outapi')
    {
        $url = '/system/route_cate';
        $cateInfo = [];
        if ($id) {
            $cateInfo = $this->dao->get($id);
            $cateInfo = $cateInfo ? $cateInfo->toArray() : [];
            $url .= '/' . $id;
        }
        $options = $this->dao->selectList(['app_name' => $appName])->toArray();
        $rule = [
            FormBuilder::select('pid', '上级分类', (int)($cateInfo['pid'] ?? 0))->options($options),
            FormBuilder::input('name', '分类名称', $cateInfo['name'] ?? '')->required(),
            FormBuilder::number('sort', '排序', (int)($cateInfo['sort'] ?? 0)),
            FormBuilder::hidden('app_name', $appName)
        ];

        return create_form($id ? '修改分类' : '添加分类', $rule, $url, $id ? 'PUT' : 'POST');
    }
}
