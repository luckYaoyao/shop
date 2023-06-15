<template>
  <div>
    <el-dialog
      title="上传图片"
      append-to-body
      :modal-append-to-body="false"
      :visible.sync="uploadModal"
      width="1024px"
      @closed="closed"
    >
      <div class="main">
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
          <el-form-item label="上传方式：" prop="type">
            <el-radio-group v-model="ruleForm.type" @change="radioChange(ruleForm.type)">
              <el-radio :label="0">本地上传</el-radio>
              <el-radio :label="1">网络上传</el-radio>
              <el-radio :label="2">扫码上传</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="上传至分组：" prop="region" v-show="ruleForm.type == 0 || ruleForm.type == 1">
            <el-cascader
              class="form-width"
              v-model="ruleForm.region"
              :props="props"
              :options="categoryList"
              @change="handleChange"
            ></el-cascader>
          </el-form-item>
          <el-form-item label="网络图片：" prop="region" v-if="ruleForm.type == 1">
            <el-input class="form-width" v-model="webImgUrl" placeholder="请网络图片地址"></el-input>
            <span class="tq-text" @click="getImg">提取照片</span>
          </el-form-item>
          <el-form-item label="上传图片：" prop="region" v-if="ruleForm.type == 0">
            <div class="acea-row">
              <div class="uploadCont">
                <el-upload
                  ref="upload"
                  :action="fileUrl"
                  list-type="picture-card"
                  :on-change="fileChange"
                  :file-list="ruleForm.imgList"
                  :auto-upload="false"
                  :data="uploadData"
                  :headers="header"
                  :before-upload="beforeUpload"
                >
                  <i slot="default" class="el-icon-plus"></i>
                  <div
                    slot="file"
                    slot-scope="{ file }"
                    draggable="false"
                    @dragstart="handleDragStart($event, file)"
                    @dragover="handleDragOver($event, file)"
                    @dragenter="handleDragEnter($event, file)"
                    @dragend="handleDragEnd($event, file)"
                  >
                    <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" />
                    <i class="el-icon-error btndel" @click="handleRemove(file)" />
                  </div>
                </el-upload>
                <div class="tips">建议上传图片最大宽度750px，不超过3MB；仅支持jpeg、png格式</div>
              </div>
            </div>
          </el-form-item>
          <template v-if="ruleForm.type == 1">
            <div class="img-box pl100">
              <div
                v-for="(item, index) in ruleForm.imgList"
                :key="index"
                class="pictrue"
                draggable="false"
                @dragstart="handleDragStart($event, item)"
                @dragover.prevent="handleDragOver($event, item)"
                @dragenter="handleDragEnter($event, item)"
                @dragend="handleDragEnd($event, item)"
              >
                <img :src="item.url" />
                <i class="el-icon-error btndel" @click="handleRemove(index)" />
              </div>
            </div>
          </template>
        </el-form>
        <div class="code-image" v-show="ruleForm.type == 2">
          <div class="left">
            <div class="code" ref="qrCodeUrl"></div>
            <el-cascader
              class="form-width"
              v-model="ruleForm.region"
              :props="props"
              :options="categoryList"
              @change="handleChange"
            ></el-cascader>
            <div>扫描二维码，快速上传手机图片</div>
          </div>
          <div class="right">
            <el-button size="small" @click="scanUploadGet">刷新图库</el-button>
            <div class="tip">刷新图库按钮，可显示移动端上传成功的图片</div>
            <div class="img-box">
              <div
                v-for="(item, index) in ruleForm.imgList"
                :key="index"
                class="pictrue"
                draggable="false"
                @dragstart="handleDragStart($event, item)"
                @dragover.prevent="handleDragOver($event, item)"
                @dragenter="handleDragEnter($event, item)"
                @dragend="handleDragEnd($event, item)"
              >
                <img :src="item.att_dir" />
                <i class="el-icon-error btndel" @click="handleRemove(index)" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <span slot="footer" class="dialog-footer">
        <el-button @click="uploadModal = false">取 消</el-button>
        <el-button type="primary" @click="submitUpload">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { getCategoryListApi, moveApi, onlineUpload } from '@/api/uploadPictures';
import Setting from '@/setting';
import { getCookies } from '@/libs/util';
import { fileUpload, scanUploadQrcode, scanUploadGet } from '@/api/setting';
import QRCode from 'qrcodejs2';

