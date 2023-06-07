<?php

namespace crmeb\services\upload\storage;

use crmeb\exceptions\AdminException;
use crmeb\services\upload\extend\obs\Client as TyClient;
use crmeb\services\upload\BaseUpload;

class Obs extends BaseUpload
{
    /**
     * accessKey
     * @var mixed
     */
    protected $accessKey;

    /**
     * secretKey
     * @var mixed
     */
    protected $secretKey;

    /**
     * 句柄
     * @var TyClient
     */
    protected $handle;

    /**
     * 空间域名 Domain
     * @var mixed
     */
    protected $uploadUrl;

    /**
     * 存储空间名称  公开空间
     * @var mixed
     */
    protected $storageName;

    /**
     * COS使用  所属地域
     * @var mixed|null
     */
    protected $storageRegion;

    /**
     * @var string
     */
    protected $cdn;

    public function move(string $file = 'file', bool $isStream = false, string $fileContent = null)
    {
        if (!$isStream) {
            $fileHandle = app()->request->file($file);
            if (!$fileHandle) {
                return $this->setError('上传的文件不存在');
            }
            if ($this->validate) {
                if (!in_array(pathinfo($fileHandle->getOriginalName(), PATHINFO_EXTENSION), $this->validate['fileExt'])) {
                    return $this->setError('不合法的文件后缀');
                }
                if (filesize($fileHandle) > $this->validate['filesize']) {
                    return $this->setError('文件过大');
                }
                if (!in_array($fileHandle->getOriginalMime(), $this->validate['fileMime'])) {
                    return $this->setError('不合法的文件类型');
                }
            }
            $key = $this->saveFileName($fileHandle->getRealPath(), $fileHandle->getOriginalExtension());

            $body = $fileHandle->getRealPath();
        } else {
            $key = $file;
            $body = $fileContent;
        }
        $key = $this->getUploadPath($key);

        try {
            $uploadInfo = $this->app()->putObject($key, $body);
            $this->fileInfo->uploadInfo = $uploadInfo;
            $this->fileInfo->realName = $fileHandle->getOriginalName();
            $this->fileInfo->filePath = ($this->cdn ?: $this->uploadUrl) . '/' . $key;
            $this->fileInfo->fileName = $key;
            $this->fileInfo->filePathWater = $this->water($this->fileInfo->filePath);
            $this->authThumb && $this->thumb($this->fileInfo->filePath);
            return $this->fileInfo;
        } catch (\Throwable $e) {
            return $this->setError($e->getMessage());
        }
    }

    public function stream($fileContent, string $key = null)
    {
        if (!$key) {
            $key = $this->saveFileName();
        }
        return $this->move($key, true, $fileContent);
    }

    public function delete(string $filePath)
    {
        try {
            return $this->app()->deleteObject($filePath);
        } catch (\Exception $e) {
            return $this->setError($e->getMessage());
        }
    }

    /**
     * 初始化
     * @param array $config
     * @return mixed|void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->accessKey = $config['accessKey'] ?? null;
        $this->secretKey = $config['secretKey'] ?? null;
        $this->uploadUrl = $this->checkUploadUrl($config['uploadUrl'] ?? '');
        $this->storageName = $config['storageName'] ?? null;
        $this->storageRegion = $config['storageRegion'] ?? null;
        $this->cdn = $config['cdn'] ?? null;
        $this->waterConfig['watermark_text_font'] = 'simfang仿宋.ttf';
    }

    /**
     * 实例化cos
     * @return TyClient
     */
    protected function app()
    {
        $this->handle = new TyClient([
            'accessKey' => $this->accessKey,
            'secretKey' => $this->secretKey,
            'region' => $this->storageRegion ?: 'oos-hazz',
            'bucket' => $this->storageName,
            'uploadUrl' => $this->uploadUrl
        ]);
        return $this->handle;
    }

