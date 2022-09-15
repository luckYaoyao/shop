<?php

namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\services\system\lang\LangCountryServices;
use think\facade\App;

class LangCountry extends AuthController
{
    /**
     * @param App $app
     * @param LangCountryServices $services
     */
    public function __construct(App $app, LangCountryServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 国家语言列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCountryList()
    {
        $where = $this->request->getMore([
            ['keyword', ''],
        ]);
        return app('json')->success($this->services->langCountryList($where));
    }

    /**
     * 设置国家语言类型表单
     * @param $id
     * @return mixed
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCountryTypeForm($id)
    {
        return app('json')->success($this->services->LangCountryTypeForm($id));
    }

    /**
     * 国家语言修改
     * @param $id
     * @return mixed
     */
    public function langCountrySave($id)
    {
        [$typeId] = $this->request->postMore([
            ['type', 0],
        ], true);
        $this->services->langCountrySave($id, $typeId);
        return app('json')->success(100000);
    }
}