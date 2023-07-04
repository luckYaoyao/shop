<template>
  <Modal v-model="modals" scrollable title="订单记录" width="700" class="order_box" footer-hide>
    <el-card :bordered="false" shadow="never">
      <el-table
        :columns="columns"
        :data="recordData"
        v-loading="loading"
        empty-text="暂无数据"
        highlight-current-row
        
      >
        <el-table-column label="订单ID" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.oid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作记录" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.change_message }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作时间" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.change_time }}</span>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </Modal>
</template>

<script>
import { getIntegralOrderRecord } from '@/api/marketing';
export default {
  name: 'orderRecord',
  data() {
    return {
      modals: false,
      loading: false,
      recordData: [],
      page: {
        page: 1, // 当前页
        limit: 10, // 每页显示条数
      },
      columns: [
        {
          title: '订单ID',
          key: 'oid',
          align: 'center',
          minWidth: 40,
        },
        {
          title: '操作记录',
          key: 'change_message',
          align: 'center',
          minWidth: 280,
        },
        {
          title: '操作时间',
          key: 'change_time',
          align: 'center',
          minWidth: 100,
        },
      ],
    };
  },
  methods: {
    pageChange(index) {
      this.page.pageNum = index;
      this.getList();
    },
    getList(id) {
      let data = {
        id: id,
        datas: this.page,
      };
      this.loading = true;
      getIntegralOrderRecord(data)
        .then(async (res) => {
          this.recordData = res.data;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
  },
};
</script>

<style scoped lang="stylus">
.ivu-table-wrapper
    border-left: 1px solid #dcdee2;
    border-top: 1px solid #dcdee2;
 .order_box >>> .ivu-table th{background: #f8f8f9 !important;}
</style>
