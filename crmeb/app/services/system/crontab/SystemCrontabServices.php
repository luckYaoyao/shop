<?php

namespace app\services\system\crontab;

use app\dao\system\crontab\SystemCrontabDao;
use app\services\activity\combination\StorePinkServices;
use app\services\activity\live\LiveGoodsServices;
use app\services\activity\live\LiveRoomServices;
use app\services\agent\AgentManageServices;
use app\services\BaseServices;
use app\services\order\StoreOrderServices;
use app\services\order\StoreOrderTakeServices;
use app\services\product\product\StoreProductServices;
use app\services\system\attachment\SystemAttachmentServices;
use crmeb\exceptions\AdminException;
use think\facade\Log;

class SystemCrontabServices extends BaseServices
{
    /**
     * 定时任务类型
     * @var string[]
     */
    private $markList = [
        'order_cancel' => '未支付自动取消订单',
        'pink_expiration' => '拼团到期订单处理',
        'agent_unbind' => '到期自动解绑上级',
        'live_product_status' => '自动更新直播商品状态',
        'live_room_status' => '自动更新直播间状态',
        'take_delivery' => '订单自动收货',
        'advance_off' => '预售商品到期自动下架',
        'product_replay' => '订单商品自动好评',
        'clear_poster' => '清除昨日海报',
    ];

