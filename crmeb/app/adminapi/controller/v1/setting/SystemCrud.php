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
use crmeb\services\crud\Make;
use think\facade\App;
use think\helper\Str;

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
            ['tableComment', ''],//表备注
            ['tableField', []],//表字段
            ['tableIndex', []],//索引
            ['filePath', []],
            ['isTable', 0],
        ]);

        $fromField = $columnField = [];
        foreach ($data['tableField'] as $item) {
            if ($item['is_table']) {
                $columnField[] = [
                    'field' => $item['field'],
                    'name' => $item['table_name'],
                ];
            }
            if ($item['from_type']) {
                $fromField[] = [
                    'field' => $item['field'],
                    'type' => $item['from_type'],
                    'name' => $item['table_name'],
                    'required' => $item['required'],
                    'option' => $item['option'] ?? [],
                ];
            }
        }
        $data['fromField'] = $fromField;
        $data['columnField'] = $columnField;
        if (!$data['tableName']) {
            return app('json')->fail('缺少表名');
        }

        $this->services->createCrud($data);

        return app('json')->success('创建成功');
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
            return app('json')->fail('缺少表名');
        }

        if (in_array($tableName, SystemCrudServices::NOT_CRUD_TABANAME)) {
            return app('json')->fail('不能生成系统自带数据表');
        }

        $routeName = 'crud/' . Str::snake($tableName);

        $make = $this->services->makeFile($tableName, $routeName, false, [
            'menuName' => '',
            'fromField' => [],
            'columnField' => [],
        ]);

        $makePath = [];
        foreach ($make as $key => $item) {
            $makePath[$key] = $item['path'];
        }

        $tableField = [];
        if ($isTable) {
            $field = $this->services->getColumnNamesList($tableName);
            if (!$field) {
                return app('json')->fail('表不存在');
            }
            foreach ($field as $item) {
                $tableField[] = [
                    'field' => $item['name'],
                    'file_type' => $item['type'],
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
            return app('json')->fail('缺少参数');
        }

        $info = $this->services->get($id);
        if (!$info) {
            return app('json')->fail('查看的生成的文件不存在');
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

        $make = $this->services->makeFile($info->table_name, $routeName, false, [
            'menuName' => $info->name,
            'key' => $key,
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
            ->getList(['auth_type' => 1], ['pid', 'id', 'menu_name as label', 'id as value']));
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
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function delete(SystemMenusServices $services, $id)
    {
        if (!$id) {
            return app('json')->fail('缺少参数');
        }

        $info = $this->services->get($id);
        if (!$info) {
            return app('json')->fail('删除的数据不存在');
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
                return app('json')->success('删除生成的菜单和信息成功,删除文件出错，详细错误：' . $e->getMessage());
            }
        }


        return app('json')->success('删除成功');
    }
}
