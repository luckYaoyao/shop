<template>
  <div class="upgrade">
    <div class="i-layout-page-header">
      <div class="i-layout-page-header">
        <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
      </div>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <div class="header">
        <div>当前版本<span class="v">V</span><span class="num">1.5</span></div>
        <div class="info">更新说明：已升级至最新版本，无需更新</div>
      </div>
      <div class="contentTime">
        <div class="acea-row row-top on">
        </div>
        <div class="acea-row row-top">
          <Timeline class="list">
          </Timeline>
        </div>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: 'systemUpgradeclient',
  data() {
    return {
      Panel: '1',
      // grid2: {
      //     xl: 10,
      //     lg: 12,
      //     md: 12,
      //     sm: 20,
      //     xs: 24
      // }
    };
  },
};
</script>

<style lang="stylus">
.primary{
  position absolute
  right 0
  bottom  0
}
.upgrade .header{
  font-size:12px;
  color: #000;
  border-bottom:1px dotted rgba(221,221,221,1);
  padding-bottom: 25px;
}
.upgrade .header .v{
  color: #1890FF;
  margin-left: 10px;
}
.upgrade .header .num{
  color: #1890FF;
  font-size: 24px;
}
.upgrade .header .info{
  color: #999999;
}
.upgrade .contentTime .list{
  width 85%;
}
.upgrade .contentTime .info{
  font-size :12px!important;
  color :#999!important;
  margin-top :13px;
}
.upgrade .contentTime .collapse{
  width 100%;
}
.upgrade .contentTime .ivu-collapse{
  border :0!important;
}
.upgrade .contentTime .ivu-collapse > .ivu-collapse-item > .ivu-collapse-header{
  height: unset!important;
  line-height: unset!important;
  border :0!important;
  font-size :16px!important;
  color :#333333;
  font-weight:600;
}
.upgrade .contentTime .ivu-collapse > .ivu-collapse-item > .ivu-collapse-header > i{
  color :#BBBBBB!important;
  margin-left :9px;
}
.upgrade .contentTime .ivu-collapse > .ivu-collapse-item.ivu-collapse-item-active > .ivu-collapse-header > i{
  transform: rotate(180deg);
}
.upgrade .contentTime{
  margin-top :30px;
}
.upgrade .contentTime .time{
  font-size: 14px;
  color: #999;
  text-align: right;
  padding-right: 28px;
}
.upgrade .contentTime .ivu-timeline-item:after{
  content: ' ';
  position: absolute;
  top:0;
  left:7px;
  width: 1px;
  height: 100%;
  background-color: #e8eaec;
}
.upgrade .contentTime .ivu-timeline-item-head-custom{
  z-index: 2;
  font-size 16px;
  color #DDDCDD;
}
.upgrade .contentTime .on .ivu-timeline-item-head-custom{
  color #1890FF;
  font-size 18px;
}
.upgrade .contentTime .on .ivu-collapse > .ivu-collapse-item > .ivu-collapse-header{
  color #1890FF;
}
</style>
