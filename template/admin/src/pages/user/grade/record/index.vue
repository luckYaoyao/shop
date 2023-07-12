<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        class="tabform"
        @submit.native.prevent
      >
        <el-row :gutter="24" justify="end">
          <el-col :span="24" class="ivu-text-left">
            <el-col :span="7" class="ivu-text-left">
              <el-form-item label="会员类型：">
                <el-select v-model="formValidate.member_type" clearable @change="userSearchs">
                  <el-option v-for="item in treeSelect" :value="item.id" :key="item.id" :label="item.label"></el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="7" class="ivu-text-left ml20">
              <el-form-item label="支付方式：">
                <el-select v-model="formValidate.pay_type" clearable @change="paySearchs">
                  <el-option v-for="item in payList" :value="item.val" :key="item.val" :label="item.label"></el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="7" class="ivu-text-left ml20">
              <el-form-item label="购买时间：">
                <el-date-picker
                  :editable="false"
                  @change="onchangeTime"
                  v-model="timeVal"
                  format="yyyy/MM/dd"
                  type="datetimerange"
                  value-format="yyyy/MM/dd"
                  range-separator="至"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期"
                  
                ></el-date-picker>
              </el-form-item>
            </el-col>
            <el-col :span="7" class="ivu-text-left">
              <el-form-item label="搜索：">
                <el-input
                  search
                  enter-button
                  @on-search="selChange"
                  placeholder="请输入用户名称搜索"
                  element-id="name"
                  v-model="formValidate.name"
                  style="width: 90%; display: inline-table"
                  class="mr"
                />
              </el-form-item>
            </el-col>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :data="tbody"
        ref="table"
        v-loading="loading"
        size="small"
        no-userFrom-text="暂无数据"
        no-filtered-userFrom-text="暂无筛选结果"
      >
        <el-table-column label="订单号" width="170">
          <template slot-scope="scope">
            <span>{{ scope.row.order_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="用户名" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.user.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="手机号码" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.user.phone || '--' }}</span>
          </template>
        </el-table-column>
        <el-table-column label="会员类型" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.member_type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="有效期限（天）" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.vip_day === -1 ? '永久' : scope.row.vip_day }}</span>
          </template>
        </el-table-column>
        <el-table-column label="支付金额（元）" min-width="50">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="支付方式" min-width="30">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="购买时间" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_time }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tablePage.page"
          :limit.sync="tablePage.limit"
          @pagination="getMemberRecord"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { userMemberCard, memberRecord } from '@/api/user';
import { mapState } from 'vuex';

export default {
  name: 'card',
  data() {
    return {
      treeSelect: [
        {
          id: 'free',
          label: '试用',
        },
        {
          id: 'card',
          label: '卡密',
        },
        {
          id: 'month',
          label: '月卡',
        },
        {
          id: 'quarter',
          label: '季卡',
        },
        {
          id: 'year',
          label: '年卡',
        },
        {
          id: 'ever',
          label: '永久',
        },
      ],
      payList: [
        {
          val: 'free',
          label: '免费',
        },
        {
          val: 'weixin',
          label: '微信',
        },
        {
          val: 'alipay',
          label: '支付宝',
        },
      ],
      thead: [
        {
          title: '订单号',
          key: 'order_id',
          minWidth: 100,
        },
        {
          title: '用户名',
          minWidth: 50,
          ellipsis: true,
          render: (h, params) => {
            return h('span', params.row.user.nickname);
          },
        },
        {
          title: '手机号码',
          minWidth: 80,
          render: (h, params) => {
            return h('span', params.row.user.phone || '--');
          },
        },
        {
          title: '会员类型',
          key: 'member_type',
          minWidth: 40,
        },
        {
          title: '有效期限（天）',
          minWidth: 50,
          render: (h, params) => {
            return h('span', params.row.vip_day === -1 ? '永久' : params.row.vip_day);
          },
        },
        {
          title: '支付金额（元）',
          key: 'pay_price',
          minWidth: 50,
        },
        {
          title: '支付方式',
          key: 'pay_type',
          minWidth: 30,
        },
        {
          title: '购买时间',
          key: 'pay_time',
          minWidth: 90,
        },
      ],
      tbody: [],
      loading: false,
      total: 0,
      formValidate: {
        name: '',
        member_type: '',
        pay_type: '',
        add_time: '',
      },
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
                end.setTime(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 1)),
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
      timeVal: [],
      tablePage: {
        page: 1,
        limit: 15,
      },
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '75px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getMemberRecord();
  },
  methods: {
    // 用户名搜索；
    selChange() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    //用户类型搜索；
    userSearchs() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    //支付方式搜索；
    paySearchs() {
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e || [];
      this.formValidate.add_time = this.timeVal[0] ? this.timeVal ? this.timeVal.join('-') : '' : '';
      this.tablePage.page = 1;
      this.getMemberRecord();
    },
    getMemberRecord() {
      this.loading = true;
      let data = {
        page: this.tablePage.page,
        limit: this.tablePage.limit,
        member_type: this.formValidate.member_type,
        pay_type: this.formValidate.pay_type,
        add_time: this.formValidate.add_time,
        name: this.formValidate.name,
      };
      memberRecord(data)
        .then((res) => {
          this.loading = false;
          const { list, count } = res.data;
          this.tbody = list;
          this.total = count;
        })
        .catch((err) => {
          this.loading = false;
          this.$message.error(err.msg);
        });
    },
  },
};
</script>
