<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

use think\facade\Route;

/**
 * 分销管理 相关路由
 */
Route::group('statistic', function () {
    /** 用户统计 */
    //用户基础
    Route::get('user/get_basic', 'v1.statistic.UserStatistic/getBasic');
    //用户增长趋势
    Route::get('user/get_trend', 'v1.statistic.UserStatistic/getTrend');
    //微信用户
    Route::get('user/get_wechat', 'v1.statistic.UserStatistic/getWechat');
    //微信用户成长趋势
    Route::get('user/get_wechat_trend', 'v1.statistic.UserStatistic/getWechatTrend');
    //用户地域排行
    Route::get('user/get_region', 'v1.statistic.UserStatistic/getRegion');
    //用户性别
    Route::get('user/get_sex', 'v1.statistic.UserStatistic/getSex');
    //商品数据导出
    Route::get('user/get_excel', 'v1.statistic.UserStatistic/getExcel');

    /** 商品统计 */
    //商品基础
    Route::get('product/get_basic', 'v1.statistic.ProductStatistic/getBasic');
    //商品趋势
    Route::get('product/get_trend', 'v1.statistic.ProductStatistic/getTrend');
    //商品排行
    Route::get('product/get_product_ranking', 'v1.statistic.ProductStatistic/getProductRanking');
    //商品数据导出
    Route::get('product/get_excel', 'v1.statistic.ProductStatistic/getExcel');

    //商品统计路由
    Route::get('product/product_survey', 'v1.statistic.ProductStatistic/productSurvey');
    /** 交易统计 */
    //今日营业额统计
    Route::get('trade/top_trade', 'v1.statistic.TradeStatistic/topTrade');
    Route::get('trade/bottom_trade', 'v1.statistic.TradeStatistic/bottomTrade');
})->middleware([
    \app\http\middleware\AllowOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminCkeckRoleMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class
]);
