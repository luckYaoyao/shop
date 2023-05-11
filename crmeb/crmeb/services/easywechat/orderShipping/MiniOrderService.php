<?php

namespace crmeb\services\easywechat\orderShipping;

use crmeb\services\easywechat\Application;
use crmeb\services\SystemConfigService;

class MiniOrderService
{

    /**
     * @var Application
     */
    protected static $instance;

    /**
     * @param array $config
     * @return array[]
     *
     * @date 2023/05/09
     * @author yyw
     */
    protected static function options(array $config = [])
    {
        $payment = SystemConfigService::more(['routine_appId', 'routine_appsecret', 'pay_weixin_mchid', 'pay_new_weixin_open', 'pay_new_weixin_mchid']);
        return [
            'order_shipping' => [
                'appid' => $payment['routine_appId'] ?? '',
                'secret' => $payment['routine_appsecret'] ?? '',
                'merchant_id' => empty($payment['pay_new_weixin_open']) ? trim($payment['pay_weixin_mchid']) : trim($payment['pay_new_weixin_mchid']),
            ]
        ];
    }

    /**
     * 初始化
     * @param bool $cache
     * @return Application
     */
    protected static function application($cache = false)
    {
        (self::$instance === null || $cache === true) && (self::$instance = new Application(self::options()));
        return self::$instance;
    }

    protected static function order()
    {
        return self::application()->order_ship;
    }


    /**
     * 上传订单
     * @param string $out_trade_no
     * @param int $logistics_type
     * @param array $shipping_list
     * @param string $payer_openid
     * @param int $delivery_mode
     * @param bool $is_all_delivered
     * @return array
     *
     * @date 2023/05/09
     * @author yyw
     */
    public static function shippingByTradeNo(string $out_trade_no, int $logistics_type, array $shipping_list, string $payer_openid, int $delivery_mode = 1, bool $is_all_delivered = true)
    {
        return self::order()->shippingByTradeNo($out_trade_no, $logistics_type, $shipping_list, $payer_openid, $delivery_mode, $is_all_delivered);
    }

    /**
     * 合单
     * @param string $out_trade_no
     * @param int $logistics_type
     * @param array $sub_orders
     * @param string $payer_openid
     * @param int $delivery_mode
     * @param bool $is_all_delivered
     * @return array
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public static function combinedShippingByTradeNo(string $out_trade_no, int $logistics_type, array $sub_orders, string $payer_openid, int $delivery_mode = 2, bool $is_all_delivered = false)
    {
        return self::order()->combinedShippingByTradeNo($out_trade_no, $logistics_type, $sub_orders, $payer_openid, $delivery_mode, $is_all_delivered);
    }

    /**
     * 签收通知
     * @param string $merchant_trade_no
     * @param string $received_time
     * @return array
     *
     * @date 2023/05/09
     * @author yyw
     */
    public static function notifyConfirmByTradeNo(string $merchant_trade_no, string $received_time)
    {
        return self::order()->notifyConfirmByTradeNo($merchant_trade_no, $received_time);
    }

    /**
     * 判断是否开通
     * @return bool
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @date 2023/05/09
     * @author yyw
     */
    public static function isManaged()
    {
        return self::order()->checkManaged();
    }


    /**
     * 设置小修跳转路径
     * @param $path
     * @return array
     * @throws \EasyWeChat\Core\Exceptions\HttpException
     *
     * @date 2023/05/10
     * @author yyw
     */
    public static function setMesJumpPath($path)
    {
        return self::order()->setMesJumpPath($path);
    }


}
