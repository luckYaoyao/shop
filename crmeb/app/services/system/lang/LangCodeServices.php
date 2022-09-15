<?php

namespace app\services\system\lang;

use app\dao\system\lang\LangCodeDao;
use app\services\BaseServices;
use crmeb\exceptions\AdminException;

class LangCodeServices extends BaseServices
{
    /**
     * @param LangCodeDao $dao
     */
    public function __construct(LangCodeDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 语言列表
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCodeList(array $where = [])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList($where, '*', $page, $limit, 'id desc', true)->toArray();
        /** @var LangTypeServices $langTypeServices */
        $langTypeServices = app()->make(LangTypeServices::class);
        $typeList = $langTypeServices->getColumn([['status', '=', 1], ['is_del', '=', 0]], 'language_name,file_name,id', 'id');
        $langType = [
            'isAdmin' => [
                ['title' => '用户端', 'value' => 0],
                ['title' => '管理端', 'value' => 1]
            ]
        ];
        foreach ($typeList as $value) {
            $langType['langType'][] = ['title' => $value['language_name'] . '(' . $value['file_name'] . ')', 'value' => $value['id']];
        }
        foreach ($list as &$item) {
            $item['language_name'] = $typeList[$item['type_id']]['language_name'] . '(' . $typeList[$item['type_id']]['file_name'] . ')';
        }
        $count = $this->dao->count($where);
        return compact('list', 'count', 'langType');
    }

    /**
     * 语言详情
     * @param $code
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function langCodeInfo($code)
    {
        $list = $this->dao->selectList(['code' => $code], '*', 0, 0, '', true)->toArray();
        if (!$list) throw new AdminException(100026);
        /** @var LangTypeServices $langTypeServices */
        $langTypeServices = app()->make(LangTypeServices::class);
        $typeList = $langTypeServices->getColumn([['status', '=', 1], ['is_del', '=', 0]], 'language_name,file_name,id', 'id');
        foreach ($list as &$item) {
            $item['language_name'] = $typeList[$item['type_id']]['language_name'] . '(' . $typeList[$item['type_id']]['file_name'] . ')';
        }
        $remarks = $list[0]['remarks'];
        return compact('list', 'code', 'remarks');
    }

    /**
     * 保存修改语言
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function langCodeSave($data)
    {
        $saveData = [];
        foreach ($data['list'] as $key => $item) {
            $saveData[$key] = [
                'code' => $data['code'],
                'remarks' => $data['remarks'],
                'lang_explain' => $item['lang_explain'],
                'type_id' => $item['type_id'],
                'is_admin' => $data['is_admin'],
            ];
            if (isset($item['id']) && $item['id'] != 0) {
                $saveData[$key]['id'] = $item['id'];
            }
        }
        $this->dao->saveAll($saveData);
        return true;
    }

    /**
     * 删除语言
     * @param $id
     * @return bool
     */
    public function langCodeDel($id)
    {
        $code = $this->dao->value(['id' => $id], 'code');
        $res = $this->dao->delete(['code' => $code]);
        if($res) return true;
        throw new AdminException(100008);
    }
}