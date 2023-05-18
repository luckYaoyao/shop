<?php
/**
 *  +----------------------------------------------------------------------
 *  | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2016~2022 https://www.crmeb.com All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
 *  +----------------------------------------------------------------------
 *  | Author: CRMEB Team <admin@crmeb.com>
 *  +----------------------------------------------------------------------
 */

namespace crmeb\services\upload\extend\obs;


use crmeb\services\upload\extend\cos\XML;

class Client
{
    const HEADER_PREFIX = 'x-obs-';

    const INTEREST_HEADER_KEY_LIST = ['content-type', 'content-md5', 'date'];

    const ALTERNATIVE_DATE_HEADER = 'x-obs-date';

    const ALLOWED_RESOURCE_PARAMTER_NAMES = [
        'acl',
        'policy',
        'torrent',
        'logging',
        'location',
        'storageinfo',
        'quota',
        'storagepolicy',
        'requestpayment',
        'versions',
        'versioning',
        'versionid',
        'uploads',
        'uploadid',
        'partnumber',
        'website',
        'notification',
        'lifecycle',
        'deletebucket',
        'delete',
        'cors',
        'restore',
        'tagging',
        'response-content-type',
        'response-content-language',
        'response-expires',
        'response-cache-control',
        'response-content-disposition',
        'response-content-encoding',
        'x-image-process',

        'backtosource',
        'storageclass',
        'replication',
        'append',
        'position',
        'x-oss-process'
    ];

    const OBS_ACL = [
        [
            'value' => 'public-read',
            'label' => '公共读(推荐)',
        ],
        [
            'value' => 'public-read-write',
            'label' => '公共读写',
        ],
    ];

    const DEFAULT_OBS_ACL = 'public-read';

    protected $isCname = false;

    protected $pathStyle;

    /**
     * @var
     */
    protected $accessKeyId = 'AUL8K0BMYLSZTJDT9FCM';

    /**
     * @var
     */
    protected $secretKey = 'SwjS5huuunY6Bzjrhr7RGvOIA3kHkfNZuzIp8t2z';

    /**
     * 桶名
     * @var string
     */
    protected $bucketName = '';

    /**
     * @var string
     */
    protected $baseUrl = 'obs.cn-north-1.myhuaweicloud.com';

    public function __construct()
    {

    }

    public function putObject()
    {

    }

    /**
     * 获取桶
     * @return false|string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    public function getListBuckets()
    {
        return $this->request('https://' . $this->baseUrl . '/', 'GET', [], []);
    }

    public function createBucket(string $bucket, string $region, string $acl = self::DEFAULT_OBS_ACL)
    {
        $header = [
            'x-obs-acl' => $acl,
            'Host' => $this->getRequestUrl($bucket, $region),
        ];

        return $this->request('https://' . $header['Host'] . '/', 'PUT', [], $header);
    }

    protected function getRequestUrl(string $bucket, string $region)
    {
        return $bucket . '.obs.' . $region . '.myhuaweicloud.com';
    }

    /**
     * 地域名称
     * @return \string[][]
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/5/17
     */
    public function getRegion()
    {
        return [
            [
                'value' => 'cn-north-1',
                'label' => '华北-北京一',
            ],
            [
                'value' => 'cn-north-4',
                'label' => '华北-北京四',
            ],
            [
                'value' => 'cn-north-9',
                'label' => '华北-乌兰察布一',
            ],
            [
                'value' => 'cn-east-2',
                'label' => '华东-上海二',
            ],
            [
                'value' => 'cn-east-3',
                'label' => '华东-上海一',
            ],
            [
                'value' => 'cn-south-1',
                'label' => '华南-广州',
            ],
            [
                'value' => 'ap-southeast-1',
                'label' => '中国-香港',
            ],
            [
                'value' => 'cn-south-4',
                'label' => '华南-广州-友好用户环境',
            ],
            [
                'value' => 'cn-southwest-2',
                'label' => '西南-贵阳一',
            ],
            [
                'value' => 'la-north-2',
                'label' => '拉美-墨西哥城二',
            ],
            [
                'value' => 'na-mexico-1',
                'label' => '拉美-墨西哥城一',
            ],
            [
                'value' => 'sa-brazil-1',
                'label' => '拉美-圣保罗一',
            ],
            [
                'value' => 'la-south-2',
                'label' => '拉美-圣地亚哥',
            ],
            [
                'value' => 'tr-west-1',
                'label' => '土耳其-伊斯坦布尔',
            ],
            [
                'value' => 'ap-southeast-2',
                'label' => '亚太-曼谷',
            ],
            [
                'value' => 'ap-southeast-3',
                'label' => '亚太-新加坡',
            ],
            [
                'value' => 'af-south-1',
                'label' => '非洲-约翰内斯堡',
            ]
        ];
    }

