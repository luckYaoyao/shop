<?php

namespace app\listener\crontab;

use app\services\activity\combination\StorePinkServices;
use app\services\activity\live\LiveGoodsServices;
use app\services\activity\live\LiveRoomServices;
use app\services\agent\AgentManageServices;
use app\services\order\StoreOrderServices;
use app\services\order\StoreOrderTakeServices;
use app\services\product\product\StoreProductServices;
use app\services\system\attachment\SystemAttachmentServices;
use app\services\system\crontab\SystemCrontabServices;
use crmeb\interfaces\ListenerInterface;
use think\facade\Log;
use Workerman\Crontab\Crontab;

/**
 * 系统定时任务
 */
class SystemCrontabListener implements ListenerInterface
{
    public function handle($event): void
    {
        //自动写入文件方便检测是否启动定时任务命令
        new Crontab('*/6 * * * * *', function () {
            file_put_contents(root_path() . 'runtime/.timer', time());
        });

        /** @var SystemCrontabServices $systemTimerServices */
        $systemCrontabServices = app()->make(SystemCrontabServices::class);
        $list = $systemCrontabServices->selectList(['is_del' => 0, 'is_open' => 1])->toArray();
        foreach ($list as &$item) {
            //获取定时任务时间字符串
            $timeStr = $this->getTimerStr($item);
            //未支付自动取消订单
            if ($item['mark'] == 'order_cancel') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(StoreOrderServices::class)->orderUnpaidCancel();
                        $this->crontabLog(' 执行未支付自动取消订单');
                    } catch (\Throwable $e) {
                        Log::error('自动取消订单失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //拼团到期订单处理
            elseif ($item['mark'] == 'pink_expiration') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(StorePinkServices::class)->statusPink();
                        $this->crontabLog(' 执行拼团到期订单处理');
                    } catch (\Throwable $e) {
                        Log::error('拼团到期订单处理失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //自动解绑上级绑定
            elseif ($item['mark'] == 'agent_unbind') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(AgentManageServices::class)->removeSpread();
                        $this->crontabLog(' 执行自动解绑上级绑定');
                    } catch (\Throwable $e) {
                        Log::error('自动解除上级绑定失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //更新直播商品状态
            elseif ($item['mark'] == 'live_product_status') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(LiveGoodsServices::class)->syncGoodStatus();
                        $this->crontabLog(' 执行更新直播商品状态');
                    } catch (\Throwable $e) {
                        Log::error('更新直播商品状态失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //更新直播间状态
            elseif ($item['mark'] == 'live_room_status') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(LiveRoomServices::class)->syncRoomStatus();
                        $this->crontabLog(' 执行更新直播间状态');
                    } catch (\Throwable $e) {
                        Log::error('更新直播间状态失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //自动收货
            elseif ($item['mark'] == 'take_delivery') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(StoreOrderTakeServices::class)->autoTakeOrder();
                        $this->crontabLog(' 执行自动收货');
                    } catch (\Throwable $e) {
                        Log::error('自动收货失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //查询预售到期商品自动下架
            elseif ($item['mark'] == 'advance_off') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(StoreProductServices::class)->downAdvance();
                        $this->crontabLog(' 执行预售到期商品自动下架');
                    } catch (\Throwable $e) {
                        Log::error('预售到期商品自动下架失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //自动好评
            elseif ($item['mark'] == 'product_replay') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(StoreOrderServices::class)->autoComment();
                        $this->crontabLog(' 执行自动好评');
                    } catch (\Throwable $e) {
                        Log::error('自动好评失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //清除昨日海报
            elseif ($item['mark'] == 'clear_poster') {
                new Crontab($timeStr, function () {
                    try {
                        app()->make(SystemAttachmentServices::class)->emptyYesterdayAttachment();
                        $this->crontabLog(' 执行清除昨日海报');
                    } catch (\Throwable $e) {
                        Log::error('清除昨日海报失败,失败原因:' . $e->getMessage());
                    }
                });
            }
            //
            else {

            }
        }
    }

    /**
     * 定时任务日志
     * @param $msg
     */
    public function crontabLog($msg)
    {
        $timer_log_open = config("log.timer_log", false);
        if ($timer_log_open){
            $date = date('Y-m-d H:i:s', time());
            Log::notice($date . $msg);
        }
    }

    /**
     *  0   1   2   3   4   5
     * |   |   |   |   |   |
     * |   |   |   |   |   +------ day of week (0 - 6) (Sunday=0)
     * |   |   |   |   +------ month (1 - 12)
     * |   |   |   +-------- day of month (1 - 31)
     * |   |   +---------- hour (0 - 23)
     * |   +------------ min (0 - 59)
     * +-------------- sec (0-59)[可省略，如果没有0位,则最小时间粒度是分钟]
     */
    /**
     * 获取定时任务时间表达式
     * @param $data
     * @return string
     */
    public function getTimerStr($data): string
    {
        $timeStr = '';
        switch ($data['type']) {
            case 1:// 每隔几秒
                $timeStr = '*/' . $data['second'] . ' * * * * *';
                break;
            case 2:// 每隔几分
                $timeStr = '0 */' . $data['minute'] . ' * * * *';
                break;
            case 3:// 每隔几时第几分钟执行
                $timeStr = '0 ' . $data['minute'] . ' */' . $data['hour'] . ' * * *';
                break;
            case 4:// 每隔几日第几小时第几分钟执行
                $timeStr = '0 ' . $data['minute'] . ' ' . $data['hour'] . ' */' . $data['day'] . ' * *';
                break;
            case 5:// 每日几时几分几秒
                $timeStr = $data['second'] . ' ' . $data['minute'] . ' ' . $data['hour'] . ' * * *';
                break;
            case 6:// 每周周几几时几分几秒
                $timeStr = $data['second'] . ' ' . $data['minute'] . ' ' . $data['hour'] . ' * * ' . ($data['week'] == 7 ? 0 : $data['week']);
                break;
            case 7:// 每月几日几时几分几秒
                $timeStr = $data['second'] . ' ' . $data['minute'] . ' ' . $data['hour'] . ' ' . $data['day'] . ' * *';
                break;
        }
        return $timeStr;
    }
}
