<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace crmeb\services\easywechat\orderShipping;

use EasyWeChat\Payment\Merchant;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use EasyWeChat\MiniProgram\AccessToken;

/**
 * Class ServiceProvider.
 *
 * @package crmeb\services\easywechat\order_ship
 * @package crmeb\services\easywechat\mini_express
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $pimple)
    {
        $pimple['order_ship'] = function ($pimple) {
            return new OrderClient($pimple['access_token'], $pimple);
        };
    }
}
