<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
namespace app;

use crmeb\services\SystemConfigService;
use crmeb\services\GroupDataService;
use crmeb\utils\Json;
use think\Service;


/**
 * AppService类
 * 
 * @package App\Services
 */
class AppService extends Service
{

    /**
     * 定义服务绑定
     *
     * @var array
     */
    public $bind = [
        'json' => Json::class, // JSON服务绑定
        'sysConfig' => SystemConfigService::class, // 系统配置服务绑定
        'sysGroupData' => GroupDataService::class // 分组数据服务绑定
    ];
    /** 
     * 服务启动时的操作
     *
     * @return void
     */
    public function boot()
    {
        defined('DS') || define('DS', DIRECTORY_SEPARATOR); // 定义目录分隔符常量
    }
}

