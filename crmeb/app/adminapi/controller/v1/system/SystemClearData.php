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
namespace app\adminapi\controller\v1\system;


use think\facade\App;
use app\adminapi\controller\AuthController;
use app\services\system\SystemClearServices;
use app\services\product\product\StoreProductServices;
use app\services\system\attachment\SystemAttachmentServices;


/**
 * 清除默认数据理控制器
 * Class SystemClearData
 * @package app\admin\controller\system
 */
class SystemClearData extends AuthController
{
    /**
     * 构造方法
     * SystemClearData constructor.
     * @param App $app
     * @param SystemClearServices $services
     */
    public function __construct(App $app, SystemClearServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 统一方法
     * @param $type
     * @return mixed
     */
    public function index($type)
    {
        switch ($type) {
            case 'temp':
                return $this->userTemp();
                break;
            case 'recycle':
                return $this->recycleProduct();
                break;
            case 'store':
                return $this->storeData();
                break;
            case 'category':
                return $this->categoryData();
                break;
            case 'order':
                return $this->orderData();
                break;
            case 'kefu':
                return $this->kefuData();
                break;
            case 'wechat':
                return $this->wechatData();
                break;
            case 'attachment':
                return $this->attachmentData();
                break;
            case 'article':
                return $this->articleData();
                break;
            case 'system':
                return $this->systemData();
                break;
            case 'user':
                return $this->userRelevantData();
                break;
            default:
                return app('json')->fail(100100);
        }
    }

    /**
     * 清除用户生成的临时附件
     * @return mixed
     */
    public function userTemp()
    {
        /** @var SystemAttachmentServices $services */
        $services = app()->make(SystemAttachmentServices::class);
        $imageUrl = $services->getColumn(['module_type' => 2], 'att_dir');
        foreach ($imageUrl as $item) {
            @unlink(app()->getRootPath() . 'public' . $item);
        }
        $services->delete(2, 'module_type');
        $this->services->clearData(['qrcode'], true);
        return app('json')->success(100046);
    }

    /**
     * 清除回收站商品
     * @return mixed
     */
    public function recycleProduct()
    {
        /** @var StoreProductServices $services */
        $services = app()->make(StoreProductServices::class);
        $services->delete(1, 'is_del');
        return app('json')->success(100046);
    }

    /**
     * 清除用户数据
     * @return mixed
     */
    public function userRelevantData()
    {
        $this->services->clearData([
            'agent_level_task_record',
            'member_card',
            'member_card_batch',
            'capital_flow',
            'delivery_service',
            'division_agent_apply',
            'luck_lottery_record',
            'other_order',
            'other_order_status',
            'qrcode',
            'sms_record',
            'store_bargain_user',
            'store_bargain_user_help',
            'store_cart',
            'store_coupon_issue_user',
            'store_coupon_user',
            'store_integral_order',
            'store_integral_order_status',
            'store_order',
            'store_order_cart_info',
            'store_order_economize',
            'store_order_invoice',
            'store_order_refund',
            'store_order_status',
            'store_pink',
            'store_product_relation',
            'store_product_reply',
            'store_service',
            'store_service_feedback',
            'store_service_log',
            'store_service_record',
            'store_visit',
            'system_store_staff',
            'user',
            'user_address',
            'user_bill',
            'user_brokerage',
            'user_brokerage_frozen',
            'user_cancel',
            'user_enter',
            'user_extract',
            'user_friends',
            'user_group',
            'user_invoice',
            'user_label',
            'user_label_relation',
            'user_level',
            'user_money',
            'user_notice',
            'user_notice_see',
            'user_recharge',
            'user_search',
            'user_sign',
            'user_spread',
            'user_visit',
            'wechat_user',
        ], true);
        $this->services->delDirAndFile('./public/uploads/store/comment');
        return app('json')->success(100046);
    }

    /**
     * 清除商城数据
     * @return mixed
     */
    public function storeData()
    {
        $this->services->clearData([
            'eb_agent_level',
            'eb_agent_level_task',
            'eb_agent_level_task_record',
            'eb_article',
            'eb_article_category',
            'eb_article_content',
            'eb_auxiliary',
            'eb_cache',
            'eb_capital_flow',
            'eb_category',
            'eb_delivery_service',
            'eb_division_agent_apply',
            'eb_live_anchor',
            'eb_live_goods',
            'eb_live_room',
            'eb_live_room_goods',
            'eb_luck_lottery',
            'eb_luck_lottery_record',
            'eb_luck_prize',
            'eb_member_card',
            'eb_member_card_batch',
            'eb_member_right',
            'eb_member_ship',
            'eb_message_system',
            'eb_other_order',
            'eb_other_order_status',
            'eb_qrcode',
            'eb_sms_record',
            'eb_store_advance',
            'eb_store_bargain',
            'eb_store_bargain_user',
            'eb_store_bargain_user_help',
            'eb_store_cart',
            'eb_store_category',
            'eb_store_combination',
            'eb_store_coupon_issue',
            'eb_store_coupon_issue_user',
            'eb_store_coupon_product',
            'eb_store_coupon_user',
            'eb_store_integral',
            'eb_store_integral_order',
            'eb_store_integral_order_status',
            'eb_store_order',
            'eb_store_order_cart_info',
            'eb_store_order_economize',
            'eb_store_order_invoice',
            'eb_store_order_refund',
            'eb_store_order_status',
            'eb_store_pink',
            'eb_store_product',
            'eb_store_product_attr',
            'eb_store_product_attr_result',
            'eb_store_product_attr_value',
            'eb_store_product_cate',
            'eb_store_product_coupon',
            'eb_store_product_description',
            'eb_store_product_log',
            'eb_store_product_relation',
            'eb_store_product_reply',
            'eb_store_product_rule',
            'eb_store_product_virtual',
            'eb_store_seckill',
            'eb_store_service',
            'eb_store_service_feedback',
            'eb_store_service_log',
            'eb_store_service_record',
            'eb_store_visit',
            'eb_system_file',
            'eb_system_log',
            'eb_system_notice',
            'eb_system_notice_admin',
            'eb_system_store',
            'eb_system_store_staff',
            'eb_user',
            'eb_user_address',
            'eb_user_bill',
            'eb_user_brokerage',
            'eb_user_brokerage_frozen',
            'eb_user_cancel',
            'eb_user_enter',
            'eb_user_extract',
            'eb_user_friends',
            'eb_user_group',
            'eb_user_invoice',
            'eb_user_label',
            'eb_user_label_relation',
            'eb_user_level',
            'eb_user_money',
            'eb_user_notice',
            'eb_user_notice_see',
            'eb_user_recharge',
            'eb_user_search',
            'eb_user_sign',
            'eb_user_spread',
            'eb_user_visit',
            'eb_wechat_key',
            'eb_wechat_media',
            'eb_wechat_message',
            'eb_wechat_news_category',
            'eb_wechat_qrcode',
            'eb_wechat_qrcode_cate',
            'eb_wechat_qrcode_record',
            'eb_wechat_reply',
            'eb_wechat_user',
        ], true);
        return app('json')->success(100046);
    }

    /**
     * 清除商品分类
     * @return mixed
     */
    public function categoryData()
    {
        $this->services->clearData(['store_category'], true);
        return app('json')->success(100046);
    }

    /**
     * 清除订单数据
     * @return mixed
     */
    public function orderData()
    {
        $this->services->clearData([
            'other_order',
            'other_order_status',
            'store_cart',
            'store_integral_order',
            'store_integral_order_status',
            'store_order',
            'store_order_cart_info',
            'store_order_economize',
            'store_order_invoice',
            'store_order_refund',
            'store_order_status',
            'store_pink',
        ], true);
        return app('json')->success(100046);
    }

    /**
     * 清除客服数据
     * @return mixed
     */
    public function kefuData()
    {
        $this->services->clearData([
            'store_service',
            'store_service_log',
            'store_service_record',
            'store_service_feedback',
            'store_service_speechcraft'
        ], true);
        $this->services->delDirAndFile('./public/uploads/store/service');
        return app('json')->success(100046);
    }

    /**
     * 清除微信管理数据
     * @return mixed
     */
    public function wechatData()
    {
        $this->services->clearData([
            'cache',
            'wechat_key',
            'wechat_media',
            'wechat_message',
            'wechat_news_category',
            'wechat_qrcode',
            'wechat_qrcode_cate',
            'wechat_qrcode_record',
            'wechat_reply'
        ], true);
        $this->services->delDirAndFile('./public/uploads/wechat');
        return app('json')->success(100046);
    }

    /**
     * 清除所有附件
     * @return mixed
     */
    public function attachmentData()
    {
        $this->services->clearData([
            'system_attachment',
            'system_attachment_category'
        ], true);
        $this->services->delDirAndFile('./public/uploads/');
        return app('json')->success(100046);
    }

    //清除内容分类
    public function articleData()
    {
        $this->services->clearData([
            'article_category',
            'article',
            'article_content'
        ], true);
        return app('json')->success(100046);
    }

    //清除系统记录
    public function systemData()
    {
        $this->services->clearData([
            'system_notice_admin',
            'system_log'
        ], true);
        return app('json')->success(100046);
    }

    /**
     * 替换域名方法
     * @return mixed
     */
    public function replaceSiteUrl()
    {
        list($url) = $this->request->postMore([
            ['url', '']
        ], true);
        if (!$url)
            return app('json')->fail(400304);
        if (!verify_domain($url))
            return app('json')->fail(400305);
        $this->services->replaceSiteUrl($url);
        return app('json')->success(400306);
    }
}
