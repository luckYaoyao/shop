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
     * 国家语言列表
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
     * 设置国家语言类型表单
     * @param $id
     * @return array
     * @throws \FormBuilder\Exception\FormBuilderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function LangCountryTypeForm($id)
    {
        if (!$id) throw new AdminException(100100);
        $info = $this->dao->get($id);
        if (!$info) throw new AdminException(100026);
        /** @var LangTypeServices $typeServices */
        $typeServices = app()->make(LangTypeServices::class);
        $typeList = $typeServices->getColumn(['status' => 1, 'is_del' => 0], 'language_name,file_name', 'id');
        $options[] = ['value' => 0, 'label' => '未定义'];
        foreach ($typeList as $item) {
            $options[] = ['value' => $item['id'], 'label' => $item['language_name'] . '(' . $item['file_name'] . ')'];
        }
        $field[] = Form::select('type', '选择语言类型', $info->type_id)->setOptions($options)->filterable(1);
        return create_form('设置语言类型', $field, Url::buildUrl('/setting/lang_country/save/' . $id), 'POST');
    }

    /**
     * 国家语言修改
     * @param $id
     * @param $typeId
     * @return bool
     */
    public function LangCountrySave($id, $typeId)
    {
        $res = $this->dao->update(['id' => $id], ['type_id' => $typeId]);
        if (!$res) throw new AdminException(100007);
        return true;
    }
}