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
use think\facade\Route;

/**
 * 应用模块 路由示例 
 * 注意：
 * 1. 路由示例只做参考，请根据实际情况修改
 * 2. 如果需要使用路由中间件，请在路由前面加上中间件名称，如：middleware(\app\middleware\ApiAuthMiddleware::class)
 * 3. 如果需要使用路由参数，请在路由后面加上参数，如：'id' => '\d+', 多个参数用英文逗号分隔
 * 4. 如果需要使用路由别名，请在路由后面加上别名，如：'id' => 'user_id', 多个参数用英文逗号分隔
 * 5. 如果需要使用路由变量，请在路由后面加上变量，如：':id' => '\
 * 6. 如果需要使用路由正则，请在路由后面加上正则，如：':id' => '/
 * 严格按照这种方法写，可以快速生成接口文档和添加接口权限设置
 */
Route::group('demo', function () {

    /** 路由示例 */
    Route::group('路由示例',function () {
        //get请求路由，第一参数为路由地址，第二参数为处理函数 目录：app/controller/v1/application/wechat/menus
        Route::get('wechat/demo1', 'v1.application.wechat.menus/index')->option([
            'real_name' => '微信公众号菜单列表' //接口名称
        ]);
        //post请求路由，第一参数为路由地址，第二参数为处理函数 目录：app/controller/v1/application/wechat/menus
        Route::post('wechat/demo2', 'v1.application.wechat.menus/save')->option([
            'real_name' => '保存微信公众号菜单' //接口名称
        ]);
        //delete请求路由，第一参数为路由地址，第二参数为处理函数 目录：app/controller/v1/application/wechat/menus
        Route::delete('wechat/demo3/:id', 'v1.application.wechat.menus/delete')->option([
            'real_name' => '删除图文' //接口名称
        ]);
        //put请求路由，第一参数为路由地址，第二参数为处理函数 目录：app/controller/v1/application/wechat/menus
        Route::put('wechat/demo3/:id', 'v1.application.wechat.menus/save')->option([
            'real_name' => '编辑图文' //接口名称
        ]);

        //资源路由可以快速创建增删改查路由 客服反馈接口 第一参数为路由地址，第二参数为处理函数 目录：app/controller/v1/kefu/StoreServiceFeedback
        Route::resource('feedback', 'v1.kefu.StoreServiceFeedback')->only([ //只允许index read edit update 四个操作
            //GET请求 对应方法index
            'index', 
            //DELETE请求 对应方法delete
            // 'delete', 
            //POST请求 对应方法save
            'save', 
            //GET请求 对应方法read
            'read', 
            //POST请求 对应方法create
            // 'create', 
            //PUT请求 对应方法update
            'update', 
            //GET请求 对应方法edit
            'edit' 
            ])->option([
            'real_name' => [//接口名称
                'index' => '获取用户反馈列表', 
                // 'delete' => '删除用户反馈列表', 
                'save' => '保存用户反馈列表', 
                'read' => '获取用户反馈', 
                // 'create' => '添加用户反馈列表', 
                'update' => '修改用户反馈', 
                'edit' => '获取修改用户反馈表单', 
            ]
        ]);
    })->option([
        'parent' => 'app', //父级路由
        'cate_name' => '公众号' //分组名称
    ]);

})->middleware([//中间件
    \app\http\middleware\AllowOriginMiddleware::class, //允许跨域
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class, //后台管理员认证
    \app\adminapi\middleware\AdminCheckRoleMiddleware::class, //后台管理员角色检测
    \app\adminapi\middleware\AdminLogMiddleware::class //后台管理员日志
])->option([
    'mark' => 'demo', //标记
    'mark_name' => 'demo演示' //标记名称
]);
