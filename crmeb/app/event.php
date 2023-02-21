<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

// 事件定义文件

return [
    'bind' => [

    ],

    'listen' => [
        'AppInit' => [],
        'HttpRun' => [],
        'HttpEnd' => [\app\listener\http\HttpEndListener::class], //HTTP请求结束回调事件
        'LogLevel' => [],
        'LogWrite' => [],
        'queue.start' => [\app\listener\queue\QueueStartListener::class],
        'user.login' => [\app\listener\user\LoginListener::class],
        'admin.login' => [\app\listener\admin\AdminLoginListener::class],//管理员登录
        'user.register' => [\app\listener\user\RegisterListener::class], //用户注册后置事件
        'wechat.auth' => [\app\listener\wechat\AuthListener::class], //用户授权后置事件
        'order.orderCreateAfter' => [\app\listener\order\OrderCreateAfterListener::class], //订单创建后置事件
        'order.orderPaySuccess' => [\app\listener\order\OrderPaySuccessListener::class], //订单支付成功后置事件
        'order.orderDelivery' => [\app\listener\order\OrderDeliveryListener::class], //订单发货后置事件
        'order.orderTake' => [\app\listener\order\OrderTakeListener::class], //订单收货后置事件
        'order.orderRefundCreateAfter' => [\app\listener\order\OrderRefundCreateAfterListener::class], //售后单生成后置事件
        'order.orderRefundCancelAfter' => [\app\listener\order\OrderRefundCancelAfterListener::class], //售后单取消后置事件
        'out.outPush' => [\app\listener\out\OutPushListener::class], //对外推送事件
        'user.userLevel' => [\app\listener\user\UserLevelListener::class], //用户升级事件
        'user.userVisit' => [\app\listener\user\UserVisitListener::class], //用户访问事件
        'notice.notice' => [\app\listener\notice\NoticeListener::class], //通知->消息事件
        'pay.notify' => [\app\listener\pay\NotifyListener::class],//支付异步回调
        'Crontab' => [\app\listener\crontab\SystemCrontabListener::class],//定时任务事件
    ],
];


