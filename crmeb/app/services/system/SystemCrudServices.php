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


use app\dao\system\SystemCrudDao;
use app\services\BaseServices;
use crmeb\services\crud\Controller;
use crmeb\services\crud\Dao;
use crmeb\services\crud\Make;
use crmeb\services\crud\Model;
use crmeb\services\crud\Route;
use crmeb\services\crud\Service;
use crmeb\services\crud\Validate;
use crmeb\services\crud\ViewApi;
use crmeb\services\crud\ViewPages;
use crmeb\services\crud\ViewRouter;
use think\exception\ValidateException;
use think\facade\Db;
use think\helper\Str;
use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

/**
 * Class SystemCrudServices
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\services\system
 */
class SystemCrudServices extends BaseServices
{

    /**
     * SystemCrudServices constructor.
     * @param SystemCrudDao $dao
     */
    public function __construct(SystemCrudDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function getList()
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList([], '*', $page, $limit, 'id desc');
        $count = $this->dao->count();

        return compact('list', 'count');
    }

    /**
     * 数据库字段类型
     * @return \string[][]
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function getTabelRule()
    {
        return [
            'types' => [
                'string',
                'char',
                'text',
                'integer',
                'biginteger',
                'float',
                'decimal',
                'datetime',
                'timestamp',
                'time',
                'date',
                'blob',
                'binary',
                'varbinary',
                'boolean',
                'uuid',
                // Geospatial data types
                'geometry',
                'point',
                'linestring',
                'polygon',
            ]
        ];
    }

    /**
     * 获取表字段
     * @param string $tableName
     * @return mixed
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function getColumnNamesList(string $tableName)
    {
        $sql = 'SELECT * FROM `information_schema`.`columns` WHERE TABLE_SCHEMA = ? AND table_name = ? ORDER BY ORDINAL_POSITION';

        $column = Db::query($sql, [config('database.connections.mysql.database'), $this->getTableName($tableName)]);

        $columns = [];
        foreach ($column as $item) {
            $column = [
                'name' => $item['COLUMN_NAME'],
                'type' => $item['DATA_TYPE'],
                'dataType' => stripos($item['COLUMN_TYPE'], '(') !== false ? substr_replace($item['COLUMN_TYPE'], '', stripos($item['COLUMN_TYPE'], ')') + 1) : $item['COLUMN_TYPE'],
                'default' => $item['COLUMN_DEFAULT'],
                'null' => $item['IS_NULLABLE'] == 'YES',
                'primaryKey' => $item['COLUMN_KEY'] == 'PRI',
                'unsigned' => (bool)stripos($item['COLUMN_TYPE'], 'unsigned'),
                'autoIncrement' => stripos($item['EXTRA'], 'auto_increment') !== false,
                'comment' => $item['COLUMN_COMMENT'],
            ];
            $columns[$item['COLUMN_NAME']] = $column;
        }

        return $columns;
    }

    /**
     * 创建
     * @param array $data
     * @return mixed
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public function createCrud(array $data)
    {
        $tableName = $data['tableName'];
        $tableComment = $data['tableComment'];
        $tableField = $data['tableField'];
        $filePath = $data['filePath'];

        //创建数据库
        if ($tableField) {
            $this->makeDatebase($tableName, $tableComment, $tableField);
        }

        //读取表结构
        $column = $this->getColumnNamesList($tableName);
        if (!$column) {
            throw new ValidateException('请先创建' . $tableName . '表');
        }

        $routeName = 'crud/' . Str::snake($tableName);
        $uniqueAuth = $routeName . '-index-list';

        $make = $this->makeFile($tableName, $routeName, true, $data, $filePath);
        $makePath = [];
        foreach ($make as $key => $item) {
            $makePath[$key] = $item['path'];
        }
        //创建菜单
        if (!$data['menuName']) {
            $data['menuName'] = $tableName;
        }
        $data = [
            'pid' => $data['pid'],
            'menu_name' => $data['menuName'],
            'menu_path' => '',
            'auth_type' => 1,
            'is_show' => 1,
            'is_del' => 0,
            'unique_auth' => $uniqueAuth,
            'is_header' => $data['pid'] ? 0 : 1,
        ];
        $menuInfo = app()->make(SystemMenusServices::class)->save($data);
        //写入路由权限
        $cateId = app()->make(SystemRouteServices::class)->topCateId('adminapi');
        $ruleData = [
            [
                'path' => $routeName,
                'method' => 'GET',
                'name' => $data['menuName'] . '列表接口',
                'app_name' => 'adminapi',
                'cate_id' => $cateId,
                'add_time' => date('Y-m-d H:i:s')
            ],
            [
                'path' => $routeName . '/create',
                'method' => 'GET',
                'name' => $data['menuName'] . '获取创建表单接口',
                'app_name' => 'adminapi',
                'cate_id' => $cateId,
                'add_time' => date('Y-m-d H:i:s')
            ],
            [
                'path' => $routeName,
                'method' => 'POST',
                'name' => $data['menuName'] . '保存数据接口',
                'app_name' => 'adminapi',
                'cate_id' => $cateId,
                'add_time' => date('Y-m-d H:i:s')
            ],
            [
                'path' => $routeName . '/<id>/edit',
                'method' => 'GET',
                'name' => $data['menuName'] . '获取修改表单接口',
                'app_name' => 'adminapi',
                'cate_id' => $cateId,
                'add_time' => date('Y-m-d H:i:s')
            ],
            [
                'path' => $routeName . '/<id>',
                'method' => 'PUT',
                'name' => $data['menuName'] . '修改数据接口',
                'app_name' => 'adminapi',
                'cate_id' => $cateId,
                'add_time' => date('Y-m-d H:i:s')
            ],
            [
                'path' => $routeName . '/<id>',
                'method' => 'DELETE',
                'name' => $data['menuName'] . '删除数据接口',
                'app_name' => 'adminapi',
                'cate_id' => $cateId,
                'add_time' => date('Y-m-d H:i:s')
            ],
        ];
        app()->make(SystemRouteServices::class)->saveAll($ruleData);
        //记录权限加入菜单表
        $menuData = [];
        foreach ($ruleData as $item) {
            $menuData[] = [
                'pid' => $menuInfo->id,
                'method' => $item['method'],
                'api_url' => $item['path'],
                'name' => $item['name'],
                'is_del' => 0,
            ];
        }
        app()->make(SystemMenusServices::class)->saveAll($menuData);
        //记录crud生成
        $res = $this->dao->save([
            'pid' => $data['pid'],
            'name' => $data['menuName'],
            'table_name' => $tableName,
            'field' => json_encode($data),
            'make_path' => json_encode($makePath),
            'add_time' => time()
        ]);
        return $res->toArray();
    }

    /**
     * 创建数据库
     * @param string $tableName
     * @param string $tableComment
     * @param array $tableField
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function makeDatebase(string $tableName, string $tableComment, array $tableField = [])
    {
        $migrator = app()->make(Migrator::class);
        //创建表
        $table = $migrator->table($tableName, $tableComment);
        //创建字段
        foreach ($tableField as $item) {
            $option = [];
            if (!isset($item['limit'])) {
                $option['limit'] = (int)$item['limit'];
            }
            if (!isset($item['default'])) {
                $option['default'] = $item['default'];
            }
            $option['comment'] = $item['comment'];
            $table->addColumn($item['field'], $item['type'], $option);
        }
        //创建修改和增加时间
        if (!empty($data['tableTime'])) {
            $table->addTimestamps();
        }
        //创建索引
        if (!empty($data['tableIndex'])) {
            foreach ($data['tableIndex'] as $item) {
                $table->addIndex($item);
            }
        }
        //创建伪删除
        if (!empty($data['tableDelete'])) {
            $table->addSoftDelete();
        }
        //执行创建
        $table->create();
    }

    /**
     * 创建文件返回文件路径和内容
     * @param string $tableName
     * @param string $routeName
     * @param bool $isMake
     * @param array $options
     * @param array $filePath
     * @return array[]
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function makeFile(string $tableName, string $routeName, bool $isMake = false, array $options = [], array $filePath = [])
    {
        $options['fromField'] = is_array($options['fromField']) ? $options['fromField'] : [];
        $options['columnField'] = is_array($options['columnField']) ? $options['columnField'] : [];
        //生成控制器
        $controller = app()->make(Controller::class);
        [$controllerContent, $controllerPath] = $controller->setFilePathName($filePath['controller'] ?? '')->isMake($isMake)->handle($tableName, '/');
        //生成模型
        $model = app()->make(Model::class);
        [$modelContent, $modelPath] = $model->setFilePathName($filePath['model'] ?? '')->isMake($isMake)->handle($tableName, '/');
        //生成dao
        $dao = app()->make(Dao::class);
        [$daoContent, $daoPath] = $dao->setFilePathName($filePath['dao'] ?? '')->isMake($isMake)->handle($tableName, '/');
        //生成路由
        $route = app()->make(Route::class);
        [$routeContent, $routePath] = $route->setFilePathName($filePath['route'] ?? '')->isMake($isMake)->handle($tableName, '/', [
            'menus' => $options['menuName'],
            'route' => $routeName
        ]);
        //生成service
        $service = app()->make(Service::class);
        [$serviceContent, $servicePath] = $service->setFilePathName($filePath['service'] ?? '')->isMake($isMake)->handle($tableName, '/', [
            'field' => $options['fromField'],
        ]);
        //生成验证器
        $validate = app()->make(Validate::class);
        [$validateContent, $validatePath] = $validate->setFilePathName($filePath['validate'] ?? '')->isMake($isMake)->handle($tableName, '/');
        //生成前台路由
        $viewRouter = app()->make(ViewRouter::class);
        [$routerContent, $routerPath] = $viewRouter->setFilePathName($filePath['router'] ?? '')->isMake($isMake)->handle($tableName, '/', [
            'route' => $routeName,
        ]);
        //生成前台接口
        $viewApi = app()->make(ViewApi::class);
        [$apiContent, $apiPath] = $viewApi->setFilePathName($filePath['api'] ?? '')->isMake($isMake)->handle($tableName, '/', [
            'route' => $routeName,
        ]);

        //生成前台页面
        $viewPages = app()->make(ViewPages::class);
        [$pagesContent, $pagesPath] = $viewPages->setFilePathName($filePath['pages'] ?? '')->isMake($isMake)->handle($tableName, '/', [
            'field' => $options['columnField']
        ]);

        return [
            'controller' => [
                'path' => $this->replace($controllerPath),
                'content' => $controllerContent
            ],
            'model' => [
                'path' => $this->replace($modelPath),
                'content' => $modelContent
            ],
            'dao' => [
                'path' => $this->replace($daoPath),
                'content' => $daoContent
            ],
            'route' => [
                'path' => $this->replace($routePath),
                'content' => $routeContent
            ],
            'service' => [
                'path' => $this->replace($servicePath),
                'content' => $serviceContent
            ],
            'validate' => [
                'path' => $this->replace($validatePath),
                'content' => $validateContent
            ],
            'router' => [
                'path' => $this->replace($routerPath),
                'content' => $routerContent
            ],
            'api' => [
                'path' => $this->replace($apiPath),
                'content' => $apiContent
            ],
            'pages' => [
                'path' => $this->replace($pagesPath),
                'content' => $pagesContent
            ],
        ];
    }

    protected function replace(string $path)
    {
        return str_replace([app()->getRootPath(), Make::adminTemplatePath()], '', $path);
    }

    /**
     * @param string $tableName
     * @param bool $fullName
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function getTableName(string $tableName, bool $fullName = true)
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        $pattern = '/^' . $tablePrefix . '/i';
        return ($fullName ? $tablePrefix : '') . (preg_replace($pattern, '', $tableName));
    }

}
