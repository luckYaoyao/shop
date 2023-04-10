<?php

namespace app\services\system\log;

use app\dao\system\log\SystemFileInfoDao;
use app\services\BaseServices;

/**
 * @author 吴汐
 * @email 442384644@qq.com
 * @date 2023/04/07
 */
class SystemFileInfoServices extends BaseServices
{
    /**
     * 构造方法
     * SystemLogServices constructor.
     * @param SystemFileInfoDao $dao
     */
    public function __construct(SystemFileInfoDao $dao)
    {
        $this->dao = $dao;
    }
}