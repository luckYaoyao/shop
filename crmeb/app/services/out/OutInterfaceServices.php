<?php

namespace app\services\out;

use app\dao\out\OutInterfaceDao;
use app\Request;
use app\services\BaseServices;
use crmeb\exceptions\AuthException;

class OutInterfaceServices extends BaseServices
{
    public function __construct(OutInterfaceDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 验证对外接口权限
     * @param Request $request
     * @return bool
     */
    public function verifyAuth(Request $request)
    {
        $rule = trim(strtolower($request->rule()->getRule()));
        $method = trim(strtolower($request->method()));
        $authList = $this->dao->getColumn([['id', 'in', $request->outInfo()['rules']]], 'method,url');
        $rolesAuth = [];
        foreach ($authList as $item) {
            $rolesAuth[trim(strtolower($item['method']))][] = trim(strtolower(str_replace(' ', '', $item['url'])));
        }
        if (in_array('/' . $rule, $rolesAuth[$method])) {
            return true;
        } else {
            throw new AuthException(110000);
        }
    }

    /**
     * 对外接口列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function outInterfaceList(): array
    {
        $list = $this->dao->selectList([], '*,name as title')->toArray();
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['pid'] == 0) {
                $data[] = $item;
                unset($list[$key]);
            }
        }
        foreach ($data as &$item_p) {
            foreach ($list as $item_c) {
                if ($item_p['id'] == $item_c['pid']) {
                    $item_p['children'][] = $item_c;
                }
            }
        }
        return $data;
    }
}