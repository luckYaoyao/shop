<?php

namespace app\listener\out;

use app\jobs\OutPushJob;
use app\services\out\OutAccountServices;
use crmeb\interfaces\ListenerInterface;

class OutPush implements ListenerInterface
{
    public function handle($event): void
    {
        [$type, $orderId] = $event;
        /** @var OutAccountServices $outAccountServices */
        $outAccountServices = app()->make(OutAccountServices::class);
        $outAccountList = $outAccountServices->selectList(['is_del' => 0, 'status' => 1])->toArray();
        foreach ($outAccountList as $item) {
            if ($item['push_open'] == 1) {
                if ($type == 'order_create_push') {
                    OutPushJob::dispatchDo('orderCreate', [$orderId, $item['order_create_push']]);
                } elseif ($type == 'order_pay_push') {
                    OutPushJob::dispatchDo('paySuccess', [$orderId, $item['order_pay_push']]);
                }elseif($type == 'refund_create_push'){
                    OutPushJob::dispatchDo('refundCreate', [$orderId, $item['refund_create_push']]);
                }elseif($type == 'refund_cancel_push'){
                    OutPushJob::dispatchDo('refundCancel', [$orderId, $item['refund_cancel_push']]);
                }
            }
        }
    }
}