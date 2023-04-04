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
 * Class ViewRouter
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/1
 * @package crmeb\services\crud
 */
class ViewRouter extends Make
{
    /**
     * @var string
     */
    protected $name = 'router';


    public function handle(string $name, string $path, array $options = [])
    {
        $this->basePath = $this->adminTemplatePath;
        $this->fileMime = 'js';

        [$nameData, $content] = $this->getStubContent($name);

        $menus = $options['menus'] ?? $name;
        $route = $options['route'] ?? Str::snake($name);
        $pagePath = $options['$pagePath'] ?? Str::camel($name);
        if (!$route) {
            throw new CrudException('缺少路由名称');
        }
        $this->value['menus'] = $menus;
        $this->value['name'] = $nameData;
        $this->value['route'] = $route;
        $this->value['pagePath'] = $pagePath;
        if (isset($this->value['path'])) {
            $this->value['path'] = $this->getfolderPath($path);
        }

        $contentStr = str_replace($this->var, $this->value, $content);

        $filePath = $this->getFilePathName($path, Str::camel($name));

        $makeContent = $this->makeFile($filePath, $contentStr);

        dump($filePath);
        $pathInfo = pathinfo($filePath);
        dump($pathInfo);
        $router = '';

        return $makeContent;
    }

    /**
     * @param string $path
     * @param string $name
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/3
     */
    protected function getFilePathName(string $path, string $name): string
    {
        $path = str_replace(['app\\', 'app/'], '', $path);

        $path = ltrim(str_replace('\\', '/', $path), '/');

        return $this->basePath . $path . DIRECTORY_SEPARATOR . $name . '.' . $this->fileMime;
    }

    /**
     * @param string $type
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/1
     */
    protected function getStub(string $type = '')
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'view' .
            DIRECTORY_SEPARATOR . 'router' . DIRECTORY_SEPARATOR . 'modules' .
            DIRECTORY_SEPARATOR . 'crud.stub';
    }
}
