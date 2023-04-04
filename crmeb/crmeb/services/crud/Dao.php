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
namespace crmeb\services\crud;


/**
 * Class Business
 * @package crmeb\services
 */
class Dao extends Make
{
    /**
     * 当前命令名称
     * @var string
     */
    protected $name = "dao";

    /**
     * 模板文件
     * @param string $type
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getStub(string $type = '')
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'dao' . DIRECTORY_SEPARATOR . 'CrudDao.stub';
    }
}
