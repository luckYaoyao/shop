<template>
  <div class="sider-menu" @click="handleChange">
    <a type="text" :class="['sider-trigger-a', collapsed ? 'collapsed' : '']"
      ><i class="iconfont" color="#fff" :class="collapsed ? 'iconcaidanzhankai' : 'iconcaidanshouqi'"></i
    ></a>
  </div>
</template>
<script>
export default {
  name: 'siderTrigger',
  props: {
    collapsed: Boolean,
    icon: {
      type: String,
      default: 'navicon-round',
    },
    size: {
      type: Number,
      default: 18,
    },
  },
  methods: {
    handleChange() {
      this.$emit('on-change', !this.collapsed);
    },
  },
};
</script>
<style lang="less" scoped>
.sider-menu {
  position: absolute;
  width: 100%;
  max-width: 90px;
  left: 0;
  bottom: 0;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-top: 1px solid #f2f2f2;
  color: #515a6e;
}
@import './sider-trigger.less';
.sider-trigger-a {
  padding: 14px 6px;
}
.iconcaidanshouqi {
  font-size: 16px;
}
</style>
