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
}
