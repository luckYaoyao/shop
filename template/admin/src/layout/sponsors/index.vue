<template></template>

<script>
export default {
  name: 'layoutSponsors',
  data() {},
  computed: {},
  methods: {},
  mounted() {
    this.delayShow();
  },
};
</script>

<style scoped lang="scss">
.sponsors-container {
  position: fixed;
  right: 15px;
  bottom: 15px;
  z-index: 3;
  width: 200px;
  background-color: var(--prev-bg-main-color);
  box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.12);
  border-radius: 5px;
  overflow: hidden;
  cursor: pointer;
  .sponsors-img {
    width: 100%;
    height: 80px;
  }
  .sponsors-text {
    padding: 10px;
    color: var(--prev-color-text-regular);
    font-size: 14px;
  }
  .sponsors-close {
    width: 60px;
    height: 60px;
    border-radius: 100%;
    background: rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    position: absolute;
    right: -35px;
    bottom: -35px;
    z-index: 5;
    i {
      position: absolute;
      left: 9px;
      top: 9px;
      color: #afafaf;
      transition: all 0.3s ease;
    }
    &:hover {
      transition: all 0.3s ease;
      i {
        color: var(--prev-color-primary);
        transition: all 0.3s ease;
      }
    }
  }
}
</style>
