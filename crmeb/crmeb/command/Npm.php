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

namespace crmeb\command;


use think\console\Command;
use think\console\input\Argument;
use think\console\input\Option;

class Npm extends Command
{
    protected function configure()
    {
        $this->setName('npm')
            ->addOption('path', 'dp', Option::VALUE_OPTIONAL, '默认路径')
            ->addOption('build', 'bu', Option::VALUE_OPTIONAL, '打包存放路径')
            ->addOption('zip', 'z', Option::VALUE_NONE, '打包成zip')
            ->setDescription('NPM打包工具');
    }

    public function handle()
    {

    }
}
