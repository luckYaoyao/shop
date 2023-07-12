<template>
  <div @resize="handleResize">
    <el-row :gutter="24">
      <el-col san="24" class="ivu-mb">
        <el-card :bordered="false" shadow="never" class="dashboard-console-visit">
          <div slot="title">
            <el-row justify="center" align="middle">
              <el-col :span="8">
                <el-avatar
                  icon="el-icon-s-marketing"
                  size="small"
                  style="color: #1890ff; background-color: #e6f7ff"
                ></el-avatar>
                <span class="ivu-pl-8">订单</span>
              </el-col>
              <el-col :span="16" class="ivu-text-right">
                <el-radio-group v-model="visitDate" type="button" class="ivu-mr-8" @change="handleChangeVisitType">
                  <el-radio label="thirtyday">30天</el-radio>
                  <el-radio label="week">周</el-radio>
                  <el-radio label="month">月</el-radio>
                  <el-radio label="year">年</el-radio>
                </el-radio-group>
              </el-col>
            </el-row>
          </div>
          <h4>订单量趋势</h4>
          <echarts-from
            ref="visitChart"
            :series="series"
            :infoList="infoList"
            v-if="infoList"
            :yAxisData="yAxisData"
          ></echarts-from>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import { orderApi } from '@/api/index';
import echartsFrom from '@/components/echarts/index';
export default {
  components: { echartsFrom },
  data() {
    return {
      infoList: null,
      visitDate: 'thirtyday',
      series: [],
      yAxisData: [],
    };
  },
  methods: {
    // 统计
    getStatistics() {
      let data = {
        cycle: this.visitDate,
      };
      orderApi(data)
        .then(async (res) => {
          this.infoList = res.data || {};
          (this.series = this.infoList.series || []),
            (this.yAxisData = [
              {
                type: 'value',
                name: '金额',
                axisLine: {
                  show: false,
                },
                axisTick: {
                  show: false,
                },
                axisLabel: {
                  textStyle: {
                    color: '#7F8B9C',
                  },
                },
                splitLine: {
                  show: true,
                  lineStyle: {
                    color: '#F5F7F9',
                  },
                },
              },
              {
                type: 'value',
                name: '数量',
                axisLine: {
                  show: false,
                },
                axisTick: {
                  show: false,
                },
                axisLabel: {
                  textStyle: {
                    color: '#7F8B9C',
                  },
                },
                splitLine: {
                  show: true,
                  lineStyle: {
                    color: '#F5F7F9',
                  },
                },
                // axisLabel: {
                //     formatter: '{value} °C'
                // }
              },
            ]);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 时间改变
    handleChangeVisitType() {
      this.getStatistics();
    },
    // 监听页面宽度变化，刷新表格
    handleResize() {
      if (this.infoList) this.$refs.visitChart.handleResize();
    },
  },
  created() {
    this.getStatistics();
  },
};
</script>
<style lang="less" scoped>
.dashboard-console-visit {
  ul {
    li {
      list-style-type: none;
      margin-top: 12px;
    }
  }
}
.ivu-text-right {
  text-align: right;
}
.ivu-pl-8 {
  padding-left: 8px !important;
}
</style>
