<?php

namespace app\services\system\log;

use app\dao\system\log\SystemFileInfoDao;
use app\services\BaseServices;

/**
 * @author 吴汐
 * @email 442384644@qq.com
 * @date 2023/04/07
 */
class SystemFileInfoServices extends BaseServices
{
    // 排除部分目录
    protected $excluded_directories = array(
        '/runtime/cache',
        '/runtime/log',
        '/runtime/session',
        '/runtime/temp',
        '/public/uploads/attach',
        '/public/install/images/install',
        '/public/admin/system_static/css',
        '/public/admin/system_static/js',
        '/public/admin/system_static/img',
        '/public/admin/system_static/fonts',
        '/public/admin/system_static/media',
        '/public/static/css',
        '/public/static/js',
        '/public/static/img',
        '/public/static/images',
        '/public/statics/images',
        '/public/statics/mp_view/static',
        '/vendor'
        );
    /**
     * 构造方法
     * SystemLogServices constructor.
     * @param SystemFileInfoDao $dao
     */
    public function __construct(SystemFileInfoDao $dao)
    {
        $this->dao = $dao;
    }
    // 同步文件信息
    public function syncfile()
    {
        $list = $this->flattenArray($this->scanDirectory());
        $this->dao->saveAll($list);
    }
    //查询文件目录是否在排除目录中，是则返回true，否则返回false
    public function isExcludedDirectory($string) {
        foreach ($this->excluded_directories as $item) {
            if (strpos($string,$item) === 0) {
                return true;
            }
        }
        return false;
    }
    // 递归扫描目录
    public function scanDirectory($dir = '')
    {
        if ($dir == '') $dir = root_path();
        $result = array();
        // 获取目录下的所有文件和子目录
        $files = array_diff(scandir($dir), array('.', '..'));
        // 遍历文件和子目录
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            $fileInfo = array(
                'name' => $file,
                'update_time' => date('Y-m-d H:i:s', filemtime($path)),
                'create_time' => date('Y-m-d H:i:s', filectime($path)),
                'path' => str_replace(root_path(), '', $dir),
                'full_path' => str_replace(root_path(), '', $path),
            );
            // 判断是否是目录 并不在排除目录中
            if (is_dir($path) && !$this->isExcludedDirectory($file, $this->excluded_directories)) {
                $fileInfo['type'] = 'dir';
                $fileInfo['contents'] = $this->scanDirectory($path);
            } else {
                $fileInfo['type'] = 'file';
            }
            $result[] = $fileInfo;
        }
        return $result;
    }
    // 数组扁平化
    public function flattenArray($arr)
    {
        $result = array();
        foreach ($arr as $item) {
            //如果不是排除目录
            if(!$this->isExcludedDirectory($item['path'])){
                $result[] = array(
                    'name' => $item['name'],
                    'type' => $item['type'],
                    'update_time' => $item['update_time'],
                    'create_time' => $item['create_time'],
                    'path' => $item['path'],
                    'full_path' => $item['full_path'],
                );
                if (isset($item['contents'])) {
                    $result = array_merge($result, $this->flattenArray($item['contents']));
                }
            }
        }
        return $result;
    }
}