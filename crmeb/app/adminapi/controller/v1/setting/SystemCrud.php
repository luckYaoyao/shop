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
            ['tableTime', 0],//表是否增加修改和添加时间
            ['tableDelete', 0],//表是否增加伪删除
            ['fromField', []],
            ['columnField', []],
            ['filePath', []],
        ]);

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
        [$menuName, $tableName, $fromField, $columnField] = $this->request->postMore([
            ['menuName', ''],
            ['tableName', ''],
            ['fromField', []],
            ['columnField', []],
        ], true);

        $routeName = 'crud/' . Str::snake($tableName);

        $make = $this->services->makeFile($tableName, $routeName, false, [
            'menuName' => $menuName,
            'fromField' => $fromField,
            'columnField' => $columnField,
        ]);

        $makePath = [];
        foreach ($make as $key => $item) {
            $makePath[$key] = $item['path'];
        }

        return app('json')->success($makePath);
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
    public function delete($id)
    {
        if (!$id) {
            return app('json')->fail('缺少参数');
        }

        $this->services->delete($id);

        return app('json')->success('删除成功');
    }
}
