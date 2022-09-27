<?php

namespace app\services\system\lang;

use app\dao\system\lang\LangCountryDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;
use crmeb\services\FormBuilder as Form;
use think\facade\Route as Url;

class LangCountryServices extends BaseServices
{
    /**
     * @param LangCountryDao $dao
     */
    public function __construct(LangCountryDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 地区语言列表
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function LangCountryList(array $where = []): array
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList($where, '*', $page, $limit, 'id desc', true)->toArray();
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * 添加语言地区表单
     * @param $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCountryForm($id)
    {
        if ($id) $info = $this->dao->get($id);
        $field = [];
        $field[] = Form::input('name', '所属地区', $info['name'] ?? '')->required('请填写所属地区');
        $field[] = Form::input('code', '语言码', $info['code'] ?? '')->required('请填写语言码');
        return create_form($id ? '修改语言地区' : '新增语言地区', $field, Url::buildUrl('/setting/lang_country/save/' . $id), 'POST');
    }

    /**
     * 保存语言地区
     * @param $id
     * @param $typeId
     * @return bool
     */
    public function LangCountrySave($id, $data)
    {
        if ($id) {
            $res = $this->dao->update(['id' => $id], $data);
        } else {
            $res = $this->dao->save($data);
        }
        if (!$res) throw new AdminException(100007);
        return true;
    }

    /**
     * 删除语言地区
     * @param $id
     * @return bool
     */
    public function langCountryDel($id)
    {
        $res = $this->dao->delete($id);
        if (!$res) throw new AdminException(100008);
        return true;
    }
}