    public function __construct(SystemCrontabDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 定时任务列表
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTimerList(array $where = [])
    {
        [$page, $limit] = $this->getPageValue();
        $list = $this->dao->selectList($where, '*', $page, $limit, 'id desc');
        foreach ($list as &$item) {
            $item['next_execution_time'] = date('Y-m-d H:i:s', $item['next_execution_time']);
            $item['last_execution_time'] = $item['last_execution_time'] != 0 ? date('Y-m-d H:i:s', $item['last_execution_time']) : '暂未执行';
        }
        $count = $this->dao->count($where);
        return compact('list', 'count');
    }

    /**
     * 定时任务详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTimerInfo($id)
    {
        $info = $this->dao->get($id);
        if (!$info) throw new AdminException(100026);
        return $info->toArray();
    }

    /**
     * 定时任务类型
     * @return string[]
     */
    public function getMarkList(): array
    {
        return $this->markList;
    }

    /**
     * 保存定时任务
     * @param array $data
     * @return bool
     */
    public function saveTimer(array $data = [])
    {
        if (!$data['id'] && $this->dao->getCount(['mark' => $data['mark'], 'is_del' => 0])) {
            throw new AdminException('该定时任务已存在，请勿重复添加');
        }
        $data['name'] = $this->markList[$data['mark']];
        $data['add_time'] = time();
        if (!$data['id']) {
            unset($data['id']);
            $res = $this->dao->save($data);
        } else {
            $res = $this->dao->update(['id' => $data['id']], $data);
        }
        if (!$res) throw new AdminException(100006);
        return true;
    }

    /**
     * 删除定时任务
     * @param $id
     * @return bool
     */
    public function delTimer($id)
    {
        $res = $this->dao->update(['id' => $id], ['is_del' => 1]);
        if (!$res) throw new AdminException(100008);
        return true;
    }

    /**
     * 设置定时任务状态
     * @param $id
     * @param $is_open
     * @return bool
     */
    public function setTimerStatus($id, $is_open)
    {
        $res = $this->dao->update(['id' => $id], ['is_open' => $is_open]);
        if (!$res) throw new AdminException(100014);
        return true;
    }

    /**
     * 计算定时任务下次执行时间
     * @param $data
     * @param int $time
     * @return false|float|int|mixed
     */
    public function getTimerCycleTime($data, $time = 0)
    {
        if (!$time) $time = time();
        switch ($data['type']) {
            case 1: // 每隔几秒
                $cycle_time = $time + $data['second'];
                break;
            case 2: // 每隔几分
                $cycle_time = $time + ($data['minute'] * 60);
                break;
            case 3: // 每隔几时
                $cycle_time = $time + ($data['hour'] * 3600) + ($data['minute'] * 60);
                break;
            case 4: // 每隔几日
                $cycle_time = $time + ($data['day'] * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60);
                break;
            case 5: // 每日几时几分几秒
                $cycle_time = strtotime(date('Y-m-d ' . $data['hour'] . ':' . $data['minute'] . ':' . $data['second'], time()));
                if ($time >= $cycle_time) {
                    $cycle_time = $cycle_time + 86400;
                }
                break;
            case 6: // 每周周几几时几分几秒
                $todayStart = strtotime(date('Y-m-d 00:00:00', time()));
                $w = date("w");
                if ($w > $data['week']) {
                    $cycle_time = $todayStart + ((7 - $w + $data['week']) * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                } else if ($w == $data['week']) {
                    $cycle_time = $todayStart + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                    if ($time >= $cycle_time) {
                        $cycle_time = $cycle_time + (7 * 86400);
                    }
                } else {
                    $cycle_time = $todayStart + (($data['week'] - $w) * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                }
                break;
            case 7: // 每月几日几时几分几秒
                $d = date("d");
                $firstDate = date('Y-m-01', time());
                $maxDay = date('d', strtotime("$firstDate + 1 month -1 day"));
                $todayStart = strtotime(date('Y-m-d 00:00:00', time()));
                if ($d > $data['day']) {
                    $cycle_time = $todayStart + (($maxDay - $d + $data['day']) * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                } elseif ($d == $data['day']) {
                    $cycle_time = $todayStart + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                    if ($time >= $cycle_time) {
                        $cycle_time = $cycle_time + (($maxDay - $d + $data['day']) * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                    }
                } else {
                    $cycle_time = $todayStart + (($data['day'] - $d) * 86400) + ($data['hour'] * 3600) + ($data['minute'] * 60) + $data['second'];
                }
                break;
            default:
                $cycle_time = 0;
                break;
        }
        return $cycle_time;
    }

    /**
     * 执行任务
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 吴汐
     * @email 442384644@qq.com
     * @date 2023/02/17
     */
    public function crontabRun()
    {
        $time = time();
        file_put_contents(root_path() . 'runtime/.timer', $time); //检测定时任务是否正常
        $list = $this->dao->selectList(['is_open' => 1, 'is_del' => 0])->toArray();
        foreach ($list as $item) {
            if ($item['next_execution_time'] < $time) {
                if ($item['mark'] == 'order_cancel') {
                    //未支付自动取消订单
                    app()->make(StoreOrderServices::class)->orderUnpaidCancel();
                    $this->crontabLog(' 执行未支付自动取消订单');
                } elseif ($item['mark'] == 'pink_expiration') {
                    //拼团到期订单处理
                    app()->make(StorePinkServices::class)->statusPink();
                    $this->crontabLog(' 执行拼团到期订单处理');
                } elseif ($item['mark'] == 'agent_unbind') {
                    //自动解绑上级绑定
                    app()->make(AgentManageServices::class)->removeSpread();
                    $this->crontabLog(' 执行自动解绑上级绑定');
                } elseif ($item['mark'] == 'live_product_status') {
                    //更新直播商品状态
                    app()->make(LiveGoodsServices::class)->syncGoodStatus();
                    $this->crontabLog(' 执行更新直播商品状态');
                } elseif ($item['mark'] == 'live_room_status') {
                    //更新直播间状态
                    app()->make(LiveRoomServices::class)->syncRoomStatus();
                    $this->crontabLog(' 执行更新直播间状态');
                } elseif ($item['mark'] == 'take_delivery') {
                    //自动收货
                    app()->make(StoreOrderTakeServices::class)->autoTakeOrder();
                    $this->crontabLog(' 执行自动收货');
                } elseif ($item['mark'] == 'advance_off') {
                    //查询预售到期商品自动下架
                    app()->make(StoreProductServices::class)->downAdvance();
                    $this->crontabLog(' 执行预售到期商品自动下架');
                } elseif ($item['mark'] == 'product_replay') {
                    //自动好评
                    app()->make(StoreOrderServices::class)->autoComment();
                    $this->crontabLog(' 执行自动好评');
                } elseif ($item['mark'] == 'clear_poster') {
                    //清除昨日海报
                    app()->make(SystemAttachmentServices::class)->emptyYesterdayAttachment();
                    $this->crontabLog(' 执行清除昨日海报');
                }
                //写入本次执行时间和下次执行时间
                $this->dao->update(['mark' => $item['mark']], ['last_execution_time' => $time, 'next_execution_time' => $this->getTimerCycleTime($item)]);
            }
        }
    }

    /**
     * 定时任务日志
     * @param $msg
     * @author 吴汐
     * @email 442384644@qq.com
     * @date 2023/02/21
     */
    public function crontabLog($msg)
    {
        $timer_log_open = config("log.timer_log", false);
        if ($timer_log_open) {
            $date = date('Y-m-d H:i:s', time());
            Log::write($date . $msg, 'crontab');
        }
    }
}