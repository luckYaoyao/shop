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

namespace crmeb\services\crud;

use crmeb\exceptions\CrudException;
use think\helper\Str;

/**
 * Class ViewApi
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/1
 * @package crmeb\services\crud
 */
class ViewApi extends Make
{

    /**
     * @var string
     */
    protected $name = 'api';

    public function handle(string $name, string $path, array $options = [])
    {

        $action = $options['action'] ?? [];
        if (!$action) {
            $action = ['index', 'create', 'save', 'edit', 'update'];
        }
        $route = $options['route'] ?? '';
        if (!$route) {
            throw new CrudException('缺少路由名称');
        }

        $contentJs = '';

        foreach ($action as $item) {
            $contentJs .= file_get_contents($this->getStub($item)) . "\n";
        }

        if ($contentJs) {
            $var = ['{%name%}', '{%route%}', '{%nameCamel%}'];
            $value = [$name, $route, Str::studly($name)];
            $contentJs = str_replace($var, $value, $contentJs);
        }

        $this->value['content-js'] = $contentJs;

        //生成api
        [$className, $content] = $this->getStubContent($name, $this->name);

        $contentStr = str_replace($this->var, $this->value, $content);

        $this->basePath = $this->adminTemplatePath;
        $this->fileMime = 'js';
        $filePath = $this->getFilePathName($path, $this->value['nameCamel']);

        $content = $this->makeFile($filePath, $contentStr);

        return [$content, $filePath];

    }

    /**
     * 模板文件配置
     * @param string $type
     * @return mixed
     */
    protected function getStub(string $type = 'api')
    {
        $servicePath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR;

        $stubs = [
            'index' => $servicePath . 'getCrudListApi.stub',
            'create' => $servicePath . 'CrudUpdate.stub',
            'save' => $servicePath . 'CrudSave.stub',
            'edit' => $servicePath . 'GetCrudForm.stub',
            'update' => $servicePath . 'CrudUpdate.stub',
            'api' => $servicePath . 'CrudService.stub',
        ];

        return $type ? $stubs[$type] : $stubs;
    }

}
