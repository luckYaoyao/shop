<template>
  <div class="table_box">
    <el-form
      ref="orderData"
      :model="orderData"
      label-width="100px"
      label-position="right"
      class="tabform"
      @submit.native.prevent
    >
      <el-row :gutter="24" justify="end">
        <el-col :span="24" class="ivu-text-left">
          <el-form-item label="订单状态：">
            <el-radio-group v-model="orderData.status" type="button" @change="selectChange2(orderData.status)">
              <el-radio-button label="">全部</el-radio-button>
              <el-radio-button label="0">未支付</el-radio-button>
              <el-radio-button label="1">未发货</el-radio-button>
              <el-radio-button label="2">待收货</el-radio-button>
              <el-radio-button label="3">待评价</el-radio-button>
              <el-radio-button label="4">交易完成</el-radio-button>
              <el-radio-button label="5">待核销</el-radio-button>
              <el-radio-button label="6">已核销</el-radio-button>
              <!--                                <el-radio-button label="-1">退款中 {{  '(' +orderChartType.refunding?orderChartType.refunding:0+ ')' }}</el-radio-button>-->
              <!--                                <el-radio-button label="-2">已退款 {{  '(' +orderChartType.refund?orderChartType.refund:0+ ')' }}</el-radio-button>-->
              <el-radio-button label="-2">已退款</el-radio-button>
              <el-radio-button label="-4">已删除</el-radio-button>
            </el-radio-group>
          </el-form-item>
        </el-col>
        <el-col :span="24" class="ivu-text-left">
          <el-form-item label="支付方式：">
            <el-radio-group v-model="orderData.pay_type" type="button" @change="userSearchs">
              <el-radio-button v-for="item in payList" :label="item.val" :key="item.id">{{
                item.label
              }}</el-radio-button>
            </el-radio-group>
          </el-form-item>
        </el-col>
        <el-col :span="8" class="ivu-text-left">
          <el-form-item label="创建时间：">
            <el-date-picker
              v-model="timeVal"
              type="datetimerange"
              :editable="false"
              @change="onchangeTime"
              value-format="yyyy/MM/dd HH:mm:ss"
              style="width: 380px"
              class="mr20"
              range-separator="至"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
            ></el-date-picker>
          </el-form-item>
        </el-col>
        <el-col :span="16">
          <el-col :span="12" class="mr">
            <el-form-item label="搜索：" prop="real_name" label-for="real_name">
              <el-input
                v-model="orderData.real_name"
                search
                enter-button
                placeholder="请输入"
                element-id="name"
                @on-search="orderSearch(orderData.real_name)"
              >
                <el-select v-model="orderData.field_key" slot="prepend" style="width: 80px">
                  <el-option value="all" label="全部"></el-option>
                  <el-option value="order_id" label="订单号"></el-option>
                  <el-option value="uid" label="UID"></el-option>
                  <el-option value="real_name" label="用户姓名"></el-option>
                  <el-option value="user_phone" label="用户电话"></el-option>
                  <el-option value="title" label="商品名称(模糊)"></el-option>
                </el-select>
              </el-input>
            </el-form-item>
          </el-col>
          <!--<el-col v-bind="grid">-->
          <!--<el-button class="mr">导出</el-button>-->
          <!--<span class="Refresh" @click="Refresh">刷新</span><Icon type="ios-refresh" />-->
          <!--</el-col>-->
        </el-col>
        <el-col :span="24">
          <div class="ml20">
            <el-button v-auth="['order-dels']" class="mr10" type="primary" @click="delAll">批量删除订单</el-button>
            <el-button v-auth="['order-write']" type="success" class="mr10 greens" @click="writeOff">
              <Icon type="md-list"></Icon>
              订单核销
            </el-button>
            <el-button v-auth="['export-storeOrder']" class="export" icon="ios-share-outline" @click="exportList"
              >导出</el-button
            >
          </div>
        </el-col>
      </el-row>
    </el-form>
    <!--订单核销模态框-->
    <el-dialog
      :visible.sync="modals2"
      title="订单核销"
      class="paymentFooter"
      :show-close="false"
      width="400px"
      @closed="changeModal"
    >
      <el-form
        ref="writeOffFrom"
        :model="writeOffFrom"
        :rules="writeOffRules"
        label-position="right"
        class="tabform"
        @submit.native.prevent
      >
        <el-form-item prop="code" label-for="code">
          <el-input style="width: 100%" type="text" placeholder="请输入12位核销码" v-model.number="writeOffFrom.code" />
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button type="primary" @click="ok('writeOffFrom')">立即核销</el-button>
        <el-button @click="del('writeOffFrom')">取消</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import { putWrite, storeOrderApi } from '@/api/order';
