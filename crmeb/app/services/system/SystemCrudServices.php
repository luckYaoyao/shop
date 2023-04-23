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
use crmeb\exceptions\AdminException;
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
use crmeb\services\FileService;
use Phinx\Db\Adapter\AdapterFactory;
use think\facade\Db;
use think\helper\Str;
use think\migration\db\Table;

/**
 * Class SystemCrudServices
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\services\system
 */
class SystemCrudServices extends BaseServices
{

    //不能生成的系统自带表
    const NOT_CRUD_TABANAME = [
        'system_config', 'system_attachment', 'system_attachment_category', 'system_config_tab',
        'system_admin', 'eb_system_city', 'system_log', 'system_menus', 'system_notice',
        'system_notice_admin', 'system_notification', 'system_role', 'system_route',
        'system_route_cate', 'system_storage', 'system_timer', 'system_user_level',
        'system_crud', 'wechat_key', 'user_label_relation', 'user_brokerage_frozen',
        'user_brokerage', 'store_product_cate', 'store_bargain_user_help', 'shipping_templates_region',
        'shipping_templates_no_delivery', 'shipping_templates_free', 'other_order_status', 'lang_code',
        'lang_country', 'app_version', 'user', 'wechat_user', 'template_message', 'store_order', 'other_order',
        'store_order_cart_info', 'store_order_economize', 'store_order_invoice', 'store_order_refund',
        'store_order_status', 'store_pink'
    ];

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
        $list = $this->dao->selectList([], 'add_time,id,name,table_name,table_comment,table_collation', $page, $limit, 'id desc');
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
        $rule = [
            'varchar' => 'string',
            'int' => 'integer',
            'biginteger' => 'bigint',
            'tinyint' => 'boolean',
        ];
        return [
            'types' => [
                'varchar',
                'char',
                'text',
                'longtext',
                'tinytext',
                'enum',
                'blob',
                'binary',
                'varbinary',

                'datetime',
                'timestamp',
                'time',
                'date',
                'year',

                'boolean',
                'tinyint',
                'int',
                'decimal',
                'float',

                'json',

                'addTimestamps',
                'addSoftDelete',
            ],
            'rule' => $rule
        ];
    }

    /**
     * 改变数据库类型
     * @param string $type
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/13
     */
    public function changeTabelRule(string $type)
    {

        if (!in_array($type, $this->getTabelRule()['types'])) {
            throw new AdminException(500047);
        }

        return $this->getTabelRule()['rule'][$type] ?? $type;
    }

    /**
     * @param string $tableName
     * @return mixed
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/14
     */
    public function getTableInfo(string $tableName)
    {
        $sql = 'SELECT * FROM `information_schema`.`TABLES` WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?';

        $tableInfo = Db::query($sql, [config('database.connections.mysql.database'), $this->getTableName($tableName)]);

        return $tableInfo[0] ?? [];
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
                'limit' => $item['CHARACTER_MAXIMUM_LENGTH'] ?: $item['NUMERIC_PRECISION'],
            ];
            $columns[$item['COLUMN_NAME']] = $column;
        }

        return $columns;
    }

    /**
     * @param array $data
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/12
     */
    public function valueReplace(array $data)
    {
        $replace = ['phar://'];
        $newData = [];
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $item = $this->valueReplace($item);
            } else {
                $item = str_replace($replace, '', $item);
            }
            $newData[str_replace($replace, '', $key)] = $item;
        }
        return $newData;
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
        $tableComment = $data['tableComment'] ?? $data['menuName'];
        $tableField = $this->valueReplace($data['tableField']);
        $filePath = $this->valueReplace($data['filePath']);
        $modelName = $data['modelName'] ?? $data['menuName'] ?? $tableName;

        if ($this->dao->value(['table_name' => $tableName])) {
            throw new AdminException(500048);
        }

        $data['softDelete'] = false;
        //创建数据库
        if ($tableField && !$data['isTable']) {
            $tableCreateInfo = $this->makeDatebase($tableName, $tableComment, $tableField);
            if ($tableCreateInfo['softDelete']) {
                $data['softDelete'] = true;
            }
        }

        if (in_array($tableName, self::NOT_CRUD_TABANAME)) {
            throw new AdminException(500044);
        }

        //读取表结构
        $column = $this->getColumnNamesList($tableName);
        if (!$column) {
            throw new AdminException(500049, ['_name' => $tableName]);
        }

        $tableInfo = $this->getTableInfo($tableName);

        //获取主键
        foreach ($column as $value) {
            if ($value['primaryKey']) {
                $data['key'] = $value['name'];
                break;
            }
        }

        $routeName = 'crud/' . Str::snake($tableName);
        $uniqueAuth = Str::snake($tableName) . '-crud-list-index';
        //增加保存的绝对路径
        foreach ($filePath as $k => $i) {
            if (in_array($k, ['pages', 'router', 'api'])) {
                $filePath[$k] = Make::adminTemplatePath() . $i;
            } else {
                $filePath[$k] = app()->getRootPath() . $i;
            }
        }

        //创建菜单
        if (!$data['menuName']) {
            $data['menuName'] = $tableName;
        }
        $dataMenu = [
            'pid' => $data['pid'],
            'menu_name' => $data['menuName'],
            'menu_path' => '/' . $routeName,
            'auth_type' => 1,
            'is_show' => 1,
            'is_del' => 0,
            'unique_auth' => $uniqueAuth,
            'is_header' => $data['pid'] ? 0 : 1,
        ];

        $res = $this->transaction(function () use ($tableInfo, $modelName, $filePath, $tableName, $routeName, $data, $dataMenu) {
            $menuInfo = app()->make(SystemMenusServices::class)->save($dataMenu);
            //写入路由权限
            $cateId = app()->make(SystemRouteServices::class)->topCateId('adminapi');
            $ruleData = [
                [
                    'path' => $routeName,
                    'method' => 'GET',
                    'name' => $modelName . '列表接口',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/create',
                    'method' => 'GET',
                    'name' => $modelName . '获取创建表单接口',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => Str::snake($tableName) . '-add',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName,
                    'method' => 'POST',
                    'name' => $modelName . '保存数据接口',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/<id>/edit',
                    'method' => 'GET',
                    'name' => $modelName . '获取修改表单接口',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/<id>',
                    'method' => 'PUT',
                    'name' => $modelName . '修改数据接口',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
                [
                    'path' => $routeName . '/<id>',
                    'method' => 'DELETE',
                    'name' => $modelName . '删除数据接口',
                    'app_name' => 'adminapi',
                    'cate_id' => $cateId,
                    'unique_auth' => '',
                    'add_time' => date('Y-m-d H:i:s')
                ],
            ];
            app()->make(SystemRouteServices::class)->saveAll($ruleData);
            //记录权限加入菜单表
            $menuData = [];
            foreach ($ruleData as $item) {
                $menuData[] = [
                    'pid' => $menuInfo->id,
                    'methods' => $item['method'],
                    'api_url' => $item['path'],
                    'unique_auth' => $item['unique_auth'],
                    'menu_name' => $item['name'],
                    'is_del' => 0,
                    'auth_type' => 2,
                ];
            }
            $menus = app()->make(SystemMenusServices::class)->saveAll($menuData);
            $menuIds = array_column($menus->toArray(), 'id');
            array_push($menuIds, $menuInfo->id);
            //生成文件
            $make = $this->makeFile($tableName, $routeName, config('app.crud_make', false), $data, $filePath);
            $makePath = [];
            foreach ($make as $key => $item) {
                $makePath[$key] = $item['path'];
            }
            //记录crud生成
            $res = $this->dao->save([
                'pid' => $data['pid'],
                'name' => $data['menuName'],
                'model_name' => $data['modelName'],
                'table_name' => $tableName,
                'table_comment' => $tableInfo['TABLE_COMMENT'] ?? '',
                'table_collation' => $tableInfo['TABLE_COLLATION'] ?? '',
                'field' => json_encode($data),//提交的数据
                'menu_ids' => json_encode($menuIds),//生成的菜单id
                'make_path' => json_encode($makePath),
                'add_time' => time()
            ]);

            return $res;
        });

        return $res->toArray();
    }

    /**
     * 获取数据库配置
     * @return array
     */
    protected function getDbConfig(): array
    {
        $default = app()->config->get('database.default');

        $config = app()->config->get("database.connections.{$default}");

        if (0 == $config['deploy']) {
            $dbConfig = [
                'adapter' => $config['type'],
                'host' => $config['hostname'],
                'name' => $config['database'],
                'user' => $config['username'],
                'pass' => $config['password'],
                'port' => $config['hostport'],
                'charset' => $config['charset'],
                'table_prefix' => $config['prefix'],
            ];
        } else {
            $dbConfig = [
                'adapter' => explode(',', $config['type'])[0],
                'host' => explode(',', $config['hostname'])[0],
                'name' => explode(',', $config['database'])[0],
                'user' => explode(',', $config['username'])[0],
                'pass' => explode(',', $config['password'])[0],
                'port' => explode(',', $config['hostport'])[0],
                'charset' => explode(',', $config['charset'])[0],
                'table_prefix' => explode(',', $config['prefix'])[0],
            ];
        }

        $table = app()->config->get('database.migration_table', 'migrations');

        $dbConfig['default_migration_table'] = $dbConfig['table_prefix'] . $table;

        return $dbConfig;
    }

    public function getAdapter()
    {
        $options = $this->getDbConfig();

        $adapter = AdapterFactory::instance()->getAdapter($options['adapter'], $options);

        if ($adapter->hasOption('table_prefix') || $adapter->hasOption('table_suffix')) {
            $adapter = AdapterFactory::instance()->getWrapper('prefix', $adapter);
        }

        return $adapter;
    }

    /**
     * 创建数据库
     * @param string $tableName
     * @param string $tableComment
     * @param array $tableField
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function makeDatebase(string $tableName, string $tableComment, array $tableField = [])
    {
        $softDelete = false;
        $timestamps = false;
        $indexField = [];
        //创建表
        $table = new Table($tableName, ['comment' => $tableComment], $this->getAdapter());
        //创建字段
        foreach ($tableField as $item) {
            $option = [];
            if (isset($item['limit'])) {
                $option['limit'] = (int)$item['limit'];
            }
            if (isset($item['default'])) {
                $option['default'] = $item['default'];
            }
            //创建伪删除
            if ($item['field_type'] === 'addSoftDelete') {
                $table->addSoftDelete();
                $softDelete = true;
            } else if ($item['field_type'] === 'addTimestamps') {
                //创建修改和增加时间
                $table->addTimestamps();
                $timestamps = true;
            } else {
                $option['comment'] = $item['comment'];
                $table->addColumn($item['field'], $this->changeTabelRule($item['field_type']), $option);
            }
        }
        //创建索引
        if (!empty($data['tableIndex'])) {
            $indexField = $data['tableIndex'];
            foreach ($data['tableIndex'] as $item) {
                $table->addIndex($item);
            }
        }
        //执行创建
        $table->create();

        return compact('indexField', 'softDelete', 'timestamps');
    }

    /**
     * 创建文件返回文件路径和内容
     * @param string $tableName
     * @param string $routeName
     * @param bool $isMake
     * @param array $options
     * @param array $filePath
     * @param string $basePath
     * @return array[]
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function makeFile(string $tableName, string $routeName, bool $isMake = false, array $options = [], array $filePath = [], string $basePath = '')
    {
        $options['fromField'] = is_array($options['fromField']) ? $options['fromField'] : [];
        $options['columnField'] = is_array($options['columnField']) ? $options['columnField'] : [];
        //生成模型
        $model = app()->make(Model::class);
        $model->setFilePathName($filePath['model'] ?? '')->setbasePath($basePath)->handle($tableName, $options);
        //生成dao
        $dao = app()->make(Dao::class);
        $dao->setFilePathName($filePath['dao'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'usePath' => $model->getUsePath(),
        ]);
        //生成service
        $service = app()->make(Service::class);
        $service->setFilePathName($filePath['service'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'field' => $options['fromField'],
            'key' => $options['key'],
            'usePath' => $dao->getUsePath(),
            'modelName' => $options['modelName'] ?? '',
        ]);
        //生成验证器
        $validate = app()->make(Validate::class);
        $validate->setFilePathName($filePath['validate'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'field' => $options['fromField'],
        ]);
        //生成控制器
        $controller = app()->make(Controller::class);
        $controller->setFilePathName($filePath['controller'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'usePath' => $service->getUsePath(),
            'validateName' => '\\' . str_replace('/', '\\', $validate->getUsePath()) . 'Validate::class',
            'field' => array_column($options['fromField'], 'field'),
        ]);
        //生成路由
        $route = app()->make(Route::class);
        $route->setFilePathName($filePath['route'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'menus' => $options['modelName'] ?? $options['menuName'],
            'route' => $routeName
        ]);
        //生成前台路由
        $viewRouter = app()->make(ViewRouter::class);
        $viewRouter->setFilePathName($filePath['router'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'route' => $routeName,
            'menuName' => $options['menuName'],
        ]);
        //生成前台接口
        $viewApi = app()->make(ViewApi::class);
        $viewApi->setFilePathName($filePath['api'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'route' => $routeName,
        ]);
        //生成前台页面
        $viewPages = app()->make(ViewPages::class);
        $viewPages->setFilePathName($filePath['pages'] ?? '')->setbasePath($basePath)->handle($tableName, [
            'field' => $options['columnField'],
            'route' => $routeName,
            'key' => $options['key'],
            'pathApiJs' => '@/' . str_replace('\\', '/', str_replace([Make::adminTemplatePath(), '.js'], '', $viewApi->getPath())),
        ]);

        //创建文件
        if ($isMake) {
            FileService::batchMakeFiles([$model, $validate, $dao, $service, $controller, $route, $viewApi, $viewPages, $viewRouter]);
        }

        return [
            'controller' => [
                'path' => $this->replace($controller->getPath()),
                'content' => $controller->getContent()
            ],
            'model' => [
                'path' => $this->replace($model->getPath()),
                'content' => $model->getContent()
            ],
            'dao' => [
                'path' => $this->replace($dao->getPath()),
                'content' => $dao->getContent()
            ],
            'route' => [
                'path' => $this->replace($route->getPath()),
                'content' => $route->getContent()
            ],
            'service' => [
                'path' => $this->replace($service->getPath()),
                'content' => $service->getContent()
            ],
            'validate' => [
                'path' => $this->replace($validate->getPath()),
                'content' => $validate->getContent()
            ],
            'router' => [
                'path' => $this->replace($viewRouter->getPath()),
                'content' => $viewRouter->getContent()
            ],
            'api' => [
                'path' => $this->replace($viewApi->getPath()),
                'content' => $viewApi->getContent()
            ],
            'pages' => [
                'path' => $this->replace($viewPages->getPath()),
                'content' => $viewPages->getContent()
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
