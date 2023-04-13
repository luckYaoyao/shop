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


use crmeb\services\FileService;
use crmeb\utils\Terminal;
use think\console\Command;
use think\console\input\Argument;
use think\console\input\Option;

/**
 * Class Npm
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/13
 * @package crmeb\command
 */
class Npm extends Command
{
    /**
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/13
     */
    protected function configure()
    {
        $this->setName('npm')
            ->addOption('path', 'dp', Option::VALUE_OPTIONAL, '默认路径')
            ->addOption('build', 'bu', Option::VALUE_OPTIONAL, '打包存放路径')
            ->addOption('zip', 'z', Option::VALUE_NONE, '打包成zip')
            ->setDescription('NPM打包工具');
    }

    /**
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/13
     */
    public function handle()
    {
        $path = $this->input->getOption('path');
        $build = $this->input->getOption('build');
        $zip = $this->input->getOption('zip');
        if (!$build) {
            $build = public_path() . 'admin';
        }

        $terminal = new Terminal();
        $terminal->setOutput($this->output);

        $adminPath = $terminal->adminTemplatePath();

        $adminPath = dirname($adminPath);
        $dir = $adminPath . DS . 'node_modules';
        if (!is_dir($dir)) {
            $terminal->run('npm-install');
        }

        $terminal->run('npm-build');

        if (!is_dir($adminPath . DS . 'dist')) {
            $this->output->error('打包失败');
            return;
        }

        FileService::copyDir($adminPath . 'dist', $build);

        $this->output->info('执行成功');
    }
}
