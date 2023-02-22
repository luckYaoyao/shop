<template>
  <div class="full-screen-btn-con" @click="handleReload">
      <Icon class="icon" type="md-refresh" color='#fff' />
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
.full-screen-btn-con {
  margin-right: 17px !important;
  cursor: pointer;
  font-size: 20px;
}
.icon:hover{
  animation: rotate 6s linear infinite; 
}
@keyframes rotate {
    0%{-webkit-transform:rotate(0deg);}
    100%{-webkit-transform:rotate(360deg);}
}
</style>