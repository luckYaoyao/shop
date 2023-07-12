<template>
  <el-row :gutter="24">
    <el-col v-bind="grid" class="ivu-mb" v-for="(item, index) in infoList" :key="index">
      <el-card shadow="never" :padding="12">
        <p slot="header">
          <span v-text="item.title"></span>
          <el-tag style="float: right;" type="success">{{ item.date }}</el-tag>
        </p>
        <div>
          <div class="number">{{ item.today }}</div>
          <div class="ivu-pt-8" style="height: 42px">
            <span>昨日 {{ item.yesterday }}</span>
            <span class="ivu-mr">
              日环比 {{ Number(item.today_ratio) }}%
              <Icon
                :type="Number(item.today_ratio) >= 0 ? 'md-arrow-dropup' : 'md-arrow-dropdown'"
                class="iconColor"
                :class="Number(item.today_ratio) >= 0 ? ' ' : 'on'"
              />
            </span>
          </div>
          <el-divider style="margin: 8px 0" />
          <div>
            <el-row>
              <el-col :span="12" v-text="item.total_name"></el-col>
              <el-col :span="12" class="ivu-text-right">{{ item.total }}</el-col>
            </el-row>
          </div>
        </div>
      </el-card>
    </el-col>
  </el-row>
</template>
<script>
import { headerApi } from '@/api/index';
export default {
  data() {
    return {
      infoList: [],
      grid: {
        xl: 6,
        lg: 6,
        md: 12,
        sm: 12,
        xs: 12,
      },
      excessStyle: {
        color: '#f56a00',
        backgroundColor: '#fde3cf',
      },
      avatarList: [],
    };
  },
  methods: {
    // 统计
    getStatistics() {
      headerApi()
        .then(async (res) => {
          let data = res.data;
          this.infoList = data.info;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
  mounted() {
    this.getStatistics();
  },
};
</script>
<style lang="stylus">
.number {
  font-size: 30px;
  margin-bottom: 10px;
}

.iconColor {
  color: #ed4014;
}

.iconColor.on {
  color: #19be6b;
}

.ivu-mr {
  display: inline-block;
  margin-left: 16px !important;
}

.ivu-text-right {
  text-align: right;
}
</style>
