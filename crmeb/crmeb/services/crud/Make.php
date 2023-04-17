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
     * 文件全部路径
     * @var string
     */
    protected $filePathName = null;

    /**
     * @var string
     */
    protected $fileBasePath;

    /**
     * 文件内容
     * @var string
     */
    protected $content = '';

    /**
     * 文件存放
     * @var null
     */
    protected $filePath = null;

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
     * 文件存在是否生成
     * @var bool
     */
    protected $isExistsMake = true;

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
        $this->adminTemplatePath = self::adminTemplatePath();
        $this->basePath = $this->app->getRootPath();
        $this->baseDir = $this->setBaseDir();
        $this->var = $this->authDrawVar();
        $this->value = $this->drawValueKeys();
        $this->setDefaultValue();
    }

    /**
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/11
     */
    public static function adminTemplatePath()
    {
        return config('app.admin_template_path');
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
        $pathAttr = explode(DS, $path);
        $basePathAttr = explode(DS, $this->baseDir);
        //替换掉和基础目录相同的
        if (count($pathAttr) > 1) {
            $newsPath = array_merge(array_diff($basePathAttr, $pathAttr))[0] ?? '';
            if ($newsPath !== 'crud') {
                $path = $newsPath;
            } else {
                $this->baseDir = '';
            }
        }
        //多个斜杠的替换成一个
        $this->fileBasePath = str_replace(DS . DS, DS, $this->basePath . ($this->baseDir ? $this->baseDir . DS : '') . ($path ? $path . DS : ''));

        return $this->fileBasePath;
    }

    /**
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function getFileBasePath()
    {
        return $this->fileBasePath;
    }

    /**
     * 设置文件保存就路径名称
     * @param string $filePathName
     * @return $this
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function setFilePathName(string $filePathName = '')
    {
        if ($filePathName) {
            $this->filePathName = $filePathName;
        }
        return $this;
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
    public function handle(string $name, array $options = [])
    {
        $path = $options['path'] ?? '';
        [$nameData, $content] = $this->getStubContent($name);

        $this->value['name'] = $nameData;
        if (isset($this->value['nameCamel']) && !$this->value['nameCamel']) {
            $this->value['nameCamel'] = Str::studly($name);
        }
        if (isset($this->value['path'])) {
            $this->value['path'] = $this->getfolderPath($path);
        }
        if (isset($this->value['use-php']) && !empty($options['usePath'])) {
            $this->value['use-php'] = "use " . $options['usePath'] . ";\n";
        }

        $contentStr = str_replace($this->var, $this->value, $content);
        $filePath = $this->getFilePathName($path, $this->value['nameCamel']);

        return [
            $this->makeFile($filePath, $contentStr),
            $this->filePathName ?: $filePath,
            $this->baseDir . '\\' . $this->value['nameCamel']
        ];
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
        $path = $path ?: $this->filePathName;
        $path = str_replace([$this->basePath, $this->baseDir], '', $path);
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $pathArr = explode('/', $path);
        array_pop($pathArr);
        if ($pathArr) {
            return '\\' . implode('\\', $pathArr);
        } else {
            return '';
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


    public function make(string $pathname)
    {
        $pathname = $this->filePathName ?: ($this->filePath ?: $pathname);

        if ($this->isMake) {

            if (is_file($pathname)) {
                if ($this->isExistsMake) {
                    unlink($pathname);
                } else {
                    throw new CrudException(500052, ['filename' => $this->name . ':' . $pathname]);
                }
            }

            try {
                if (!is_dir(dirname($pathname))) {
                    mkdir(dirname($pathname), 0755, true);
                }
            } catch (\Throwable $e) {
                throw new CrudException(500050, ['pathname' => dirname($pathname)]);
            }

            try {
                file_put_contents($pathname, $this->content);
            } catch (\Throwable $e) {
                throw new CrudException(500051, ['pathname' => $pathname]);
            }
        }
    }

    /**
     * 设置内容
     * @param string $content
     * @return array|string|string[]
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/14
     */
    protected function setContent(string $content)
    {
        $this->content = str_replace('﻿', '', $content);

        return $this->content;
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
        $this->content = str_replace('﻿', '', $content);
        $this->make($pathname);
        return $this->content;
    }

    public function __destruct()
    {
        $this->content = '';
    }
}
