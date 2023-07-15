<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mb-16">
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
                format="yyyy/MM/dd"
                type="daterange"
                value-format="yyyy/MM/dd"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                style="width: 200px"
              ></el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="提现状态：">
              <el-radio-group type="button" v-model="formValidate.status" class="mr15" @change="selChange">
                <el-radio-button :label="itemn.value" v-for="(itemn, indexn) in treeData.withdrawal" :key="indexn">{{
                  itemn.title
                }}</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="提现方式：">
              <el-radio-group type="button" v-model="formValidate.extract_type" class="mr15" @change="selChange">
                <el-radio-button :label="itemn.value" v-for="(itemn, indexn) in treeData.payment" :key="indexn">{{
                  itemn.title
                }}</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="搜索：">
              <div class="acea-row row-middle">
                <el-input
                  search
                  enter-button
                  @on-search="selChange"
                  placeholder="微信昵称/姓名/支付宝账号/银行卡号"
                  element-id="name"
                  v-model="formValidate.nireid"
                  style="width: 30%"
                />
                <router-link :to="$routeProStr + '/finance/finance/commission'" class="ml20">佣金记录</router-link>
              </div>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </el-card>
    <cards-data :cardLists="cardLists" v-if="extractStatistics"></cards-data>
    <el-card :bordered="false" shadow="never">
      <el-table ref="table" :data="tabList" class="ivu-mt" :loading="loading" empty-text="暂无数据">
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="用户信息" min-width="130">
          <template slot-scope="scope">
            <div>
              用户昵称: {{ scope.row.nickname }} <br />
              用户id:{{ scope.row.uid }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="提现金额" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.extract_price }}</div>
          </template>
        </el-table-column>
        <el-table-column label="提现手续费" min-width="100">
          <template slot-scope="scope">
            <div>{{ scope.row.extract_fee }}</div>
          </template>
        </el-table-column>
        <el-table-column label="提现方式" min-width="130">
          <template slot-scope="scope">
            <div class="type" v-if="scope.row.extract_type === 'bank'">
              <div class="item">姓名:{{ scope.row.real_name }}</div>
              <div class="item">银行卡号:{{ scope.row.bank_code }}</div>
              <div class="item">银行开户地址:{{ scope.row.bank_address }}</div>
            </div>
            <div class="type" v-if="scope.row.extract_type === 'weixin'">
              <div class="item">昵称:{{ scope.row.nickname }}</div>
              <div class="item">微信号:{{ scope.row.wechat }}</div>
            </div>
            <div class="type" v-if="scope.row.extract_type === 'alipay'">
              <div class="item">姓名:{{ scope.row.real_name }}</div>
              <div class="item">支付宝号:{{ scope.row.alipay_code }}</div>
            </div>
            <div class="type" v-if="scope.row.extract_type === 'balance'">
              <div class="item">姓名:{{ scope.row.real_name }}</div>
              <div class="item">提现方式：佣金转入余额</div>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="收款码" min-width="90">
          <template slot-scope="scope">
            <div
              class="tabBox_img"
              v-viewer
              v-if="scope.row.extract_type === 'weixin' || scope.row.extract_type === 'alipay'"
            >
              <img v-lazy="scope.row.qrcode_url" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="添加时间" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time | formatDate }}</span>
          </template>
        </el-table-column>
        <el-table-column label="备注" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.mark }}</span>
          </template>
        </el-table-column>
        <el-table-column label="审核状态" min-width="130">
          <template slot-scope="scope">
            <div class="status" v-if="scope.row.status === 0">
              <div class="statusVal">申请中</div>
              <div>
                <el-button type="error" icon="md-close" size="small" class="item" @click="invalid(scope.row)"
                  >驳回</el-button
                >
                <el-button
                  type="info"
                  icon="md-checkmark"
                  size="small"
                  class="item"
                  @click="adopt(scope.row, '审核通过', index)"
                  >通过</el-button
                >
              </div>
            </div>
            <div class="statusVal" v-if="scope.row.status === 1">提现通过</div>
            <div class="statusVal" v-if="scope.row.status === -1">
              提现未通过<br />未通过原因：{{ scope.row.fail_msg }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="170">
          <template slot-scope="scope">
            <a href="javascript:void(0);" @click="edit(scope.row)">编辑</a>
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

    <!-- 编辑表单-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
    <!-- 拒绝通过-->
    <el-dialog :visible.sync="modals" title="未通过原因" :close-on-click-modal="false">
      <el-input v-model="fail_msg.message" type="textarea" :rows="4" placeholder="请输入未通过原因" />
      <div slot="footer">
        <el-button type="primary" size="large" long :loading="modal_loading" @click="oks">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import cardsData from '@/components/cards/cards';
import searchFrom from '@/components/publicSearchFrom';
import { mapState } from 'vuex';
import { cashListApi, cashEditApi, refuseApi } from '@/api/finance';
import { formatDate } from '@/utils/validate';
import editFrom from '@/components/from/from';
export default {
  name: 'cashApply',
  components: { cardsData, searchFrom, editFrom },
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
      fail_msg: {
        message: '输入信息不完整或有误!',
      },
      modals: false,
      total: 0,
      cardLists: [],
      loading: false,
      tabList: [],
      fromList: {
        title: '选择时间',
        custom: true,
        fromTxt: [
          { text: '全部', val: '' },
          { text: '今天', val: 'today' },
          { text: '昨天', val: 'yesterday' },
          { text: '本周', val: 'week' },
          { text: '本月', val: 'month' },
          { text: '本季度', val: 'quarter' },
          { text: '本年', val: 'year' },
        ],
      },
      treeData: {
        withdrawal: [
          {
            title: '全部',
            value: '',
          },
          {
            title: '未通过',
            value: -1,
          },
          {
            title: '申请中',
            value: 0,
          },
          {
            title: '已通过',
            value: 1,
          },
        ],
        payment: [
          {
            title: '全部',
            value: '',
          },
          {
            title: '微信',
            value: 'wx',
          },
          {
            title: '支付宝',
            value: 'alipay',
          },
          {
            title: '银行卡',
            value: 'bank',
          },
        ],
      },
      formValidate: {
        status: '',
        extract_type: '',
        nireid: '',
        data: '',
        page: 1,
        limit: 20,
      },
      extractStatistics: {},
      timeVal: [],
      FromData: null,
      extractId: 0,
    };
  },
  watch: {
    $route() {
      if (this.$route.fullPath === this.$routeProStr + '/finance/user_extract/index?status=0') {
        this.getPath();
      }
    },
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
    if (this.$route.fullPath === this.$routeProStr + '/finance/user_extract/index?status=0') {
      this.getPath();
    } else {
      this.getList();
    }
  },
  methods: {
    getPath() {
      this.formValidate.page = 1;
      this.formValidate.status = parseInt(this.$route.query.status);
      this.getList();
    },
    // 无效
    invalid(row) {
      this.extractId = row.id;
      this.modals = true;
    },
    // 确定
    oks() {
      this.modal_loading = true;
      refuseApi(this.extractId, this.fail_msg)
        .then(async (res) => {
          this.$message.success(res.msg);
          this.modal_loading = false;
          this.modals = false;
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 通过
    adopt(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `finance/extract/adopt/${row.id}`,
        method: 'put',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.getList();
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal ? this.timeVal.join('-') : '';
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
    // 选择
    selChange() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      cashListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list.list;
          this.total = data.list.count;
          this.extractStatistics = data.extract_statistics;
          this.cardLists = [
            { col: 6, count: this.extractStatistics.brokerage_count, name: '佣金总金额', className: 'md-pricetags' },
            { col: 6, count: this.extractStatistics.price, name: '待提现金额', className: 'md-basket' },
            { col: 6, count: this.extractStatistics.priced, name: '已提现金额', className: 'md-cash' },
            { col: 6, count: this.extractStatistics.brokerage_not, name: '未提现金额', className: 'ios-cash' },
          ];
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 编辑
    edit(row) {
      cashEditApi(row.id)
        .then(async (res) => {
          if (res.data.status === false) {
            return this.$authLapse(res.data);
          }
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
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
.ivu-mt .type .item
    margin:3px 0;
.tabform
    margin-bottom 10px
.Refresh
    font-size 12px
    color #1890FF
    cursor pointer
.ivu-form-item
    margin-bottom 10px
.status >>> .item~.item
    margin-left 6px
.status >>> .statusVal
    margin-bottom 7px
/*.ivu-mt >>> .ivu-table-header*/
/*    border-top:1px dashed #ddd!important*/
.type
   padding 3px 0
   box-sizing border-box
.tabBox_img
    width 36px
    height 36px
    border-radius:4px
    cursor pointer
    img
        width 100%
        height 100%
</style>
