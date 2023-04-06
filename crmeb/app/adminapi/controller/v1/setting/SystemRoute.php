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
use app\services\system\SystemRouteServices;
use think\facade\App;

/**
 * Class SystemRoute
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\adminapi\controller\v1\setting
 */
class SystemRoute extends AuthController
{

    /**
     * SystemRoute constructor.
     * @param App $app
     * @param SystemRouteServices $services
     */
    public function __construct(App $app, SystemRouteServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 同步路由权限
     * @param $appName
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function syncRoute($appName)
    {
        $this->services->syncRoute($appName);
        return app('json')->success();
    }

    public function index()
    {

    }

    public function create()
    {

    }

    public function save()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }


}
