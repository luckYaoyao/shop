<?php

namespace crmeb\services\upload\storage;

use crmeb\services\upload\BaseUpload;
use crmeb\services\upload\extend\jdoss\Client as CrmebClient;

/**
 * 京东云COS文件上传
 * Class Jdoss
 * @package crmeb\services\upload\storage
 */
class Jdoss extends BaseUpload
{


    /**
     * 应用id
     * @var string
     */
    protected $appid;

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
     * @var CrmebClient
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

    /**
     * 水印位置
     * @var string[]
     */
    protected $position = [
        '1' => 'northwest',//：左上
        '2' => 'north',//：中上
        '3' => 'northeast',//：右上
        '4' => 'west',//：左中
        '5' => 'center',//：中部
        '6' => 'east',//：右中
        '7' => 'southwest',//：左下
        '8' => 'south',//：中下
        '9' => 'southeast',//：右下
    ];

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
     * @return CrmebClient
     */
    protected function app()
    {
        $this->handle = new CrmebClient([
            'accessKey' => $this->accessKey,
            'secretKey' => $this->secretKey,
            'region' => $this->storageRegion,
            'bucket' => $this->storageName,
            'uploadUrl' => $this->uploadUrl
        ]);
        return $this->handle;
    }

    public function move(string $file = 'file')
    {
        // TODO: Implement move() method.
    }

    public function stream($fileContent, string $key = null)
    {
        // TODO: Implement stream() method.
    }

    public function delete(string $filePath)
    {
        // TODO: Implement delete() method.
    }


    public function listbuckets(string $region, bool $line = false, bool $shared = false)
    {
        try {
            $res = $this->app()->listBuckets();
            return $res['Buckets']['Bucket'] ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function createBucket(string $name, string $region = '', string $acl = 'public-read')
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
            $app->headBucket($name);
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
                'value' => 'cn-north-1',
                'label' => '华北-北京'
            ],
            [
                'value' => 'cn-east-1',
                'label' => '华东-宿迁'
            ],
            [
                'value' => 'cn-east-2',
                'label' => '华东-上海'
            ],
            [
                'value' => 'cn-south-1',
                'label' => '华南-广州'
            ]
        ];
    }

    public function deleteBucket(string $name)
    {
        // TODO: Implement deleteBucket() method.
    }

    public function bindDomian(string $name, string $domain, string $region = null)
    {
        // TODO: Implement bindDomian() method.
    }

    public function setBucketCors(string $name, string $region)
    {
        // TODO: Implement setBucketCors() method.
    }

    public function getTempKeys()
    {
        // TODO: Implement getTempKeys() method.
    }

    public function thumb(string $filePath = '')
    {
        // TODO: Implement thumb() method.
    }

    public function water(string $filePath = '')
    {
        // TODO: Implement water() method.
    }
}
