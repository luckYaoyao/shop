<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt mb10">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="6">
            <el-form-item label="订单时间：">
              <!-- <dateRadio @selectDate="onSelectDate"></dateRadio> -->
              <DatePicker
                :editable="false"
                @change="onchangeTime"
                :value="timeVal"
                format="yyyy/MM/dd"
                type="daterange"
                placement="bottom-start"
                placeholder="请选择时间"
                style="width: 100%"
                :options="options"
                clearable
                class="mr20"
              ></DatePicker>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="交易类型：">
              <el-select
                type="button"
                v-model="formValidate.trading_type"
                class="mr15"
                @change="selChange"
                style="width: 100%"
                clearable
              >
                <el-option
                  :label="item"
                  :value="Object.keys(withdrawal)[index]"
                  v-for="(item, index) in Object.values(withdrawal)"
                  :key="index"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </el-card>
    <el-card :bordered="false" shadow="never">
      <el-table ref="table" :data="tabList" class="ivu-mt" :loading="loading" empty-text="暂无数据">
        <el-table-column label="ID" min-width="50">
          <template slot-scope="scope">
            <div>{{ scope.row.id }}</div>
          </template>
        </el-table-column>
        <el-table-column label="关联订单" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.relation }}</div>
          </template>
        </el-table-column>
        <el-table-column label="交易时间" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.add_time }}</div>
          </template>
        </el-table-column>
        <el-table-column label="交易积分" min-width="80">
          <template slot-scope="scope">
            <div v-if="scope.row.pm" class="z-price">+ {{ scope.row.number }}</div>
            <div v-else class="f-price">- {{ scope.row.number }}</div>
          </template>
        </el-table-column>
        <el-table-column label="用户" min-width="80">
          <template slot-scope="scope">
            <div>{{ scope.row.nickname }}</div>
          </template>
        </el-table-column>
        <el-table-column label="交易类型" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.type_name }}</div>
          </template>
        </el-table-column>
        <el-table-column label="备注" width="100">
          <template slot-scope="scope">
            <a class="item" @click="setMark(scope.row)">备注</a>
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
        <el-button type="primary" size="large" long :loading="modal_loading" @click="oks">确定</el-button>
      </div>
    </Modal>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import { pointRecordList, setPointRecordMark } from '@/api/marketing';
import { formatDate } from '@/utils/validate';
import dateRadio from '@/components/dateRadio';
export default {
  name: 'cashApply',
  components: { dateRadio },
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
          title: 'ID',
          key: 'id',
          width: 50,
        },
        {
          title: '关联订单',
          key: 'relation',
          minWidth: 120,
        },
        {
          title: '交易时间',
          key: 'add_time',
          minWidth: 90,
        },
        {
          title: '交易积分',
          slot: 'number',
          minWidth: 100,
        },
        {
          title: '用户',
          key: 'nickname',
          minWidth: 80,
        },
        {
          title: '交易类型',
          key: 'type_name',
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
      selectIndexTime: '',
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
        trading_type: '',
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
      this.mark_msg.mark = this.mark_msg.mark.trim();
      setPointRecordMark(this.extractId, this.mark_msg)
        .then(async (res) => {
          this.$Message.success(res.msg);
          this.modal_loading = false;
          this.modals = false;
          this.getList();
        })
        .catch((res) => {
          this.modal_loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 备注
    setMark(row) {
      this.modals = true;
      this.extractId = row.id;
      this.mark_msg.mark = row.mark;
    },
    onSelectDate(e) {
      this.formValidate.time = e;
      this.formValidate.page = 1;
      this.getList();
    },
    dateToMs(date) {
      let result = new Date(date).getTime();
      return result;
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
      pointRecordList(this.formValidate)
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
