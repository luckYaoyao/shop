<template>
  <div>
    <el-dialog
      :visible.sync="modals"
      :title="listTitle === 'man' ? '统计推广人列表' : '推广订单'"
      :close-on-click-modal="false"
      width="900"
      @closed="onCancel"
    >
      <div class="table_box">
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
              <el-form-item label="时间选择：">
                <el-radio-group
                  v-model="formValidate.data"
                  type="button"
                  @change="selectChange(formValidate.data)"
                  class="mr"
                >
                  <el-radio-button :label="item.val" v-for="(item, i) in fromList.fromTxt" :key="i">{{
                    item.text
                  }}</el-radio-button>
                </el-radio-group>
                <el-date-picker
                  :editable="false"
                  @change="onchangeTime"
                  v-model="timeVal"
                  value-format="yyyy/MM/dd"
                  type="daterange"
                  range-separator="至"
                  start-placeholder="开始日期"
                  end-placeholder="结束日期"
                  style="width: 200px"
                ></el-date-picker>
              </el-form-item>
            </el-col>
            <el-col :span="24" class="ivu-text-left">
              <el-col :xl="15" :lg="15" :md="20" :sm="24" :xs="24">
                <el-form-item label="用户类型：">
                  <el-radio-group v-model="formValidate.type" type="button" @change="userSearchs" class="mr">
                    <el-radio-button
                      :label="item.val"
                      v-for="(item, i) in listTitle === 'man' ? fromList.fromTxt2 : fromList.fromTxt3"
                      :key="i"
                    >
                      {{ item.text }}
                    </el-radio-button>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :xl="15" :lg="15" :md="20" :sm="24" :xs="24" v-if="listTitle === 'man'">
                <el-form-item label="搜索：">
                  <el-input
                    search
                    enter-button
                    placeholder="请输入请姓名、电话、UID"
                    v-model="formValidate.nickname"
                    style="width: 90%"
                    @on-search="userSearchs"
                  ></el-input>
                </el-form-item>
              </el-col>
              <el-col :xl="15" :lg="15" :md="20" :sm="24" :xs="24" v-if="listTitle === 'order'">
                <el-form-item label="订单号：">
                  <el-input
                    search
                    enter-button
                    placeholder="请输入请订单号"
                    v-model="formValidate.order_id"
                    style="width: 90%"
                    @on-search="userSearchs"
                  ></el-input>
                </el-form-item>
              </el-col>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <el-table
        ref="selection"
        :data="tabList"
        :loading="loading"
        empty-text="暂无数据"
        highlight-current-row
        max-height="400"
      >
        <template v-if="listTitle === 'man'">
          <el-table-column label="UID" width="80">
            <template slot-scope="scope">
              <span>{{ scope.row.uid }}</span>
            </template>
          </el-table-column>
          <el-table-column label="头像" min-width="90">
            <template slot-scope="scope">
              <div class="tabBox_img" v-viewer>
                <img v-lazy="scope.row.avatar ? scope.row.avatar : require('../../../assets/images/moren.jpg')" />
              </div>
            </template>
          </el-table-column>
          <el-table-column label="用户信息" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.nickname }}</span>
            </template>
          </el-table-column>
          <el-table-column label="是否推广员" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.promoter_name }}</span>
            </template>
          </el-table-column>
          <el-table-column label="推广人数" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.spread_count }}</span>
            </template>
          </el-table-column>
          <el-table-column label="订单数" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.order_count }}</span>
            </template>
          </el-table-column>
          <el-table-column label="绑定时间" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.spread_time | formatDate }}</span>
            </template>
          </el-table-column>
        </template>
        <template v-else>
          <el-table-column label="订单ID" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.order_id }}</span>
            </template>
          </el-table-column>
          <el-table-column label="用户信息" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.user_info }}</span>
            </template>
          </el-table-column>
          <el-table-column label="时间" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row._add_time }}</span>
            </template>
          </el-table-column>
          <el-table-column label="返佣金额" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.brokerage_price || 0 }}</span>
            </template>
          </el-table-column>
        </template>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="pageChange"
        />
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { stairListApi } from '@/api/agent';
import { formatDate } from '@/utils/validate';
export default {
  name: 'promotersList',
  // props: {
  //     listTitle: {
  //         type: String,
  //         default: ''
  //     }
  // },
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
        fromTxt2: [
          { text: '全部', val: '' },
          { text: '一级推广人', val: 1 },
          { text: '二级推广人', val: 2 },
        ],
        fromTxt3: [
          { text: '全部', val: '' },
          { text: '一级推广人订单', val: 1 },
          { text: '二级推广人订单', val: 2 },
        ],
      },
      formValidate: {
        limit: 15,
        page: 1,
        nickname: '',
        data: '',
        type: '',
        order_id: '',
        uid: 0,
      },
      loading: false,
      tabList: [],
      total: 0,
      timeVal: [],
      columns4: [],
      listTitle: '',
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '100px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  methods: {
    onCancel() {
      this.formValidate = {
        limit: 7,
        page: 1,
        nickname: '',
        data: '',
        type: '',
        order_id: '',
        uid: 0,
      };
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
      this.getList(this.rowsList, this.listTitle);
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList(this.rowsList, this.listTitle);
    },
    // 列表
    getList(row, tit) {
      this.listTitle = tit;
      this.rowsList = row;
      this.loading = true;
      let url = '';
      if (this.listTitle === 'man') {
        url = 'agent/stair';
      } else {
        url = 'agent/stair/order';
      }
      this.formValidate.uid = row.uid;
      stairListApi(url, this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.tabList = [];
          this.$message.error(res.msg);
        });
    },
    pageChange() {
      this.getList(this.rowsList, this.listTitle);
    },
    // 搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList(this.rowsList, this.listTitle);
    },
  },
};
</script>

<style scoped></style>
