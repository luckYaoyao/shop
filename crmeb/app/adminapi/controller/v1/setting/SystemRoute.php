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

namespace app\adminapi\controller\v1\setting;


use app\adminapi\controller\AuthController;
use app\services\system\SystemRouteServices;
use think\facade\App;

/**
 * Class SystemRoute
 * @author 等风来
 * @email 136327134@qq.com
 * @date 2023/4/6
 * @package app\adminapi\controller\v1\setting
 */
class SystemRoute extends AuthController
{

    /**
     * SystemRoute constructor.
     * @param App $app
     * @param SystemRouteServices $services
     */
    public function __construct(App $app, SystemRouteServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 同步路由权限
     * @param string $appName
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/6
     */
    public function syncRoute(string $appName = 'adminapi')
    {
        $this->services->syncRoute($appName);
        return app('json')->success();
    }

    /**
     * 列表数据
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function index()
    {
        $where = $this->request->getMore([
            ['name_like', ''],
            ['app_name', 'adminapi']
        ]);

        return app('json')->success($this->services->getList($where));
    }

    /**
     * tree数据
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function tree()
    {
        $where = $this->request->getMore([
            ['name_like', ''],
            ['app_name', 'adminapi']
        ]);

        return app('json')->success($this->services->getTreeList($where));
    }


    /**
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function save($id = 0)
    {
        $data = $this->request->getMore([
            ['cate_id', 0],
            ['name', ''],
            ['path', ''],
            ['method', ''],
            ['type', 0],
            ['app_name', ''],
            ['request', []],
            ['response', []],
            ['request_example', []],
            ['response_example', []],
            ['describe', ''],
        ]);

        if (!$data['name']) {
            return app('json')->fail('请输入路由名称');
        }
        if (!$data['path']) {
            return app('json')->fail('请输入路由路径');
        }
        if (!$data['method']) {
            return app('json')->fail('请选择路由请求方式');
        }
        if (!$data['app_name']) {
            return app('json')->fail('缺少应用名参数');
        }
        if ($id) {
            $this->services->update($id, $data);
        } else {
            $this->services->save($data);
        }

        return app('json')->success($id ? '修改成功' : '添加成功');
    }

    /**
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function read($id)
    {
        return app('json')->success($this->services->getInfo((int)$id));
    }

    /**
     * @param $id
     * @return \think\Response
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/4/7
     */
    public function delete($id)
    {
        if (!$id) {
            return app('json')->fail('缺少参数');
        }

        $this->services->delete($id);

        return app('json')->success('删除成功');
    }


}
