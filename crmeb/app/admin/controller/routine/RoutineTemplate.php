<?php

namespace app\admin\controller\routine;

use app\admin\controller\AuthController;
use crmeb\services\CacheService;
use crmeb\services\FormBuilder as Form;
use crmeb\services\UtilService as Util;
use crmeb\services\JsonService as Json;
use think\facade\Route as Url;
use app\admin\model\routine\RoutineTemplate as RoutineTemplateModel;
use crmeb\services\MiniProgramService;

/**
 * 小程序模板消息控制器
 * Class RoutineTemplate
 * @package app\admin\controller\routine
 */
class RoutineTemplate extends AuthController
{
    public function index()
    {
        $where = Util::getMore([
            ['name', ''],
            ['status', '']
        ], $this->request);
        $this->assign('where', $where);
        $this->assign(RoutineTemplateModel::SystemPage($where));
        return $this->fetch();
    }

    /**
     * 添加模板消息
     * @return mixed
     */
    public function create()
    {
        $f = array();
        $f[] = Form::input('tempkey', '模板编号');
        $f[] = Form::input('tempid', '模板ID');
        $f[] = Form::input('name', '模板名');
        $f[] = Form::input('content', '回复内容')->type('textarea');
        $f[] = Form::radio('status', '状态', 1)->options([['label' => '开启', 'value' => 1], ['label' => '关闭', 'value' => 0]]);
        $form = Form::make_post_form('添加模板消息', $f, Url::buildUrl('save'));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function save()
    {
        $data = Util::postMore([
            'tempkey',
            'tempid',
            'name',
            'content',
            ['status', 0]
        ]);
        if ($data['tempkey'] == '') return Json::fail('请输入模板编号');
        if ($data['tempkey'] != '' && RoutineTemplateModel::be($data['tempkey'], 'tempkey'))
            return Json::fail('请输入模板编号已存在,请重新输入');
        if ($data['tempid'] == '') return Json::fail('请输入模板ID');
        if ($data['name'] == '') return Json::fail('请输入模板名');
        if ($data['content'] == '') return Json::fail('请输入回复内容');
        $data['add_time'] = time();
        $data['type'] = 0;
        RoutineTemplateModel::create($data);
        CacheService::clear();
        return Json::successful('添加模板消息成功!');
    }

    /**
     * 编辑模板消息
     * @param $id
     * @return mixed|\think\response\Json|void
     */
    public function edit($id)
    {
        if (!$id) return $this->failed('数据不存在');
        $product = RoutineTemplateModel::get($id);
        if (!$product) return Json::fail('数据不存在!');
        $f = array();
        $f[] = Form::input('tempkey', '模板编号', $product->getData('tempkey'))->disabled(1);
        $f[] = Form::input('name', '模板名', $product->getData('name'))->disabled(1);
        $f[] = Form::input('tempid', '模板ID', $product->getData('tempid'));
        $f[] = Form::radio('status', '状态', $product->getData('status'))->options([['label' => '开启', 'value' => 1], ['label' => '关闭', 'value' => 0]]);
        $form = Form::make_post_form('编辑模板消息', $f, Url::buildUrl('update', compact('id')));
        $this->assign(compact('form'));
        return $this->fetch('public/form-builder');
    }

    public function update($id)
    {
        $data = Util::postMore([
            'tempid',
            ['status', 0]
        ]);
        if ($data['tempid'] == '') return Json::fail('请输入模板ID');
        if (!$id) return $this->failed('数据不存在');
        $product = RoutineTemplateModel::get($id);
        if (!$product) return Json::fail('数据不存在!');
        RoutineTemplateModel::edit($data, $id);
        CacheService::clear();
        return Json::successful('修改成功!');
    }

    /**
     * 删除模板消息
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        if (!$id) return Json::fail('数据不存在!');
        if (!RoutineTemplateModel::del($id))
            return Json::fail(RoutineTemplateModel::getErrorInfo('删除失败,请稍候再试!'));
        else {
            CacheService::clear();
            return Json::successful('删除成功!');
        }
    }

    /**
     * 同步订阅消息
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function syncSubscribe()
    {
        $all = RoutineTemplateModel::where(['status' => 1, 'type' => 0])->select();
        $errData = [];
        if ($all) {
            $time = time();
            foreach ($all as $template) {
                if ($template['tempkey']) {
                    if (!isset($template['kid'])) {
                        return Json::fail('数据库模版表(template_message)缺少字段：kid');
                    }
                    $works = [];
                    try {
                        $works = MiniProgramService::getSubscribeTemplateKeyWords($template['tempkey']);
                    } catch (\Throwable $e) {
                        $errData[] = $template['name'] . '获取关键词列表失败';
                    }
                    $kid = [];
                    if ($works) {
                        $works = array_combine(array_column($works, 'name'), $works);
                        $content = is_array($template['content']) ? $template['content'] : explode("\n", $template['content']);
                        foreach ($content as $c) {
                            $name = explode('{{', $c)[0] ?? '';
                            if ($name && isset($works[$name])) {
                                $kid[] = $works[$name]['kid'];
                            }
                        }
                    }
                    if ($kid && isset($template['kid']) && !$template['kid']) {
                        $tempid = '';
                        try {
                            $tempid = MiniProgramService::addSubscribeTemplate($template['tempkey'], $kid, $template['name']);
                        } catch (\Throwable $e) {
                            $errData[] = $template['name'] . '添加订阅消息模版失败';
                        }
                        if ($tempid != $template['tempid']) {
                            RoutineTemplateModel::update(['tempid' => $tempid, 'kid' => json_encode($kid), 'add_time' => $time], ['id' => $template['id']]);
                        }
                    }
                }

            }
        }
        $msg = $errData ? implode('\n', $errData) : '同步成功';
        return Json::success($msg);
    }
}