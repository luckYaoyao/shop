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


use app\dao\system\SystemRouteDao;
use app\services\BaseServices;
use crmeb\services\FormBuilder;
use think\exception\ValidateException;
use think\helper\Str;

/**
 * Class SystemRouteServices
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\services\system
 */
class SystemRouteServices extends BaseServices
{

    /**
     * SystemRouteServices constructor.
     * @param SystemRouteDao $dao
     */
    public function __construct(SystemRouteDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $where
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function getList(array $where)
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList($where, 'name,path,method', $page, $limit)->toArray();
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * @param int $id
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/10
     */
    public function getInfo(int $id)
    {
        $routeInfo = $this->dao->get($id);
        if (!$routeInfo) {
            throw new ValidateException('修改的路由不存在');
        }

        return $routeInfo->toArray();
    }

    /**
     * 获取tree数据
     * @param string $appName
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function getTreeList(array $where, string $appName = 'adminapi')
    {
        $list = app()->make(SystemRouteCateServices::class)
            ->selectList(['app_name' => $appName], '*', 0, 0, 'id asc,sort desc', [
                'children' => function ($query) use ($where) {
                    $query->where('app_name', $where['app_name'])
                        ->when('' !== $where['name_like'], function ($q) use ($where) {
                            $q->where('name|path', 'LIKE', '%' . $where['name_like'] . '%');
                        });
                }
            ])
            ->toArray();
        return get_tree_children($list);
    }

    /**
     * 获取某个应用下的所有路由权限
     * @param string $app
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function getRouteListAll(string $app = 'adminapi')
    {
        //获取所有的路由
        $this->app = app();
        $this->app->route->setTestMode(true);
        $this->app->route->clear();
        $path = $this->app->getRootPath() . 'app' . DS . $app . DS . 'route' . DS;
        $files = is_dir($path) ? scandir($path) : [];
        foreach ($files as $file) {
            if (strpos($file, '.php')) {
                include $path . $file;
            }
        }

        $route = $this->app->route->getRuleList();
        $action_arr = ['index', 'read', 'create', 'save', 'edit', 'update', 'delete'];

        foreach ($route as &$item) {
            $real_name = $item['option']['real_name'] ?? '';
            if (is_array($real_name)) {
                foreach ($action_arr as $action) {
                    if (Str::contains($item['route'], $action)) {
                        $real_name = $real_name[$action] ?? '';
                    }
                }
            }
            $item['option']['real_name'] = $real_name;
        }

        return $route;
    }

    /**
     * 同步路由
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function syncRoute(string $app = 'adminapi')
    {
        $id = app()->make(SystemRouteCateServices::class)->value(['app_name' => $app, 'name' => '全部权限', 'pid' => 0], 'id');
        if (!$id) {
            $res = app()->make(SystemRouteCateServices::class)->save([
                'app_name' => $app,
                'name' => '全部权限',
                'pid' => 0,
                'add_time' => time(),
            ]);
            $id = $res->id;
        }
        $listAll = $this->getRouteListAll($app);
        //保持新增的权限路由
        $data = $this->dao->selectList(['app_name' => $app], 'path,method')->toArray();
        $save = [];
        foreach ($listAll as $item) {
            if (!$this->diffRoute($data, $item['rule'], $item['method']) && strstr($item['rule'], '<MISS>') === false) {
                $save[] = [
                    'name' => $item['option']['real_name'] ?? $item['name'],
                    'path' => $item['rule'],
                    'cate_id' => $id,
                    'app_name' => $app,
                    'type' => isset($item['option']['is_common']) && $item['option']['is_common'] ? 1 : 0,
                    'method' => $item['method'],
                    'add_time' => date('Y-m-d H:i:s'),
                ];
            }
        }

        if ($save) {
            $this->dao->saveAll($save);
        }
        //删除不存在的权限路由
        $data = $this->dao->selectList(['app_name' => $app], 'path,method,id')->toArray();
        $delete = [];
        foreach ($data as $item) {
            if (!$this->diffRoute($listAll, $item['path'], $item['method'], 'rule') && $item['path'] !== '<MISS>') {
                $delete[] = $item['id'];
            }
        }
        if ($delete) {
            $this->dao->delete([['id', 'in', $delete]]);
        }
    }

    /**
     * 对比路由
     * @param array $data
     * @param string $path
     * @param string $method
     * @return bool
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    protected function diffRoute(array $data, string $path, string $method, string $key = 'path')
    {
        $res = false;
        foreach ($data as $item) {
            if (strtolower($item['method']) == strtolower($method) && strtolower($item[$key]) == strtolower($path)) {
                $res = true;
                break;
            } else if ($method === '*' && strtolower($item[$key]) == strtolower($path)) {
                $res = true;
                break;
            }
        }
        return $res;
    }

    /**
     * 添加和修改路由
     * @param int $id
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function getFrom(int $id = 0, string $appName = 'adminapi')
    {
        $cateList = app()->make(SystemRouteCateServices::class)->getAllList($appName, 'name as label,path as value');

        $url = '/system/route';
        $routeInfo = [];
        if ($id) {
            $routeInfo = $this->dao->get($id);
            $routeInfo = $routeInfo ? $routeInfo->toArray() : [];
            $url .= '/' . $id;
        }

        $rule = [
            FormBuilder::cascader('cate_id', '分类', $routeInfo['cate_id'] ?? 0)->data($cateList),
            FormBuilder::input('name', '路由名称', $routeInfo['name'] ?? '')->required(),
            FormBuilder::input('path', '路由路径', $routeInfo['path'] ?? '')->required(),
            FormBuilder::select('method', '请求方式', $routeInfo['method'] ?? '')->options([
                ['value' => 'POST', 'label' => 'POST'],
                ['value' => 'GET', 'label' => 'GET'],
                ['value' => 'DELETE', 'label' => 'DELETE'],
                ['value' => 'PUT', 'label' => 'PUT'],
                ['value' => '*', 'label' => '*'],
            ])->required(),
            FormBuilder::radio('type', '类型', $routeInfo['type'] ?? 0)->options([
                ['value' => 0, 'lable' => '普通路由'],
                ['value' => 1, 'lable' => '公共路由'],
            ]),
            FormBuilder::hidden('app_name', $appName),
        ];

        return create_form($id ? '修改路由' : '添加路由', $rule, $url, $id ? 'PUT' : 'POST');
    }
}
