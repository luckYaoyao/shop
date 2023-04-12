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

use think\App;
use think\helper\Str;

/**
 * Class ViewPages
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/1
 * @package crmeb\services\crud
 */
class ViewPages extends Make
{

    /**
     * @var string
     */
    protected $name = 'pages';

    /**
     * @var string
     */
    protected $fileMime = 'vue';

    /**
     * ViewPages constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->basePath = $this->adminTemplatePath;
    }

    /**
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/4
     */
    protected function setBaseDir(): string
    {
        return 'pages' . DS . 'crud';
    }

    /**
     * @param string $name
     * @param string $path
     * @param array $options
     * @return false|mixed|void
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/3
     */
    public function handle(string $name, array $options = [])
    {
        $field = $options['field'] ?? [];

        $columnStr = [];
        foreach ($field as $item) {
            $columnStr[] = $this->tab() . "{\ntitle:\"{$item['name']}\",\nkey:\"{$item['field']}\"\n}";
        }
        $this->value['auth'] = Str::snake($name);
        $this->value['content-vue'] = "\n" . implode(',', $columnStr);
        $this->value['pathApiJs'] = $options['pathApiJs'] ?? '';
        $this->value['nameCamel'] = Str::snake($name, '-');

        return parent::handle($name, $options);
    }

    /**
     * @param string $path
     * @param string $name
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/4
     */
    protected function getFilePathName(string $path, string $name): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        return $this->getBasePath($path) . $name . '.' . $this->fileMime;
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
        return __DIR__ . DS . 'stubs' . DS . 'view' .
            DS . 'pages' . DS . 'crud' .
            DS . 'index.stub';
    }
}
