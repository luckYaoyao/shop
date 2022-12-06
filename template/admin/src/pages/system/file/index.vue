<template>
  <div>
    <Card :bordered="false" dis-hover class="ivu-mt">
      <div class="box" ref="picBox">
        <upload-file v-if="uploadShow" :isShow="0" :pageLimit="pageLimit"></upload-file>
      </div>
    </Card>
  </div>
</template>
<script>
import uploadFile from '@/components/uploadPictures/index';
export default {
  components: { uploadFile },
  name: 'system_file',
  data() {
    return {
      pageLimit: 30,
      uploadShow: false,
    };
  },
  mounted() {
    console.log(this.$refs.picBox.clientWidth);

    this.$nextTick(() => {
      let winwidth = this.$refs.picBox.clientWidth;
      console.log(winwidth);
      if (winwidth < 1018) {
        this.pageLimit = 18;
      } else if (winwidth < 1185) {
        this.pageLimit = 18;
      } else if (winwidth < 1222) {
        this.pageLimit = 24;
      } else if (winwidth < 1327) {
        this.pageLimit = 32;
      } else if (winwidth > 1483) {
        this.pageLimit = 40;
      }
      this.uploadShow = true;
    });
  },
  methods: {},
};
</script>
<style scoped lang="stylus">
.box {
  width: 100%;
  height 100%
  background: #fff;
}
/deep/ .ivu-card-body{
  min-height: 700px;
}
</style>
