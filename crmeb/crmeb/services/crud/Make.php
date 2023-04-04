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
use think\App;
use think\helper\Str;

/**
 * 创建crud基类
 * Class Make
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/3/13
 * @package crmeb\services\crud
 */
abstract class Make
{

    /**
     * 名称
     * @var string
     */
    protected $name = '';

    /**
     * 文件类型
     * @var string
     */
    protected $fileMime = 'php';

    /**
     * 变量名称
     * @var array
     */
    protected $var = [];

    /**
     * 内容
     * @var array
     */
    protected $value = [];

    /**
     * @var bool
     */
    protected $isMake = true;

    /**
     * 后台前端模板根路径
     * @var string
     */
    protected $adminTemplatePath;

    /**
     * 默认保存路径
     * @var string
     */
    protected $basePath;

    /**
     * 默认文件夹
     * @var string
     */
    protected $baseDir;

    /**
     * @var
     */
    protected $app;


    /**
     * Make constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->adminTemplatePath = dirname($this->app->getRootPath()) . DS . 'template' . DS . 'admin' . DS . 'src' . DS;
        $this->basePath = $this->app->getRootPath();
        $this->baseDir = $this->setBaseDir();
        $this->var = $this->authDrawVar();
        $this->value = $this->drawValueKeys();
        $this->setDefaultValue();
    }

    /**
     * 设置默认保存目录
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/4
     */
    protected function setBaseDir(): string
    {
        return 'crud';
    }

    /**
     * 获取保存文件的目录
     * @param string $path
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/4
     */
    protected function getBasePath(string $path = '')
    {
        //替换成本地路径格式
        $path = str_replace('/', DS, $path);
        //替换掉和基础目录相同的
        $path = str_replace($this->baseDir, '', $path);
        //多个斜杠的替换成一个
        return str_replace(DS . DS, DS, $this->basePath . $this->baseDir . DS . ($path ? $path . DS : ''));
    }

    /**
     * 生成tab
     * @param int $num
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/29
     */
    public function tab(int $num = 1): string
    {
        return str_pad('', 4 * $num);
    }

    /**
     * 是否生成文件
     * @param bool $isMake
     * @return $this
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/3
     */
    public function isMake(bool $isMake = true)
    {
        $this->isMake = $isMake;
        return $this;
    }

    /**
     * 执行创建
     * @return mixed|void
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    public function handle(string $name, string $path, array $options = [])
    {

        [$nameData, $content] = $this->getStubContent($name);

        $this->value['name'] = $nameData;
        if (isset($this->value['nameCamel']) && !$this->value['nameCamel']) {
            $this->value['nameCamel'] = Str::studly($name);
        }
        if (isset($this->value['path'])) {
            $this->value['path'] = $this->getfolderPath($path);
        }

        $contentStr = str_replace($this->var, $this->value, $content);

        $filePath = $this->getFilePathName($path, $this->value['nameCamel']);

        return [$this->makeFile($filePath, $contentStr), $filePath];
    }

    /**
     * 模板文件配置
     * @param string $type
     * @return mixed
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    abstract protected function getStub(string $type = '');

    /**
     * 自动获取模板变量
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/29
     */
    protected function authDrawVar(): array
    {
        $content = file_get_contents($this->getStub());
        $pattern = '/\{\%+[a-zA-Z0-9_-]+\%\}/';
        preg_match_all($pattern, $content, $var);
        $varData = $var[0] ?? [];
        $varData = array_unique($varData);
        return $varData;
    }

    /**
     * 提取value key
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/29
     */
    protected function drawValueKeys(): array
    {
        $data = [];
        foreach ($this->var as $value) {
            $data[str_replace(['{%', '%}'], '', $value)] = '';
        }
        return $data;
    }

    /**
     * 设置默认值
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function setDefaultValue()
    {
        if (isset($this->value['year'])) {
            $this->value['year'] = date('Y');
        }
        if (isset($this->value['time'])) {
            $this->value['time'] = date('Y/m/d H:i:s');
        }
        if (isset($this->value['date'])) {
            $this->value['date'] = date('Y/m/d');
        }
    }

    /**
     * 提取模板文件
     * @param string $name
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getStubContent(string $name, string $type = '')
    {
        $stub = file_get_contents($this->getStub($type));

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);

        return [$class, $stub];
    }

    /**
     * 获取文件路径
     * @param string $path
     * @param string $name
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getFilePathName(string $path, string $name): string
    {
        $path = str_replace(['app\\', 'app/'], '', $path);

        $path = ltrim(str_replace('\\', '/', $path), '/');

        return $this->getBasePath($path) . $name . ucwords($this->name) . '.' . $this->fileMime;
    }

    /**
     * @param string $path
     * @return mixed|string|null
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getfolderPath(string $path)
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $pathArr = explode('/', $path);
        $count = count($pathArr);
        $res = $pathArr[$count - 1] ?? null;
        if ($pathArr && $res && $res == $this->name) {
            return '';
        } else {
            return '\\' . $res;
        }
    }

    /**
     * 获取保存文件路径
     * @param string $name
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getPathName(string $name): string
    {
        $name = str_replace('app\\', '', $name);

        return $this->app->getBasePath() . ltrim(str_replace('\\', '/', $name), '/') . '.php';
    }

    /**
     * 获取类名
     * @param string $name
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getClassName(string $name): string
    {
        if (strpos($name, '\\') !== false) {
            return $name;
        }

        if (strpos($name, '@')) {
            [$app, $name] = explode('@', $name);
        } else {
            $app = '';
        }

        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->getNamespace($app) . '\\' . $name;
    }

    /**
     * 获取命名空间名
     * @param string $app
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function getNamespace(string $app): string
    {
        return 'app' . ($app ? '\\' . $app : '');
    }

    /**
     * 执行创建文件
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/3/13
     */
    protected function makeFile(string $pathname, string $content)
    {

        if (is_file($pathname)) {
            throw new CrudException($this->name . ':' . $pathname . ' already exists!');
        }

        $content = str_replace('﻿', '', $content);

        if ($this->isMake) {

            try {
                if (!is_dir(dirname($pathname))) {
                    mkdir(dirname($pathname), 0755, true);
                }
            } catch (\Throwable $e) {
                throw new CrudException('CRUD创建目录报错,无法创建:' . dirname($pathname));
            }

            try {
                file_put_contents($pathname, $content);
            } catch (\Throwable $e) {
                throw new CrudException('CRUD生成文件报错,无法写入:' . $pathname);
            }
        }

        return $content;
    }
}