    /**
     * 设置桶名
     * @param string $bucketName
     * @return $this
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    public function setBucketName(string $bucketName)
    {
        $this->bucketName = $bucketName;
        return $this;
    }


    /**
     * 获取签名
     * @param array $result
     * @return array
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/5/17
     */
    protected function getSign(array $result)
    {
        $result['headers']['Date'] = gmdate('D, d M Y H:i:s \G\M\T');
        $canonicalstring = $this->makeCanonicalstring($result['method'], $result['headers'], $result['pathArgs'], $result['dnsParam'], $result['uriParam']);

        $result['cannonicalRequest'] = $canonicalstring;

        $signature = base64_encode(hash_hmac('sha1', $canonicalstring, $this->secretKey, true));

        $authorization = 'OBS ' . $this->accessKeyId . ':' . $signature;

        $result['headers']['Authorization'] = $authorization;

        return $result;
    }

    /**
     * 处理签名数据
     * @param $method
     * @param $headers
     * @param $pathArgs
     * @param $bucketName
     * @param $objectKey
     * @param null $expires
     * @return string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/5/17
     */
    public function makeCanonicalstring($method, $headers, $pathArgs, $bucketName, $objectKey, $expires = null)
    {
        $buffer = [];
        $buffer[] = $method;
        $buffer[] = "\n";
        $interestHeaders = [];

        foreach ($headers as $key => $value) {
            $key = strtolower($key);
            if (in_array($key, self::INTEREST_HEADER_KEY_LIST) || strpos($key, self::HEADER_PREFIX) === 0) {
                $interestHeaders[$key] = $value;
            }
        }

        if (array_key_exists(self::ALTERNATIVE_DATE_HEADER, $interestHeaders)) {
            $interestHeaders['date'] = '';
        }

        if ($expires !== null) {
            $interestHeaders['date'] = strval($expires);
        }

        if (!array_key_exists('content-type', $interestHeaders)) {
            $interestHeaders['content-type'] = '';
        }

        if (!array_key_exists('content-md5', $interestHeaders)) {
            $interestHeaders['content-md5'] = '';
        }

        ksort($interestHeaders);

        foreach ($interestHeaders as $key => $value) {
            if (strpos($key, self::HEADER_PREFIX) === 0) {
                $buffer[] = $key . ':' . $value;
            } else {
                $buffer[] = $value;
            }
            $buffer[] = "\n";
        }

        $uri = '';

        $bucketName = $this->isCname ? $headers['Host'] : $bucketName;

        if ($bucketName) {
            $uri .= '/';
            $uri .= $bucketName;
            if (!$this->pathStyle) {
                $uri .= '/';
            }
        }

        if ($objectKey) {
            if (!($pos = strripos($uri, '/')) || strlen($uri) - 1 !== $pos) {
                $uri .= '/';
            }
            $uri .= $objectKey;
        }

        $buffer[] = $uri === '' ? '/' : $uri;


        if (!empty($pathArgs)) {
            ksort($pathArgs);
            $_pathArgs = [];
            foreach ($pathArgs as $key => $value) {
                if (in_array(strtolower($key), self::ALLOWED_RESOURCE_PARAMTER_NAMES) || strpos($key, self::HEADER_PREFIX) === 0) {
                    $_pathArgs[] = $value === null || $value === '' ? $key : $key . '=' . urldecode($value);
                }
            }
            if (!empty($_pathArgs)) {
                $buffer[] = '?';
                $buffer[] = implode('&', $_pathArgs);
            }
        }

        return implode('', $buffer);
    }

    /**
     * 发起请求
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $clientHeader
     * @param int $timeout
     * @return false|string
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2023/5/16
     */
    public function request(string $url, string $method, array $data = [], array $clientHeader = [], int $timeout = 10)
    {
        $method = strtoupper($method);

        $result = $this->getSign([
            'method' => $method,
            'headers' => $clientHeader,
            'pathArgs' => '',
            'dnsParam' => '',
            'uriParam' => '',
        ]);
        $clientHeader = $result['headers'];
        $headers = [];
        foreach ($clientHeader as $key => $item) {
            $headers[] = $key . ':' . $item;
        }
        $curl = curl_init($url);
        //请求方式
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        //post请求
        if (!empty($data['body'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data['body']);
        } else if (!empty($data['json'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data['json']));
        }
        //超时时间
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        //设置header头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        //返回抓取数据
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //输出header头信息
        curl_setopt($curl, CURLOPT_HEADER, true);
        //TRUE 时追踪句柄的请求字符串，从 PHP 5.1.3 开始可用。这个很关键，就是允许你查看请求header
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        //https请求
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        [$content, $status] = [curl_exec($curl), curl_getinfo($curl)];
//        dump($status);
        $content = trim(substr($content, $status['header_size']));
        $res = XML::parse($content);
        if ($res) {
            return $res;
        }
        return (intval($status["http_code"]) === 200) ? $content : false;
    }

}
