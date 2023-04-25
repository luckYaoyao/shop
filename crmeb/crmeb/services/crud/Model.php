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


/**
 * Class Model
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/3/13
 * @package crmeb\command\crud
 */
class Model extends Make
{
    /**
     * 当前命令名称
     * @var string
     */
    protected $name = "model";

    /**
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/4
     */
    protected function setBaseDir(): string
    {
        return 'app' . DS . 'model' . DS . 'crud';
    }


    /**
     * @param string $name
     * @param array $options
     * @return Model
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/12
     */
    public function handle(string $name, array $options = [])
    {
        $this->value['key'] = $options['key'] ?? 'id';
        if (isset($options['softDelete']) && $options['softDelete']) {
            $this->value['use-php'] = "use think\model\concern\SoftDelete;\n";
            $this->value['content-php'] = $this->tab() . "use SoftDelete;\n";
        }
        $this->value['modelName'] = $options['modelName'] ?? $name;
        return parent::handle($name, $options);
    }

    /**
     * @param string $path
     * @param string $name
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/12
     */
    protected function getFilePathName(string $path, string $name): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        return $this->getBasePath($path) . $name . '.' . $this->fileMime;
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
        return __DIR__ . DS . 'stubs' . DS . 'model' . DS . 'crudModel.stub';
    }
}
