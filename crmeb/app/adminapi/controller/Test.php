<?php
/**
 * @author: liaofei<136327134@qq.com>
 * @day: 2020/9/12
 */

namespace app\adminapi\controller;

use crmeb\services\easywechat\orderShipping\MiniOrderService;

class Test
{
    public function index()
    {
        $ship_list = [
            [
                'item_desc' => '蓝牙音乐手表 | Jeep智能表蓝牙'
            ]
        ];
        MiniOrderService::shippingByTradeNo('cp179280225803173888', 3, $ship_list, 'oZEAhsy60rrq96hE86LmBb9v_Kes');
    }
}

