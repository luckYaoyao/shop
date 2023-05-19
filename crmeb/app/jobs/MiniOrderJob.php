<?php

namespace app\jobs;

use crmeb\basic\BaseJobs;
use crmeb\services\easywechat\orderShipping\MiniOrderService;
use crmeb\traits\QueueTrait;
use EasyWeChat\Core\Exceptions\HttpException;
use think\Exception;

class MiniOrderJob extends BaseJobs
{
    use QueueTrait;

    /**
     * @throws HttpException
     */
    public function doJob($shippingOrder)
    {
        try {
            MiniOrderService::shippingByTradeNo($shippingOrder['out_trade_no'], $shippingOrder['logistics_type'], $shippingOrder['shipping_list'], $shippingOrder['payer_openid'], $shippingOrder['delivery_mode'] ?? 1, $shippingOrder['is_all_delivered'] ?? true);
        } catch (HttpException $e) {
            // 订单异常处理
            throw new HttpException($e);
        }
    }
}
