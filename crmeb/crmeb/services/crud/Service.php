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

use think\helper\Str;

/**
 * Class Business
 * @package crmeb\services
 */
class Service extends Make
{
    /**
     * @var string
     */
    protected $name = "services";

    /**
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/4
     */
    protected function setBaseDir(): string
    {
        return 'app' . DS . 'services' . DS . 'crud';
    }

    /**
     * @param string $name
     * @param array $options
     * @return mixed|void
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/23
     */
    public function handle(string $name, array $options = [])
    {
        $path = $options['path'] ?? '';

        $this->value['use-php'] = $this->getDaoClassName($name, $path);

        $action = $options['action'] ?? [];
        $field = $options['field'] ?? [];

        if (!$action) {
            $action = ['index', 'form', 'save', 'update'];
        }

        $contentAction = '';
        foreach ($action as $item) {
            [$class, $stub] = $this->getStubContent($name, $item);
            $contentAction .= $stub . "\n";
        }

        //生成form表单
        if (in_array('save', $action) || in_array('update', $action)) {
            $var = ['{%date%}', '{%route%}', '{%form-php%}', '{%menus%}'];
            $value = [$this->value['date'], Str::snake($options['route'] ?? $name)];
            $from = [];
            foreach ($field as $item) {
                $from[] = $this->tab(2) . '$rule[] = FormBuilder::' . $item['type'] . '("' . $item['field'] . '","' . $item['name'] . '",$info["' . $item['field'] . '"] ?? "")' . $this->getOptionContent($item['option'] ?? []) . (!empty($item['required']) ? '->required()' : '') . ';';
            }
            if ($from) {
                $this->value['use-php'] .= "\n" . 'use crmeb\services\FormBuilder;';
                $value[] = implode("\n", $from);
            }
            $value[] = $options['menus'] ?? $name;

            if ($value && $var) {
                $contentAction = str_replace($var, $value, $contentAction);
            }
        }

        //生成service
        [$className, $content] = $this->getStubContent($name, $this->name);

        $this->value['nameCamel'] = Str::studly($name);
        $this->value['name'] = $className;
        $this->value['path'] = $this->getfolderPath($path);
        $this->value['content-php'] = $contentAction;

        $contentStr = str_replace($this->var, $this->value, $content);

        $filePath = $this->getFilePathName($path, $this->value['nameCamel']);

        return [
            $this->makeFile($filePath, $contentStr),
            $this->filePathName ?: $filePath,
            $this->baseDir . '\\' . $this->value['nameCamel']
        ];
    }

    /**
     * @param array $option
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/23
     */
    protected function getOptionContent(array $option = [])
    {
        $php = '';
        if ($option) {
            $res = var_export($option, true);

            $strOption = str_replace(['array', '(', ')'], ['', '[', ']'], $res);
            $php = '->options(' . $strOption . ')';
        }

        return $php;
    }

    /**
     * @param string $name
     * @param string $path
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/23
     */
    protected function getDaoClassName(string $name, string $path)
    {
        $path = str_replace(['app\\services', 'app/services'], '', $path);
        $path = ltrim(str_replace('\\', '/', $path), '/');
        return 'use app\dao\\' . ($path ? $path . '\\' : '') . Str::studly($name) . 'Dao;';
    }


    /**
     * @param string $type
     * @return mixed|string|string[]
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getStub(string $type = 'services')
    {
        $servicePath = __DIR__ . DS . 'stubs' . DS . 'service' . DS;

        $stubs = [
            'index' => $servicePath . 'CrudListIndex.stub',
            'form' => $servicePath . 'GetCrudForm.stub',
            'save' => $servicePath . 'CrudSave.stub',
            'update' => $servicePath . 'CrudUpdate.stub',
            'services' => $servicePath . 'CrudService.stub',
        ];

        return $type ? $stubs[$type] : $stubs;
    }
}
