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

namespace app;

use Spatie\Macroable\Macroable;

/**
 * Class Request
 * @package app
 * @method tokenData() 获取token信息
 * @method user(string $key = null) 获取用户信息
 * @method uid() 获取用户uid
 * @method isAdminLogin() 后台登陆状态
 * @method adminId() 后台管理员id
 * @method adminInfo() 后台管理信息
 * @method kefuId() 客服id
 * @method kefuInfo() 客服信息
 */
class Request extends \think\Request
{
    use Macroable;// 允许注入公共方法 

    /**
     * 不过滤变量名
     * @var array
     */
    protected $except = ['menu_path', 'api_url', 'unique_auth',
        'description', 'custom_form', 'content', 'tableField'];

    /**
     * 获取请求的数据
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */
    public function more(array $params, bool $suffix = false, bool $filter = true): array
    {
        $p = []; // 初始化一个空数组
        $i = 0; // 初始化计数器
        foreach ($params as $param) { // 遍历参数数组
            if (!is_array($param)) { // 如果当前元素不是数组
                $p[$suffix == true ? $i++ : $param] = $this->filterWord(is_string($this->param($param)) ? trim($this->param($param)) : $this->param($param), $filter && !in_array($param, $this->except));
            } else { // 如果当前元素是数组
                if (!isset($param[1])) $param[1] = null; // 如果第二个元素不存在则设置为null
                if (!isset($param[2])) $param[2] = ''; // 如果第三个元素不存在则设置为空字符串
                if (is_array($param[0])) { // 如果第一个元素也是数组
                    $name = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1]; // 根据第二个元素是否为数组来构造参数名
                    $keyName = $param[0][0]; // 参数名作为键名
                } else { // 如果当前元素是数组
                        $name = is_array($param[1]) ? $param[0] . '/a' : $param[0]; // 根据第二个元素是否为数组来构造参数名
                        $keyName = $param[0]; // 参数名作为键名
                }
                // 获取当前元素的值并存入新数组 
                $p[$suffix == true ? $i++ : ($param[3] ?? $keyName)] = $this->filterWord( // 对当前元素进行过滤并存入新数组
                    is_string($this->param($name, $param[1], $param[2])) ? // 获取当前元素的值并去除首尾空格
                                trim($this->param($name, $param[1], $param[2])) :
                                $this->param($name, $param[1], $param[2]),
                            $filter && !in_array($keyName, $this->except));
            }
        }
        return $p;

    }

    /**
     * 过滤接受的参数
     * @param $str
     * @param bool $filter
     * @return array|mixed|string|string[]
     */
    public function filterWord($str, bool $filter = true)
    {
        if (!$str || !$filter) return $str;
        // 把数据过滤
        $farr = [
            "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
            "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
            '/phar/is',
            "/select|join|where|drop|like|modify|rename|insert|update|table|database|alter|truncate|\'|\/\*|\.\.\/|\.\/|union|into|load_file|outfile/is"
        ];
        if (is_array($str)) {
            foreach ($str as &$v) {
                if (is_array($v)) {
                    foreach ($v as &$vv) {
                        if (!is_array($vv)) {
                            $vv = $this->replaceWord($farr, $vv);// 替换特殊字符  
                        }
                    }
                } else {
                    $v = $this->replaceWord($farr, $v);
                }
            }
        } else {
            $str = $this->replaceWord($farr, $str);
        }
        return $str;
    }

    /**
     * 替换
     * @param $farr
     * @param $str
     * @return array|string|string[]|null
     * @author: 吴汐
     * @email: 442384644@qq.com
     * @date: 2023/9/19
     */
    public function replaceWord($farr, $str)
    {
        if (filter_var($str, FILTER_VALIDATE_URL)) {
            $url = parse_url($str);
            $host = $url['scheme'] . '://' . $url['host'];
            $str = $host . preg_replace($farr, '', str_replace($host, '', $str));
        } else {
            $str = preg_replace($farr, '', $str);
        }
        return $str;
    }

    /**
     * 获取get参数
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */
    public function getMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    /**
     * 获取post参数
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */
    public function postMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    /**
     * 获取用户访问端
     * @return array|string|null
     */
    public function getFromType()
    {
        return $this->header('Form-type', '');
    }

    /**
     * 当前访问端
     * @param string $terminal
     * @return bool
     */
    public function isTerminal(string $terminal)
    {
        return strtolower($this->getFromType()) === $terminal;
    }

    /**
     * 是否是H5端
     * @return bool
     */
    public function isH5()
    {
        return $this->isTerminal('h5');
    }

    /**
     * 是否是微信端
     * @return bool
     */
    public function isWechat()
    {
        return $this->isTerminal('wechat');
    }

    /**
     * 是否是小程序端
     * @return bool
     */
    public function isRoutine()
    {
        return $this->isTerminal('routine');
    }

    /**
     * 是否是app端
     * @return bool
     */
    public function isApp()
    {
        return $this->isTerminal('app');
    }

    /**
     * 是否是app端
     * @return bool
     */
    public function isPc()
    {
        return $this->isTerminal('pc');
    }
}
