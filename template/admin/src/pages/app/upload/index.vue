<template>
  <div class="main">
    <div v-if="uploading">
      <div class="img-list">
        <el-upload
          ref="upload"
          :action="fileUrl"
          list-type="picture-card"
          :on-change="fileChange"
          :file-list="imgList"
          :auto-upload="false"
        >
          <i slot="default" class="el-icon-plus"></i>
          <div slot="file" slot-scope="{ file }">
            <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" />
            <i class="el-icon-error btndel" @click="handleRemove(file)" />
          </div>
        </el-upload>
      </div>

      <div class="footer">
        <div>共{{ imgList.length }}张，{{ (allSize / 1000000).toFixed(2) }} M</div>
        <div class="upload-btn">
          <!-- <div class="btn">选择图片</div> -->
          <div class="btn upload" @click="submitUpload">确认上传</div>
        </div>
      </div>
    </div>
    <div v-else class="upload-success">
      <div class="success">
        <img class="image" src="@/assets/images/success.jpg" alt="" />
      </div>
      <div class="text">图片上传成功</div>
      <div class="again" @click="again">继续上传</div>
    </div>
  </div>
</template>

<script>
import Setting from '@/setting';
import { scanUpload } from '@/api/setting';
import compressImg from '@/utils/compressImg.js';
export default {
  name: 'app_upload_file',
  data() {
    return {
      fileUrl: Setting.apiBaseURL + '/image/scan_upload',
      imgList: [],
      allSize: 0,
      token: '',
      uploading: true,
    };
  },
  created() {
    this.token = this.$route.query.token;
    document.title = '手机端扫码上传';
  },
  methods: {
    again() {
      this.uploading = true;
      this.imgList = [];
      this.allSize = 0;
    },
    async submitUpload() {
      for (let i = 0; i < this.imgList.length; i++) {
        const file = this.imgList[i].raw;
        await this.uploadItem(file);
        if (i == this.imgList.length - 1) {
          this.uploading = false;
        }
      }
    },
    handleRemove(file) {
      console.log(file);
      let index = this.imgList.findIndex((e) => {
        e.url == file.url;
      });
      this.imgList.splice(index, 1);
      this.$nextTick((e) => {
        this.imgList.map((e) => {
          this.allSize += e.raw.size;
        });
      });
    },

    uploadItem(file) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('uploadToken', this.token);
        scanUpload(formData)
          .then((res) => {
            if (res.status == 200) {
              resolve();
            } else {
              this.$message({
                message: '上传失败',
                type: 'error',
                duration: 1000,
              });
            }
          })
          .catch((err) => {
            this.$Message.error(err.msg);
          });
      });
    },

    dataURLtoBlob(dataurl) {
      const arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]);
      let n = bstr.length;
      const u8arr = new Uint8Array(n);
      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }
      return new Blob([u8arr], { type: mime });
    },
    fileChange(file, fileList) {
      compressImg(file.raw).then((res) => {
        if (fileList.length) fileList[fileList.length - 1].raw = res;
        this.imgList = fileList;
        this.imgList.map((e) => {
          console.log(e);
          this.allSize += e.raw.size;
        });
      });
    },
    loadData(item, callback) {
      getCategoryListApi({
        pid: item.value,
      })
        .then(async (res) => {
          const data = res.data.list;
          callback(data);
        })
        .catch((res) => {});
    },
  },
};
</script>
<style lang="scss" scoped>
.upload-btn {
  display: flex;
  align-items: center;
}
.img-list {
  padding: 10px;
}
/deep/ .el-upload--picture-card,
/deep/ .el-upload-list--picture-card .el-upload-list__item {
  width: 113px;
  height: 113px;
  line-height: 113px;
  overflow: inherit;
}
/deep/ .el-upload-list--picture-card .el-upload-list__item img {
  width: 111px;
  height: 111px;
  border-radius: 6px;
}
.btndel {
  position: absolute;
  z-index: 1;
  font-size: 18px;
  right: 1px;
  top: 1px;
  color: red;
}
.img-box {
  display: flex;
  padding-left: 100px;
  flex-wrap: wrap;
}
.footer {
  padding: 0 10px 0 15px;
  position: fixed;
  bottom: 0;
  width: 100%;
  box-sizing: border-box;
  background-color: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(10px);
  z-index: 277;
  border-top: 1px solid #f0f0f0;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  .btn {
    border: 1px solid #cccccc;
    width: 88px;
    height: 30px;
    border-radius: 15px;
    color: #000;
    font-size: 14px;
    font-family: PingFang SC-Regular, PingFang SC;
    font-weight: 400;
    color: #666666;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .upload {
    background-color: #e93323;
    color: #fff;
    margin-left: 10px;
  }
}
.upload-success {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  height: 80vh;
  .success {
    width: 50px;
    height: 50px;
    background: #4bbc12;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    .image {
      width: 60%;
    }
  }
  .text {
    font-size: 16px;
    font-family: PingFang SC-Medium, PingFang SC;
    font-weight: 500;
    color: #282828;
    margin-bottom: 40px;
  }
  .again {
    width: 150px;
    height: 43px;
    border-radius: 21px;
    text-align: center;
    line-height: 41px;
    font-size: 15px;
    font-weight: 400;
    color: #333333;
    border: 1px solid #cccccc;
  }
}
</style>
