<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form ref="formValidate" :model="formValidate" label-width="85px" @submit.native.prevent>
        <el-row :gutter="24">
          <el-col :span="24" class="ivu-text-left">
            <el-form-item label="核销日期：">
              <el-radio-group
                v-model="formValidate.data"
                type="button"
                class="mr"
                @change="selectChange(formValidate.data)"
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
          <el-col :span="12" class="ivu-text-left">
            <el-form-item label="筛选条件：">
              <el-input enter-button placeholder="请输入搜索内容" v-model="formValidate.real_name" style="width: 430px">
                <el-select v-model="field_key" slot="prepend" style="width: 80px">
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
          <el-col :span="12" class="mr">
            <el-form-item label="选择门店：" label-for="store_name">
              <el-select
                v-model="formValidate.store_id"
                element-id="store_id"
                clearable
                @change="userSearchs"
                style="width: 430px"
              >
                <el-option
                  v-for="item in storeSelectList"
                  :value="item.id"
                  :key="item.id"
                  :label="item.name"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12" class="mr">
            <el-form-item label="" label-for="store_name">
              <el-button type="primary" class="mr15" @click="userSearchs">搜索</el-button>
              <el-button class="mr15" @click="refresh">刷新</el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :data="orderList"
        ref="table"
        :loading="loading"
        highlight-current-row
        empty-text="暂无数据"
        class="orderData mt25"
      >
        <el-table-column label="订单号" min-width="180">
          <template slot-scope="scope">
            <span>{{ scope.row.order_id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="用户信息" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}/{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="推荐人信息" min-width="120">
          <template slot-scope="scope">
            <a href="javascript:void(0);" @click="referenceInfo(scope.row.spread_uid)">{{
              scope.row.spread_nickname
            }}</a>
          </template>
        </el-table-column>
        <el-table-column label="商品信息" min-width="300">
          <template slot-scope="scope">
            <div class="tabBox" v-for="(val, i) in scope.row._info" :key="i">
              <div class="tabBox_img">
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
        <el-table-column label="实际支付" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="核销员" min-width="90">
          <template slot-scope="scope">
            <span>{{ scope.row.clerk_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="核销门店" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.store_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="支付状态" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.pay_type_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="订单状态" min-width="80">
          <template slot-scope="scope">
            <span> {{ scope.row.status_name.status_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="下单时间" min-width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
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
    <referrer-info ref="info"></referrer-info>
  </div>
</template>

<script>
import { verifyOrderApi, merchantStoreListApi } from '@/api/setting';
import cardsData from '@/components/cards/cards';
import referrerInfo from '@/components/referrerInfo/index';
export default {
  name: 'setting_order',
  components: { cardsData, referrerInfo },
  data() {
    return {
      formValidate: {
        page: 1,
        limit: 15,
        data: '',
        real_name: '',
        store_id: '',
        field_key: '',
      },
      field_key: '',
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
      storeSelectList: [],

      orderList: [],
      loading: false,
      total: 0,
    };
  },
  mounted() {
    this.getList();
    this.storeList();
  },
  methods: {
    getList() {
      let that = this;
      that.loading = true;
      that.formValidate.field_key = this.field_key === 'all' ? '' : this.field_key;
      verifyOrderApi(that.formValidate)
        .then((res) => {
          that.loading = false;
          that.orderList = res.data.data;
          that.total = res.data.count;
        })
        .catch((res) => {
          that.$Message.error(res.msg);
        });
    },
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal.join('-');
      this.getList();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.page = 1;
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList();
    },
    storeList() {
      let that = this;
      merchantStoreListApi()
        .then((res) => {
          that.storeSelectList = res.data;
        })
        .catch((res) => {
          that.$Message.error(res.msg);
        });
    },
    referenceInfo(uid) {
      this.$refs.info.isTemplate = true;
      this.$refs.info.verifySpreadInfo(uid);
    },
    refresh() {
      this.formValidate = {
        page: 1,
        limit: 15,
        data: '',
        real_name: '',
        store_id: '',
        field_key: '',
      };
      this.field_key = '';
      this.getList();
    },
  },
};
</script>

<style scoped lang="stylus">
img {
    height: 36px;
    display: block;
}
.tabBox
    width 100%
    height 100%
    display flex
    align-items: center
    .tabBox_img
        width 36px
        height 36px
        img
            width 100%
            height 100%
    .tabBox_tit
        width 60%
        font-size 12px !important
        margin 0 2px 0 10px
        letter-spacing: 1px;
        padding: 5px 0;
        box-sizing: border-box;
.orderData >>>.ivu-table-cell{padding-left: 0 !important;}
.vertical-center-modal{
    display: flex;
    align-items: center;
    justify-content: center;}
.ivu-mt{
  margin-bottom 12px
}
.ivu-mt a
   color #515a6e
.ivu-mt a:hover
    color: #2D8cF0;
.ivu-mt /deep/ .ivu-form-item{
  padding 7px 0;
  margin-bottom 0
}
</style>
