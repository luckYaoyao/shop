<template>
  <div class="article-manager">
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24">
            <el-form-item label="时间选择：">
              <el-radio-group
                v-model="formValidate.data"
                type="button"
                @change="selectChange(formValidate.data)"
                class="mr"
              >
                <el-radio :label="item.val" v-for="(item, i) in fromList.fromTxt" :key="i">{{ item.text }}</el-radio>
              </el-radio-group>
              <DatePicker
                :editable="false"
                @change="onchangeTime"
                :value="timeVal"
                format="yyyy/MM/dd"
                type="daterange"
                placement="bottom-end"
                placeholder="请选择时间"
                style="width: 200px"
              ></DatePicker>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="砍价状态：">
              <el-select v-model="formValidate.status" placeholder="请选择" clearable @change="userSearchs">
                <el-option :value="1" label="进行中"></el-option>
                <el-option :value="2" label="已失败"></el-option>
                <el-option :value="3" label="已成功"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :columns="columns1"
        :data="tableList"
        :loading="loading"
        highlight-current-row
        no-userFrom-text="暂无数据"
        no-filtered-userFrom-text="暂无筛选结果"
      >
        <el-table-column label="头像" width="80">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.avatar" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="发起用户" min-width="100">
          <template slot-scope="scope">
            <span> {{ scope.row.nickname + ' / ' + scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="开启时间" min-width="110">
          <template slot-scope="scope">
            <span> {{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="砍价商品" min-width="300">
          <template slot-scope="scope">
            <span> {{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="最低价" min-width="60">
          <template slot-scope="scope">
            <span> {{ scope.row.bargain_price_min }}</span>
          </template>
        </el-table-column>
        <el-table-column label="当前价" min-width="60">
          <template slot-scope="scope">
            <span> {{ scope.row.now_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="总砍价次数" min-width="60">
          <template slot-scope="scope">
            <span> {{ scope.row.people_num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="剩余砍价次数" min-width="100">
          <template slot-scope="scope">
            <span> {{ scope.row.num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="结束时间" min-width="150">
          <template slot-scope="scope">
            <span> {{ scope.row.datatime }}</span>
          </template>
        </el-table-column>
        <el-table-column label="状态" min-width="100">
          <template slot-scope="scope">
            <Tag color="blue" v-show="scope.row.status === 1">进行中</Tag>
            <Tag color="volcano" v-show="scope.row.status === 2">已失败</Tag>
            <Tag color="cyan" v-show="scope.row.status === 3">已成功</Tag>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="100">
          <template slot-scope="scope">
            <a @click="Info(scope.row)">查看详情</a>
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

    <!-- 详情模态框-->
    <Modal
      v-model="modals"
      class="tableBox"
      scrollable
      footer-hide
      closable
      title="查看详情"
      :mask-closable="false"
      width="750"
    >
      <el-table
        ref="selection"
        :data="tabList3"
        v-loading="loading2"
        empty-text="暂无数据"
        highlight-current-row
        max-height="600"
        size="small"
      >
        <el-table-column label="用户ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="用户头像" min-width="100">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.avatar" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="用户名称" min-width="100">
          <template slot-scope="scope">
            <span> {{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="砍价金额" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="砍价时间" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
      </el-table>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { formatDate } from '@/utils/validate';
import { bargainUserListApi, bargainUserInfoApi } from '@/api/marketing';
export default {
  name: 'bargainList',
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
  // components: { cardsData },
  data() {
    return {
      cardLists: [],
      modals: false,
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
      grid: {
        xl: 7,
        lg: 10,
        md: 12,
        sm: 12,
        xs: 24,
      },
      loading: false,
      formValidate: {
        status: '',
        data: '',
        page: 1,
        limit: 15,
      },
      columns1: [
        {
          title: '头像',
          slot: 'avatar',
          minWidth: 100,
        },
        {
          title: '发起用户',
          slot: 'nickname',
          minWidth: 150,
        },
        {
          title: '开启时间',
          key: 'add_time',
          minWidth: 150,
        },
        {
          title: '砍价商品',
          key: 'title',
          minWidth: 300,
        },
        {
          title: '最低价',
          key: 'bargain_price_min',
          minWidth: 120,
        },
        {
          title: '当前价',
          key: 'now_price',
          minWidth: 100,
        },
        {
          title: '总砍价次数',
          key: 'people_num',
          minWidth: 100,
        },
        {
          title: '剩余砍价次数',
          key: 'num',
          minWidth: 100,
        },
        {
          title: '结束时间',
          key: 'datatime',
          minWidth: 150,
        },
        {
          title: '状态',
          slot: 'status',
          minWidth: 100,
        },
        {
          title: '操作',
          slot: 'action',
          fixed: 'right',
          minWidth: 170,
        },
      ],
      tableList: [],
      total: 0,
      timeVal: [],
      loading2: false,
      tabList3: [],
      columns2: [
        {
          title: '用户ID',
          key: 'uid',
          width: 80,
        },
        {
          title: '用户头像',
          slot: 'avatar',
        },
        {
          title: '用户名称',
          slot: 'nickname',
          minWidth: 100,
        },
        {
          title: '砍价金额',
          key: 'price',
        },
        {
          title: '砍价时间',
          key: 'add_time',
          minWidth: 100,
        },
      ],
      rows: {},
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
    this.getList();
  },
  methods: {
    // 查看详情
    Info(row) {
      this.modals = true;
      this.rows = row;
      bargainUserInfoApi(row.id)
        .then(async (res) => {
          let data = res.data;
          this.tabList3 = data.list;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal[0] ? this.timeVal.join('-') : '';
      this.formValidate.page = 1;
      this.getList();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.page = 1;
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      this.formValidate.status = this.formValidate.status || '';
      bargainUserListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tableList = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 表格搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
  },
};
</script>

<style scoped lang="stylus">
/deep/.ivu-tag-cyan .ivu-tag-text{
    color #19be6b!important
}
.ivu-tag-cyan{
    background: rgba(25,190,170,0.1);
    border-color: #19be6b!important;
}
.tabBox_img
    width 36px
    height 36px
    border-radius:4px
    cursor pointer
    img
        width 100%
        height 100%
</style>
