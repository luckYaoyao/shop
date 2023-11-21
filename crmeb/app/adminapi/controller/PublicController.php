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

namespace app\adminapi\controller;


use app\Request;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\system\SystemRouteServices;
use crmeb\services\CacheService;
use think\Response;

/**
 * 公共控制器   
 */
class PublicController
{

    /**
     * 下载文件
     * @param string $key
     * @return Response|\think\response\File
     */
    public function download(string $key = '')
    {
        if (!$key) {
            return Response::create()->code(500);//参数错误 
        }
        $fileName = CacheService::get($key);//缓存中的文件路径
        //判断文件是否存在  
        if (is_array($fileName) && isset($fileName['path']) && isset($fileName['fileName']) && $fileName['path'] && $fileName['fileName'] && file_exists($fileName['path'])) {
            CacheService::delete($key);//删除缓存
            return download($fileName['path'], $fileName['fileName']);//返回文件
        }
        return Response::create()->code(500);
    }

    /**
     * 获取workerman请求域名
     * @return mixed
     */
    public function getWorkerManUrl()
    {
        //获取当前长链接域名
        return app('json')->success(getWorkerManUrl());
    }

    /**
     * 扫码上传
     * @param Request $request
     * @param int $upload_type
     * @param int $type
     * @return Response
     * @author 吴汐
     * @email 442384644@qq.com
     * @date 2023/06/13
     */
    public function scanUpload(Request $request, $upload_type = 0, $type = 0)
    {
        [$file, $uploadToken, $pid] = $request->postMore([
            ['file', 'file'],
            ['uploadToken', ''],
            ['pid', 0]
        ], true);
        //附件服务
        $service = app()->make(SystemAttachmentServices::class);
        // 获取缓存中的上传令牌并与当前上传令牌比较
        if (CacheService::get('scan_upload') != $uploadToken) { 
            return app('json')->fail(410086); // 如果不一致则返回错误码410086
        }
        //上传文件
        $service->upload((int)$pid, $file, $upload_type, $type, '', $uploadToken);
        return app('json')->success(100032);
    }

    public function import(Request $request)
    {
        //获取文件路径
        $filePath = $request->param('file_path', '');
        if (empty($filePath)) {
            return app('json')->fail(12894);
        }
        //导入数据
        app()->make(SystemRouteServices::class)->import($filePath);
        return app('json')->success(100010);
    }
}
