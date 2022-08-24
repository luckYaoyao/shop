<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace app\adminapi\controller;

use app\Request;
use app\services\order\StoreOrderCartInfoServices;
use app\services\order\StoreOrderCreateServices;
use app\services\order\StoreOrderRefundServices;
use app\services\order\StoreOrderServices;
use app\services\system\UpgradeServices;
use app\services\user\UserBillServices;
use app\services\user\UserBrokerageServices;
use app\services\user\UserMoneyServices;
use app\services\user\UserBrokerageFrozenServices;
use think\facade\Db;


class UpgradeController
{
    /**
     * @var UpgradeServices
     */
    private $services;

    /**
     * UpgradeController constructor.
     * @param UpgradeServices $services
     */
    public function __construct(UpgradeServices $services)
    {
        $this->services = $services;
    }

    /**
     * 升级程序页面
     * @param Request $request
     * @return \think\response\View
     */
    public function index(Request $request)
    {
        $data = $this->upData();
        $Title = "CRMEB升级程序";
        $Powered = "Powered by CRMEB";

        //获取当前版本号
        $version_now = $this->getversion('.version')['version'];
        $version_new = $data['new_version'];
        $isUpgrade = true;
        $executeIng = false;

        return view('/upgrade/step1', [
            'title' => $Title,
            'powered' => $Powered,
            'version_now' => $version_now,
            'version_new' => $version_new,
            'isUpgrade' => json_encode($isUpgrade),
            'executeIng' => json_encode($executeIng),
            'next' => 1,
            'action' => 'upgrade'
        ]);
    }

    /**
     * 获取当前版本号
     * @return array
     */
    public function getversion($str)
    {
        $version_arr = [];
        $curent_version = @file(app()->getRootPath() . $str);

        foreach ($curent_version as $val) {
            list($k, $v) = explode('=', $val);
            $version_arr[$k] = $v;
        }
        return $version_arr;
    }

    /**
     * 写入升级过程
     * @param string $field
     * @param int $n
     * @return bool
     */
    public function setIsUpgrade(string $field, int $n = 0)
    {
        $upgrade = parse_ini_file(public_path('upgrade') . '.upgrade');
        if ($n) {
            if (!is_array($upgrade)) {
                $upgrade = [];
            }
            $string = '';
            foreach ($upgrade as $key => $item) {
                $string .= $key . '=' . $item . "\r\n";
            }
            $string .= $field . '=' . $n . "\r\n";
            file_put_contents(public_path('upgrade') . '.upgrade', $string);
            return true;
        } else {
            if (!is_array($upgrade)) {
                return false;
            }
            return isset($upgrade[$field]);
        }
    }

    public function upgrade(Request $request)
    {
        list($sleep, $page, $prefix) = $request->getMore([
            ['sleep', 0],
            ['page', 1],
            ['prefix', 'eb_'],
        ], true);
        $data = $this->upData();
        $code_now = $this->getversion('.version')['version_code'];
        if ($data['new_code'] == $code_now) {
            return app('json')->success(['sleep' => -1]);
        }
        $sql_arr = [];
        foreach ($data['update_sql'] as $items) {
            if ($items['code'] > $code_now) {
                $sql_arr[] = $items;
            }
        }
        //sql 执行完成，开始执行修改数据
        if (!isset($sql_arr[$sleep])) {
//            $limit = 100;
//            if (!$this->setIsUpgrade('money')) {
//                $res = $this->handleMoney((int)$sleep, (int)$page, (int)$limit);
//                return app('json')->success($res);
//            } elseif (!$this->setIsUpgrade('brokerage')) {
//                $res = $this->handleBrokerage((int)$sleep, (int)$page, (int)$limit);
//                return app('json')->success($res);
//            } elseif (!$this->setIsUpgrade('orderRefund')) {
//                $res = $this->handleOrderRefund((int)$sleep, (int)$page, (int)$limit);
//                return app('json')->success($res);
//            } else {
                file_put_contents(app()->getRootPath() . '.version', "version=" . $data['new_version'] . "\nversion_code=" . $data['new_code']);
                return app('json')->success(['sleep' => -1]);
//            }
        }
        $sql = $sql_arr[$sleep];
        Db::startTrans();
        try {
            if ($sql['type'] == 1) {
                if (isset($sql['findSql']) && $sql['findSql']) {
                    $table = $prefix . $sql['table'];
                    $findSql = str_replace('@table', $table, $sql['findSql']);
                    if (!empty(Db::query($findSql))) {
                        $item['table'] = $table;
                        $item['status'] = 1;
                        $item['error'] = $table . '表已存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
                if (isset($sql['sql']) && $sql['sql']) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $table;
                    $item['status'] = 1;
                    $item['error'] = $table . '表添加成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->success($item);
                }
            } elseif ($sql['type'] == 2) {
                if (isset($sql['findSql']) && $sql['findSql']) {
                    $table = $prefix . $sql['table'];
                    $findSql = str_replace('@table', $table, $sql['findSql']);
                    if (empty(Db::query($findSql))) {
                        $item['table'] = $table;
                        $item['status'] = 1;
                        $item['error'] = $table . '表不存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
                if (isset($sql['sql']) && $sql['sql']) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $table;
                    $item['status'] = 1;
                    $item['error'] = $table . '表删除成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->success($item);
                }
            } elseif ($sql['type'] == 3) {
                if (isset($sql['findSql']) && $sql['findSql']) {
                    $table = $prefix . $sql['table'];
                    $findSql = str_replace('@table', $table, $sql['findSql']);
                    if (!empty(Db::query($findSql))) {
                        $item['table'] = $table;
                        $item['status'] = 1;
                        $item['error'] = $table . '表中' . $sql['field'] . '已存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
                if (isset($sql['sql']) && $sql['sql']) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $table;
                    $item['status'] = 1;
                    $item['error'] = $table . '表中' . $sql['field'] . '字段添加成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->success($item);
                }
            } elseif ($sql['type'] == 4) {
                if (isset($sql['findSql']) && $sql['findSql']) {
                    $table = $prefix . $sql['table'];
                    $findSql = str_replace('@table', $table, $sql['findSql']);
                    if (empty(Db::query($findSql))) {
                        $item['table'] = $table;
                        $item['status'] = 1;
                        $item['error'] = $table . '表中' . $sql['field'] . '不存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
                if (isset($sql['sql']) && $sql['sql']) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $table;
                    $item['status'] = 1;
                    $item['error'] = $table . '表中' . $sql['field'] . '字段修改成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->success($item);
                }
            } elseif ($sql['type'] == 5) {
                if (isset($sql['findSql']) && $sql['findSql']) {
                    $table = $prefix . $sql['table'];
                    $findSql = str_replace('@table', $table, $sql['findSql']);
                    if (empty(Db::query($findSql))) {
                        $item['table'] = $table;
                        $item['status'] = 1;
                        $item['error'] = $table . '表中' . $sql['field'] . '不存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
                if (isset($sql['sql']) && $sql['sql']) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    Db::execute($upSql);
                    $item['table'] = $table;
                    $item['status'] = 1;
                    $item['error'] = $table . '表中' . $sql['field'] . '字段删除成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->success($item);
                }
            } elseif ($sql['type'] == 6) {
                $table = $prefix . $sql['table'] ?? '';
                $whereTable = $prefix . $sql['whereTable'] ?? '';
                if (isset($sql['findSql']) && $sql['findSql']) {
                    $findSql = str_replace('@table', $table, $sql['findSql']);
                    if (!empty(Db::query($findSql))) {
                        $item['table'] = $prefix . $sql['table'];
                        $item['status'] = 1;
                        $item['error'] = $table . '表中此数据已存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
                if (isset($sql['sql']) && $sql['sql']) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    if (isset($sql['whereSql']) && $sql['whereSql']) {
                        $whereSql = str_replace('@whereTable', $whereTable, $sql['whereSql']);
                        $tabId = Db::query($whereSql)[0]['tabId'] ?? 0;
                        if (!$tabId) {
                            $item['table'] = $whereTable;
                            $item['status'] = 1;
                            $item['error'] = '查询父类ID不存在';
                            $item['sleep'] = $sleep + 1;
                            $item['add_time'] = date('Y-m-d H:i:s', time());
                            Db::commit();
                            return app('json')->success($item);
                        }
                        $upSql = str_replace('@tabId', $tabId, $upSql);
                    }
                    if (Db::execute($upSql)) {
                        $item['table'] = $table;
                        $item['status'] = 1;
                        $item['error'] = '数据添加成功';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
            } elseif ($sql['type'] == 7) {
                $table = $prefix . $sql['table'] ?? '';
                $whereTable = $prefix . $sql['whereTable'] ?? '';
                if (isset($sql['findSql']) && $sql['findSql']) {
                    $findSql = str_replace('@table', $table, $sql['findSql']);
                    if (empty(Db::query($findSql))) {
                        $item['table'] = $prefix . $sql['table'];
                        $item['status'] = 1;
                        $item['error'] = $table . '表中此数据不存在';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
                if (isset($sql['sql']) && $sql['sql']) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    if (isset($sql['whereSql']) && $sql['whereSql']) {
                        $whereSql = str_replace('@whereTable', $whereTable, $sql['whereSql']);
                        $tabId = Db::query($whereSql)[0]['tabId'] ?? 0;
                        if (!$tabId) {
                            $item['table'] = $whereTable;
                            $item['status'] = 1;
                            $item['error'] = '查询父类ID不存在';
                            $item['sleep'] = $sleep + 1;
                            $item['add_time'] = date('Y-m-d H:i:s', time());
                            Db::commit();
                            return app('json')->success($item);
                        }
                        $upSql = str_replace('@tabId', $tabId, $upSql);
                    }
                    if (Db::execute($upSql)) {
                        $item['table'] = $table;
                        $item['status'] = 1;
                        $item['error'] = '数据修改成功';
                        $item['sleep'] = $sleep + 1;
                        $item['add_time'] = date('Y-m-d H:i:s', time());
                        Db::commit();
                        return app('json')->success($item);
                    }
                }
            } elseif ($sql['type'] == 8) {

            } elseif ($sql['type'] == -1) {
                $table = $prefix . $sql['table'];
                if (isset($sql['sql']) && $sql['sql']) {
                    $upSql = $sql['sql'];
                    $upSql = str_replace('@table', $table, $upSql);
                    if (isset($sql['new_table']) && $sql['new_table']) {
                        $new_table = $prefix . $sql['new_table'];
                        $upSql = str_replace('@new_table', $new_table, $upSql);
                    }
                    Db::execute($upSql);
                    $item['table'] = $table;
                    $item['status'] = 1;
                    $item['error'] = $table . ' sql执行成功';
                    $item['sleep'] = $sleep + 1;
                    $item['add_time'] = date('Y-m-d H:i:s', time());
                    Db::commit();
                    return app('json')->success($item);
                }
            }
        } catch (\Throwable $e) {
            $item['table'] = $prefix . $sql['table'];
            $item['status'] = 0;
            $item['sleep'] = $sleep + 1;
            $item['add_time'] = date('Y-m-d H:i:s', time());
            $item['error'] = $e->getMessage();
            Db::rollBack();
            return app('json')->success($item);
        }
    }

    /**
     * 处理历史余额数据
     * @param int $sleep
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function handleMoney(int $sleep = 1, int $page = 1, int $limit = 100)
    {
        /** @var UserBillServices $userBillServics */
        $userBillServics = app()->make(UserBillServices::class);
        $where = ['category' => 'now_money', 'type' => ['pay_product', 'pay_product_refund', 'system_add', 'system_sub', 'recharge', 'lottery_use', 'lottery_add']];
        $list = $userBillServics->getList($where, '*', $page, $limit, [], 'id asc');
        if ($list) {
            $allData = $data = [];
            foreach ($list as $item) {
                $data = [
                    'uid' => $item['uid'],
                    'link_id' => $item['link_id'],
                    'pm' => $item['pm'],
                    'title' => $item['title'],
                    'type' => $item['type'],
                    'number' => $item['number'],
                    'balance' => $item['balance'],
                    'mark' => $item['mark'],
                    'add_time' => strtotime($item['add_time']),
                ];
                $allData[] = $data;
            }
            if ($allData) {
                /** @var UserMoneyServices $userMoneyServices */
                $userMoneyServices = app()->make(UserMoneyServices::class);
                $userMoneyServices->saveAll($allData);
            }
            $info['table'] = 'user_money';
            $info['status'] = 1;
            $info['error'] = '余额数据更新成功';
            $info['sleep'] = $sleep + 1;
            $info['page'] = $page + 1;
            $info['add_time'] = date('Y-m-d H:i:s', time());
            return $info;
        } else {
            $this->setIsUpgrade('money', 1);
            $info['table'] = 'user_money';
            $info['status'] = 1;
            $info['error'] = '余额数据更新成功';
            $info['sleep'] = $sleep + 1;
            $info['page'] = 1;
            $info['add_time'] = date('Y-m-d H:i:s', time());
            return $info;
        }
    }

    /**
     * 处理历史佣金数据
     * @param int $sleep
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function handleBrokerage(int $sleep = 1, int $page = 1, int $limit = 100)
    {
        /** @var UserBillServices $userBillServics */
        $userBillServics = app()->make(UserBillServices::class);
        $where = ['category' => ['', 'now_money'], 'type' => ['brokerage', 'brokerage_user', 'extract', 'refund', 'extract_fail']];
        $list = $userBillServics->getList($where, '*', $page, $limit, [], 'id asc');
        if ($list) {
            $allData = $data = [];
            /** @var  $brokerageFrozenServices */
            $brokerageFrozenServices = app()->make(UserBrokerageFrozenServices::class);
            $frozenList = $brokerageFrozenServices->getColumn([['uill_id', 'in', array_column($list, 'id')], ['frozen_time', '>', time()]], 'uill_id,frozen_time', 'uill_id');
            foreach ($list as $item) {
                if (in_array($item['type'], ['brokerage_user', 'extract', 'refund', 'extract_fail'])) {
                    $type = $item['type'];
                } else {
                    if (strpos($item['mark'], '二级')) {
                        $type = 'two_brokerage';
                    } else {
                        $type = 'one_brokerage';
                    }
                }
                $data = [
                    'uid' => $item['uid'],
                    'link_id' => $item['link_id'],
                    'pm' => $item['pm'],
                    'title' => $item['title'],
                    'type' => $type,
                    'number' => $item['number'],
                    'balance' => $item['balance'],
                    'mark' => $item['mark'],
                    'frozen_time' => $frozenList[$item['id']]['frozen_time'] ?? 0,
                    'add_time' => strtotime($item['add_time']),
                ];
                $allData[] = $data;
            }
            if ($allData) {
                /** @var UserBrokerageServices $userBrokerageServices */
                $userBrokerageServices = app()->make(UserBrokerageServices::class);
                $userBrokerageServices->saveAll($allData);
            }
            $info['table'] = 'user_brokerage';
            $info['status'] = 1;
            $info['error'] = '佣金数据更新成功';
            $info['sleep'] = $sleep + 1;
            $info['page'] = $page + 1;
            $info['add_time'] = date('Y-m-d H:i:s', time());
            return $info;
        } else {
            $this->setIsUpgrade('brokerage', 1);
            $info['table'] = 'user_brokerage';
            $info['status'] = 1;
            $info['error'] = '佣金数据更新成功';
            $info['sleep'] = $sleep + 1;
            $info['page'] = 1;
            $info['add_time'] = date('Y-m-d H:i:s', time());
            return $info;
        }
    }

    /**
     * 处理历史退款数据
     * @param int $sleep
     * @param int $page
     * @param int $limit
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handleOrderRefund(int $sleep = 1, int $page = 1, int $limit = 100)
    {
        /** @var StoreOrderServices $storeOrderServices */
        $storeOrderServices = app()->make(StoreOrderServices::class);
        $list = $storeOrderServices->getSplitOrderList(['refund_status' => [1, 2], ['refund_type' => [1, 2, 4, 5, 6]]], ['*'], [], $page, $limit, 'id asc');
        $allData = $refundOrderData = [];
        if ($list) {
            /** @var StoreOrderCreateServices $storeOrderCreateServices */
            $storeOrderCreateServices = app()->make(StoreOrderCreateServices::class);
            /** @var StoreOrderCartInfoServices $storeOrderCartInfoServices */
            $storeOrderCartInfoServices = app()->make(StoreOrderCartInfoServices::class);
            $time = time();
            foreach ($list as $order) {
                //生成退款订单
                $refundOrderData['uid'] = $order['uid'];
                $refundOrderData['store_id'] = $order['store_id'];
                $refundOrderData['store_order_id'] = $order['id'];
                $refundOrderData['order_id'] = $storeOrderCreateServices->getNewOrderId('');
                $refundOrderData['refund_num'] = $order['total_num'];
                $refundOrderData['refund_type'] = $order['refund_type'];
                $refundOrderData['refund_price'] = $order['pay_price'];
                $refundOrderData['refunded_price'] = 0;
                $refundOrderData['refund_explain'] = $order['refund_reason_wap_explain'];
                $refundOrderData['refund_img'] = $order['refund_reason_wap_img'];
                $refundOrderData['refund_reason'] = $order['refund_reason_wap'];
                $refundOrderData['refund_express'] = $order['refund_express'];
                $refundOrderData['refunded_time'] = $order['refund_type'] == 6 ? $order['refund_reason_time'] : 0;
                $refundOrderData['add_time'] = $order['refund_reason_time'];
                $cartInfos = $storeOrderCartInfoServices->getCartColunm(['oid' => $order['id']], 'id,cart_id,cart_num,cart_info');
                foreach ($cartInfos as &$cartInfo) {
                    $cartInfo['cart_info'] = is_string($cartInfo['cart_info']) ? json_decode($cartInfo['cart_info'], true) : $cartInfo['cart_info'];
                }
                $refundOrderData['cart_info'] = json_encode(array_column($cartInfos, 'cart_info'));
                $allData[] = $refundOrderData;
            }
            if ($allData) {
                /** @var StoreOrderRefundServices $storeOrderRefundServices */
                $storeOrderRefundServices = app()->make(StoreOrderRefundServices::class);
                $storeOrderRefundServices->saveAll($allData);
            }
            $info['table'] = 'store_order_refund';
            $info['status'] = 1;
            $info['error'] = '退款数据更新成功';
            $info['sleep'] = $sleep + 1;
            $info['page'] = $page + 1;
            $info['add_time'] = date('Y-m-d H:i:s', time());
            return $info;
        } else {
            $this->setIsUpgrade('orderRefund', 1);
            $info['table'] = 'store_order_refund';
            $info['status'] = 1;
            $info['error'] = '退款数据更新成功';
            $info['sleep'] = $sleep + 1;
            $info['page'] = 1;
            $info['add_time'] = date('Y-m-d H:i:s', time());
            return $info;
        }
    }


    /**
     * 升级数据
     * @return mixed
     */
    public function upData()
    {
        $data['new_version'] = 'CRMEB-BZ v4.4.4';
        $data['new_code'] = 444;
        $data['update_sql'] = [
            [
                'code' => 440,
                'type' => 1,
                'table' => "capital_flow",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `flow_id` varchar(32) NOT NULL DEFAULT '' COMMENT '流水id',
  `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '关联id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '电话',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '交易金额',
  `trading_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '交易类型',
  `pay_type` varchar(32) NOT NULL DEFAULT '' COMMENT '支付类型',
  `mark` varchar(500) NOT NULL DEFAULT '' COMMENT '备注',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '交易时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金流水表';"
            ],
            [
                'code' => 440,
                'type' => 1,
                'table' => "division_agent_apply",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `agent_name` varchar(255) NOT NULL DEFAULT '' COMMENT '代理商名称',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名称',
  `phone` varchar(32) NOT NULL DEFAULT '0' COMMENT '代理商电话',
  `division_id` int(11) NOT NULL DEFAULT '0' COMMENT '事业部id',
  `division_invite` int(11) NOT NULL DEFAULT '0' COMMENT '邀请码',
  `images` varchar(2000) NOT NULL DEFAULT '' COMMENT '申请图片',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '申请时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '申请状态0申请，1同意，2拒绝',
  `refusal_reason` varchar(1000) NOT NULL DEFAULT '' COMMENT '拒绝理由',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商申请表';"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "diy",
                'sql' => "ALTER TABLE `@table` MODIFY COLUMN `value` longtext"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "diy",
                'sql' => "ALTER TABLE `@table` MODIFY COLUMN `default_value` longtext"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "diy",
                'field' => "is_diy",
                'findSql' => "show columns from `@table` like 'is_diy'",
                'sql' => "ALTER TABLE `@table` ADD `is_diy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否diy'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "diy",
                'field' => "title",
                'findSql' => "show columns from `@table` like 'title'",
                'sql' => "ALTER TABLE `@table` ADD `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'diy顶部title'"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "diy",
                'sql' => <<<SQL
INSERT INTO `eb_diy` (`id`, `version`, `name`, `template_name`, `value`, `default_value`, `add_time`, `update_time`, `status`, `type`, `is_show`, `is_bg_color`, `is_bg_pic`, `color_picker`, `bg_pic`, `bg_tab_val`, `is_del`, `order_status`, `my_banner_status`, `is_diy`) VALUES
(NULL, '1.0', '粉色主题模板', '', '{\"1643013843593000\":{\"name\":\"headerSerch\",\"timestamp\":1643013843593000,\"setUp\":{\"tabVal\":1},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"},{\"item\":\"rgba(255,68,143,1)\"}]},\"boxStyle\":{\"title\":\"\\\\u8fb9\\\\u6846\\\\u6837\\\\u5f0f\",\"name\":\"boxStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"hotWords\":{\"list\":[{\"val\":\"\"}]},\"logoConfig\":{\"type\":1,\"header\":\"\\\\u8bbe\\\\u7f6elogo\",\"title\":\"\",\"url\":\"\"},\"id\":\"id1643013843593000\"},\"1643013843593001\":{\"name\":\"tabNav\",\"timestamp\":1643013843593001,\"status\":{\"title\":\"\\\\u5f00\\\\u5173\",\"default\":{\"status\":false}},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"},{\"item\":\"rgba(255,68,143,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013842911000\"},\"1643013843593002\":{\"name\":\"swiperBg\",\"timestamp\":1643013843593002,\"setUp\":{\"tabVal\":0},\"swiperConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6750px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u56fe\\\\u7247 \\\\u987a\\\\u5e8f\",\"maxList\":10,\"list\":[{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/d54f87813d22fc2ce2e6e231f3bcfae7.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"isShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u80cc\\\\u666f\\\\u8272\",\"val\":true},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"},{\"item\":\"rgba(255,68,143,1)\"}]},\"dotColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"docConfig\":{\"cname\":\"swiper\",\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u76f4\\\\u7ebf\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u6570\\\\u5b57\",\"icon\":\"iconshuzi\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"txtStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"imgConfig\":{\"cname\":\"docStyle\",\"title\":\"\\\\u8f6e\\\\u64ad\\\\u56fe\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"id\":\"id1643013838175000\"},\"1643013843593003\":{\"name\":\"bargain\",\"timestamp\":1643013843593003,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":5},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"titleColor\":{\"title\":\"\\\\u6807\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#FF6000\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}],\"color\":[{\"item\":\"rgba(255,68,143,0.17)\"},{\"item\":\"rgba(255,68,143,0.17)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"productGap\":{\"title\":\"\\\\u5546\\\\u54c1\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":10},\"mbCongfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"id\":\"id1636702418597006\"},\"1643013843593004\":{\"name\":\"seckill\",\"timestamp\":1643013843593004,\"setUp\":{\"tabVal\":1},\"countDownColor\":{\"title\":\"\\\\u5012\\\\u8ba1\\\\u65f6\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"countDownColor\",\"default\":[{\"item\":\"rgba(252,60,62,0.09)\"}],\"color\":[{\"item\":\"rgba(252,60,62,0.09)\"}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"conStyle\":{\"title\":\"\\\\u5185\\\\u5bb9\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"rgba(255,255,255,1)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"discountShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6298\\\\u6263\\\\u6807\\\\u7b7e\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"seckillShow\":{\"title\":\"\\\\u62a2\\\\u8d2d\\\\u6807\\\\u7b7e\",\"val\":true},\"numberConfig\":{\"val\":5},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":10},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"http:\\/\\/pro.crmeb.net\\/static\\/images\\/spike-icon-002.gif\"},\"id\":\"id1636702418597007\"},\"1643013843593005\":{\"name\":\"activeParty\",\"timestamp\":1643013843593005,\"setUp\":{\"tabVal\":1},\"titleConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u6807\\\\u9898\",\"value\":\"\\\\u8d85\\\\u503c\\\\u7206\\\\u6b3e\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u6807\\\\u9898\",\"max\":10},\"desConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7f8e\\\\u597d\\\\u751f\\\\u6d3b\\\\u7531\\\\u6b64\\\\u5f00\\\\u59cb\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u7b80\\\\u4ecb\",\"max\":8},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff0c\\\\u56fe\\\\u7247\\\\u5efa\\\\u8bae\\\\u5c3a\\\\u5bf8140 * 140px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef \\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"maxList\":4,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/08\\/d545e202108091906106850.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=109\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/08\\/92ec5202108091906075891.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=2\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/08\\/034cd202108091906172767.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u65b0\\\\u54c1\\\\u4e0a\\\\u67b6\\\\u7b49 \\\\u4f60\\\\u6765\\\\u62ff\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=3\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/08\\/03112202108091906082734.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7efc\\\\u5408\\\\u8bc4\\\\u9009\\\\u597d \\\\u4ea7\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=4\",\"max\":100}]}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fc3c3e\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"bgColor\":{\"title\":\"\\\\u6807\\\\u7b7e\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"},{\"item\":\"rgba(255,68,143,1)\"}]},\"boxColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"boxColor\",\"default\":[{\"item\":\"#ffe5e3\"}],\"color\":[{\"item\":\"rgba(242,236,242,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"id\":\"id1636702418597008\"},\"1643013843593006\":{\"name\":\"combination\",\"timestamp\":1643013843593006,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":5},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"pinkShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u62fc\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"joinShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u53c2\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"rgba(255,68,68,0.1)\"}],\"color\":[{\"item\":\"rgba(255,68,68,0.1)\"}]},\"conStyle\":{\"title\":\"\\\\u5185\\\\u5bb9\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"rgba(255,255,255,1)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":10},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"http:\\/\\/pro.crmeb.net\\/static\\/images\\/group02.gif\"},\"id\":\"id1636702418597009\"},\"1643013843593007\":{\"name\":\"promotionList\",\"timestamp\":1643013843593007,\"setUp\":{\"tabVal\":0},\"productList\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u5217\\\\u8868\"},\"titleConfig\":{\"title\":\"\\\\u6807\\\\u9898\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u540d\\\\u79f0\",\"val\":true},\"opriceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u539f\\\\u4ef7\",\"val\":true},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u4ef7\\\\u683c\",\"val\":true},\"couponShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4f18\\\\u60e0\\\\u5238\",\"val\":true},\"tabConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":4,\"tabCur\":3,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u54c1\\\\u8d28\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":1,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u7206\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":2,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u65b0\\\\u54c1\\\\u9996\\\\u53d1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u5e94\\\\u5b63\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":3,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u6298\\\\u6263\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":4,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#F95429\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"fontColor\":{\"title\":\"\\\\u4ef7\\\\u683c\\\\u989c\\\\u8272\",\"name\":\"fontColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"labelColor\":{\"title\":\"\\\\u6d3b\\\\u52a8\\\\u6807\\\\u7b7e\",\"name\":\"labelColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"numConfig\":{\"val\":30},\"id\":\"id1636702418597010\"},\"1643013927673000\":{\"name\":\"pageFoot\",\"setUp\":{\"tabVal\":0},\"status\":{\"title\":\"\\\\u662f\\\\u5426\\\\u81ea\\\\u5b9a\\\\u4e49\",\"name\":\"status\",\"status\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#282828\"}],\"color\":[{\"item\":\"#282828\"}]},\"activeTxtColor\":{\"title\":\"\\\\u9009\\\\u4e2d\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#F62C2C\"}],\"color\":[{\"item\":\"#F62C2C\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"menuList\":[{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/ba3bc202111121526229568.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/95aa520211112152609908.png\"],\"name\":\"\\\\u9996\\\\u9875\",\"link\":\"\\/pages\\/index\\/index\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/59773202111121526507460.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/4e9be202111121526368000.png\"],\"name\":\"\\\\u5206\\\\u7c7b\",\"link\":\"\\/pages\\/goods_cate\\/goods_cate\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/67d83202111121527411431.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3f2b1202111121527274601.png\"],\"name\":\"\\\\u8d2d\\\\u7269\\\\u8f66\",\"link\":\"\\/pages\\/order_addcart\\/order_addcart\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/c0ce6202111121528012733.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/25c19202111121528175379.png\"],\"name\":\"\\\\u6211\\\\u7684\",\"link\":\"\\/pages\\/user\\/index\"}],\"id\":\"idundefined\"}}', '{\"1643013843593000\":{\"name\":\"headerSerch\",\"timestamp\":1643013843593000,\"setUp\":{\"tabVal\":1},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"},{\"item\":\"rgba(255,68,143,1)\"}]},\"boxStyle\":{\"title\":\"\\\\u8fb9\\\\u6846\\\\u6837\\\\u5f0f\",\"name\":\"boxStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"hotWords\":{\"list\":[{\"val\":\"\"}]},\"logoConfig\":{\"type\":1,\"header\":\"\\\\u8bbe\\\\u7f6elogo\",\"title\":\"\",\"url\":\"\"},\"id\":\"id1643013843593000\"},\"1643013843593001\":{\"name\":\"tabNav\",\"timestamp\":1643013843593001,\"status\":{\"title\":\"\\\\u5f00\\\\u5173\",\"default\":{\"status\":false}},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"},{\"item\":\"rgba(255,68,143,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013842911000\"},\"1643013843593002\":{\"name\":\"swiperBg\",\"timestamp\":1643013843593002,\"setUp\":{\"tabVal\":0},\"swiperConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6750px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u56fe\\\\u7247 \\\\u987a\\\\u5e8f\",\"maxList\":10,\"list\":[{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/d54f87813d22fc2ce2e6e231f3bcfae7.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"isShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u80cc\\\\u666f\\\\u8272\",\"val\":true},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"},{\"item\":\"rgba(255,68,143,1)\"}]},\"dotColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"docConfig\":{\"cname\":\"swiper\",\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u76f4\\\\u7ebf\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u6570\\\\u5b57\",\"icon\":\"iconshuzi\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"txtStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"imgConfig\":{\"cname\":\"docStyle\",\"title\":\"\\\\u8f6e\\\\u64ad\\\\u56fe\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"id\":\"id1643013838175000\"},\"1643013843593003\":{\"name\":\"bargain\",\"timestamp\":1643013843593003,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":5},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"titleColor\":{\"title\":\"\\\\u6807\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#FF6000\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}],\"color\":[{\"item\":\"rgba(255,68,143,0.17)\"},{\"item\":\"rgba(255,68,143,0.17)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"productGap\":{\"title\":\"\\\\u5546\\\\u54c1\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":10},\"mbCongfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"id\":\"id1636702418597006\"},\"1643013843593004\":{\"name\":\"seckill\",\"timestamp\":1643013843593004,\"setUp\":{\"tabVal\":1},\"countDownColor\":{\"title\":\"\\\\u5012\\\\u8ba1\\\\u65f6\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"countDownColor\",\"default\":[{\"item\":\"rgba(252,60,62,0.09)\"}],\"color\":[{\"item\":\"rgba(252,60,62,0.09)\"}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"conStyle\":{\"title\":\"\\\\u5185\\\\u5bb9\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"rgba(255,255,255,1)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"discountShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6298\\\\u6263\\\\u6807\\\\u7b7e\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"seckillShow\":{\"title\":\"\\\\u62a2\\\\u8d2d\\\\u6807\\\\u7b7e\",\"val\":true},\"numberConfig\":{\"val\":5},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":10},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"http:\\/\\/pro.crmeb.net\\/static\\/images\\/spike-icon-002.gif\"},\"id\":\"id1636702418597007\"},\"1643013843593005\":{\"name\":\"activeParty\",\"timestamp\":1643013843593005,\"setUp\":{\"tabVal\":1},\"titleConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u6807\\\\u9898\",\"value\":\"\\\\u8d85\\\\u503c\\\\u7206\\\\u6b3e\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u6807\\\\u9898\",\"max\":10},\"desConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7f8e\\\\u597d\\\\u751f\\\\u6d3b\\\\u7531\\\\u6b64\\\\u5f00\\\\u59cb\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u7b80\\\\u4ecb\",\"max\":8},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff0c\\\\u56fe\\\\u7247\\\\u5efa\\\\u8bae\\\\u5c3a\\\\u5bf8140 * 140px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef \\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"maxList\":4,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/08\\/d545e202108091906106850.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=109\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/08\\/92ec5202108091906075891.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=2\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/08\\/034cd202108091906172767.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u65b0\\\\u54c1\\\\u4e0a\\\\u67b6\\\\u7b49 \\\\u4f60\\\\u6765\\\\u62ff\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=3\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/08\\/03112202108091906082734.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7efc\\\\u5408\\\\u8bc4\\\\u9009\\\\u597d \\\\u4ea7\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=4\",\"max\":100}]}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fc3c3e\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"bgColor\":{\"title\":\"\\\\u6807\\\\u7b7e\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"},{\"item\":\"rgba(255,68,143,1)\"}]},\"boxColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"boxColor\",\"default\":[{\"item\":\"#ffe5e3\"}],\"color\":[{\"item\":\"rgba(242,236,242,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"id\":\"id1636702418597008\"},\"1643013843593006\":{\"name\":\"combination\",\"timestamp\":1643013843593006,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":5},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"pinkShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u62fc\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"joinShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u53c2\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"rgba(255,68,68,0.1)\"}],\"color\":[{\"item\":\"rgba(255,68,68,0.1)\"}]},\"conStyle\":{\"title\":\"\\\\u5185\\\\u5bb9\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"rgba(255,255,255,1)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":10},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"http:\\/\\/pro.crmeb.net\\/static\\/images\\/group02.gif\"},\"id\":\"id1636702418597009\"},\"1643013843593007\":{\"name\":\"promotionList\",\"timestamp\":1643013843593007,\"setUp\":{\"tabVal\":0},\"productList\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u5217\\\\u8868\"},\"titleConfig\":{\"title\":\"\\\\u6807\\\\u9898\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u540d\\\\u79f0\",\"val\":true},\"opriceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u539f\\\\u4ef7\",\"val\":true},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u4ef7\\\\u683c\",\"val\":true},\"couponShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4f18\\\\u60e0\\\\u5238\",\"val\":true},\"tabConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":4,\"tabCur\":3,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u54c1\\\\u8d28\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":1,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u7206\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":2,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u65b0\\\\u54c1\\\\u9996\\\\u53d1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u5e94\\\\u5b63\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":3,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u6298\\\\u6263\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":4,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#F95429\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"fontColor\":{\"title\":\"\\\\u4ef7\\\\u683c\\\\u989c\\\\u8272\",\"name\":\"fontColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"labelColor\":{\"title\":\"\\\\u6d3b\\\\u52a8\\\\u6807\\\\u7b7e\",\"name\":\"labelColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(255,68,143,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"numConfig\":{\"val\":30},\"id\":\"id1636702418597010\"},\"1643013927673000\":{\"name\":\"pageFoot\",\"setUp\":{\"tabVal\":0},\"status\":{\"title\":\"\\\\u662f\\\\u5426\\\\u81ea\\\\u5b9a\\\\u4e49\",\"name\":\"status\",\"status\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#282828\"}],\"color\":[{\"item\":\"#282828\"}]},\"activeTxtColor\":{\"title\":\"\\\\u9009\\\\u4e2d\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#F62C2C\"}],\"color\":[{\"item\":\"#F62C2C\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"menuList\":[{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/ba3bc202111121526229568.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/95aa520211112152609908.png\"],\"name\":\"\\\\u9996\\\\u9875\",\"link\":\"\\/pages\\/index\\/index\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/59773202111121526507460.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/4e9be202111121526368000.png\"],\"name\":\"\\\\u5206\\\\u7c7b\",\"link\":\"\\/pages\\/goods_cate\\/goods_cate\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/67d83202111121527411431.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3f2b1202111121527274601.png\"],\"name\":\"\\\\u8d2d\\\\u7269\\\\u8f66\",\"link\":\"\\/pages\\/order_addcart\\/order_addcart\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/c0ce6202111121528012733.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/25c19202111121528175379.png\"],\"name\":\"\\\\u6211\\\\u7684\",\"link\":\"\\/pages\\/user\\/index\"}],\"id\":\"idundefined\"}}', 1625564366, 1643013933, 1, 2, 1, 0, 0, '#f5f5f5', '', 0, 0, 0, 0, 1),
(NULL, '1.0', '蓝色主题模板', '', '{\"1642948693686000\":{\"name\":\"headerSerch\",\"timestamp\":1642948693686000,\"setUp\":{\"tabVal\":1},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"},{\"item\":\"rgba(64,209,244,1)\"}]},\"boxStyle\":{\"title\":\"\\\\u8fb9\\\\u6846\\\\u6837\\\\u5f0f\",\"name\":\"boxStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"hotWords\":{\"list\":[{\"val\":\"\"}]},\"logoConfig\":{\"type\":1,\"header\":\"\\\\u8bbe\\\\u7f6elogo\",\"title\":\"\",\"url\":\"\"},\"id\":\"id1642948693686000\"},\"1642948693686001\":{\"name\":\"swiperBg\",\"timestamp\":1642948693686001,\"setUp\":{\"tabVal\":0},\"swiperConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6750px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u56fe\\\\u7247 \\\\u987a\\\\u5e8f\",\"maxList\":10,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/78c7c20211011171437256.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/ce66b202110111714168104.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/e48d720211011171416562.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"isShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u80cc\\\\u666f\\\\u8272\",\"val\":true},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"},{\"item\":\"rgba(64,209,244,1)\"}]},\"dotColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":11,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"docConfig\":{\"cname\":\"swiper\",\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u76f4\\\\u7ebf\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u6570\\\\u5b57\",\"icon\":\"iconshuzi\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"txtStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u4f4d\\\\u7f6e\",\"type\":2,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"imgConfig\":{\"cname\":\"docStyle\",\"title\":\"\\\\u8f6e\\\\u64ad\\\\u56fe\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"id\":\"id1642948693686001\"},\"1642948693686002\":{\"name\":\"menus\",\"timestamp\":1642948693686002,\"setUp\":{\"tabVal\":0},\"tabConfig\":{\"title\":\"\\\\u5c55\\\\u793a\\\\u6837\\\\u5f0f\",\"tabVal\":1,\"type\":1,\"tabList\":[{\"name\":\"\\\\u5355\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"icondanhang\"},{\"name\":\"\\\\u591a\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"iconduohang\"}]},\"rowsNum\":{\"title\":\"\\\\u663e\\\\u793a\\\\u884c\\\\u6570\",\"name\":\"rowsNum\",\"type\":0,\"list\":[{\"val\":\"2\\\\u884c\",\"icon\":\"icon2hang\"},{\"val\":\"3\\\\u884c\",\"icon\":\"icon3hang\"},{\"val\":\"4\\\\u884c\",\"icon\":\"icon4hang\"}]},\"menuStyle\":{\"title\":\"\\\\u56fe\\\\u6807\\\\u6837\\\\u5f0f\",\"name\":\"menuStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u65b9\\\\u5f62\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"icondayuanjiao\"}]},\"number\":{\"title\":\"\\\\u663e\\\\u793a\\\\u4e2a\\\\u6570\",\"name\":\"number\",\"type\":2,\"list\":[{\"val\":\"3\\\\u4e2a\",\"icon\":\"icon3ge\"},{\"val\":\"4\\\\u4e2a\",\"icon\":\"icon4ge1\"},{\"val\":\"5\\\\u4e2a\",\"icon\":\"icon5ge1\"}]},\"pointerStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"name\":\"pointerStyle\",\"type\":2,\"list\":[{\"val\":\"\\\\u957f\\\\u6761\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea690 * 90px\",\"maxList\":100,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/02d9e202109101106364468.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5546\\\\u54c1\\\\u5206\\\\u7c7b\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=1&name=\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/4a21a202109101106368349.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9886\\\\u4f18\\\\u60e0\\\\u5238\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=2&name=\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/49d20202109101106377392.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u884c\\\\u4e1a\\\\u8d44\\\\u8baf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=3&name=\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/9d74b20210910110637104.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u6211\\\\u7684\\\\u6536\\\\u85cf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_goods_collection\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/1c95120210910110636424.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u780d\\\\u4ef7\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/23332202109101106361126.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79d2\\\\u6740\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/34f81202109101106357549.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79ef\\\\u5206\\\\u5546\\\\u57ce\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/34930202109101106363502.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u62fc\\\\u56e2\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/a4c37202109101106353478.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u8ba2\\\\u5355\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/f4598202109101106358762.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u62bd\\\\u5956\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"pointerColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"name\":\"pointerColor\",\"default\":[{\"item\":\"#f44\"}],\"color\":[{\"item\":\"#f44\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"titleColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#333333\"}],\"color\":[{\"item\":\"rgba(51,51,51,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"id\":\"id1642948693686002\"},\"1642948693686003\":{\"name\":\"news\",\"timestamp\":1642948693686003,\"setUp\":{\"tabVal\":0},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\",\"style\":\"left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\",\"style\":\"center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\",\"style\":\"right\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#333\"}],\"color\":[{\"item\":\"#333\"}]},\"listConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":10,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u89e3\\\\u8bfb\\\\u793e\\\\u4ea4\\\\u65b0\\\\u96f6\\\\u552e\\\\u4e2d\\\\u7684\\\\u5fae\\\\u4fe1\\\\u5206\\\\u9500\\\\u6a21\\\\u5f0f\",\"max\":30,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc730\\\\u4e2a\\\\u5b57\",\"empty\":true},{\"title\":\"\\\\u94fe\\\\u63a5\",\"val\":\"\\/pages\\/news_details\\/index?id=31\",\"max\":200,\"pla\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u8fde\\\\u63a5\"}]}],\"tabCur\":0},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"logoConfig\":{\"header\":\"\\\\u56fe\\\\u6807\\\\u8bbe\\\\u7f6e\",\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6130 * 36px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/6d4fe202111181229083421.png\"},\"id\":\"id1642948693686003\"},\"1642948693686004\":{\"name\":\"activeParty\",\"timestamp\":1642948693686004,\"setUp\":{\"tabVal\":1},\"titleConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u6807\\\\u9898\",\"value\":\"\\\\u8d85\\\\u503c\\\\u7206\\\\u6b3e\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u6807\\\\u9898\",\"max\":10},\"desConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7f8e\\\\u597d\\\\u751f\\\\u6d3b\\\\u7531\\\\u6b64\\\\u5f00\\\\u59cb\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u7b80\\\\u4ecb\",\"max\":8},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff0c\\\\u56fe\\\\u7247\\\\u5efa\\\\u8bae\\\\u5c3a\\\\u5bf8140 * 140px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef \\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"maxList\":4,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/16\\/216aaf8ea5fdeff2a31a48f3e931ff46.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=114\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/15\\/1c72a02ad97f695bd85fa04821b72985.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=113\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/612bb202110121143097664.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u65b0\\\\u54c1\\\\u4e0a\\\\u67b6\\\\u7b49 \\\\u4f60\\\\u6765\\\\u62ff\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=111\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/59c17202110130919585671.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7efc\\\\u5408\\\\u8bc4\\\\u9009\\\\u597d \\\\u4ea7\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=101\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fc3c3e\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"bgColor\":{\"title\":\"\\\\u6807\\\\u7b7e\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"},{\"item\":\"rgba(64,209,244,1)\"}]},\"boxColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"boxColor\",\"default\":[{\"item\":\"#ffe5e3\"}],\"color\":[{\"item\":\"rgba(208,240,255,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"id\":\"id1642948693686004\"},\"1642948693686005\":{\"name\":\"bargain\",\"timestamp\":1642948693686005,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"titleColor\":{\"title\":\"\\\\u6807\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#FF6000\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}],\"color\":[{\"item\":\"rgba(208,240,255,1)\"},{\"item\":\"rgba(225,245,255,1)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"productGap\":{\"title\":\"\\\\u5546\\\\u54c1\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"mbCongfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"id\":\"id1642948693686005\"},\"1642948693686006\":{\"name\":\"combination\",\"timestamp\":1642948693686006,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"pinkShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u62fc\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"joinShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u53c2\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"rgba(255,68,68,0.1)\"}],\"color\":[{\"item\":\"rgba(208,240,255,1)\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/238c5202111181433515309.gif\"},\"id\":\"id1642948693686006\"},\"1642948693686007\":{\"name\":\"promotionList\",\"timestamp\":1642948693686007,\"setUp\":{\"tabVal\":1},\"productList\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u5217\\\\u8868\"},\"titleConfig\":{\"title\":\"\\\\u6807\\\\u9898\\\\u4f4d\\\\u7f6e\",\"type\":2,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u540d\\\\u79f0\",\"val\":true},\"opriceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u539f\\\\u4ef7\",\"val\":true},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u4ef7\\\\u683c\",\"val\":true},\"couponShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4f18\\\\u60e0\\\\u5238\",\"val\":true},\"tabConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":4,\"tabCur\":3,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u6700\\\\u65b0\\\\u51fa\\\\u7089\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":3,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u731c\\\\u4f60\\\\u559c\\\\u6b22\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":1,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u597d\\\\u8bc4\\\\u5982\\\\u4e91\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":2,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u591a\\\\u4e70\\\\u591a\\\\u9500\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":4,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#F95429\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"fontColor\":{\"title\":\"\\\\u4ef7\\\\u683c\\\\u989c\\\\u8272\",\"name\":\"fontColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(253,80,47,1)\"}]},\"labelColor\":{\"title\":\"\\\\u6d3b\\\\u52a8\\\\u6807\\\\u7b7e\",\"name\":\"labelColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"numConfig\":{\"val\":9},\"id\":\"id1642948693686007\"},\"1643013787723000\":{\"name\":\"pageFoot\",\"setUp\":{\"tabVal\":0},\"status\":{\"title\":\"\\\\u662f\\\\u5426\\\\u81ea\\\\u5b9a\\\\u4e49\",\"name\":\"status\",\"status\":false},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#282828\"}],\"color\":[{\"item\":\"rgba(51,51,51,1)\"}]},\"activeTxtColor\":{\"title\":\"\\\\u9009\\\\u4e2d\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#F62C2C\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"menuList\":[{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/bdf25202111181452426458.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/aaf1b202111181452576658.png\"],\"name\":\"\\\\u9996\\\\u9875\",\"link\":\"\\/pages\\/index\\/index\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/1109d202111181453128599.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/59d4a202111181453208678.png\"],\"name\":\"\\\\u5206\\\\u7c7b\",\"link\":\"\\/pages\\/goods_cate\\/goods_cate\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/076ac202111181453294517.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/201c9202111181453378599.png\"],\"name\":\"\\\\u8d2d\\\\u7269\\\\u8f66\",\"link\":\"\\/pages\\/order_addcart\\/order_addcart\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/f04cf20211118145343755.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3a141202111181453495771.png\"],\"name\":\"\\\\u6211\\\\u7684\",\"link\":\"\\/pages\\/user\\/index\"}],\"id\":\"idundefined\"}}', '{\"1642948693686000\":{\"name\":\"headerSerch\",\"timestamp\":1642948693686000,\"setUp\":{\"tabVal\":1},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"},{\"item\":\"rgba(64,209,244,1)\"}]},\"boxStyle\":{\"title\":\"\\\\u8fb9\\\\u6846\\\\u6837\\\\u5f0f\",\"name\":\"boxStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"hotWords\":{\"list\":[{\"val\":\"\"}]},\"logoConfig\":{\"type\":1,\"header\":\"\\\\u8bbe\\\\u7f6elogo\",\"title\":\"\",\"url\":\"\"},\"id\":\"id1642948693686000\"},\"1642948693686001\":{\"name\":\"swiperBg\",\"timestamp\":1642948693686001,\"setUp\":{\"tabVal\":0},\"swiperConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6750px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u56fe\\\\u7247 \\\\u987a\\\\u5e8f\",\"maxList\":10,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/78c7c20211011171437256.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/ce66b202110111714168104.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/e48d720211011171416562.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"isShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u80cc\\\\u666f\\\\u8272\",\"val\":true},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"},{\"item\":\"rgba(64,209,244,1)\"}]},\"dotColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":11,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"docConfig\":{\"cname\":\"swiper\",\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u76f4\\\\u7ebf\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u6570\\\\u5b57\",\"icon\":\"iconshuzi\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"txtStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u4f4d\\\\u7f6e\",\"type\":2,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"imgConfig\":{\"cname\":\"docStyle\",\"title\":\"\\\\u8f6e\\\\u64ad\\\\u56fe\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"id\":\"id1642948693686001\"},\"1642948693686002\":{\"name\":\"menus\",\"timestamp\":1642948693686002,\"setUp\":{\"tabVal\":0},\"tabConfig\":{\"title\":\"\\\\u5c55\\\\u793a\\\\u6837\\\\u5f0f\",\"tabVal\":1,\"type\":1,\"tabList\":[{\"name\":\"\\\\u5355\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"icondanhang\"},{\"name\":\"\\\\u591a\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"iconduohang\"}]},\"rowsNum\":{\"title\":\"\\\\u663e\\\\u793a\\\\u884c\\\\u6570\",\"name\":\"rowsNum\",\"type\":0,\"list\":[{\"val\":\"2\\\\u884c\",\"icon\":\"icon2hang\"},{\"val\":\"3\\\\u884c\",\"icon\":\"icon3hang\"},{\"val\":\"4\\\\u884c\",\"icon\":\"icon4hang\"}]},\"menuStyle\":{\"title\":\"\\\\u56fe\\\\u6807\\\\u6837\\\\u5f0f\",\"name\":\"menuStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u65b9\\\\u5f62\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"icondayuanjiao\"}]},\"number\":{\"title\":\"\\\\u663e\\\\u793a\\\\u4e2a\\\\u6570\",\"name\":\"number\",\"type\":2,\"list\":[{\"val\":\"3\\\\u4e2a\",\"icon\":\"icon3ge\"},{\"val\":\"4\\\\u4e2a\",\"icon\":\"icon4ge1\"},{\"val\":\"5\\\\u4e2a\",\"icon\":\"icon5ge1\"}]},\"pointerStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"name\":\"pointerStyle\",\"type\":2,\"list\":[{\"val\":\"\\\\u957f\\\\u6761\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea690 * 90px\",\"maxList\":100,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/02d9e202109101106364468.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5546\\\\u54c1\\\\u5206\\\\u7c7b\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=1&name=\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/4a21a202109101106368349.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9886\\\\u4f18\\\\u60e0\\\\u5238\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=2&name=\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/49d20202109101106377392.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u884c\\\\u4e1a\\\\u8d44\\\\u8baf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/columnGoods\\/HotNewGoods\\/index?type=3&name=\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/9d74b20210910110637104.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u6211\\\\u7684\\\\u6536\\\\u85cf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_goods_collection\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/1c95120210910110636424.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u780d\\\\u4ef7\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/23332202109101106361126.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79d2\\\\u6740\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/34f81202109101106357549.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79ef\\\\u5206\\\\u5546\\\\u57ce\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/34930202109101106363502.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u62fc\\\\u56e2\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/a4c37202109101106353478.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u8ba2\\\\u5355\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/f4598202109101106358762.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u62bd\\\\u5956\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"pointerColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"name\":\"pointerColor\",\"default\":[{\"item\":\"#f44\"}],\"color\":[{\"item\":\"#f44\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"titleColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#333333\"}],\"color\":[{\"item\":\"rgba(51,51,51,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"id\":\"id1642948693686002\"},\"1642948693686003\":{\"name\":\"news\",\"timestamp\":1642948693686003,\"setUp\":{\"tabVal\":0},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\",\"style\":\"left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\",\"style\":\"center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\",\"style\":\"right\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#333\"}],\"color\":[{\"item\":\"#333\"}]},\"listConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":10,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u89e3\\\\u8bfb\\\\u793e\\\\u4ea4\\\\u65b0\\\\u96f6\\\\u552e\\\\u4e2d\\\\u7684\\\\u5fae\\\\u4fe1\\\\u5206\\\\u9500\\\\u6a21\\\\u5f0f\",\"max\":30,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc730\\\\u4e2a\\\\u5b57\",\"empty\":true},{\"title\":\"\\\\u94fe\\\\u63a5\",\"val\":\"\\/pages\\/news_details\\/index?id=31\",\"max\":200,\"pla\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u8fde\\\\u63a5\"}]}],\"tabCur\":0},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"logoConfig\":{\"header\":\"\\\\u56fe\\\\u6807\\\\u8bbe\\\\u7f6e\",\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6130 * 36px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/6d4fe202111181229083421.png\"},\"id\":\"id1642948693686003\"},\"1642948693686004\":{\"name\":\"activeParty\",\"timestamp\":1642948693686004,\"setUp\":{\"tabVal\":1},\"titleConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u6807\\\\u9898\",\"value\":\"\\\\u8d85\\\\u503c\\\\u7206\\\\u6b3e\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u6807\\\\u9898\",\"max\":10},\"desConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7f8e\\\\u597d\\\\u751f\\\\u6d3b\\\\u7531\\\\u6b64\\\\u5f00\\\\u59cb\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u7b80\\\\u4ecb\",\"max\":8},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff0c\\\\u56fe\\\\u7247\\\\u5efa\\\\u8bae\\\\u5c3a\\\\u5bf8140 * 140px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef \\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"maxList\":4,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/16\\/216aaf8ea5fdeff2a31a48f3e931ff46.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=114\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/15\\/1c72a02ad97f695bd85fa04821b72985.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=113\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/612bb202110121143097664.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u65b0\\\\u54c1\\\\u4e0a\\\\u67b6\\\\u7b49 \\\\u4f60\\\\u6765\\\\u62ff\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=111\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/59c17202110130919585671.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7efc\\\\u5408\\\\u8bc4\\\\u9009\\\\u597d \\\\u4ea7\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=101\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fc3c3e\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"bgColor\":{\"title\":\"\\\\u6807\\\\u7b7e\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"},{\"item\":\"rgba(64,209,244,1)\"}]},\"boxColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"boxColor\",\"default\":[{\"item\":\"#ffe5e3\"}],\"color\":[{\"item\":\"rgba(208,240,255,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"id\":\"id1642948693686004\"},\"1642948693686005\":{\"name\":\"bargain\",\"timestamp\":1642948693686005,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"titleColor\":{\"title\":\"\\\\u6807\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#FF6000\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}],\"color\":[{\"item\":\"rgba(208,240,255,1)\"},{\"item\":\"rgba(225,245,255,1)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"productGap\":{\"title\":\"\\\\u5546\\\\u54c1\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"mbCongfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"id\":\"id1642948693686005\"},\"1642948693686006\":{\"name\":\"combination\",\"timestamp\":1642948693686006,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"pinkShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u62fc\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"joinShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u53c2\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"rgba(255,68,68,0.1)\"}],\"color\":[{\"item\":\"rgba(208,240,255,1)\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/238c5202111181433515309.gif\"},\"id\":\"id1642948693686006\"},\"1642948693686007\":{\"name\":\"promotionList\",\"timestamp\":1642948693686007,\"setUp\":{\"tabVal\":1},\"productList\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u5217\\\\u8868\"},\"titleConfig\":{\"title\":\"\\\\u6807\\\\u9898\\\\u4f4d\\\\u7f6e\",\"type\":2,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u540d\\\\u79f0\",\"val\":true},\"opriceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u539f\\\\u4ef7\",\"val\":true},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u4ef7\\\\u683c\",\"val\":true},\"couponShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4f18\\\\u60e0\\\\u5238\",\"val\":true},\"tabConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":4,\"tabCur\":3,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u6700\\\\u65b0\\\\u51fa\\\\u7089\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":3,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u731c\\\\u4f60\\\\u559c\\\\u6b22\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":1,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u597d\\\\u8bc4\\\\u5982\\\\u4e91\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":2,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u591a\\\\u4e70\\\\u591a\\\\u9500\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":4,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#F95429\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"fontColor\":{\"title\":\"\\\\u4ef7\\\\u683c\\\\u989c\\\\u8272\",\"name\":\"fontColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(253,80,47,1)\"}]},\"labelColor\":{\"title\":\"\\\\u6d3b\\\\u52a8\\\\u6807\\\\u7b7e\",\"name\":\"labelColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"numConfig\":{\"val\":9},\"id\":\"id1642948693686007\"},\"1643013787723000\":{\"name\":\"pageFoot\",\"setUp\":{\"tabVal\":0},\"status\":{\"title\":\"\\\\u662f\\\\u5426\\\\u81ea\\\\u5b9a\\\\u4e49\",\"name\":\"status\",\"status\":false},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#282828\"}],\"color\":[{\"item\":\"rgba(51,51,51,1)\"}]},\"activeTxtColor\":{\"title\":\"\\\\u9009\\\\u4e2d\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#F62C2C\"}],\"color\":[{\"item\":\"rgba(29,176,252,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"menuList\":[{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/bdf25202111181452426458.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/aaf1b202111181452576658.png\"],\"name\":\"\\\\u9996\\\\u9875\",\"link\":\"\\/pages\\/index\\/index\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/1109d202111181453128599.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/59d4a202111181453208678.png\"],\"name\":\"\\\\u5206\\\\u7c7b\",\"link\":\"\\/pages\\/goods_cate\\/goods_cate\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/076ac202111181453294517.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/201c9202111181453378599.png\"],\"name\":\"\\\\u8d2d\\\\u7269\\\\u8f66\",\"link\":\"\\/pages\\/order_addcart\\/order_addcart\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/f04cf20211118145343755.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3a141202111181453495771.png\"],\"name\":\"\\\\u6211\\\\u7684\",\"link\":\"\\/pages\\/user\\/index\"}],\"id\":\"idundefined\"}}', 1637209845, 1643013801, 0, 2, 1, 0, 0, '#f5f5f5', '', 0, 0, 0, 0, 1),
(NULL, '1.0', '绿色主题模板', '', '{\"1643013708659000\":{\"name\":\"headerSerch\",\"timestamp\":1643013708659000,\"setUp\":{\"tabVal\":1},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(63,202,17,1)\"},{\"item\":\"rgba(213,235,87,1)\"}]},\"boxStyle\":{\"title\":\"\\\\u8fb9\\\\u6846\\\\u6837\\\\u5f0f\",\"name\":\"boxStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"hotWords\":{\"list\":[{\"val\":\"\"}]},\"logoConfig\":{\"type\":1,\"header\":\"\\\\u8bbe\\\\u7f6elogo\",\"title\":\"\",\"url\":\"\"},\"id\":\"id1643013708659000\"},\"1643013708659001\":{\"name\":\"swiperBg\",\"timestamp\":1643013708659001,\"setUp\":{\"tabVal\":1},\"swiperConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6750px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u56fe\\\\u7247 \\\\u987a\\\\u5e8f\",\"maxList\":10,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/78c7c20211011171437256.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"isShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u80cc\\\\u666f\\\\u8272\",\"val\":true},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(63,202,17,1)\"},{\"item\":\"rgba(213,235,87,1)\"}]},\"dotColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"docConfig\":{\"cname\":\"swiper\",\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"type\":1,\"list\":[{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u76f4\\\\u7ebf\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u6570\\\\u5b57\",\"icon\":\"iconshuzi\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"txtStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u4f4d\\\\u7f6e\",\"type\":1,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"imgConfig\":{\"cname\":\"docStyle\",\"title\":\"\\\\u8f6e\\\\u64ad\\\\u56fe\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"id\":\"id1643013708659001\"},\"1643013708659002\":{\"name\":\"menus\",\"timestamp\":1643013708659002,\"setUp\":{\"tabVal\":0},\"tabConfig\":{\"title\":\"\\\\u5c55\\\\u793a\\\\u6837\\\\u5f0f\",\"tabVal\":1,\"type\":1,\"tabList\":[{\"name\":\"\\\\u5355\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"icondanhang\"},{\"name\":\"\\\\u591a\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"iconduohang\"}]},\"rowsNum\":{\"title\":\"\\\\u663e\\\\u793a\\\\u884c\\\\u6570\",\"name\":\"rowsNum\",\"type\":0,\"list\":[{\"val\":\"2\\\\u884c\",\"icon\":\"icon2hang\"},{\"val\":\"3\\\\u884c\",\"icon\":\"icon3hang\"},{\"val\":\"4\\\\u884c\",\"icon\":\"icon4hang\"}]},\"menuStyle\":{\"title\":\"\\\\u56fe\\\\u6807\\\\u6837\\\\u5f0f\",\"name\":\"menuStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u65b9\\\\u5f62\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"icondayuanjiao\"}]},\"number\":{\"title\":\"\\\\u663e\\\\u793a\\\\u4e2a\\\\u6570\",\"name\":\"number\",\"type\":2,\"list\":[{\"val\":\"3\\\\u4e2a\",\"icon\":\"icon3ge\"},{\"val\":\"4\\\\u4e2a\",\"icon\":\"icon4ge1\"},{\"val\":\"5\\\\u4e2a\",\"icon\":\"icon5ge1\"}]},\"pointerStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"name\":\"pointerStyle\",\"type\":2,\"list\":[{\"val\":\"\\\\u957f\\\\u6761\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":0,\"min\":0},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea690 * 90px\",\"maxList\":100,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/02d9e202109101106364468.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5546\\\\u57ce\\\\u5206\\\\u7c7b\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_cate\\/goods_cate\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/4a21a202109101106368349.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9886\\\\u4f18\\\\u60e0\\\\u5238\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_coupon\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/49d20202109101106377392.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u884c\\\\u4e1a\\\\u8d44\\\\u8baf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/news_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/1c95120210910110636424.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u780d\\\\u4ef7\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/bargain\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/23332202109101106361126.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79d2\\\\u6740\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/goods_seckill\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/a4c37202109101106353478.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u8ba2\\\\u5355\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/order_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/0047b202109101106374214.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u6211\\\\u8981\\\\u7b7e\\\\u5230\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_sgin\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/f4598202109101106358762.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u62bd\\\\u5956\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/lottery\\/grids\\/index?type=4\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/34f81202109101106357549.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79ef\\\\u5206\\\\u5546\\\\u57ce\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/points_mall\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/f4e7420210910110635959.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5730\\\\u5740\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_address_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"pointerColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"name\":\"pointerColor\",\"default\":[{\"item\":\"#f44\"}],\"color\":[{\"item\":\"#f44\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"titleColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#333333\"}],\"color\":[{\"item\":\"#333333\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"id\":\"id1643013708659002\"},\"1643013708659003\":{\"name\":\"news\",\"timestamp\":1643013708659003,\"setUp\":{\"tabVal\":0},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\",\"style\":\"left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\",\"style\":\"center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\",\"style\":\"right\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#333\"}],\"color\":[{\"item\":\"#333\"}]},\"listConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":10,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u89e3\\\\u8bfb\\\\u793e\\\\u4ea4\\\\u65b0\\\\u96f6\\\\u552e\\\\u4e2d\\\\u7684\\\\u5fae\\\\u4fe1\\\\u5206\\\\u9500\\\\u6a21\\\\u5f0f\",\"max\":30,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc730\\\\u4e2a\\\\u5b57\",\"empty\":true},{\"title\":\"\\\\u94fe\\\\u63a5\",\"val\":\"\\/pages\\/news_details\\/index?id=31\",\"max\":200,\"pla\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u8fde\\\\u63a5\"}]}],\"tabCur\":0},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":1,\"min\":0},\"logoConfig\":{\"header\":\"\\\\u56fe\\\\u6807\\\\u8bbe\\\\u7f6e\",\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6130 * 36px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/c6e3a202111251019162735.png\"},\"id\":\"id1643013708659003\"},\"1643013708659004\":{\"name\":\"activeParty\",\"timestamp\":1643013708659004,\"setUp\":{\"tabVal\":1},\"titleConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u6807\\\\u9898\",\"value\":\"\\\\u8d85\\\\u503c\\\\u7206\\\\u6b3e\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u6807\\\\u9898\",\"max\":10},\"desConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7f8e\\\\u597d\\\\u751f\\\\u6d3b\\\\u7531\\\\u6b64\\\\u5f00\\\\u59cb\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u7b80\\\\u4ecb\",\"max\":8},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff0c\\\\u56fe\\\\u7247\\\\u5efa\\\\u8bae\\\\u5c3a\\\\u5bf8140 * 140px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef \\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"maxList\":4,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/16\\/216aaf8ea5fdeff2a31a48f3e931ff46.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=114\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/15\\/86e928c002fefb45fc42ae09aec7c86e.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=113\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/22\\/9e2f6a8072dce6494cf224bc099b0c00.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u65b0\\\\u54c1\\\\u4e0a\\\\u67b6\\\\u7b49 \\\\u4f60\\\\u6765\\\\u62ff\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=98\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/59c17202110130919585671.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7efc\\\\u5408\\\\u8bc4\\\\u9009\\\\u597d \\\\u4ea7\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=101\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fc3c3e\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"bgColor\":{\"title\":\"\\\\u6807\\\\u7b7e\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"},{\"item\":\"rgba(185,211,38,1)\"}]},\"boxColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"boxColor\",\"default\":[{\"item\":\"#ffe5e3\"}],\"color\":[{\"item\":\"rgba(230,248,231,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"id\":\"id1643013708659004\"},\"1643013708659005\":{\"name\":\"bargain\",\"timestamp\":1643013708659005,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"titleColor\":{\"title\":\"\\\\u6807\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#FF6000\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}],\"color\":[{\"item\":\"rgba(230,248,231,1)\"},{\"item\":\"rgba(239,252,239,1)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"productGap\":{\"title\":\"\\\\u5546\\\\u54c1\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"mbCongfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"id\":\"id1643013708659005\"},\"1643013708659006\":{\"name\":\"combination\",\"timestamp\":1643013708659006,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":6},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"pinkShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u62fc\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"joinShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u53c2\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"rgba(255,68,68,0.1)\"}],\"color\":[{\"item\":\"rgba(230,248,231,1)\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/fac24202111181528488411.gif\"},\"id\":\"id1643013708659006\"},\"1643013708659007\":{\"name\":\"seckill\",\"timestamp\":1643013708659007,\"setUp\":{\"tabVal\":1},\"countDownColor\":{\"title\":\"\\\\u5012\\\\u8ba1\\\\u65f6\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"countDownColor\",\"default\":[{\"item\":\"rgba(252,60,62,0.09)\"}],\"color\":[{\"item\":\"rgba(230,248,231,1)\"}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"discountShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6298\\\\u6263\\\\u6807\\\\u7b7e\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"seckillShow\":{\"title\":\"\\\\u62a2\\\\u8d2d\\\\u6807\\\\u7b7e\",\"val\":true},\"numberConfig\":{\"val\":6},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3b72620211118153011706.gif\"},\"id\":\"id1643013708659007\"},\"1643013708659008\":{\"name\":\"promotionList\",\"timestamp\":1643013708659008,\"setUp\":{\"tabVal\":1},\"productList\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u5217\\\\u8868\"},\"titleConfig\":{\"title\":\"\\\\u6807\\\\u9898\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u540d\\\\u79f0\",\"val\":true},\"opriceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u539f\\\\u4ef7\",\"val\":true},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u4ef7\\\\u683c\",\"val\":true},\"couponShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4f18\\\\u60e0\\\\u5238\",\"val\":true},\"tabConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":4,\"tabCur\":3,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u6700\\\\u65b0\\\\u51fa\\\\u7089\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":3,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u731c\\\\u4f60\\\\u559c\\\\u6b22\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":2,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u597d\\\\u8bc4\\\\u5982\\\\u4e91\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":1,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u591a\\\\u4e70\\\\u591a\\\\u7701\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":4,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#F95429\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"fontColor\":{\"title\":\"\\\\u4ef7\\\\u683c\\\\u989c\\\\u8272\",\"name\":\"fontColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(255,118,0,1)\"}]},\"labelColor\":{\"title\":\"\\\\u6d3b\\\\u52a8\\\\u6807\\\\u7b7e\",\"name\":\"labelColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(255,118,0,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"numConfig\":{\"val\":20},\"id\":\"id1643013708659008\"},\"1643013797165000\":{\"name\":\"pageFoot\",\"setUp\":{\"tabVal\":0},\"status\":{\"title\":\"\\\\u662f\\\\u5426\\\\u81ea\\\\u5b9a\\\\u4e49\",\"name\":\"status\",\"status\":false},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#282828\"}],\"color\":[{\"item\":\"rgba(51,51,51,1)\"}]},\"activeTxtColor\":{\"title\":\"\\\\u9009\\\\u4e2d\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#F62C2C\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"menuList\":[{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/9d22e202111181538437101.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/aaf1b202111181452576658.png\"],\"name\":\"\\\\u9996\\\\u9875\",\"link\":\"\\/pages\\/index\\/index\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/88386202111181539257153.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/59d4a202111181453208678.png\"],\"name\":\"\\\\u5206\\\\u7c7b\",\"link\":\"\\/pages\\/goods_cate\\/goods_cate\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/189a2202111181538583033.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/201c9202111181453378599.png\"],\"name\":\"\\\\u8d2d\\\\u7269\\\\u8f66\",\"link\":\"\\/pages\\/order_addcart\\/order_addcart\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/0386120211118153911347.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3a141202111181453495771.png\"],\"name\":\"\\\\u6211\\\\u7684\",\"link\":\"\\/pages\\/user\\/index\"}],\"id\":\"idundefined\"}}', '{\"1643013708659000\":{\"name\":\"headerSerch\",\"timestamp\":1643013708659000,\"setUp\":{\"tabVal\":1},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(63,202,17,1)\"},{\"item\":\"rgba(213,235,87,1)\"}]},\"boxStyle\":{\"title\":\"\\\\u8fb9\\\\u6846\\\\u6837\\\\u5f0f\",\"name\":\"boxStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"hotWords\":{\"list\":[{\"val\":\"\"}]},\"logoConfig\":{\"type\":1,\"header\":\"\\\\u8bbe\\\\u7f6elogo\",\"title\":\"\",\"url\":\"\"},\"id\":\"id1643013708659000\"},\"1643013708659001\":{\"name\":\"swiperBg\",\"timestamp\":1643013708659001,\"setUp\":{\"tabVal\":1},\"swiperConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6750px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u56fe\\\\u7247 \\\\u987a\\\\u5e8f\",\"maxList\":10,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/78c7c20211011171437256.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"isShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u80cc\\\\u666f\\\\u8272\",\"val\":true},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(63,202,17,1)\"},{\"item\":\"rgba(213,235,87,1)\"}]},\"dotColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"docConfig\":{\"cname\":\"swiper\",\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"type\":1,\"list\":[{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u76f4\\\\u7ebf\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u6570\\\\u5b57\",\"icon\":\"iconshuzi\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"txtStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u4f4d\\\\u7f6e\",\"type\":1,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"imgConfig\":{\"cname\":\"docStyle\",\"title\":\"\\\\u8f6e\\\\u64ad\\\\u56fe\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"id\":\"id1637220725797000\"},\"1643013708659002\":{\"name\":\"menus\",\"timestamp\":1643013708659002,\"setUp\":{\"tabVal\":0},\"tabConfig\":{\"title\":\"\\\\u5c55\\\\u793a\\\\u6837\\\\u5f0f\",\"tabVal\":1,\"type\":1,\"tabList\":[{\"name\":\"\\\\u5355\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"icondanhang\"},{\"name\":\"\\\\u591a\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"iconduohang\"}]},\"rowsNum\":{\"title\":\"\\\\u663e\\\\u793a\\\\u884c\\\\u6570\",\"name\":\"rowsNum\",\"type\":0,\"list\":[{\"val\":\"2\\\\u884c\",\"icon\":\"icon2hang\"},{\"val\":\"3\\\\u884c\",\"icon\":\"icon3hang\"},{\"val\":\"4\\\\u884c\",\"icon\":\"icon4hang\"}]},\"menuStyle\":{\"title\":\"\\\\u56fe\\\\u6807\\\\u6837\\\\u5f0f\",\"name\":\"menuStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u65b9\\\\u5f62\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"icondayuanjiao\"}]},\"number\":{\"title\":\"\\\\u663e\\\\u793a\\\\u4e2a\\\\u6570\",\"name\":\"number\",\"type\":2,\"list\":[{\"val\":\"3\\\\u4e2a\",\"icon\":\"icon3ge\"},{\"val\":\"4\\\\u4e2a\",\"icon\":\"icon4ge1\"},{\"val\":\"5\\\\u4e2a\",\"icon\":\"icon5ge1\"}]},\"pointerStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"name\":\"pointerStyle\",\"type\":2,\"list\":[{\"val\":\"\\\\u957f\\\\u6761\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":0,\"min\":0},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea690 * 90px\",\"maxList\":100,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/02d9e202109101106364468.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5546\\\\u57ce\\\\u5206\\\\u7c7b\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_cate\\/goods_cate\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/4a21a202109101106368349.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9886\\\\u4f18\\\\u60e0\\\\u5238\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_coupon\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/49d20202109101106377392.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u884c\\\\u4e1a\\\\u8d44\\\\u8baf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/news_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/1c95120210910110636424.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u780d\\\\u4ef7\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/bargain\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/23332202109101106361126.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79d2\\\\u6740\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/goods_seckill\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/a4c37202109101106353478.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u8ba2\\\\u5355\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/order_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/0047b202109101106374214.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u6211\\\\u8981\\\\u7b7e\\\\u5230\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_sgin\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/f4598202109101106358762.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u62bd\\\\u5956\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/lottery\\/grids\\/index?type=4\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/34f81202109101106357549.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79ef\\\\u5206\\\\u5546\\\\u57ce\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/points_mall\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/f4e7420210910110635959.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5730\\\\u5740\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_address_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"pointerColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"name\":\"pointerColor\",\"default\":[{\"item\":\"#f44\"}],\"color\":[{\"item\":\"#f44\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"titleColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#333333\"}],\"color\":[{\"item\":\"#333333\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"id\":\"id1637220725797001\"},\"1643013708659003\":{\"name\":\"news\",\"timestamp\":1643013708659003,\"setUp\":{\"tabVal\":0},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\",\"style\":\"left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\",\"style\":\"center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\",\"style\":\"right\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#333\"}],\"color\":[{\"item\":\"#333\"}]},\"listConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":10,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u89e3\\\\u8bfb\\\\u793e\\\\u4ea4\\\\u65b0\\\\u96f6\\\\u552e\\\\u4e2d\\\\u7684\\\\u5fae\\\\u4fe1\\\\u5206\\\\u9500\\\\u6a21\\\\u5f0f\",\"max\":30,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc730\\\\u4e2a\\\\u5b57\",\"empty\":true},{\"title\":\"\\\\u94fe\\\\u63a5\",\"val\":\"\\/pages\\/news_details\\/index?id=31\",\"max\":200,\"pla\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u8fde\\\\u63a5\"}]}],\"tabCur\":0},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":1,\"min\":0},\"logoConfig\":{\"header\":\"\\\\u56fe\\\\u6807\\\\u8bbe\\\\u7f6e\",\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6130 * 36px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/c6e3a202111251019162735.png\"},\"id\":\"id1637220725797003\"},\"1643013708659004\":{\"name\":\"activeParty\",\"timestamp\":1643013708659004,\"setUp\":{\"tabVal\":1},\"titleConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u6807\\\\u9898\",\"value\":\"\\\\u8d85\\\\u503c\\\\u7206\\\\u6b3e\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u6807\\\\u9898\",\"max\":10},\"desConfig\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7f8e\\\\u597d\\\\u751f\\\\u6d3b\\\\u7531\\\\u6b64\\\\u5f00\\\\u59cb\",\"place\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u7b80\\\\u4ecb\",\"max\":8},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff0c\\\\u56fe\\\\u7247\\\\u5efa\\\\u8bae\\\\u5c3a\\\\u5bf8140 * 140px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef \\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"maxList\":4,\"list\":[{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/16\\/216aaf8ea5fdeff2a31a48f3e931ff46.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4eca\\\\u65e5\\\\u63a8\\\\u8350\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=114\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/15\\/86e928c002fefb45fc42ae09aec7c86e.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u5e97\\\\u4e3b\\\\u8bda\\\\u610f\\\\u63a8\\\\u8350 \\\\u54c1\\\\u8d28\\\\u5546\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=113\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/09\\/22\\/9e2f6a8072dce6494cf224bc099b0c00.jpg\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u65b0\\\\u54c1\\\\u4e0a\\\\u67b6\\\\u7b49 \\\\u4f60\\\\u6765\\\\u62ff\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=98\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/10\\/59c17202110130919585671.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"value\":\"\\\\u7efc\\\\u5408\\\\u8bc4\\\\u9009\\\\u597d \\\\u4ea7\\\\u54c1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc720\\\\u4e2a\\\\u5b57\",\"max\":20},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_details\\/index?id=101\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fc3c3e\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"bgColor\":{\"title\":\"\\\\u6807\\\\u7b7e\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"},{\"item\":\"rgba(185,211,38,1)\"}]},\"boxColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"boxColor\",\"default\":[{\"item\":\"#ffe5e3\"}],\"color\":[{\"item\":\"rgba(230,248,231,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"id\":\"id1637220725797005\"},\"1643013708659005\":{\"name\":\"bargain\",\"timestamp\":1643013708659005,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"titleColor\":{\"title\":\"\\\\u6807\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#FF6000\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}],\"color\":[{\"item\":\"rgba(230,248,231,1)\"},{\"item\":\"rgba(239,252,239,1)\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"productGap\":{\"title\":\"\\\\u5546\\\\u54c1\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"mbCongfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"id\":\"id1637220725797006\"},\"1643013708659006\":{\"name\":\"combination\",\"timestamp\":1643013708659006,\"setUp\":{\"tabVal\":1},\"numConfig\":{\"val\":6},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"pinkShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u62fc\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"joinShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u53c2\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"rgba(255,68,68,0.1)\"}],\"color\":[{\"item\":\"rgba(230,248,231,1)\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":12,\"min\":0},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/fac24202111181528488411.gif\"},\"id\":\"id1637220725797007\"},\"1643013708659007\":{\"name\":\"seckill\",\"timestamp\":1643013708659007,\"setUp\":{\"tabVal\":1},\"countDownColor\":{\"title\":\"\\\\u5012\\\\u8ba1\\\\u65f6\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"countDownColor\",\"default\":[{\"item\":\"rgba(252,60,62,0.09)\"}],\"color\":[{\"item\":\"rgba(230,248,231,1)\"}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"discountShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6298\\\\u6263\\\\u6807\\\\u7b7e\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"seckillShow\":{\"title\":\"\\\\u62a2\\\\u8d2d\\\\u6807\\\\u7b7e\",\"val\":true},\"numberConfig\":{\"val\":6},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3b72620211118153011706.gif\"},\"id\":\"id1637220725797008\"},\"1643013708659008\":{\"name\":\"promotionList\",\"timestamp\":1643013708659008,\"setUp\":{\"tabVal\":1},\"productList\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u5217\\\\u8868\"},\"titleConfig\":{\"title\":\"\\\\u6807\\\\u9898\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u540d\\\\u79f0\",\"val\":true},\"opriceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u539f\\\\u4ef7\",\"val\":true},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u4ef7\\\\u683c\",\"val\":true},\"couponShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4f18\\\\u60e0\\\\u5238\",\"val\":true},\"tabConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":4,\"tabCur\":3,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u6700\\\\u65b0\\\\u51fa\\\\u7089\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":3,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u731c\\\\u4f60\\\\u559c\\\\u6b22\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":2,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u597d\\\\u8bc4\\\\u5982\\\\u4e91\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":1,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u591a\\\\u4e70\\\\u591a\\\\u7701\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":4,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#F95429\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"fontColor\":{\"title\":\"\\\\u4ef7\\\\u683c\\\\u989c\\\\u8272\",\"name\":\"fontColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(255,118,0,1)\"}]},\"labelColor\":{\"title\":\"\\\\u6d3b\\\\u52a8\\\\u6807\\\\u7b7e\",\"name\":\"labelColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"rgba(255,118,0,1)\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"numConfig\":{\"val\":20},\"id\":\"id1637220725797009\"},\"1643013724416000\":{\"name\":\"pageFoot\",\"setUp\":{\"tabVal\":0},\"status\":{\"title\":\"\\\\u662f\\\\u5426\\\\u81ea\\\\u5b9a\\\\u4e49\",\"name\":\"status\",\"status\":false},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#282828\"}],\"color\":[{\"item\":\"rgba(51,51,51,1)\"}]},\"activeTxtColor\":{\"title\":\"\\\\u9009\\\\u4e2d\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#F62C2C\"}],\"color\":[{\"item\":\"rgba(66,202,77,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"menuList\":[{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/9d22e202111181538437101.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/aaf1b202111181452576658.png\"],\"name\":\"\\\\u9996\\\\u9875\",\"link\":\"\\/pages\\/index\\/index\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/88386202111181539257153.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/59d4a202111181453208678.png\"],\"name\":\"\\\\u5206\\\\u7c7b\",\"link\":\"\\/pages\\/goods_cate\\/goods_cate\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/189a2202111181538583033.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/201c9202111181453378599.png\"],\"name\":\"\\\\u8d2d\\\\u7269\\\\u8f66\",\"link\":\"\\/pages\\/order_addcart\\/order_addcart\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/0386120211118153911347.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3a141202111181453495771.png\"],\"name\":\"\\\\u6211\\\\u7684\",\"link\":\"\\/pages\\/user\\/index\"}],\"id\":\"idundefined\"}}', 1637218684, 1643013796, 0, 2, 1, 0, 0, '#f5f5f5', '', 0, 0, 0, 0, 1),
(NULL, '1.0', '橘色主题模板', '', '{\"1643013051450000\":{\"name\":\"headerSerch\",\"timestamp\":1643013051450000,\"setUp\":{\"tabVal\":0},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}]},\"boxStyle\":{\"title\":\"\\\\u8fb9\\\\u6846\\\\u6837\\\\u5f0f\",\"name\":\"boxStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"hotWords\":{\"list\":[{\"val\":\"\"}]},\"logoConfig\":{\"type\":1,\"header\":\"\\\\u8bbe\\\\u7f6elogo\",\"title\":\"\",\"url\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/28a88411581c15dbc65fa4fa8e0d0c17.png\"},\"id\":\"id1643013051450000\"},\"1643013051450001\":{\"name\":\"tabNav\",\"timestamp\":1643013051450001,\"status\":{\"title\":\"\\\\u5f00\\\\u5173\",\"default\":{\"status\":false}},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013051450001\"},\"1643013051450002\":{\"name\":\"swiperBg\",\"timestamp\":1643013051450002,\"setUp\":{\"tabVal\":0},\"swiperConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6750px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u56fe\\\\u7247 \\\\u987a\\\\u5e8f\",\"maxList\":10,\"list\":[{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/d54f87813d22fc2ce2e6e231f3bcfae7.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/index\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/84578abf1060697da0529a5ccc50934c.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"2\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/index\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"isShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u80cc\\\\u666f\\\\u8272\",\"val\":true},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}]},\"dotColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"docConfig\":{\"cname\":\"swiper\",\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u76f4\\\\u7ebf\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u6570\\\\u5b57\",\"icon\":\"iconshuzi\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"txtStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"imgConfig\":{\"cname\":\"docStyle\",\"title\":\"\\\\u8f6e\\\\u64ad\\\\u56fe\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"id\":\"id1643013051450002\"},\"1643013051450003\":{\"name\":\"menus\",\"timestamp\":1643013051450003,\"setUp\":{\"tabVal\":0},\"tabConfig\":{\"title\":\"\\\\u5c55\\\\u793a\\\\u6837\\\\u5f0f\",\"tabVal\":1,\"type\":1,\"tabList\":[{\"name\":\"\\\\u5355\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"icondanhang\"},{\"name\":\"\\\\u591a\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"iconduohang\"}]},\"rowsNum\":{\"title\":\"\\\\u663e\\\\u793a\\\\u884c\\\\u6570\",\"name\":\"rowsNum\",\"type\":0,\"list\":[{\"val\":\"2\\\\u884c\",\"icon\":\"icon2hang\"},{\"val\":\"3\\\\u884c\",\"icon\":\"icon3hang\"},{\"val\":\"4\\\\u884c\",\"icon\":\"icon4hang\"}]},\"menuStyle\":{\"title\":\"\\\\u56fe\\\\u6807\\\\u6837\\\\u5f0f\",\"name\":\"menuStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u65b9\\\\u5f62\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"icondayuanjiao\"}]},\"number\":{\"title\":\"\\\\u663e\\\\u793a\\\\u4e2a\\\\u6570\",\"name\":\"number\",\"type\":2,\"list\":[{\"val\":\"3\\\\u4e2a\",\"icon\":\"icon3ge\"},{\"val\":\"4\\\\u4e2a\",\"icon\":\"icon4ge1\"},{\"val\":\"5\\\\u4e2a\",\"icon\":\"icon5ge1\"}]},\"pointerStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"name\":\"pointerStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u957f\\\\u6761\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":0,\"min\":0},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea690 * 90px\",\"maxList\":100,\"list\":[{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/32c4cb933e83faef5a74e4d144301cd9.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5546\\\\u54c1\\\\u5206\\\\u7c7b\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_cate\\/goods_cate\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/b7e8c6d6ff3cd09f96fde82bb84f01cc.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9886\\\\u4f18\\\\u60e0\\\\u5238\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_get_coupon\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/3c379782da41503cd2ee5836e221e168.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u884c\\\\u4e1a\\\\u8d44\\\\u8baf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/news_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/4cf6e92566e1a2f0f12bd94e0b6e8de8.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u6211\\\\u7684\\\\u6536\\\\u85cf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_goods_collection\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/6051ac070afdc33341f9e89f998d3576.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5730\\\\u5740\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_address_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/c4aa268853c2b03716444a7c91428b4b.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79d2\\\\u6740\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/goods_seckill\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/43f81463ae2477a627c4657e72559134.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u780d\\\\u4ef7\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/goods_bargain\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/b91e5277a5c9a89d74b07f3a7b7400d1.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u62fc\\\\u56e2\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/goods_combination\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/84127a55428452cbc2d8c30f8d474228.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9884\\\\u552e\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/presell\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/1837e57bf9d5eff1203d310939fb8f8a.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79ef\\\\u5206\\\\u5546\\\\u57ce\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/points_mall\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"pointerColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"name\":\"pointerColor\",\"default\":[{\"item\":\"#f44\"}],\"color\":[{\"item\":\"#f44\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"titleColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#333333\"}],\"color\":[{\"item\":\"#333333\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013051450003\"},\"1643013051450004\":{\"name\":\"news\",\"timestamp\":1643013051450004,\"setUp\":{\"tabVal\":0},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\",\"style\":\"left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\",\"style\":\"center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\",\"style\":\"right\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#333\"}],\"color\":[{\"item\":\"#333\"}]},\"listConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":10,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u6807\\\\u9898\",\"max\":30,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc730\\\\u4e2a\\\\u5b57\",\"empty\":true},{\"title\":\"\\\\u94fe\\\\u63a5\",\"val\":\"\\/pages\\/index\\/index\",\"max\":200,\"pla\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u8fde\\\\u63a5\"}]}],\"tabCur\":0},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":0,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"logoConfig\":{\"header\":\"\\\\u56fe\\\\u6807\\\\u8bbe\\\\u7f6e\",\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6130 * 36px\",\"url\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/66fe3edcff404253d4383a5e6ff66e82.png\"},\"id\":\"id1643013051450004\"},\"1643013051450005\":{\"name\":\"coupon\",\"timestamp\":1643013051450005,\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#F8F8F8\"}],\"color\":[{\"item\":\"#F8F8F8\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":0,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"#E93323\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013051450005\"},\"1643013051450007\":{\"name\":\"seckill\",\"timestamp\":1643013051450007,\"setUp\":{\"tabVal\":0},\"countDownColor\":{\"title\":\"\\\\u5012\\\\u8ba1\\\\u65f6\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"countDownColor\",\"default\":[{\"item\":\"rgba(252,60,62,0.09)\"}],\"color\":[{\"item\":\"rgba(252,60,62,0.09)\"}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"#E93323\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"discountShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6298\\\\u6263\\\\u6807\\\\u7b7e\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"seckillShow\":{\"title\":\"\\\\u62a2\\\\u8d2d\\\\u6807\\\\u7b7e\",\"val\":true},\"numberConfig\":{\"val\":3},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"http:\\/\\/pro.crmeb.net\\/static\\/images\\/spike-icon-002.gif\"},\"id\":\"id1643013051450007\"},\"1643013051450008\":{\"name\":\"bargain\",\"timestamp\":1643013051450008,\"setUp\":{\"tabVal\":0},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"#E93323\"}]},\"titleColor\":{\"title\":\"\\\\u6807\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#FF6000\"}],\"color\":[{\"item\":\"#FF6000\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}],\"color\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"productGap\":{\"title\":\"\\\\u5546\\\\u54c1\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"mbCongfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013051450008\"},\"1643013051450009\":{\"name\":\"combination\",\"timestamp\":1643013051450009,\"setUp\":{\"tabVal\":0},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"pinkShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u62fc\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"joinShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u53c2\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"rgba(255,68,68,0.1)\"}],\"color\":[{\"item\":\"rgba(255,68,68,0.1)\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"#E93323\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"http:\\/\\/pro.crmeb.net\\/static\\/images\\/group02.gif\"},\"id\":\"id1643013051450009\"},\"1643013051450010\":{\"name\":\"promotionList\",\"timestamp\":1643013051450010,\"setUp\":{\"tabVal\":0},\"productList\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u5217\\\\u8868\"},\"titleConfig\":{\"title\":\"\\\\u6807\\\\u9898\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u540d\\\\u79f0\",\"val\":true},\"opriceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u539f\\\\u4ef7\",\"val\":true},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u4ef7\\\\u683c\",\"val\":true},\"couponShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4f18\\\\u60e0\\\\u5238\",\"val\":true},\"tabConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":4,\"tabCur\":3,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u6700\\\\u65b0\\\\u51fa\\\\u7089\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":3,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u731c\\\\u4f60\\\\u559c\\\\u6b22\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":1,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u597d\\\\u8bc4\\\\u5982\\\\u4e91\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":2,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u591a\\\\u4e70\\\\u591a\\\\u9500\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":4,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#F95429\"}],\"color\":[{\"item\":\"#F95429\"}]},\"fontColor\":{\"title\":\"\\\\u4ef7\\\\u683c\\\\u989c\\\\u8272\",\"name\":\"fontColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"#e93323\"}]},\"labelColor\":{\"title\":\"\\\\u6d3b\\\\u52a8\\\\u6807\\\\u7b7e\",\"name\":\"labelColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"#e93323\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"numConfig\":{\"val\":6},\"id\":\"id1643013051450010\"},\"1643013666018000\":{\"name\":\"pageFoot\",\"setUp\":{\"tabVal\":0},\"status\":{\"title\":\"\\\\u662f\\\\u5426\\\\u81ea\\\\u5b9a\\\\u4e49\",\"name\":\"status\",\"status\":false},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#282828\"}],\"color\":[{\"item\":\"rgba(51,51,51,1)\"}]},\"activeTxtColor\":{\"title\":\"\\\\u9009\\\\u4e2d\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#F62C2C\"}],\"color\":[{\"item\":\"rgba(254,92,45,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"menuList\":[{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/44cdf202111221541497320.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/aaf1b202111181452576658.png\"],\"name\":\"\\\\u9996\\\\u9875\",\"link\":\"\\/pages\\/index\\/index\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/37ef1202111221542206434.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/59d4a202111181453208678.png\"],\"name\":\"\\\\u5206\\\\u7c7b\",\"link\":\"\\/pages\\/goods_cate\\/goods_cate\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/09bcd202111221542207036.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/201c9202111181453378599.png\"],\"name\":\"\\\\u8d2d\\\\u7269\\\\u8f66\",\"link\":\"\\/pages\\/order_addcart\\/order_addcart\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/f2790202111221541549352.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3a141202111181453495771.png\"],\"name\":\"\\\\u6211\\\\u7684\",\"link\":\"\\/pages\\/user\\/index\"}],\"id\":\"idundefined\"}}', '{\"1643013051450000\":{\"name\":\"headerSerch\",\"timestamp\":1643013051450000,\"setUp\":{\"tabVal\":0},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}]},\"boxStyle\":{\"title\":\"\\\\u8fb9\\\\u6846\\\\u6837\\\\u5f0f\",\"name\":\"boxStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"hotWords\":{\"list\":[{\"val\":\"\"}]},\"logoConfig\":{\"type\":1,\"header\":\"\\\\u8bbe\\\\u7f6elogo\",\"title\":\"\",\"url\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/28a88411581c15dbc65fa4fa8e0d0c17.png\"},\"id\":\"id1643013051450000\"},\"1643013051450001\":{\"name\":\"tabNav\",\"timestamp\":1643013051450001,\"status\":{\"title\":\"\\\\u5f00\\\\u5173\",\"default\":{\"status\":false}},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013051450001\"},\"1643013051450002\":{\"name\":\"swiperBg\",\"timestamp\":1643013051450002,\"setUp\":{\"tabVal\":0},\"swiperConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6750px\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u56fe\\\\u7247 \\\\u987a\\\\u5e8f\",\"maxList\":10,\"list\":[{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/d54f87813d22fc2ce2e6e231f3bcfae7.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"1\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/index\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/84578abf1060697da0529a5ccc50934c.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"2\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/index\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"isShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u80cc\\\\u666f\\\\u8272\",\"val\":true},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272(\\\\u6e10\\\\u53d8)\",\"default\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}],\"color\":[{\"item\":\"#F62C2C\"},{\"item\":\"#F96E29\"}]},\"dotColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"docConfig\":{\"cname\":\"swiper\",\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u76f4\\\\u7ebf\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u6570\\\\u5b57\",\"icon\":\"iconshuzi\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"txtStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"imgConfig\":{\"cname\":\"docStyle\",\"title\":\"\\\\u8f6e\\\\u64ad\\\\u56fe\\\\u6837\\\\u5f0f\",\"type\":0,\"list\":[{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"},{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"}]},\"id\":\"id1643013051450002\"},\"1643013051450003\":{\"name\":\"menus\",\"timestamp\":1643013051450003,\"setUp\":{\"tabVal\":0},\"tabConfig\":{\"title\":\"\\\\u5c55\\\\u793a\\\\u6837\\\\u5f0f\",\"tabVal\":1,\"type\":1,\"tabList\":[{\"name\":\"\\\\u5355\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"icondanhang\"},{\"name\":\"\\\\u591a\\\\u884c\\\\u5c55\\\\u793a\",\"icon\":\"iconduohang\"}]},\"rowsNum\":{\"title\":\"\\\\u663e\\\\u793a\\\\u884c\\\\u6570\",\"name\":\"rowsNum\",\"type\":0,\"list\":[{\"val\":\"2\\\\u884c\",\"icon\":\"icon2hang\"},{\"val\":\"3\\\\u884c\",\"icon\":\"icon3hang\"},{\"val\":\"4\\\\u884c\",\"icon\":\"icon4hang\"}]},\"menuStyle\":{\"title\":\"\\\\u56fe\\\\u6807\\\\u6837\\\\u5f0f\",\"name\":\"menuStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u65b9\\\\u5f62\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"icondayuanjiao\"}]},\"number\":{\"title\":\"\\\\u663e\\\\u793a\\\\u4e2a\\\\u6570\",\"name\":\"number\",\"type\":2,\"list\":[{\"val\":\"3\\\\u4e2a\",\"icon\":\"icon3ge\"},{\"val\":\"4\\\\u4e2a\",\"icon\":\"icon4ge1\"},{\"val\":\"5\\\\u4e2a\",\"icon\":\"icon5ge1\"}]},\"pointerStyle\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u6837\\\\u5f0f\",\"name\":\"pointerStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u957f\\\\u6761\",\"icon\":\"iconSquarepoint\"},{\"val\":\"\\\\u5706\\\\u5f62\",\"icon\":\"iconDot\"},{\"val\":\"\\\\u65e0\\\\u6307\\\\u793a\\\\u5668\",\"icon\":\"iconjinyong\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":0,\"min\":0},\"menuConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea690 * 90px\",\"maxList\":100,\"list\":[{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/32c4cb933e83faef5a74e4d144301cd9.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5546\\\\u54c1\\\\u5206\\\\u7c7b\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/goods_cate\\/goods_cate\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/b7e8c6d6ff3cd09f96fde82bb84f01cc.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9886\\\\u4f18\\\\u60e0\\\\u5238\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_get_coupon\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/3c379782da41503cd2ee5836e221e168.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u884c\\\\u4e1a\\\\u8d44\\\\u8baf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/news_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/4cf6e92566e1a2f0f12bd94e0b6e8de8.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u6211\\\\u7684\\\\u6536\\\\u85cf\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_goods_collection\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/6051ac070afdc33341f9e89f998d3576.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u5730\\\\u5740\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/users\\/user_address_list\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/c4aa268853c2b03716444a7c91428b4b.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79d2\\\\u6740\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/goods_seckill\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/43f81463ae2477a627c4657e72559134.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u780d\\\\u4ef7\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/goods_bargain\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/b91e5277a5c9a89d74b07f3a7b7400d1.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u62fc\\\\u56e2\\\\u6d3b\\\\u52a8\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/goods_combination\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/84127a55428452cbc2d8c30f8d474228.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u9884\\\\u552e\\\\u7ba1\\\\u7406\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/activity\\/presell\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]},{\"img\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/1837e57bf9d5eff1203d310939fb8f8a.png\",\"info\":[{\"title\":\"\\\\u6807\\\\u9898\",\"value\":\"\\\\u79ef\\\\u5206\\\\u5546\\\\u57ce\",\"tips\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc74\\\\u4e2a\\\\u5b57\",\"max\":4},{\"title\":\"\\\\u94fe\\\\u63a5\",\"value\":\"\\/pages\\/points_mall\\/index\",\"tips\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u94fe\\\\u63a5\",\"max\":100}]}]},\"pointerColor\":{\"title\":\"\\\\u6307\\\\u793a\\\\u5668\\\\u989c\\\\u8272\",\"name\":\"pointerColor\",\"default\":[{\"item\":\"#f44\"}],\"color\":[{\"item\":\"#f44\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"titleColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#333333\"}],\"color\":[{\"item\":\"#333333\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013051450003\"},\"1643013051450004\":{\"name\":\"news\",\"timestamp\":1643013051450004,\"setUp\":{\"tabVal\":0},\"txtStyle\":{\"title\":\"\\\\u6587\\\\u672c\\\\u4f4d\\\\u7f6e\",\"name\":\"txtStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\",\"style\":\"left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\",\"style\":\"center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\",\"style\":\"right\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#333\"}],\"color\":[{\"item\":\"#333\"}]},\"listConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a010\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":10,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u6807\\\\u9898\",\"max\":30,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc730\\\\u4e2a\\\\u5b57\",\"empty\":true},{\"title\":\"\\\\u94fe\\\\u63a5\",\"val\":\"\\/pages\\/index\\/index\",\"max\":200,\"pla\":\"\\\\u8bf7\\\\u8f93\\\\u5165\\\\u8fde\\\\u63a5\"}]}],\"tabCur\":0},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":0,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"logoConfig\":{\"header\":\"\\\\u56fe\\\\u6807\\\\u8bbe\\\\u7f6e\",\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea6130 * 36px\",\"url\":\"https:\\/\\/data44.wuht.net\\/\\/uploads\\/attach\\/2022\\/01\\/20220115\\/66fe3edcff404253d4383a5e6ff66e82.png\"},\"id\":\"id1643013051450004\"},\"1643013051450005\":{\"name\":\"coupon\",\"timestamp\":1643013051450005,\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#F8F8F8\"}],\"color\":[{\"item\":\"#F8F8F8\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":0,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":0,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u989c\\\\u8272\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"#E93323\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013051450005\"},\"1643013051450007\":{\"name\":\"seckill\",\"timestamp\":1643013051450007,\"setUp\":{\"tabVal\":0},\"countDownColor\":{\"title\":\"\\\\u5012\\\\u8ba1\\\\u65f6\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"countDownColor\",\"default\":[{\"item\":\"rgba(252,60,62,0.09)\"}],\"color\":[{\"item\":\"rgba(252,60,62,0.09)\"}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"#E93323\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"discountShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6298\\\\u6263\\\\u6807\\\\u7b7e\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"seckillShow\":{\"title\":\"\\\\u62a2\\\\u8d2d\\\\u6807\\\\u7b7e\",\"val\":true},\"numberConfig\":{\"val\":3},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"http:\\/\\/pro.crmeb.net\\/static\\/images\\/spike-icon-002.gif\"},\"id\":\"id1643013051450007\"},\"1643013051450008\":{\"name\":\"bargain\",\"timestamp\":1643013051450008,\"setUp\":{\"tabVal\":0},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"#E93323\"}]},\"titleColor\":{\"title\":\"\\\\u6807\\\\u9898\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#FF6000\"}],\"color\":[{\"item\":\"#FF6000\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}],\"color\":[{\"item\":\"#FDDBB2\"},{\"item\":\"#FDEFC6\"}]},\"bgStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"bgStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"productGap\":{\"title\":\"\\\\u5546\\\\u54c1\\\\u95f4\\\\u8ddd\",\"val\":10,\"min\":0},\"mbCongfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"id\":\"id1643013051450008\"},\"1643013051450009\":{\"name\":\"combination\",\"timestamp\":1643013051450009,\"setUp\":{\"tabVal\":0},\"numConfig\":{\"val\":3},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4ef7\\\\u683c\",\"val\":true},\"bntShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u6309\\\\u94ae\",\"val\":true},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u540d\\\\u79f0\",\"val\":true},\"pinkShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u62fc\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"joinShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u53c2\\\\u56e2\\\\u6807\\\\u7b7e\",\"val\":true},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u80cc\\\\u666f\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"rgba(255,68,68,0.1)\"}],\"color\":[{\"item\":\"rgba(255,68,68,0.1)\"}]},\"conStyle\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u6837\\\\u5f0f\",\"name\":\"conStyle\",\"type\":1,\"list\":[{\"val\":\"\\\\u76f4\\\\u89d2\",\"icon\":\"iconPic_square\"},{\"val\":\"\\\\u5706\\\\u89d2\",\"icon\":\"iconPic_fillet\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"prConfig\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#E93323\"}],\"color\":[{\"item\":\"#E93323\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"lrConfig\":{\"title\":\"\\\\u5de6\\\\u53f3\\\\u8fb9\\\\u8ddd\",\"val\":10,\"min\":0},\"imgConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a01\\\\u5f20\\\\u56fe\\\\u7247\\\\uff0c\\\\u5efa\\\\u8bae\\\\u5bbd\\\\u5ea618 * 18px\",\"url\":\"http:\\/\\/pro.crmeb.net\\/static\\/images\\/group02.gif\"},\"id\":\"id1643013051450009\"},\"1643013051450010\":{\"name\":\"promotionList\",\"timestamp\":1643013051450010,\"setUp\":{\"tabVal\":0},\"productList\":{\"title\":\"\\\\u4fc3\\\\u9500\\\\u5217\\\\u8868\"},\"titleConfig\":{\"title\":\"\\\\u6807\\\\u9898\\\\u4f4d\\\\u7f6e\",\"type\":0,\"list\":[{\"val\":\"\\\\u5c45\\\\u5de6\",\"icon\":\"icondoc_left\"},{\"val\":\"\\\\u5c45\\\\u4e2d\",\"icon\":\"icondoc_center\"},{\"val\":\"\\\\u5c45\\\\u53f3\",\"icon\":\"icondoc_right\"}]},\"titleShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u540d\\\\u79f0\",\"val\":true},\"opriceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u539f\\\\u4ef7\",\"val\":true},\"priceShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u5546\\\\u54c1\\\\u4ef7\\\\u683c\",\"val\":true},\"couponShow\":{\"title\":\"\\\\u662f\\\\u5426\\\\u663e\\\\u793a\\\\u4f18\\\\u60e0\\\\u5238\",\"val\":true},\"tabConfig\":{\"title\":\"\\\\u6700\\\\u591a\\\\u53ef\\\\u6dfb\\\\u52a04\\\\u4e2a\\\\u7248\\\\u5757\\\\uff1b\\\\u9f20\\\\u6807\\\\u62d6\\\\u62fd\\\\u5de6\\\\u4fa7\\\\u5706\\\\u70b9\\\\u53ef\\\\u8c03\\\\u6574\\\\u7248\\\\u5757\\\\u987a\\\\u5e8f\",\"max\":4,\"tabCur\":3,\"list\":[{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u6700\\\\u65b0\\\\u51fa\\\\u7089\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":3,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u731c\\\\u4f60\\\\u559c\\\\u6b22\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":1,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u597d\\\\u8bc4\\\\u5982\\\\u4e91\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":2,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}},{\"chiild\":[{\"title\":\"\\\\u6807\\\\u9898\",\"val\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"},{\"title\":\"\\\\u7b80\\\\u4ecb\",\"val\":\"\\\\u591a\\\\u4e70\\\\u591a\\\\u9500\",\"max\":4,\"pla\":\"\\\\u9009\\\\u586b\\\\uff0c\\\\u4e0d\\\\u8d85\\\\u8fc7\\\\u56db\\\\u4e2a\\\\u5b57\"}],\"link\":{\"title\":\"\\\\u94fe\\\\u63a5\",\"activeVal\":4,\"optiops\":[{\"type\":0,\"value\":1,\"label\":\"\\\\u7cbe\\\\u54c1\\\\u63a8\\\\u8350\"},{\"type\":1,\"value\":2,\"label\":\"\\\\u70ed\\\\u95e8\\\\u699c\\\\u5355\"},{\"type\":2,\"value\":3,\"label\":\"\\\\u9996\\\\u53d1\\\\u65b0\\\\u54c1\"},{\"type\":3,\"value\":4,\"label\":\"\\\\u4fc3\\\\u9500\\\\u5355\\\\u54c1\"}]}}]},\"themeColor\":{\"title\":\"\\\\u4e3b\\\\u9898\\\\u98ce\\\\u683c\",\"name\":\"themeColor\",\"default\":[{\"item\":\"#F95429\"}],\"color\":[{\"item\":\"#F95429\"}]},\"fontColor\":{\"title\":\"\\\\u4ef7\\\\u683c\\\\u989c\\\\u8272\",\"name\":\"fontColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"#e93323\"}]},\"labelColor\":{\"title\":\"\\\\u6d3b\\\\u52a8\\\\u6807\\\\u7b7e\",\"name\":\"labelColor\",\"default\":[{\"item\":\"#e93323\"}],\"color\":[{\"item\":\"#e93323\"}]},\"mbConfig\":{\"title\":\"\\\\u9875\\\\u9762\\\\u95f4\\\\u8ddd\",\"val\":0,\"min\":0},\"numConfig\":{\"val\":6},\"id\":\"id1643013051450010\"},\"1643013666018000\":{\"name\":\"pageFoot\",\"setUp\":{\"tabVal\":0},\"status\":{\"title\":\"\\\\u662f\\\\u5426\\\\u81ea\\\\u5b9a\\\\u4e49\",\"name\":\"status\",\"status\":false},\"txtColor\":{\"title\":\"\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#282828\"}],\"color\":[{\"item\":\"rgba(51,51,51,1)\"}]},\"activeTxtColor\":{\"title\":\"\\\\u9009\\\\u4e2d\\\\u6587\\\\u5b57\\\\u989c\\\\u8272\",\"name\":\"txtColor\",\"default\":[{\"item\":\"#F62C2C\"}],\"color\":[{\"item\":\"rgba(254,92,45,1)\"}]},\"bgColor\":{\"title\":\"\\\\u80cc\\\\u666f\\\\u989c\\\\u8272\",\"name\":\"bgColor\",\"default\":[{\"item\":\"#fff\"}],\"color\":[{\"item\":\"#fff\"}]},\"menuList\":[{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/44cdf202111221541497320.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/aaf1b202111181452576658.png\"],\"name\":\"\\\\u9996\\\\u9875\",\"link\":\"\\/pages\\/index\\/index\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/37ef1202111221542206434.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/59d4a202111181453208678.png\"],\"name\":\"\\\\u5206\\\\u7c7b\",\"link\":\"\\/pages\\/goods_cate\\/goods_cate\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/09bcd202111221542207036.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/201c9202111181453378599.png\"],\"name\":\"\\\\u8d2d\\\\u7269\\\\u8f66\",\"link\":\"\\/pages\\/order_addcart\\/order_addcart\"},{\"imgList\":[\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/f2790202111221541549352.png\",\"https:\\/\\/qiniu.crmeb.net\\/attach\\/2021\\/11\\/3a141202111181453495771.png\"],\"name\":\"\\\\u6211\\\\u7684\",\"link\":\"\\/pages\\/user\\/index\"}],\"id\":\"idundefined\"}}', 1637566882, 1643013673, 0, 2, 1, 0, 0, '#f5f5f5', '', 0, 0, 0, 0, 1);
SQL
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "page_categroy",
                'sql' => "truncate table `@table`"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "page_categroy",
                'sql' => <<<SQL
INSERT INTO `@table` (`id`, `pid`, `type`, `name`, `sort`, `status`, `add_time`) VALUES
(1, 0, 'link', '商城页面', 99, 1, 1626831994),
(2, 0, 'product', '商品页面', 98, 1, 1626831994),
(3, 0, 'article', '文章页面', 98, 1, 1626831994),
(4, 0, 'custom', '自定义', 97, 1, 1626831994),
(5, 1, 'link', '商城链接', 99, 1, 1626831994),
(6, 1, 'marketing_link', '营销链接', 98, 1, 1626831994),
(7, 1, 'special', '专题页', 97, 1, 1626831994),
(8, 2, 'product_category', '商品分类', 98, 1, 1626831994),
(9, 2, 'product', '商品', 97, 1, 1626831994),
(10, 2, 'seckill', '秒杀商品', 96, 1, 1626831994),
(11, 2, 'bargain', '砍价商品', 95, 1, 1626831994),
(12, 2, 'combination', '拼团商品', 94, 1, 1626831994),
(13, 2, 'integral', '积分商品', 93, 1, 1626831994),
(14, 3, 'news', '文章', 99, 1, 1626831994),
(15, 4, 'custom', '自定义链接', 99, 1, 1626831994)
SQL
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "page_link",
                'sql' => "truncate table `@table`"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "page_link",
                'sql' => <<<SQL
INSERT INTO `@table` (`id`, `cate_id`, `type`, `name`, `url`, `param`, `example`, `status`, `sort`, `add_time`) VALUES
(1, 6, 5, '抽奖中奖记录', '/pages/activity/lottery/grids/record', ' ', '/pages/activity/lottery/grids/record', 1, 0, 1626837579),
(2, 6, 4, '积分兑换商品列表', '/pages/points_mall/integral_goods_list', ' ', '/pages/points_mall/integral_goods_list', 1, 0, 1626837579),
(3, 6, 4, '积分兑换记录', '/pages/points_mall/exchange_record', ' ', '/pages/points_mall/exchange_record', 1, 0, 1626837579),
(4, 6, 5, '积分抽奖', '/pages/activity/lottery/grids/index?type=1', 'type=1 积分抽奖 type=5订单评价', '/pages/activity/lottery/grids/index?type=1', 1, 0, 1626837579),
(5, 6, 5, '订单支付抽奖', '/pages/activity/lottery/grids/index?type=3', 'type=1 积分抽奖 type=5订单评价', '/pages/activity/lottery/grids/index?type=3', 1, 0, 1626837579),
(7, 6, 5, '订单评价抽奖', '/pages/activity/lottery/grids/index?type=5', 'type=1 积分抽奖 type=5订单评价', '/pages/activity/lottery/grids/index?type=5', 1, 0, 1626837579),
(8, 6, 4, '积分商城', '/pages/points_mall/index', ' ', '/pages/points_mall/index', 1, 0, 1626837579),
(9, 5, 3, '付费会员', '/pages/annex/vip_paid/index', ' ', '/pages/annex/vip_paid/index', 1, 0, 1626837579),
(10, 5, 3, '收银页面', '/pages/annex/offline_pay/index', ' ', '/pages/annex/offline_pay/index', 1, 0, 1626837579),
(11, 5, 3, '充值页面', '/pages/users/user_payment/index', ' ', '/pages/users/user_payment/index', 1, 0, 1626837579),
(12, 5, 3, '订单核销', '/pages/admin/order_cancellation/index', ' ', '/pages/admin/order_cancellation/index', 1, 0, 1626837579),
(13, 5, 3, '统计管理', '/pages/admin/order/index', ' ', '/pages/admin/order/index', 1, 0, 1626837579),
(14, 5, 3, '联系客服', '/pages/customer_list/chat', ' ', '/pages/customer_list/chat', 1, 0, 1626837579),
(15, 5, 3, '佣金排行', '/pages/users/commission_rank/index', ' ', '/pages/users/commission_rank/index', 1, 0, 1626837579),
(16, 5, 3, '推广人排行', '/pages/users/promoter_rank/index', ' ', '/pages/users/promoter_rank/index', 1, 0, 1626837579),
(17, 5, 3, '推广人订单', '/pages/users/promoter-order/index', ' ', '/pages/users/promoter-order/index', 1, 0, 1626837579),
(18, 5, 2, '推广人列表', '/pages/users/promoter-list/index', ' ', '/pages/users/promoter-list/index', 1, 0, 1626837579),
(19, 5, 2, '分销海报', '/pages/users/user_spread_code/index', ' ', '/pages/users/user_spread_code/index', 1, 0, 1626837579),
(20, 5, 3, '提现页面', '/pages/users/user_cash/index', ' ', '/pages/users/user_cash/index', 1, 0, 1626837579),
(21, 5, 2, '我的推广', '/pages/users/user_spread_user/index', ' ', '/pages/users/user_spread_user/index', 1, 0, 1626837579),
(22, 6, 2, '砍价记录', '/pages/activity/bargain/index', ' ', '/pages/activity/bargain/index', 1, 0, 1626837579),
(23, 5, 3, '用户等级', '/pages/users/user_vip/index', ' ', '/pages/users/user_vip/index', 1, 0, 1626837579),
(24, 5, 1, '退款列表', '/pages/users/user_return_list/index', ' ', '/pages/users/user_return_list/index', 1, 0, 1626837579),
(25, 6, 0, '我的优惠券', '/pages/users/user_coupon/index', ' ', '/pages/users/user_coupon/index', 1, 0, 1626837579),
(26, 5, 1, '我的订单', '/pages/users/order_list/index', ' ', '/pages/users/order_list/index', 1, 0, 1626837579),
(27, 6, 4, '积分详情', '/pages/users/user_integral/index', ' ', '/pages/users/user_integral/index', 1, 0, 1626837579),
(28, 5, 3, '个人资料', '/pages/users/user_info/index', ' ', '/pages/users/user_info/index', 1, 0, 1626837579),
(29, 5, 3, '我的账户', '/pages/users/user_money/index', ' ', '/pages/users/user_money/index', 1, 0, 1626837579),
(30, 5, 3, '地址列表', '/pages/users/user_address_list/index', ' ', '/pages/users/user_address_list/index', 1, 0, 1626837579),
(31, 6, 2, '砍价列表', '/pages/activity/goods_bargain/index', ' ', '/pages/activity/goods_bargain/index', 1, 0, 1626837579),
(32, 6, 1, '秒杀列表', '/pages/activity/goods_seckill/index', ' ', '/pages/activity/goods_seckill/index', 1, 0, 1626837579),
(34, 6, 3, '拼团列表', '/pages/activity/goods_combination/index', ' ', '/pages/activity/goods_combination/index', 1, 0, 1626837579),
(35, 5, 3, '收藏页面', '/pages/users/user_goods_collection/index', ' ', '/pages/users/user_goods_collection/index', 1, 0, 1626837579),
(36, 5, 3, '签到页面', '/pages/users/user_sgin/index', ' ', '/pages/users/user_sgin/index', 1, 0, 1626837579),
(37, 5, 1, '精品推荐', '/pages/columnGoods/HotNewGoods/index?type=1&name=精品推荐', 'type=类型ID，1=精品推荐，2=热门榜单，3=首发新品，4=促销单品', '/pages/columnGoods/HotNewGoods/index?type=1&name=精品推荐', 1, 0, 1626837579),
(38, 5, 1, '热门榜单', '/pages/columnGoods/HotNewGoods/index?type=2&name=热门榜单', 'type=类型ID，1=精品推荐，2=热门榜单，3=首发新品，4=促销单品', '/pages/columnGoods/HotNewGoods/index?type=2&name=热门榜单', 1, 0, 1626837579),
(39, 5, 1, '首发新品', '/pages/columnGoods/HotNewGoods/index?type=3&name=首发新品', 'type=类型ID，1=精品推荐，2=热门榜单，3=首发新品，4=促销单品', '/pages/columnGoods/HotNewGoods/index?type=3&name=首发新品', 1, 0, 1626837579),
(40, 5, 1, '促销单品', '/pages/columnGoods/HotNewGoods/index?type=4&name=促销单品', 'type=类型ID，1=精品推荐，2=热门榜单，3=首发新品，4=促销单品', '/pages/columnGoods/HotNewGoods/index?type=4&name=促销单品', 1, 0, 1626837579),
(41, 6, 0, '优惠券列表', '/pages/users/user_get_coupon/index', ' ', '/pages/users/user_get_coupon/index', 1, 0, 1626837579),
(42, 5, 1, '文章列表', '/pages/news_list/index', 'id=文章ID', '/pages/news_list/index', 1, 0, 1626837579),
(43, 5, 1, '分类商品列表', '/pages/goods_list/index', 'sid=1&title=测试分类名称', '/pages/goods_list/index?sid=1&title=测试分类名称', 1, 996, 1626837579),
(44, 5, 1, '个人中心', '/pages/user/index', ' ', '/pages/user/index', 1, 0, 1626837579),
(45, 5, 1, '购物车', '/pages/order_addcart/order_addcart', ' ', '/pages/order_addcart/order_addcart', 1, 997, 1626837579),
(46, 5, 1, '商城分类', '/pages/goods_cate/goods_cate', ' ', '/pages/goods_cate/goods_cate', 1, 998, 1626837579),
(47, 5, 1, '商城首页', '/pages/index/index', ' ', '/pages/index/index', 1, 999, 1626837579),
(48, 5, 3, '佣金记录', '/pages/users/user_spread_money/index?type=2', ' ', '/pages/users/user_spread_money/index?type=2', 1, 0, 1626837579),
(49, 5, 3, '提现记录', '/pages/users/user_spread_money/index?type=1', ' ', '/pages/users/user_spread_money/index?type=1', 1, 0, 1626837579),
(50, 6, 6, '预售列表', '/pages/activity/presell/index', ' ', '/pages/activity/presell/index', 1, 0, 1626837579),
(51, 5, 1, '直播列表', '/pages/live_list/index', ' ', '/pages/live_list/index', 1, 0, 1626837579)
SQL
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_bargain",
                'field' => "logistics",
                'findSql' => "show columns from `@table` like 'logistics'",
                'sql' => "ALTER TABLE `@table` ADD `logistics` varchar(11) NOT NULL DEFAULT '1,2' COMMENT '物流方式'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_bargain",
                'field' => "freight",
                'findSql' => "show columns from `@table` like 'freight'",
                'sql' => "ALTER TABLE `@table` ADD `freight` tinyint(1) NOT NULL DEFAULT '2' COMMENT '运费设置'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_bargain",
                'field' => "custom_form",
                'findSql' => "show columns from `@table` like 'custom_form'",
                'sql' => "ALTER TABLE `@table` ADD `custom_form` varchar(2000) NOT NULL DEFAULT '' COMMENT '自定义表单'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_bargain",
                'field' => "virtual_type",
                'findSql' => "show columns from `@table` like 'virtual_type'",
                'sql' => "ALTER TABLE `@table` ADD `virtual_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_combination",
                'field' => "logistics",
                'findSql' => "show columns from `@table` like 'logistics'",
                'sql' => "ALTER TABLE `@table` ADD `logistics` varchar(11) NOT NULL DEFAULT '1,2' COMMENT '物流方式'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_combination",
                'field' => "freight",
                'findSql' => "show columns from `@table` like 'freight'",
                'sql' => "ALTER TABLE `@table` ADD `freight` tinyint(1) NOT NULL DEFAULT '2' COMMENT '运费设置'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_combination",
                'field' => "custom_form",
                'findSql' => "show columns from `@table` like 'custom_form'",
                'sql' => "ALTER TABLE `@table` ADD `custom_form` varchar(2000) NOT NULL DEFAULT '' COMMENT '自定义表单'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_combination",
                'field' => "virtual_type",
                'findSql' => "show columns from `@table` like 'virtual_type'",
                'sql' => "ALTER TABLE `@table` ADD `virtual_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "refund_express_name",
                'findSql' => "show columns from `@table` like 'refund_express_name'",
                'sql' => "ALTER TABLE `@table` ADD `refund_express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '退货快递名称' AFTER `refund_express`"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "pay_uid",
                'findSql' => "show columns from `@table` like 'pay_uid'",
                'sql' => "ALTER TABLE `@table` ADD `pay_uid` int(11) NOT NULL DEFAULT '0' COMMENT '支付用户uid'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "custom_form",
                'findSql' => "show columns from `@table` like 'custom_form'",
                'sql' => "ALTER TABLE `@table` ADD `custom_form` text NOT NULL COMMENT '自定义表单'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "staff_id",
                'findSql' => "show columns from `@table` like 'staff_id'",
                'sql' => "ALTER TABLE `@table` ADD `staff_id` int(11) NOT NULL DEFAULT '0' COMMENT '员工id'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "agent_id",
                'findSql' => "show columns from `@table` like 'agent_id'",
                'sql' => "ALTER TABLE `@table` ADD `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理id'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "division_id",
                'findSql' => "show columns from `@table` like 'division_id'",
                'sql' => "ALTER TABLE `@table` ADD `division_id` int(11) NOT NULL DEFAULT '0' COMMENT '事业部id'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "staff_brokerage",
                'findSql' => "show columns from `@table` like 'staff_brokerage'",
                'sql' => "ALTER TABLE `@table` ADD `staff_brokerage` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '员工返佣'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "agent_brokerage",
                'findSql' => "show columns from `@table` like 'agent_brokerage'",
                'sql' => "ALTER TABLE `@table` ADD `agent_brokerage` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '代理返佣'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order",
                'field' => "division_brokerage",
                'findSql' => "show columns from `@table` like 'division_brokerage'",
                'sql' => "ALTER TABLE `@table` ADD `division_brokerage` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '事业部返佣'"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "store_order_cart_info",
                'sql' => "ALTER TABLE `@table` ADD `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_order_cart_info",
                'field' => "refund_num",
                'findSql' => "show columns from `@table` like 'refund_num'",
                'sql' => "ALTER TABLE `@table` ADD `refund_num` int(11) NOT NULL DEFAULT '0' COMMENT '退款件数' AFTER `cart_num`"
            ],
            [
                'code' => 440,
                'type' => 1,
                'table' => "store_order_refund",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `store_order_id` int(10) NOT NULL DEFAULT '0' COMMENT '订单表ID',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '门店ID',
  `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户UID',
  `refund_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退款申请类型',
  `refund_num` int(5) NOT NULL DEFAULT '0' COMMENT '退款件数',
  `refund_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `refunded_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '已退款金额',
  `refund_phone` varchar(32) NOT NULL DEFAULT '' COMMENT '退款电话',
  `refund_express` varchar(100) NOT NULL DEFAULT '' COMMENT '退货快递单号',
  `refund_express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '退货快递名称',
  `refund_explain` varchar(255) NOT NULL DEFAULT '' COMMENT '退款用户说明',
  `refund_img` text NOT NULL COMMENT '退款图片',
  `refund_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '不退款的理由',
  `refuse_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '拒绝原因',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `refunded_time` int(10) NOT NULL DEFAULT '0' COMMENT '处理时间',
  `cart_info` longtext NOT NULL COMMENT '退款商品信息',
  `is_cancel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否取消',
  `is_pink_cancel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否拼团自动取消',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '取消申请',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '申请退款时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product",
                'field' => "vip_product",
                'findSql' => "show columns from `@table` like 'vip_product'",
                'sql' => "ALTER TABLE `@table` ADD `vip_product` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否会员专属商品'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product",
                'field' => "presale",
                'findSql' => "show columns from `@table` like 'presale'",
                'sql' => "ALTER TABLE `@table` ADD `presale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否预售商品'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product",
                'field' => "presale_start_time",
                'findSql' => "show columns from `@table` like 'presale_start_time'",
                'sql' => "ALTER TABLE `@table` ADD `presale_start_time` int(11) NOT NULL DEFAULT '0' COMMENT '预售开始时间'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product",
                'field' => "presale_end_time",
                'findSql' => "show columns from `@table` like 'presale_end_time'",
                'sql' => "ALTER TABLE `@table` ADD `presale_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '预售结束时间'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product",
                'field' => "presale_day",
                'findSql' => "show columns from `@table` like 'presale_day'",
                'sql' => "ALTER TABLE `@table` ADD `presale_day` int(11) NOT NULL DEFAULT '0' COMMENT '预售结束后几天内发货'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product",
                'field' => "logistics",
                'findSql' => "show columns from `@table` like 'logistics'",
                'sql' => "ALTER TABLE `@table` ADD `logistics` varchar(10) NOT NULL DEFAULT '1,2' COMMENT '物流方式'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product",
                'field' => "freight",
                'findSql' => "show columns from `@table` like 'freight'",
                'sql' => "ALTER TABLE `@table` ADD `freight` tinyint(1) NOT NULL DEFAULT '2' COMMENT '运费设置'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product",
                'field' => "custom_form",
                'findSql' => "show columns from `@table` like 'custom_form'",
                'sql' => "ALTER TABLE `@table` ADD `custom_form` varchar(2000) NOT NULL DEFAULT '' COMMENT '自定义表单'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product_attr_result",
                'field' => "id",
                'findSql' => "show columns from `@table` like 'id'",
                'sql' => "ALTER TABLE `@table` ADD `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT '主键id' FIRST, ADD PRIMARY KEY (`id`)"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product_attr_value",
                'field' => "id",
                'findSql' => "show columns from `@table` like 'id'",
                'sql' => "ALTER TABLE `@table` ADD `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT '主键id' FIRST, ADD PRIMARY KEY (`id`)"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_product_attr_value",
                'field' => "disk_info",
                'findSql' => "show columns from `@table` like 'disk_info'",
                'sql' => "ALTER TABLE `@table` ADD `disk_info` text NOT NULL COMMENT '虚拟信息内容'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_seckill",
                'field' => "logistics",
                'findSql' => "show columns from `@table` like 'logistics'",
                'sql' => "ALTER TABLE `@table` ADD `logistics` varchar(11) NOT NULL DEFAULT '1,2' COMMENT '物流方式'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_seckill",
                'field' => "freight",
                'findSql' => "show columns from `@table` like 'freight'",
                'sql' => "ALTER TABLE `@table` ADD `freight` tinyint(1) NOT NULL DEFAULT '2' COMMENT '运费设置'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_seckill",
                'field' => "custom_form",
                'findSql' => "show columns from `@table` like 'custom_form'",
                'sql' => "ALTER TABLE `@table` ADD `custom_form` varchar(2000) NOT NULL DEFAULT '' COMMENT '自定义表单'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "store_seckill",
                'field' => "virtual_type",
                'findSql' => "show columns from `@table` like 'virtual_type'",
                'sql' => "ALTER TABLE `@table` ADD `virtual_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "system_admin",
                'field' => "division_id",
                'findSql' => "show columns from `@table` like 'division_id'",
                'sql' => "ALTER TABLE `@table` ADD `division_id` int(11) NOT NULL DEFAULT '0' COMMENT '事业部id'"
            ],
            [
                'code' => 440,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'sys_app'",
                'whereSql' => "",
                'sql' => "INSERT INTO `@table` (`id`, `pid`, `title`, `eng_title`, `status`, `info`, `icon`, `type`, `sort`) VALUES (NULL, 0, '应用配置', 'sys_app', 0, 0, '', 0, 0)"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'wechat'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='sys_app'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId',`type` = 3 WHERE `eng_title` = 'wechat';"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'routine'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='sys_app'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId',`type` = 3 WHERE `eng_title` = 'routine';"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'point'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='store'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId' WHERE `eng_title` = 'point';"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'upload_set'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='sys_app'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId',`title` = '缩略图配置',`status` = 0 WHERE `eng_title` = 'upload_set';"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'system_sms'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='sys_app'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId',`title` = '一号通',`status` = 0 WHERE `eng_title` = 'system_sms';"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'base_config'",
                'whereSql' => "",
                'sql' => "UPDATE `@table` SET `title` = '缩略图配置',`status` = 0 WHERE `eng_title` = 'base_config';"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'aliyun_uploads'",
                'whereSql' => "",
                'sql' => "delete from @table where `eng_title` = 'aliyun_uploads'"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'qiniu_uplaods'",
                'whereSql' => "",
                'sql' => "delete from @table where `eng_title` = 'qiniu_uplaods'"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'tengxun_uploads'",
                'whereSql' => "",
                'sql' => "delete from @table where `eng_title` = 'tengxun_uploads'"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'system_pc'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='sys_app'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId',`type` = 3 WHERE `eng_title` = 'system_pc';"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'app'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='sys_app'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId',`type` = 3 WHERE `eng_title` = 'app';"
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'h5_avatar'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='web_site'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = '@tabId',`type` = 3 WHERE `menu_name` = 'h5_avatar';"
            ],
            [
                'code' => 440,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "",
                'findSql' => "select id from @table where `menu_name` = 'tengxun_appid'",
                'whereSql' => "",
                'sql' => "INSERT INTO `@table` (`id`, `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `sort`, `status`) VALUES (NULL, 'tengxun_appid', 'text', 'input', 34, '', 0, '', 0, 0, '', '腾讯云APPID', '腾讯云APPID', 0, 1)"
            ],
            [
                'code' => 440,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'extract_type'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='extract_set'",
                'sql' => "INSERT INTO `@table` (`id`, `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `sort`, `status`) VALUES (NULL, 'extract_type', 'checkbox', 'input', @tabId, '0=>银行卡\n1=>微信\n2=>支付宝', 1, '', 0, 0, '[\"0\",\"1\",\"2\"]', '提现方式', '提现方式', 0, 1)"
            ],
            [
                'code' => 440,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'integral_frozen'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='point'",
                'sql' => "INSERT INTO `@table` (`id`, `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `sort`, `status`) VALUES (NULL, 'integral_frozen', 'text', 'input', @tabId, '', 1, '', 100, 0, '\"0\"', '积分冻结(天)', '积分冻结(天)', 0, 1)"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "system_menus",
                'sql' => "truncate table `@table`"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "system_menus",
                'sql' => <<<SQL
INSERT INTO `@table` (`id`, `pid`, `icon`, `menu_name`, `module`, `controller`, `action`, `api_url`, `methods`, `params`, `sort`, `is_show`, `is_show_path`, `access`, `menu_path`, `path`, `auth_type`, `header`, `is_header`, `unique_auth`, `is_del`) VALUES
(1, 0, 'md-basket', '商品', 'admin', 'product', 'index', '', '', '[]', 126, 1, 0, 1, '/admin/product', '', 1, '0', 1, 'admin-product', 0),
(2, 1, '', '商品管理', 'admin', 'product.product', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_list', '', 1, '', 0, 'admin-store-storeProuduct-index', 0),
(3, 1, '', '商品分类', 'admin', 'product.storeCategory', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_classify', '', 1, 'product', 0, 'admin-store-storeCategory-index', 0),
(4, 0, 'md-cart', '订单', 'admin', 'order', 'index', '', '', '[]', 110, 1, 0, 1, '/admin/order', '', 1, 'home', 1, 'admin-order', 0),
(5, 4, '', '订单管理', 'admin', 'order.store_order', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/order/list', '', 1, 'order', 0, 'admin-order-storeOrder-index', 0),
(6, 1, '', '商品评论', 'admin', 'store.store_product_reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/product/product_reply', '', 1, 'product', 0, 'product-product-reply', 0),
(7, 0, 'md-home', '主页', 'admin', 'index', '', '', '', '[]', 127, 1, 0, 1, '/admin/home/', '', 1, 'home', 1, 'admin-index-index', 0),
(9, 0, 'md-person', '用户', 'admin', 'user.user', '', '', '', '[]', 100, 1, 0, 1, '/admin/user', '', 1, 'user', 1, 'admin-user', 0),
(10, 9, '', '用户管理', 'admin', 'user.user', 'index', '', '', '[]', 10, 1, 0, 1, '/admin/user/list', '', 1, 'user', 1, 'admin-user-user-index', 0),
(11, 9, '', '用户等级', 'admin', 'user.user_level', 'index', '', '', '[]', 7, 1, 0, 1, '/admin/user/level', '', 1, 'user', 1, 'user-user-level', 0),
(12, 0, 'md-settings', '设置', 'admin', 'setting.system_config', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting', '', 1, 'setting', 1, 'admin-setting', 0),
(14, 12, '', '管理权限', 'admin', 'setting.system_admin', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/auth/list', '', 1, 'setting', 1, 'setting-system-admin', 0),
(19, 14, '', '角色管理', 'admin', 'setting.system_role', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_role/index', '', 1, 'setting', 1, 'setting-system-role', 0),
(20, 14, '', '管理员列表', 'admin', 'setting.system_admin', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_admin/index', '', 1, 'setting', 0, 'setting-system-list', 0),
(21, 14, '', '权限规则', 'admin', 'setting.system_menus', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_menus/index', '', 1, 'setting', 0, 'setting-system-menus', 0),
(22, 1, '', '产品添加', 'admin', 'store.store_product', 'save', '', '', '[]', 1, 1, 1, 1, '/admin/product/add_product', '', 1, 'product', 1, 'product-product-save', 0),
(23, 12, '', '系统设置', 'admin', 'setting.system_config', 'index', '', '', '[]', 10, 1, 0, 1, '/admin/setting/system_config', '', 1, 'setting', 1, 'setting-system-config', 0),
(25, 0, 'md-hammer', '维护', 'admin', 'system', '', '', '', '[]', -1, 1, 0, 1, '/admin/system', '', 1, 'setting', 1, 'admin-system', 0),
(26, 0, 'ios-people', '分销', 'admin', 'agent', '', '', '', '[]', 90, 1, 0, 1, '/admin/agent', '', 1, 'user', 1, 'admin-agent', 0),
(27, 0, 'ios-paper-plane', '营销', 'admin', 'marketing', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing', '', 1, 'home', 1, 'admin-marketing', 0),
(28, 26, '', '分销设置', 'admin', 'setting.system_config', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_config_retail/2/9', '', 1, 'setting', 0, 'setting-system-config', 0),
(29, 26, '', '分销员管理', 'admin', 'agent.agent_manage', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/agent/agent_manage/index', '', 1, 'user', 0, 'agent-agent-manage', 0),
(30, 27, '', '优惠券', 'admin', 'marketing.store_coupon', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_coupon', '', 1, 'marketing', 1, 'marketing-store_coupon-index', 0),
(31, 27, '', '砍价管理', 'admin', 'marketing.store_bargain', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_bargain', '', 1, 'marketing', 0, 'marketing-store_bargain-index', 0),
(32, 27, '', '拼团管理', 'admin', 'marketing.store_combination', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_combination', '', 1, 'marketing', 0, 'marketing-store_combination-index', 0),
(33, 27, '', '秒杀管理', 'admin', 'marketing.store_seckill', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_seckill', '', 1, 'marketing', 0, 'marketing-store_seckill-index', 0),
(34, 27, '', '积分管理', 'admin', 'marketing.user_point', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/user_point', '', 1, 'marketing', 1, 'marketing-user_point-index', 0),
(35, 0, 'logo-usd', '财务', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance', '', 1, 'home', 0, 'admin-finance', 0),
(36, 35, '', '财务操作', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_extract', '', 1, 'finance', 0, 'finance-user_extract-index', 0),
(37, 35, '', '财务记录', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_recharge', '', 1, 'finance', 0, 'finance-user-recharge-index', 0),
(38, 35, '', '佣金记录', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/finance', '', 1, 'finance', 0, 'finance-finance-index', 0),
(39, 36, '', '提现申请', 'admin', 'finance.user_extract', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_extract/index', '', 1, 'finance', 0, 'finance-user_extract', 0),
(40, 37, '', '充值记录', 'admin', 'finance.user_recharge', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_recharge/index', '', 1, 'finance', 0, 'finance-user-recharge', 0),
(42, 38, '', '佣金记录', 'admin', 'finance.finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/finance/commission', '', 1, 'finance', 0, 'finance-finance-commission', 0),
(43, 0, 'ios-book', '内容', 'admin', 'cms', '', '', '', '[]', 1, 1, 0, 1, '/admin/cms', '', 1, 'home', 0, 'admin-cms', 0),
(44, 43, '', '文章管理', 'admin', 'cms.article', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/cms/article/index', '', 1, 'cms', 0, 'cms-article-index', 0),
(45, 43, '', '文章分类', 'admin', 'cms.article_category', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/cms/article_category/index', '', 1, 'cms', 0, 'cms-article-category', 0),
(46, 43, '', '文章添加', 'admin', 'cms.article', 'add_article', '', '', '[]', 0, 1, 1, 1, '/admin/cms/article/add_article', '', 1, 'cms', 1, 'cms-article-creat', 0),
(47, 65, '', '系统日志', 'admin', 'system.system_log', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_log/index', '', 1, 'system', 0, 'system-maintain-system-log', 0),
(48, 7, '', '控制台', 'admin', 'index', 'index', '', '', '[]', 127, 1, 0, 1, '/admin/home/index', '', 1, 'home', 0, '', 1),
(56, 25, '', '开发配置', 'admin', 'system', '', '', '', '[]', 10, 1, 0, 1, '/admin/system/config', '', 1, 'system', 1, 'system-config-index', 0),
(57, 65, '', '刷新缓存', 'admin', 'system', 'clear', '', '', '[]', 1, 1, 0, 1, '/admin/system/maintain/clear/index', '', 1, 'system', 1, 'system-clear', 0),
(64, 65, '', '文件校验', 'admin', 'system.system_file', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_file/index', '', 1, 'system', 0, 'system-maintain-system-file', 0),
(65, 25, '', '安全维护', 'admin', 'system', '', '', '', '[]', 7, 1, 0, 1, '/admin/system/maintain', '', 1, 'system', 1, 'system-maintain-index', 0),
(66, 65, '', '清除数据', 'admin', 'system.system_cleardata', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_cleardata/index', '', 1, 'system', 0, 'system-maintain-system-cleardata', 0),
(67, 65, '', '数据备份', 'admin', 'system.system_databackup', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_databackup/index', '', 1, 'system', 0, 'system-maintain-system-databackup', 0),
(69, 135, '', '公众号', 'admin', 'wechat', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat', '', 1, 'app', 1, 'admin-wechat', 0),
(70, 30, '', '优惠券模板', 'admin', 'marketing.store_coupon', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/index', '', 1, 'marketing', 1, 'marketing-store_coupon', 0),
(71, 30, '', '优惠券列表', 'admin', 'marketing.store_coupon_issue', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_coupon_issue/index', '', 1, 'marketing', 1, 'marketing-store_coupon_issue', 0),
(72, 30, '', '用户领取记录', 'admin', 'marketing.store_coupon_user', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_coupon_user/index', '', 1, 'marketing', 1, 'marketing-store_coupon_user', 0),
(74, 31, '', '砍价商品', 'admin', 'marketing.store_bargain', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_bargain/index', '', 1, 'marketing', 1, 'marketing-store_bargain', 0),
(75, 32, '', '拼团商品', 'admin', 'marketing.store_combination', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/index', '', 1, 'marketing', 1, 'marketing-store_combination', 0),
(76, 32, '', '拼团列表', 'admin', 'marketing.store_combination', 'combina_list', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/combina_list', '', 1, 'marketing', 0, 'marketing-store_combination-combina_list', 0),
(77, 33, '', '秒杀商品', 'admin', 'marketing.store_seckill', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_seckill/index', '', 1, 'marketing', 1, 'marketing-store_seckill', 0),
(78, 33, '', '秒杀配置', 'admin', 'marketing.store_seckill', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_seckill_data/index/49', '', 1, 'marketing', 1, 'marketing-store_seckill-data', 0),
(79, 34, '', '积分配置', 'admin', 'setting.system_config/index.html', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/integral/system_config/3/11', '', 1, 'marketing', 1, 'marketing-integral-system_config', 0),
(90, 32, '', '拼团添加', 'admin', 'marketing.store_combination', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/add_commodity/:id', '', 1, 'marketing', 0, '', 1),
(91, 69, '', '公众号配置', 'admin', 'application.wechat', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/setting', '', 1, 'app', 0, '', 1),
(92, 69, '', '微信菜单', 'admin', 'application.wechat_menus', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/setting/menus/index', '', 1, 'app', 0, 'application-wechat-menus', 0),
(94, 12, '', '一号通', 'admin', 'setting.sms_config', '', '', '', '[]', 8, 1, 0, 1, '/admin/setting/sms/sms_config/index', '', 1, 'setting', 1, 'setting-sms', 0),
(95, 94, '', '账户管理', 'admin', 'sms.sms_config', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_config/index', '', 1, 'setting', 1, 'setting-sms-sms-config', 0),
(96, 94, '', '短信模板', 'admin', 'sms.sms_template_apply', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_template_apply/index', '', 1, 'setting', 0, 'setting-sms-config-template', 0),
(97, 94, '', '套餐购买', 'admin', 'sms.sms_pay', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_pay/index', '', 1, 'setting', 1, 'setting-sms-sms-template', 0),
(99, 1, '', '商品规格', 'admin', 'store.store_product', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_attr', '', 1, 'product', 1, 'product-product-attr', 0),
(105, 22, '', '添加产品保存', 'admin', 'store.store_product', 'save', 'product/product/<id>', 'POST', '[]', 0, 0, 0, 1, '/admin/product/save', '', 2, 'product', 0, 'product-save', 0),
(108, 2, '', '产品列表', 'admin', 'product.product', 'index', 'product/product', 'GET', '[]', 20, 0, 0, 1, '/admin/product/product', '1/2', 2, 'product', 1, 'product-product-index', 0),
(109, 69, '', '图文管理', 'admin', 'wechat.wechat_news_category', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/news_category/index', '', 1, 'app', 0, 'wechat-wechat-news-category-index', 0),
(110, 69, '', '图文添加', 'admin', 'wechat.wechat_news_category', 'save', '', '', '[]', 0, 1, 1, 1, '/admin/app/wechat/news_category/save', '', 1, 'app', 1, 'wechat-wechat-news-category-save', 0),
(111, 56, '', '配置分类', 'admin', 'setting.system_config_tab', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/config/system_config_tab/index', '', 1, 'system', 0, 'system-config-system_config-tab', 0),
(112, 56, '', '组合数据', 'admin', 'setting.system_group', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/config/system_group/index', '', 1, 'system', 0, 'system-config-system_config-group', 0),
(113, 114, '', '微信关注回复', 'admin', 'wechat.reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/follow/subscribe', '', 1, 'app', 0, 'wechat-wechat-reply-subscribe', 0),
(114, 69, '', '自动回复', 'admin', 'wechat.reply', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply', '', 1, 'app', 0, 'wechat-wechat-reply-index', 0),
(115, 114, '', '关键字回复', 'admin', 'wechat.reply', 'keyword', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/keyword', '', 1, 'app', 0, 'wechat-wechat-reply-keyword', 0),
(116, 114, '', '无效关键词回复', 'admin', 'wechat.reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/index/default', '', 1, 'app', 0, 'wechat-wechat-reply-default', 0),
(125, 56, '', '配置列表', 'admin', 'system.config', 'index', '', '', '[]', 0, 0, 1, 1, '/admin/system/config/system_config_tab/list', '', 1, 'system', 1, 'system-config-system_config_tab-list', 0),
(126, 56, '', '组合数据列表', 'admin', 'system.system_group', 'list', '', '', '[]', 0, 1, 1, 1, '/admin/system/config/system_group/list', '', 1, 'system', 1, 'system-config-system_config-list', 0),
(128, 656, '', '数据配置', 'admin', 'setting.system_group_data', 'index', '', '', '[]', 2, 1, 0, 1, '/admin/setting/system_visualization_data', '12/656', 1, 'system', 1, 'admin-setting-system_visualization_data', 0),
(134, 114, '', '关键字添加', 'admin', '', 'index', '', '', '[]', 0, 1, 1, 1, '/admin/app/wechat/reply/keyword/save', '', 1, 'app', 1, 'wechat-wechat-reply-save', 0),
(135, 0, 'md-cube', '应用', 'admin', 'app', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app', '', 1, 'app', 1, 'admin-app', 0),
(144, 303, '', '提货点设置', 'admin', 'merchant.system_store', 'index', '', '', '[]', 5, 1, 0, 1, '/admin/setting/merchant/system_store/index', '', 1, '', 0, 'setting-system-config-merchant', 0),
(145, 25, '', '物流公司', 'admin', 'freight.express', 'index', '', '', '[]', 4, 1, 0, 1, '/admin/setting/freight/express/index', '', 1, '', 0, 'setting-freight-express', 0),
(146, 31, '', '添加砍价', 'admin', '/marketing.store_bargain', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_bargain/create', '', 1, '', 0, 'marketing-store_bargain-create', 0),
(147, 32, '', '添加拼团', 'admin', 'marketing.store_combination', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_combination/create', '', 1, '', 0, 'marketing-store_combination-create', 0),
(148, 33, '', '添加秒杀', 'admin', 'marketing.store_seckill', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_seckill/create', '', 1, '', 0, 'marketing-store_seckill-create', 0),
(154, 34, '', '签到配置', 'admin', 'setting.system_group_data', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/sign', '', 1, '', 0, 'marketing-sign-index', 0),
(165, 0, 'md-chatboxes', '客服', 'admin', 'setting.storeService', 'index', '', '', '[]', 2, 1, 0, 1, '/admin/kefu', '', 1, '', 0, 'setting-store-service', 0),
(166, 25, '', '日志', 'admin', '', '', '', '', '[]', 0, 1, 1, 1, '/admin/system/log', '', 1, '', 0, 'system-log', 0),
(169, 577, '', '商品删除', 'admin', 'product', '商品删除', 'product/product/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '0', 1, '', 0),
(170, 3, '', '分类列表', 'admin', '', '', 'product/category', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/category', '', 2, '', 0, '', 0),
(171, 578, '', '删除分类', 'admin', '', '', 'product/category/<id>', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/category/<id>', '', 2, '', 0, '', 0),
(172, 578, '', '修改分类', 'admin', '', '', 'product/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/<id>', '', 2, '', 0, '', 0),
(173, 578, '', '新增分类', 'admin', '', '', 'product/category', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/category', '', 2, '', 0, 'product-save-cate', 0),
(174, 578, '', '分类状态', 'admin', '', '', 'product/category/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/set_show/<id>/<is_show>', '', 2, '', 0, '', 0),
(175, 578, '', '快速编辑', 'admin', '', '', 'product/category/set_category/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/set_category/<id>', '', 2, '', 0, '', 0),
(176, 578, '', '分类表单添加', 'admin', '', '', 'product/category/create', 'GET', '[]', 0, 0, 0, 1, '/admincategory/create', '', 2, '', 0, '', 0),
(177, 578, '', '分类表单编辑', 'admin', '', '', 'product/category/<id>', 'GET', '[]', 0, 0, 0, 1, '/admincategory/<id>/edit', '', 2, '', 0, '', 0),
(178, 3, '', '分类树形列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '/admincategory/tree/:type', '', 2, '', 0, '', 0),
(179, 577, '', '产品状态', 'admin', '', '', 'product/product/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/set_show/<id>/<is_show>', '', 2, '', 0, '', 0),
(180, 577, '', '快速编辑', 'admin', '', '', 'product/product/set_product/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/product/set_product/<id>', '', 2, '', 0, '', 0),
(181, 577, '', '批量上架商品', 'admin', '', '', 'product/product/product_show', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/product/product_show', '', 2, '', 0, 'product-product-product_show', 0),
(182, 577, '', '采集商品', 'admin', '', '', 'product/copy', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/crawl', '', 2, '', 0, 'product-crawl-save', 0),
(183, 577, '', '采集商品保存', 'admin', '', '', 'product/crawl/save', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/crawl/save', '', 2, '', 0, '', 0),
(184, 579, '', '虚拟评论表单', 'admin', '', '', 'product/reply/fictitious_reply/<product_id>', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/reply/fictitious_reply', '', 2, '', 0, '', 0),
(185, 579, '', '保存虚拟评论', 'admin', '', '', 'product/reply/save_fictitious_reply', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/reply/save_fictitious_reply', '', 2, '', 0, 'product-reply-save_fictitious_reply', 0),
(186, 22, '', '获取属性模板列表', 'admin', '', '', 'product/product/get_rule', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/get_rule', '', 2, '', 0, '', 0),
(187, 22, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/get_template', '', 2, '', 0, '', 0),
(188, 579, '', '删除评论', 'admin', '', '', 'product/reply/<id>', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/reply/<id>', '', 2, '', 0, '', 0),
(189, 579, '', '评论回复', 'admin', '', '', 'product/reply/set_reply/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminreply/set_reply/<id>', '', 2, '', 0, '', 0),
(190, 6, '', '评论列表', 'admin', '', '', 'product/reply', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/reply', '', 2, '', 0, '', 0),
(191, 22, '', '生成属性', 'admin', '', '', 'product/generate_attr/<id>/<type>', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/generate_attr/<id>', '', 2, '', 0, '', 0),
(192, 2, '', '商品列表头部', 'admin', '', '', 'product/product/type_header', 'GET', '[]', 10, 0, 0, 1, '/adminproduct/product/type_header', '', 2, '', 0, '', 0),
(193, 577, '', '商品列表插件', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/list', '', 2, '', 0, '', 0),
(194, 99, '', '属性规则列表', 'admin', '', '', 'product/product/rule', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/rule', '', 2, '', 0, '', 0),
(195, 580, '', '保存修改规则', 'admin', '', '', 'product/product/rule/<id>', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/rule/<id>', '', 2, '', 0, 'product-rule-save', 0),
(196, 580, '', '规则详情', 'admin', '', '', 'product/product/rule/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/rule/<id>', '', 2, '', 0, '', 0),
(197, 580, '', '删除规则', 'admin', '', '', 'product/product/rule/delete', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/product/rule/delete', '', 2, '', 0, 'product-product-rule-delete', 0),
(198, 5, '', '订单列表', 'admin', '', '', 'order/list', 'GET', '[]', 0, 0, 0, 1, '/adminorder/list', '', 2, '', 0, '', 0),
(199, 5, '', '订单数据', 'admin', '', '', 'order/chart', 'GET', '[]', 0, 0, 0, 1, '/adminorder/chart', '', 2, '', 0, '', 0),
(200, 581, '', '订单核销', 'admin', '', '', 'order/write', 'POST', '[]', 0, 0, 0, 1, '/adminorder/write', '', 2, '', 0, 'order-write', 0),
(201, 215, '', '订单修改表格', 'admin', '', '', 'order/edit/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/edit/<id>', '', 2, '', 0, '', 0),
(202, 215, '', '订单修改', 'admin', '', '', 'order/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/update/<id>', '', 2, '', 0, '', 0),
(203, 581, '', '订单收货', 'admin', '', '', 'order/take/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/take/<id>', '', 2, '', 0, '', 0),
(204, 209, '', '订单发货', 'admin', '', '', 'order/delivery/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/delivery/<id>', '', 2, '', 0, '', 0),
(205, 214, '', '订单退款表格', 'admin', '', '', 'order/refund/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/refund/<id>', '', 2, '', 0, '', 0),
(206, 214, '', '订单退款', 'admin', '', '', 'order/refund/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/refund/<id>', '', 2, '', 0, '', 0),
(207, 581, '', '订单物流信息', 'admin', '', '', 'order/express/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/express/<id>', '', 2, '', 0, '', 0),
(208, 209, '', '物流公司列表', 'admin', '', '', 'order/express_list', 'GET', '[]', 0, 0, 0, 1, '/adminorder/express_list', '', 2, '', 0, '', 0),
(209, 581, '', '发货', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/delivery', '', 1, '', 0, '', 0),
(210, 767, '', '附加权限', 'admin', '', '', '', 'GET', '[]', 99, 1, 0, 1, '/adminorder/info/<id>', '', 2, '', 0, '', 0),
(211, 213, '', '订单配送表格', 'admin', '', '', 'order/distribution/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/distribution/<id>', '', 2, '', 0, '', 0),
(212, 213, '', '修改配送信息', 'admin', '', '', 'order/distribution/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/distribution/<id>', '', 2, '', 0, '', 0),
(213, 581, '', '订单配送', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/distribution', '', 1, '', 0, '', 0),
(214, 581, '', '退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/refund', '', 1, '', 0, '', 0),
(215, 581, '', '修改', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/update', '', 1, '', 0, '', 0),
(216, 581, '', '不退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/no_refund', '', 1, '', 0, '', 0),
(217, 216, '', '不退款表格', 'admin', '', '', 'order/no_refund/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/no_refund/<id>', '', 2, '', 0, '', 0),
(218, 216, '', '不退款理由修改', 'admin', '', '', 'order/no_refund/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/no_refund/<id>', '', 2, '', 0, '', 0),
(219, 581, '', '线下支付', 'admin', '', '', 'order/pay_offline/<id>', 'POST', '[]', 98, 0, 0, 1, '', '', 2, '', 0, '', 0),
(220, 581, '', '退积分', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/refund_integral', '', 1, '', 0, '', 0),
(221, 220, '', '退积分表单', 'admin', '', '', 'order/refund_integral/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(222, 220, '', '修改退积分', 'admin', '', '', 'order/refund_integral/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(223, 581, '', '订单备注', 'admin', '', '', 'order/remark/<id>', 'PUT', '[]', 97, 0, 0, 1, '', '', 2, '', 0, '', 0),
(224, 209, '', '获取电子面单信息', 'admin', '', '', 'order/express/temp', 'GET', '[]', 96, 0, 1, 1, '', '4/5/581/209', 2, '', 0, '', 0),
(225, 581, '', '订单删除', 'admin', '', '', 'order/del/<id>', 'DELETE', '[]', 95, 0, 0, 1, '', '', 2, '', 0, '', 0),
(226, 581, '', '批量删除订单', 'admin', '', '', 'order/dels', 'POST', '[]', 100, 0, 0, 1, '', '4/5/581', 2, '', 0, 'order-dels', 0),
(227, 9, '', '用户分组', 'admin', 'user.user_group', 'index', '', '', '[]', 9, 1, 0, 1, '/admin/user/group', '', 1, 'user', 1, 'user-user-group', 0),
(229, 25, '', '城市数据', 'admin', 'setting.system_city', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/freight/city/list', '', 1, 'setting', 1, 'setting-system-city', 0),
(230, 303, '', '运费模板', 'admin', 'setting.shipping_templates', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/freight/shipping_templates/list', '', 1, 'setting', 1, 'setting-shipping-templates', 0),
(231, 767, '', '发票列表', 'admin', '', '', 'order/invoice/list', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-index', 0),
(232, 585, '', '用户详情', 'admin', '', '', 'user/one_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(233, 585, '', '创建用户表单', 'admin', '', '', 'user/user/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(234, 585, '', '修改用户信息表单', 'admin', '', '', 'user/user/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(235, 585, '', '获取用户信息', 'admin', '', '', 'user/user/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(236, 585, '', '修改用户信息', 'admin', '', '', 'user/user/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(238, 585, '', '发送优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/coupon', '', 1, '', 0, 'admin-user-coupon', 0),
(239, 238, '', '优惠卷列表', 'admin', '', '', 'marketing/coupon/grant', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(240, 238, '', '发送优惠卷', 'admin', '', '', 'marketing/coupon/user/grant', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(241, 585, '', '发送图文', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/wechat/news/', '', 1, '', 0, 'admin-wechat-news', 0),
(242, 241, '', '图文列表', 'admin', '', '', 'app/wechat/news', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(243, 241, '', '发送图文', 'admin', '', '', 'app/wechat/push', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(244, 585, '', '批量用户分组', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/group_set/', '', 1, '', 0, 'admin-user-group_set', 0),
(245, 244, '', '用户分组表单', 'admin', '', '', 'user/set_group/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(246, 244, '', '保存分组', 'admin', '', '', 'user/set_group', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(247, 586, '', '添加修改用户等级', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/level_add', '', 1, '', 0, 'admin-user-level_add', 0),
(248, 247, '', '添加会员等级表单', 'admin', '', '', 'user/user_level/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(249, 247, '', '保存会员等级', 'admin', '', '', 'user/user_level', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(250, 11, '', '用户等级列表', 'admin', '', '', 'user/user_level/vip_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(251, 586, '', '用户等级是否显示', 'admin', '', '', 'user/user_level/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(252, 586, '', '删除用户等级', 'admin', '', '', 'user/user_level/delete/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(253, 586, '', '等级任务', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/user_level', '', 1, '', 0, '', 0),
(254, 253, '', '等级任务列表', 'admin', '', '', 'user/user_level/task/<level_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(255, 253, '', '等级任务显示隐藏', 'admin', '', '', 'user/user_level/set_task_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(256, 253, '', '等级任务是否必达', 'admin', '', '', 'user/user_level/set_task_must/<id>/<is_must>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(257, 253, '', '添加修改等级任务', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(258, 257, '', '添加等级任务表单', 'admin', '', '', 'user/user_level/create_task', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(259, 257, '', '保存修改任务', 'admin', '', '', 'user/user_level/save_task', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(260, 253, '', '删除等级任务', 'admin', '', '', 'user/user_level/delete_task/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(261, 227, '', '用户分组列表', 'admin', '', '', 'user/user_group/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(262, 227, '', '删除用户分组', 'admin', '', '', 'user/user_group/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(263, 227, '', '添加修改用户分组', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/group', '', 1, '', 0, 'admin-user-group', 0),
(264, 263, '', '添加修改用户分组表单', 'admin', '', '', 'user/user_group/add/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(265, 263, '', '保存修改用户分组', 'admin', '', '', 'user/user_group/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(266, 29, '', '分销员列表', 'admin', '', '', 'agent/index', 'GET', '[]', 0, 0, 0, 1, '', '26/29', 2, '', 0, '', 0),
(267, 584, '', '分销员数据', 'admin', '', '', 'agent/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(268, 29, '', '推广人列表', 'admin', '', '', 'agent/stair', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(269, 29, '', '推广人订单列表', 'admin', '', '', 'agent/stair/order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(270, 584, '', '清除推广人', 'admin', '', '', 'agent/stair/delete_spread/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(271, 584, '', '推广二维码', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(272, 271, '', '公众号推广二维码', 'admin', '', '', 'agent/look_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(273, 271, '', '小程序推广二维码', 'admin', '', '', 'agent/look_xcx_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(274, 583, '', '添加优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/add', '', 1, '', 0, 'admin-marketing-store_coupon-add', 0),
(275, 274, '', '添加优惠卷表单', 'admin', '', '', 'marketing/coupon/create/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(276, 274, '', '保存优惠卷', 'admin', '', '', 'marketing/coupon/save_coupon', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(277, 583, '', '发布优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/push', '', 1, '', 0, 'admin-marketing-store_coupon-push', 0),
(278, 277, '', '发布优惠卷表单', 'admin', '', '', 'marketing/coupon/issue/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(279, 277, '', '发布优惠卷', 'admin', '', '', 'marketing/coupon/issue/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(280, 583, '', '立即失效', 'admin', '', '', 'marketing/coupon/status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(281, 583, '', '删除优惠卷', 'admin', '', '', 'marketing/coupon/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(282, 71, '', '优惠卷已发布列表', 'admin', '', '', 'marketing/coupon/released', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(283, 71, '', '领取记录', 'admin', '', '', 'marketing/coupon/released/issue_log/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(284, 71, '', '删除优惠卷', 'admin', '', '', 'marketing/coupon/released/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(285, 71, '', '修改状态', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(286, 285, '', '修改状态表单', 'admin', '', '', 'marketing/coupon/released/<id>/status', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(287, 285, '', '保存修改状态', 'admin', '', '', 'marketing/coupon/released/status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(290, 405, '', '审核状态通过', 'admin', '', '', 'finance/extract/adopt/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(291, 405, '', '拒绝申请', 'admin', '', '', 'finance/extract/refuse/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(292, 405, '', '提现编辑', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(293, 292, '', '编辑表单', 'admin', '', '', 'finance/extract/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(294, 292, '', '保存修改', 'admin', '', '', 'finance/extract/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(295, 40, '', '充值列表', 'admin', '', '', 'finance/recharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(296, 40, '', '充值数据', 'admin', '', '', 'finance/recharge/user_recharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(297, 40, '', '退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(298, 297, '', '获取退款表单', 'admin', '', '', 'finance/recharge/<id>/refund_edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(299, 297, '', '保存退款', 'admin', '', '', 'finance/recharge/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(300, 144, '', '提货点', 'admin', 'merchant.system_store', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_store/list', '', 1, 'setting', 1, 'setting-merchant-system-store', 0),
(301, 144, '', '核销员', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_store_staff/index', '', 1, 'setting', 1, 'setting-merchant-system-store-staff', 0),
(302, 144, '', '核销订单', 'admin', '', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_verify_order/index', '', 1, 'setting', 1, 'setting-merchant-system-verify-order', 0),
(303, 12, '', '发货设置', 'admin', 'setting', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/freight', '', 1, '', 0, '', 0),
(304, 303, '', '物流配置', 'admin', 'setting.systemConfig', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_config_logistics/3/10', '', 1, '', 0, 'setting-system-config-logistics', 0),
(305, 44, '', '文章列表', 'admin', '', '', 'cms/cms', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(306, 409, '', '文章分类', 'admin', '', '', 'cms/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(307, 42, '', '佣金记录列表', 'admin', '', '', 'finance/finance/commission_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(308, 42, '', '用户详情', 'admin', 'finance.finance', 'user_info', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(309, 308, '', '获取用户信息', 'admin', '', '', 'finance/finance/user_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(310, 308, '', '佣金详细列表', 'admin', '', '', 'finance/finance/extract_list/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(313, 23, '', '获取头部导航', 'admin', '', '', 'setting/config/header_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(314, 23, '', '获取配置列表', 'admin', '', '', 'setting/config/edit_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(315, 23, '', '修改配置', 'admin', '', '', 'setting/config/save_basics', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(316, 423, '', '添加客服', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/add', '', 1, '', 0, 'setting-store_service-add', 0),
(317, 316, '', '客服用户列表', 'admin', '', '', 'app/wechat/kefu/add', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(318, 316, '', '保存客服', 'admin', '', '', 'app/wechat/kefu', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(319, 423, '', '聊天记录', 'admin', '', '', 'app/wechat/kefu/record/', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(320, 423, '', '编辑客服', 'admin', '', '', '', '', '[]', 80, 0, 0, 1, '/admin/setting/store_service/edit', '', 1, '', 0, 'setting-store_service-edit', 0),
(321, 423, '', '删除客服', 'admin', '', '', 'app/wechat/kefu/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(322, 423, '', '客服是否开启', 'admin', '', '', 'app/wechat/kefu/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(323, 320, '', '编辑客服表单', 'admin', '', '', 'app/wechat/kefu/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(324, 320, '', '修改客服', 'admin', '', '', 'app/wechat/kefu/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(325, 19, '', '添加身份', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_role/add', '', 1, '', 0, 'setting-system_role-add', 0),
(326, 325, '', '添加身份表单', 'admin', '', '', 'setting/role/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(327, 325, '', '添加修改身份', 'admin', '', '', 'setting/role/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(328, 325, '', '修改身份表单', 'admin', '', '', 'setting/role/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(329, 19, '', '修改身份状态', 'admin', '', '', 'setting/role/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(330, 19, '', '删除身份', 'admin', '', '', 'setting/role/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(331, 20, '', '添加管理员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_admin/add', '', 1, '', 0, 'setting-system_admin-add', 0),
(332, 331, '', '添加管理员表单', 'admin', '', '', 'setting/admin/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(333, 331, '', '添加管理员', 'admin', '', '', 'setting/admin', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(334, 20, '', '编辑管理员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin /setting/system_admin/edit', '', 1, '', 0, ' setting-system_admin-edit', 0),
(335, 334, '', '编辑管理员表单', 'admin', '', '', 'setting/admin/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(336, 334, '', '修改管理员', 'admin', '', '', 'setting/admin/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(337, 20, '', '删除管理员', 'admin', '', '', 'setting/admin/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(338, 21, '', '添加规则', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/add', '', 1, '', 0, 'setting-system_menus-add', 0),
(339, 338, '', '添加权限表单', 'admin', '', '', 'setting/menus/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(340, 338, '', '添加权限', 'admin', '', '', 'setting/menus', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(341, 21, '', '修改权限', 'admin', 'setting.system_menus', 'edit', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/edit', '', 1, '', 0, '/setting-system_menus-edit', 0),
(342, 341, '', '编辑权限表单', 'admin', '', '', 'setting/menus/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(343, 341, '', '修改权限', 'admin', '', '', 'setting/menus/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(344, 21, '', '修改权限状态', 'admin', '', '', 'setting/menus/show/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(345, 21, '', '删除权限菜单', 'admin', '', '', 'setting/menus/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(346, 338, '', '添加子菜单', 'admin', 'setting.system_menus', 'add', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/add_sub', '', 1, '', 0, 'setting-system_menus-add_sub', 0),
(347, 361, '', '是否登陆短信平台', 'admin', '', '', 'notify/sms/is_login', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(348, 361, '', '短信剩余条数', 'admin', '', '', 'notify/sms/number', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(349, 95, '', '获取短信验证码', 'admin', '', '', 'serve/captcha', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(350, 95, '', '修改注册账号', 'admin', '', '', 'serve/register', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(351, 95, '', '登陆短信平台', 'admin', '', '', 'serve/login', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(353, 95, '', '退出短信登陆', 'admin', '', '', 'notify/sms/logout', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(355, 96, '', '短信模板列表', 'admin', '', '', 'serve/sms/temps', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(356, 96, '', '申请模板', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_template_apply/add', '', 1, '', 0, 'setting-sms-sms_template_apply-add', 0),
(357, 356, '', '申请短信模板表单', 'admin', '', '', 'notify/sms/temp/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(358, 356, '', '保存申请短信模板', 'admin', '', '', 'notify/sms/temp', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(359, 97, '', '短信套餐', 'admin', '', '', 'serve/meal_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(360, 97, '', '短信购买支付码', 'admin', '', '', 'serve/pay_meal', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(361, 94, '', '短信设置附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/attach', '', 1, '', 0, '', 0),
(362, 300, '', '门店数据', 'admin', '', '', 'merchant/store/get_header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(363, 300, '', '门店列表展示', 'admin', '', '', 'merchant/store', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(364, 424, '', '修改门店状态', 'admin', '', '', 'merchant/store/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(366, 7, '', '首页统计数据', 'admin', '', '', 'home/header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(367, 7, '', '首页订单图表', 'admin', '', '', 'home/order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(368, 7, '', '首页用户图表', 'admin', '', '', 'home/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(369, 7, '', '首页交易额排行', 'admin', '', '', 'home/rank', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(370, 72, '', '优惠卷领取列表', 'admin', '', '', 'marketing/coupon/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(371, 74, '', '砍价列表', 'admin', '', '', 'marketing/bargain', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(372, 74, '', '附加权限', 'admin', 'marketing.store_bargain', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_bargain/attr', '', 1, '', 0, '', 0),
(373, 372, '', '修改砍价状态', 'admin', '', '', 'marketing/bargain/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(374, 372, '', '砍价商品详情', 'admin', '', '', 'marketing/bargain/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(375, 74, '', '公共权限', 'admin', 'marketing.store_bargain', 'public', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_bargain/public', '', 1, '', 0, '', 0),
(376, 375, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(377, 375, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(378, 375, '', '运费模板', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(379, 372, '', '修改添加砍价商品', 'admin', '', '', 'marketing/bargain/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(380, 372, '', '删除砍价商品', 'admin', '', '', 'marketing/bargain/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(381, 75, '', '拼团列表', 'admin', '', '', 'marketing/combination', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(382, 75, '', '拼团数据', 'admin', '', '', 'marketing/combination/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(383, 75, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(384, 383, '', '拼团状态', 'admin', '', '', 'marketing/combination/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(385, 383, '', '删除拼团', 'admin', '', '', 'marketing/combination/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(386, 75, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(387, 386, '', '树型分类列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(388, 386, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(389, 386, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(390, 383, '', '获取拼团详情', 'admin', '', '', 'marketing/combination/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(391, 383, '', '编辑添加拼团', 'admin', '', '', 'marketing/combination/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(392, 76, '', '正在拼团列表', 'admin', '', '', 'marketing/combination/combine/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(393, 76, '', '拼团人员列表', 'admin', '', '', 'marketing/combination/order_pink/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(395, 77, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(396, 395, '', '修改拼团状态', 'admin', '', '', 'marketing/seckill/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(397, 77, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(398, 397, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(399, 397, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(400, 397, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(401, 397, '', '秒杀时间段列表', 'admin', '', '', 'marketing/seckill/time_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(402, 395, '', '编辑添加秒杀商品', 'admin', '', '', 'marketing/seckill/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(403, 395, '', '删除秒杀商品', 'admin', '', '', 'marketing/seckill/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(404, 39, '', '提现申请列表', 'admin', '', '', 'finance/extract', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(405, 39, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(406, 44, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(407, 406, '', '保存修改文章', 'admin', '', '', 'cms/cms', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(408, 406, '', '获取文章详情', 'admin', '', '', 'cms/cms/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(409, 44, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(410, 406, '', '关联商品列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(411, 406, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(412, 406, '', '关联商品', 'admin', '', '', 'cms/cms/relation/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(413, 406, '', '取消关联', 'admin', '', '', 'cms/cms/unrelation/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(414, 406, '', '删除文章', 'admin', '', '', 'cms/cms/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(415, 45, '', '文章列表', 'admin', '', '', 'cms/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(416, 45, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(417, 416, '', '文章分类添加表单', 'admin', '', '', 'cms/category/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'cms-category-create', 0),
(418, 416, '', '保存文章分类', 'admin', '', '', 'cms/category', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(419, 416, '', '编辑文章分类', 'admin', '', '', 'cms/category/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(420, 416, '', '修改文章分类', 'admin', '', '', 'cms/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(421, 416, '', '删除文章分类', 'admin', '', '', 'cms/category/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(422, 678, '', '客服列表', 'admin', '', '', 'app/wechat/kefu', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(423, 678, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(424, 300, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(425, 144, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(426, 425, '', '地图KEY权限', 'admin', '', '', 'merchant/store/address', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(427, 424, '', '添加编辑门店', 'admin', '', '', 'merchant/store/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'setting-merchant-system_store-save', 0),
(428, 424, '', '设置门店隐藏显示', 'admin', '', '', 'merchant/store/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(429, 424, '', '门店详情', 'admin', '', '', 'merchant/store/get_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(430, 424, '', '删除门店', 'admin', '', '', 'merchant/store/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(431, 425, '', '店员搜索门店列表', 'admin', '', '', 'merchant/store_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(432, 301, '', '店员列表', 'admin', '', '', 'merchant/store_staff', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(433, 301, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(434, 433, '', '添加店员表单', 'admin', '', '', 'merchant/store_staff/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'merchant-store_staff-create', 0),
(435, 425, '', '选择用户插件列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(436, 433, '', '添加修改店员', 'admin', '', '', 'merchant/store_staff/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(437, 433, '', '店员显示隐藏', 'admin', '', '', 'merchant/store_staff/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(438, 433, '', '编辑店员表单', 'admin', '', '', 'merchant/store_staff/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(439, 433, '', '删除店员', 'admin', '', '', 'merchant/store_staff/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(440, 302, '', '核销订单列表', 'admin', '', '', 'merchant/verify_order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(441, 302, '', '核销订单数据', 'admin', '', '', 'merchant/verify_badge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(442, 229, '', '城市数据列表', 'admin', '', '', 'setting/city/list/<parent_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(443, 229, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(444, 443, '', '获取添加城市表单', 'admin', '', '', 'setting/city/add/<parent_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(445, 443, '', '保存修改城市数据', 'admin', '', '', 'setting/city/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(446, 443, '', '获取修改城市表单', 'admin', '', '', 'setting/city/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(447, 443, '', '删除城市数据', 'admin', '', '', 'setting/city/del/<city_id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(448, 145, '', '物流公司列表', 'admin', '', '', 'freight/express', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(449, 145, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(450, 449, '', '修改物流公司状态', 'admin', '', '', 'freight/express/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(451, 449, '', '获取添加物流公司表单', 'admin', '', '', 'freight/express/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(452, 449, '', '保存物流公司', 'admin', '', '', 'freight/express', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(453, 449, '', '获取编辑物流公司表单', 'admin', '', '', 'freight/express/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(454, 449, '', '修改物流公司', 'admin', '', '', 'freight/express/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(455, 449, '', '删除物流公司', 'admin', '', '', 'freight/express/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(456, 230, '', '运费模板列表', 'admin', '', '', 'setting/shipping_templates/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(457, 230, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(458, 457, '', '运费模板城市数据', 'admin', '', '', 'setting/shipping_templates/city_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(459, 457, '', '保存或者修改运费模板', 'admin', '', '', 'setting/shipping_templates/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(460, 457, '', '删除运费模板', 'admin', '', '', 'setting/shipping_templates/del/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(461, 111, '', '配置分类列表', 'admin', '', '', 'setting/config_class', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(462, 111, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(463, 462, '', '配置分类添加表单', 'admin', '', '', 'setting/config_class/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(464, 462, '', '保存配置分类', 'admin', '', '', 'setting/config_class', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(465, 641, '', '编辑配置分类', 'admin', '', '', 'setting/config_class/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(466, 462, '', '删除配置分类', 'admin', '', '', 'setting/config_class/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(467, 125, '', '配置列表展示', 'admin', '', '', 'setting/config', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(468, 125, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(469, 468, '', '添加配置字段表单', 'admin', '', '', 'setting/config/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(470, 468, '', '保存配置字段', 'admin', '', '', 'setting/config', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(471, 468, '', '编辑配置字段表单', 'admin', '', '', 'setting/config/<id>/edit', '', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(472, 468, '', '编辑配置分类', 'admin', '', '', 'setting/config/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(473, 468, '', '删除配置', 'admin', '', '', 'setting/config/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(474, 468, '', '修改配置状态', 'admin', '', '', 'setting/config/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(475, 112, '', '组合数据列表', 'admin', '', '', 'setting/group', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(476, 112, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(477, 476, '', '新增组合数据', 'admin', '', '', 'setting/group', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(478, 476, '', '获取组合数据', 'admin', '', '', 'setting/group/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(479, 476, '', '修改组合数据', 'admin', '', '', 'setting/group/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(480, 476, '', '删除组合数据', 'admin', '', '', 'setting/group/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(481, 126, '', '组合数据列表表头', 'admin', '', '', 'setting/group_data/header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(482, 126, '', '组合数据列表', 'admin', '', '', 'setting/group_data', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(483, 126, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(484, 483, '', '获取组合数据添加表单', 'admin', '', '', 'setting/group_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(485, 483, '', '保存组合数据', 'admin', '', '', 'setting/group_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(486, 483, '', '获取组合数据信息', 'admin', '', '', 'setting/group_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(487, 483, '', '修改组合数据信息', 'admin', '', '', 'setting/group_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(488, 483, '', '删除组合数据', 'admin', '', '', 'setting/group_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(489, 483, '', '修改组合数据状态', 'admin', '', '', 'setting/group_data/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(490, 57, '', '清除缓存', 'admin', '', '', 'system/refresh_cache/cache', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(491, 57, '', '清除日志', 'admin', '', '', 'system/refresh_cache/log', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(492, 47, '', '管理员搜索列表', 'admin', '', '', 'system/log/search_admin', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(493, 47, '', '系统日志列表', 'admin', '', '', 'system/log', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(494, 64, '', '文件校验列表', 'admin', '', '', 'system/file', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(495, 66, '', '清除数据接口', 'admin', '', '', 'system/clear/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(496, 67, '', '数据库列表', 'admin', '', '', 'system/backup', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(497, 67, '', '数据库备份列表', 'admin', '', '', 'system/backup/file_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(498, 67, '', '数据表详情', 'admin', '', '', 'system/backup/read', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(499, 67, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(500, 499, '', '备份表', 'admin', '', '', 'system/backup/backup', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(501, 499, '', '优化表', 'admin', '', '', 'system/backup/optimize', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(502, 499, '', '修复表', 'admin', '', '', 'system/backup/repair', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(503, 499, '', '导入sql', 'admin', '', '', 'system/backup/import', 'POST', '[]', 0, 0, 1, 1, '', '', 2, '', 0, '', 0),
(504, 499, '', '删除数据库备份', 'admin', '', '', 'system/backup/del_file', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(505, 499, '', '备份下载', 'admin', '', '', 'backup/download', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(507, 92, '', '微信菜单列表', 'admin', '', '', 'app/wechat/menu', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(508, 92, '', '保存微信菜单', 'admin', '', '', 'app/wechat/menu', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(553, 109, '', '图文列表', 'admin', '', '', 'app/wechat/news', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(554, 109, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(555, 554, '', '保存图文', 'admin', '', '', 'app/wechat/news', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(556, 554, '', '图文详情', 'admin', '', '', 'app/wechat/news/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(557, 554, '', '删除图文', 'admin', '', '', 'app/wechat/news/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(558, 114, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(559, 558, '', '回复关键词', 'admin', '', '', 'app/wechat/reply', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(560, 115, '', '关键词回复列表', 'admin', '', '', 'app/wechat/keyword', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(561, 115, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(562, 558, '', '保存修改关键字', 'admin', '', '', 'app/wechat/keyword/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(563, 561, '', '获取关键字信息', 'admin', '', '', 'app/wechat/keyword/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(564, 561, '', '修改关键字状态', 'admin', '', '', 'app/wechat/keyword/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(565, 561, '', '删除关键字', 'admin', '', '', 'app/wechat/keyword/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(566, 656, '', '素材管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/system/file', '12/656', 1, '', 0, 'system-file', 0),
(567, 566, '', '附件列表', 'admin', '', '', 'file/file', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(568, 566, '', '附件分类', 'admin', '', '', 'file/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(569, 566, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(570, 569, '', '附件分类表单', 'admin', '', '', 'file/category/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(571, 569, '', '附件分类保存', 'admin', '', '', 'file/category', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(572, 569, '', '删除附件', 'admin', '', '', 'file/file/delete', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(573, 569, '', '移动附件分类', 'admin', '', '', 'file/file/do_move', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(574, 566, '', '上传附件', 'admin', '', '', 'file/upload/<upload_type?>', 'POST', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(575, 569, '', '附件分类编辑表单', 'admin', '', '', 'file/category/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(576, 569, '', '附件分类修改', 'admin', '', '', 'file/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(577, 2, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(578, 3, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(579, 6, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(580, 99, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(581, 5, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(582, 70, '', '优惠卷模板列表', 'admin', '', '', 'marketing/coupon/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(583, 70, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(584, 29, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(585, 10, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(586, 11, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(587, 25, '', '个人中心', 'admin', '', '', '', '', '[]', 0, 1, 1, 1, '/admin/system/user', '', 1, '', 0, 'system-user', 0),
(589, 9, '', '用户标签', 'admin', 'user.user_label', 'index', '', '', '[]', 8, 1, 0, 1, '/admin/user/label', '', 1, 'user', 1, 'user-user-label', 0),
(590, 589, '', '获取用户标签', 'admin', '', '', 'user/label/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '9/589', 2, '', 0, '', 0),
(591, 589, '', '删除用户标签', 'admin', '', '', 'user/user_label/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(592, 589, '', '添加修改用户标签', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_add', '', 1, '', 0, 'admin-user-label_add', 0),
(593, 592, '', '添加修改用户标签表单', 'admin', '', '', 'user/user_label/add/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(594, 592, '', '保存修改用户标签', 'admin', '', '', 'user/user_label/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(596, 2, '', '商品导出', 'admin', '', '', 'export/storeProduct', 'GET', '[]', 10, 0, 0, 1, '', '', 2, '', 0, 'export-storeProduct', 0),
(597, 5, '', '订单导出', 'admin', '', '', 'export/storeorder', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeOrder', 0),
(598, 77, '', '秒杀商品导出', 'admin', '', '', 'export/storeSeckill', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeSeckill', 0),
(600, 75, '', '拼团商品导出', 'admin', '', '', 'export/storeCombination', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeCombination', 0),
(601, 74, '', '砍价商品导出', 'admin', '', '', 'export/storeBargain', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeBargain', 0),
(602, 29, '', '推广员列表导出', 'admin', '', '', 'export/userAgent', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userAgent', 0),
(603, 40, '', '用户充值导出', 'admin', '', '', 'export/userRecharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userRecharge', 0),
(605, 25, '', '商业授权', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/auth', '', 1, '', 0, 'system-maintain-auth', 0),
(606, 29, '', '分销员数据', 'admin', '', '', 'agent/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(607, 587, '', '修改密码', 'admin', '', '', 'setting/update_admin', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(608, 605, '', '商业授权', 'admin', '', '', 'auth', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(610, 20, '', '管理员列表', 'admin', '', '', 'setting/admin', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(611, 19, '', '身份列表', 'admin', '', '', 'setting/role', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(612, 2, '', '批量上下架', 'admin', '', '', 'product/product/product_show', 'PUT', '[]', 5, 0, 0, 1, '', '', 2, '', 0, 'product-product-product_show', 0),
(613, 585, '', '批量设置标签', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/set_label', '', 1, '', 0, 'admin-user-set_label', 0),
(614, 613, '', '获取标签表单', 'admin', '', '', 'user/set_label', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(615, 613, '', '保存标签', 'admin', '', '', 'user/save_set_label', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(618, 42, '', '佣金导出', 'admin', '', '', 'export/userCommission', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userCommission', 0),
(619, 21, '', '权限列表', 'admin', '', '', 'setting/menus', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(620, 22, '', '商品详情', 'admin', '', '', 'product/product/<id>', 'GET', '[]', 0, 1, 1, 1, '', '', 2, '', 0, '', 0),
(621, 585, '', '保存用户信息', 'admin', '', '', 'user/user', 'POST', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(622, 585, '', '积分余额', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/edit_other', '', 1, '', 0, '', 0),
(623, 622, '', '获取修改用户详情表单', 'admin', '', '', 'user/edit_other/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(624, 622, '', '修改用户余额', 'admin', '', '', 'user/update_other/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(625, 585, '', '赠送用户', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/user_level', '', 1, '', 0, '', 0),
(626, 625, '', '获取表单', 'admin', '', '', 'user/give_level/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(627, 625, '', '赠送会员等级', 'admin', '', '', 'user/save_give_level/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(628, 585, '', '单个用户分组设置', 'admin', '', '', 'user/save_set_group', 'PUT', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(630, 375, '', '获取商品属性', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(631, 386, '', '商品规格获取', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(632, 397, '', '商品规格和获取', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(633, 395, '', '获取秒杀详情', 'admin', '', '', 'marketing/seckill/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(634, 40, '', '删除充值记录', 'admin', '', '', 'finance/recharge/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(635, 20, '', '修改管理员状态', 'admin', '', '', 'setting/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(636, 25, '', '其他权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/system/other', '', 1, '', 0, '', 0),
(637, 636, '', '消息提醒', 'admin', '', '', 'jnotice', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(638, 457, '', '获取运费模板详情', 'admin', '', '', 'setting/shipping_templates/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(639, 457, '', '删除运费模板', 'admin', '', '', 'setting/shipping_templates/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(640, 462, '', '修改配置分类状态', 'admin', '', '', 'setting/config_class/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(641, 462, '', '编辑配置分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, 'system/config/system_config_tab/edit', '', 1, '', 0, '', 0),
(642, 641, '', '获取编辑配置分类表单', 'admin', '', '', 'setting/config_class/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(655, 65, '', '在线升级', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/system/system_upgradeclient/index', '', 1, '', 0, 'system-system-upgradeclient', 0),
(656, 12, '', '页面管理', 'admin', '', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/pages', '', 1, '', 0, 'admin-setting-pages', 0),
(657, 656, '', '页面设计', 'admin', '', '', '', '', '[]', 3, 1, 0, 1, '/admin/setting/pages/devise', '12/656', 1, '', 0, 'admin-setting-pages-devise', 0),
(658, 656, '', '页面编辑', 'admin', '', '', '', '', '[]', 3, 1, 1, 1, '/admin/setting/pages/diy', '12/656', 1, '', 0, 'admin-setting-pages-diy', 0),
(660, 656, '', '页面链接', 'admin', '', '', '', '', '[]', 3, 0, 0, 1, '/admin/setting/pages/links', '12/656', 1, '', 0, 'admin-setting-pages-links', 0),
(661, 657, '', 'DIY列表', 'admin', '', '', 'diy/get_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(662, 657, '', '组件文章分类', 'admin', '', '', 'cms/category_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(663, 657, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/diy', '', 1, '', 0, 'admin-setting-diy-additional', 0),
(664, 663, '', '获取页面设计', 'admin', '', '', 'diy/get_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(665, 663, '', '保存和修改页面', 'admin', '', '', 'diy/save/<id?>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-setting-pages-diy-save', 0),
(666, 660, '', '路径列表', 'admin', '', '', 'diy/get_url', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(667, 663, '', '删除页面', 'admin', '', '', 'diy/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(668, 663, '', '修改页面状态', 'admin', '', '', 'diy/set_status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(669, 2, '', '批量下架', 'admin', '', '', 'product/product/product_unshow', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(670, 581, '', '订单打印', 'admin', '', '', 'order/print/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(671, 585, '', '清除会员等级', 'admin', '', '', 'user/del_level/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(672, 271, '', 'H5推广二维码', 'admin', '', '', 'agent/look_h5_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(673, 416, '', '修改文章分类状态', 'admin', '', '', 'cms/category/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(674, 229, '', '清除城市缓存', 'admin', '', '', 'setting/city/clean_cache', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(675, 657, '', '组件商品分类', 'admin', '', '', 'diy/get_category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(676, 657, '', '组件商品列表', 'admin', '', '', 'diy/get_product', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(677, 581, '', '订单号核销', 'admin', '', '', 'order/write_update/<order_id>', 'PUT', '[]', 0, 0, 0, 1, 'order/dels', '', 2, '', 0, 'admin-order-write_update', 0),
(678, 165, '', '客服列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/index', '', 1, '', 0, 'admin-setting-store_service-index', 0),
(679, 165, '', '客服话术', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/speechcraft', '', 1, '', 0, 'admin-setting-store_service-speechcraft', 0),
(685, 22, '', '上传商品视频', 'admin', '', '', 'product/product/get_temp_keys', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(686, 27, '', '直播管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live', '', 1, '', 0, 'admin-marketing-live', 0),
(687, 686, '', '直播间管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/live_room', '', 1, '', 0, 'admin-marketing-live-live_room', 0),
(688, 686, '', '直播商品管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/live_goods', '', 1, '', 0, 'admin-marketing-live-live_goods', 0),
(689, 686, '', '主播管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/anchor', '', 1, '', 0, 'admin-marketing-live-anchor', 0),
(690, 687, '', '添加直播间', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/live/add_live_room', '', 1, '', 0, 'admin-marketing-live-add_live_room', 0),
(691, 688, '', '添加直播商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/live/add_live_goods', '', 1, '', 0, 'admin-marketing-live-add_live_goods', 0),
(693, 689, '', '主播列表', 'admin', '', '', 'live/anchor/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-marketing-live-anchor', 0),
(694, 689, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '', 0),
(695, 694, '', '添加/修改主播表单', 'admin', '', '', 'live/anchor/add/<id>', 'GET', '[]', 0, 0, 0, 1, 'live/anchor/add/<id>', '', 2, '', 0, 'live-anchor-add', 0),
(696, 694, '', '添加/修改提交', 'admin', '', '', 'live/anchor/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(697, 694, '', '删除主播', 'admin', '', '', 'live/anchor/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(698, 694, '', '设置主播是否显示', 'admin', '', '', 'live/anchor/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(699, 688, '', '直播商品列表', 'admin', '', '', 'live/goods/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(700, 691, '', '生成直播商品', 'admin', '', '', 'live/goods/create', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(701, 691, '', '保存直播商品', 'admin', '', '', 'live/goods/add', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(702, 688, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '/admin/*', 0),
(703, 702, '', '直播商品详情', 'admin', '', '', 'live/goods/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(704, 702, '', '删除直播商品', 'admin', '', '', 'live/goods/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(705, 702, '', '同步直播商品', 'admin', '', '', 'live/goods/syncGoods', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(706, 702, '', '设置直播商品是否显示', 'admin', '', '', 'live/goods/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(707, 687, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '', 0),
(708, 687, '', '直播间列表', 'admin', '', '', 'live/room/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(709, 707, '', '添加直播间提交', 'admin', '', '', 'live/room/add', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(710, 707, '', '直播间详情', 'admin', '', '', 'live/room/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(711, 707, '', '直播间添加（关联）商品', 'admin', '', '', 'live/room/add_goods', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(712, 707, '', '删除直播间', 'admin', '', '', 'live/room/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(713, 707, '', '设置直播间是否显示', 'admin', '', '', 'live/room/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(714, 707, '', '同步直播间状态', 'admin', '', '', 'live/room/syncRoom', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(715, 898, '', '一键同步订阅消息', 'admin', '', '', 'app/routine/syncSubscribe', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, 'app-wechat-template-sync', 0),
(716, 0, 'md-stats', '统计', 'admin', '', '', '', '', '[]', 1, 1, 0, 1, '/admin/statistic', '', 1, '', 0, 'admin-statistic', 0),
(717, 716, '', '商品统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/product', '', 1, '', 0, 'admin-statistic', 0),
(718, 716, '', '用户统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/user', '', 1, '', 0, 'admin-statistic', 0),
(719, 71, '', '添加优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon_issue/create', '27/30/71', 1, '', 0, 'marketing-store_coupon_issue-create', 0),
(720, 303, '', '配送员管理', 'admin', '', '', '', '', '[]', 10, 1, 0, 1, '/admin/setting/delivery_service/index', '', 1, '', 0, 'setting-delivery-service', 0),
(721, 729, '', '编辑配送员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/delivery_service/edit', '', 1, '', 0, 'setting-delivery_service-edit', 0),
(722, 720, '', '配送员列表', 'admin', '', '', 'order/delivery/index', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(723, 721, '', '修改配送员', 'admin', '', '', 'order/delivery/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(724, 729, '', '添加配送员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/delivery_service/add', '', 1, '', 0, 'setting-delivery_service-add', 0),
(725, 724, '', '获取添加配送员表单', 'admin', '', '', 'order/delivery/add', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(726, 724, '', '保存配送员', 'admin', '', '', 'order/delivery/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(727, 729, '', '删除配送员', 'admin', '', '', 'order/delivery/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(728, 729, '', '配送员是否开启', 'admin', '', '', 'order/delivery/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(729, 720, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(731, 9, '', '付费会员', 'admin', '', '', '', '', '[]', 7, 1, 0, 1, '/admin/user/grade', '', 1, '', 0, 'user-user-grade', 0),
(732, 762, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(733, 732, '', ' 添加会员批次', 'admin', '', '', 'user/member_batch/save/<id>', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(734, 732, '', '列表字段修改', 'admin', '', '', 'user/member_batch/set_value/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'user-member_batch-set_value', 0),
(735, 732, '', '会员卡导出', 'admin', '', '', 'export/memberCard/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'export-member_card', 0),
(736, 762, '', '卡密列表', 'admin', '', '', 'user/member_batch/index', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(737, 732, '', '会员卡列表', 'admin', '', '', 'user/member_card/index', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member_card-index', 0),
(738, 165, '', '用户留言', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/feedback', '', 1, '', 0, 'admin-setting-store_service-feedback', 0),
(739, 738, '', '列表展示', 'admin', '', '', 'app/feedback', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(740, 738, '', '附件权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '*', '', 1, '', 0, '', 0),
(741, 740, '', '删除反馈', 'admin', '', '', 'app/feedback/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(742, 679, '', '列表展示', 'admin', '', '', 'app/wechat/speechcraft', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(743, 679, '', '附件权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '*', '', 1, '', 0, '', 0),
(744, 743, '', '添加话术', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/add', '', 1, '', 0, 'admin-setting-store_service-speechcraft-add', 0),
(745, 743, '', '编辑话术', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/edit', '', 1, '', 0, 'admin-setting-store_service-speechcraft-edit', 0),
(746, 744, '', '获取添加话术表单', 'admin', '', '', 'app/wechat/speechcraft/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(747, 744, '', '保存话术', 'admin', '', '', 'app/wechat/speechcraft', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(748, 745, '', '获取编辑话术表单', 'admin', '', '', 'app/wechat/speechcraft/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(749, 745, '', '确认修改', 'admin', '', '', 'app/wechat/speechcraft/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(750, 743, '', '删除话术', 'admin', '', '', 'app/wechat/speechcraft/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(751, 731, '', '会员类型', 'admin', '', '', '', '', '[]', 5, 1, 0, 1, '/admin/user/grade/type', '', 1, '', 0, 'admin-user-member-type', 0),
(752, 751, '', '会员分类列表', 'admin', '', '', 'user/member/ship', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-ship', 0),
(753, 751, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(754, 753, '', '会员卡类型保存', 'admin', '', '', 'user/member_ship/save/<id>', 'POST', '[]', 0, 1, 1, 1, '', '', 2, '', 0, 'user-member_ship-save', 0),
(755, 31, '', '砍价列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_bargain/bargain_list', '', 1, '', 0, 'marketing-store_bargain-bargain_list', 0),
(756, 585, '', '添加用户', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/save', '', 1, '', 0, 'admin-user-save', 0),
(757, 756, '', '获取添加用户表单', 'admin', '', '', 'user/user/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(758, 756, '', '保存用户', 'admin', '', '', 'user/user', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(759, 585, '', '同步公众号用户', 'admin', '', '', 'user/user/syncUsers', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-user-synchro', 0),
(760, 4, '', '收银订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/offline', '', 1, '', 0, 'admin-order-offline', 0),
(761, 760, '', '线下收银订单', 'admin', '', '', 'order/scan_list', 'GET', '[]', 0, 0, 1, 1, '', '', 2, '', 0, 'admin-order-scan_list', 0),
(762, 731, '', '卡密会员', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/user/grade/card', '', 1, '', 0, 'admin-user-grade-card', 0),
(763, 731, '', '会员记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/user/grade/record', '', 1, '', 0, 'admin-user-grade-record', 0),
(764, 763, '', '会员记录列表', 'admin', '', '', 'user/member/record', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-record', 0),
(765, 731, '', '会员权益', 'admin', '', '', '', '', '[]', 4, 1, 0, 1, '/admin/user/grade/right', '', 1, '', 0, 'admin-user-grade-right', 0),
(766, 716, '', '交易统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/transaction', '', 1, '', 0, 'admin-statistic', 0),
(767, 36, '', '发票管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/invoice/list', '', 1, '', 0, 'admin-order-startOrderInvoice-index', 0),
(768, 210, '', '编辑', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-edit', 0),
(769, 210, '', '订单信息', 'admin', '', '', 'order/invoice_order_info/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-orderInfo', 0),
(770, 210, '', '编辑提交', 'admin', '', '', 'order/invoice/set/<id>', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-update', 0),
(771, 765, '', '会员权益列表', 'admin', '', '', 'user/member/right', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-right', 0),
(772, 765, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(773, 772, '', '会员权益保存', 'admin', '', '', 'user/member_right/save/<id>', 'POST', '[]', 0, 1, 1, 1, '', '', 2, '', 0, 'user-member_right-save', 0),
(774, 589, '', '用户标签列表', 'admin', '', '', 'user/user_label_cate/all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-user-user_lable_cate-all', 0),
(775, 731, '', '会员协议', 'admin', '', '', '', '', '[]', 3, 1, 0, 1, '/admin/user/grade/agreement', '', 1, '', 0, 'admin-user-grade-agreement', 0),
(776, 775, '', '编辑会员协议', 'admin', '', '', 'user/member_agreement/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'member_agreement-save', 0),
(777, 775, '', '会员协议列表', 'admin', '', '', 'user/member/agreement', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-agreement-list', 0),
(778, 740, '', '获取修改备注表单接口', 'admin', '', '', 'app/feedback/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(779, 740, '', '修改用户备注接口', 'admin', '', '', 'app/feedback/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(780, 589, '', '标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate', '', 1, '', 0, '', 0),
(781, 780, '', '获取标签分类列表', 'admin', '', '', 'user/user_label_cate/all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(782, 780, '', '添加标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate/add', '', 1, '', 0, '', 0),
(783, 782, '', '获取标签分类表单', 'admin', '', '', 'user/user_label_cate/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(784, 782, '', '保存标签分类', 'admin', '', '', 'user/user_label_cate', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(785, 780, '', '修改标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate/edit', '', 1, '', 0, '', 0),
(786, 785, '', '获取修改标签分类表单', 'admin', '', '', 'user/user_label_cate/<id>/edit', 'GET', '[]', 0, 0, 0, 1, 'user/user_label_cate/<id>/edit', '', 2, '', 0, '', 0),
(787, 785, '', '保存用户标签分类', 'admin', '', '', 'user/user_label_cate/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(788, 780, '', '删除用户标签分类', 'admin', '', '', 'user/user_label_cate/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(789, 743, '', '话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate', '', 1, '', 0, 'admin-setting-store_service-speechcraft-cate', 0),
(790, 789, '', '获取话术分类列表', 'admin', '', '', 'app/wechat/speechcraftcate', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(791, 789, '', '添加话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate/create', '', 1, '', 0, '', 0),
(792, 791, '', '获取话术分类表单', 'admin', '', '', 'app/wechat/speechcraftcate/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(793, 791, '', '保存话术分类', 'admin', '', '', 'app/wechat/speechcraftcate', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(794, 795, '', '获取话术分类表单', 'admin', '', '', 'app/wechat/speechcraftcate/<id>/edit', 'GET', '[]', 0, 0, 0, 1, 'app/wechat/speechcraftcate/<id>/edit', '', 2, '', 0, '', 0),
(795, 789, '', '修改话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate/edit', '', 1, '', 0, '', 0),
(796, 795, '', '保存修改客户话术分类', 'admin', '', '', 'app/wechat/speechcraftcate/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(797, 789, '', '删除话术分类', 'admin', '', '', 'app/wechat/speechcraftcate/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(798, 209, '', '获取送货人列表', 'admin', '', '', 'order/delivery/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(799, 209, '', '获取电子面单打印默认配置', 'admin', '', '', 'order/sheet_info', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(800, 581, '', '电子面单打印', 'admin', '', '', 'order/order_dump/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(801, 760, '', '获取收银二维码', 'admin', '', '', 'order/offline_scan', 'GET', '[]', 0, 0, 0, 1, '', '4/760', 2, '', 0, '', 0),
(802, 767, '', '获取订单发票数据', 'admin', '', '', 'order/invoice/chart', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(803, 762, '', '下载卡密二维码', 'admin', '', '', 'user/member_scan', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(805, 584, '', '修改推广人', 'admin', '', '', 'agent/spread', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(806, 71, '', '复制优惠券', 'admin', '', '', 'marketing/coupon/copy/<id>', 'GET', '[]', 0, 0, 0, 1, 'marketing/coupon/copy/369', '', 2, '', 0, '', 0),
(807, 755, '', '获取砍价列表', 'admin', '', '', 'marketing/bargain_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(808, 77, '', '秒杀商品列表', 'admin', '', '', 'marketing/seckill', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(809, 95, '', '获取平台用户信息', 'admin', '', '', 'serve/info', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(810, 95, '', '获取平台消费列表', 'admin', '', '', 'serve/record', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(811, 95, '', '修改手机号', 'admin', '', '', 'serve/update_phone', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(812, 95, '', '修改签名', 'admin', '', '', 'serve/sms/sign', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(813, 95, '', '修改账号密码', 'admin', '', '', 'serve/modify', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(814, 721, '', '获取编辑配送员表单', 'admin', '', '', 'order/delivery/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(815, 717, '', '获取基础商品接口', 'admin', '', '', 'statistic/product/get_basic', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(816, 717, '', '获取商品趋势', 'admin', '', '', 'statistic/product/get_trend', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(817, 717, '', '获取商品排行', 'admin', '', '', 'statistic/product/get_product_ranking', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(818, 718, '', '获取用户基础', 'admin', '', '', 'statistic/user/get_basic', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(819, 718, '', '获取用户趋势', 'admin', '', '', 'statistic/user/get_trend', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(820, 718, '', '获取用户地区排行', 'admin', '', '', 'statistic/user/get_region', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(821, 718, '', '获取用户性别排行', 'admin', '', '', 'statistic/user/get_sex', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(822, 766, '', '获取交易趋势', 'admin', '', '', 'statistic/trade/top_trade', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(823, 766, '', '获取订单趋势', 'admin', '', '', 'statistic/trade/bottom_trade', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(824, 718, '', '导出用户统计', 'admin', '', '', 'statistic/user/get_excel', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(825, 717, '', '导出商品统计', 'admin', '', '', 'statistic/product/get_excel', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(828, 10, '', '用户列表', 'admin', '', '', 'user/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(830, 732, '', '卡密列表', 'admin', '', '', 'user/member_card/index/<card_batch_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(831, 423, '', '进入工作台', 'admin', '', '', 'app/wechat/kefu/login/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(832, 71, '', '保存优惠券', 'admin', '', '', 'marketing/coupon/save_coupon', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(833, 755, '', '砍价详情', 'admin', '', '', 'marketing/bargain_list_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(834, 95, '', '短信记录列表', 'admin', '', '', 'notify/sms/record', 'GET', '[]', 0, 0, 0, 1, 'notify/sms/record', '', 2, '', 0, '', 0),
(835, 28, '', '分销设置表单', 'admin', '', '', 'agent/config/edit_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(836, 28, '', '分销设置表单提交', 'admin', '', '', 'agent/config/save_basics', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(837, 79, '', '积分配置表单', 'admin', '', '', 'marketing/integral_config/edit_basics', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(838, 79, '', '积分配置表单提交', 'admin', '', '', 'marketing/integral_config/save_basics', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(843, 154, '', '签到天数头部数据', 'admin', '', '', 'setting/sign_data/header', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(844, 154, '', '设置签到数据状态', 'admin', '', '', 'setting/sign_data/set_status/<id>/<status>', 'PUT', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(845, 154, '', '签到天数列表', 'admin', '', '', 'setting/sign_data', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(846, 154, '', '添加签到天数表单', 'admin', '', '', 'setting/sign_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(847, 154, '', '添加签到天数', 'admin', '', '', 'setting/sign_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(848, 154, '', '编辑签到天数表单', 'admin', '', '', 'setting/sign_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(849, 154, '', '编辑签到天数', 'admin', '', '', 'setting/sign_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(850, 154, '', '删除签到天数', 'admin', '', '', 'setting/sign_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(876, 78, '', '秒杀配置列表', 'admin', '', '', 'setting/seckill_data', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(877, 78, '', '添加秒杀表单', 'admin', '', '', 'setting/seckill_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(878, 78, '', '添加秒杀', 'admin', '', '', 'setting/seckill_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(879, 78, '', '编辑秒杀表单', 'admin', '', '', 'setting/seckill_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(880, 78, '', '编辑秒杀', 'admin', '', '', 'settting/seckill_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(881, 78, '', '删除秒杀', 'admin', '', '', 'setting/seckill_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(884, 128, '', '获取数据分类', 'admin', '', '', 'setting/group_all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(885, 569, '', '附件名称修改', 'admin', '', '', 'file/file/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '25/566/569', 2, '', 0, '', 0),
(886, 577, '', '用户标签接口', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '1/2/577', 2, '', 0, '', 0),
(887, 625, '', '获取赠送付费会员时长表单', 'admin', '', '', 'user/give_level_time/<id>', 'GET', '[]', 0, 0, 0, 1, '', '9/10/585/625', 2, '', 0, '', 0),
(888, 625, '', '保存赠送付费会员时长', 'admin', '', '', 'user/save_give_level_time/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '9/10/585/625', 2, '', 0, '', 0),
(889, 663, '', '添加页面', 'admin', '', '', 'diy/create', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, 'admin-template', 0),
(890, 663, '', '保存新增', 'admin', '', '', 'diy/create', 'POST', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, 'admin-template', 0),
(891, 663, '', '设置默认数据', 'admin', '', '', 'diy/set_recovery/<id?>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, '', 0),
(892, 663, '', '获取商品列表', 'admin', '', '', 'diy/get_product_list', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, '', 0),
(893, 577, '', '商品活动状态检测', 'admin', '', '', 'product/product/check_activity/<id>', 'GET', '[]', 0, 0, 0, 1, '', '1/2/577', 2, '', 0, '', 0),
(894, 589, '', '会员标签列表', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '9/589', 2, '', 0, '', 0),
(895, 585, '', '新增客服选择用户列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 0, 1, '', '9/10/585', 2, '', 0, '', 0),
(896, 26, '', '分销等级', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/membership_level/index', '26', 1, '', 0, '', 0),
(897, 4, '', '售后订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/refund', '4', 1, '', 0, 'admin-order-refund', 0),
(898, 12, '', '消息管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/notification/index', '12', 1, '', 0, 'setting-notification', 0),
(902, 656, '', '主题风格', 'admin', '', '', '', '', '[]', 2, 1, 0, 1, '/admin/setting/theme_style', '12/656', 1, '', 0, 'admin-setting-theme_style', 0),
(903, 656, '', 'PC商城', 'admin', '', '', '', '', '[]', 2, 1, 0, 1, '/admin/setting/pc_group_data', '12/656', 1, '', 0, 'setting-system-pc_data', 0),
(904, 656, '', '客服页面广告', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/system_group_data/kf_adv', '', 1, '', 0, 'setting-system-group_data-kf_adv', 1),
(905, 34, '', '积分商品', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_integral/index', '27/34', 1, '', 0, 'marketing-store_integral', 0),
(906, 905, '', '积分商品列表', 'admin', '', '', 'marketing/integral_product', 'GET', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(908, 905, '', '添加积分商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/store_integral/create', '27/34/905', 1, '', 0, 'marketing-store_integral-create', 0),
(909, 27, '', '九宫格抽奖', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/index', '27', 1, '', 0, 'marketing-lottery-index', 0),
(912, 34, '', '积分订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_integral/order_list', '27/34', 1, '', 0, 'marketing-store_integral-order', 0),
(913, 905, '', '批量添加积分商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/pages/marketing/store_integral/add_store_integral', '27/34/905', 1, '', 0, 'marketing-store_integral-create', 0),
(914, 897, '', '售后订单列表', 'admin', '', '', 'refund/list', 'GET', '[]', 0, 0, 0, 1, '', '4/897', 2, '', 0, '', 0),
(915, 5, '', '子订单列表', 'admin', '', '', 'order/split_order/<id>', 'GET', '[]', 0, 0, 0, 1, 'order/split_order/<id>', '4/5', 2, '', 0, '', 0),
(916, 5, '', '订单详情', 'admin', '', '', 'order/info/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(917, 5, '', '订单记录', 'admin', '', '', 'order/status/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(918, 5, '', '可拆分商品列表', 'admin', '', '', 'order/split_cart_info/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(919, 5, '', '拆单发送货', 'admin', '', '', 'order/split_delivery/<id>', 'PUT', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(920, 896, '', '修改分销等级状态', 'admin', '', '', 'agent/level/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 1, 1, '', '26/896', 2, '', 0, '', 0),
(921, 896, '', '修改分销等级任务状态', 'admin', '', '', 'agent/level_task/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(922, 896, '', '获取赠送分销等级表单', 'admin', '', '', 'agent/get_level_form', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(923, 896, '', '赠送分销等级', 'admin', '', '', 'agent/give_level', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(924, 896, '', '分销等级列表', 'admin', '', '', 'agent/level', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(925, 896, '', '添加分销等级表单', 'admin', '', '', 'agent/level/create', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(926, 896, '', '编辑分销等级表单', 'admin', '', '', 'agent/level/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(927, 896, '', '分销等级任务', 'admin', '', '', 'agent/level_task', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(928, 896, '', '删除分销等级', 'admin', '', '', 'agent/level/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(929, 896, '', '添加分销员等级', 'admin', '', '', 'agent/level', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(930, 896, '', '编辑分销员等级', 'admin', '', '', 'agent/level/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(931, 896, '', '添加分销员等级任务表单', 'admin', '', '', 'agent/level_task/create', 'GET', '[]', 0, 0, 0, 1, 'agent/level_task/create', '26/896', 2, '', 0, '', 0),
(932, 896, '', '添加分销员等级任务', 'admin', '', '', 'agent/level_task', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(933, 896, '', '编辑分销员等级任务表单', 'admin', '', '', 'agent/level_task/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(934, 896, '', '编辑分销员等级任务', 'admin', '', '', 'agent/level_task/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(935, 896, '', '删除分销员等级任务', 'admin', '', '', 'agent/level_task/<id>', 'DELETE', '[]', 0, 0, 0, 1, 'agent/level_task/<id>', '26/896', 2, '', 0, '', 0),
(936, 423, '', '新增客服选择用户列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 1, 1, '', '165/678/423', 2, '', 0, '', 0),
(937, 78, '', '秒杀配置头部', 'admin', '', '', 'setting/seckill_data/header', 'GET', '[]', 0, 0, 0, 1, '', '27/33/78', 2, '', 0, '', 0),
(938, 154, '', '签到配置编辑保存', 'admin', '', '', 'setting/group_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/154', 2, '', 0, '', 0),
(939, 154, '', '签到配置添加保存', 'admin', '', '', 'setting/group_data', 'POST', '[]', 0, 0, 0, 1, '', '27/34/154', 2, '', 0, '', 0),
(940, 905, '', '添加积分商品保存', 'admin', '', '', 'marketing/integral/<id>', 'POST', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(941, 912, '', '积分订单头部', 'admin', '', '', 'marketing/integral/order/chart', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(942, 912, '', '积分订单列表', 'admin', '', '', 'marketing/integral/order/list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(943, 905, '', '积分商品编辑', 'admin', '', '', 'marketing/integral/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(944, 912, '', '发货物流列表', 'admin', '', '', 'marketing/integral/order/express_list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(945, 912, '', '快递列表', 'admin', '', '', 'marketing/integral/order/delivery/list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(946, 912, '', '图表详情', 'admin', '', '', 'marketing/integral/order/sheet_info', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(947, 912, '', '发货', 'admin', '', '', 'marketing/integral/order/delivery/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(948, 912, '', '配送信息表单', 'admin', '', '', 'marketing/integral/order/distribution/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(949, 912, '', '配送信息保存', 'admin', '', '', 'marketing/integral/order/distribution/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(950, 912, '', '订单详情', 'admin', '', '', 'marketing/integral/order/info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(951, 912, '', '订单记录', 'admin', '', '', 'marketing/integral/order/status/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(952, 912, '', '小票打印', 'admin', '', '', 'marketing/integral/order/print/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(953, 912, '', '物流查询', 'admin', '', '', 'marketing/integral/order/express/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(954, 912, '', '订单备注', 'admin', '', '', 'marketing/integral/order/remark/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(955, 912, '', '收货', 'admin', '', '', 'marketing/integral/order/take/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(960, 909, '', '抽奖列表', 'admin', '', '', 'marketing/lottery/list', 'GET', '[]', 0, 1, 0, 1, '', '27/909', 2, '', 0, '', 0),
(961, 909, '', '抽奖商品详情', 'admin', '', '', 'marketing/lottery/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(962, 909, '', '用户等级', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(963, 909, '', '会员等级', 'admin', '', '', 'user/user_level/vip_list', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(964, 909, '', '编辑保存', 'admin', '', '', 'marketing/lottery/edit/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(965, 27, '', '营销公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, 'admin-marketing', '27', 1, '', 0, '', 0),
(966, 965, '', '附件分类', 'admin', '', '', 'file/category', 'GET', '[]', 0, 0, 0, 1, '', '27/965', 2, '', 0, '', 0),
(967, 965, '', '附件列表', 'admin', '', '', 'file/file', 'GET', '[]', 0, 0, 0, 1, '', '27/965', 2, '', 0, '', 0),
(968, 909, '', '删除抽奖', 'admin', '', '', 'marketing/lottery/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(969, 909, '', '抽奖记录列表', 'admin', '', '', 'marketing/lottery/record/list/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(970, 909, '', '物流公司', 'admin', '', '', 'order/express_list', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(971, 909, '', '抽奖记录备注', 'admin', '', '', 'marketing/lottery/record/deliver', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(972, 909, '', '抽奖状态', 'admin', '', '', 'marketing/lottery/set_status/<id>/<status>', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(973, 909, '', '添加抽奖', 'admin', '', '', 'marketing/lottery/add', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(975, 28, '', '分销配置头部', 'admin', '', '', 'setting/config/header_basics', 'GET', '[]', 0, 0, 0, 1, '', '26/28', 2, '', 0, '', 0),
(976, 717, '', '查看商品', 'admin', '', '', 'product/product/<id>', 'GET', '[]', 0, 0, 0, 1, '', '716/717', 2, '', 0, '', 0),
(977, 657, '', '获取风格设置', 'admin', '', '', 'diy/get_color_change/<type>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(978, 657, '', '获取个人中心菜单', 'admin', '', '', 'diy/get_member', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(979, 657, '', '个人中心组件分类', 'admin', '', '', 'diy/get_page_category', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(980, 657, '', '个人中心组件树形分类', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(981, 657, '', '获取页面链接', 'admin', '', '', 'diy/get_page_link/<cate_id>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(982, 657, '', '商品分类页保存', 'admin', '', '', 'diy/color_change/<status>/<type>', 'PUT', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(983, 657, '', '个人中心页保存', 'admin', '', '', 'diy/member_save', 'POST', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(984, 128, '', '获取组合数据', 'admin', '', '', 'setting/group_data', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(985, 128, '', '获取头部', 'admin', '', '', 'setting/sign_data/header', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(986, 128, '', '保存配置', 'admin', '', '', 'setting/group_data/save_all', 'POST', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(987, 128, '', '客服页面广告', 'admin', '', '', 'setting/get_kf_adv', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(988, 898, '', '消息管理列表', 'admin', '', '', 'setting/notification/index', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(989, 898, '', '模板消息详情', 'admin', '', '', 'setting/notification/info', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(990, 898, '', '编辑保存', 'admin', '', '', 'setting/notification/save', 'POST', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(991, 898, '', '模板消息状态修改', 'admin', '', '', 'setting/notification/set_status/<type>/<status>/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(992, 898, '', '一键同步模版消息', 'admin', '', '', 'app/wechat/syncSubscribe', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(993, 135, '', '小程序', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/routine', '135', 1, '', 0, 'admin-routine', 0),
(994, 993, '', '小程序下载', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/routine/download', '135/993', 1, '', 0, 'routine-download', 0),
(995, 994, '', '下载小程序页面数据', 'admin', '', '', 'app/routine/info', 'GET', '[]', 0, 0, 0, 1, '', '135/993/994', 2, '', 0, '', 0),
(996, 994, '', '下载小程序模版', 'admin', '', '', 'app/routine/download', 'POST', '[]', 0, 0, 0, 1, '', '135/993/994', 2, '', 0, '', 0),
(997, 716, '', '订单统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/order', '716', 1, '', 0, 'admin-statistic', 0),
(998, 37, '', '资金流水', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/capital_flow/index', '35/37', 1, '', 0, 'finance-capital_flow-index', 0),
(999, 37, '', '账单记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/billing_records/index', '35/37', 1, '', 0, 'finance-billing_records-index', 0),
(1000, 566, '', '富文本上传图片', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/widget.images/index.html', '25/566', 1, '', 0, 'admin-user-user-index', 0),
(1001, 34, '', '积分记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/point_record', '27/34', 1, '', 0, 'marketing-point_record-index', 0),
(1002, 34, '', '积分统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/point_statistic', '27/34', 1, '', 0, 'marketing-point_statistic-index', 0),
(1003, 35, '', '余额记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/balance', '35', 1, '', 0, 'finance-balance-index', 0),
(1004, 1003, '', '余额记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/balance/balance', '35/1003', 1, '', 0, 'finance-user-balance', 0),
(1005, 716, '', '余额统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/balance', '716', 1, '', 0, 'admin-statistic', 0),
(1006, 69, '', '公众号配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/wechat_config/3/2', '135/69', 1, '', 0, 'setting-system-config', 0),
(1007, 993, '', '小程序配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/routine_config/3/7', '135/993', 1, '', 0, 'setting-system-config', 0),
(1008, 135, '', 'PC端', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/pc', '135', 1, '', 0, 'admin-pc', 0),
(1009, 135, '', 'APP', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/app', '135', 1, '', 0, 'admin-app', 0),
(1010, 1008, '', 'PC端配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/pc_config/3/75', '135/1008', 1, '', 0, 'setting-system-config', 0),
(1011, 1009, '', 'APP配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/app_config/3/77', '135/1009', 1, '', 0, 'setting-system-config', 0),
(1012, 12, '', '存储配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/storage', '12', 1, '', 0, 'setting-storage', 0),
(1013, 26, '', '事业部', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/agent/division', '26', 1, '', 0, 'agent-division', 0),
(1014, 1013, '', '事业部列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/index', '26/1013', 1, '', 0, 'agent-division-index', 0),
(1015, 1013, '', '代理商列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/agent/index', '26/1013', 1, '', 0, 'agent-division-agent-index', 0),
(1016, 1013, '', '代理商申请', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/agent/applyList', '26/1013', 1, '', 0, 'agent-division-agent-applyList', 0),
(1017, 1013, '', '代理商规则', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/agent/agreement', '26/1013', 1, '', 0, 'agent-division-agent-agreement', 0),
(1018, 909, '', '抽奖配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/create', '27/909', 1, '', 0, 'admin-marketing-lottery-create', 0),
(1019, 909, '', '中奖记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/recording_list', '27/909', 1, '', 0, 'admin-marketing-lottery-recording_list-id', 0),
(1020, 1014, '', '事业部列表', 'admin', '', '', 'agent/division/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1021, 1014, '', '添加事业部', 'admin', '', '', 'agent/division/create/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1023, 27, '', '公众号渠道码', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/channel_code/channelCodeIndex', '27', 1, '', 0, 'marketing-channel_code-index', 0),
(1024, 1023, '', '添加公众号渠道码', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/channel_code/create', '27/1021', 1, '', 0, 'marketing-channel_code-create', 0),
(1025, 1023, '', '渠道码统计', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/channel_code/code_statistic', '27/1021', 1, '', 0, 'marketing-channel_code-statistic', 0),
(1026, 1014, '', '事业部下级列表', 'admin', '', '', '/agent/division/down_list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1027, 1014, '', '事业部保存', 'admin', '', '', 'agent/division/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1028, 1014, '', '事业部状态切换', 'admin', '', '', 'agent/division/set_status/<status>/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1029, 1014, '', '事业部删除', 'admin', '', '', 'division/del/<type>/<uid>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1030, 1015, '', '代理商列表', 'admin', '', '', 'agent/division/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1031, 1015, '', '代理商下级列表', 'admin', '', '', 'agent/division/down_list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1032, 1015, '', '添加代理商', 'admin', '', '', 'agent/division/agent/create/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1033, 1015, '', '代理商保存', 'admin', '', '', 'agent/division/agent/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1034, 1015, '', '代理商状态切换', 'admin', '', '', 'agent/division/set_status/<status>/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1035, 1015, '', '代理商删除', 'admin', '', '', 'agent/division/del/<type>/<uid>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1036, 1016, '', '代理商申请列表', 'admin', '', '', 'agent/division/agent_apply/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1037, 1016, '', '代理商审核', 'admin', '', '', 'agent/division/examine_apply/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1038, 1016, '', '提交审核', 'admin', '', '', 'agent/division/apply_agent/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1039, 1016, '', '删除审核', 'admin', '', '', 'agent/division/del_apply/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1040, 1017, '', '协议详情', 'admin', '', '', 'agent/division/agent_agreement/info', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1017', 2, '', 0, '', 0),
(1041, 1017, '', '保存协议', 'admin', '', '', 'agent/division/agent_agreement/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1017', 2, '', 0, '', 0),
(1042, 1023, '', '渠道码分类列表', 'admin', '', '', 'app/wechat_qrcode/cate/list', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1043, 1023, '', '渠道码分类添加编辑表单', 'admin', '', '', 'app/wechat_qrcode/cate/create/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1044, 1023, '', '渠道码分类保存', 'admin', '', '', 'app/wechat_qrcode/cate/save', 'POST', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1045, 1023, '', '渠道码分类删除', 'admin', '', '', 'app/wechat_qrcode/cate/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1046, 1023, '', '保存渠道码', 'admin', '', '', 'app/wechat_qrcode/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1047, 1023, '', '渠道码详情', 'admin', '', '', 'app/wechat_qrcode/info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1048, 1023, '', '渠道码列表', 'admin', '', '', 'app/wechat_qrcode/list', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1049, 1023, '', '删除渠道码', 'admin', '', '', 'app/wechat_qrcode/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1050, 1023, '', '渠道码状态切换', 'admin', '', '', 'app/wechat_qrcode/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1051, 1023, '', '渠道码用户列表', 'admin', '', '', 'app/wechat_qrcode/user_list/<qid>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1052, 1023, '', '获取用户标签', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1053, 27, '', '充值配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/recharge', '27', 1, '', 0, 'marketing-recharge-index', 0),
(1054, 1009, '', '隐私协议', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/app/agreement', '135/1009', 1, '', 0, 'admin-app-agreement', 0),
(1055, 1009, '', '版本管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/app/version', '135/1009', 1, '', 0, 'admin-app-version', 0)
SQL
            ],
            [
                'code' => 440,
                'type' => 7,
                'table' => "system_notification",
                'whereTable' => "",
                'findSql' => "select id from @table where `mark` = 'oreder_takever'",
                'whereSql' => "",
                'sql' => "UPDATE `@table` SET `mark` = 'order_take' WHERE `mark` = 'oreder_takever';"
            ],
            [
                'code' => 440,
                'type' => 1,
                'table' => "system_storage",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_key` varchar(100) NOT NULL DEFAULT '' COMMENT 'access_key',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=本地存储,2=七牛,3=oss,4=cos',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '空间名',
  `region` varchar(100) NOT NULL DEFAULT '' COMMENT '地域',
  `acl` enum('private','public-read','public-read-write') NOT NULL DEFAULT 'public-read' COMMENT '权限',
  `domain` varchar(100) NOT NULL DEFAULT '' COMMENT '空间域名',
  `cname` varchar(255) NOT NULL DEFAULT '' COMMENT 'CNAME值',
  `is_ssl` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=http,1=https',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `add_time` int(10) NOT NULL COMMENT '添加事件',
  `update_time` int(10) NOT NULL COMMENT '更新事件',
  PRIMARY KEY (`id`),
  KEY `is_delete` (`is_delete`),
  KEY `status` (`status`),
  KEY `access_key` (`access_key`,`type`,`is_delete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='云储存';"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "division_type",
                'findSql' => "show columns from `@table` like 'division_type'",
                'sql' => "ALTER TABLE `@table` ADD `division_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '代理类型：0普通，1事业部，2代理，3员工'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "division_status",
                'findSql' => "show columns from `@table` like 'division_status'",
                'sql' => "ALTER TABLE `@table` ADD `division_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '代理状态'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "is_division",
                'findSql' => "show columns from `@table` like 'is_division'",
                'sql' => "ALTER TABLE `@table` ADD `is_division` tinyint(1) NOT NULL DEFAULT '0' COMMENT '事业部状态'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "is_agent",
                'findSql' => "show columns from `@table` like 'is_agent'",
                'sql' => "ALTER TABLE `@table` ADD `is_agent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '代理状态'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "is_staff",
                'findSql' => "show columns from `@table` like 'is_staff'",
                'sql' => "ALTER TABLE `@table` ADD `is_staff` tinyint(1) NOT NULL DEFAULT '0' COMMENT '员工状态'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "division_id",
                'findSql' => "show columns from `@table` like 'division_id'",
                'sql' => "ALTER TABLE `@table` ADD `division_id` int(11) NOT NULL DEFAULT '0' COMMENT '事业部id'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "agent_id",
                'findSql' => "show columns from `@table` like 'agent_id'",
                'sql' => "ALTER TABLE `@table` ADD `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理商id'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "staff_id",
                'findSql' => "show columns from `@table` like 'staff_id'",
                'sql' => "ALTER TABLE `@table` ADD `staff_id` int(11) NOT NULL DEFAULT '0' COMMENT '员工id'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "division_percent",
                'findSql' => "show columns from `@table` like 'division_percent'",
                'sql' => "ALTER TABLE `@table` ADD `division_percent` int(11) NOT NULL DEFAULT '0' COMMENT '分佣比例'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "division_change_time",
                'findSql' => "show columns from `@table` like 'division_change_time'",
                'sql' => "ALTER TABLE `@table` ADD `division_change_time` int(11) NOT NULL DEFAULT '0' COMMENT '事业部/代理/员工修改时间'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "division_end_time",
                'findSql' => "show columns from `@table` like 'division_end_time'",
                'sql' => "ALTER TABLE `@table` ADD `division_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '事业部/代理/员工结束时间'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user",
                'field' => "division_invite",
                'findSql' => "show columns from `@table` like 'division_invite'",
                'sql' => "ALTER TABLE `@table` ADD `division_invite` int(11) NOT NULL DEFAULT '0' COMMENT '代理商邀请码'"
            ],
            [
                'code' => 440,
                'type' => 3,
                'table' => "user_bill",
                'field' => "frozen_time",
                'findSql' => "show columns from `@table` like 'frozen_time'",
                'sql' => "ALTER TABLE `@table` ADD `frozen_time` int(11) NOT NULL DEFAULT '0' COMMENT '积分冻结时间'"
            ],
            [
                'code' => 440,
                'type' => 1,
                'table' => "user_brokerage",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户佣金id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户uid',
  `link_id` varchar(32) NOT NULL DEFAULT '0' COMMENT '关联id',
  `type` varchar(64) NOT NULL DEFAULT '' COMMENT '明细类型',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '账单标题',
  `number` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '明细数字',
  `balance` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '剩余',
  `pm` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = 支出 1 = 获得',
  `mark` varchar(512) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = 带确定 1 = 有效 -1 = 无效',
  `take` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = 未收货 1 = 已收货',
  `frozen_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '冻结到期时间',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE,
  KEY `pm` (`pm`) USING BTREE,
  KEY `type` (`type`,`link_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户分佣账单表';"
            ],
            [
                'code' => 440,
                'type' => 1,
                'table' => "user_money",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户余额id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户uid',
  `link_id` varchar(32) NOT NULL DEFAULT '0' COMMENT '关联id',
  `type` varchar(64) NOT NULL DEFAULT '' COMMENT '明细类型',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '账单标题',
  `number` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '明细数字',
  `balance` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '剩余',
  `pm` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = 支出 1 = 获得',
  `mark` varchar(512) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = 带确定 1 = 有效 -1 = 无效',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE,
  KEY `pm` (`pm`) USING BTREE,
  KEY `type` (`type`,`link_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户余额账单表';"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "wechat_qrcode",
                'sql' => "DROP TABLE IF EXISTS `@table`"
            ],
            [
                'code' => 440,
                'type' => 1,
                'table' => "wechat_qrcode",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '二维码名称',
  `image` varchar(500) NOT NULL DEFAULT '' COMMENT '二维码图片',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `label_id` varchar(32) NOT NULL DEFAULT '' COMMENT '标签id',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '回复类型',
  `content` text NOT NULL COMMENT '回复内容',
  `data` text NOT NULL COMMENT '发送数据',
  `follow` int(11) NOT NULL DEFAULT '0' COMMENT '关注人数',
  `scan` int(11) NOT NULL DEFAULT '0' COMMENT '扫码人数',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `continue_time` int(11) NOT NULL DEFAULT '0' COMMENT '有效期',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '到期时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
            ],
            [
                'code' => 440,
                'type' => 1,
                'table' => "wechat_qrcode_cate",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `cate_name` varchar(255) NOT NULL DEFAULT '' COMMENT '渠道码分类名称',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
            ],
            [
                'code' => 440,
                'type' => 1,
                'table' => "wechat_qrcode_record",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL DEFAULT '0' COMMENT '渠道码id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `is_follow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否关注',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '扫码时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='渠道码扫码记录表';"
            ],
            [
                'code' => 440,
                'type' => -1,
                'table' => "wechat_user",
                'sql' => "ALTER TABLE `@table` MODIFY COLUMN `openid` VARCHAR(255)"
            ],
            [
                'code' => 441,
                'type' => 3,
                'table' => "store_product_attr_result",
                'field' => "id",
                'findSql' => "show columns from `@table` like 'id'",
                'sql' => "ALTER TABLE `@table` ADD `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT '主键id' FIRST, ADD PRIMARY KEY (`id`)"
            ],
            [
                'code' => 441,
                'type' => 3,
                'table' => "store_product_attr_value",
                'field' => "id",
                'findSql' => "show columns from `@table` like 'id'",
                'sql' => "ALTER TABLE `@table` ADD `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT '主键id' FIRST, ADD PRIMARY KEY (`id`)"
            ],
            [
                'code' => 441,
                'type' => 3,
                'table' => "diy",
                'field' => "title",
                'findSql' => "show columns from `@table` like 'title'",
                'sql' => "ALTER TABLE `@table` ADD `title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'diy顶部title'"
            ],
            [
                'code' => 441,
                'type' => -1,
                'table' => "wechat_user",
                'sql' => "ALTER TABLE `@table` MODIFY COLUMN `openid` VARCHAR(255)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'storage_config'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='system_serve'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '存储配置', 'storage_config', 0, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'qiniu_config'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='storage_config'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '七牛云配置', 'qiniu_config', 0, 0, '', 0, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'oss_config'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='storage_config'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '阿里云配置', 'oss_config', 0, 0, '', 0, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'cos_config'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='storage_config'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '腾讯云配置', 'cos_config', 0, 0, '', 0, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'print_basic'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='printing_deploy'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '基础配置', 'print_basic', 1, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'yly_config'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='printing_deploy'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '易联云配置', 'yly_config', 1, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'copy_basic'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='copy_product'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '基础配置', 'copy_basic', 1, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = '99api_config'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='copy_product'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '99api配置', '99api_config', 1, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'logistics_basic'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='logistics_select'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '基础配置', 'logistics_basic', 1, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'logistics_aliyun'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='logistics_select'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '阿里云配置', 'logistics_aliyun', 1, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'electronic_basic'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='electronic_sheet'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '基础配置', 'electronic_basic', 1, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'system_electronic_config'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='electronic_sheet'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '一号通配置', 'system_electronic_config', 1, 0, '', 3, 0)"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'upload_set'",
                'whereSql' => "",
                'sql' => "delete from @table where `eng_title` = 'upload_set'"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'system_sms'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='system_serve'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId' WHERE `eng_title` = 'system_sms';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'printing_deploy'",
                'whereSql' => "",
                'sql' => "UPDATE `@table` SET `type` = 3 WHERE `eng_title` = 'printing_deploy';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'base_config'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='storage_config'",
                'sql' => "UPDATE `@table` SET `pid` = '@tabId',`title` = '基础配置' WHERE `eng_title` = 'base_config';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'copy_product'",
                'whereSql' => "",
                'sql' => "UPDATE `@table` SET `type` = 3 WHERE `eng_title` = 'copy_product';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'logistics_select'",
                'whereSql' => "",
                'sql' => "UPDATE `@table` SET `title` = '物流查询配置',`type` = 3 WHERE `eng_title` = 'logistics_select';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'system_serve'",
                'whereSql' => "",
                'sql' => "UPDATE `@table` SET `type` = 3 WHERE `eng_title` = 'system_serve';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'electronic_sheet'",
                'whereSql' => "",
                'sql' => "UPDATE `@table` SET `title` = '电子面单配置',`type` = 3 WHERE `eng_title` = 'electronic_sheet';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config_tab",
                'whereTable' => "",
                'findSql' => "select id from @table where `eng_title` = 'map'",
                'whereSql' => "",
                'sql' => "delete from @table where `eng_title` = 'map'"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'system_express_app_code'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='logistics_aliyun'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'system_express_app_code';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'tengxun_map_key'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='web_site'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'tengxun_map_key';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'pay_success_printing_switch'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='print_basic'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'pay_success_printing_switch';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'develop_id'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='yly_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'develop_id';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'printing_api_key'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='yly_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'printing_api_key';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'printing_client_id'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='yly_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'printing_client_id';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'terminal_number'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='yly_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'terminal_number';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'qiniu_accessKey'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='qiniu_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'qiniu_accessKey';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'qiniu_secretKey'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='qiniu_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'qiniu_secretKey';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'tengxun_accessKey'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='cos_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'tengxun_accessKey';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'tengxun_secretKey'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='cos_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'tengxun_secretKey';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'copy_product_apikey'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='99api_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'copy_product_apikey';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'logistics_type'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='logistics_basic'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'logistics_type';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'system_product_copy_type'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='copy_basic'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'system_product_copy_type';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'config_export_id'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='system_electronic_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'config_export_id';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'config_export_temp_id'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='system_electronic_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'config_export_temp_id';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'config_export_to_name'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='system_electronic_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'config_export_to_name';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'config_export_to_tel'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='system_electronic_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'config_export_to_tel';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'config_export_to_address'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='system_electronic_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'config_export_to_address';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'config_export_siid'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='system_electronic_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'config_export_siid';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'config_export_open'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='electronic_basic'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'config_export_open';"
            ],
            [
                'code' => 442,
                'type' => 7,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'tengxun_appid'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='cos_config'",
                'sql' => "UPDATE `@table` SET `config_tab_id` = @tabId WHERE `menu_name` = 'tengxun_appid';"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'print_type'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='print_basic'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 'print_type', 'radio', 'input', @tabId, '1=>易联云', 1, '', 0, 0, '\"1\"', '平台选择', '平台选择', 0, 1)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'config_export_type'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='electronic_basic'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 'config_export_type', 'radio', 'input', @tabId, '1=>一号通', 1, '', 0, 0, '\"1\"', '电子面单类型', '电子面单类型', 0, 1)"
            ],
            [
                'code' => 442,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'customer_corpId'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='kefu_config'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 'customer_corpId', 'text', 'input', @tabId, '', 1, '', 100, 0, '\"\"', '企业ID', '小程序需要跳转企业微信客服的话需要配置此项', 0, 1)"
            ],
            [
                'code' => 442,
                'type' => -1,
                'table' => "system_menus",
                'sql' => "truncate table `@table`"
            ],
            [
                'code' => 442,
                'type' => -1,
                'table' => "system_menus",
                'sql' => <<<SQL
INSERT INTO `eb_system_menus` (`id`, `pid`, `icon`, `menu_name`, `module`, `controller`, `action`, `api_url`, `methods`, `params`, `sort`, `is_show`, `is_show_path`, `access`, `menu_path`, `path`, `auth_type`, `header`, `is_header`, `unique_auth`, `is_del`) VALUES
(1, 0, 'md-basket', '商品', 'admin', 'product', 'index', '', '', '[]', 126, 1, 0, 1, '/admin/product', '', 1, '0', 1, 'admin-product', 0),
(2, 1, '', '商品管理', 'admin', 'product.product', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_list', '', 1, '', 0, 'admin-store-storeProuduct-index', 0),
(3, 1, '', '商品分类', 'admin', 'product.storeCategory', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_classify', '', 1, 'product', 0, 'admin-store-storeCategory-index', 0),
(4, 0, 'md-cart', '订单', 'admin', 'order', 'index', '', '', '[]', 110, 1, 0, 1, '/admin/order', '', 1, 'home', 1, 'admin-order', 0),
(5, 4, '', '订单管理', 'admin', 'order.store_order', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/order/list', '', 1, 'order', 0, 'admin-order-storeOrder-index', 0),
(6, 1, '', '商品评论', 'admin', 'store.store_product_reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/product/product_reply', '', 1, 'product', 0, 'product-product-reply', 0),
(7, 0, 'md-home', '主页', 'admin', 'index', '', '', '', '[]', 127, 1, 0, 1, '/admin/home/', '', 1, 'home', 1, 'admin-index-index', 0),
(9, 0, 'md-person', '用户', 'admin', 'user.user', '', '', '', '[]', 100, 1, 0, 1, '/admin/user', '', 1, 'user', 1, 'admin-user', 0),
(10, 9, '', '用户管理', 'admin', 'user.user', 'index', '', '', '[]', 10, 1, 0, 1, '/admin/user/list', '', 1, 'user', 1, 'admin-user-user-index', 0),
(11, 9, '', '用户等级', 'admin', 'user.user_level', 'index', '', '', '[]', 7, 1, 0, 1, '/admin/user/level', '', 1, 'user', 1, 'user-user-level', 0),
(12, 0, 'md-settings', '设置', 'admin', 'setting.system_config', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting', '', 1, 'setting', 1, 'admin-setting', 0),
(14, 12, '', '管理权限', 'admin', 'setting.system_admin', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/auth/list', '', 1, 'setting', 1, 'setting-system-admin', 0),
(19, 14, '', '角色管理', 'admin', 'setting.system_role', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_role/index', '', 1, 'setting', 1, 'setting-system-role', 0),
(20, 14, '', '管理员列表', 'admin', 'setting.system_admin', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_admin/index', '', 1, 'setting', 0, 'setting-system-list', 0),
(21, 14, '', '权限规则', 'admin', 'setting.system_menus', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_menus/index', '', 1, 'setting', 0, 'setting-system-menus', 0),
(22, 1, '', '产品添加', 'admin', 'store.store_product', 'save', '', '', '[]', 1, 1, 1, 1, '/admin/product/add_product', '', 1, 'product', 1, 'product-product-save', 0),
(23, 12, '', '系统设置', 'admin', 'setting.system_config', 'index', '', '', '[]', 10, 1, 0, 1, '/admin/setting/system_config', '', 1, 'setting', 1, 'setting-system-config', 0),
(25, 0, 'md-hammer', '维护', 'admin', 'system', '', '', '', '[]', -1, 1, 0, 1, '/admin/system', '', 1, 'setting', 1, 'admin-system', 0),
(26, 0, 'ios-people', '分销', 'admin', 'agent', '', '', '', '[]', 90, 1, 0, 1, '/admin/agent', '', 1, 'user', 1, 'admin-agent', 0),
(27, 0, 'ios-paper-plane', '营销', 'admin', 'marketing', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing', '', 1, 'home', 1, 'admin-marketing', 0),
(28, 26, '', '分销设置', 'admin', 'setting.system_config', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_config_retail/2/9', '', 1, 'setting', 0, 'setting-system-config', 0),
(29, 26, '', '分销员管理', 'admin', 'agent.agent_manage', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/agent/agent_manage/index', '', 1, 'user', 0, 'agent-agent-manage', 0),
(30, 27, '', '优惠券', 'admin', 'marketing.store_coupon', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_coupon', '', 1, 'marketing', 1, 'marketing-store_coupon-index', 0),
(31, 27, '', '砍价管理', 'admin', 'marketing.store_bargain', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_bargain', '', 1, 'marketing', 0, 'marketing-store_bargain-index', 0),
(32, 27, '', '拼团管理', 'admin', 'marketing.store_combination', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_combination', '', 1, 'marketing', 0, 'marketing-store_combination-index', 0),
(33, 27, '', '秒杀管理', 'admin', 'marketing.store_seckill', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_seckill', '', 1, 'marketing', 0, 'marketing-store_seckill-index', 0),
(34, 27, '', '积分管理', 'admin', 'marketing.user_point', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/user_point', '', 1, 'marketing', 1, 'marketing-user_point-index', 0),
(35, 0, 'logo-usd', '财务', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance', '', 1, 'home', 0, 'admin-finance', 0),
(36, 35, '', '财务操作', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_extract', '', 1, 'finance', 0, 'finance-user_extract-index', 0),
(37, 35, '', '财务记录', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_recharge', '', 1, 'finance', 0, 'finance-user-recharge-index', 0),
(38, 35, '', '佣金记录', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/finance', '', 1, 'finance', 0, 'finance-finance-index', 0),
(39, 36, '', '提现申请', 'admin', 'finance.user_extract', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_extract/index', '', 1, 'finance', 0, 'finance-user_extract', 0),
(40, 37, '', '充值记录', 'admin', 'finance.user_recharge', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_recharge/index', '', 1, 'finance', 0, 'finance-user-recharge', 0),
(42, 38, '', '佣金记录', 'admin', 'finance.finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/finance/commission', '', 1, 'finance', 0, 'finance-finance-commission', 0),
(43, 0, 'ios-book', '内容', 'admin', 'cms', '', '', '', '[]', 1, 1, 0, 1, '/admin/cms', '', 1, 'home', 0, 'admin-cms', 0),
(44, 43, '', '文章管理', 'admin', 'cms.article', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/cms/article/index', '', 1, 'cms', 0, 'cms-article-index', 0),
(45, 43, '', '文章分类', 'admin', 'cms.article_category', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/cms/article_category/index', '', 1, 'cms', 0, 'cms-article-category', 0),
(46, 43, '', '文章添加', 'admin', 'cms.article', 'add_article', '', '', '[]', 0, 1, 1, 1, '/admin/cms/article/add_article', '', 1, 'cms', 1, 'cms-article-creat', 0),
(47, 65, '', '系统日志', 'admin', 'system.system_log', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_log/index', '', 1, 'system', 0, 'system-maintain-system-log', 0),
(48, 7, '', '控制台', 'admin', 'index', 'index', '', '', '[]', 127, 1, 0, 1, '/admin/home/index', '', 1, 'home', 0, '', 1),
(56, 25, '', '开发配置', 'admin', 'system', '', '', '', '[]', 10, 1, 0, 1, '/admin/system/config', '', 1, 'system', 1, 'system-config-index', 0),
(57, 65, '', '刷新缓存', 'admin', 'system', 'clear', '', '', '[]', 1, 1, 0, 1, '/admin/system/maintain/clear/index', '', 1, 'system', 1, 'system-clear', 0),
(64, 65, '', '文件校验', 'admin', 'system.system_file', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_file/index', '', 1, 'system', 0, 'system-maintain-system-file', 0),
(65, 25, '', '安全维护', 'admin', 'system', '', '', '', '[]', 7, 1, 0, 1, '/admin/system/maintain', '', 1, 'system', 1, 'system-maintain-index', 0),
(66, 65, '', '清除数据', 'admin', 'system.system_cleardata', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_cleardata/index', '', 1, 'system', 0, 'system-maintain-system-cleardata', 0),
(67, 65, '', '数据备份', 'admin', 'system.system_databackup', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_databackup/index', '', 1, 'system', 0, 'system-maintain-system-databackup', 0),
(69, 135, '', '公众号', 'admin', 'wechat', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat', '', 1, 'app', 1, 'admin-wechat', 0),
(70, 30, '', '优惠券模板', 'admin', 'marketing.store_coupon', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/index', '', 1, 'marketing', 1, 'marketing-store_coupon', 0),
(71, 30, '', '优惠券列表', 'admin', 'marketing.store_coupon_issue', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_coupon_issue/index', '', 1, 'marketing', 1, 'marketing-store_coupon_issue', 0),
(72, 30, '', '用户领取记录', 'admin', 'marketing.store_coupon_user', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_coupon_user/index', '', 1, 'marketing', 1, 'marketing-store_coupon_user', 0),
(74, 31, '', '砍价商品', 'admin', 'marketing.store_bargain', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_bargain/index', '', 1, 'marketing', 1, 'marketing-store_bargain', 0),
(75, 32, '', '拼团商品', 'admin', 'marketing.store_combination', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/index', '', 1, 'marketing', 1, 'marketing-store_combination', 0),
(76, 32, '', '拼团列表', 'admin', 'marketing.store_combination', 'combina_list', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/combina_list', '', 1, 'marketing', 0, 'marketing-store_combination-combina_list', 0),
(77, 33, '', '秒杀商品', 'admin', 'marketing.store_seckill', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_seckill/index', '', 1, 'marketing', 1, 'marketing-store_seckill', 0),
(78, 33, '', '秒杀配置', 'admin', 'marketing.store_seckill', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_seckill_data/index/49', '', 1, 'marketing', 1, 'marketing-store_seckill-data', 0),
(79, 34, '', '积分配置', 'admin', 'setting.system_config/index.html', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/integral/system_config/3/11', '', 1, 'marketing', 1, 'marketing-integral-system_config', 0),
(90, 32, '', '拼团添加', 'admin', 'marketing.store_combination', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/add_commodity/:id', '', 1, 'marketing', 0, '', 1),
(91, 69, '', '公众号配置', 'admin', 'application.wechat', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/setting', '', 1, 'app', 0, '', 1),
(92, 69, '', '微信菜单', 'admin', 'application.wechat_menus', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/setting/menus/index', '', 1, 'app', 0, 'application-wechat-menus', 0),
(94, 12, '', '一号通', 'admin', 'setting.sms_config', '', '', '', '[]', 8, 1, 0, 1, '/admin/setting/sms/sms_config/index', '', 1, 'setting', 1, 'setting-sms', 0),
(95, 94, '', '账户管理', 'admin', 'sms.sms_config', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_config/index', '', 1, 'setting', 1, 'setting-sms-sms-config', 0),
(96, 94, '', '短信模板', 'admin', 'sms.sms_template_apply', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_template_apply/index', '', 1, 'setting', 0, 'setting-sms-config-template', 0),
(97, 94, '', '套餐购买', 'admin', 'sms.sms_pay', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_pay/index', '', 1, 'setting', 1, 'setting-sms-sms-template', 0),
(99, 1, '', '商品规格', 'admin', 'store.store_product', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_attr', '', 1, 'product', 1, 'product-product-attr', 0),
(105, 22, '', '添加产品保存', 'admin', 'store.store_product', 'save', 'product/product/<id>', 'POST', '[]', 0, 0, 0, 1, '/admin/product/save', '', 2, 'product', 0, 'product-save', 0),
(108, 2, '', '产品列表', 'admin', 'product.product', 'index', 'product/product', 'GET', '[]', 20, 0, 0, 1, '/admin/product/product', '1/2', 2, 'product', 1, 'product-product-index', 0),
(109, 69, '', '图文管理', 'admin', 'wechat.wechat_news_category', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/news_category/index', '', 1, 'app', 0, 'wechat-wechat-news-category-index', 0),
(110, 69, '', '图文添加', 'admin', 'wechat.wechat_news_category', 'save', '', '', '[]', 0, 1, 1, 1, '/admin/app/wechat/news_category/save', '', 1, 'app', 1, 'wechat-wechat-news-category-save', 0),
(111, 56, '', '配置分类', 'admin', 'setting.system_config_tab', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/config/system_config_tab/index', '', 1, 'system', 0, 'system-config-system_config-tab', 0),
(112, 56, '', '组合数据', 'admin', 'setting.system_group', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/config/system_group/index', '', 1, 'system', 0, 'system-config-system_config-group', 0),
(113, 114, '', '微信关注回复', 'admin', 'wechat.reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/follow/subscribe', '', 1, 'app', 0, 'wechat-wechat-reply-subscribe', 0),
(114, 69, '', '自动回复', 'admin', 'wechat.reply', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply', '', 1, 'app', 0, 'wechat-wechat-reply-index', 0),
(115, 114, '', '关键字回复', 'admin', 'wechat.reply', 'keyword', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/keyword', '', 1, 'app', 0, 'wechat-wechat-reply-keyword', 0),
(116, 114, '', '无效关键词回复', 'admin', 'wechat.reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/index/default', '', 1, 'app', 0, 'wechat-wechat-reply-default', 0),
(125, 56, '', '配置列表', 'admin', 'system.config', 'index', '', '', '[]', 0, 0, 1, 1, '/admin/system/config/system_config_tab/list', '', 1, 'system', 1, 'system-config-system_config_tab-list', 0),
(126, 56, '', '组合数据列表', 'admin', 'system.system_group', 'list', '', '', '[]', 0, 1, 1, 1, '/admin/system/config/system_group/list', '', 1, 'system', 1, 'system-config-system_config-list', 0),
(128, 656, '', '数据配置', 'admin', 'setting.system_group_data', 'index', '', '', '[]', 2, 1, 0, 1, '/admin/setting/system_visualization_data', '12/656', 1, 'system', 1, 'admin-setting-system_visualization_data', 0),
(134, 114, '', '关键字添加', 'admin', '', 'index', '', '', '[]', 0, 1, 1, 1, '/admin/app/wechat/reply/keyword/save', '', 1, 'app', 1, 'wechat-wechat-reply-save', 0),
(135, 0, 'md-cube', '应用', 'admin', 'app', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app', '', 1, 'app', 1, 'admin-app', 0),
(144, 303, '', '提货点设置', 'admin', 'merchant.system_store', 'index', '', '', '[]', 5, 1, 0, 1, '/admin/setting/merchant/system_store/index', '', 1, '', 0, 'setting-system-config-merchant', 0),
(145, 25, '', '物流公司', 'admin', 'freight.express', 'index', '', '', '[]', 4, 1, 0, 1, '/admin/setting/freight/express/index', '', 1, '', 0, 'setting-freight-express', 0),
(146, 31, '', '添加砍价', 'admin', '/marketing.store_bargain', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_bargain/create', '', 1, '', 0, 'marketing-store_bargain-create', 0),
(147, 32, '', '添加拼团', 'admin', 'marketing.store_combination', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_combination/create', '', 1, '', 0, 'marketing-store_combination-create', 0),
(148, 33, '', '添加秒杀', 'admin', 'marketing.store_seckill', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_seckill/create', '', 1, '', 0, 'marketing-store_seckill-create', 0),
(154, 34, '', '签到配置', 'admin', 'setting.system_group_data', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/sign', '', 1, '', 0, 'marketing-sign-index', 0),
(165, 0, 'md-chatboxes', '客服', 'admin', 'setting.storeService', 'index', '', '', '[]', 2, 1, 0, 1, '/admin/kefu', '', 1, '', 0, 'setting-store-service', 0),
(166, 25, '', '日志', 'admin', '', '', '', '', '[]', 0, 1, 1, 1, '/admin/system/log', '', 1, '', 0, 'system-log', 0),
(169, 577, '', '商品删除', 'admin', 'product', '商品删除', 'product/product/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '0', 1, '', 0),
(170, 3, '', '分类列表', 'admin', '', '', 'product/category', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/category', '', 2, '', 0, '', 0),
(171, 578, '', '删除分类', 'admin', '', '', 'product/category/<id>', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/category/<id>', '', 2, '', 0, '', 0),
(172, 578, '', '修改分类', 'admin', '', '', 'product/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/<id>', '', 2, '', 0, '', 0),
(173, 578, '', '新增分类', 'admin', '', '', 'product/category', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/category', '', 2, '', 0, 'product-save-cate', 0),
(174, 578, '', '分类状态', 'admin', '', '', 'product/category/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/set_show/<id>/<is_show>', '', 2, '', 0, '', 0),
(175, 578, '', '快速编辑', 'admin', '', '', 'product/category/set_category/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/set_category/<id>', '', 2, '', 0, '', 0),
(176, 578, '', '分类表单添加', 'admin', '', '', 'product/category/create', 'GET', '[]', 0, 0, 0, 1, '/admincategory/create', '', 2, '', 0, '', 0),
(177, 578, '', '分类表单编辑', 'admin', '', '', 'product/category/<id>', 'GET', '[]', 0, 0, 0, 1, '/admincategory/<id>/edit', '', 2, '', 0, '', 0),
(178, 3, '', '分类树形列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '/admincategory/tree/:type', '', 2, '', 0, '', 0),
(179, 577, '', '产品状态', 'admin', '', '', 'product/product/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/set_show/<id>/<is_show>', '', 2, '', 0, '', 0),
(180, 577, '', '快速编辑', 'admin', '', '', 'product/product/set_product/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/product/set_product/<id>', '', 2, '', 0, '', 0),
(181, 577, '', '批量上架商品', 'admin', '', '', 'product/product/product_show', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/product/product_show', '', 2, '', 0, 'product-product-product_show', 0),
(182, 577, '', '采集商品', 'admin', '', '', 'product/copy', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/crawl', '', 2, '', 0, 'product-crawl-save', 0),
(183, 577, '', '采集商品保存', 'admin', '', '', 'product/crawl/save', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/crawl/save', '', 2, '', 0, '', 0),
(184, 579, '', '虚拟评论表单', 'admin', '', '', 'product/reply/fictitious_reply/<product_id>', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/reply/fictitious_reply', '', 2, '', 0, '', 0),
(185, 579, '', '保存虚拟评论', 'admin', '', '', 'product/reply/save_fictitious_reply', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/reply/save_fictitious_reply', '', 2, '', 0, 'product-reply-save_fictitious_reply', 0),
(186, 22, '', '获取属性模板列表', 'admin', '', '', 'product/product/get_rule', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/get_rule', '', 2, '', 0, '', 0),
(187, 22, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/get_template', '', 2, '', 0, '', 0),
(188, 579, '', '删除评论', 'admin', '', '', 'product/reply/<id>', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/reply/<id>', '', 2, '', 0, '', 0),
(189, 579, '', '评论回复', 'admin', '', '', 'product/reply/set_reply/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminreply/set_reply/<id>', '', 2, '', 0, '', 0),
(190, 6, '', '评论列表', 'admin', '', '', 'product/reply', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/reply', '', 2, '', 0, '', 0),
(191, 22, '', '生成属性', 'admin', '', '', 'product/generate_attr/<id>/<type>', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/generate_attr/<id>', '', 2, '', 0, '', 0),
(192, 2, '', '商品列表头部', 'admin', '', '', 'product/product/type_header', 'GET', '[]', 10, 0, 0, 1, '/adminproduct/product/type_header', '', 2, '', 0, '', 0),
(193, 577, '', '商品列表插件', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/list', '', 2, '', 0, '', 0),
(194, 99, '', '属性规则列表', 'admin', '', '', 'product/product/rule', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/rule', '', 2, '', 0, '', 0),
(195, 580, '', '保存修改规则', 'admin', '', '', 'product/product/rule/<id>', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/rule/<id>', '', 2, '', 0, 'product-rule-save', 0),
(196, 580, '', '规则详情', 'admin', '', '', 'product/product/rule/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/rule/<id>', '', 2, '', 0, '', 0),
(197, 580, '', '删除规则', 'admin', '', '', 'product/product/rule/delete', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/product/rule/delete', '', 2, '', 0, 'product-product-rule-delete', 0),
(198, 5, '', '订单列表', 'admin', '', '', 'order/list', 'GET', '[]', 0, 0, 0, 1, '/adminorder/list', '', 2, '', 0, '', 0),
(199, 5, '', '订单数据', 'admin', '', '', 'order/chart', 'GET', '[]', 0, 0, 0, 1, '/adminorder/chart', '', 2, '', 0, '', 0),
(200, 581, '', '订单核销', 'admin', '', '', 'order/write', 'POST', '[]', 0, 0, 0, 1, '/adminorder/write', '', 2, '', 0, 'order-write', 0),
(201, 215, '', '订单修改表格', 'admin', '', '', 'order/edit/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/edit/<id>', '', 2, '', 0, '', 0),
(202, 215, '', '订单修改', 'admin', '', '', 'order/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/update/<id>', '', 2, '', 0, '', 0),
(203, 581, '', '订单收货', 'admin', '', '', 'order/take/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/take/<id>', '', 2, '', 0, '', 0),
(204, 209, '', '订单发货', 'admin', '', '', 'order/delivery/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/delivery/<id>', '', 2, '', 0, '', 0),
(205, 214, '', '订单退款表格', 'admin', '', '', 'order/refund/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/refund/<id>', '', 2, '', 0, '', 0),
(206, 214, '', '订单退款', 'admin', '', '', 'order/refund/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/refund/<id>', '', 2, '', 0, '', 0),
(207, 581, '', '订单物流信息', 'admin', '', '', 'order/express/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/express/<id>', '', 2, '', 0, '', 0),
(208, 209, '', '物流公司列表', 'admin', '', '', 'order/express_list', 'GET', '[]', 0, 0, 0, 1, '/adminorder/express_list', '', 2, '', 0, '', 0),
(209, 581, '', '发货', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/delivery', '', 1, '', 0, '', 0),
(210, 767, '', '附加权限', 'admin', '', '', '', 'GET', '[]', 99, 1, 0, 1, '/adminorder/info/<id>', '', 2, '', 0, '', 0),
(211, 213, '', '订单配送表格', 'admin', '', '', 'order/distribution/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/distribution/<id>', '', 2, '', 0, '', 0),
(212, 213, '', '修改配送信息', 'admin', '', '', 'order/distribution/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/distribution/<id>', '', 2, '', 0, '', 0),
(213, 581, '', '订单配送', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/distribution', '', 1, '', 0, '', 0),
(214, 581, '', '退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/refund', '', 1, '', 0, '', 0),
(215, 581, '', '修改', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/update', '', 1, '', 0, '', 0),
(216, 581, '', '不退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/no_refund', '', 1, '', 0, '', 0),
(217, 216, '', '不退款表格', 'admin', '', '', 'order/no_refund/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/no_refund/<id>', '', 2, '', 0, '', 0),
(218, 216, '', '不退款理由修改', 'admin', '', '', 'order/no_refund/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/no_refund/<id>', '', 2, '', 0, '', 0),
(219, 581, '', '线下支付', 'admin', '', '', 'order/pay_offline/<id>', 'POST', '[]', 98, 0, 0, 1, '', '', 2, '', 0, '', 0),
(220, 581, '', '退积分', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/refund_integral', '', 1, '', 0, '', 0),
(221, 220, '', '退积分表单', 'admin', '', '', 'order/refund_integral/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(222, 220, '', '修改退积分', 'admin', '', '', 'order/refund_integral/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(223, 581, '', '订单备注', 'admin', '', '', 'order/remark/<id>', 'PUT', '[]', 97, 0, 0, 1, '', '', 2, '', 0, '', 0),
(224, 209, '', '获取电子面单信息', 'admin', '', '', 'order/express/temp', 'GET', '[]', 96, 0, 1, 1, '', '4/5/581/209', 2, '', 0, '', 0),
(225, 581, '', '订单删除', 'admin', '', '', 'order/del/<id>', 'DELETE', '[]', 95, 0, 0, 1, '', '', 2, '', 0, '', 0),
(226, 581, '', '批量删除订单', 'admin', '', '', 'order/dels', 'POST', '[]', 100, 0, 0, 1, '', '4/5/581', 2, '', 0, 'order-dels', 0),
(227, 9, '', '用户分组', 'admin', 'user.user_group', 'index', '', '', '[]', 9, 1, 0, 1, '/admin/user/group', '', 1, 'user', 1, 'user-user-group', 0),
(229, 25, '', '城市数据', 'admin', 'setting.system_city', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/freight/city/list', '', 1, 'setting', 1, 'setting-system-city', 0),
(230, 303, '', '运费模板', 'admin', 'setting.shipping_templates', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/freight/shipping_templates/list', '', 1, 'setting', 1, 'setting-shipping-templates', 0),
(231, 767, '', '发票列表', 'admin', '', '', 'order/invoice/list', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-index', 0),
(232, 585, '', '用户详情', 'admin', '', '', 'user/one_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(233, 585, '', '创建用户表单', 'admin', '', '', 'user/user/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(234, 585, '', '修改用户信息表单', 'admin', '', '', 'user/user/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(235, 585, '', '获取用户信息', 'admin', '', '', 'user/user/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(236, 585, '', '修改用户信息', 'admin', '', '', 'user/user/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(238, 585, '', '发送优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/coupon', '', 1, '', 0, 'admin-user-coupon', 0),
(239, 238, '', '优惠卷列表', 'admin', '', '', 'marketing/coupon/grant', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(240, 238, '', '发送优惠卷', 'admin', '', '', 'marketing/coupon/user/grant', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(241, 585, '', '发送图文', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/wechat/news/', '', 1, '', 0, 'admin-wechat-news', 0),
(242, 241, '', '图文列表', 'admin', '', '', 'app/wechat/news', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(243, 241, '', '发送图文', 'admin', '', '', 'app/wechat/push', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(244, 585, '', '批量用户分组', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/group_set/', '', 1, '', 0, 'admin-user-group_set', 0),
(245, 244, '', '用户分组表单', 'admin', '', '', 'user/set_group/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(246, 244, '', '保存分组', 'admin', '', '', 'user/set_group', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(247, 586, '', '添加修改用户等级', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/level_add', '', 1, '', 0, 'admin-user-level_add', 0),
(248, 247, '', '添加会员等级表单', 'admin', '', '', 'user/user_level/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(249, 247, '', '保存会员等级', 'admin', '', '', 'user/user_level', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(250, 11, '', '用户等级列表', 'admin', '', '', 'user/user_level/vip_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(251, 586, '', '用户等级是否显示', 'admin', '', '', 'user/user_level/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(252, 586, '', '删除用户等级', 'admin', '', '', 'user/user_level/delete/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(253, 586, '', '等级任务', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/user_level', '', 1, '', 0, '', 0),
(254, 253, '', '等级任务列表', 'admin', '', '', 'user/user_level/task/<level_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(255, 253, '', '等级任务显示隐藏', 'admin', '', '', 'user/user_level/set_task_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(256, 253, '', '等级任务是否必达', 'admin', '', '', 'user/user_level/set_task_must/<id>/<is_must>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(257, 253, '', '添加修改等级任务', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(258, 257, '', '添加等级任务表单', 'admin', '', '', 'user/user_level/create_task', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(259, 257, '', '保存修改任务', 'admin', '', '', 'user/user_level/save_task', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(260, 253, '', '删除等级任务', 'admin', '', '', 'user/user_level/delete_task/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(261, 227, '', '用户分组列表', 'admin', '', '', 'user/user_group/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(262, 227, '', '删除用户分组', 'admin', '', '', 'user/user_group/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(263, 227, '', '添加修改用户分组', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/group', '', 1, '', 0, 'admin-user-group', 0),
(264, 263, '', '添加修改用户分组表单', 'admin', '', '', 'user/user_group/add/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(265, 263, '', '保存修改用户分组', 'admin', '', '', 'user/user_group/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(266, 29, '', '分销员列表', 'admin', '', '', 'agent/index', 'GET', '[]', 0, 0, 0, 1, '', '26/29', 2, '', 0, '', 0),
(267, 584, '', '分销员数据', 'admin', '', '', 'agent/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(268, 29, '', '推广人列表', 'admin', '', '', 'agent/stair', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(269, 29, '', '推广人订单列表', 'admin', '', '', 'agent/stair/order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(270, 584, '', '清除推广人', 'admin', '', '', 'agent/stair/delete_spread/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(271, 584, '', '推广二维码', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(272, 271, '', '公众号推广二维码', 'admin', '', '', 'agent/look_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(273, 271, '', '小程序推广二维码', 'admin', '', '', 'agent/look_xcx_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(274, 583, '', '添加优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/add', '', 1, '', 0, 'admin-marketing-store_coupon-add', 0),
(275, 274, '', '添加优惠卷表单', 'admin', '', '', 'marketing/coupon/create/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(276, 274, '', '保存优惠卷', 'admin', '', '', 'marketing/coupon/save_coupon', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(277, 583, '', '发布优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/push', '', 1, '', 0, 'admin-marketing-store_coupon-push', 0),
(278, 277, '', '发布优惠卷表单', 'admin', '', '', 'marketing/coupon/issue/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(279, 277, '', '发布优惠卷', 'admin', '', '', 'marketing/coupon/issue/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(280, 583, '', '立即失效', 'admin', '', '', 'marketing/coupon/status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(281, 583, '', '删除优惠卷', 'admin', '', '', 'marketing/coupon/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(282, 71, '', '优惠卷已发布列表', 'admin', '', '', 'marketing/coupon/released', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(283, 71, '', '领取记录', 'admin', '', '', 'marketing/coupon/released/issue_log/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(284, 71, '', '删除优惠卷', 'admin', '', '', 'marketing/coupon/released/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(285, 71, '', '修改状态', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(286, 285, '', '修改状态表单', 'admin', '', '', 'marketing/coupon/released/<id>/status', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(287, 285, '', '保存修改状态', 'admin', '', '', 'marketing/coupon/released/status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(290, 405, '', '审核状态通过', 'admin', '', '', 'finance/extract/adopt/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(291, 405, '', '拒绝申请', 'admin', '', '', 'finance/extract/refuse/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(292, 405, '', '提现编辑', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(293, 292, '', '编辑表单', 'admin', '', '', 'finance/extract/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(294, 292, '', '保存修改', 'admin', '', '', 'finance/extract/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(295, 40, '', '充值列表', 'admin', '', '', 'finance/recharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(296, 40, '', '充值数据', 'admin', '', '', 'finance/recharge/user_recharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(297, 40, '', '退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(298, 297, '', '获取退款表单', 'admin', '', '', 'finance/recharge/<id>/refund_edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(299, 297, '', '保存退款', 'admin', '', '', 'finance/recharge/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(300, 144, '', '提货点', 'admin', 'merchant.system_store', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_store/list', '', 1, 'setting', 1, 'setting-merchant-system-store', 0),
(301, 144, '', '核销员', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_store_staff/index', '', 1, 'setting', 1, 'setting-merchant-system-store-staff', 0),
(302, 144, '', '核销订单', 'admin', '', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_verify_order/index', '', 1, 'setting', 1, 'setting-merchant-system-verify-order', 0),
(303, 12, '', '发货设置', 'admin', 'setting', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/freight', '', 1, '', 0, '', 0),
(304, 303, '', '物流配置', 'admin', 'setting.systemConfig', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_config_logistics/3/10', '', 1, '', 0, 'setting-system-config-logistics', 0),
(305, 44, '', '文章列表', 'admin', '', '', 'cms/cms', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(306, 409, '', '文章分类', 'admin', '', '', 'cms/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(307, 42, '', '佣金记录列表', 'admin', '', '', 'finance/finance/commission_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(308, 42, '', '用户详情', 'admin', 'finance.finance', 'user_info', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(309, 308, '', '获取用户信息', 'admin', '', '', 'finance/finance/user_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(310, 308, '', '佣金详细列表', 'admin', '', '', 'finance/finance/extract_list/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(313, 23, '', '获取头部导航', 'admin', '', '', 'setting/config/header_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(314, 23, '', '获取配置列表', 'admin', '', '', 'setting/config/edit_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(315, 23, '', '修改配置', 'admin', '', '', 'setting/config/save_basics', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(316, 423, '', '添加客服', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/add', '', 1, '', 0, 'setting-store_service-add', 0),
(317, 316, '', '客服用户列表', 'admin', '', '', 'app/wechat/kefu/add', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(318, 316, '', '保存客服', 'admin', '', '', 'app/wechat/kefu', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(319, 423, '', '聊天记录', 'admin', '', '', 'app/wechat/kefu/record/', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(320, 423, '', '编辑客服', 'admin', '', '', '', '', '[]', 80, 0, 0, 1, '/admin/setting/store_service/edit', '', 1, '', 0, 'setting-store_service-edit', 0),
(321, 423, '', '删除客服', 'admin', '', '', 'app/wechat/kefu/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(322, 423, '', '客服是否开启', 'admin', '', '', 'app/wechat/kefu/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(323, 320, '', '编辑客服表单', 'admin', '', '', 'app/wechat/kefu/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(324, 320, '', '修改客服', 'admin', '', '', 'app/wechat/kefu/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(325, 19, '', '添加身份', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_role/add', '', 1, '', 0, 'setting-system_role-add', 0),
(326, 325, '', '添加身份表单', 'admin', '', '', 'setting/role/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(327, 325, '', '添加修改身份', 'admin', '', '', 'setting/role/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(328, 325, '', '修改身份表单', 'admin', '', '', 'setting/role/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(329, 19, '', '修改身份状态', 'admin', '', '', 'setting/role/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(330, 19, '', '删除身份', 'admin', '', '', 'setting/role/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(331, 20, '', '添加管理员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_admin/add', '', 1, '', 0, 'setting-system_admin-add', 0),
(332, 331, '', '添加管理员表单', 'admin', '', '', 'setting/admin/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(333, 331, '', '添加管理员', 'admin', '', '', 'setting/admin', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(334, 20, '', '编辑管理员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin /setting/system_admin/edit', '', 1, '', 0, ' setting-system_admin-edit', 0),
(335, 334, '', '编辑管理员表单', 'admin', '', '', 'setting/admin/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(336, 334, '', '修改管理员', 'admin', '', '', 'setting/admin/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(337, 20, '', '删除管理员', 'admin', '', '', 'setting/admin/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(338, 21, '', '添加规则', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/add', '', 1, '', 0, 'setting-system_menus-add', 0),
(339, 338, '', '添加权限表单', 'admin', '', '', 'setting/menus/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(340, 338, '', '添加权限', 'admin', '', '', 'setting/menus', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(341, 21, '', '修改权限', 'admin', 'setting.system_menus', 'edit', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/edit', '', 1, '', 0, '/setting-system_menus-edit', 0),
(342, 341, '', '编辑权限表单', 'admin', '', '', 'setting/menus/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(343, 341, '', '修改权限', 'admin', '', '', 'setting/menus/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(344, 21, '', '修改权限状态', 'admin', '', '', 'setting/menus/show/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(345, 21, '', '删除权限菜单', 'admin', '', '', 'setting/menus/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(346, 338, '', '添加子菜单', 'admin', 'setting.system_menus', 'add', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/add_sub', '', 1, '', 0, 'setting-system_menus-add_sub', 0),
(347, 361, '', '是否登陆短信平台', 'admin', '', '', 'notify/sms/is_login', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(348, 361, '', '短信剩余条数', 'admin', '', '', 'notify/sms/number', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(349, 95, '', '获取短信验证码', 'admin', '', '', 'serve/captcha', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(350, 95, '', '修改注册账号', 'admin', '', '', 'serve/register', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(351, 95, '', '登陆短信平台', 'admin', '', '', 'serve/login', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(353, 95, '', '退出短信登陆', 'admin', '', '', 'notify/sms/logout', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(355, 96, '', '短信模板列表', 'admin', '', '', 'serve/sms/temps', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(356, 96, '', '申请模板', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_template_apply/add', '', 1, '', 0, 'setting-sms-sms_template_apply-add', 0),
(357, 356, '', '申请短信模板表单', 'admin', '', '', 'notify/sms/temp/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(358, 356, '', '保存申请短信模板', 'admin', '', '', 'notify/sms/temp', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(359, 97, '', '短信套餐', 'admin', '', '', 'serve/meal_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(360, 97, '', '短信购买支付码', 'admin', '', '', 'serve/pay_meal', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(361, 94, '', '短信设置附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/attach', '', 1, '', 0, '', 0),
(362, 300, '', '门店数据', 'admin', '', '', 'merchant/store/get_header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(363, 300, '', '门店列表展示', 'admin', '', '', 'merchant/store', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(364, 424, '', '修改门店状态', 'admin', '', '', 'merchant/store/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(366, 7, '', '首页统计数据', 'admin', '', '', 'home/header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(367, 7, '', '首页订单图表', 'admin', '', '', 'home/order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(368, 7, '', '首页用户图表', 'admin', '', '', 'home/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(369, 7, '', '首页交易额排行', 'admin', '', '', 'home/rank', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(370, 72, '', '优惠卷领取列表', 'admin', '', '', 'marketing/coupon/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(371, 74, '', '砍价列表', 'admin', '', '', 'marketing/bargain', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(372, 74, '', '附加权限', 'admin', 'marketing.store_bargain', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_bargain/attr', '', 1, '', 0, '', 0),
(373, 372, '', '修改砍价状态', 'admin', '', '', 'marketing/bargain/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(374, 372, '', '砍价商品详情', 'admin', '', '', 'marketing/bargain/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(375, 74, '', '公共权限', 'admin', 'marketing.store_bargain', 'public', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_bargain/public', '', 1, '', 0, '', 0),
(376, 375, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(377, 375, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(378, 375, '', '运费模板', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(379, 372, '', '修改添加砍价商品', 'admin', '', '', 'marketing/bargain/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(380, 372, '', '删除砍价商品', 'admin', '', '', 'marketing/bargain/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(381, 75, '', '拼团列表', 'admin', '', '', 'marketing/combination', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(382, 75, '', '拼团数据', 'admin', '', '', 'marketing/combination/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(383, 75, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(384, 383, '', '拼团状态', 'admin', '', '', 'marketing/combination/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(385, 383, '', '删除拼团', 'admin', '', '', 'marketing/combination/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(386, 75, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(387, 386, '', '树型分类列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(388, 386, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(389, 386, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(390, 383, '', '获取拼团详情', 'admin', '', '', 'marketing/combination/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(391, 383, '', '编辑添加拼团', 'admin', '', '', 'marketing/combination/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(392, 76, '', '正在拼团列表', 'admin', '', '', 'marketing/combination/combine/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(393, 76, '', '拼团人员列表', 'admin', '', '', 'marketing/combination/order_pink/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(395, 77, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(396, 395, '', '修改拼团状态', 'admin', '', '', 'marketing/seckill/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(397, 77, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(398, 397, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(399, 397, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(400, 397, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(401, 397, '', '秒杀时间段列表', 'admin', '', '', 'marketing/seckill/time_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(402, 395, '', '编辑添加秒杀商品', 'admin', '', '', 'marketing/seckill/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(403, 395, '', '删除秒杀商品', 'admin', '', '', 'marketing/seckill/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(404, 39, '', '提现申请列表', 'admin', '', '', 'finance/extract', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(405, 39, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(406, 44, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(407, 406, '', '保存修改文章', 'admin', '', '', 'cms/cms', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(408, 406, '', '获取文章详情', 'admin', '', '', 'cms/cms/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(409, 44, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(410, 406, '', '关联商品列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(411, 406, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(412, 406, '', '关联商品', 'admin', '', '', 'cms/cms/relation/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(413, 406, '', '取消关联', 'admin', '', '', 'cms/cms/unrelation/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(414, 406, '', '删除文章', 'admin', '', '', 'cms/cms/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(415, 45, '', '文章列表', 'admin', '', '', 'cms/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(416, 45, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(417, 416, '', '文章分类添加表单', 'admin', '', '', 'cms/category/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'cms-category-create', 0),
(418, 416, '', '保存文章分类', 'admin', '', '', 'cms/category', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(419, 416, '', '编辑文章分类', 'admin', '', '', 'cms/category/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(420, 416, '', '修改文章分类', 'admin', '', '', 'cms/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(421, 416, '', '删除文章分类', 'admin', '', '', 'cms/category/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(422, 678, '', '客服列表', 'admin', '', '', 'app/wechat/kefu', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(423, 678, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(424, 300, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(425, 144, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(426, 425, '', '地图KEY权限', 'admin', '', '', 'merchant/store/address', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(427, 424, '', '添加编辑门店', 'admin', '', '', 'merchant/store/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'setting-merchant-system_store-save', 0),
(428, 424, '', '设置门店隐藏显示', 'admin', '', '', 'merchant/store/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(429, 424, '', '门店详情', 'admin', '', '', 'merchant/store/get_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(430, 424, '', '删除门店', 'admin', '', '', 'merchant/store/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(431, 425, '', '店员搜索门店列表', 'admin', '', '', 'merchant/store_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(432, 301, '', '店员列表', 'admin', '', '', 'merchant/store_staff', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(433, 301, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(434, 433, '', '添加店员表单', 'admin', '', '', 'merchant/store_staff/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'merchant-store_staff-create', 0),
(435, 425, '', '选择用户插件列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(436, 433, '', '添加修改店员', 'admin', '', '', 'merchant/store_staff/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(437, 433, '', '店员显示隐藏', 'admin', '', '', 'merchant/store_staff/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(438, 433, '', '编辑店员表单', 'admin', '', '', 'merchant/store_staff/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(439, 433, '', '删除店员', 'admin', '', '', 'merchant/store_staff/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(440, 302, '', '核销订单列表', 'admin', '', '', 'merchant/verify_order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(441, 302, '', '核销订单数据', 'admin', '', '', 'merchant/verify_badge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(442, 229, '', '城市数据列表', 'admin', '', '', 'setting/city/list/<parent_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(443, 229, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(444, 443, '', '获取添加城市表单', 'admin', '', '', 'setting/city/add/<parent_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(445, 443, '', '保存修改城市数据', 'admin', '', '', 'setting/city/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(446, 443, '', '获取修改城市表单', 'admin', '', '', 'setting/city/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(447, 443, '', '删除城市数据', 'admin', '', '', 'setting/city/del/<city_id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(448, 145, '', '物流公司列表', 'admin', '', '', 'freight/express', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(449, 145, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(450, 449, '', '修改物流公司状态', 'admin', '', '', 'freight/express/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(451, 449, '', '获取添加物流公司表单', 'admin', '', '', 'freight/express/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(452, 449, '', '保存物流公司', 'admin', '', '', 'freight/express', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(453, 449, '', '获取编辑物流公司表单', 'admin', '', '', 'freight/express/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(454, 449, '', '修改物流公司', 'admin', '', '', 'freight/express/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(455, 449, '', '删除物流公司', 'admin', '', '', 'freight/express/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(456, 230, '', '运费模板列表', 'admin', '', '', 'setting/shipping_templates/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(457, 230, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(458, 457, '', '运费模板城市数据', 'admin', '', '', 'setting/shipping_templates/city_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(459, 457, '', '保存或者修改运费模板', 'admin', '', '', 'setting/shipping_templates/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(460, 457, '', '删除运费模板', 'admin', '', '', 'setting/shipping_templates/del/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(461, 111, '', '配置分类列表', 'admin', '', '', 'setting/config_class', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(462, 111, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(463, 462, '', '配置分类添加表单', 'admin', '', '', 'setting/config_class/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(464, 462, '', '保存配置分类', 'admin', '', '', 'setting/config_class', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(465, 641, '', '编辑配置分类', 'admin', '', '', 'setting/config_class/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(466, 462, '', '删除配置分类', 'admin', '', '', 'setting/config_class/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(467, 125, '', '配置列表展示', 'admin', '', '', 'setting/config', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(468, 125, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(469, 468, '', '添加配置字段表单', 'admin', '', '', 'setting/config/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(470, 468, '', '保存配置字段', 'admin', '', '', 'setting/config', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(471, 468, '', '编辑配置字段表单', 'admin', '', '', 'setting/config/<id>/edit', '', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(472, 468, '', '编辑配置分类', 'admin', '', '', 'setting/config/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(473, 468, '', '删除配置', 'admin', '', '', 'setting/config/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(474, 468, '', '修改配置状态', 'admin', '', '', 'setting/config/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(475, 112, '', '组合数据列表', 'admin', '', '', 'setting/group', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(476, 112, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(477, 476, '', '新增组合数据', 'admin', '', '', 'setting/group', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(478, 476, '', '获取组合数据', 'admin', '', '', 'setting/group/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(479, 476, '', '修改组合数据', 'admin', '', '', 'setting/group/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(480, 476, '', '删除组合数据', 'admin', '', '', 'setting/group/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(481, 126, '', '组合数据列表表头', 'admin', '', '', 'setting/group_data/header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(482, 126, '', '组合数据列表', 'admin', '', '', 'setting/group_data', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(483, 126, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(484, 483, '', '获取组合数据添加表单', 'admin', '', '', 'setting/group_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(485, 483, '', '保存组合数据', 'admin', '', '', 'setting/group_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(486, 483, '', '获取组合数据信息', 'admin', '', '', 'setting/group_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(487, 483, '', '修改组合数据信息', 'admin', '', '', 'setting/group_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(488, 483, '', '删除组合数据', 'admin', '', '', 'setting/group_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(489, 483, '', '修改组合数据状态', 'admin', '', '', 'setting/group_data/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(490, 57, '', '清除缓存', 'admin', '', '', 'system/refresh_cache/cache', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(491, 57, '', '清除日志', 'admin', '', '', 'system/refresh_cache/log', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(492, 47, '', '管理员搜索列表', 'admin', '', '', 'system/log/search_admin', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(493, 47, '', '系统日志列表', 'admin', '', '', 'system/log', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(494, 64, '', '文件校验列表', 'admin', '', '', 'system/file', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(495, 66, '', '清除数据接口', 'admin', '', '', 'system/clear/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(496, 67, '', '数据库列表', 'admin', '', '', 'system/backup', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(497, 67, '', '数据库备份列表', 'admin', '', '', 'system/backup/file_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(498, 67, '', '数据表详情', 'admin', '', '', 'system/backup/read', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(499, 67, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(500, 499, '', '备份表', 'admin', '', '', 'system/backup/backup', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(501, 499, '', '优化表', 'admin', '', '', 'system/backup/optimize', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(502, 499, '', '修复表', 'admin', '', '', 'system/backup/repair', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(503, 499, '', '导入sql', 'admin', '', '', 'system/backup/import', 'POST', '[]', 0, 0, 1, 1, '', '', 2, '', 0, '', 0),
(504, 499, '', '删除数据库备份', 'admin', '', '', 'system/backup/del_file', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(505, 499, '', '备份下载', 'admin', '', '', 'backup/download', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(507, 92, '', '微信菜单列表', 'admin', '', '', 'app/wechat/menu', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(508, 92, '', '保存微信菜单', 'admin', '', '', 'app/wechat/menu', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(553, 109, '', '图文列表', 'admin', '', '', 'app/wechat/news', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(554, 109, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(555, 554, '', '保存图文', 'admin', '', '', 'app/wechat/news', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(556, 554, '', '图文详情', 'admin', '', '', 'app/wechat/news/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(557, 554, '', '删除图文', 'admin', '', '', 'app/wechat/news/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(558, 114, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(559, 558, '', '回复关键词', 'admin', '', '', 'app/wechat/reply', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(560, 115, '', '关键词回复列表', 'admin', '', '', 'app/wechat/keyword', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(561, 115, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(562, 558, '', '保存修改关键字', 'admin', '', '', 'app/wechat/keyword/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(563, 561, '', '获取关键字信息', 'admin', '', '', 'app/wechat/keyword/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(564, 561, '', '修改关键字状态', 'admin', '', '', 'app/wechat/keyword/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(565, 561, '', '删除关键字', 'admin', '', '', 'app/wechat/keyword/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(566, 656, '', '素材管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/system/file', '12/656', 1, '', 0, 'system-file', 0),
(567, 566, '', '附件列表', 'admin', '', '', 'file/file', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(568, 566, '', '附件分类', 'admin', '', '', 'file/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(569, 566, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(570, 569, '', '附件分类表单', 'admin', '', '', 'file/category/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(571, 569, '', '附件分类保存', 'admin', '', '', 'file/category', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(572, 569, '', '删除附件', 'admin', '', '', 'file/file/delete', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(573, 569, '', '移动附件分类', 'admin', '', '', 'file/file/do_move', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(574, 566, '', '上传附件', 'admin', '', '', 'file/upload/<upload_type?>', 'POST', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(575, 569, '', '附件分类编辑表单', 'admin', '', '', 'file/category/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(576, 569, '', '附件分类修改', 'admin', '', '', 'file/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(577, 2, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(578, 3, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(579, 6, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(580, 99, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(581, 5, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(582, 70, '', '优惠卷模板列表', 'admin', '', '', 'marketing/coupon/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(583, 70, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(584, 29, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(585, 10, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(586, 11, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(587, 25, '', '个人中心', 'admin', '', '', '', '', '[]', 0, 1, 1, 1, '/admin/system/user', '', 1, '', 0, 'system-user', 0),
(589, 9, '', '用户标签', 'admin', 'user.user_label', 'index', '', '', '[]', 8, 1, 0, 1, '/admin/user/label', '', 1, 'user', 1, 'user-user-label', 0),
(590, 589, '', '获取用户标签', 'admin', '', '', 'user/label/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '9/589', 2, '', 0, '', 0),
(591, 589, '', '删除用户标签', 'admin', '', '', 'user/user_label/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(592, 589, '', '添加修改用户标签', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_add', '', 1, '', 0, 'admin-user-label_add', 0),
(593, 592, '', '添加修改用户标签表单', 'admin', '', '', 'user/user_label/add/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(594, 592, '', '保存修改用户标签', 'admin', '', '', 'user/user_label/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(596, 2, '', '商品导出', 'admin', '', '', 'export/storeProduct', 'GET', '[]', 10, 0, 0, 1, '', '', 2, '', 0, 'export-storeProduct', 0),
(597, 5, '', '订单导出', 'admin', '', '', 'export/storeorder', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeOrder', 0),
(598, 77, '', '秒杀商品导出', 'admin', '', '', 'export/storeSeckill', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeSeckill', 0),
(600, 75, '', '拼团商品导出', 'admin', '', '', 'export/storeCombination', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeCombination', 0),
(601, 74, '', '砍价商品导出', 'admin', '', '', 'export/storeBargain', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeBargain', 0),
(602, 29, '', '推广员列表导出', 'admin', '', '', 'export/userAgent', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userAgent', 0),
(603, 40, '', '用户充值导出', 'admin', '', '', 'export/userRecharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userRecharge', 0),
(605, 25, '', '商业授权', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/auth', '', 1, '', 0, 'system-maintain-auth', 0),
(606, 29, '', '分销员数据', 'admin', '', '', 'agent/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(607, 587, '', '修改密码', 'admin', '', '', 'setting/update_admin', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(608, 605, '', '商业授权', 'admin', '', '', 'auth', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(610, 20, '', '管理员列表', 'admin', '', '', 'setting/admin', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(611, 19, '', '身份列表', 'admin', '', '', 'setting/role', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(612, 2, '', '批量上下架', 'admin', '', '', 'product/product/product_show', 'PUT', '[]', 5, 0, 0, 1, '', '', 2, '', 0, 'product-product-product_show', 0),
(613, 585, '', '批量设置标签', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/set_label', '', 1, '', 0, 'admin-user-set_label', 0),
(614, 613, '', '获取标签表单', 'admin', '', '', 'user/set_label', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(615, 613, '', '保存标签', 'admin', '', '', 'user/save_set_label', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(618, 42, '', '佣金导出', 'admin', '', '', 'export/userCommission', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userCommission', 0),
(619, 21, '', '权限列表', 'admin', '', '', 'setting/menus', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(620, 22, '', '商品详情', 'admin', '', '', 'product/product/<id>', 'GET', '[]', 0, 1, 1, 1, '', '', 2, '', 0, '', 0),
(621, 585, '', '保存用户信息', 'admin', '', '', 'user/user', 'POST', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(622, 585, '', '积分余额', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/edit_other', '', 1, '', 0, '', 0),
(623, 622, '', '获取修改用户详情表单', 'admin', '', '', 'user/edit_other/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(624, 622, '', '修改用户余额', 'admin', '', '', 'user/update_other/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(625, 585, '', '赠送用户', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/user_level', '', 1, '', 0, '', 0),
(626, 625, '', '获取表单', 'admin', '', '', 'user/give_level/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(627, 625, '', '赠送会员等级', 'admin', '', '', 'user/save_give_level/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(628, 585, '', '单个用户分组设置', 'admin', '', '', 'user/save_set_group', 'PUT', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(630, 375, '', '获取商品属性', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(631, 386, '', '商品规格获取', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(632, 397, '', '商品规格和获取', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(633, 395, '', '获取秒杀详情', 'admin', '', '', 'marketing/seckill/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(634, 40, '', '删除充值记录', 'admin', '', '', 'finance/recharge/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(635, 20, '', '修改管理员状态', 'admin', '', '', 'setting/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(636, 25, '', '其他权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/system/other', '', 1, '', 0, '', 0),
(637, 636, '', '消息提醒', 'admin', '', '', 'jnotice', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(638, 457, '', '获取运费模板详情', 'admin', '', '', 'setting/shipping_templates/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(639, 457, '', '删除运费模板', 'admin', '', '', 'setting/shipping_templates/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(640, 462, '', '修改配置分类状态', 'admin', '', '', 'setting/config_class/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(641, 462, '', '编辑配置分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, 'system/config/system_config_tab/edit', '', 1, '', 0, '', 0),
(642, 641, '', '获取编辑配置分类表单', 'admin', '', '', 'setting/config_class/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(655, 65, '', '在线升级', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/system/system_upgradeclient/index', '', 1, '', 0, 'system-system-upgradeclient', 0),
(656, 12, '', '页面管理', 'admin', '', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/pages', '', 1, '', 0, 'admin-setting-pages', 0),
(657, 656, '', '页面设计', 'admin', '', '', '', '', '[]', 3, 1, 0, 1, '/admin/setting/pages/devise', '12/656', 1, '', 0, 'admin-setting-pages-devise', 0),
(658, 656, '', '页面编辑', 'admin', '', '', '', '', '[]', 3, 1, 1, 1, '/admin/setting/pages/diy', '12/656', 1, '', 0, 'admin-setting-pages-diy', 0),
(660, 656, '', '页面链接', 'admin', '', '', '', '', '[]', 3, 0, 0, 1, '/admin/setting/pages/links', '12/656', 1, '', 0, 'admin-setting-pages-links', 0),
(661, 657, '', 'DIY列表', 'admin', '', '', 'diy/get_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(662, 657, '', '组件文章分类', 'admin', '', '', 'cms/category_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(663, 657, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/diy', '', 1, '', 0, 'admin-setting-diy-additional', 0),
(664, 663, '', '获取页面设计', 'admin', '', '', 'diy/get_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(665, 663, '', '保存和修改页面', 'admin', '', '', 'diy/save/<id?>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-setting-pages-diy-save', 0),
(666, 660, '', '路径列表', 'admin', '', '', 'diy/get_url', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(667, 663, '', '删除页面', 'admin', '', '', 'diy/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(668, 663, '', '修改页面状态', 'admin', '', '', 'diy/set_status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(669, 2, '', '批量下架', 'admin', '', '', 'product/product/product_unshow', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(670, 581, '', '订单打印', 'admin', '', '', 'order/print/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(671, 585, '', '清除会员等级', 'admin', '', '', 'user/del_level/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(672, 271, '', 'H5推广二维码', 'admin', '', '', 'agent/look_h5_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(673, 416, '', '修改文章分类状态', 'admin', '', '', 'cms/category/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(674, 229, '', '清除城市缓存', 'admin', '', '', 'setting/city/clean_cache', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(675, 657, '', '组件商品分类', 'admin', '', '', 'diy/get_category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(676, 657, '', '组件商品列表', 'admin', '', '', 'diy/get_product', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(677, 581, '', '订单号核销', 'admin', '', '', 'order/write_update/<order_id>', 'PUT', '[]', 0, 0, 0, 1, 'order/dels', '', 2, '', 0, 'admin-order-write_update', 0),
(678, 165, '', '客服列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/index', '', 1, '', 0, 'admin-setting-store_service-index', 0),
(679, 165, '', '客服话术', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/speechcraft', '', 1, '', 0, 'admin-setting-store_service-speechcraft', 0),
(685, 22, '', '上传商品视频', 'admin', '', '', 'product/product/get_temp_keys', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(686, 27, '', '直播管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live', '', 1, '', 0, 'admin-marketing-live', 0),
(687, 686, '', '直播间管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/live_room', '', 1, '', 0, 'admin-marketing-live-live_room', 0),
(688, 686, '', '直播商品管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/live_goods', '', 1, '', 0, 'admin-marketing-live-live_goods', 0),
(689, 686, '', '主播管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/anchor', '', 1, '', 0, 'admin-marketing-live-anchor', 0),
(690, 687, '', '添加直播间', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/live/add_live_room', '', 1, '', 0, 'admin-marketing-live-add_live_room', 0),
(691, 688, '', '添加直播商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/live/add_live_goods', '', 1, '', 0, 'admin-marketing-live-add_live_goods', 0),
(693, 689, '', '主播列表', 'admin', '', '', 'live/anchor/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-marketing-live-anchor', 0),
(694, 689, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '', 0),
(695, 694, '', '添加/修改主播表单', 'admin', '', '', 'live/anchor/add/<id>', 'GET', '[]', 0, 0, 0, 1, 'live/anchor/add/<id>', '', 2, '', 0, 'live-anchor-add', 0),
(696, 694, '', '添加/修改提交', 'admin', '', '', 'live/anchor/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(697, 694, '', '删除主播', 'admin', '', '', 'live/anchor/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(698, 694, '', '设置主播是否显示', 'admin', '', '', 'live/anchor/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(699, 688, '', '直播商品列表', 'admin', '', '', 'live/goods/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(700, 691, '', '生成直播商品', 'admin', '', '', 'live/goods/create', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(701, 691, '', '保存直播商品', 'admin', '', '', 'live/goods/add', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(702, 688, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '/admin/*', 0),
(703, 702, '', '直播商品详情', 'admin', '', '', 'live/goods/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(704, 702, '', '删除直播商品', 'admin', '', '', 'live/goods/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(705, 702, '', '同步直播商品', 'admin', '', '', 'live/goods/syncGoods', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(706, 702, '', '设置直播商品是否显示', 'admin', '', '', 'live/goods/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(707, 687, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '', 0),
(708, 687, '', '直播间列表', 'admin', '', '', 'live/room/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(709, 707, '', '添加直播间提交', 'admin', '', '', 'live/room/add', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(710, 707, '', '直播间详情', 'admin', '', '', 'live/room/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(711, 707, '', '直播间添加（关联）商品', 'admin', '', '', 'live/room/add_goods', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(712, 707, '', '删除直播间', 'admin', '', '', 'live/room/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(713, 707, '', '设置直播间是否显示', 'admin', '', '', 'live/room/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(714, 707, '', '同步直播间状态', 'admin', '', '', 'live/room/syncRoom', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(715, 898, '', '一键同步订阅消息', 'admin', '', '', 'app/routine/syncSubscribe', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, 'app-wechat-template-sync', 0),
(716, 0, 'md-stats', '统计', 'admin', '', '', '', '', '[]', 1, 1, 0, 1, '/admin/statistic', '', 1, '', 0, 'admin-statistic', 0),
(717, 716, '', '商品统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/product', '', 1, '', 0, 'admin-statistic', 0),
(718, 716, '', '用户统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/user', '', 1, '', 0, 'admin-statistic', 0),
(719, 71, '', '添加优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon_issue/create', '27/30/71', 1, '', 0, 'marketing-store_coupon_issue-create', 0),
(720, 303, '', '配送员管理', 'admin', '', '', '', '', '[]', 10, 1, 0, 1, '/admin/setting/delivery_service/index', '', 1, '', 0, 'setting-delivery-service', 0),
(721, 729, '', '编辑配送员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/delivery_service/edit', '', 1, '', 0, 'setting-delivery_service-edit', 0),
(722, 720, '', '配送员列表', 'admin', '', '', 'order/delivery/index', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(723, 721, '', '修改配送员', 'admin', '', '', 'order/delivery/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(724, 729, '', '添加配送员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/delivery_service/add', '', 1, '', 0, 'setting-delivery_service-add', 0),
(725, 724, '', '获取添加配送员表单', 'admin', '', '', 'order/delivery/add', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(726, 724, '', '保存配送员', 'admin', '', '', 'order/delivery/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(727, 729, '', '删除配送员', 'admin', '', '', 'order/delivery/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(728, 729, '', '配送员是否开启', 'admin', '', '', 'order/delivery/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(729, 720, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(731, 9, '', '付费会员', 'admin', '', '', '', '', '[]', 7, 1, 0, 1, '/admin/user/grade', '', 1, '', 0, 'user-user-grade', 0),
(732, 762, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(733, 732, '', ' 添加会员批次', 'admin', '', '', 'user/member_batch/save/<id>', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(734, 732, '', '列表字段修改', 'admin', '', '', 'user/member_batch/set_value/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'user-member_batch-set_value', 0),
(735, 732, '', '会员卡导出', 'admin', '', '', 'export/memberCard/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'export-member_card', 0),
(736, 762, '', '卡密列表', 'admin', '', '', 'user/member_batch/index', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(737, 732, '', '会员卡列表', 'admin', '', '', 'user/member_card/index', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member_card-index', 0),
(738, 165, '', '用户留言', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/feedback', '', 1, '', 0, 'admin-setting-store_service-feedback', 0),
(739, 738, '', '列表展示', 'admin', '', '', 'app/feedback', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(740, 738, '', '附件权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '*', '', 1, '', 0, '', 0),
(741, 740, '', '删除反馈', 'admin', '', '', 'app/feedback/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(742, 679, '', '列表展示', 'admin', '', '', 'app/wechat/speechcraft', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(743, 679, '', '附件权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '*', '', 1, '', 0, '', 0),
(744, 743, '', '添加话术', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/add', '', 1, '', 0, 'admin-setting-store_service-speechcraft-add', 0),
(745, 743, '', '编辑话术', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/edit', '', 1, '', 0, 'admin-setting-store_service-speechcraft-edit', 0),
(746, 744, '', '获取添加话术表单', 'admin', '', '', 'app/wechat/speechcraft/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(747, 744, '', '保存话术', 'admin', '', '', 'app/wechat/speechcraft', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(748, 745, '', '获取编辑话术表单', 'admin', '', '', 'app/wechat/speechcraft/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(749, 745, '', '确认修改', 'admin', '', '', 'app/wechat/speechcraft/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(750, 743, '', '删除话术', 'admin', '', '', 'app/wechat/speechcraft/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(751, 731, '', '会员类型', 'admin', '', '', '', '', '[]', 5, 1, 0, 1, '/admin/user/grade/type', '', 1, '', 0, 'admin-user-member-type', 0),
(752, 751, '', '会员分类列表', 'admin', '', '', 'user/member/ship', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-ship', 0),
(753, 751, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(754, 753, '', '会员卡类型保存', 'admin', '', '', 'user/member_ship/save/<id>', 'POST', '[]', 0, 1, 1, 1, '', '', 2, '', 0, 'user-member_ship-save', 0),
(755, 31, '', '砍价列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_bargain/bargain_list', '', 1, '', 0, 'marketing-store_bargain-bargain_list', 0),
(756, 585, '', '添加用户', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/save', '', 1, '', 0, 'admin-user-save', 0),
(757, 756, '', '获取添加用户表单', 'admin', '', '', 'user/user/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(758, 756, '', '保存用户', 'admin', '', '', 'user/user', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(759, 585, '', '同步公众号用户', 'admin', '', '', 'user/user/syncUsers', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-user-synchro', 0),
(760, 4, '', '收银订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/offline', '', 1, '', 0, 'admin-order-offline', 0),
(761, 760, '', '线下收银订单', 'admin', '', '', 'order/scan_list', 'GET', '[]', 0, 0, 1, 1, '', '', 2, '', 0, 'admin-order-scan_list', 0),
(762, 731, '', '卡密会员', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/user/grade/card', '', 1, '', 0, 'admin-user-grade-card', 0),
(763, 731, '', '会员记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/user/grade/record', '', 1, '', 0, 'admin-user-grade-record', 0),
(764, 763, '', '会员记录列表', 'admin', '', '', 'user/member/record', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-record', 0),
(765, 731, '', '会员权益', 'admin', '', '', '', '', '[]', 4, 1, 0, 1, '/admin/user/grade/right', '', 1, '', 0, 'admin-user-grade-right', 0),
(766, 716, '', '交易统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/transaction', '', 1, '', 0, 'admin-statistic', 0),
(767, 36, '', '发票管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/invoice/list', '', 1, '', 0, 'admin-order-startOrderInvoice-index', 0),
(768, 210, '', '编辑', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-edit', 0),
(769, 210, '', '订单信息', 'admin', '', '', 'order/invoice_order_info/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-orderInfo', 0),
(770, 210, '', '编辑提交', 'admin', '', '', 'order/invoice/set/<id>', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-update', 0),
(771, 765, '', '会员权益列表', 'admin', '', '', 'user/member/right', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-right', 0),
(772, 765, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(773, 772, '', '会员权益保存', 'admin', '', '', 'user/member_right/save/<id>', 'POST', '[]', 0, 1, 1, 1, '', '', 2, '', 0, 'user-member_right-save', 0),
(774, 589, '', '用户标签列表', 'admin', '', '', 'user/user_label_cate/all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-user-user_lable_cate-all', 0),
(775, 731, '', '会员协议', 'admin', '', '', '', '', '[]', 3, 1, 0, 1, '/admin/user/grade/agreement', '', 1, '', 0, 'admin-user-grade-agreement', 0),
(776, 775, '', '编辑会员协议', 'admin', '', '', 'user/member_agreement/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'member_agreement-save', 0),
(777, 775, '', '会员协议列表', 'admin', '', '', 'user/member/agreement', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-agreement-list', 0),
(778, 740, '', '获取修改备注表单接口', 'admin', '', '', 'app/feedback/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(779, 740, '', '修改用户备注接口', 'admin', '', '', 'app/feedback/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(780, 589, '', '标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate', '', 1, '', 0, '', 0),
(781, 780, '', '获取标签分类列表', 'admin', '', '', 'user/user_label_cate/all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(782, 780, '', '添加标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate/add', '', 1, '', 0, '', 0),
(783, 782, '', '获取标签分类表单', 'admin', '', '', 'user/user_label_cate/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(784, 782, '', '保存标签分类', 'admin', '', '', 'user/user_label_cate', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(785, 780, '', '修改标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate/edit', '', 1, '', 0, '', 0),
(786, 785, '', '获取修改标签分类表单', 'admin', '', '', 'user/user_label_cate/<id>/edit', 'GET', '[]', 0, 0, 0, 1, 'user/user_label_cate/<id>/edit', '', 2, '', 0, '', 0),
(787, 785, '', '保存用户标签分类', 'admin', '', '', 'user/user_label_cate/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(788, 780, '', '删除用户标签分类', 'admin', '', '', 'user/user_label_cate/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(789, 743, '', '话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate', '', 1, '', 0, 'admin-setting-store_service-speechcraft-cate', 0),
(790, 789, '', '获取话术分类列表', 'admin', '', '', 'app/wechat/speechcraftcate', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(791, 789, '', '添加话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate/create', '', 1, '', 0, '', 0),
(792, 791, '', '获取话术分类表单', 'admin', '', '', 'app/wechat/speechcraftcate/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(793, 791, '', '保存话术分类', 'admin', '', '', 'app/wechat/speechcraftcate', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(794, 795, '', '获取话术分类表单', 'admin', '', '', 'app/wechat/speechcraftcate/<id>/edit', 'GET', '[]', 0, 0, 0, 1, 'app/wechat/speechcraftcate/<id>/edit', '', 2, '', 0, '', 0),
(795, 789, '', '修改话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate/edit', '', 1, '', 0, '', 0),
(796, 795, '', '保存修改客户话术分类', 'admin', '', '', 'app/wechat/speechcraftcate/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(797, 789, '', '删除话术分类', 'admin', '', '', 'app/wechat/speechcraftcate/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(798, 209, '', '获取送货人列表', 'admin', '', '', 'order/delivery/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(799, 209, '', '获取电子面单打印默认配置', 'admin', '', '', 'order/sheet_info', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(800, 581, '', '电子面单打印', 'admin', '', '', 'order/order_dump/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(801, 760, '', '获取收银二维码', 'admin', '', '', 'order/offline_scan', 'GET', '[]', 0, 0, 0, 1, '', '4/760', 2, '', 0, '', 0),
(802, 767, '', '获取订单发票数据', 'admin', '', '', 'order/invoice/chart', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(803, 762, '', '下载卡密二维码', 'admin', '', '', 'user/member_scan', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(805, 584, '', '修改推广人', 'admin', '', '', 'agent/spread', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(806, 71, '', '复制优惠券', 'admin', '', '', 'marketing/coupon/copy/<id>', 'GET', '[]', 0, 0, 0, 1, 'marketing/coupon/copy/369', '', 2, '', 0, '', 0),
(807, 755, '', '获取砍价列表', 'admin', '', '', 'marketing/bargain_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(808, 77, '', '秒杀商品列表', 'admin', '', '', 'marketing/seckill', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(809, 95, '', '获取平台用户信息', 'admin', '', '', 'serve/info', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(810, 95, '', '获取平台消费列表', 'admin', '', '', 'serve/record', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(811, 95, '', '修改手机号', 'admin', '', '', 'serve/update_phone', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(812, 95, '', '修改签名', 'admin', '', '', 'serve/sms/sign', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(813, 95, '', '修改账号密码', 'admin', '', '', 'serve/modify', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(814, 721, '', '获取编辑配送员表单', 'admin', '', '', 'order/delivery/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(815, 717, '', '获取基础商品接口', 'admin', '', '', 'statistic/product/get_basic', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(816, 717, '', '获取商品趋势', 'admin', '', '', 'statistic/product/get_trend', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(817, 717, '', '获取商品排行', 'admin', '', '', 'statistic/product/get_product_ranking', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(818, 718, '', '获取用户基础', 'admin', '', '', 'statistic/user/get_basic', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(819, 718, '', '获取用户趋势', 'admin', '', '', 'statistic/user/get_trend', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(820, 718, '', '获取用户地区排行', 'admin', '', '', 'statistic/user/get_region', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(821, 718, '', '获取用户性别排行', 'admin', '', '', 'statistic/user/get_sex', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(822, 766, '', '获取交易趋势', 'admin', '', '', 'statistic/trade/top_trade', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(823, 766, '', '获取订单趋势', 'admin', '', '', 'statistic/trade/bottom_trade', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(824, 718, '', '导出用户统计', 'admin', '', '', 'statistic/user/get_excel', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(825, 717, '', '导出商品统计', 'admin', '', '', 'statistic/product/get_excel', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(828, 10, '', '用户列表', 'admin', '', '', 'user/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(830, 732, '', '卡密列表', 'admin', '', '', 'user/member_card/index/<card_batch_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(831, 423, '', '进入工作台', 'admin', '', '', 'app/wechat/kefu/login/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(832, 71, '', '保存优惠券', 'admin', '', '', 'marketing/coupon/save_coupon', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(833, 755, '', '砍价详情', 'admin', '', '', 'marketing/bargain_list_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(834, 95, '', '短信记录列表', 'admin', '', '', 'notify/sms/record', 'GET', '[]', 0, 0, 0, 1, 'notify/sms/record', '', 2, '', 0, '', 0),
(835, 28, '', '分销设置表单', 'admin', '', '', 'agent/config/edit_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(836, 28, '', '分销设置表单提交', 'admin', '', '', 'agent/config/save_basics', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(837, 79, '', '积分配置表单', 'admin', '', '', 'marketing/integral_config/edit_basics', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(838, 79, '', '积分配置表单提交', 'admin', '', '', 'marketing/integral_config/save_basics', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(843, 154, '', '签到天数头部数据', 'admin', '', '', 'setting/sign_data/header', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(844, 154, '', '设置签到数据状态', 'admin', '', '', 'setting/sign_data/set_status/<id>/<status>', 'PUT', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(845, 154, '', '签到天数列表', 'admin', '', '', 'setting/sign_data', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(846, 154, '', '添加签到天数表单', 'admin', '', '', 'setting/sign_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(847, 154, '', '添加签到天数', 'admin', '', '', 'setting/sign_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(848, 154, '', '编辑签到天数表单', 'admin', '', '', 'setting/sign_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(849, 154, '', '编辑签到天数', 'admin', '', '', 'setting/sign_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(850, 154, '', '删除签到天数', 'admin', '', '', 'setting/sign_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(876, 78, '', '秒杀配置列表', 'admin', '', '', 'setting/seckill_data', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(877, 78, '', '添加秒杀表单', 'admin', '', '', 'setting/seckill_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(878, 78, '', '添加秒杀', 'admin', '', '', 'setting/seckill_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(879, 78, '', '编辑秒杀表单', 'admin', '', '', 'setting/seckill_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(880, 78, '', '编辑秒杀', 'admin', '', '', 'settting/seckill_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(881, 78, '', '删除秒杀', 'admin', '', '', 'setting/seckill_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(884, 128, '', '获取数据分类', 'admin', '', '', 'setting/group_all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(885, 569, '', '附件名称修改', 'admin', '', '', 'file/file/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '25/566/569', 2, '', 0, '', 0),
(886, 577, '', '用户标签接口', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '1/2/577', 2, '', 0, '', 0),
(887, 625, '', '获取赠送付费会员时长表单', 'admin', '', '', 'user/give_level_time/<id>', 'GET', '[]', 0, 0, 0, 1, '', '9/10/585/625', 2, '', 0, '', 0),
(888, 625, '', '保存赠送付费会员时长', 'admin', '', '', 'user/save_give_level_time/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '9/10/585/625', 2, '', 0, '', 0),
(889, 663, '', '添加页面', 'admin', '', '', 'diy/create', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, 'admin-template', 0),
(890, 663, '', '保存新增', 'admin', '', '', 'diy/create', 'POST', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, 'admin-template', 0),
(891, 663, '', '设置默认数据', 'admin', '', '', 'diy/set_recovery/<id?>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, '', 0),
(892, 663, '', '获取商品列表', 'admin', '', '', 'diy/get_product_list', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, '', 0),
(893, 577, '', '商品活动状态检测', 'admin', '', '', 'product/product/check_activity/<id>', 'GET', '[]', 0, 0, 0, 1, '', '1/2/577', 2, '', 0, '', 0),
(894, 589, '', '会员标签列表', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '9/589', 2, '', 0, '', 0),
(895, 585, '', '新增客服选择用户列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 0, 1, '', '9/10/585', 2, '', 0, '', 0),
(896, 26, '', '分销等级', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/membership_level/index', '26', 1, '', 0, '', 0),
(897, 4, '', '售后订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/refund', '4', 1, '', 0, 'admin-order-refund', 0),
(898, 12, '', '消息管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/notification/index', '12', 1, '', 0, 'setting-notification', 0),
(902, 656, '', '主题风格', 'admin', '', '', '', '', '[]', 2, 1, 0, 1, '/admin/setting/theme_style', '12/656', 1, '', 0, 'admin-setting-theme_style', 0),
(903, 656, '', 'PC商城', 'admin', '', '', '', '', '[]', 2, 1, 0, 1, '/admin/setting/pc_group_data', '12/656', 1, '', 0, 'setting-system-pc_data', 0),
(904, 656, '', '客服页面广告', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/system_group_data/kf_adv', '', 1, '', 0, 'setting-system-group_data-kf_adv', 1),
(905, 34, '', '积分商品', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_integral/index', '27/34', 1, '', 0, 'marketing-store_integral', 0),
(906, 905, '', '积分商品列表', 'admin', '', '', 'marketing/integral_product', 'GET', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(908, 905, '', '添加积分商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/store_integral/create', '27/34/905', 1, '', 0, 'marketing-store_integral-create', 0),
(909, 27, '', '九宫格抽奖', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/index', '27', 1, '', 0, 'marketing-lottery-index', 0),
(912, 34, '', '积分订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_integral/order_list', '27/34', 1, '', 0, 'marketing-store_integral-order', 0),
(913, 905, '', '批量添加积分商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/pages/marketing/store_integral/add_store_integral', '27/34/905', 1, '', 0, 'marketing-store_integral-create', 0),
(914, 897, '', '售后订单列表', 'admin', '', '', 'refund/list', 'GET', '[]', 0, 0, 0, 1, '', '4/897', 2, '', 0, '', 0),
(915, 5, '', '子订单列表', 'admin', '', '', 'order/split_order/<id>', 'GET', '[]', 0, 0, 0, 1, 'order/split_order/<id>', '4/5', 2, '', 0, '', 0),
(916, 5, '', '订单详情', 'admin', '', '', 'order/info/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(917, 5, '', '订单记录', 'admin', '', '', 'order/status/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(918, 5, '', '可拆分商品列表', 'admin', '', '', 'order/split_cart_info/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(919, 5, '', '拆单发送货', 'admin', '', '', 'order/split_delivery/<id>', 'PUT', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(920, 896, '', '修改分销等级状态', 'admin', '', '', 'agent/level/set_status/<id>/<status>', 'PUT', '{\"[PUT] agent\":\"level\",\"set_status\":\"<id>\"}', 0, 0, 1, 1, '', '26/896', 2, '', 0, '', 0),
(921, 896, '', '修改分销等级任务状态', 'admin', '', '', 'agent/level_task/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(922, 896, '', '获取赠送分销等级表单', 'admin', '', '', 'agent/get_level_form', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(923, 896, '', '赠送分销等级', 'admin', '', '', 'agent/give_level', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(924, 896, '', '分销等级列表', 'admin', '', '', 'agent/level', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(925, 896, '', '添加分销等级表单', 'admin', '', '', 'agent/level/create', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(926, 896, '', '编辑分销等级表单', 'admin', '', '', 'agent/level/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(927, 896, '', '分销等级任务', 'admin', '', '', 'agent/level_task', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(928, 896, '', '删除分销等级', 'admin', '', '', 'agent/level/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(929, 896, '', '添加分销员等级', 'admin', '', '', 'agent/level', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(930, 896, '', '编辑分销员等级', 'admin', '', '', 'agent/level/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(931, 896, '', '添加分销员等级任务表单', 'admin', '', '', 'agent/level_task/create', 'GET', '[]', 0, 0, 0, 1, 'agent/level_task/create', '26/896', 2, '', 0, '', 0),
(932, 896, '', '添加分销员等级任务', 'admin', '', '', 'agent/level_task', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(933, 896, '', '编辑分销员等级任务表单', 'admin', '', '', 'agent/level_task/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(934, 896, '', '编辑分销员等级任务', 'admin', '', '', 'agent/level_task/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(935, 896, '', '删除分销员等级任务', 'admin', '', '', 'agent/level_task/<id>', 'DELETE', '[]', 0, 0, 0, 1, 'agent/level_task/<id>', '26/896', 2, '', 0, '', 0),
(936, 423, '', '新增客服选择用户列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 1, 1, '', '165/678/423', 2, '', 0, '', 0),
(937, 78, '', '秒杀配置头部', 'admin', '', '', 'setting/seckill_data/header', 'GET', '[]', 0, 0, 0, 1, '', '27/33/78', 2, '', 0, '', 0),
(938, 154, '', '签到配置编辑保存', 'admin', '', '', 'setting/group_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/154', 2, '', 0, '', 0),
(939, 154, '', '签到配置添加保存', 'admin', '', '', 'setting/group_data', 'POST', '[]', 0, 0, 0, 1, '', '27/34/154', 2, '', 0, '', 0),
(940, 905, '', '添加积分商品保存', 'admin', '', '', 'marketing/integral/<id>', 'POST', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(941, 912, '', '积分订单头部', 'admin', '', '', 'marketing/integral/order/chart', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(942, 912, '', '积分订单列表', 'admin', '', '', 'marketing/integral/order/list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(943, 905, '', '积分商品编辑', 'admin', '', '', 'marketing/integral/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(944, 912, '', '发货物流列表', 'admin', '', '', 'marketing/integral/order/express_list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(945, 912, '', '快递列表', 'admin', '', '', 'marketing/integral/order/delivery/list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(946, 912, '', '图表详情', 'admin', '', '', 'marketing/integral/order/sheet_info', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(947, 912, '', '发货', 'admin', '', '', 'marketing/integral/order/delivery/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(948, 912, '', '配送信息表单', 'admin', '', '', 'marketing/integral/order/distribution/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(949, 912, '', '配送信息保存', 'admin', '', '', 'marketing/integral/order/distribution/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(950, 912, '', '订单详情', 'admin', '', '', 'marketing/integral/order/info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(951, 912, '', '订单记录', 'admin', '', '', 'marketing/integral/order/status/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(952, 912, '', '小票打印', 'admin', '', '', 'marketing/integral/order/print/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(953, 912, '', '物流查询', 'admin', '', '', 'marketing/integral/order/express/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(954, 912, '', '订单备注', 'admin', '', '', 'marketing/integral/order/remark/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(955, 912, '', '收货', 'admin', '', '', 'marketing/integral/order/take/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(960, 909, '', '抽奖列表', 'admin', '', '', 'marketing/lottery/list', 'GET', '[]', 0, 1, 0, 1, '', '27/909', 2, '', 0, '', 0),
(961, 909, '', '抽奖商品详情', 'admin', '', '', 'marketing/lottery/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(962, 909, '', '用户等级', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(963, 909, '', '会员等级', 'admin', '', '', 'user/user_level/vip_list', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(964, 909, '', '编辑保存', 'admin', '', '', 'marketing/lottery/edit/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(965, 27, '', '营销公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, 'admin-marketing', '27', 1, '', 0, '', 0),
(966, 965, '', '附件分类', 'admin', '', '', 'file/category', 'GET', '[]', 0, 0, 0, 1, '', '27/965', 2, '', 0, '', 0),
(967, 965, '', '附件列表', 'admin', '', '', 'file/file', 'GET', '[]', 0, 0, 0, 1, '', '27/965', 2, '', 0, '', 0),
(968, 909, '', '删除抽奖', 'admin', '', '', 'marketing/lottery/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(969, 909, '', '抽奖记录列表', 'admin', '', '', 'marketing/lottery/record/list/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(970, 909, '', '物流公司', 'admin', '', '', 'order/express_list', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(971, 909, '', '抽奖记录备注', 'admin', '', '', 'marketing/lottery/record/deliver', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(972, 909, '', '抽奖状态', 'admin', '', '', 'marketing/lottery/set_status/<id>/<status>', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(973, 909, '', '添加抽奖', 'admin', '', '', 'marketing/lottery/add', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(975, 28, '', '分销配置头部', 'admin', '', '', 'setting/config/header_basics', 'GET', '[]', 0, 0, 0, 1, '', '26/28', 2, '', 0, '', 0),
(976, 717, '', '查看商品', 'admin', '', '', 'product/product/<id>', 'GET', '[]', 0, 0, 0, 1, '', '716/717', 2, '', 0, '', 0),
(977, 657, '', '获取风格设置', 'admin', '', '', 'diy/get_color_change/<type>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(978, 657, '', '获取个人中心菜单', 'admin', '', '', 'diy/get_member', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(979, 657, '', '个人中心组件分类', 'admin', '', '', 'diy/get_page_category', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(980, 657, '', '个人中心组件树形分类', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(981, 657, '', '获取页面链接', 'admin', '', '', 'diy/get_page_link/<cate_id>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(982, 657, '', '商品分类页保存', 'admin', '', '', 'diy/color_change/<status>/<type>', 'PUT', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(983, 657, '', '个人中心页保存', 'admin', '', '', 'diy/member_save', 'POST', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(984, 128, '', '获取组合数据', 'admin', '', '', 'setting/group_data', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(985, 128, '', '获取头部', 'admin', '', '', 'setting/sign_data/header', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(986, 128, '', '保存配置', 'admin', '', '', 'setting/group_data/save_all', 'POST', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(987, 128, '', '客服页面广告', 'admin', '', '', 'setting/get_kf_adv', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(988, 898, '', '消息管理列表', 'admin', '', '', 'setting/notification/index', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(989, 898, '', '模板消息详情', 'admin', '', '', 'setting/notification/info', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(990, 898, '', '编辑保存', 'admin', '', '', 'setting/notification/save', 'POST', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(991, 898, '', '模板消息状态修改', 'admin', '', '', 'setting/notification/set_status/<type>/<status>/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(992, 898, '', '一键同步模版消息', 'admin', '', '', 'app/wechat/syncSubscribe', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(993, 135, '', '小程序', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/routine', '135', 1, '', 0, 'admin-routine', 0),
(994, 993, '', '小程序下载', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/routine/download', '135/993', 1, '', 0, 'routine-download', 0),
(995, 994, '', '下载小程序页面数据', 'admin', '', '', 'app/routine/info', 'GET', '[]', 0, 0, 0, 1, '', '135/993/994', 2, '', 0, '', 0),
(996, 994, '', '下载小程序模版', 'admin', '', '', 'app/routine/download', 'POST', '[]', 0, 0, 0, 1, '', '135/993/994', 2, '', 0, '', 0),
(997, 716, '', '订单统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/order', '716', 1, '', 0, 'admin-statistic', 0),
(998, 37, '', '资金流水', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/capital_flow/index', '35/37', 1, '', 0, 'finance-capital_flow-index', 0),
(999, 37, '', '账单记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/billing_records/index', '35/37', 1, '', 0, 'finance-billing_records-index', 0),
(1000, 566, '', '富文本上传图片', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/widget.images/index.html', '25/566', 1, '', 0, 'admin-user-user-index', 0),
(1001, 34, '', '积分记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/point_record', '27/34', 1, '', 0, 'marketing-point_record-index', 0),
(1002, 34, '', '积分统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/point_statistic', '27/34', 1, '', 0, 'marketing-point_statistic-index', 0),
(1003, 35, '', '余额记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/balance', '35', 1, '', 0, 'finance-balance-index', 0),
(1004, 1003, '', '余额记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/balance/balance', '35/1003', 1, '', 0, 'finance-user-balance', 0),
(1005, 716, '', '余额统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/balance', '716', 1, '', 0, 'admin-statistic', 0),
(1006, 69, '', '公众号配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/wechat_config/3/2', '135/69', 1, '', 0, 'setting-system-config', 0),
(1007, 993, '', '小程序配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/routine_config/3/7', '135/993', 1, '', 0, 'setting-system-config', 0),
(1008, 135, '', 'PC端', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/pc', '135', 1, '', 0, 'admin-pc', 0),
(1009, 135, '', 'APP', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/app', '135', 1, '', 0, 'admin-app', 0),
(1010, 1008, '', 'PC端配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/pc_config/3/75', '135/1008', 1, '', 0, 'setting-system-config', 0),
(1011, 1009, '', 'APP配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/app_config/3/77', '135/1009', 1, '', 0, 'setting-system-config', 0),
(1012, 12, '', '存储配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/storage', '12', 1, '', 0, 'setting-storage', 0),
(1013, 26, '', '事业部', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/agent/division', '26', 1, '', 0, 'agent-division', 0),
(1014, 1013, '', '事业部列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/index', '26/1013', 1, '', 0, 'agent-division-index', 0),
(1015, 1013, '', '代理商列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/agent/index', '26/1013', 1, '', 0, 'agent-division-agent-index', 0),
(1016, 1013, '', '代理商申请', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/agent/applyList', '26/1013', 1, '', 0, 'agent-division-agent-applyList', 0),
(1017, 1013, '', '代理商规则', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/agent/agreement', '26/1013', 1, '', 0, 'agent-division-agent-agreement', 0),
(1018, 909, '', '抽奖配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/create', '27/909', 1, '', 0, 'admin-marketing-lottery-create', 0),
(1019, 909, '', '中奖记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/recording_list', '27/909', 1, '', 0, 'admin-marketing-lottery-recording_list-id', 0),
(1020, 1014, '', '事业部列表', 'admin', '', '', 'agent/division/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1021, 1014, '', '添加事业部', 'admin', '', '', 'agent/division/create/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1023, 27, '', '公众号渠道码', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/channel_code/channelCodeIndex', '27', 1, '', 0, 'marketing-channel_code-index', 0),
(1024, 1023, '', '添加公众号渠道码', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/channel_code/create', '27/1021', 1, '', 0, 'marketing-channel_code-create', 0),
(1025, 1023, '', '渠道码统计', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/channel_code/code_statistic', '27/1021', 1, '', 0, 'marketing-channel_code-statistic', 0),
(1026, 1014, '', '事业部下级列表', 'admin', '', '', '/agent/division/down_list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1027, 1014, '', '事业部保存', 'admin', '', '', 'agent/division/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1028, 1014, '', '事业部状态切换', 'admin', '', '', 'agent/division/set_status/<status>/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1029, 1014, '', '事业部删除', 'admin', '', '', 'division/del/<type>/<uid>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1030, 1015, '', '代理商列表', 'admin', '', '', 'agent/division/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1031, 1015, '', '代理商下级列表', 'admin', '', '', 'agent/division/down_list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1032, 1015, '', '添加代理商', 'admin', '', '', 'agent/division/agent/create/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1033, 1015, '', '代理商保存', 'admin', '', '', 'agent/division/agent/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1034, 1015, '', '代理商状态切换', 'admin', '', '', 'agent/division/set_status/<status>/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1035, 1015, '', '代理商删除', 'admin', '', '', 'agent/division/del/<type>/<uid>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1036, 1016, '', '代理商申请列表', 'admin', '', '', 'agent/division/agent_apply/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1037, 1016, '', '代理商审核', 'admin', '', '', 'agent/division/examine_apply/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1038, 1016, '', '提交审核', 'admin', '', '', 'agent/division/apply_agent/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1039, 1016, '', '删除审核', 'admin', '', '', 'agent/division/del_apply/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1040, 1017, '', '协议详情', 'admin', '', '', 'agent/division/agent_agreement/info', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1017', 2, '', 0, '', 0),
(1041, 1017, '', '保存协议', 'admin', '', '', 'agent/division/agent_agreement/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1017', 2, '', 0, '', 0),
(1042, 1023, '', '渠道码分类列表', 'admin', '', '', 'app/wechat_qrcode/cate/list', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1043, 1023, '', '渠道码分类添加编辑表单', 'admin', '', '', 'app/wechat_qrcode/cate/create/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1044, 1023, '', '渠道码分类保存', 'admin', '', '', 'app/wechat_qrcode/cate/save', 'POST', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1045, 1023, '', '渠道码分类删除', 'admin', '', '', 'app/wechat_qrcode/cate/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1046, 1023, '', '保存渠道码', 'admin', '', '', 'app/wechat_qrcode/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1047, 1023, '', '渠道码详情', 'admin', '', '', 'app/wechat_qrcode/info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1048, 1023, '', '渠道码列表', 'admin', '', '', 'app/wechat_qrcode/list', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1049, 1023, '', '删除渠道码', 'admin', '', '', 'app/wechat_qrcode/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1050, 1023, '', '渠道码状态切换', 'admin', '', '', 'app/wechat_qrcode/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1051, 1023, '', '渠道码用户列表', 'admin', '', '', 'app/wechat_qrcode/user_list/<qid>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1052, 1023, '', '获取用户标签', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1053, 27, '', '充值配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/recharge', '27', 1, '', 0, 'marketing-recharge-index', 0),
(1054, 1009, '', '隐私协议', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/app/agreement', '135/1009', 1, '', 0, 'admin-app-agreement', 0),
(1055, 1009, '', '版本管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/app/version', '135/1009', 1, '', 0, 'admin-app-version', 0),
(1056, 12, '', '第三方接口配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config', '12', 1, '', 0, 'setting-other', 0),
(1057, 1056, '', '小票打印配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config/print/2/21', '12/1056', 1, '', 0, 'setting-other-print', 0),
(1058, 1056, '', '商品采集配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config/copy/2/41', '12/1056', 1, '', 0, 'setting-other-copy', 0),
(1059, 1056, '', '物流查询配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config/logistics/2/64', '12/1056', 1, '', 0, 'setting-other-logistics', 0),
(1060, 1056, '', '电子面单配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config/electronic/2/66', '12/1056', 1, '', 0, 'setting-other-electronic', 0)
SQL
            ],
            [
                'code' => 444,
                'type' => 6,
                'table' => "agreement",
                'whereTable' => "",
                'findSql' => "select id from @table where `title` = '隐私协议'",
                'whereSql' => "",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 3, '隐私协议', '', 0, 1, 1650440124)"
            ],
            [
                'code' => 444,
                'type' => 6,
                'table' => "agreement",
                'whereTable' => "",
                'findSql' => "select id from @table where `title` = '用户协议'",
                'whereSql' => "",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 4, '用户协议', '', 0, 1, 1650440124)"
            ],
            [
                'code' => 444,
                'type' => 6,
                'table' => "agreement",
                'whereTable' => "",
                'findSql' => "select id from @table where `title` = '注销协议'",
                'whereSql' => "",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 5, '注销协议', '', 0, 1, 1650440124)"
            ],
            [
                'code' => 444,
                'type' => 6,
                'table' => "agreement",
                'whereTable' => "",
                'findSql' => "select id from @table where `title` = '积分协议'",
                'whereSql' => "",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 6, '积分协议', '', 0, 1, 1650440124)"
            ],
            [
                'code' => 444,
                'type' => 6,
                'table' => "system_config_tab",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `eng_title` = 'friend_pay'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='pay_config'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, @tabId, '好友代付', 'friend_pay', 1, 0, '', 0, 0)"
            ],
            [
                'code' => 444,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'create_wechat_user'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='wechat'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 'create_wechat_user', 'radio', 'input', @tabId, '1=>开启\n0=>关闭', 1, '', 0, 0, '0', '关注公众号是否生成用户', '关注公众号是否生成用户', 0, 1)"
            ],
            [
                'code' => 444,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'friend_pay_status'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='friend_pay'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 'friend_pay_status', 'radio', 'input', @tabId, '1=>开启\r\n0=>关闭', '1', '', '0', '0', '1', '好友代付开关', '好友代付开关', '0', '1')"
            ],
            [
                'code' => 444,
                'type' => 6,
                'table' => "system_config",
                'whereTable' => "system_config_tab",
                'findSql' => "select id from @table where `menu_name` = 'division_status'",
                'whereSql' => "SELECT id as tabId FROM `@whereTable` WHERE `eng_title`='brokerage_type'",
                'sql' => "INSERT INTO `@table` VALUES (NULL, 'division_status', 'radio', 'input', @tabId, '1=>开启\r\n0=>关闭', '1', '', '0', '0', '1', '事业部开关', '事业部开关', '0', '1')"
            ],
            [
                'code' => 444,
                'type' => -1,
                'table' => "system_menus",
                'sql' => "truncate table `@table`"
            ],
            [
                'code' => 444,
                'type' => -1,
                'table' => "system_menus",
                'sql' => <<<SQL
INSERT INTO `@table` (`id`, `pid`, `icon`, `menu_name`, `module`, `controller`, `action`, `api_url`, `methods`, `params`, `sort`, `is_show`, `is_show_path`, `access`, `menu_path`, `path`, `auth_type`, `header`, `is_header`, `unique_auth`, `is_del`) VALUES
(1, 0, 'md-basket', '商品', 'admin', 'product', 'index', '', '', '[]', 126, 1, 0, 1, '/admin/product', '', 1, '0', 1, 'admin-product', 0),
(2, 1, '', '商品管理', 'admin', 'product.product', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_list', '', 1, '', 0, 'admin-store-storeProuduct-index', 0),
(3, 1, '', '商品分类', 'admin', 'product.storeCategory', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_classify', '', 1, 'product', 0, 'admin-store-storeCategory-index', 0),
(4, 0, 'md-cart', '订单', 'admin', 'order', 'index', '', '', '[]', 110, 1, 0, 1, '/admin/order', '', 1, 'home', 1, 'admin-order', 0),
(5, 4, '', '订单管理', 'admin', 'order.store_order', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/order/list', '', 1, 'order', 0, 'admin-order-storeOrder-index', 0),
(6, 1, '', '商品评论', 'admin', 'store.store_product_reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/product/product_reply', '', 1, 'product', 0, 'product-product-reply', 0),
(7, 0, 'md-home', '主页', 'admin', 'index', '', '', '', '[]', 127, 1, 0, 1, '/admin/home/', '', 1, 'home', 1, 'admin-index-index', 0),
(9, 0, 'md-person', '用户', 'admin', 'user.user', '', '', '', '[]', 100, 1, 0, 1, '/admin/user', '', 1, 'user', 1, 'admin-user', 0),
(10, 9, '', '用户管理', 'admin', 'user.user', 'index', '', '', '[]', 10, 1, 0, 1, '/admin/user/list', '', 1, 'user', 1, 'admin-user-user-index', 0),
(11, 9, '', '用户等级', 'admin', 'user.user_level', 'index', '', '', '[]', 7, 1, 0, 1, '/admin/user/level', '', 1, 'user', 1, 'user-user-level', 0),
(12, 0, 'md-settings', '设置', 'admin', 'setting.system_config', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting', '', 1, 'setting', 1, 'admin-setting', 0),
(14, 12, '', '管理权限', 'admin', 'setting.system_admin', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/auth/list', '', 1, 'setting', 1, 'setting-system-admin', 0),
(19, 14, '', '角色管理', 'admin', 'setting.system_role', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_role/index', '', 1, 'setting', 1, 'setting-system-role', 0),
(20, 14, '', '管理员列表', 'admin', 'setting.system_admin', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_admin/index', '', 1, 'setting', 0, 'setting-system-list', 0),
(21, 14, '', '权限规则', 'admin', 'setting.system_menus', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_menus/index', '', 1, 'setting', 0, 'setting-system-menus', 0),
(22, 1, '', '产品添加', 'admin', 'store.store_product', 'save', '', '', '[]', 1, 1, 1, 1, '/admin/product/add_product', '', 1, 'product', 1, 'product-product-save', 0),
(23, 12, '', '系统设置', 'admin', 'setting.system_config', 'index', '', '', '[]', 10, 1, 0, 1, '/admin/setting/system_config', '', 1, 'setting', 1, 'setting-system-config', 0),
(25, 0, 'md-hammer', '维护', 'admin', 'system', '', '', '', '[]', -1, 1, 0, 1, '/admin/system', '', 1, 'setting', 1, 'admin-system', 0),
(26, 0, 'ios-people', '分销', 'admin', 'agent', '', '', '', '[]', 90, 1, 0, 1, '/admin/agent', '', 1, 'user', 1, 'admin-agent', 0),
(27, 0, 'ios-paper-plane', '营销', 'admin', 'marketing', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing', '', 1, 'home', 1, 'admin-marketing', 0),
(28, 26, '', '分销设置', 'admin', 'setting.system_config', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/system_config_retail/2/9', '', 1, 'setting', 0, 'setting-system-config', 0),
(29, 26, '', '分销员管理', 'admin', 'agent.agent_manage', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/agent/agent_manage/index', '', 1, 'user', 0, 'agent-agent-manage', 0),
(30, 27, '', '优惠券', 'admin', 'marketing.store_coupon', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_coupon', '', 1, 'marketing', 1, 'marketing-store_coupon-index', 0),
(31, 27, '', '砍价管理', 'admin', 'marketing.store_bargain', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_bargain', '', 1, 'marketing', 0, 'marketing-store_bargain-index', 0),
(32, 27, '', '拼团管理', 'admin', 'marketing.store_combination', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_combination', '', 1, 'marketing', 0, 'marketing-store_combination-index', 0),
(33, 27, '', '秒杀管理', 'admin', 'marketing.store_seckill', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/store_seckill', '', 1, 'marketing', 0, 'marketing-store_seckill-index', 0),
(34, 27, '', '积分管理', 'admin', 'marketing.user_point', '', '', '', '[]', 1, 1, 0, 1, '/admin/marketing/user_point', '', 1, 'marketing', 1, 'marketing-user_point-index', 0),
(35, 0, 'logo-usd', '财务', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance', '', 1, 'home', 0, 'admin-finance', 0),
(36, 35, '', '财务操作', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_extract', '', 1, 'finance', 0, 'finance-user_extract-index', 0),
(37, 35, '', '财务记录', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_recharge', '', 1, 'finance', 0, 'finance-user-recharge-index', 0),
(38, 35, '', '佣金记录', 'admin', 'finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/finance', '', 1, 'finance', 0, 'finance-finance-index', 0),
(39, 36, '', '提现申请', 'admin', 'finance.user_extract', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_extract/index', '', 1, 'finance', 0, 'finance-user_extract', 0),
(40, 37, '', '充值记录', 'admin', 'finance.user_recharge', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/user_recharge/index', '', 1, 'finance', 0, 'finance-user-recharge', 0),
(42, 38, '', '佣金记录', 'admin', 'finance.finance', '', '', '', '[]', 1, 1, 0, 1, '/admin/finance/finance/commission', '', 1, 'finance', 0, 'finance-finance-commission', 0),
(43, 0, 'ios-book', '内容', 'admin', 'cms', '', '', '', '[]', 1, 1, 0, 1, '/admin/cms', '', 1, 'home', 0, 'admin-cms', 0),
(44, 43, '', '文章管理', 'admin', 'cms.article', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/cms/article/index', '', 1, 'cms', 0, 'cms-article-index', 0),
(45, 43, '', '文章分类', 'admin', 'cms.article_category', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/cms/article_category/index', '', 1, 'cms', 0, 'cms-article-category', 0),
(46, 43, '', '文章添加', 'admin', 'cms.article', 'add_article', '', '', '[]', 0, 1, 1, 1, '/admin/cms/article/add_article', '', 1, 'cms', 1, 'cms-article-creat', 0),
(47, 65, '', '系统日志', 'admin', 'system.system_log', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_log/index', '', 1, 'system', 0, 'system-maintain-system-log', 0),
(48, 7, '', '控制台', 'admin', 'index', 'index', '', '', '[]', 127, 1, 0, 1, '/admin/home/index', '', 1, 'home', 0, '', 1),
(56, 25, '', '开发配置', 'admin', 'system', '', '', '', '[]', 10, 1, 0, 1, '/admin/system/config', '', 1, 'system', 1, 'system-config-index', 0),
(57, 65, '', '刷新缓存', 'admin', 'system', 'clear', '', '', '[]', 1, 1, 0, 1, '/admin/system/maintain/clear/index', '', 1, 'system', 1, 'system-clear', 0),
(64, 65, '', '文件校验', 'admin', 'system.system_file', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_file/index', '', 1, 'system', 0, 'system-maintain-system-file', 0),
(65, 25, '', '安全维护', 'admin', 'system', '', '', '', '[]', 7, 1, 0, 1, '/admin/system/maintain', '', 1, 'system', 1, 'system-maintain-index', 0),
(66, 65, '', '清除数据', 'admin', 'system.system_cleardata', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_cleardata/index', '', 1, 'system', 0, 'system-maintain-system-cleardata', 0),
(67, 65, '', '数据备份', 'admin', 'system.system_databackup', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/system_databackup/index', '', 1, 'system', 0, 'system-maintain-system-databackup', 0),
(69, 135, '', '公众号', 'admin', 'wechat', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat', '', 1, 'app', 1, 'admin-wechat', 0),
(70, 30, '', '优惠券模板', 'admin', 'marketing.store_coupon', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/index', '', 1, 'marketing', 1, 'marketing-store_coupon', 0),
(71, 30, '', '优惠券列表', 'admin', 'marketing.store_coupon_issue', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_coupon_issue/index', '', 1, 'marketing', 1, 'marketing-store_coupon_issue', 0),
(72, 30, '', '用户领取记录', 'admin', 'marketing.store_coupon_user', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_coupon_user/index', '', 1, 'marketing', 1, 'marketing-store_coupon_user', 0),
(74, 31, '', '砍价商品', 'admin', 'marketing.store_bargain', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_bargain/index', '', 1, 'marketing', 1, 'marketing-store_bargain', 0),
(75, 32, '', '拼团商品', 'admin', 'marketing.store_combination', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/index', '', 1, 'marketing', 1, 'marketing-store_combination', 0),
(76, 32, '', '拼团列表', 'admin', 'marketing.store_combination', 'combina_list', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/combina_list', '', 1, 'marketing', 0, 'marketing-store_combination-combina_list', 0),
(77, 33, '', '秒杀商品', 'admin', 'marketing.store_seckill', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_seckill/index', '', 1, 'marketing', 1, 'marketing-store_seckill', 0),
(78, 33, '', '秒杀配置', 'admin', 'marketing.store_seckill', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_seckill_data/index/49', '', 1, 'marketing', 1, 'marketing-store_seckill-data', 0),
(79, 34, '', '积分配置', 'admin', 'setting.system_config/index.html', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/integral/system_config/3/11', '', 1, 'marketing', 1, 'marketing-integral-system_config', 0),
(90, 32, '', '拼团添加', 'admin', 'marketing.store_combination', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_combination/add_commodity/:id', '', 1, 'marketing', 0, '', 1),
(91, 69, '', '公众号配置', 'admin', 'application.wechat', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/setting', '', 1, 'app', 0, '', 1),
(92, 69, '', '微信菜单', 'admin', 'application.wechat_menus', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/setting/menus/index', '', 1, 'app', 0, 'application-wechat-menus', 0),
(94, 12, '', '一号通', 'admin', 'setting.sms_config', '', '', '', '[]', 8, 1, 0, 1, '/admin/setting/sms/sms_config/index', '', 1, 'setting', 1, 'setting-sms', 0),
(95, 94, '', '账户管理', 'admin', 'sms.sms_config', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_config/index', '', 1, 'setting', 1, 'setting-sms-sms-config', 0),
(96, 94, '', '短信模板', 'admin', 'sms.sms_template_apply', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_template_apply/index', '', 1, 'setting', 0, 'setting-sms-config-template', 0),
(97, 94, '', '套餐购买', 'admin', 'sms.sms_pay', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_pay/index', '', 1, 'setting', 1, 'setting-sms-sms-template', 0),
(99, 1, '', '商品规格', 'admin', 'store.store_product', 'index', '', '', '[]', 1, 1, 0, 1, '/admin/product/product_attr', '', 1, 'product', 1, 'product-product-attr', 0),
(105, 22, '', '添加产品保存', 'admin', 'store.store_product', 'save', 'product/product/<id>', 'POST', '[]', 0, 0, 0, 1, '/admin/product/save', '', 2, 'product', 0, 'product-save', 0),
(108, 2, '', '产品列表', 'admin', 'product.product', 'index', 'product/product', 'GET', '[]', 20, 0, 0, 1, '/admin/product/product', '1/2', 2, 'product', 1, 'product-product-index', 0),
(109, 69, '', '图文管理', 'admin', 'wechat.wechat_news_category', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/news_category/index', '', 1, 'app', 0, 'wechat-wechat-news-category-index', 0),
(110, 69, '', '图文添加', 'admin', 'wechat.wechat_news_category', 'save', '', '', '[]', 0, 1, 1, 1, '/admin/app/wechat/news_category/save', '', 1, 'app', 1, 'wechat-wechat-news-category-save', 0),
(111, 56, '', '配置分类', 'admin', 'setting.system_config_tab', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/config/system_config_tab/index', '', 1, 'system', 0, 'system-config-system_config-tab', 0),
(112, 56, '', '组合数据', 'admin', 'setting.system_group', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/system/config/system_group/index', '', 1, 'system', 0, 'system-config-system_config-group', 0),
(113, 114, '', '微信关注回复', 'admin', 'wechat.reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/follow/subscribe', '', 1, 'app', 0, 'wechat-wechat-reply-subscribe', 0),
(114, 69, '', '自动回复', 'admin', 'wechat.reply', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply', '', 1, 'app', 0, 'wechat-wechat-reply-index', 0),
(115, 114, '', '关键字回复', 'admin', 'wechat.reply', 'keyword', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/keyword', '', 1, 'app', 0, 'wechat-wechat-reply-keyword', 0),
(116, 114, '', '无效关键词回复', 'admin', 'wechat.reply', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app/wechat/reply/index/default', '', 1, 'app', 0, 'wechat-wechat-reply-default', 0),
(125, 56, '', '配置列表', 'admin', 'system.config', 'index', '', '', '[]', 0, 0, 1, 1, '/admin/system/config/system_config_tab/list', '', 1, 'system', 1, 'system-config-system_config_tab-list', 0),
(126, 56, '', '组合数据列表', 'admin', 'system.system_group', 'list', '', '', '[]', 0, 1, 1, 1, '/admin/system/config/system_group/list', '', 1, 'system', 1, 'system-config-system_config-list', 0),
(128, 656, '', '数据配置', 'admin', 'setting.system_group_data', 'index', '', '', '[]', 2, 1, 0, 1, '/admin/setting/system_visualization_data', '12/656', 1, 'system', 1, 'admin-setting-system_visualization_data', 0),
(134, 114, '', '关键字添加', 'admin', '', 'index', '', '', '[]', 0, 1, 1, 1, '/admin/app/wechat/reply/keyword/save', '', 1, 'app', 1, 'wechat-wechat-reply-save', 0),
(135, 0, 'md-cube', '应用', 'admin', 'app', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/app', '', 1, 'app', 1, 'admin-app', 0),
(144, 303, '', '提货点设置', 'admin', 'merchant.system_store', 'index', '', '', '[]', 5, 1, 0, 1, '/admin/setting/merchant/system_store/index', '', 1, '', 0, 'setting-system-config-merchant', 0),
(145, 25, '', '物流公司', 'admin', 'freight.express', 'index', '', '', '[]', 4, 1, 0, 1, '/admin/setting/freight/express/index', '', 1, '', 0, 'setting-freight-express', 0),
(146, 31, '', '添加砍价', 'admin', '/marketing.store_bargain', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_bargain/create', '', 1, '', 0, 'marketing-store_bargain-create', 0),
(147, 32, '', '添加拼团', 'admin', 'marketing.store_combination', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_combination/create', '', 1, '', 0, 'marketing-store_combination-create', 0),
(148, 33, '', '添加秒杀', 'admin', 'marketing.store_seckill', 'create', '', '', '[]', 0, 1, 1, 1, '/admin/marketing/store_seckill/create', '', 1, '', 0, 'marketing-store_seckill-create', 0),
(154, 34, '', '签到配置', 'admin', 'setting.system_group_data', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/sign', '', 1, '', 0, 'marketing-sign-index', 0),
(165, 0, 'md-chatboxes', '客服', 'admin', 'setting.storeService', 'index', '', '', '[]', 2, 1, 0, 1, '/admin/kefu', '', 1, '', 0, 'setting-store-service', 0),
(166, 25, '', '日志', 'admin', '', '', '', '', '[]', 0, 1, 1, 1, '/admin/system/log', '', 1, '', 0, 'system-log', 0),
(169, 577, '', '商品删除', 'admin', 'product', '商品删除', 'product/product/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '0', 1, '', 0),
(170, 3, '', '分类列表', 'admin', '', '', 'product/category', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/category', '', 2, '', 0, '', 0),
(171, 578, '', '删除分类', 'admin', '', '', 'product/category/<id>', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/category/<id>', '', 2, '', 0, '', 0),
(172, 578, '', '修改分类', 'admin', '', '', 'product/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/<id>', '', 2, '', 0, '', 0),
(173, 578, '', '新增分类', 'admin', '', '', 'product/category', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/category', '', 2, '', 0, 'product-save-cate', 0),
(174, 578, '', '分类状态', 'admin', '', '', 'product/category/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/set_show/<id>/<is_show>', '', 2, '', 0, '', 0),
(175, 578, '', '快速编辑', 'admin', '', '', 'product/category/set_category/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/category/set_category/<id>', '', 2, '', 0, '', 0),
(176, 578, '', '分类表单添加', 'admin', '', '', 'product/category/create', 'GET', '[]', 0, 0, 0, 1, '/admincategory/create', '', 2, '', 0, '', 0),
(177, 578, '', '分类表单编辑', 'admin', '', '', 'product/category/<id>', 'GET', '[]', 0, 0, 0, 1, '/admincategory/<id>/edit', '', 2, '', 0, '', 0),
(178, 3, '', '分类树形列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '/admincategory/tree/:type', '', 2, '', 0, '', 0),
(179, 577, '', '产品状态', 'admin', '', '', 'product/product/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/set_show/<id>/<is_show>', '', 2, '', 0, '', 0),
(180, 577, '', '快速编辑', 'admin', '', '', 'product/product/set_product/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/product/set_product/<id>', '', 2, '', 0, '', 0),
(181, 577, '', '批量上架商品', 'admin', '', '', 'product/product/product_show', 'PUT', '[]', 0, 0, 0, 1, '/adminproduct/product/product_show', '', 2, '', 0, 'product-product-product_show', 0),
(182, 577, '', '采集商品', 'admin', '', '', 'product/copy', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/crawl', '', 2, '', 0, 'product-crawl-save', 0),
(183, 577, '', '采集商品保存', 'admin', '', '', 'product/crawl/save', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/crawl/save', '', 2, '', 0, '', 0),
(184, 579, '', '虚拟评论表单', 'admin', '', '', 'product/reply/fictitious_reply/<product_id>', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/reply/fictitious_reply', '', 2, '', 0, '', 0),
(185, 579, '', '保存虚拟评论', 'admin', '', '', 'product/reply/save_fictitious_reply', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/reply/save_fictitious_reply', '', 2, '', 0, 'product-reply-save_fictitious_reply', 0),
(186, 22, '', '获取属性模板列表', 'admin', '', '', 'product/product/get_rule', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/get_rule', '', 2, '', 0, '', 0),
(187, 22, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/get_template', '', 2, '', 0, '', 0),
(188, 579, '', '删除评论', 'admin', '', '', 'product/reply/<id>', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/reply/<id>', '', 2, '', 0, '', 0),
(189, 579, '', '评论回复', 'admin', '', '', 'product/reply/set_reply/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminreply/set_reply/<id>', '', 2, '', 0, '', 0),
(190, 6, '', '评论列表', 'admin', '', '', 'product/reply', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/reply', '', 2, '', 0, '', 0),
(191, 22, '', '生成属性', 'admin', '', '', 'product/generate_attr/<id>/<type>', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/generate_attr/<id>', '', 2, '', 0, '', 0),
(192, 2, '', '商品列表头部', 'admin', '', '', 'product/product/type_header', 'GET', '[]', 10, 0, 0, 1, '/adminproduct/product/type_header', '', 2, '', 0, '', 0),
(193, 577, '', '商品列表插件', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/list', '', 2, '', 0, '', 0),
(194, 99, '', '属性规则列表', 'admin', '', '', 'product/product/rule', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/rule', '', 2, '', 0, '', 0),
(195, 580, '', '保存修改规则', 'admin', '', '', 'product/product/rule/<id>', 'POST', '[]', 0, 0, 0, 1, '/adminproduct/rule/<id>', '', 2, '', 0, 'product-rule-save', 0),
(196, 580, '', '规则详情', 'admin', '', '', 'product/product/rule/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminproduct/product/rule/<id>', '', 2, '', 0, '', 0),
(197, 580, '', '删除规则', 'admin', '', '', 'product/product/rule/delete', 'DELETE', '[]', 0, 0, 0, 1, '/adminproduct/product/rule/delete', '', 2, '', 0, 'product-product-rule-delete', 0),
(198, 5, '', '订单列表', 'admin', '', '', 'order/list', 'GET', '[]', 0, 0, 0, 1, '/adminorder/list', '', 2, '', 0, '', 0),
(199, 5, '', '订单数据', 'admin', '', '', 'order/chart', 'GET', '[]', 0, 0, 0, 1, '/adminorder/chart', '', 2, '', 0, '', 0),
(200, 581, '', '订单核销', 'admin', '', '', 'order/write', 'POST', '[]', 0, 0, 0, 1, '/adminorder/write', '', 2, '', 0, 'order-write', 0),
(201, 215, '', '订单修改表格', 'admin', '', '', 'order/edit/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/edit/<id>', '', 2, '', 0, '', 0),
(202, 215, '', '订单修改', 'admin', '', '', 'order/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/update/<id>', '', 2, '', 0, '', 0),
(203, 581, '', '订单收货', 'admin', '', '', 'order/take/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/take/<id>', '', 2, '', 0, '', 0),
(204, 209, '', '订单发货', 'admin', '', '', 'order/delivery/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/delivery/<id>', '', 2, '', 0, '', 0),
(205, 214, '', '订单退款表格', 'admin', '', '', 'order/refund/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/refund/<id>', '', 2, '', 0, '', 0),
(206, 214, '', '订单退款', 'admin', '', '', 'order/refund/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/refund/<id>', '', 2, '', 0, '', 0),
(207, 581, '', '订单物流信息', 'admin', '', '', 'order/express/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/express/<id>', '', 2, '', 0, '', 0),
(208, 209, '', '物流公司列表', 'admin', '', '', 'order/express_list', 'GET', '[]', 0, 0, 0, 1, '/adminorder/express_list', '', 2, '', 0, '', 0),
(209, 581, '', '发货', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/delivery', '', 1, '', 0, '', 0),
(210, 767, '', '附加权限', 'admin', '', '', '', 'GET', '[]', 99, 1, 0, 1, '/adminorder/info/<id>', '', 2, '', 0, '', 0),
(211, 213, '', '订单配送表格', 'admin', '', '', 'order/distribution/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/distribution/<id>', '', 2, '', 0, '', 0),
(212, 213, '', '修改配送信息', 'admin', '', '', 'order/distribution/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/distribution/<id>', '', 2, '', 0, '', 0),
(213, 581, '', '订单配送', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/distribution', '', 1, '', 0, '', 0),
(214, 581, '', '退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/refund', '', 1, '', 0, '', 0),
(215, 581, '', '修改', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/update', '', 1, '', 0, '', 0),
(216, 581, '', '不退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/no_refund', '', 1, '', 0, '', 0),
(217, 216, '', '不退款表格', 'admin', '', '', 'order/no_refund/<id>', 'GET', '[]', 0, 0, 0, 1, '/adminorder/no_refund/<id>', '', 2, '', 0, '', 0),
(218, 216, '', '不退款理由修改', 'admin', '', '', 'order/no_refund/<id>', 'PUT', '[]', 0, 0, 0, 1, '/adminorder/no_refund/<id>', '', 2, '', 0, '', 0),
(219, 581, '', '线下支付', 'admin', '', '', 'order/pay_offline/<id>', 'POST', '[]', 98, 0, 0, 1, '', '', 2, '', 0, '', 0),
(220, 581, '', '退积分', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/adminorder/refund_integral', '', 1, '', 0, '', 0),
(221, 220, '', '退积分表单', 'admin', '', '', 'order/refund_integral/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(222, 220, '', '修改退积分', 'admin', '', '', 'order/refund_integral/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(223, 581, '', '订单备注', 'admin', '', '', 'order/remark/<id>', 'PUT', '[]', 97, 0, 0, 1, '', '', 2, '', 0, '', 0),
(224, 209, '', '获取电子面单信息', 'admin', '', '', 'order/express/temp', 'GET', '[]', 96, 0, 1, 1, '', '4/5/581/209', 2, '', 0, '', 0),
(225, 581, '', '订单删除', 'admin', '', '', 'order/del/<id>', 'DELETE', '[]', 95, 0, 0, 1, '', '', 2, '', 0, '', 0),
(226, 581, '', '批量删除订单', 'admin', '', '', 'order/dels', 'POST', '[]', 100, 0, 0, 1, '', '4/5/581', 2, '', 0, 'order-dels', 0),
(227, 9, '', '用户分组', 'admin', 'user.user_group', 'index', '', '', '[]', 9, 1, 0, 1, '/admin/user/group', '', 1, 'user', 1, 'user-user-group', 0),
(229, 25, '', '城市数据', 'admin', 'setting.system_city', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/freight/city/list', '', 1, 'setting', 1, 'setting-system-city', 0),
(230, 303, '', '运费模板', 'admin', 'setting.shipping_templates', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/freight/shipping_templates/list', '', 1, 'setting', 1, 'setting-shipping-templates', 0),
(231, 767, '', '发票列表', 'admin', '', '', 'order/invoice/list', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-index', 0),
(232, 585, '', '用户详情', 'admin', '', '', 'user/one_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(233, 585, '', '创建用户表单', 'admin', '', '', 'user/user/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(234, 585, '', '修改用户信息表单', 'admin', '', '', 'user/user/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(235, 585, '', '获取用户信息', 'admin', '', '', 'user/user/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(236, 585, '', '修改用户信息', 'admin', '', '', 'user/user/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(238, 585, '', '发送优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/coupon', '', 1, '', 0, 'admin-user-coupon', 0),
(239, 238, '', '优惠卷列表', 'admin', '', '', 'marketing/coupon/grant', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(240, 238, '', '发送优惠卷', 'admin', '', '', 'marketing/coupon/user/grant', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(241, 585, '', '发送图文', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/wechat/news/', '', 1, '', 0, 'admin-wechat-news', 0),
(242, 241, '', '图文列表', 'admin', '', '', 'app/wechat/news', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(243, 241, '', '发送图文', 'admin', '', '', 'app/wechat/push', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(244, 585, '', '批量用户分组', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/group_set/', '', 1, '', 0, 'admin-user-group_set', 0),
(245, 244, '', '用户分组表单', 'admin', '', '', 'user/set_group/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(246, 244, '', '保存分组', 'admin', '', '', 'user/set_group', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(247, 586, '', '添加修改用户等级', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/level_add', '', 1, '', 0, 'admin-user-level_add', 0),
(248, 247, '', '添加会员等级表单', 'admin', '', '', 'user/user_level/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(249, 247, '', '保存会员等级', 'admin', '', '', 'user/user_level', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(250, 11, '', '用户等级列表', 'admin', '', '', 'user/user_level/vip_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(251, 586, '', '用户等级是否显示', 'admin', '', '', 'user/user_level/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(252, 586, '', '删除用户等级', 'admin', '', '', 'user/user_level/delete/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(253, 586, '', '等级任务', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/user_level', '', 1, '', 0, '', 0),
(254, 253, '', '等级任务列表', 'admin', '', '', 'user/user_level/task/<level_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(255, 253, '', '等级任务显示隐藏', 'admin', '', '', 'user/user_level/set_task_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(256, 253, '', '等级任务是否必达', 'admin', '', '', 'user/user_level/set_task_must/<id>/<is_must>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(257, 253, '', '添加修改等级任务', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(258, 257, '', '添加等级任务表单', 'admin', '', '', 'user/user_level/create_task', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(259, 257, '', '保存修改任务', 'admin', '', '', 'user/user_level/save_task', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(260, 253, '', '删除等级任务', 'admin', '', '', 'user/user_level/delete_task/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(261, 227, '', '用户分组列表', 'admin', '', '', 'user/user_group/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(262, 227, '', '删除用户分组', 'admin', '', '', 'user/user_group/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(263, 227, '', '添加修改用户分组', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/group', '', 1, '', 0, 'admin-user-group', 0),
(264, 263, '', '添加修改用户分组表单', 'admin', '', '', 'user/user_group/add/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(265, 263, '', '保存修改用户分组', 'admin', '', '', 'user/user_group/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(266, 29, '', '分销员列表', 'admin', '', '', 'agent/index', 'GET', '[]', 0, 0, 0, 1, '', '26/29', 2, '', 0, '', 0),
(267, 584, '', '分销员数据', 'admin', '', '', 'agent/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(268, 29, '', '推广人列表', 'admin', '', '', 'agent/stair', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(269, 29, '', '推广人订单列表', 'admin', '', '', 'agent/stair/order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(270, 584, '', '清除推广人', 'admin', '', '', 'agent/stair/delete_spread/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(271, 584, '', '推广二维码', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(272, 271, '', '公众号推广二维码', 'admin', '', '', 'agent/look_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(273, 271, '', '小程序推广二维码', 'admin', '', '', 'agent/look_xcx_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(274, 583, '', '添加优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/add', '', 1, '', 0, 'admin-marketing-store_coupon-add', 0),
(275, 274, '', '添加优惠卷表单', 'admin', '', '', 'marketing/coupon/create/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(276, 274, '', '保存优惠卷', 'admin', '', '', 'marketing/coupon/save_coupon', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(277, 583, '', '发布优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon/push', '', 1, '', 0, 'admin-marketing-store_coupon-push', 0),
(278, 277, '', '发布优惠卷表单', 'admin', '', '', 'marketing/coupon/issue/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(279, 277, '', '发布优惠卷', 'admin', '', '', 'marketing/coupon/issue/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(280, 583, '', '立即失效', 'admin', '', '', 'marketing/coupon/status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(281, 583, '', '删除优惠卷', 'admin', '', '', 'marketing/coupon/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(282, 71, '', '优惠卷已发布列表', 'admin', '', '', 'marketing/coupon/released', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(283, 71, '', '领取记录', 'admin', '', '', 'marketing/coupon/released/issue_log/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(284, 71, '', '删除优惠卷', 'admin', '', '', 'marketing/coupon/released/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(285, 71, '', '修改状态', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(286, 285, '', '修改状态表单', 'admin', '', '', 'marketing/coupon/released/<id>/status', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(287, 285, '', '保存修改状态', 'admin', '', '', 'marketing/coupon/released/status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(290, 405, '', '审核状态通过', 'admin', '', '', 'finance/extract/adopt/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(291, 405, '', '拒绝申请', 'admin', '', '', 'finance/extract/refuse/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(292, 405, '', '提现编辑', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(293, 292, '', '编辑表单', 'admin', '', '', 'finance/extract/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(294, 292, '', '保存修改', 'admin', '', '', 'finance/extract/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(295, 40, '', '充值列表', 'admin', '', '', 'finance/recharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(296, 40, '', '充值数据', 'admin', '', '', 'finance/recharge/user_recharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(297, 40, '', '退款', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(298, 297, '', '获取退款表单', 'admin', '', '', 'finance/recharge/<id>/refund_edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(299, 297, '', '保存退款', 'admin', '', '', 'finance/recharge/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(300, 144, '', '提货点', 'admin', 'merchant.system_store', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_store/list', '', 1, 'setting', 1, 'setting-merchant-system-store', 0),
(301, 144, '', '核销员', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_store_staff/index', '', 1, 'setting', 1, 'setting-merchant-system-store-staff', 0),
(302, 144, '', '核销订单', 'admin', '', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/merchant/system_verify_order/index', '', 1, 'setting', 1, 'setting-merchant-system-verify-order', 0),
(303, 12, '', '发货设置', 'admin', 'setting', 'index', '', '', '[]', 0, 1, 0, 1, '/admin/setting/freight', '', 1, '', 0, '', 0),
(304, 303, '', '物流配置', 'admin', 'setting.systemConfig', 'index', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_config_logistics/3/10', '', 1, '', 0, 'setting-system-config-logistics', 0),
(305, 44, '', '文章列表', 'admin', '', '', 'cms/cms', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(306, 409, '', '文章分类', 'admin', '', '', 'cms/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(307, 42, '', '佣金记录列表', 'admin', '', '', 'finance/finance/commission_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(308, 42, '', '用户详情', 'admin', 'finance.finance', 'user_info', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(309, 308, '', '获取用户信息', 'admin', '', '', 'finance/finance/user_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(310, 308, '', '佣金详细列表', 'admin', '', '', 'finance/finance/extract_list/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(313, 23, '', '获取头部导航', 'admin', '', '', 'setting/config/header_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(314, 23, '', '获取配置列表', 'admin', '', '', 'setting/config/edit_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(315, 23, '', '修改配置', 'admin', '', '', 'setting/config/save_basics', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(316, 423, '', '添加客服', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/add', '', 1, '', 0, 'setting-store_service-add', 0),
(317, 316, '', '客服用户列表', 'admin', '', '', 'app/wechat/kefu/add', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(318, 316, '', '保存客服', 'admin', '', '', 'app/wechat/kefu', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(319, 423, '', '聊天记录', 'admin', '', '', 'app/wechat/kefu/record/', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(320, 423, '', '编辑客服', 'admin', '', '', '', '', '[]', 80, 0, 0, 1, '/admin/setting/store_service/edit', '', 1, '', 0, 'setting-store_service-edit', 0),
(321, 423, '', '删除客服', 'admin', '', '', 'app/wechat/kefu/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(322, 423, '', '客服是否开启', 'admin', '', '', 'app/wechat/kefu/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(323, 320, '', '编辑客服表单', 'admin', '', '', 'app/wechat/kefu/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(324, 320, '', '修改客服', 'admin', '', '', 'app/wechat/kefu/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(325, 19, '', '添加身份', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_role/add', '', 1, '', 0, 'setting-system_role-add', 0),
(326, 325, '', '添加身份表单', 'admin', '', '', 'setting/role/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(327, 325, '', '添加修改身份', 'admin', '', '', 'setting/role/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(328, 325, '', '修改身份表单', 'admin', '', '', 'setting/role/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(329, 19, '', '修改身份状态', 'admin', '', '', 'setting/role/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(330, 19, '', '删除身份', 'admin', '', '', 'setting/role/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(331, 20, '', '添加管理员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_admin/add', '', 1, '', 0, 'setting-system_admin-add', 0),
(332, 331, '', '添加管理员表单', 'admin', '', '', 'setting/admin/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(333, 331, '', '添加管理员', 'admin', '', '', 'setting/admin', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(334, 20, '', '编辑管理员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin /setting/system_admin/edit', '', 1, '', 0, ' setting-system_admin-edit', 0),
(335, 334, '', '编辑管理员表单', 'admin', '', '', 'setting/admin/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(336, 334, '', '修改管理员', 'admin', '', '', 'setting/admin/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(337, 20, '', '删除管理员', 'admin', '', '', 'setting/admin/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(338, 21, '', '添加规则', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/add', '', 1, '', 0, 'setting-system_menus-add', 0),
(339, 338, '', '添加权限表单', 'admin', '', '', 'setting/menus/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(340, 338, '', '添加权限', 'admin', '', '', 'setting/menus', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(341, 21, '', '修改权限', 'admin', 'setting.system_menus', 'edit', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/edit', '', 1, '', 0, '/setting-system_menus-edit', 0),
(342, 341, '', '编辑权限表单', 'admin', '', '', 'setting/menus/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(343, 341, '', '修改权限', 'admin', '', '', 'setting/menus/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(344, 21, '', '修改权限状态', 'admin', '', '', 'setting/menus/show/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(345, 21, '', '删除权限菜单', 'admin', '', '', 'setting/menus/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(346, 338, '', '添加子菜单', 'admin', 'setting.system_menus', 'add', '', '', '[]', 0, 0, 0, 1, '/admin/setting/system_menus/add_sub', '', 1, '', 0, 'setting-system_menus-add_sub', 0),
(347, 361, '', '是否登陆短信平台', 'admin', '', '', 'notify/sms/is_login', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(348, 361, '', '短信剩余条数', 'admin', '', '', 'notify/sms/number', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(349, 95, '', '获取短信验证码', 'admin', '', '', 'serve/captcha', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(350, 95, '', '修改注册账号', 'admin', '', '', 'serve/register', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(351, 95, '', '登陆短信平台', 'admin', '', '', 'serve/login', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(353, 95, '', '退出短信登陆', 'admin', '', '', 'notify/sms/logout', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(355, 96, '', '短信模板列表', 'admin', '', '', 'serve/sms/temps', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(356, 96, '', '申请模板', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/sms_template_apply/add', '', 1, '', 0, 'setting-sms-sms_template_apply-add', 0),
(357, 356, '', '申请短信模板表单', 'admin', '', '', 'notify/sms/temp/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(358, 356, '', '保存申请短信模板', 'admin', '', '', 'notify/sms/temp', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(359, 97, '', '短信套餐', 'admin', '', '', 'serve/meal_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(360, 97, '', '短信购买支付码', 'admin', '', '', 'serve/pay_meal', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(361, 94, '', '短信设置附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/sms/attach', '', 1, '', 0, '', 0),
(362, 300, '', '门店数据', 'admin', '', '', 'merchant/store/get_header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(363, 300, '', '门店列表展示', 'admin', '', '', 'merchant/store', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(364, 424, '', '修改门店状态', 'admin', '', '', 'merchant/store/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(366, 7, '', '首页统计数据', 'admin', '', '', 'home/header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(367, 7, '', '首页订单图表', 'admin', '', '', 'home/order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(368, 7, '', '首页用户图表', 'admin', '', '', 'home/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(369, 7, '', '首页交易额排行', 'admin', '', '', 'home/rank', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(370, 72, '', '优惠卷领取列表', 'admin', '', '', 'marketing/coupon/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(371, 74, '', '砍价列表', 'admin', '', '', 'marketing/bargain', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(372, 74, '', '附加权限', 'admin', 'marketing.store_bargain', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_bargain/attr', '', 1, '', 0, '', 0),
(373, 372, '', '修改砍价状态', 'admin', '', '', 'marketing/bargain/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(374, 372, '', '砍价商品详情', 'admin', '', '', 'marketing/bargain/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(375, 74, '', '公共权限', 'admin', 'marketing.store_bargain', 'public', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_bargain/public', '', 1, '', 0, '', 0),
(376, 375, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(377, 375, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(378, 375, '', '运费模板', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(379, 372, '', '修改添加砍价商品', 'admin', '', '', 'marketing/bargain/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(380, 372, '', '删除砍价商品', 'admin', '', '', 'marketing/bargain/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(381, 75, '', '拼团列表', 'admin', '', '', 'marketing/combination', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(382, 75, '', '拼团数据', 'admin', '', '', 'marketing/combination/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(383, 75, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(384, 383, '', '拼团状态', 'admin', '', '', 'marketing/combination/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(385, 383, '', '删除拼团', 'admin', '', '', 'marketing/combination/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(386, 75, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(387, 386, '', '树型分类列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(388, 386, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(389, 386, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(390, 383, '', '获取拼团详情', 'admin', '', '', 'marketing/combination/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(391, 383, '', '编辑添加拼团', 'admin', '', '', 'marketing/combination/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(392, 76, '', '正在拼团列表', 'admin', '', '', 'marketing/combination/combine/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(393, 76, '', '拼团人员列表', 'admin', '', '', 'marketing/combination/order_pink/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(395, 77, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(396, 395, '', '修改拼团状态', 'admin', '', '', 'marketing/seckill/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(397, 77, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(398, 397, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(399, 397, '', '商品插件列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(400, 397, '', '运费模板列表', 'admin', '', '', 'product/product/get_template', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(401, 397, '', '秒杀时间段列表', 'admin', '', '', 'marketing/seckill/time_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(402, 395, '', '编辑添加秒杀商品', 'admin', '', '', 'marketing/seckill/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(403, 395, '', '删除秒杀商品', 'admin', '', '', 'marketing/seckill/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(404, 39, '', '提现申请列表', 'admin', '', '', 'finance/extract', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(405, 39, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(406, 44, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(407, 406, '', '保存修改文章', 'admin', '', '', 'cms/cms', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(408, 406, '', '获取文章详情', 'admin', '', '', 'cms/cms/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(409, 44, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(410, 406, '', '关联商品列表', 'admin', '', '', 'product/product/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(411, 406, '', '分类树型列表', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(412, 406, '', '关联商品', 'admin', '', '', 'cms/cms/relation/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(413, 406, '', '取消关联', 'admin', '', '', 'cms/cms/unrelation/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(414, 406, '', '删除文章', 'admin', '', '', 'cms/cms/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(415, 45, '', '文章列表', 'admin', '', '', 'cms/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(416, 45, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(417, 416, '', '文章分类添加表单', 'admin', '', '', 'cms/category/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'cms-category-create', 0),
(418, 416, '', '保存文章分类', 'admin', '', '', 'cms/category', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(419, 416, '', '编辑文章分类', 'admin', '', '', 'cms/category/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(420, 416, '', '修改文章分类', 'admin', '', '', 'cms/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(421, 416, '', '删除文章分类', 'admin', '', '', 'cms/category/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(422, 678, '', '客服列表', 'admin', '', '', 'app/wechat/kefu', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(423, 678, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(424, 300, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(425, 144, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(426, 425, '', '地图KEY权限', 'admin', '', '', 'merchant/store/address', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(427, 424, '', '添加编辑门店', 'admin', '', '', 'merchant/store/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'setting-merchant-system_store-save', 0),
(428, 424, '', '设置门店隐藏显示', 'admin', '', '', 'merchant/store/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(429, 424, '', '门店详情', 'admin', '', '', 'merchant/store/get_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(430, 424, '', '删除门店', 'admin', '', '', 'merchant/store/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(431, 425, '', '店员搜索门店列表', 'admin', '', '', 'merchant/store_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(432, 301, '', '店员列表', 'admin', '', '', 'merchant/store_staff', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(433, 301, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(434, 433, '', '添加店员表单', 'admin', '', '', 'merchant/store_staff/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'merchant-store_staff-create', 0),
(435, 425, '', '选择用户插件列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(436, 433, '', '添加修改店员', 'admin', '', '', 'merchant/store_staff/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(437, 433, '', '店员显示隐藏', 'admin', '', '', 'merchant/store_staff/set_show/<id>/<is_show>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(438, 433, '', '编辑店员表单', 'admin', '', '', 'merchant/store_staff/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(439, 433, '', '删除店员', 'admin', '', '', 'merchant/store_staff/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(440, 302, '', '核销订单列表', 'admin', '', '', 'merchant/verify_order', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(441, 302, '', '核销订单数据', 'admin', '', '', 'merchant/verify_badge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(442, 229, '', '城市数据列表', 'admin', '', '', 'setting/city/list/<parent_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(443, 229, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(444, 443, '', '获取添加城市表单', 'admin', '', '', 'setting/city/add/<parent_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(445, 443, '', '保存修改城市数据', 'admin', '', '', 'setting/city/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(446, 443, '', '获取修改城市表单', 'admin', '', '', 'setting/city/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(447, 443, '', '删除城市数据', 'admin', '', '', 'setting/city/del/<city_id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(448, 145, '', '物流公司列表', 'admin', '', '', 'freight/express', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(449, 145, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(450, 449, '', '修改物流公司状态', 'admin', '', '', 'freight/express/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(451, 449, '', '获取添加物流公司表单', 'admin', '', '', 'freight/express/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(452, 449, '', '保存物流公司', 'admin', '', '', 'freight/express', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(453, 449, '', '获取编辑物流公司表单', 'admin', '', '', 'freight/express/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(454, 449, '', '修改物流公司', 'admin', '', '', 'freight/express/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(455, 449, '', '删除物流公司', 'admin', '', '', 'freight/express/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(456, 230, '', '运费模板列表', 'admin', '', '', 'setting/shipping_templates/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(457, 230, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(458, 457, '', '运费模板城市数据', 'admin', '', '', 'setting/shipping_templates/city_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(459, 457, '', '保存或者修改运费模板', 'admin', '', '', 'setting/shipping_templates/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(460, 457, '', '删除运费模板', 'admin', '', '', 'setting/shipping_templates/del/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(461, 111, '', '配置分类列表', 'admin', '', '', 'setting/config_class', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(462, 111, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(463, 462, '', '配置分类添加表单', 'admin', '', '', 'setting/config_class/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(464, 462, '', '保存配置分类', 'admin', '', '', 'setting/config_class', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(465, 641, '', '编辑配置分类', 'admin', '', '', 'setting/config_class/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(466, 462, '', '删除配置分类', 'admin', '', '', 'setting/config_class/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(467, 125, '', '配置列表展示', 'admin', '', '', 'setting/config', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(468, 125, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(469, 468, '', '添加配置字段表单', 'admin', '', '', 'setting/config/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(470, 468, '', '保存配置字段', 'admin', '', '', 'setting/config', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(471, 468, '', '编辑配置字段表单', 'admin', '', '', 'setting/config/<id>/edit', '', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(472, 468, '', '编辑配置分类', 'admin', '', '', 'setting/config/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(473, 468, '', '删除配置', 'admin', '', '', 'setting/config/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(474, 468, '', '修改配置状态', 'admin', '', '', 'setting/config/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(475, 112, '', '组合数据列表', 'admin', '', '', 'setting/group', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(476, 112, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(477, 476, '', '新增组合数据', 'admin', '', '', 'setting/group', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(478, 476, '', '获取组合数据', 'admin', '', '', 'setting/group/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(479, 476, '', '修改组合数据', 'admin', '', '', 'setting/group/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(480, 476, '', '删除组合数据', 'admin', '', '', 'setting/group/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(481, 126, '', '组合数据列表表头', 'admin', '', '', 'setting/group_data/header', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(482, 126, '', '组合数据列表', 'admin', '', '', 'setting/group_data', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(483, 126, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(484, 483, '', '获取组合数据添加表单', 'admin', '', '', 'setting/group_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(485, 483, '', '保存组合数据', 'admin', '', '', 'setting/group_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(486, 483, '', '获取组合数据信息', 'admin', '', '', 'setting/group_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(487, 483, '', '修改组合数据信息', 'admin', '', '', 'setting/group_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(488, 483, '', '删除组合数据', 'admin', '', '', 'setting/group_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(489, 483, '', '修改组合数据状态', 'admin', '', '', 'setting/group_data/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(490, 57, '', '清除缓存', 'admin', '', '', 'system/refresh_cache/cache', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(491, 57, '', '清除日志', 'admin', '', '', 'system/refresh_cache/log', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(492, 47, '', '管理员搜索列表', 'admin', '', '', 'system/log/search_admin', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(493, 47, '', '系统日志列表', 'admin', '', '', 'system/log', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(494, 64, '', '文件校验列表', 'admin', '', '', 'system/file', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(495, 66, '', '清除数据接口', 'admin', '', '', 'system/clear/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(496, 67, '', '数据库列表', 'admin', '', '', 'system/backup', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(497, 67, '', '数据库备份列表', 'admin', '', '', 'system/backup/file_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(498, 67, '', '数据表详情', 'admin', '', '', 'system/backup/read', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(499, 67, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(500, 499, '', '备份表', 'admin', '', '', 'system/backup/backup', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(501, 499, '', '优化表', 'admin', '', '', 'system/backup/optimize', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(502, 499, '', '修复表', 'admin', '', '', 'system/backup/repair', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(503, 499, '', '导入sql', 'admin', '', '', 'system/backup/import', 'POST', '[]', 0, 0, 1, 1, '', '', 2, '', 0, '', 0),
(504, 499, '', '删除数据库备份', 'admin', '', '', 'system/backup/del_file', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(505, 499, '', '备份下载', 'admin', '', '', 'backup/download', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(507, 92, '', '微信菜单列表', 'admin', '', '', 'app/wechat/menu', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(508, 92, '', '保存微信菜单', 'admin', '', '', 'app/wechat/menu', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(553, 109, '', '图文列表', 'admin', '', '', 'app/wechat/news', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(554, 109, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(555, 554, '', '保存图文', 'admin', '', '', 'app/wechat/news', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(556, 554, '', '图文详情', 'admin', '', '', 'app/wechat/news/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(557, 554, '', '删除图文', 'admin', '', '', 'app/wechat/news/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(558, 114, '', '公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(559, 558, '', '回复关键词', 'admin', '', '', 'app/wechat/reply', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(560, 115, '', '关键词回复列表', 'admin', '', '', 'app/wechat/keyword', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(561, 115, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(562, 558, '', '保存修改关键字', 'admin', '', '', 'app/wechat/keyword/<id>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(563, 561, '', '获取关键字信息', 'admin', '', '', 'app/wechat/keyword/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(564, 561, '', '修改关键字状态', 'admin', '', '', 'app/wechat/keyword/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(565, 561, '', '删除关键字', 'admin', '', '', 'app/wechat/keyword/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(566, 656, '', '素材管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/system/file', '12/656', 1, '', 0, 'system-file', 0),
(567, 566, '', '附件列表', 'admin', '', '', 'file/file', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(568, 566, '', '附件分类', 'admin', '', '', 'file/category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(569, 566, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(570, 569, '', '附件分类表单', 'admin', '', '', 'file/category/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(571, 569, '', '附件分类保存', 'admin', '', '', 'file/category', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(572, 569, '', '删除附件', 'admin', '', '', 'file/file/delete', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(573, 569, '', '移动附件分类', 'admin', '', '', 'file/file/do_move', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(574, 566, '', '上传附件', 'admin', '', '', 'file/upload/<upload_type?>', 'POST', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(575, 569, '', '附件分类编辑表单', 'admin', '', '', 'file/category/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(576, 569, '', '附件分类修改', 'admin', '', '', 'file/category/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(577, 2, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(578, 3, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(579, 6, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(580, 99, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(581, 5, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(582, 70, '', '优惠卷模板列表', 'admin', '', '', 'marketing/coupon/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(583, 70, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(584, 29, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(585, 10, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(586, 11, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(587, 25, '', '个人中心', 'admin', '', '', '', '', '[]', 0, 1, 1, 1, '/admin/system/user', '', 1, '', 0, 'system-user', 0),
(589, 9, '', '用户标签', 'admin', 'user.user_label', 'index', '', '', '[]', 8, 1, 0, 1, '/admin/user/label', '', 1, 'user', 1, 'user-user-label', 0),
(590, 589, '', '获取用户标签', 'admin', '', '', 'user/label/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '9/589', 2, '', 0, '', 0),
(591, 589, '', '删除用户标签', 'admin', '', '', 'user/user_label/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(592, 589, '', '添加修改用户标签', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_add', '', 1, '', 0, 'admin-user-label_add', 0),
(593, 592, '', '添加修改用户标签表单', 'admin', '', '', 'user/user_label/add/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(594, 592, '', '保存修改用户标签', 'admin', '', '', 'user/user_label/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(596, 2, '', '商品导出', 'admin', '', '', 'export/storeProduct', 'GET', '[]', 10, 0, 0, 1, '', '', 2, '', 0, 'export-storeProduct', 0),
(597, 5, '', '订单导出', 'admin', '', '', 'export/storeorder', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeOrder', 0),
(598, 77, '', '秒杀商品导出', 'admin', '', '', 'export/storeSeckill', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeSeckill', 0),
(600, 75, '', '拼团商品导出', 'admin', '', '', 'export/storeCombination', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeCombination', 0),
(601, 74, '', '砍价商品导出', 'admin', '', '', 'export/storeBargain', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-storeBargain', 0),
(602, 29, '', '推广员列表导出', 'admin', '', '', 'export/userAgent', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userAgent', 0),
(603, 40, '', '用户充值导出', 'admin', '', '', 'export/userRecharge', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userRecharge', 0),
(605, 25, '', '商业授权', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/system/maintain/auth', '', 1, '', 0, 'system-maintain-auth', 0),
(606, 29, '', '分销员数据', 'admin', '', '', 'agent/statistics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(607, 587, '', '修改密码', 'admin', '', '', 'setting/update_admin', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(608, 605, '', '商业授权', 'admin', '', '', 'auth', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(610, 20, '', '管理员列表', 'admin', '', '', 'setting/admin', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(611, 19, '', '身份列表', 'admin', '', '', 'setting/role', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(612, 2, '', '批量上下架', 'admin', '', '', 'product/product/product_show', 'PUT', '[]', 5, 0, 0, 1, '', '', 2, '', 0, 'product-product-product_show', 0),
(613, 585, '', '批量设置标签', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/set_label', '', 1, '', 0, 'admin-user-set_label', 0),
(614, 613, '', '获取标签表单', 'admin', '', '', 'user/set_label', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(615, 613, '', '保存标签', 'admin', '', '', 'user/save_set_label', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(618, 42, '', '佣金导出', 'admin', '', '', 'export/userCommission', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'export-userCommission', 0),
(619, 21, '', '权限列表', 'admin', '', '', 'setting/menus', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(620, 22, '', '商品详情', 'admin', '', '', 'product/product/<id>', 'GET', '[]', 0, 1, 1, 1, '', '', 2, '', 0, '', 0),
(621, 585, '', '保存用户信息', 'admin', '', '', 'user/user', 'POST', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(622, 585, '', '积分余额', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/edit_other', '', 1, '', 0, '', 0),
(623, 622, '', '获取修改用户详情表单', 'admin', '', '', 'user/edit_other/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(624, 622, '', '修改用户余额', 'admin', '', '', 'user/update_other/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(625, 585, '', '赠送用户', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/user_level', '', 1, '', 0, '', 0),
(626, 625, '', '获取表单', 'admin', '', '', 'user/give_level/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(627, 625, '', '赠送会员等级', 'admin', '', '', 'user/save_give_level/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(628, 585, '', '单个用户分组设置', 'admin', '', '', 'user/save_set_group', 'PUT', '[]', 10, 0, 0, 1, '', '', 2, '', 0, '', 0),
(630, 375, '', '获取商品属性', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(631, 386, '', '商品规格获取', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(632, 397, '', '商品规格和获取', 'admin', '', '', 'product/product/attrs/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(633, 395, '', '获取秒杀详情', 'admin', '', '', 'marketing/seckill/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(634, 40, '', '删除充值记录', 'admin', '', '', 'finance/recharge/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(635, 20, '', '修改管理员状态', 'admin', '', '', 'setting/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(636, 25, '', '其他权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/system/other', '', 1, '', 0, '', 0),
(637, 636, '', '消息提醒', 'admin', '', '', 'jnotice', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(638, 457, '', '获取运费模板详情', 'admin', '', '', 'setting/shipping_templates/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(639, 457, '', '删除运费模板', 'admin', '', '', 'setting/shipping_templates/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(640, 462, '', '修改配置分类状态', 'admin', '', '', 'setting/config_class/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(641, 462, '', '编辑配置分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, 'system/config/system_config_tab/edit', '', 1, '', 0, '', 0),
(642, 641, '', '获取编辑配置分类表单', 'admin', '', '', 'setting/config_class/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(655, 65, '', '在线升级', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/system/system_upgradeclient/index', '', 1, '', 0, 'system-system-upgradeclient', 0),
(656, 12, '', '页面管理', 'admin', '', '', '', '', '[]', 1, 1, 0, 1, '/admin/setting/pages', '', 1, '', 0, 'admin-setting-pages', 0),
(657, 656, '', '页面设计', 'admin', '', '', '', '', '[]', 3, 1, 0, 1, '/admin/setting/pages/devise', '12/656', 1, '', 0, 'admin-setting-pages-devise', 0),
(658, 656, '', '页面编辑', 'admin', '', '', '', '', '[]', 3, 1, 1, 1, '/admin/setting/pages/diy', '12/656', 1, '', 0, 'admin-setting-pages-diy', 0),
(660, 656, '', '页面链接', 'admin', '', '', '', '', '[]', 3, 0, 0, 1, '/admin/setting/pages/links', '12/656', 1, '', 0, 'admin-setting-pages-links', 0),
(661, 657, '', 'DIY列表', 'admin', '', '', 'diy/get_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(662, 657, '', '组件文章分类', 'admin', '', '', 'cms/category_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(663, 657, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/diy', '', 1, '', 0, 'admin-setting-diy-additional', 0),
(664, 663, '', '获取页面设计', 'admin', '', '', 'diy/get_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(665, 663, '', '保存和修改页面', 'admin', '', '', 'diy/save/<id?>', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-setting-pages-diy-save', 0),
(666, 660, '', '路径列表', 'admin', '', '', 'diy/get_url', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(667, 663, '', '删除页面', 'admin', '', '', 'diy/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(668, 663, '', '修改页面状态', 'admin', '', '', 'diy/set_status/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(669, 2, '', '批量下架', 'admin', '', '', 'product/product/product_unshow', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(670, 581, '', '订单打印', 'admin', '', '', 'order/print/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(671, 585, '', '清除会员等级', 'admin', '', '', 'user/del_level/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(672, 271, '', 'H5推广二维码', 'admin', '', '', 'agent/look_h5_code', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(673, 416, '', '修改文章分类状态', 'admin', '', '', 'cms/category/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(674, 229, '', '清除城市缓存', 'admin', '', '', 'setting/city/clean_cache', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(675, 657, '', '组件商品分类', 'admin', '', '', 'diy/get_category', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(676, 657, '', '组件商品列表', 'admin', '', '', 'diy/get_product', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(677, 581, '', '订单号核销', 'admin', '', '', 'order/write_update/<order_id>', 'PUT', '[]', 0, 0, 0, 1, 'order/dels', '', 2, '', 0, 'admin-order-write_update', 0),
(678, 165, '', '客服列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/index', '', 1, '', 0, 'admin-setting-store_service-index', 0),
(679, 165, '', '客服话术', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/speechcraft', '', 1, '', 0, 'admin-setting-store_service-speechcraft', 0),
(685, 22, '', '上传商品视频', 'admin', '', '', 'product/product/get_temp_keys', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(686, 27, '', '直播管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live', '', 1, '', 0, 'admin-marketing-live', 0),
(687, 686, '', '直播间管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/live_room', '', 1, '', 0, 'admin-marketing-live-live_room', 0),
(688, 686, '', '直播商品管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/live_goods', '', 1, '', 0, 'admin-marketing-live-live_goods', 0),
(689, 686, '', '主播管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/live/anchor', '', 1, '', 0, 'admin-marketing-live-anchor', 0),
(690, 687, '', '添加直播间', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/live/add_live_room', '', 1, '', 0, 'admin-marketing-live-add_live_room', 0),
(691, 688, '', '添加直播商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/live/add_live_goods', '', 1, '', 0, 'admin-marketing-live-add_live_goods', 0),
(693, 689, '', '主播列表', 'admin', '', '', 'live/anchor/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-marketing-live-anchor', 0),
(694, 689, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '', 0),
(695, 694, '', '添加/修改主播表单', 'admin', '', '', 'live/anchor/add/<id>', 'GET', '[]', 0, 0, 0, 1, 'live/anchor/add/<id>', '', 2, '', 0, 'live-anchor-add', 0),
(696, 694, '', '添加/修改提交', 'admin', '', '', 'live/anchor/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(697, 694, '', '删除主播', 'admin', '', '', 'live/anchor/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(698, 694, '', '设置主播是否显示', 'admin', '', '', 'live/anchor/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(699, 688, '', '直播商品列表', 'admin', '', '', 'live/goods/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(700, 691, '', '生成直播商品', 'admin', '', '', 'live/goods/create', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(701, 691, '', '保存直播商品', 'admin', '', '', 'live/goods/add', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(702, 688, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '/admin/*', 0),
(703, 702, '', '直播商品详情', 'admin', '', '', 'live/goods/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(704, 702, '', '删除直播商品', 'admin', '', '', 'live/goods/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(705, 702, '', '同步直播商品', 'admin', '', '', 'live/goods/syncGoods', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(706, 702, '', '设置直播商品是否显示', 'admin', '', '', 'live/goods/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(707, 687, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/*', '', 1, '', 0, '', 0),
(708, 687, '', '直播间列表', 'admin', '', '', 'live/room/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(709, 707, '', '添加直播间提交', 'admin', '', '', 'live/room/add', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(710, 707, '', '直播间详情', 'admin', '', '', 'live/room/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(711, 707, '', '直播间添加（关联）商品', 'admin', '', '', 'live/room/add_goods', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(712, 707, '', '删除直播间', 'admin', '', '', 'live/room/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(713, 707, '', '设置直播间是否显示', 'admin', '', '', 'live/room/set_show/<id>/<is_show>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(714, 707, '', '同步直播间状态', 'admin', '', '', 'live/room/syncRoom', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(715, 898, '', '一键同步订阅消息', 'admin', '', '', 'app/routine/syncSubscribe', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, 'app-wechat-template-sync', 0),
(716, 0, 'md-stats', '统计', 'admin', '', '', '', '', '[]', 1, 1, 0, 1, '/admin/statistic', '', 1, '', 0, 'admin-statistic', 0),
(717, 716, '', '商品统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/product', '', 1, '', 0, 'admin-statistic', 0),
(718, 716, '', '用户统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/user', '', 1, '', 0, 'admin-statistic', 0),
(719, 71, '', '添加优惠卷', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/store_coupon_issue/create', '27/30/71', 1, '', 0, 'marketing-store_coupon_issue-create', 0),
(720, 303, '', '配送员管理', 'admin', '', '', '', '', '[]', 10, 1, 0, 1, '/admin/setting/delivery_service/index', '', 1, '', 0, 'setting-delivery-service', 0),
(721, 729, '', '编辑配送员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/delivery_service/edit', '', 1, '', 0, 'setting-delivery_service-edit', 0),
(722, 720, '', '配送员列表', 'admin', '', '', 'order/delivery/index', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(723, 721, '', '修改配送员', 'admin', '', '', 'order/delivery/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(724, 729, '', '添加配送员', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/delivery_service/add', '', 1, '', 0, 'setting-delivery_service-add', 0),
(725, 724, '', '获取添加配送员表单', 'admin', '', '', 'order/delivery/add', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(726, 724, '', '保存配送员', 'admin', '', '', 'order/delivery/save', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(727, 729, '', '删除配送员', 'admin', '', '', 'order/delivery/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(728, 729, '', '配送员是否开启', 'admin', '', '', 'order/delivery/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(729, 720, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin*', '', 1, '', 0, '', 0),
(731, 9, '', '付费会员', 'admin', '', '', '', '', '[]', 7, 1, 0, 1, '/admin/user/grade', '', 1, '', 0, 'user-user-grade', 0),
(732, 762, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(733, 732, '', ' 添加会员批次', 'admin', '', '', 'user/member_batch/save/<id>', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(734, 732, '', '列表字段修改', 'admin', '', '', 'user/member_batch/set_value/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'user-member_batch-set_value', 0),
(735, 732, '', '会员卡导出', 'admin', '', '', 'export/memberCard/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'export-member_card', 0),
(736, 762, '', '卡密列表', 'admin', '', '', 'user/member_batch/index', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(737, 732, '', '会员卡列表', 'admin', '', '', 'user/member_card/index', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member_card-index', 0),
(738, 165, '', '用户留言', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/store_service/feedback', '', 1, '', 0, 'admin-setting-store_service-feedback', 0),
(739, 738, '', '列表展示', 'admin', '', '', 'app/feedback', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(740, 738, '', '附件权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '*', '', 1, '', 0, '', 0),
(741, 740, '', '删除反馈', 'admin', '', '', 'app/feedback/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(742, 679, '', '列表展示', 'admin', '', '', 'app/wechat/speechcraft', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(743, 679, '', '附件权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '*', '', 1, '', 0, '', 0),
(744, 743, '', '添加话术', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/add', '', 1, '', 0, 'admin-setting-store_service-speechcraft-add', 0),
(745, 743, '', '编辑话术', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/edit', '', 1, '', 0, 'admin-setting-store_service-speechcraft-edit', 0),
(746, 744, '', '获取添加话术表单', 'admin', '', '', 'app/wechat/speechcraft/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(747, 744, '', '保存话术', 'admin', '', '', 'app/wechat/speechcraft', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(748, 745, '', '获取编辑话术表单', 'admin', '', '', 'app/wechat/speechcraft/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(749, 745, '', '确认修改', 'admin', '', '', 'app/wechat/speechcraft/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(750, 743, '', '删除话术', 'admin', '', '', 'app/wechat/speechcraft/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(751, 731, '', '会员类型', 'admin', '', '', '', '', '[]', 5, 1, 0, 1, '/admin/user/grade/type', '', 1, '', 0, 'admin-user-member-type', 0),
(752, 751, '', '会员分类列表', 'admin', '', '', 'user/member/ship', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-ship', 0),
(753, 751, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(754, 753, '', '会员卡类型保存', 'admin', '', '', 'user/member_ship/save/<id>', 'POST', '[]', 0, 1, 1, 1, '', '', 2, '', 0, 'user-member_ship-save', 0),
(755, 31, '', '砍价列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_bargain/bargain_list', '', 1, '', 0, 'marketing-store_bargain-bargain_list', 0),
(756, 585, '', '添加用户', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/save', '', 1, '', 0, 'admin-user-save', 0),
(757, 756, '', '获取添加用户表单', 'admin', '', '', 'user/user/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(758, 756, '', '保存用户', 'admin', '', '', 'user/user', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(759, 585, '', '同步公众号用户', 'admin', '', '', 'user/user/syncUsers', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-user-synchro', 0),
(760, 4, '', '收银订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/offline', '', 1, '', 0, 'admin-order-offline', 0),
(761, 760, '', '线下收银订单', 'admin', '', '', 'order/scan_list', 'GET', '[]', 0, 0, 1, 1, '', '', 2, '', 0, 'admin-order-scan_list', 0),
(762, 731, '', '卡密会员', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/user/grade/card', '', 1, '', 0, 'admin-user-grade-card', 0),
(763, 731, '', '会员记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/user/grade/record', '', 1, '', 0, 'admin-user-grade-record', 0),
(764, 763, '', '会员记录列表', 'admin', '', '', 'user/member/record', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-record', 0),
(765, 731, '', '会员权益', 'admin', '', '', '', '', '[]', 4, 1, 0, 1, '/admin/user/grade/right', '', 1, '', 0, 'admin-user-grade-right', 0),
(766, 716, '', '交易统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/transaction', '', 1, '', 0, 'admin-statistic', 0),
(767, 36, '', '发票管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/invoice/list', '', 1, '', 0, 'admin-order-startOrderInvoice-index', 0),
(768, 210, '', '编辑', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-edit', 0),
(769, 210, '', '订单信息', 'admin', '', '', 'order/invoice_order_info/<id>', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-orderInfo', 0),
(770, 210, '', '编辑提交', 'admin', '', '', 'order/invoice/set/<id>', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, 'admin-order-invoice-update', 0),
(771, 765, '', '会员权益列表', 'admin', '', '', 'user/member/right', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'user-member-right', 0),
(772, 765, '', '附加权限', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin*', '', 1, '', 0, '', 0),
(773, 772, '', '会员权益保存', 'admin', '', '', 'user/member_right/save/<id>', 'POST', '[]', 0, 1, 1, 1, '', '', 2, '', 0, 'user-member_right-save', 0),
(774, 589, '', '用户标签列表', 'admin', '', '', 'user/user_label_cate/all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, 'admin-user-user_lable_cate-all', 0),
(778, 740, '', '获取修改备注表单接口', 'admin', '', '', 'app/feedback/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(779, 740, '', '修改用户备注接口', 'admin', '', '', 'app/feedback/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(780, 589, '', '标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate', '', 1, '', 0, '', 0),
(781, 780, '', '获取标签分类列表', 'admin', '', '', 'user/user_label_cate/all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(782, 780, '', '添加标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate/add', '', 1, '', 0, '', 0),
(783, 782, '', '获取标签分类表单', 'admin', '', '', 'user/user_label_cate/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(784, 782, '', '保存标签分类', 'admin', '', '', 'user/user_label_cate', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(785, 780, '', '修改标签分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/user/label_cate/edit', '', 1, '', 0, '', 0),
(786, 785, '', '获取修改标签分类表单', 'admin', '', '', 'user/user_label_cate/<id>/edit', 'GET', '[]', 0, 0, 0, 1, 'user/user_label_cate/<id>/edit', '', 2, '', 0, '', 0),
(787, 785, '', '保存用户标签分类', 'admin', '', '', 'user/user_label_cate/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(788, 780, '', '删除用户标签分类', 'admin', '', '', 'user/user_label_cate/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(789, 743, '', '话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate', '', 1, '', 0, 'admin-setting-store_service-speechcraft-cate', 0),
(790, 789, '', '获取话术分类列表', 'admin', '', '', 'app/wechat/speechcraftcate', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(791, 789, '', '添加话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate/create', '', 1, '', 0, '', 0),
(792, 791, '', '获取话术分类表单', 'admin', '', '', 'app/wechat/speechcraftcate/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(793, 791, '', '保存话术分类', 'admin', '', '', 'app/wechat/speechcraftcate', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(794, 795, '', '获取话术分类表单', 'admin', '', '', 'app/wechat/speechcraftcate/<id>/edit', 'GET', '[]', 0, 0, 0, 1, 'app/wechat/speechcraftcate/<id>/edit', '', 2, '', 0, '', 0),
(795, 789, '', '修改话术分类', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/setting/store_service/speechcraft/cate/edit', '', 1, '', 0, '', 0),
(796, 795, '', '保存修改客户话术分类', 'admin', '', '', 'app/wechat/speechcraftcate/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(797, 789, '', '删除话术分类', 'admin', '', '', 'app/wechat/speechcraftcate/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(798, 209, '', '获取送货人列表', 'admin', '', '', 'order/delivery/list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(799, 209, '', '获取电子面单打印默认配置', 'admin', '', '', 'order/sheet_info', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(800, 581, '', '电子面单打印', 'admin', '', '', 'order/order_dump/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(801, 760, '', '获取收银二维码', 'admin', '', '', 'order/offline_scan', 'GET', '[]', 0, 0, 0, 1, '', '4/760', 2, '', 0, '', 0),
(802, 767, '', '获取订单发票数据', 'admin', '', '', 'order/invoice/chart', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(803, 762, '', '下载卡密二维码', 'admin', '', '', 'user/member_scan', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(805, 584, '', '修改推广人', 'admin', '', '', 'agent/spread', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(806, 71, '', '复制优惠券', 'admin', '', '', 'marketing/coupon/copy/<id>', 'GET', '[]', 0, 0, 0, 1, 'marketing/coupon/copy/369', '', 2, '', 0, '', 0),
(807, 755, '', '获取砍价列表', 'admin', '', '', 'marketing/bargain_list', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(808, 77, '', '秒杀商品列表', 'admin', '', '', 'marketing/seckill', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(809, 95, '', '获取平台用户信息', 'admin', '', '', 'serve/info', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(810, 95, '', '获取平台消费列表', 'admin', '', '', 'serve/record', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(811, 95, '', '修改手机号', 'admin', '', '', 'serve/update_phone', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(812, 95, '', '修改签名', 'admin', '', '', 'serve/sms/sign', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(813, 95, '', '修改账号密码', 'admin', '', '', 'serve/modify', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(814, 721, '', '获取编辑配送员表单', 'admin', '', '', 'order/delivery/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(815, 717, '', '获取基础商品接口', 'admin', '', '', 'statistic/product/get_basic', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(816, 717, '', '获取商品趋势', 'admin', '', '', 'statistic/product/get_trend', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(817, 717, '', '获取商品排行', 'admin', '', '', 'statistic/product/get_product_ranking', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(818, 718, '', '获取用户基础', 'admin', '', '', 'statistic/user/get_basic', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(819, 718, '', '获取用户趋势', 'admin', '', '', 'statistic/user/get_trend', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(820, 718, '', '获取用户地区排行', 'admin', '', '', 'statistic/user/get_region', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(821, 718, '', '获取用户性别排行', 'admin', '', '', 'statistic/user/get_sex', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(822, 766, '', '获取交易趋势', 'admin', '', '', 'statistic/trade/top_trade', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(823, 766, '', '获取订单趋势', 'admin', '', '', 'statistic/trade/bottom_trade', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(824, 718, '', '导出用户统计', 'admin', '', '', 'statistic/user/get_excel', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(825, 717, '', '导出商品统计', 'admin', '', '', 'statistic/product/get_excel', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(828, 10, '', '用户列表', 'admin', '', '', 'user/user', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(830, 732, '', '卡密列表', 'admin', '', '', 'user/member_card/index/<card_batch_id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(831, 423, '', '进入工作台', 'admin', '', '', 'app/wechat/kefu/login/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(832, 71, '', '保存优惠券', 'admin', '', '', 'marketing/coupon/save_coupon', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(833, 755, '', '砍价详情', 'admin', '', '', 'marketing/bargain_list_info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(834, 95, '', '短信记录列表', 'admin', '', '', 'notify/sms/record', 'GET', '[]', 0, 0, 0, 1, 'notify/sms/record', '', 2, '', 0, '', 0),
(835, 28, '', '分销设置表单', 'admin', '', '', 'agent/config/edit_basics', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(836, 28, '', '分销设置表单提交', 'admin', '', '', 'agent/config/save_basics', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(837, 79, '', '积分配置表单', 'admin', '', '', 'marketing/integral_config/edit_basics', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(838, 79, '', '积分配置表单提交', 'admin', '', '', 'marketing/integral_config/save_basics', 'POST', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(843, 154, '', '签到天数头部数据', 'admin', '', '', 'setting/sign_data/header', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(844, 154, '', '设置签到数据状态', 'admin', '', '', 'setting/sign_data/set_status/<id>/<status>', 'PUT', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(845, 154, '', '签到天数列表', 'admin', '', '', 'setting/sign_data', 'GET', '[]', 0, 1, 0, 1, '', '', 2, '', 0, '', 0),
(846, 154, '', '添加签到天数表单', 'admin', '', '', 'setting/sign_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(847, 154, '', '添加签到天数', 'admin', '', '', 'setting/sign_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(848, 154, '', '编辑签到天数表单', 'admin', '', '', 'setting/sign_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(849, 154, '', '编辑签到天数', 'admin', '', '', 'setting/sign_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(850, 154, '', '删除签到天数', 'admin', '', '', 'setting/sign_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(876, 78, '', '秒杀配置列表', 'admin', '', '', 'setting/seckill_data', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(877, 78, '', '添加秒杀表单', 'admin', '', '', 'setting/seckill_data/create', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(878, 78, '', '添加秒杀', 'admin', '', '', 'setting/seckill_data', 'POST', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(879, 78, '', '编辑秒杀表单', 'admin', '', '', 'setting/seckill_data/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(880, 78, '', '编辑秒杀', 'admin', '', '', 'settting/seckill_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(881, 78, '', '删除秒杀', 'admin', '', '', 'setting/seckill_data/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(884, 128, '', '获取数据分类', 'admin', '', '', 'setting/group_all', 'GET', '[]', 0, 0, 0, 1, '', '', 2, '', 0, '', 0),
(885, 569, '', '附件名称修改', 'admin', '', '', 'file/file/update/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '25/566/569', 2, '', 0, '', 0),
(886, 577, '', '用户标签接口', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '1/2/577', 2, '', 0, '', 0),
(887, 625, '', '获取赠送付费会员时长表单', 'admin', '', '', 'user/give_level_time/<id>', 'GET', '[]', 0, 0, 0, 1, '', '9/10/585/625', 2, '', 0, '', 0),
(888, 625, '', '保存赠送付费会员时长', 'admin', '', '', 'user/save_give_level_time/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '9/10/585/625', 2, '', 0, '', 0),
(889, 663, '', '添加页面', 'admin', '', '', 'diy/create', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, 'admin-template', 0),
(890, 663, '', '保存新增', 'admin', '', '', 'diy/create', 'POST', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, 'admin-template', 0),
(891, 663, '', '设置默认数据', 'admin', '', '', 'diy/set_recovery/<id?>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, '', 0),
(892, 663, '', '获取商品列表', 'admin', '', '', 'diy/get_product_list', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657/663', 2, '', 0, '', 0),
(893, 577, '', '商品活动状态检测', 'admin', '', '', 'product/product/check_activity/<id>', 'GET', '[]', 0, 0, 0, 1, '', '1/2/577', 2, '', 0, '', 0),
(894, 589, '', '会员标签列表', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '9/589', 2, '', 0, '', 0),
(895, 585, '', '新增客服选择用户列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 0, 1, '', '9/10/585', 2, '', 0, '', 0),
(896, 26, '', '分销等级', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/membership_level/index', '26', 1, '', 0, '', 0),
(897, 4, '', '售后订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/order/refund', '4', 1, '', 0, 'admin-order-refund', 0),
(898, 12, '', '消息管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/notification/index', '12', 1, '', 0, 'setting-notification', 0),
(902, 656, '', '主题风格', 'admin', '', '', '', '', '[]', 2, 1, 0, 1, '/admin/setting/theme_style', '12/656', 1, '', 0, 'admin-setting-theme_style', 0),
(903, 656, '', 'PC商城', 'admin', '', '', '', '', '[]', 2, 1, 0, 1, '/admin/setting/pc_group_data', '12/656', 1, '', 0, 'setting-system-pc_data', 0),
(904, 656, '', '客服页面广告', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/system_group_data/kf_adv', '', 1, '', 0, 'setting-system-group_data-kf_adv', 1),
(905, 34, '', '积分商品', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_integral/index', '27/34', 1, '', 0, 'marketing-store_integral', 0),
(906, 905, '', '积分商品列表', 'admin', '', '', 'marketing/integral_product', 'GET', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(908, 905, '', '添加积分商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/marketing/store_integral/create', '27/34/905', 1, '', 0, 'marketing-store_integral-create', 0),
(909, 27, '', '九宫格抽奖', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/index', '27', 1, '', 0, 'marketing-lottery-index', 0),
(912, 34, '', '积分订单', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/store_integral/order_list', '27/34', 1, '', 0, 'marketing-store_integral-order', 0),
(913, 905, '', '批量添加积分商品', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/pages/marketing/store_integral/add_store_integral', '27/34/905', 1, '', 0, 'marketing-store_integral-create', 0),
(914, 897, '', '售后订单列表', 'admin', '', '', 'refund/list', 'GET', '[]', 0, 0, 0, 1, '', '4/897', 2, '', 0, '', 0),
(915, 5, '', '子订单列表', 'admin', '', '', 'order/split_order/<id>', 'GET', '[]', 0, 0, 0, 1, 'order/split_order/<id>', '4/5', 2, '', 0, '', 0),
(916, 5, '', '订单详情', 'admin', '', '', 'order/info/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(917, 5, '', '订单记录', 'admin', '', '', 'order/status/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(918, 5, '', '可拆分商品列表', 'admin', '', '', 'order/split_cart_info/<id>', 'GET', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(919, 5, '', '拆单发送货', 'admin', '', '', 'order/split_delivery/<id>', 'PUT', '[]', 0, 0, 1, 1, '', '4/5', 2, '', 0, '', 0),
(920, 896, '', '修改分销等级状态', 'admin', '', '', 'agent/level/set_status/<id>/<status>', 'PUT', '{\"[PUT] agent\":\"level\",\"set_status\":\"<id>\"}', 0, 0, 1, 1, '', '26/896', 2, '', 0, '', 0),
(921, 896, '', '修改分销等级任务状态', 'admin', '', '', 'agent/level_task/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(922, 896, '', '获取赠送分销等级表单', 'admin', '', '', 'agent/get_level_form', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(923, 896, '', '赠送分销等级', 'admin', '', '', 'agent/give_level', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(924, 896, '', '分销等级列表', 'admin', '', '', 'agent/level', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(925, 896, '', '添加分销等级表单', 'admin', '', '', 'agent/level/create', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(926, 896, '', '编辑分销等级表单', 'admin', '', '', 'agent/level/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(927, 896, '', '分销等级任务', 'admin', '', '', 'agent/level_task', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(928, 896, '', '删除分销等级', 'admin', '', '', 'agent/level/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(929, 896, '', '添加分销员等级', 'admin', '', '', 'agent/level', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(930, 896, '', '编辑分销员等级', 'admin', '', '', 'agent/level/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(931, 896, '', '添加分销员等级任务表单', 'admin', '', '', 'agent/level_task/create', 'GET', '[]', 0, 0, 0, 1, 'agent/level_task/create', '26/896', 2, '', 0, '', 0),
(932, 896, '', '添加分销员等级任务', 'admin', '', '', 'agent/level_task', 'POST', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(933, 896, '', '编辑分销员等级任务表单', 'admin', '', '', 'agent/level_task/<id>/edit', 'GET', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(934, 896, '', '编辑分销员等级任务', 'admin', '', '', 'agent/level_task/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '26/896', 2, '', 0, '', 0),
(935, 896, '', '删除分销员等级任务', 'admin', '', '', 'agent/level_task/<id>', 'DELETE', '[]', 0, 0, 0, 1, 'agent/level_task/<id>', '26/896', 2, '', 0, '', 0),
(936, 423, '', '新增客服选择用户列表', 'admin', '', '', 'app/wechat/kefu/create', 'GET', '[]', 0, 0, 1, 1, '', '165/678/423', 2, '', 0, '', 0),
(937, 78, '', '秒杀配置头部', 'admin', '', '', 'setting/seckill_data/header', 'GET', '[]', 0, 0, 0, 1, '', '27/33/78', 2, '', 0, '', 0),
(938, 154, '', '签到配置编辑保存', 'admin', '', '', 'setting/group_data/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/154', 2, '', 0, '', 0),
(939, 154, '', '签到配置添加保存', 'admin', '', '', 'setting/group_data', 'POST', '[]', 0, 0, 0, 1, '', '27/34/154', 2, '', 0, '', 0),
(940, 905, '', '添加积分商品保存', 'admin', '', '', 'marketing/integral/<id>', 'POST', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(941, 912, '', '积分订单头部', 'admin', '', '', 'marketing/integral/order/chart', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(942, 912, '', '积分订单列表', 'admin', '', '', 'marketing/integral/order/list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(943, 905, '', '积分商品编辑', 'admin', '', '', 'marketing/integral/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/905', 2, '', 0, '', 0),
(944, 912, '', '发货物流列表', 'admin', '', '', 'marketing/integral/order/express_list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(945, 912, '', '快递列表', 'admin', '', '', 'marketing/integral/order/delivery/list', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(946, 912, '', '图表详情', 'admin', '', '', 'marketing/integral/order/sheet_info', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(947, 912, '', '发货', 'admin', '', '', 'marketing/integral/order/delivery/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(948, 912, '', '配送信息表单', 'admin', '', '', 'marketing/integral/order/distribution/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(949, 912, '', '配送信息保存', 'admin', '', '', 'marketing/integral/order/distribution/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(950, 912, '', '订单详情', 'admin', '', '', 'marketing/integral/order/info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(951, 912, '', '订单记录', 'admin', '', '', 'marketing/integral/order/status/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(952, 912, '', '小票打印', 'admin', '', '', 'marketing/integral/order/print/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(953, 912, '', '物流查询', 'admin', '', '', 'marketing/integral/order/express/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(954, 912, '', '订单备注', 'admin', '', '', 'marketing/integral/order/remark/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(955, 912, '', '收货', 'admin', '', '', 'marketing/integral/order/take/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/34/912', 2, '', 0, '', 0),
(960, 909, '', '抽奖列表', 'admin', '', '', 'marketing/lottery/list', 'GET', '[]', 0, 1, 0, 1, '', '27/909', 2, '', 0, '', 0),
(961, 909, '', '抽奖商品详情', 'admin', '', '', 'marketing/lottery/detail/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(962, 909, '', '用户等级', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(963, 909, '', '会员等级', 'admin', '', '', 'user/user_level/vip_list', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(964, 909, '', '编辑保存', 'admin', '', '', 'marketing/lottery/edit/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(965, 27, '', '营销公共权限', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, 'admin-marketing', '27', 1, '', 0, '', 0),
(966, 965, '', '附件分类', 'admin', '', '', 'file/category', 'GET', '[]', 0, 0, 0, 1, '', '27/965', 2, '', 0, '', 0),
(967, 965, '', '附件列表', 'admin', '', '', 'file/file', 'GET', '[]', 0, 0, 0, 1, '', '27/965', 2, '', 0, '', 0),
(968, 909, '', '删除抽奖', 'admin', '', '', 'marketing/lottery/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(969, 909, '', '抽奖记录列表', 'admin', '', '', 'marketing/lottery/record/list/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(970, 909, '', '物流公司', 'admin', '', '', 'order/express_list', 'GET', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(971, 909, '', '抽奖记录备注', 'admin', '', '', 'marketing/lottery/record/deliver', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(972, 909, '', '抽奖状态', 'admin', '', '', 'marketing/lottery/set_status/<id>/<status>', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(973, 909, '', '添加抽奖', 'admin', '', '', 'marketing/lottery/add', 'POST', '[]', 0, 0, 0, 1, '', '27/909', 2, '', 0, '', 0),
(975, 28, '', '分销配置头部', 'admin', '', '', 'setting/config/header_basics', 'GET', '[]', 0, 0, 0, 1, '', '26/28', 2, '', 0, '', 0),
(976, 717, '', '查看商品', 'admin', '', '', 'product/product/<id>', 'GET', '[]', 0, 0, 0, 1, '', '716/717', 2, '', 0, '', 0),
(977, 657, '', '获取风格设置', 'admin', '', '', 'diy/get_color_change/<type>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(978, 657, '', '获取个人中心菜单', 'admin', '', '', 'diy/get_member', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(979, 657, '', '个人中心组件分类', 'admin', '', '', 'diy/get_page_category', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(980, 657, '', '个人中心组件树形分类', 'admin', '', '', 'product/category/tree/<type>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(981, 657, '', '获取页面链接', 'admin', '', '', 'diy/get_page_link/<cate_id>', 'GET', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(982, 657, '', '商品分类页保存', 'admin', '', '', 'diy/color_change/<status>/<type>', 'PUT', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(983, 657, '', '个人中心页保存', 'admin', '', '', 'diy/member_save', 'POST', '[]', 0, 0, 0, 1, '', '12/656/657', 2, '', 0, '', 0),
(984, 128, '', '获取组合数据', 'admin', '', '', 'setting/group_data', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(985, 128, '', '获取头部', 'admin', '', '', 'setting/sign_data/header', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(986, 128, '', '保存配置', 'admin', '', '', 'setting/group_data/save_all', 'POST', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(987, 128, '', '客服页面广告', 'admin', '', '', 'setting/get_kf_adv', 'GET', '[]', 0, 0, 0, 1, '', '12/656/128', 2, '', 0, '', 0),
(988, 898, '', '消息管理列表', 'admin', '', '', 'setting/notification/index', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(989, 898, '', '模板消息详情', 'admin', '', '', 'setting/notification/info', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(990, 898, '', '编辑保存', 'admin', '', '', 'setting/notification/save', 'POST', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(991, 898, '', '模板消息状态修改', 'admin', '', '', 'setting/notification/set_status/<type>/<status>/<id>', 'PUT', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(992, 898, '', '一键同步模版消息', 'admin', '', '', 'app/wechat/syncSubscribe', 'GET', '[]', 0, 0, 0, 1, '', '12/898', 2, '', 0, '', 0),
(993, 135, '', '小程序', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/routine', '135', 1, '', 0, 'admin-routine', 0),
(994, 993, '', '小程序下载', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/routine/download', '135/993', 1, '', 0, 'routine-download', 0),
(995, 994, '', '下载小程序页面数据', 'admin', '', '', 'app/routine/info', 'GET', '[]', 0, 0, 0, 1, '', '135/993/994', 2, '', 0, '', 0),
(996, 994, '', '下载小程序模版', 'admin', '', '', 'app/routine/download', 'POST', '[]', 0, 0, 0, 1, '', '135/993/994', 2, '', 0, '', 0),
(997, 716, '', '订单统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/order', '716', 1, '', 0, 'admin-statistic', 0),
(998, 37, '', '资金流水', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/capital_flow/index', '35/37', 1, '', 0, 'finance-capital_flow-index', 0),
(999, 37, '', '账单记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/billing_records/index', '35/37', 1, '', 0, 'finance-billing_records-index', 0),
(1000, 566, '', '富文本上传图片', 'admin', '', '', '', '', '[]', 0, 0, 1, 1, '/admin/widget.images/index.html', '25/566', 1, '', 0, 'admin-user-user-index', 0),
(1001, 34, '', '积分记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/point_record', '27/34', 1, '', 0, 'marketing-point_record-index', 0),
(1002, 34, '', '积分统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/point_statistic', '27/34', 1, '', 0, 'marketing-point_statistic-index', 0),
(1003, 35, '', '余额记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/balance', '35', 1, '', 0, 'finance-balance-index', 0),
(1004, 1003, '', '余额记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/finance/balance/balance', '35/1003', 1, '', 0, 'finance-user-balance', 0),
(1005, 716, '', '余额统计', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/statistic/balance', '716', 1, '', 0, 'admin-statistic', 0),
(1006, 69, '', '公众号配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/wechat_config/3/2', '135/69', 1, '', 0, 'setting-system-config', 0),
(1007, 993, '', '小程序配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/routine_config/3/7', '135/993', 1, '', 0, 'setting-system-config', 0),
(1008, 135, '', 'PC端', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/pc', '135', 1, '', 0, 'admin-pc', 0),
(1009, 135, '', 'APP', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/app', '135', 1, '', 0, 'admin-app', 0),
(1010, 1008, '', 'PC端配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/pc_config/3/75', '135/1008', 1, '', 0, 'setting-system-config', 0),
(1011, 1009, '', 'APP配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/app_config/3/77', '135/1009', 1, '', 0, 'setting-system-config', 0),
(1012, 12, '', '存储配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/storage', '12', 1, '', 0, 'setting-storage', 0),
(1013, 26, '', '事业部', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/agent/division', '26', 1, '', 0, 'agent-division', 0),
(1014, 1013, '', '事业部列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/index', '26/1013', 1, '', 0, 'agent-division-index', 0),
(1015, 1013, '', '代理商列表', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/agent/index', '26/1013', 1, '', 0, 'agent-division-agent-index', 0),
(1016, 1013, '', '代理商申请', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/division/agent/applyList', '26/1013', 1, '', 0, 'agent-division-agent-applyList', 0),
(1018, 909, '', '抽奖配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/create', '27/909', 1, '', 0, 'admin-marketing-lottery-create', 0),
(1019, 909, '', '中奖记录', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/lottery/recording_list', '27/909', 1, '', 0, 'admin-marketing-lottery-recording_list-id', 0),
(1020, 1014, '', '事业部列表', 'admin', '', '', 'agent/division/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1021, 1014, '', '添加事业部', 'admin', '', '', 'agent/division/create/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1023, 27, '', '公众号渠道码', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/channel_code/channelCodeIndex', '27', 1, '', 0, 'marketing-channel_code-index', 0),
(1024, 1023, '', '添加公众号渠道码', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/channel_code/create', '27/1021', 1, '', 0, 'marketing-channel_code-create', 0),
(1025, 1023, '', '渠道码统计', 'admin', '', '', '', '', '[]', 0, 0, 0, 1, '/admin/marketing/channel_code/code_statistic', '27/1021', 1, '', 0, 'marketing-channel_code-statistic', 0),
(1026, 1014, '', '事业部下级列表', 'admin', '', '', '/agent/division/down_list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1027, 1014, '', '事业部保存', 'admin', '', '', 'agent/division/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1028, 1014, '', '事业部状态切换', 'admin', '', '', 'agent/division/set_status/<status>/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1029, 1014, '', '事业部删除', 'admin', '', '', 'division/del/<type>/<uid>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1014', 2, '', 0, '', 0),
(1030, 1015, '', '代理商列表', 'admin', '', '', 'agent/division/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1031, 1015, '', '代理商下级列表', 'admin', '', '', 'agent/division/down_list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1032, 1015, '', '添加代理商', 'admin', '', '', 'agent/division/agent/create/<uid>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1033, 1015, '', '代理商保存', 'admin', '', '', 'agent/division/agent/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1034, 1015, '', '代理商状态切换', 'admin', '', '', 'agent/division/set_status/<status>/<uid>', 'PUT', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1035, 1015, '', '代理商删除', 'admin', '', '', 'agent/division/del/<type>/<uid>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1015', 2, '', 0, '', 0),
(1036, 1016, '', '代理商申请列表', 'admin', '', '', 'agent/division/agent_apply/list', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1037, 1016, '', '代理商审核', 'admin', '', '', 'agent/division/examine_apply/<id>/<type>', 'GET', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1038, 1016, '', '提交审核', 'admin', '', '', 'agent/division/apply_agent/save', 'POST', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1039, 1016, '', '删除审核', 'admin', '', '', 'agent/division/del_apply/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '26/1013/1016', 2, '', 0, '', 0),
(1042, 1023, '', '渠道码分类列表', 'admin', '', '', 'app/wechat_qrcode/cate/list', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1043, 1023, '', '渠道码分类添加编辑表单', 'admin', '', '', 'app/wechat_qrcode/cate/create/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1044, 1023, '', '渠道码分类保存', 'admin', '', '', 'app/wechat_qrcode/cate/save', 'POST', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1045, 1023, '', '渠道码分类删除', 'admin', '', '', 'app/wechat_qrcode/cate/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1046, 1023, '', '保存渠道码', 'admin', '', '', 'app/wechat_qrcode/save/<id>', 'POST', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1047, 1023, '', '渠道码详情', 'admin', '', '', 'app/wechat_qrcode/info/<id>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1048, 1023, '', '渠道码列表', 'admin', '', '', 'app/wechat_qrcode/list', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1049, 1023, '', '删除渠道码', 'admin', '', '', 'app/wechat_qrcode/del/<id>', 'DELETE', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1050, 1023, '', '渠道码状态切换', 'admin', '', '', 'app/wechat_qrcode/set_status/<id>/<status>', 'PUT', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1051, 1023, '', '渠道码用户列表', 'admin', '', '', 'app/wechat_qrcode/user_list/<qid>', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1052, 1023, '', '获取用户标签', 'admin', '', '', 'user/user_label', 'GET', '[]', 0, 0, 0, 1, '', '27/1023', 2, '', 0, '', 0),
(1053, 27, '', '充值配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/marketing/recharge', '27', 1, '', 0, 'marketing-recharge-index', 0),
(1055, 1009, '', '版本管理', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/app/app/version', '135/1009', 1, '', 0, 'admin-app-version', 0),
(1056, 12, '', '第三方接口配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config', '12', 1, '', 0, 'setting-other', 0),
(1057, 1056, '', '小票打印配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config/print/2/21', '12/1056', 1, '', 0, 'setting-other-print', 0),
(1058, 1056, '', '商品采集配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config/copy/2/41', '12/1056', 1, '', 0, 'setting-other-copy', 0),
(1059, 1056, '', '物流查询配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config/logistics/2/64', '12/1056', 1, '', 0, 'setting-other-logistics', 0),
(1060, 1056, '', '电子面单配置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/other_config/electronic/2/66', '12/1056', 1, '', 0, 'setting-other-electronic', 0),
(1061, 12, '', '协议设置', 'admin', '', '', '', '', '[]', 0, 1, 0, 1, '/admin/setting/agreement', '12', 1, '', 0, 'setting-agreement', 0)
SQL
            ],
            [
                'code' => 444,
                'type' => 3,
                'table' => "system_notification",
                'field' => "wechat_id",
                'findSql' => "show columns from `@table` like 'wechat_id'",
                'sql' => "ALTER TABLE `@table` ADD `wechat_id` int(10) NOT NULL DEFAULT '0' COMMENT '模版消息id'"
            ],
            [
                'code' => 444,
                'type' => 3,
                'table' => "system_notification",
                'field' => "routine_id",
                'findSql' => "show columns from `@table` like 'routine_id'",
                'sql' => "ALTER TABLE `@table` ADD `routine_id` int(10) NOT NULL DEFAULT '0' COMMENT '订阅消息id'"
            ],
            [
                'code' => 444,
                'type' => -1,
                'table' => "system_notification",
                'sql' => "truncate table `@table`"
            ],
            [
                'code' => 444,
                'type' => -1,
                'table' => "system_menus",
                'sql' => <<<SQL
INSERT INTO `@table` (`id`, `mark`, `name`, `title`, `is_system`, `is_app`, `is_wechat`, `is_routine`, `is_sms`, `is_ent_wechat`, `system_title`, `system_text`, `app_id`, `sms_id`, `wechat_id`, `routine_id`, `ent_wechat_text`, `variable`, `url`, `type`, `add_time`) VALUES
(1, 'bind_spread_uid', '绑定推广关系', '注册完成给上级发送', 1, 0, 1, 1, 0, 0, '绑定下级通知', '恭喜，又一员猛将将永久绑定到您的团队，用户{nickname}加入您的队伍！', 0, 0, 31, 4, '0', '{nikename}用户名', '', 1, 0),
(2, 'order_pay_success', '支付成功给用户发送', '支付成功给用户发送', 1, 0, 1, 1, 2, 0, '购买成功通知', '您购买的商品已支付成功，支付金额{pay_price}元，订单号{order_id},感谢您的光临！', 0, 440396, 26, 12, '0', '{order_id}订单号,{total_num}商品总数,{pay_price}支付金额', '', 1, 0),
(3, 'order_take', '确认收货提醒发送', '确认收货用户提醒发送', 1, 0, 1, 1, 2, 0, '确认收货通知', '亲，您的订单{order_id},商品{store_name}已确认收货,感谢您的光临！', 0, 440402, 17, 3, '0', '{order_id}订单号,{store_name}商品名称', '', 1, 0),
(4, 'price_revision', '改价提醒发送', '改价给用户提醒发送', 1, 0, 1, 0, 2, 0, '改价通知', '您的订单{order_id}，实际支付金额已被修改为{pay_price}', 0, 440410, 36, 0, '0', '{order_id}订单号,{pay_price}订单金额', '', 1, 0),
(5, 'order_refund', '退款成功提醒发送', '退款给用户提醒发送', 1, 0, 1, 1, 0, 0, '退款成功通知', '您的订单{order_id}已同意退款,退款金额{refund_price}元。', 0, 0, 18, 10, '0', '{order_id}订单号,{refund_price}退款金额,{pay_price}订单金额', '', 1, 0),
(7, 'recharge_success', '充值成功提醒发送', '充值成功给用户提醒发送', 1, 0, 1, 1, 0, 0, '充值成功通知', '您成功充值￥{price}，现剩余余额￥{now_money}元', 0, 0, 37, 11, '0', '{order_id}充值订单,{price}充值金额,{now_money}现有余额', '', 1, 0),
(8, 'integral_accout', '积分到账提醒发送', '积分到账给用户提醒发送', 1, 0, 1, 1, 0, 0, '积分到账通知', '亲，您成功获得积分{gain_integral}，现有积分{integral}', 0, 0, 41, 14, '0', '{order_id}订单号,{store_name}商品名称,{pay_price}支付金额,{gain_integral}获取积分,{integral}现有积分', '', 1, 0),
(9, 'order_brokerage', '佣金到账提醒发送', '佣金到账给用户提醒发送', 1, 0, 1, 1, 0, 0, '佣金到账通知', '亲，恭喜您成功获得佣金{brokerage_price}元', 0, 0, 30, 1, '0', '{goods_name}商品名称,{goods_price}商品金额,{brokerage_price}分佣金额', '', 1, 0),
(10, 'bargain_success', '砍价成功提醒发送', '砍价成功给用户提醒发送', 1, 0, 1, 1, 0, 0, '砍价成功通知', '亲，好腻害！你的朋友们已经帮你砍到底价了，商品名称{title}，底价{min_price}', 0, 0, 27, 7, '0', '{title}活动名称{min_price}最低价', '', 1, 0),
(11, 'order_user_groups_success', '拼团成功提醒发送', '拼团成功给用户提醒发送', 1, 0, 1, 1, 0, 0, '拼团成功通知', '亲，您的拼团已经完成了，拼团名称{title}，团长{nickname}', 0, 0, 23, 6, '0', '{title}活动名称,{nickname}团长,{count}拼团人数,{pink_time}开团时间', '', 1, 0),
(12, 'send_order_pink_fial', '拼团失败提醒发送', '拼团失败给用户提醒发送', 1, 0, 1, 1, 0, 0, '拼团失败通知', '亲，您的拼团失败，活动名称{title}', 0, 0, 24, 15, '0', '{title}活动名称{count}拼团人数', '', 1, 0),
(13, 'open_pink_success', '开团成功提醒发送', '开团成功给用户提醒发送', 1, 0, 1, 1, 0, 0, '开团成功通知', '亲，您已成功参与拼团，活动名称{title}', 0, 0, 28, 6, '0', '{title}活动名称,{nickname}团长,{count}拼团人数,{pink_time}开团时间', '', 1, 0),
(14, 'user_extract', '提现成功提醒发送', '提现成功给用户提醒发送', 1, 0, 1, 1, 0, 0, '提现成功通知', '亲，您成功提现佣金{extract_number}元', 0, 0, 38, 2, '0', '{extract_number}提现金额,{nickname}用户昵称,{date}提现时间', '', 1, 0),
(15, 'user_balance_change', '提现失败提醒发送', '提现失败给用户提醒发送', 1, 0, 1, 1, 0, 0, '提现失败通知', '亲，您发起的提现被驳回，返回佣金{extract_number}元', 0, 0, 39, 2, '0', '{extract_number}提现金额,{nickname}用户昵称,{date}提现时间,{message}失败原因', '', 1, 0),
(16, 'recharge_order_refund_status', '充值退款提醒发送', '充值退款给用户提醒发送', 1, 0, 1, 1, 0, 0, '充值退款通知', '亲，您充值的金额已退款,本次退款{refund_price}元', 0, 0, 18, 11, '0', '{refund_price}退款金额,{order_id}充值订单,{price}充值金额', '', 1, 0),
(17, 'send_order_refund_no_status', '退款申请未通过提醒发送', '退款申请未通过给用户提醒发送', 1, 0, 1, 1, 0, 0, '退款申请拒绝通知', '您好！您的订单{order_id}已拒绝退款。', 0, 0, 18, 10, '0', '{order_id}订单号,{store_name}商品名称,{pay_price}订单金额', '', 1, 0),
(18, 'send_order_apply_refund', '申请退款给客服发消息', '申请退款给客服发消息', 1, 0, 0, 0, 2, 1, '您有新的退款待处理', '您有一笔退款订单待处理，订单号{order_id}!', 0, 440407, 0, 0, '您有个订单退款请注意查收\\n订单号：{order_id}', '{admin_name}管理员,{order_id}订单号', '', 2, 0),
(19, 'admin_pay_success_code', '下单给客服发消息', '下单支付给客服发消息', 1, 0, 0, 0, 2, 1, '您有新的订单待处理', '您有一笔支付成功的订单待处理，订单号{order_id}!', 0, 440405, 0, 0, '您有个新订单请注意查收\\n订单号：{order_id}', '{admin_name}管理员,{order_id}订单号', '', 2, 0),
(20, 'order_deliver_success', '发货提醒发送', '发货用户提醒发送', 1, 0, 1, 1, 2, 0, '发货通知', '亲爱的用户{nickname}您的商品{store_name}，订单号{order_id}已发货，请注意查收', 0, 441596, 20, 5, '0', '{nickname}用户昵称,{store_name}商品名称,{order_id}订单号,{delivery_name}配送员姓名,{delivery_id}配送员电话,{user_address}收货地址', '', 1, 0),
(21, 'order_postage_success', '发货快递提醒发送', '发货快递用户提醒发送', 1, 0, 1, 1, 2, 0, '发货通知', '亲爱的用户{nickname}您的商品{store_name}，订单号{order_id}已发货，请注意查收', 0, 441596, 16, 9, '0', '{nickname}用户昵称,{store_name}商品名称,{order_id}订单号,{delivery_name}快递名称,{delivery_id}快递单号,{user_address}收货地址', '', 1, 0),
(22, 'send_order_pink_clone', '取消拼团提醒发送', '取消拼团给用户提醒发送', 1, 0, 1, 1, 0, 0, '取消拼团通知', '亲，您的拼团取消，活动名称{title}', 0, 0, 24, 15, '0', '{title}活动名称{count}拼团人数', '', 1, 0),
(23, 'can_pink_success', '参团成功提醒发送', '参团成功给用户提醒发送', 1, 0, 1, 1, 0, 0, '参团成功通知', '亲，您已成功参与拼团，活动名称{title}', 0, 0, 23, 6, '0', '{title}活动名称,{nickname}团长,{count}拼团人数,{pink_time}开团时间', '', 1, 0),
(24, 'kefu_send_extract_application', '提现申请给客服发消息', '提现申请给客服发消息', 1, 0, 0, 0, 0, 1, '你有个新的提现申请待处理', '您有一笔提现申请待处理，提现金额{money}!', 0, 0, 0, 0, '您有个提现申请请注意查收\\n>提现金额{money}', '{nickname}用户昵称,{money}提现金额', 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=ae3s-safwea-aa', 2, 0),
(25, 'send_admin_confirm_take_over', '收货给客服发消息', '收货给客服发消息', 1, 0, 0, 0, 2, 1, '你有个新的用户收货待处理', '您有一笔订单已经确认收货，订单号{order_id}!', 0, 440408, 0, 0, '您有个订单确认收货\\n>订单号{order_id}', '{storeTitle}商品名称,{order_id}订单号', '', 2, 0),
(26, 'order_pay_false', '提醒付款通知', '提醒付款通知用户提醒发送', 1, 0, 1, 0, 2, 0, '提醒付款通知', '您有未付款订单,订单号为:{order_id}，商品数量有限，请及时付款。', 0, 440409, 40, 0, '0', '', '', 1, 0)
SQL
            ],
            [
                'code' => 444,
                'type' => 3,
                'table' => "user",
                'field' => "is_del",
                'findSql' => "show columns from `@table` like 'is_del'",
                'sql' => "ALTER TABLE `@table` ADD `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否注销'"
            ],
            [
                'code' => 444,
                'type' => 1,
                'table' => "user_cancel",
                'findSql' => "select * from information_schema.tables where table_name ='@table'",
                'sql' => "CREATE TABLE IF NOT EXISTS `@table` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '用户提交申请时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1通过，2拒绝',
  `up_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
            ],
            [
                'code' => 444,
                'type' => 3,
                'table' => "user_extract",
                'field' => "wechat_order_id",
                'findSql' => "show columns from `@table` like 'wechat_order_id'",
                'sql' => "ALTER TABLE `@table` ADD `wechat_order_id` varchar(32) NOT NULL DEFAULT '' COMMENT '微信订单ID'"
            ],
            [
                'code' => 444,
                'type' => 3,
                'table' => "wechat_user",
                'field' => "is_del",
                'findSql' => "show columns from `@table` like 'is_del'",
                'sql' => "ALTER TABLE `@table` ADD `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否注销'"
            ],
            [
                'code' => 444,
                'type' => -1,
                'table' => "wechat_user",
                'sql' => "ALTER TABLE `@table` DROP INDEX `openid`, ADD UNIQUE `openid` (`openid`, `uid`) USING BTREE"
            ],
        ];
        return $data;
    }

    /**
     * 升级列表
     * @return mixed
     */
    public function upgradeList()
    {
        return app('json')->success($this->services->getUpgradeList());
    }

    /**
     * 可升级列表
     * @return mixed
     */
    public function upgradeableList()
    {
        return app('json')->success($this->services->getUpgradeableList());
    }

    /**
     * 可升级列表
     * @return mixed
     */
    public function agreement()
    {
        return app('json')->success($this->services->getAgreement());
    }

    /**
     * 下载升级包
     * @param $packageKey
     * @return mixed
     */
    public function download($packageKey)
    {
        if (empty($packageKey)) {
            return app('json')->fail(100100);
        }

        $this->services->packageDownload($packageKey);
        return app('json')->success();
    }

    /**
     * 升级进度
     * @return mixed
     */
    public function progress()
    {
        $result = $this->services->getProgress();
        return app('json')->success($result);
    }

    /**
     * 获取升级状态
     * @return mixed
     */
    public function upgradeStatus()
    {
        $data = $this->services->getUpgradeStatus();
        return app('json')->success($data);
    }

    /**
     * 升级记录
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     */
    public function upgradeLogList()
    {
        $data = $this->services->getUpgradeLogList();
        return app('json')->success($data);
    }

    /**
     * 导出备份
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     */
    public function export($id, $type)
    {
        if (!$id || !$type) {
            return app('json')->fail(100026);
        }
        return app('json')->success($this->services->export((int)$id, $type));
    }
}