import { exportOrderList } from '@/api/export';
export default {
  name: 'table_from',
  data() {
    const codeNum = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('请填写核销码'));
      }
      // 模拟异步验证效果
      if (!Number.isInteger(value)) {
        callback(new Error('请填写12位数字'));
      } else {
        const reg = /\b\d{12}\b/;
        if (!reg.test(value)) {
          callback(new Error('请填写12位数字'));
        } else {
          callback();
        }
      }
    };
    return {
      fromList: {
        title: '选择时间',
        custom: true,
        fromTxt: [
          { text: '全部', val: '' },
          { text: '今天', val: 'today' },
          { text: '昨天', val: 'yesterday' },
          { text: '最近7天', val: 'lately7' },
          { text: '最近30天', val: 'lately30' },
          { text: '本月', val: 'month' },
          { text: '本年', val: 'year' },
        ],
      },
      currentTab: '',
      grid: {
        xl: 8,
        lg: 8,
        md: 8,
        sm: 24,
        xs: 24,
      },
      // 搜索条件
      orderData: {
        status: '',
        data: '',
        real_name: '',
        field_key: 'all',
        pay_type: '',
        type: '',
      },
      modalTitleSs: '',
      statusType: '',
      time: '',
      value2: [],
      writeOffRules: {
        code: [{ validator: codeNum, trigger: 'blur', required: true }],
      },
      writeOffFrom: {
        code: '',
        confirm: 0,
      },
      modals2: false,
      timeVal: [],
      options: {
        shortcuts: [
          {
            text: '今天',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()));
              return [start, end];
            },
          },
          {
            text: '昨天',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(
                start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 1)),
              );
              end.setTime(
                end.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()) - 1),
              );
              return [start, end];
            },
          },
          {
            text: '最近7天',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(
                start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 6)),
              );
              return [start, end];
            },
          },
          {
            text: '最近30天',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(
                start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 29)),
              );
              return [start, end];
            },
          },
          {
            text: '本月',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(start.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), 1)));
              return [start, end];
            },
          },
          {
            text: '本年',
            value() {
              const end = new Date();
              const start = new Date();
              start.setTime(start.setTime(new Date(new Date().getFullYear(), 0, 1)));
              return [start, end];
            },
          },
        ],
      },
      payList: [
        { label: '全部', val: '' },
        { label: '微信支付', val: '1' },
        { label: '支付宝支付', val: '4' },
        { label: '余额支付', val: '2' },
        { label: '线下支付', val: '3' },
      ],
    };
  },
  computed: {
    ...mapState('order', ['orderChartType', 'isDels', 'delIdList', 'orderType']),

    today() {
      const end = new Date();
      const start = new Date();
      var datetimeStart = start.getFullYear() + '/' + (start.getMonth() + 1) + '/' + start.getDate();
      var datetimeEnd = end.getFullYear() + '/' + (end.getMonth() + 1) + '/' + end.getDate();
      return [datetimeStart, datetimeEnd];
    },
  },
  watch: {
    $route() {
      if (this.$route.fullPath === this.$routeProStr + '/order/list?status=1') {
        this.getPath();
      }
    },
  },
  created() {
    // this.timeVal = this.today;
    // this.orderData.data = this.timeVal ? this.timeVal.join('-') : '';
    if (this.$route.fullPath === this.$routeProStr + '/order/list?status=1') {
      this.getPath();
    }
  },
  methods: {
    ...mapMutations('order', ['getOrderStatus', 'getOrderType', 'getOrderTime', 'getOrderNum', 'getfieldKey']),
    getPath() {
      this.orderData.status = this.$route.query.status.toString();
      this.getOrderStatus(this.orderData.status);
      this.$emit('getList', 1);
    },
    changeModal() {
      this.writeOffFrom.code = '';
    },
    // 导出
    async exportList() {
      this.orderData.type = this.orderType === 0 ? '' : this.orderType;
      let [th, filekey, data, fileName] = [[], [], [], ''];
      let excelData = JSON.parse(JSON.stringify(this.orderData));
      excelData.page = 1;
      excelData.limit = 200;
      excelData.ids = this.delIdList;
      for (let i = 0; i < excelData.page + 1; i++) {
        let lebData = await this.getExcelData(excelData);
        if (!fileName) fileName = lebData.filename;
        if (!filekey.length) {
          filekey = lebData.fileKey;
        }
        if (!th.length) th = lebData.header;
        if (lebData.export.length) {
          data = data.concat(lebData.export);
          excelData.page++;
        } else {
          this.$exportExcel(th, filekey, fileName, data);
          return;
        }
      }
    },
    getExcelData(excelData) {
      return new Promise((resolve, reject) => {
        exportOrderList(excelData).then((res) => {
          resolve(res.data);
        });
      });
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e || [];
      this.orderData.data = this.timeVal[0] ? (this.timeVal ? this.timeVal.join('-') : '') : '';
      this.$store.dispatch('order/getOrderTabs', { data: this.orderData.data });
      this.getOrderTime(this.orderData.data);
      this.$emit('getList', 1);
    },
    // 选择时间
    selectChange(tab) {
      this.$store.dispatch('order/getOrderTabs', { data: tab });
      this.orderData.data = tab;
      this.getOrderTime(this.orderData.data);
      this.timeVal = [];
      this.$emit('getList');
    },
    // 订单选择状态
    selectChange2(tab) {
      this.getOrderStatus(tab);
      this.$emit('getList', 1);
    },
    userSearchs(type) {
      this.getOrderType(type);
      this.$emit('getList', 1);
    },
    // 时间状态
    timeChange(time) {
      this.getOrderTime(time);
      this.$emit('getList');
    },
    // 订单号搜索
    orderSearch(num) {
      this.getOrderNum(num);
      this.getfieldKey(this.orderData.field_key);
      this.$emit('getList', 1);
    },
    // 点击订单类型
    onClickTab() {
      this.$emit('onChangeType', this.currentTab);
    },
    // 批量删除
    delAll() {
      if (this.delIdList.length === 0) {
        this.$message.error('请先选择删除的订单！');
      } else {
        if (this.isDels) {
          let idss = {
            ids: this.delIdList,
          };
          let delfromData = {
            title: '删除订单',
            url: `/order/dels`,
            method: 'post',
            ids: idss,
          };
          this.$modalSure(delfromData)
            .then((res) => {
              this.$message.success(res.msg);
              this.$emit('getList');
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          const title = '错误！';
          const content = '<p>您选择的的订单存在用户未删除的订单，无法删除用户未删除的订单！</p>';
          this.$Modal.error({
            title: title,
            content: content,
          });
        }
      }
    },
    // 订单核销
    writeOff() {
      this.modals2 = true;
    },
    // 验证
    search(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.writeOffFrom.confirm = 0;
          putWrite(this.writeOffFrom)
            .then(async (res) => {
              if (res.status === 200) {
                this.$message.success(res.msg);
                // this.modals2 = false
                // this.$refs[name].resetFields()
                // this.$emit('getList')
              } else {
                this.$message.error(res.msg);
              }
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          this.$message.error('请填写正确的核销码');
        }
      });
    },
    // 订单核销
    ok(name) {
      if (!this.writeOffFrom.code) {
        this.$message.warning('请先验证订单！');
      } else {
        this.writeOffFrom.confirm = 1;
        putWrite(this.writeOffFrom)
          .then(async (res) => {
            if (res.status === 200) {
              this.$message.success(res.msg);
              this.modals2 = false;
              this.$refs[name].resetFields();
              this.$emit('getList', 1);
            } else {
              this.$message.error(res.msg);
            }
          })
          .catch((res) => {
            this.$message.error(res.msg);
          });
      }
    },
    del(name) {
      this.modals2 = false;
      this.writeOffFrom.code = '';
      this.$refs[name].resetFields();
    },
    handleSubmit() {
      this.$emit('on-submit', this.data);
    },
    // 刷新
    Refresh() {
      this.$emit('getList');
    },
    //
    handleReset() {
      this.$refs.form.resetFields();
      this.$emit('on-reset');
    },
  },
};
</script>

<style scoped lang="stylus">
.tab_data >>> .ivu-form-item-content {
  margin-left: 0 !important;
}

.table_box >>> .ivu-divider-horizontal {
  margin-top: 0px !important;
}

.table_box >>> .ivu-form-item {
  margin-bottom: 15px !important;
}

.tabform {
  margin-bottom: 10px;
}

.Refresh {
  font-size: 12px;
  color: #1890FF;
  cursor: pointer;
}
</style>
