<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="tableFrom"
        :model="tableFrom"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col v-bind="grid">
            <el-form-item label="是否有效：">
              <el-select placeholder="请选择" clearable v-model="tableFrom.status" @change="userSearchs">
                <el-option value="1" label="已使用"></el-option>
                <el-option value="0" label="未使用"></el-option>
                <el-option value="2" label="已过期"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="领取人：" label-for="nickname">
              <el-input placeholder="请输入领取人" v-model="tableFrom.nickname" clearable />
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="优惠券搜索：" label-for="coupon_title">
              <el-input
                search
                enter-button
                placeholder="请输入优惠券名称"
                v-model="tableFrom.coupon_title"
                @on-search="userSearchs"
              />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <el-table :data="tableList">
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="优惠券名称" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.coupon_title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="领取人" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="面值" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.coupon_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="最低消费额" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.use_min_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="开始使用时间" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.start_time | formatDate }}</span>
          </template>
        </el-table-column>
        <el-table-column label="结束使用时间" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.end_time | formatDate }}</span>
          </template>
        </el-table-column>
        <el-table-column label="获取方式" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="是否可用" min-width="150">
          <template slot-scope="scope">
            <Icon type="md-checkmark" v-if="scope.row.is_fail === 0" color="#0092DC" size="14" />
            <Icon type="md-close" v-else color="#ed5565" size="14" />
          </template>
        </el-table-column>
        <el-table-column label="状态" min-width="150">
          <template slot-scope="scope">
            <span>{{ scope.row.status }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="tableFrom.page"
          :limit.sync="tableFrom.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { userListApi } from '@/api/marketing';
import { formatDate } from '@/utils/validate';
export default {
  name: 'storeCouponUser',
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
      tableList: [],
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      tableFrom: {
        status: '',
        coupon_title: '',
        nickname: '',
        page: 1,
        limit: 15,
      },
      total: 0,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '90px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getList();
  },
  methods: {
    // 列表
    getList() {
      this.loading = true;
      this.tableFrom.status = this.tableFrom.status || '';
      userListApi(this.tableFrom)
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
      this.tableFrom.page = 1;
      this.getList();
    },
  },
};
</script>

<style scoped></style>
