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

namespace app\adminapi\controller\v1\setting;


use app\adminapi\controller\AuthController;
use app\services\system\SystemCrudServices;
use app\services\system\SystemMenusServices;
use crmeb\services\CacheService;
use crmeb\services\crud\Make;
use crmeb\services\FileService;
use crmeb\utils\Terminal;
use think\facade\App;
use think\helper\Str;
use think\Response;

/**
 * Class SystemCrud
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\adminapi\controller\v1\setting
 */
class SystemCrud extends AuthController
{

    /**
     * SystemCrud constructor.
     * @param App $app
     * @param SystemCrudServices $services
     */
    public function __construct(App $app, SystemCrudServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function index()
    {
        return app('json')->success($this->services->getList());
    }

    /**
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function save()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['menuName', ''],
            ['tableName', ''],
            ['modelName', ''],
            ['tableComment', ''],//表备注
            ['tableField', []],//表字段
            ['tableIndex', []],//索引
            ['filePath', []],
            ['isTable', 0],
        ]);

        $fromField = $columnField = [];
        foreach ($data['tableField'] as $item) {
            if ($item['is_table'] && !in_array($item['field_type'], ['addSoftDelete', 'addSoftDelete'])) {
                $columnField[] = [
                    'field' => $item['field'],
                    'name' => $item['table_name'],
                    'type' => $item['from_type'],
                ];
            }
            if ($item['from_type']) {
                $name = $item['table_name'] ?: $item['comment'];
                if (!$name) {
                    return app('json')->fail($item['field'] . '字段的表单标题必须填写');
                }
                $fromField[] = [
                    'field' => $item['field'],
                    'type' => $item['from_type'],
                    'name' => $name,
                    'required' => $item['required'],
                    'option' => $item['option'] ?? [],
                ];
            }
        }
        if (!$fromField) {
            return app('json')->fail('至少选择一个字段作为表单项');
        }
        if (!$columnField) {
            return app('json')->fail('至少选择一个字段作为列展示在列表中');
        }
        $data['fromField'] = $fromField;
        $data['columnField'] = $columnField;
        if (!$data['tableName']) {
            return app('json')->fail(500045);
        }

        $this->services->createCrud($data);

        return app('json')->success(500046);
    }

    /**
     * 获取创建文件的目录存放位置
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function getFilePath()
    {
        [$tableName, $isTable] = $this->request->postMore([
            ['tableName', ''],
            ['isTable', 0],
        ], true);

        if (!$tableName) {
            return app('json')->fail(500045);
        }

        if (in_array($tableName, SystemCrudServices::NOT_CRUD_TABANAME)) {
            return app('json')->fail(500044);
        }

        $routeName = 'crud/' . Str::snake($tableName);

        $key = 'id';
        $tableField = [];
        if ($isTable) {
            $field = $this->services->getColumnNamesList($tableName);
            if (!$field) {
                return app('json')->fail('表不存在');
            }
            foreach ($field as $item) {
                if ($item['primaryKey']) {
                    $key = $item['name'];
                }
                $tableField[] = [
                    'field' => $item['name'],
                    'field_type' => $item['type'],
                    'primaryKey' => (bool)$item['primaryKey'],
                    'default' => $item['default'],
                    'limit' => $item['limit'],
                    'comment' => $item['comment'],
                    'required' => false,
                    'is_table' => false,
                    'table_name' => '',
                    'from_type' => '',
                ];
            }
        }

        $make = $this->services->makeFile($tableName, $routeName, false, [
            'menuName' => '',
            'key' => $key,
            'fromField' => [],
            'columnField' => [],
        ]);

        $makePath = [];
        foreach ($make as $k => $item) {
            $makePath[$k] = $item['path'];
        }

        return app('json')->success(compact('makePath', 'tableField'));
    }

    /**
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/12
     */
    public function read($id)
    {
        if (!$id) {
            return app('json')->fail(500035);
        }

        $info = $this->services->get($id);
        if (!$info) {
            return app('json')->fail(500043);
        }

        $routeName = 'crud/' . Str::snake($info->table_name);

        $column = $this->services->getColumnNamesList($info->table_name);
        $key = 'id';
        foreach ($column as $value) {
            if ($value['primaryKey']) {
                $key = $value['name'];
                break;
            }
        }

        $softDelete = false;

        foreach ((array)$info->field['tableField'] as $item) {
            if (isset($item['field_type']) && $item['field_type'] === 'addSoftDelete') {
                $softDelete = true;
                break;
            }
        }

        $make = $this->services->makeFile($info->table_name, $routeName, false, [
            'menuName' => $info->name,
            'modelName' => $info->model_name,
            'key' => $key,
            'softDelete' => $softDelete,
            'fromField' => $info->field['fromField'] ?? [],
            'columnField' => $info->field['columnField'] ?? [],
        ]);

        $data = [];
        foreach ($make as $item) {
            $item['name'] = $item['path'];
            $data[] = $item;
        }

        return app('json')->success($data);
    }

    /**
     * 获取tree菜单
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function getMenus()
    {
        return app('json')->success(app()->make(SystemMenusServices::class)
            ->getList(['auth_type' => 1, 'is_show' => 1], ['auth_type', 'pid', 'id', 'menu_name as label', 'id as value']));
    }

    /**
     * 获取创建表数据类型
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function columnType()
    {
        return app('json')->success($this->services->getTabelRule());
    }

    /**
     * @param SystemMenusServices $services
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function delete(SystemMenusServices $services, $id)
    {
        if (!$id) {
            return app('json')->fail(500035);
        }

        $info = $this->services->get($id);
        if (!$info) {
            return app('json')->fail(500042);
        }

        $services->transaction(function () use ($services, $info) {
            if ($info->menu_ids) {
                $services->deleteMenus($info->menu_ids);
            }

            $info->delete();
        });

        if ($info->make_path) {
            try {
                foreach ($info->make_path as $key => $item) {
                    if (in_array($key, ['pages', 'router', 'api'])) {
                        $item = Make::adminTemplatePath() . $item;
                    } else {
                        $item = app()->getRootPath() . $item;
                    }
                    unlink($item);
                }
            } catch (\Throwable $e) {
                return app('json')->success(500041, [], ['message' => $e->getMessage()]);
            }
        }


        return app('json')->success(100002);
    }

    /**
     * 下载文件
     * @param $id
     * @return Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/15
     */
    public function download($id)
    {
        if (!$id) {
            return app('json')->fail(500035);
        }

        $info = $this->services->get($id);
        if (!$info) {
            return app('json')->fail(500039);
        }
        $zipPath = app()->getRootPath() . 'backup' . DS . Str::camel($info->table_name);
        $zipName = app()->getRootPath() . 'backup' . DS . Str::camel($info->table_name) . '.zip';
        if (is_file($zipName)) {
            unlink($zipName);
        }
        $makePath = $info->make_path ?? [];
        foreach ($makePath as $key => $item) {
            if (in_array($key, ['pages', 'router', 'api'])) {
                $item = $zipPath . str_replace(dirname(app()->getRootPath()), '', Make::adminTemplatePath()) . $item;
            } else {
                $item = $zipPath . DS . $item;
            }
            $makePath[$key] = $item;
        }

        $routeName = 'crud/' . Str::snake($info->table_name);

        $column = $this->services->getColumnNamesList($info->table_name);
        $key = 'id';
        foreach ($column as $value) {
            if ($value['primaryKey']) {
                $key = $value['name'];
                break;
            }
        }

        $softDelete = false;

        foreach ((array)$info->field['tableField'] as $item) {
            if (isset($item['field_type']) && $item['field_type'] === 'addSoftDelete') {
                $softDelete = true;
                break;
            }
        }

        $this->services->makeFile($info->table_name, $routeName, true, [
            'menuName' => $info->name,
            'key' => $key,
            'softDelete' => $softDelete,
            'fromField' => $info->field['fromField'] ?? [],
            'columnField' => $info->field['columnField'] ?? [],
        ], $makePath, $zipPath);

        if (!extension_loaded('zip')) {
            return app('json')->fail(500040);
        }

        $fileService = new FileService();
        $fileService->addZip($zipPath, $zipName, app()->getRootPath() . 'backup');

        $key = md5($zipName);
        CacheService::set($key, [
            'path' => $zipName,
            'fileName' => Str::camel($info->table_name) . '.zip',
        ], 300);
        return app('json')->success(['download_url' => sys_config('site_url') . '/adminapi/download/' . $key]);
    }

    /**
     * 获取权限路由
     * @param $tableName
     * @return Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/20
     */
    public function getRouteList($tableName)
    {
        $info = $this->services->get(['table_name' => $tableName]);
        if (!$info) {
            return app('json')->fail('crud详情查询失败');
        }

        $routeList = app()->make(SystemMenusServices::class)->getColumn([
            ['id', 'in', $info->menu_ids],
            ['auth_type', '=', 2]
        ], 'methods,api_url');

        $newRoute = [];
        foreach ($routeList as $item) {
            if ($item['methods'] == 'GET') {
                if (strstr($item['api_url'], 'create')) {
                    $newRoute['create'] = $item['api_url'];
                } else if (strstr($item['api_url'], 'edit')) {
                    $newRoute['edit'] = $item['api_url'];
                } else {
                    $newRoute['index'] = $item['api_url'];
                }
            } else if ($item['methods'] == 'DELETE') {
                $newRoute['delete'] = $item['api_url'];
            }
        }

        $column = $this->services->getColumnNamesList($info->table_name);
        $key = 'id';
        foreach ($column as $value) {
            if ($value['primaryKey']) {
                $key = $value['name'];
                break;
            }
        }

        $columns = [
            [
                'title' => 'ID',
                'key' => $key,
                'from_type' => '',
            ]
        ];
        foreach ((array)$info->field['tableField'] as $item) {
            if (isset($item['is_table']) && $item['is_table']) {
                $columns[] = [
                    'title' => $item['table_name'] ?: $item['comment'],
                    'key' => $item['field'],
                    'from_type' => $item['from_type'],
                ];
            }
        }
        $route = $newRoute;
        return app('json')->success(compact('key', 'route', 'columns'));
    }

    /**
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/14
     */
    public function npm()
    {
        $terminal = new Terminal();

        $adminPath = $terminal->adminTemplatePath();

        $adminPath = dirname($adminPath);

        try {
            $dir = $adminPath . DS . 'node_modules';
            if (!is_dir($dir)) {
//                $terminal->run('npm-install');
            }

//            $res = $terminal->run('npm-build');
        } catch (\Throwable $e) {
            $terminal->echoOutputFlag($e->getMessage());
        }

        if (!is_dir($adminPath . DS . 'dist')) {
            echo Response::create([
                'message' => '打包失败',
            ], 'json')->getContent();
        }

        $build = public_path() . config('app.admin_prefix');

//        $this->app->make(FileService::class)->copyDir($adminPath . DS . 'dist', $build);

//        echo $res;
    }
}
