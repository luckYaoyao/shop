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

    public function index()
    {

    }

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
    }


}
