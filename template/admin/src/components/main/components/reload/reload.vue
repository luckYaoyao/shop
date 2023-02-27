<template>
  <div class="refresh-btn-con" @click="handleReload">
    <Tooltip content="刷新" placement="bottom">
      <Icon class="icon" type="ios-refresh" color="rgba(255,255,255,.8)" />
    </Tooltip>
  </div>
</template>

<script>
import { R } from '@/libs/util';

export default {
  name: 'reload',
  mounted() {},
  methods: {
    handleReload() {
      this.$emit('on-reload');
    },
  },
};
</script>
<style scoped lang="less">
.refresh-btn-con {
  margin-right: 17px !important;
  cursor: pointer;
  font-size: 28px;
  color: #f2f2f2;
}
// .icon:hover{
//   animation: rotate 6s linear infinite;
// }
// @keyframes rotate {
//     0%{-webkit-transform:rotate(0deg);}
//     100%{-webkit-transform:rotate(360deg);}
// }
</style>
