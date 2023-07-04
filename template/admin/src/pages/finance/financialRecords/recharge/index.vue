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
          <el-col :span="24" class="ivu-text-left">
            <el-form-item label="支付类型：">
              <el-radio-group v-model="formValidate.paid" type="button" @change="selChange">
                <el-radio-button label="">全部</el-radio-button>
                <el-radio-button label="1">已支付</el-radio-button>
                <el-radio-button label="0">未支付</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24" class="ivu-text-left">
            <el-form-item label="搜索：">
              <el-input
                search
                enter-button
                @on-search="selChange"
                placeholder="请输入用户昵称、订单号"
                element-id="name"
                v-model="formValidate.nickname"
                style="width: 30%; display: inline-table"
                class="mr"
              />
              <el-button v-auth="['export-userRecharge']" class="mr" icon="ios-share-outline" @click="exports"
                >导出</el-button
              >
              <!--                            <span class="Refresh">刷新</span><Icon type="ios-refresh" />-->
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </el-card>
    <cards-data :cardLists="cardLists" v-if="cardLists.length >= 0"></cards-data>
    <el-card :bordered="false" shadow="never">
      <div>充值记录列表</div>
      <el-table ref="table" :data="tabList" class="ivu-mt" :loading="loading" empty-text="暂无数据"
        ><el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="头像" min-width="90">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.avatar ? scope.row.avatar : require('../../../../assets/images/moren.jpg')" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="用户昵称" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="订单号" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.order_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="支付金额" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="是否支付" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.paid_type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="充值类型" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row._recharge_type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="支付时间" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row._pay_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="170">
          <template slot-scope="scope">
            <a
              href="javascript:void(0);"
              v-if="scope.row.refund_price <= 0 && scope.row.paid && scope.row.recharge_type != 'system'"
              @click="refund(scope.row)"
              >退款</a
            >
            <!--                    <el-divider direction="vertical"  v-if="scope.row.paid"/>-->
            <a href="javascript:void(0);" v-if="scope.row.paid === 0" @click="del(scope.row, '此条充值记录', index)"
              >删除</a
            >
            <span class="refund" v-if="scope.row.refund_price > 0">已退款</span>
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
    <!-- 退款表单-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
  </div>
</template>
<script>
import cardsData from '@/components/cards/cards';
import searchFrom from '@/components/publicSearchFrom';
import { mapState } from 'vuex';
import { rechargelistApi, userRechargeApi, refundEditApi, exportUserRechargeApi } from '@/api/finance';
import editFrom from '@/components/from/from';
export default {
  name: 'recharge',
  components: { cardsData, searchFrom, editFrom },
  data() {
    return {
      FromData: null,
      formValidate: {
        data: '',
        paid: '',
        nickname: '',
        excel: 0,
        page: 1,
        limit: 20,
      },
      formValidate2: {
        data: '',
        paid: '',
        nickname: '',
      },
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
          { text: '最近7天', val: 'lately7' },
          { text: '最近30天', val: 'lately30' },
          { text: '本月', val: 'month' },
          { text: '本年', val: 'year' },
        ],
      },
      timeVal: [],
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '85px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.getList();
    this.getUserRecharge();
  },
  methods: {
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `finance/recharge/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$Message.success(res.msg);
          this.tabList.splice(delfromData.num, 1);
          this.getList();
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 退款
    refund(row) {
      refundEditApi(row.id)
        .then(async (res) => {
          if (res.data.status === false) {
            return this.$authLapse(res.data);
          }
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 编辑提交成功
    submitFail() {
      this.getList();
      this.getUserRecharge();
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal.join('-');
      this.formValidate.page = 1;
      this.getList();
      this.getUserRecharge();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.data = tab;
      this.timeVal = [];
      this.formValidate.page = 1;
      this.getList();
      this.getUserRecharge();
    },
    // 选择
    selChange(x) {
      this.formValidate.page = 1;
      this.getList();
      this.getUserRecharge();
    },
    // 列表
    getList() {
      this.loading = true;
      rechargelistApi(this.formValidate)
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
    // 小方块
    getUserRecharge() {
      userRechargeApi({
        data: this.formValidate.data,
        paid: this.formValidate.paid,
        nickname: this.formValidate.nickname,
      })
        .then(async (res) => {
          let data = res.data;
          this.cardLists = data;
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 导出
    exports() {
      let formValidate = this.formValidate;
      let data = {
        data: formValidate.data,
        paid: formValidate.paid,
        nickname: formValidate.nickname,
      };
      exportUserRechargeApi(data)
        .then((res) => {
          location.href = res.data[0];
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
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
</style>
