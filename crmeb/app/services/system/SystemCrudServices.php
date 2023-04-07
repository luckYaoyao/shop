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
use crmeb\services\crud\Model;
use crmeb\services\crud\Route;
use crmeb\services\crud\Service;
use crmeb\services\crud\Validate;
use crmeb\services\crud\ViewApi;
use crmeb\services\crud\ViewPages;
use crmeb\services\crud\ViewRouter;
use think\exception\ValidateException;
use think\facade\Db;
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

    public function getTabelRule()
    {
        $adapter = app()->make(MysqlAdapter::class);
        return [
            'types' => $adapter->getColumnTypes(),
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

        //TODO 没写完
        $routeName = '';
        $uniqueAuth = '';

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
        //TODO 没写完

        //记录crud生成
        $this->dao->save([
            'pid' => $data['pid'],
            'name' => $data['menuName'],
            'table_name' => $tableName,
            'field' => json_encode($data),
            'make_path' => json_encode($makePath),
            'add_time' => time()
        ]);
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
            'field' => $options['fromField']
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
                'path' => $controllerContent,
                'content' => $controllerPath
            ],
            'model' => [
                'path' => $modelPath,
                'content' => $modelContent
            ],
            'dao' => [
                'path' => $daoPath,
                'content' => $daoContent
            ],
            'route' => [
                'path' => $routePath,
                'content' => $routeContent
            ],
            'service' => [
                'path' => $servicePath,
                'content' => $serviceContent
            ],
            'validate' => [
                'path' => $validatePath,
                'content' => $validateContent
            ],
            'router' => [
                'path' => $routerPath,
                'content' => $routerContent
            ],
            'api' => [
                'path' => $apiPath,
                'content' => $apiContent
            ],
            'pages' => [
                'path' => $pagesPath,
                'content' => $pagesContent
            ],
        ];
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
