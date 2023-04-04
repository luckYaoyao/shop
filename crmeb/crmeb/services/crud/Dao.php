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
namespace crmeb\command\crud;

use think\console\input\Option;

/**
 * Class Business
 * @package crmeb\command
 */
class Dao extends Make
{
    /**
     * 当前命令名称
     * @var string
     */
    protected $name = "dao";

    /**
     * 变量名称
     * @var string[]
     */
    protected $var = [
        '{%year%}',
        '{%time%}',
        '{%path%}',
        '{%name%}',
        '{%nameCamel%}',
        '{%date%}',
    ];

    /**
     * @var null[]
     */
    protected $value = [
        'year' => '',
        'time' => '',
        'path' => '',
        'name' => '',
        'nameCamel' => '',
        'date' => '',
    ];

    /**
     * 命令行配置
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function configure()
    {
        $this->preConfigure();
        $this->addOption('f', null, Option::VALUE_REQUIRED, 'dao层文件存放');
        $this->setDescription('CRUD创建dao层代码');
    }

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
