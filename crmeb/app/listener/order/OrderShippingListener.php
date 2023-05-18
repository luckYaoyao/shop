<?php

namespace app\listener\order;

use app\model\order\StoreOrder;
use app\services\order\StoreOrderCartInfoServices;
use app\services\order\StoreOrderServices;
use app\services\wechat\WechatUserServices;
use crmeb\exceptions\AdminException;
use crmeb\interfaces\ListenerInterface;
use crmeb\services\easywechat\orderShipping\MiniOrderService;

class OrderShippingListener implements ListenerInterface
{
    public function handle($event): void
    {
        /** @var StoreOrder $order */
        [$order, $delivery_type, $delivery_id, $delivery_name] = $event;
        $order_shipping_open = sys_config('order_shipping_open', 0);  // 小程序发货信息管理服务开关
        if ($order && $order_shipping_open) {
            if ($order['is_channel'] == 1 && $order['pay_type'] == 'weixin') {
                $out_trade_no = $order['order_id'];
                //判断订单是否拆单
                $delivery_mode = 1;
                $is_all_delivered = true;
                if ($order['pid'] > 0) {
                    $delivery_mode = 2;
                    // 判断订单是否全部发货
                    /** @var StoreOrderServices $orderServices */
                    $orderServices = app()->make(StoreOrderServices::class);
                    $is_all_delivered = $orderServices->checkSubOrderNotSend((int)$order['pid'], (int)$order['id']);
                    $p_order = $orderServices->get((int)$order['pid']);
                    if (!$p_order) {
                        throw new AdminException('拆单异常');
                    }
                    $out_trade_no = $p_order['order_id'];
                }

                // 整理商品信息
                /** @var StoreOrderCartInfoServices $orderInfoServices */
                $orderInfoServices = app()->make(StoreOrderCartInfoServices::class);
                $item_desc = $orderInfoServices->getCarIdByProductTitle((int)$order['id'], true);
                $shipping_list = [
                    ['item_desc' => $item_desc]
                ];
                //判断订单物流模式
                if ($order['shipping_type'] == 1) {
                    if ($delivery_type == 1) {
                        $shipping_list = [
                            [
                                'tracking_no' => $delivery_id ?? '',
                                'express_company' => $delivery_name ?? '',
                                'item_desc' => $item_desc,
                                'contact' => [
                                    'receiver_contact' => $order['user_phone']
                                ]
                            ]
                        ];
                    }
                    $logistics_type = $delivery_type;
                } else {
                    $logistics_type = 4;
                }
                //查找支付者openid
                /** @var WechatUserServices $wechatUserService */
                $wechatUserService = app()->make(WechatUserServices::class);
                $payer_openid = $wechatUserService->uidToOpenid($order['pay_uid'], 'routine');
                if (empty($payer_openid)) {
                    throw new AdminException('订单支付人异常');
                }
                MiniOrderService::shippingByTradeNo($out_trade_no, $logistics_type, $shipping_list, $payer_openid, $delivery_mode, $is_all_delivered);
            }
        }
    }
}
