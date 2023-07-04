<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        class="tabform"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :xl="5" :lg="8" :md="10" :sm="11" :xs="24" class="mr10">
            <el-form-item label="昵称/ID：">
              <el-input
                enter-button
                placeholder="请输入"
                element-id="nickname"
                v-model="formValidate.nickname"
                clearable
              />
            </el-form-item>
          </el-col>
          <el-col :xl="6" :lg="12" :md="13" :sm="12" :xs="24">
            <el-form-item label="佣金范围：" class="tab_data">
              <el-input-number
                type="number"
                :min="0"
                enter-button
                placeholder="￥"
                element-id="price_min"
                class="mr10"
                v-model="formValidate.price_min"
              />
              <span class="mr10">一</span>
              <el-input-number
                type="number"
                :min="0"
                enter-button
                placeholder="￥"
                element-id="price_max"
                v-model="formValidate.price_max"
              />
            </el-form-item>
          </el-col>
          <el-col>
            <el-button type="primary" icon="ios-search" @click="userSearchs">搜索</el-button>
            <el-button v-auth="['export-userCommission']" class="export" icon="ios-share-outline" @click="exports"
              >导出
            </el-button>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        ref="table"
        :columns="columns"
        :data="tabList"
        :loading="loading"
        empty-text="暂无数据"
        @on-sort-change="sortChanged"
      >
        <el-table-column label="用户信息" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="总佣金金额" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.sum_number }}</span>
          </template>
        </el-table-column>
        <el-table-column label="账户余额" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.now_money }}</span>
          </template>
        </el-table-column>
        <el-table-column label="账户佣金" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.brokerage_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="提现到账佣金" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.extract_price }}</span>
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
    <commission-details ref="commission"></commission-details>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import { commissionListApi, userCommissionApi } from '@/api/finance';
import commissionDetails from './handle/commissionDetails';

export default {
  name: 'commissionRecord',
  components: { commissionDetails },
  data() {
    return {
      total: 0,
      loading: false,
      tabList: [],
      formValidate: {
        nickname: '',
        price_max: '',
        price_min: '',
        excel: 0,
        page: 1, // 当前页
        limit: 20, // 每页显示条数
      },
      columns: [
        {
          title: '用户信息',
          key: 'nickname',
          minWidth: 150,
        },
        {
          title: '总佣金金额',
          key: 'sum_number',
          sortable: 'custom',
          minWidth: 120,
        },
        {
          title: '账户余额',
          key: 'now_money',
          minWidth: 100,
        },
        {
          title: '账户佣金',
          key: 'brokerage_price',
          sortable: 'custom',
          minWidth: 120,
        },
        {
          title: '提现到账佣金',
          key: 'extract_price',
          minWidth: 150,
        },
        // {
        //   title: '操作',
        //   slot: 'action',
        //   fixed: 'right',
        //   minWidth: 100
        // }
      ],
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
    // 列表
    getList() {
      this.loading = true;
      commissionListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 导出
    exports() {
      let formValidate = this.formValidate;
      let data = {
        price_max: formValidate.price_max,
        price_min: formValidate.price_min,
        nickname: formValidate.nickname,
      };
      userCommissionApi(data)
        .then((res) => {
          location.href = res.data[0];
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 详情
    Info(row) {
      this.$refs.commission.modals = true;
      this.$refs.commission.getDetails(row.uid);
      this.$refs.commission.getList(row.uid);
    },
    // 排序
    sortChanged(e) {
      if (e.key == 'sum_number') {
        delete this.formValidate.brokerage_price;
      } else {
        delete this.formValidate.sum_number;
      }
      this.formValidate[e.key] = e.order;
      this.getList();
    },
  },
};
</script>

<style scoped lang="stylus">
.lines {
  padding-top: 6px !important;
}

.tabform .export {
  margin-left: 10px;
}

.tab_data >>> .ivu-form-item-content {
  display: flex !important;
}
</style>