    public function listbuckets(string $region, bool $line = false, bool $shared = false)
    {
        try {
            $this->storageRegion = $region;
            $res = $this->app()->listBuckets();
            return $res['Buckets']['Bucket'] ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function createBucket(string $name, string $region, string $acl = TyClient::DEFAULT_OBS_ACL)
    {
        $regionData = $this->getRegion();
        $regionData = array_column($regionData, 'value');
        if (!in_array($region, $regionData)) {
            return $this->setError('COS:无效的区域!');
        }
        $this->storageRegion = $region;
        $app = $this->app();
        //检测桶
        try {
            $app->headBucket($name, $region);
        } catch (\Throwable $e) {
            //桶不存在返回404
            if (strstr('404', $e->getMessage())) {
                return $this->setError('COS:' . $e->getMessage());
            }
        }
        //创建桶
        try {
            $res = $app->createBucket($name, $region, $acl);
        } catch (\Throwable $e) {
            if (strstr('[curl] 6', $e->getMessage())) {
                return $this->setError('COS:无效的区域!!');
            } else if (strstr('Access Denied.', $e->getMessage())) {
                return $this->setError('COS:无权访问');
            }
            return $this->setError('COS:' . $e->getMessage());
        }
        return $res;
    }

    public function getRegion()
    {
        return [
            [
                'value' => 'oos-hazz',
                'label' => '郑州'
            ],
            [
                'value' => 'oos-lnsy',
                'label' => '沈阳'
            ],
            [
                'value' => 'oos-sccd',
                'label' => '四川成都'
            ],
            [
                'value' => 'oos-xjwlmq',
                'label' => '乌鲁木齐'
            ],
            [
                'value' => 'oos-xjwlmq',
                'label' => '乌鲁木齐'
            ],
            [
                'value' => 'oos-gslz',
                'label' => '甘肃兰州'
            ],
            [
                'value' => 'oos-sdqd',
                'label' => '山东青岛'
            ],
            [
                'value' => 'oos-gzgy',
                'label' => '贵州贵阳'
            ],
            [
                'value' => 'oos-hbwh',
                'label' => '湖北武汉'
            ],
            [
                'value' => 'oos-xzls',
                'label' => '西藏拉萨'
            ],
            [
                'value' => 'oos-hbwh',
                'label' => '湖北武汉'
            ],
            [
                'value' => 'oos-ahwh',
                'label' => '安徽芜湖'
            ],
            [
                'value' => 'oos-gdsz',
                'label' => '广东深圳'
            ],
            [
                'value' => 'oos-jssz',
                'label' => '江苏苏州'
            ],
            [
                'value' => 'oos-sh2',
                'label' => '上海2'
            ],
            [
                'value' => 'cn-snxy1',
                'label' => '西安2'
            ]
        ];
    }

    public function deleteBucket(string $name, string $region = '')
    {
        try {
            $this->app()->deleteBucket($name, $region);
            return true;
        } catch (\Throwable $e) {
            return $this->setError($e->getMessage());
        }
    }

    public function bindDomian(string $name, string $domain, string $region = null)
    {
        // TODO: Implement bindDomian() method.
    }

    public function setBucketCors(string $name, string $region)
    {
        try {
            $res = $this->app()->PutBucketCors($name, $region, [
                'AllowedHeaders' => ['*'],
                'AllowedMethods' => ['PUT', 'GET', 'POST', 'DELETE', 'HEAD'],
                'AllowedOrigins' => ['*'],
                'ExposeHeaders' => ['ETag', 'Content-Length', 'x-cos-request-id'],
                'MaxAgeSeconds' => 12
            ]);
            return true;
        } catch (\Throwable $e) {
            return $this->setError($e->getMessage());
        }
    }

    public function getTempKeys()
    {
        // TODO: Implement getTempKeys() method.
    }

    /**
     * 缩略图
     * @param string $filePath
     * @param string $fileName
     * @param string $type
     * @return array|mixed
     */
    public function thumb(string $filePath = '', string $fileName = '', string $type = 'all')
    {
        $filePath = $this->getFilePath($filePath);
        $data = ['big' => $filePath, 'mid' => $filePath, 'small' => $filePath];
        $this->fileInfo->filePathBig = $this->fileInfo->filePathMid = $this->fileInfo->filePathSmall = $this->fileInfo->filePathWater = $filePath;
        if ($filePath) {
            $config = $this->thumbConfig;
            foreach ($this->thumb as $v) {
                if ($type == 'all' || $type == $v) {
                    $height = 'thumb_' . $v . '_height';
                    $width = 'thumb_' . $v . '_width';
                    $key = 'filePath' . ucfirst($v);
                    if (sys_config('image_thumbnail_status', 1) && isset($config[$height]) && isset($config[$width]) && $config[$height] && $config[$width]) {
                        $this->fileInfo->$key = $filePath . '?x-oss-process=image/resize,h_' . $config[$height] . ',w_' . $config[$width];
                        $this->fileInfo->$key = $this->water($this->fileInfo->$key);
                        $data[$v] = $this->fileInfo->$key;
                    } else {
                        $this->fileInfo->$key = $this->water($this->fileInfo->$key);
                        $data[$v] = $this->fileInfo->$key;
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 水印
     * @param string $filePath
     * @return mixed|string
     */
    public function water(string $filePath = '')
    {
        $filePath = $this->getFilePath($filePath);
        $waterConfig = $this->waterConfig;
        $waterPath = $filePath;
        if ($waterConfig['image_watermark_status'] && $filePath) {
            if (strpos($filePath, '?x-oss-process') === false) {
                $filePath .= '?x-oss-process=image';
            }
            switch ($waterConfig['watermark_type']) {
                case 1://图片
                    if (!$waterConfig['watermark_image']) {
                        throw new AdminException(400722);
                    }
                    $waterPath = $filePath .= '/watermark,image_' . base64_encode($waterConfig['watermark_image']) . ',t_' . $waterConfig['watermark_opacity'] . ',g_' . ($this->position[$waterConfig['watermark_position']] ?? 'nw') . ',x_' . $waterConfig['watermark_x'] . ',y_' . $waterConfig['watermark_y'];
                    break;
                case 2://文字
                    if (!$waterConfig['watermark_text']) {
                        throw new AdminException(400723);
                    }
                    $waterConfig['watermark_text_color'] = str_replace('#', '', $waterConfig['watermark_text_color']);
                    $waterPath = $filePath .= '/watermark,text_' . base64_encode($waterConfig['watermark_text']) . ',color_' . $waterConfig['watermark_text_color'] . ',size_' . $waterConfig['watermark_text_size'] . ',g_' . ($this->position[$waterConfig['watermark_position']] ?? 'nw') . ',x_' . $waterConfig['watermark_x'] . ',y_' . $waterConfig['watermark_y'];
                    break;
            }
        }
        return $waterPath;
    }
}
