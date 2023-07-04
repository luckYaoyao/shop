<template>
  <div>
    <el-card :bordered="false" shadow="never">
      <el-tabs class="mb20" v-model="currentTab" @tab-click="onClickTab" v-if="tablists">
        <el-tab-pane v-for="(item, index) in tabs" :name="item.type" :key="index">
          <span slot="label">
            <el-badge :hidden="!item.value" :value="item.value" :max="999999">
              {{ item.label }}
            </el-badge>
          </span>
        </el-tab-pane>
      </el-tabs>
      <productlist-details
        v-if="currentTab === 'article' || 'project' || 'app'"
        ref="productlist"
      ></productlist-details>
      <Spin size="large" fix v-if="spinShow"></Spin>
    </el-card>
  </div>
</template>

<script>
import productlistDetails from './orderlistDetails';
import { mapMutations } from 'vuex';
export default {
  name: 'list',
  components: {
    productlistDetails,
  },
  data() {
    return {
      tabs: [
        {
          type: '',
          label: '全部订单',
          value: Number(this.tablists?.all) || 0,
          max: 999999,
        },
        {
          type: '1',
          label: '普通订单',
          value: Number(this.tablists?.general) || 0,
          max: 999999,
        },
        {
          type: '2',
          label: '拼团订单',
          value: Number(this.tablists?.pink) || 0,
          max: 999999,
        },
        {
          type: '3',
          label: '秒杀订单',
          value: Number(this.tablists?.seckill) || 0,
          max: 999999,
        },
        {
          type: '4',
          label: '砍价订单',
          value: Number(this.tablists?.bargain) || 0,
          max: 999999,
        },
        {
          type: '5',
          label: '预售订单',
          value: Number(this.tablists?.advance) || 0,
          max: 999999,
        },
      ],
      spinShow: false,
      currentTab: '',
      data: [],
      tablists: null,
    };
  },
  created() {
    this.getOrderType('');
    this.getOrderStatus('');
    this.getOrderTime('');
    this.getOrderNum('');
    this.getfieldKey('');
    this.onChangeTabs('');
    this.getisDelIdListl('');
    this.getIsDel(1);
  },
  beforeDestroy() {
    this.getOrderType('');
    this.getOrderStatus('');
    this.getOrderTime('');
    this.getOrderNum('');
    this.getfieldKey('');
    this.onChangeTabs('');
    this.getisDelIdListl('');
    this.getIsDel(1);
  },
  mounted() {
    this.getTabs();
  },
  methods: {
    ...mapMutations('order', [
      'onChangeTabs',
      'getOrderStatus',
      'getOrderTime',
      'getOrderNum',
      'getfieldKey',
      'getOrderType',
      'getisDelIdListl',
      'getIsDel',
      // 'onChangeChart'
    ]),
    // 订单类型  @on-changeTabs="getChangeTabs"
    getTabs() {
      this.spinShow = true;
      this.$store
        .dispatch('order/getOrderTabs', {
          data: '',
        })
        .then((res) => {
          this.tablists = res.data;
          // this.onChangeChart(this.tablists)
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$Message.error(res.msg);
        });
    },
    onClickTab() {
      this.onChangeTabs(Number(this.currentTab));
      // this.$store.dispatch("order/getOrderTabs", {
      //   data: "",
      //   type: Number(this.currentTab),
      // });
      // this.$refs.productlist.getChangeTabs();
      this.$store.dispatch('order/getOrderTabs', { type: this.currentTab });
    },
  },
};
</script>
<style scoped lang="stylus">
.product_tabs >>> .ivu-tabs-bar {
  margin-bottom: 0px !important;
}

.product_tabs >>> .ivu-page-header-content {
  margin-bottom: 0px !important;
}

.product_tabs >>> .ivu-page-header-breadcrumb {
  margin-bottom: 0px !important;
}

/deep/ .el-badge__content.is-fixed {
  top: 7px;
}

</style>
