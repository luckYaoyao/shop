<template>
  <div>
    <el-table
      :data="orderList"
      ref="table"
      :loading="loading"
      highlight-current-row
      empty-text="暂无数据"
      @select="handleSelectRow"
      @select-all="handleSelectAll"
      class="orderData mt25"
    >
      <el-table-column type="expand">
        <template slot-scope="scope">
          <expandRow :row="scope.row"></expandRow>
        </template>
      </el-table-column>
      <el-table-column type="selection" width="55"> </el-table-column>
      <el-table-column label="订单号 | 类型" width="200">
        <template slot-scope="scope">
          <div>{{ scope.row.order_id }}</div>
          <div class="pink_name">{{ scope.row.pink_name }}</div>
          <span v-show="scope.row.is_del === 1" style="color: #ed4014; display: block">用户已删除</span>
        </template>
      </el-table-column>
      <el-table-column label="用户昵称 | ID" min-width="150">
        <template slot-scope="scope">
          <span class="nickname">{{ scope.row.nickname }}</span> | <span class="uid">{{ scope.row.uid }}</span>
        </template>
      </el-table-column>
      <el-table-column label="商品信息" min-width="330">
        <template slot-scope="scope">
          <div class="tabBox" v-for="(val, i) in scope.row._info" :key="i">
            <div class="tabBox_img" v-viewer>
              <img
                v-lazy="
                  val.cart_info.productInfo.attrInfo
                    ? val.cart_info.productInfo.attrInfo.image
                    : val.cart_info.productInfo.image
                "
              />
            </div>
            <span class="tabBox_tit"
              >{{ val.cart_info.productInfo.store_name + ' | '
              }}{{ val.cart_info.productInfo.attrInfo ? val.cart_info.productInfo.attrInfo.suk : '' }}</span
            >
            <span class="tabBox_pice">{{ '￥' + val.cart_info.truePrice + ' x ' + val.cart_info.cart_num }}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="实际支付" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.paid ? scope.row.pay_price : '未支付' }}</span>
        </template>
      </el-table-column>
      <el-table-column label="支付方式" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.pay_type_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="支付时间" min-width="100">
        <template slot-scope="scope">
          <span>{{ scope.row._pay_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="订单状态" min-width="100">
        <template slot-scope="scope">
          <div v-html="scope.row.status_name.status_name" class="pt5"></div>
          <div v-if="!scope.row.is_all_refund && scope.row.refund.length" class="trip">部分退款中</div>
          <div
            v-if="
              scope.row.refund_status == 0 &&
              scope.row.is_all_refund &&
              scope.row.refund.length &&
              scope.row.refund_type != 6
            "
            class="trip"
          >
            退款中
          </div>
          <div class="img">
            <div
              v-viewer
              v-if="scope.row.status_name.pics"
              class="pictrue"
              v-for="(item, index) in scope.row.status_name.pics || []"
              :key="index"
            >
              <img v-lazy="item" :src="item" />
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="操作" fixed="right" width="130">
        <template slot-scope="scope">
          <a @click="edit(scope.row)" v-if="scope.row._status === 1 && scope.row.is_del !== 1">编辑</a>
          <a
            @click="sendOrder(scope.row)"
            v-if="
              (scope.row.status === 4 || scope.row._status === 2 || scope.row._status === 8) &&
              scope.row.shipping_type === 1 &&
              (scope.row.pinkStatus === null || scope.row.pinkStatus === 2) &&
              scope.row.is_del !== 1
            "
            >发送货</a
          >
          <a @click="delivery(scope.row)" v-if="scope.row._status === 4 && !scope.row.split.length">配送信息</a>
          <a
            @click="bindWrite(scope.row)"
            v-if="
              scope.row.shipping_type == 2 &&
              scope.row.status == 0 &&
              scope.row.paid == 1 &&
              scope.row.refund_status === 0
            "
            >立即核销</a
          >
          <el-divider
            direction="vertical"
            v-if="
              (scope.row._status === 2 && scope.row.shipping_type === 1 && scope.row.pinkStatus === 2) ||
              (scope.row.split.length && scope.row.is_del !== 1)
            "
          />
          <el-divider
            direction="vertical"
            v-if="
              scope.row.refund_type !== 4 &&
              scope.row.refund_type !== 5 &&
              (scope.row._status === 1 ||
                scope.row._status === 3 ||
                (scope.row._status === 2 && !scope.row.pinkStatus) ||
                scope.row._status === 4 ||
                (scope.row.shipping_type == 2 &&
                  scope.row.status == 0 &&
                  scope.row.paid == 1 &&
                  scope.row.refund_status === 0)) &&
              scope.row.is_del !== 1
            "
          />
          <template>
            <el-dropdown size="small" @command="changeMenu(scope.row, $event)" :transfer="true">
              <span class="el-dropdown-link"> 更多<i class="el-icon-arrow-down el-icon--right"></i> </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                  command="1"
                  v-show="
                    scope.row._status === 1 &&
                    scope.row.paid === 0 &&
                    scope.row.pay_type === 'offline' &&
                    scope.row.is_del !== 1
                  "
                  >确认付款</el-dropdown-item
                >
                <el-dropdown-item command="2">订单详情</el-dropdown-item>
                <el-dropdown-item command="3">订单记录</el-dropdown-item>
                <el-dropdown-item command="11" v-show="scope.row._status >= 3 && scope.row.express_dump"
                  >电子面单打印</el-dropdown-item
                >
                <el-dropdown-item command="10" v-show="scope.row._status >= 2">小票打印</el-dropdown-item>
                <el-dropdown-item
                  command="4"
                  v-show="
                    scope.row._status !== 1 ||
                    (scope.row._status === 3 &&
                      scope.row.use_integral > 0 &&
                      scope.row.use_integral >= scope.row.back_integral)
                  "
                  >订单备注</el-dropdown-item
                >
                <!-- <el-dropdown-item
                command="5"
                v-show="
                  scope.row._status !== 1 &&
                  (parseFloat(scope.row.pay_price) > parseFloat(scope.row.refund_price) ||
                    (scope.row.pay_price == 0 &&
                      [0, 1].indexOf(scope.row.refund_status) !== -1))
                "
                >立即退款</el-dropdown-item
              > -->
                <!--                            <el-dropdown-item command="6"  v-show='scope.row._status !==1 && (scope.row.use_integral > 0 && scope.row.use_integral >= scope.row.back_integral) '>退积分</el-dropdown-item>-->
                <!--                            <el-dropdown-item command="7"  v-show='scope.row._status === 3'>不退款</el-dropdown-item>-->
                <el-dropdown-item command="8" v-show="scope.row._status === 4">已收货</el-dropdown-item>
                <el-dropdown-item command="9">删除订单</el-dropdown-item>
                <el-dropdown-item command="12" v-show="scope.row.kuaidi_label">快递面单打印</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </template>
      </el-table-column>
    </el-table>
    <div class="acea-row row-right page">
      <pagination v-if="total" :total="total" :page.sync="page.page" :limit.sync="page.limit" @pagination="getList" />
    </div>
    <!-- 编辑 退款 退积分 不退款-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
    <!-- 详情 -->
    <details-from ref="details" :orderDatalist="orderDatalist" :orderId="orderId"></details-from>
    <!-- 备注 -->
    <order-remark ref="remarks" :orderId="orderId" @submitFail="submitFail"></order-remark>
    <!-- 记录 -->
    <order-record ref="record"></order-record>
    <!-- 发送货 -->
    <order-send
      ref="send"
      :orderId="orderId"
      :status="status"
      :pay_type="pay_type"
      :virtual_type="virtual_type"
      @submitFail="submitFail"
      @clearId="
        () => {
          orderId = 0;
        }
      "
    ></order-send>
  </div>
</template>

<script>
import expandRow from './tableExpand.vue';
import printJS from 'print-js';
import {
  orderList,
  getOrdeDatas,
  getDataInfo,
  getRefundFrom,
  getnoRefund,
  refundIntegral,
  getDistribution,
  writeUpdate,
} from '@/api/order';
import { mapState, mapMutations } from 'vuex';
import editFrom from '../../../../components/from/from';
import detailsFrom from '../handle/orderDetails';
import orderRemark from '../handle/orderRemark';
import orderRecord from '../handle/orderRecord';
import orderSend from '../handle/orderSend';

export default {
  name: 'table_list',
  components: {
    expandRow,
    editFrom,
    detailsFrom,
    orderRemark,
    orderRecord,
    orderSend,
  },
  data() {
    return {
      delfromData: {},
      modal: false,
      orderList: [],
      orderCards: [],
      loading: false,
      orderId: 0,
      total_num: 0,
      virtual_type: 0,
      status: 0,
      pay_type: '',

      total: 0, // 总条数
      page: {
        page: 1, // 当前页
        limit: 10, // 每页显示条数
      },
      data: [],
      FromData: null,
      orderDatalist: null,
      // modalTitleSs: '',
      selectedIds: [], //选中合并项的id
    };
  },
  computed: {
    ...mapState('order', ['orderPayType', 'orderStatus', 'orderTime', 'orderNum', 'fieldKey', 'orderType']),
  },
  mounted() {},
  created() {
    this.getList();
  },
  watch: {
    orderType: function () {
      this.page.page = 1;
      this.getList();
    },
  },
  methods: {
    ...mapMutations('order', ['getIsDel', 'getisDelIdListl']),
    // 操作
    changeMenu(row, name) {
      console.log(row, name);
      this.orderId = row.id;
      switch (name) {
        case '1':
          this.delfromData = {
            title: '修改立即支付',
            url: `/order/pay_offline/${row.id}`,
            method: 'post',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$Message.success(res.msg);
              this.$emit('changeGetTabs');
              this.getList();
            })
            .catch((res) => {
              this.$Message.error(res.msg);
            });
          // this.modalTitleSs = '修改立即支付';
          break;
        case '2':
          this.getData(row.id);
          break;
        case '3':
          this.$refs.record.modals = true;
          this.$refs.record.getList(row.id);
          break;
        case '4':
          this.$refs.remarks.modals = true;
          this.$refs.remarks.formValidate.remark = row.remark;
          break;
        case '5':
          this.getRefundData(row.id);
          break;
        case '6':
          this.getRefundIntegral(row.id);
          break;
        case '7':
          this.getNoRefundData(row.id);
          break;
        case '8':
          this.delfromData = {
            title: '修改确认收货',
            url: `/order/take/${row.id}`,
            method: 'put',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$Message.success(res.msg);
              this.getList();
            })
            .catch((res) => {
              this.$Message.error(res.msg);
            });
          // this.modalTitleSs = '修改确认收货';
          break;
        case '10':
          this.delfromData = {
            title: '立即打印订单',
            info: '您确认打印此订单吗?',
            url: `/order/print/${row.id}`,
            method: 'get',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$Message.success(res.msg);
              this.$emit('changeGetTabs');
              this.getList();
            })
            .catch((res) => {
              this.$Message.error(res.msg);
            });
          break;
        case '11':
          this.delfromData = {
            title: '立即打印电子面单',
            info: '您确认打印此电子面单吗?',
            url: `/order/order_dump/${row.id}`,
            method: 'get',
            ids: '',
          };
          this.$modalSure(this.delfromData)
            .then((res) => {
              this.$Message.success(res.msg);
              this.getList();
            })
            .catch((res) => {
              this.$Message.error(res.msg);
            });
          break;
        case '12':
          this.printImg(row.kuaidi_label);
          break;
        default:
          this.delfromData = {
            title: '删除订单',
            url: `/order/del/${row.id}`,
            method: 'DELETE',
            ids: '',
          };
          // this.modalTitleSs = '删除订单';
          this.delOrder(row, this.delfromData);
      }
    },
    printImg(url) {
      printJS({
        printable: url,
        type: 'image',
        documentTitle: '快递信息',
        style: `img{
          width: 100%;
          height: 476px;
        }`,
      });
    },
    // 立即支付 /确认收货//删除单条订单
    submitModel() {
      this.getList();
    },
    // 订单列表
    getList(res) {
      console.log(this.page, 'resresres');
      this.page.page = res === 1 ? 1 : this.page.page;
      this.loading = true;
      orderList({
        page: this.page.page,
        limit: this.page.limit,
        status: this.orderStatus,
        pay_type: this.orderPayType,
        data: this.orderTime,
        real_name: this.orderNum,
        field_key: this.fieldKey,
        type: this.orderType === 0 ? '' : this.orderType,
      })
        .then(async (res) => {
          let data = res.data;
          this.orderList = data.data;
          this.orderCards = data.stat;
          this.total = data.count;
          this.$nextTick(() => {
            //确保dom加载完毕
            this.setChecked();
          });
          this.$emit('on-changeCards', data.stat);
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 全选
    // onSelectTab (selection) {
    //     let isDel = selection.some(item => {
    //         return item.is_del === 1
    //     });
    //     this.getIsDel(isDel);
    //     this.getisDelIdListl(selection);
    // },
    //全选和取消全选时触发
    handleSelectAll(selection) {
      let ids = [];
      selection.map((e) => {
        ids.push(e.uid);
      });
      this.selectedIds = ids;
      this.$nextTick(() => {
        //确保dom加载完毕
        this.setChecked();
      });
    },
    //  选中某一行
    handleSelectRow(selection, row) {
      console.log(selection);
      let ids = [];
      selection.map((e) => {
        ids.push(e.uid);
      });
      this.selectedIds = ids;
      this.$nextTick(() => {
        //确保dom加载完毕
        this.setChecked();
      });
    },
    //  取消某一行
    handleCancelRow(selection, row) {
      this.isDel(selection);
      this.selectedIds.delete(row.id);
      this.$nextTick(() => {
        //确保dom加载完毕
        this.setChecked();
      });
    },
    setChecked() {
      //将new Set()转化为数组
      let ids = [...this.selectedIds];
      this.getisDelIdListl(ids);
      // 找到绑定的table的ref对应的dom，找到table的objData对象，objData保存的是当前页的数据
      let objData = this.$refs.table.objData;
      for (let index in objData) {
        if (this.selectedIds.has(objData[index].id)) {
          objData[index]._isChecked = true;
        }
      }
    },
    isDel(selection) {
      if (selection.findIndex((target) => target.is_del === 0) == -1) {
        this.getIsDel(1);
      } else {
        this.getIsDel(0);
      }
    },
    // 编辑
    edit(row) {
      this.getOrderData(row.id);
    },
    // 删除单条订单
    delOrder(row, data) {
      if (row.is_del === 1) {
        this.$modalSure(data)
          .then((res) => {
            this.$Message.success(res.msg);
            this.getList();
          })
          .catch((res) => {
            this.$Message.error(res.msg);
          });
      } else {
        const title = '错误！';
        const content = '<p>您选择的的订单存在用户未删除的订单，无法删除用户未删除的订单！</p>';
        this.$Modal.error({
          title: title,
          content: content,
        });
      }
    },
    splitOrderDetail(row) {
      this.$router.push({
        path: 'split_list',
        query: {
          id: row.id,
        },
      });
    },
    // 获取编辑表单数据
    getOrderData(id) {
      getOrdeDatas(id)
        .then(async (res) => {
          if (res.data.status === false) {
            return this.$authLapse(res.data);
          }
          this.$authLapse(res.data);
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 获取详情表单数据
    getData(id) {
      getDataInfo(id)
        .then(async (res) => {
          this.$refs.details.modals = true;
          this.orderDatalist = res.data;
          if (this.orderDatalist.orderInfo.refund_reason_wap_img) {
            try {
              this.orderDatalist.orderInfo.refund_reason_wap_img = JSON.parse(
                this.orderDatalist.orderInfo.refund_reason_wap_img,
              );
            } catch (e) {
              this.orderDatalist.orderInfo.refund_reason_wap_img = [];
            }
          }
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 修改成功
    submitFail() {
      this.getList();
      this.$emit('changeGetTabs');
    },
    // 获取退款表单数据
    getRefundData(id) {
      this.$modalForm(getRefundFrom(id)).then(() => {
        this.getList();
        this.$emit('changeGetTabs');
      });
    },
    // 获取退积分表单数据
    getRefundIntegral(id) {
      refundIntegral(id)
        .then(async (res) => {
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 不退款表单数据
    getNoRefundData(id) {
      this.$modalForm(getnoRefund(id)).then(() => {
        this.getList();
        this.$emit('changeGetTabs');
      });
    },
    // 发送货
    sendOrder(row) {
      this.$refs.send.total_num = row.total_num;
      this.$refs.send.modals = true;
      this.orderId = row.id;
      this.status = row._status;
      this.pay_type = row.pay_type;
      this.virtual_type = row.virtual_type;
      this.$refs.send.getList();
      this.$refs.send.getDeliveryList();
      this.$nextTick((e) => {
        this.$refs.send.getCartInfo(row._status, row.id);
      });
    },
    // 配送信息表单数据
    delivery(row) {
      getDistribution(row.id)
        .then(async (res) => {
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    change(status) {},
    // 数据导出；
    exportData: function () {
      this.$refs.table.exportCsv({
        filename: '商品列表',
      });
    },
    // 核销订单
    bindWrite(row) {
      let self = this;
      this.$Modal.confirm({
        title: '提示',
        content: '确定要核销该订单吗？',
        cancelText: '取消',
        closable: true,
        maskClosable: true,
        onOk: function () {
          writeUpdate(row.order_id)
            .then((res) => {
              self.$Message.success(res.msg);
              self.getList();
            })
            .catch((res) => {
              self.$Message.error(res.msg);
            });
        },
      });
    },
  },
};
</script>

<style scoped lang="stylus">
img {
  height: 36px;
  display: block;
}

.tabBox {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  margin-bottom: 2px;

  .tabBox_img {
    width: 36px;
    height: 36px;

    img {
      width: 100%;
      height: 100%;
    }
  }

  .tabBox_tit {
    width: 60%;
    font-size: 12px !important;
    margin: 0 10px 0 10px;
    letter-spacing: 1px;
    padding: 5px 0;
    box-sizing: border-box;
  }
}

.orderData >>>.ivu-table-cell {
  padding-left: 0 !important;
}

.vertical-center-modal {
  display: flex;
  align-items: center;
  justify-content: center;
}

.nickname {
}

.uid {
  color: #2d8cf0;
}

.pink_name {
  color: #666;
}

.img {
  display: flex;
  flex-wrap: wrap;

  img {
    width: 30px;
    height: 30px;
    margin-right: 3px;
  }
}
</style>
