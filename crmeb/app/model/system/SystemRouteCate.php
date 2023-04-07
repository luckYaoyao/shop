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

namespace app\model\system;


use crmeb\basic\BaseModel;

class SystemRouteCate extends BaseModel
{

    /**
     * @var string
     */
    protected $name = 'system_route_cate';

    /**
     * @var string
     */
    protected $pk = 'id';

    /**
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     * @return \think\model\relation\HasMany
     */
    public function children()
    {
        return $this->hasMany(SystemRoute::class, 'id', 'cate_id')->field(['id', 'cate_id', 'name', 'path', 'method'])->order('add_time desc');
    }
}
