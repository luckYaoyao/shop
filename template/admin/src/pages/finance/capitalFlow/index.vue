<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt mb10">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        class="tabform"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24">
            <el-form-item label="订单时间：">
              <DatePicker
                :editable="false"
                :clearable="false"
                @change="onchangeTime"
                :value="timeVal"
                format="yyyy/MM/dd"
                type="daterange"
                placement="bottom-start"
                placeholder="请选择时间"
                style="width: 200px"
                :options="options"
                class="mr20"
              ></DatePicker>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="交易类型：">
              <el-select
                type="button"
                v-model="formValidate.status"
                class="mr15"
                @change="selChange"
                style="width: 30%"
              >
                <el-option :label="item" :value="index" v-for="(item, index) in withdrawal" :key="index"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="流水搜索：">
              <div class="acea-row row-middle">
                <el-input
                  search
                  enter-button
                  @on-search="getList"
                  placeholder="订单号/昵称/电话/用户ID"
                  element-id="name"
                  v-model="formValidate.keywords"
                  style="width: 30%"
                />
              </div>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </el-card>
    <el-card :bordered="false" shadow="never">
      <el-table ref="table" :data="tabList" class="ivu-mt" v-loading="loading" empty-text="暂无数据">
        <el-table-column label="交易单号" width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.flow_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="关联订单" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.order_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="交易时间" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="交易金额" min-width="90">
          <template slot-scope="scope">
            <div v-if="scope.row.price >= 0" class="z-price">+{{ scope.row.price }}</div>
            <div v-if="scope.row.price < 0" class="f-price">{{ scope.row.price }}</div>
          </template>
        </el-table-column>
        <el-table-column label="交易用户" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="支付方式" min-width="90">
          <template slot-scope="scope">
            <div v-for="item in payment" :key="item.value">
              <span v-if="scope.row.pay_type == item.value"> {{ item.title }} </span>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="备注" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.mark }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="170">
          <template slot-scope="scope">
            <a @click="setMark(scope.row)">备注</a>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
    <!-- 拒绝通过-->
    <Modal v-model="modals" scrollable closable title="备注" :mask-closable="false">
      <el-input v-model="mark_msg.mark" type="textarea" :rows="4" placeholder="请输入备注" />
      <div slot="footer">
        <el-button type="primary" size="large" long :loading="modal_loading" @click.prevent="oks">确定</el-button>
      </div>
    </Modal>
  </div>
</template>
<script>
import searchFrom from '@/components/publicSearchFrom';
import { mapState } from 'vuex';
import { getFlowList, cashEditApi, setMarks } from '@/api/finance';
import { formatDate } from '@/utils/validate';
import editFrom from '@/components/from/from';
export default {
  name: 'cashApply',
  components: { searchFrom, editFrom },
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
  data() {
    return {
      images: ['1.jpg', '2.jpg'],
      modal_loading: false,
      options: this.$timeOptions,

      mark_msg: {
        mark: '',
      },
      modals: false,
      total: 0,
      loading: false,
      columns: [
        {
          title: '交易单号',
          key: 'flow_id',
          width: 180,
        },
        {
          title: '关联订单',
          key: 'order_id',
          minWidth: 180,
        },
        {
          title: '交易时间',
          key: 'add_time',
          minWidth: 90,
        },
        {
          title: '交易金额',
          slot: 'price',
          minWidth: 150,
        },
        {
          title: '交易用户',
          key: 'nickname',
          minWidth: 150,
        },
        {
          title: '交易类型',
          key: 'trading_type',
          minWidth: 100,
        },
        {
          title: '支付方式',
          slot: 'pay_type',
          minWidth: 100,
        },
        {
          title: '备注',
          key: 'mark',
          minWidth: 100,
        },
        {
          title: '操作',
          slot: 'set',
          fixed: 'right',
          width: 100,
        },
      ],
      tabList: [],
      withdrawal: [],
      payment: [
        {
          title: '全部',
          value: '',
        },
        {
          title: '微信',
          value: 'weixin',
        },
        {
          title: '支付宝',
          value: 'alipay',
        },
        {
          title: '银行卡',
          value: 'bank',
        },
        {
          title: '线下支付',
          value: 'offline',
        },
      ],
      formValidate: {
        trading_type: 0,
        time: '',
        keywords: '',
        page: 1,
        limit: 20,
      },
      timeVal: [],
      FromData: null,
      extractId: 0,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '85px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'left';
    },
  },
  mounted() {
    this.getList();
  },
  methods: {
    // 确定
    oks() {
      this.modal_loading = true;
      setMarks(this.extractId, this.mark_msg)
        .then((res) => {
          this.$Message.success(res.msg);
          this.modal_loading = false;
          this.modals = false;
          this.getList();
        })
        .catch((err) => {
          this.modal_loading = false;
          this.$Message.error(err.msg);
        });
    },
    // 备注
    setMark(row) {
      this.modals = true;
      this.extractId = row.id;
      this.mark_msg.mark = row.mark;
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.time = this.timeVal.join('-');
      this.formValidate.page = 1;
      this.getList();
    },
    // 选择
    selChange(e) {
      this.formValidate.page = 1;
      this.formValidate.trading_type = e;
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      getFlowList(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.withdrawal = data.status;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 编辑提交成功
    submitFail() {
      this.getList();
    },
  },
};
</script>
<style scoped lang="stylus">
.ivu-mt .type .item {
  margin: 3px 0;
}

.tabform {
}

.Refresh {
  font-size: 12px;
  color: #1890FF;
  cursor: pointer;
}

.ivu-form-item {
  margin-bottom: 10px;
}

.status >>> .item~.item {
  margin-left: 6px;
}

.status >>> .statusVal {
  margin-bottom: 7px;
}

/* .ivu-mt >>> .ivu-table-header */
/* border-top:1px dashed #ddd!important */
.type {
  padding: 3px 0;
  box-sizing: border-box;
}

.tabBox_img {
  width: 36px;
  height: 36px;
  border-radius: 4px;
  cursor: pointer;

  img {
    width: 100%;
    height: 100%;
  }
}

.z-price {
  color: red;
}

.f-price {
  color: green;
}
</style>