export default {
  name: '',
  props: {
    categoryList: {
      default: () => {
        return [];
      },
    },
  },
  data() {
    return {
      webImgUrl: '',
      uploadModal: false,
      fileUrl: Setting.apiBaseURL + '/file/upload',
      header: {
        'Authori-zation': 'Bearer ' + getCookies('token'),
      },
      uploadData: {},
      props: { label: 'title', value: 'id', multiple: false, lazy: true, lazyLoad: this.loadData },
      disabled: false,
      ruleForm: {
        type: 0,
        imgList: [],
      },
      rules: { type: [{ required: true, message: '请选择活动资源', trigger: 'change' }] },
      treeId: '',
      qrcode: '',
      scanToken: '',
    };
  },
  created() {},
  mounted() {},
  methods: {
    closed() {
      this.ruleForm.type = 0;
      this.ruleForm.imgList = [];
    },
    radioChange(type) {
      this.ruleForm.type = type;
      this.ruleForm.imgList = [];
      if (type == 2) {
        this.scanUploadQrcode();
      }
    },
    scanUploadQrcode() {
      scanUploadQrcode().then((res) => {
        this.creatQrCode(res.data.url);
        this.scanToken = res.data.url;
      });
    },
    scanUploadGet() {
      let token = this.scanToken.split('token=')[1];
      scanUploadGet(token).then((res) => {
        this.ruleForm.imgList = res.data;
        console.log(res);
      });
    },

    getImg() {
      this.ruleForm.imgList.push({
        url: this.webImgUrl,
      });
    },
    async submitUpload() {
      if (this.ruleForm.type == 0) {
        this.uploadData = {
          pid: this.treeId,
        };
        for (let i = 0; i < this.ruleForm.imgList.length; i++) {
          const file = this.ruleForm.imgList[i].raw;
          await this.uploadItem(file);
          if (i == this.ruleForm.imgList.length - 1) {
            this.$Message.success('上传成功');
            this.$emit('uploadSuccess');
            this.uploadModal = false;
          }
        }
      } else if (this.ruleForm.type == 1) {
        let urls = this.ruleForm.imgList.map((e) => {
          return e.url;
        });
        onlineUpload({ pid: this.treeId, images: urls }).then((res) => {
          this.$Message.success('上传成功');
          this.$emit('uploadSuccess');
          this.uploadModal = false;
        });
      } else if (this.ruleForm.type == 2) {
        let attId = this.ruleForm.imgList.map((e) => {
          return e.att_id;
        });
        moveApi({ pid: this.treeId, images: attId }).then((res) => {
          this.$Message.success('上传成功');
          this.$emit('uploadSuccess');
          this.uploadModal = false;
        });
      }
    },
    uploadItem(file) {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('pid', this.treeId);
        fileUpload(formData).then((res) => {
          if (res.status == 200) {
            resolve();
            // this.$emit('uploadImgSuccess', res.data);
          } else {
            this.$message({
              message: '上传失败',
              type: 'error',
              duration: 1000,
            });
          }
        });
      });
    },
    beforeUpload(file) {
      //   console.log('1s ');
      //   if (!/image\/\w+/.test(file.type)) {
      //     this.$Message.error('请上传以jpg、jpeg、png等结尾的图片文件'); //FileExt.toLowerCase()
      //     return false;
      //   }
      //   let promise = new Promise((resolve) => {
      //     this.$nextTick(function () {
      //       resolve(true);
      //     });
      //   });
      //   return promise;
    },
    creatQrCode(url) {
      this.$refs.qrCodeUrl.innerHTML = '';
      var qrcode = new QRCode(this.$refs.qrCodeUrl, {
        text: url, // 需要转换为二维码的内容
        width: 160,
        height: 160,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H,
      });
    },
    handleRemove(file) {
      console.log(file);
      let index = this.ruleForm.imgList.findIndex((e) => {
        e.url == file.url;
      });
      this.ruleForm.imgList.splice(index, 1);
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleDownload(file) {
      console.log(file);
    },
    fileChange(file, fileList) {
      console.log(file, fileList);
      this.ruleForm.imgList = fileList;
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
    handleChange(e) {
      console.log(e, e.length == 1 ? e[0] : e[e.length - 1]);
      this.treeId = e[e.length - 1];
    },
    // 移动
    handleDragStart(e, item) {
      this.dragging = item;
    },
    handleDragEnd(e, item) {
      this.dragging = null;
    },
    handleDragOver(e) {
      e.dataTransfer.dropEffect = 'move';
    },
    handleDragEnter(e, item) {
      e.dataTransfer.effectAllowed = 'move';
      if (item === this.dragging) {
        return;
      }
      const newItems = [...this.ruleForm.imgList];
      const src = newItems.indexOf(this.dragging);
      const dst = newItems.indexOf(item);
      newItems.splice(dst, 0, ...newItems.splice(src, 1));
      this.ruleForm.imgList = newItems;
    },
  },
};
</script>
<style lang="stylus" scoped>
.main{
    min-height: 600px
}
.pictrue {
  width: 60px;
  height: 60px;
  border: 1px dotted rgba(0, 0, 0, 0.1);
  margin-right: 10px;
  position: relative;
  cursor: pointer;

  img {
    width: 100%;
    height: 100%;
  }
}
.btndel {
  position: absolute;
  z-index: 1;
  width: 20px !important;
  height: 20px !important;
  left: 52px;
  top: -4px;
  color red
}
.form-width{
    width 280px
}
.tq-text{
    margin-left 14px
    font-size: 12px;
    font-weight: 400;
    color: #1890FF;
    cursor pointer
}
/deep/ .el-upload--picture-card, /deep/ .el-upload-list--picture-card .el-upload-list__item{
    width 64px
    height 64px
    line-height: 72px;
    overflow inherit
}
.pl100{
    padding-left 100px
}
.img-box{
    display flex
    flex-wrap: wrap
}
.tips{
    font-size: 12px;
    color #BBBBBB
}
.code-image{
    display flex
    padding-left 100px
    margin-top 12px
    .left{
        display flex
        flex-direction: column
            margin-right 20px
        align-items center
        .code{
            border: 1px solid #DDDDDD;
            display flex
            align-items center
            justify-content center
            width 200px
            height 200px
            border-radius: 4px
            .code-img{
                width 160px
                height 160px
            }
        }
        .form-width{
            width 200px
            margin-bottom 18px
        }
        .code{
            margin-bottom 14px
        }
    }
    .right{
        .tip{
            font-size: 12px;
            font-weight: 400;
            color: #BBBBBB;
            margin 10px 0
        }
    }
}
</style>
