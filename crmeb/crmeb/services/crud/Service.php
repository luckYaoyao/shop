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
class Service extends Make
{
    /**
     * @var string
     */
    protected $name = "service";

    /**
     * @var string[]
     */
    protected $var = [
        '{%year%}',
        '{%time%}',
        '{%date%}',
        '{%nameCamel%}',
        '{%path%}',
        '{%content-php%}'
    ];

    /**
     * @var string[]
     */
    protected $value = [
        'year' => '',
        'time' => '',
        'date' => '',
        'nameCamel' => '',
        'path' => '',
        'content-php' => '',
        'dao-php' => ''
    ];

    /**
     * 配置指令
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function configure()
    {
        $this->preConfigure();
        $this->addOption('a', null, Option::VALUE_OPTIONAL, '需要的生成的方法，可选参数：index、form、save、update；多个生成用英文逗号隔开');
        $this->addOption('f', null, Option::VALUE_REQUIRED, 'service层文件存放');
        $this->addOption('k', null, Option::VALUE_OPTIONAL, '需要的生成获取的数据字段;多个生成用英文逗号隔开');
        $this->addOption('d', null, Option::VALUE_OPTIONAL, '关联dao层');
        $this->setDescription('CRUD创建service层代码');
    }

    public function handle()
    {
        $this->setDefaultValue();

        $name = $this->input->getArgument('name');
        $action = $this->input->getOption('a');
        $field = $this->input->getOption('k');
        $path = $this->input->getOption('f');

        if ($action) {
            $action = explode(',', $action);
        } else {
            $action = ['index', 'form', 'save', 'update'];
        }

        $fieldData = [];;
        if ($field) {
            $fieldData = json_decode($field, true);
            if ($fieldData === null && strstr($field, ',')) {
                $fieldData = explode(',', $field);
            }
        }

//        $from = [];
//        foreach ($fieldData as $item) {
//            $from[] = '';
//        }
        $axja = php_sapi_name();
        $this->writeln(compact('fieldData', 'axja'));
    }

    /**
     * @param string $type
     * @return mixed|string|string[]
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getStub(string $type = 'service')
    {
        $servicePath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'service' . DIRECTORY_SEPARATOR;

        $stubs = [
            'index' => $servicePath . 'CrudListIndex.stub',
            'form' => $servicePath . 'GetCrudForm.stub',
            'save' => $servicePath . 'CrudSave.stub',
            'update' => $servicePath . 'CrudUpdate.stub',
            'service' => $servicePath . 'CrudService.stub',
        ];

        return $type ? $stubs[$type] : $stubs;
    }
}
