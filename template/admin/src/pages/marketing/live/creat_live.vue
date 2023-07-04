<template>
  <div>
    <div class="i-layout-page-header header-title">
      <div class="fl_header">
        <span>
          <el-button icon="ios-arrow-back" size="small" type="text" @click="$router.go(-1)">返回</el-button>
        </span>
        <el-divider direction="vertical"></el-divider>
        <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
      </div>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        class="tabform"
        :rules="ruleValidate"
        @submit.native.prevent
      >
        <el-col :span="24">
          <Alert type="warning" show-icon style="width: 550px; margin-left: 17px; margin-bottom: 25px"
            >必须前往微信小程序官方后台开通直播权限，关注<span style="color: red; cursor: pointer" @click="codeImg"
              >【小程序直播】</span
            >须知直播状态</Alert
          >
        </el-col>
        <el-col :span="24">
          <el-form-item label="选择主播：" prop="anchor_wechat">
            <el-select
              v-model="formValidate.anchor_wechat"
              filterable
              clearable
              style="width: 300px"
              @change="anchorName"
            >
              <el-option
                v-for="(item, index) in liveList"
                :value="item.wechat"
                :key="index"
                :label="item.wechat"
              ></el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-row :gutter="24" >
          <el-col :span="24">
            <el-form-item label="直播间名称：" prop="name">
              <el-input
                enter-button
                placeholder="请输入直播间名称"
                element-id="name"
                v-model="formValidate.name"
                style="width: 300px"
              />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <div style="display: flex">
              <el-form-item label="背景图：" prop="name">
                <div @click="modalPicTap(0)" class="box">
                  <img :src="formValidate.cover_img" alt="" v-if="formValidate.cover_img" />
                  <div class="upload-box" v-else>
                    <Icon type="ios-camera-outline" size="36" />
                  </div>
                </div>
              </el-form-item>
              <span style="margin-left: 20px; color: #b0bac5">尺寸：1080*1920px</span>
            </div>
          </el-col>
          <el-col :span="24">
            <div style="display: flex">
              <el-form-item label="分享图：" prop="name">
                <div @click="modalPicTap(1)" class="box">
                  <img :src="formValidate.share_img" alt="" v-if="formValidate.share_img" />
                  <div class="upload-box" v-else>
                    <Icon type="ios-camera-outline" size="36" />
                  </div>
                </div>
              </el-form-item>
              <span style="margin-left: 20px; color: #b0bac5">尺寸：800*640px</span>
            </div>
          </el-col>
          <!--<el-col :span="24">-->
          <!--<el-form-item label="主播昵称：">-->
          <!--<el-input enter-button  placeholder="请输入主播昵称" element-id="anchor_name" v-model="formValidate.anchor_name" style="width: 60%;"/>-->
          <!--</el-form-item>-->
          <!--</el-col>-->
          <el-col :span="24">
            <el-form-item label="联系电话：">
              <el-input
                enter-button
                placeholder="请输入主播联系电话"
                element-id="phone"
                v-model="formValidate.phone"
                style="width: 300px"
              />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="直播时间：" prop="name">
              <DatePicker
                type="datetimerange"
                format="yyyy-MM-dd HH:mm"
                placeholder="请选择直播时间"
                style="width: 300px"
                :value="timeVal"
                @change="selectDate"
              ></DatePicker>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="排序：">
              <el-input
                type="number"
                enter-button
                placeholder="0"
                element-id="phone"
                v-model="formValidate.sort"
                style="width: 300px"
              />
            </el-form-item>
          </el-col>
          <!-- <el-col :span="24">
            <el-form-item label="显示样式：">
              <el-radio-group v-model="formValidate.screen_type">
                <el-radio :label="item.label" v-for="(item, index) in screen_type" :key="index">
                  <span>{{ item.value }}</span>
                </el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col> -->
          <el-col :span="24">
            <el-form-item label="直播间类型：">
              <el-radio-group v-model="formValidate.type">
                <el-radio :label="item.label" v-for="(item, index) in type" :key="index">
                  <span>{{ item.value }}</span>
                </el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="直播间点赞：">
              <el-radio-group v-model="formValidate.close_like">
                <el-radio :label="item.label" v-for="(item, index) in close_like" :key="index">
                  <span>{{ item.value }}</span>
                </el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="直播卖货：">
              <el-radio-group v-model="formValidate.close_goods">
                <el-radio :label="item.label" v-for="(item, index) in close_goods" :key="index">
                  <span>{{ item.value }}</span>
                </el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="直播间评论：">
              <el-radio-group v-model="formValidate.close_comment">
                <el-radio :label="item.label" v-for="(item, index) in close_comment" :key="index">
                  <span>{{ item.value }}</span>
                </el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="24" >
          <el-col v-bind="grid" :span="24">
            <el-button :loading="loading" type="primary" style="margin-left: 99px" @click="handleSubmit('formItem')">
              <span v-if="!loading">提交</span>
              <span v-else>提交中...</span>
            </el-button>
            <!-- <el-button
              type="primary"
              @click="handleSubmit('formItem')"
              style="width: 19%; margin-left: 99px"
              >提交</el-button
            > -->
          </el-col>
        </el-row>
      </el-form>
    </el-card>
    <div>
      <Modal
        v-model="modalPic"
        width="950px"
        scrollable
        footer-hide
        closable
        title="上传商品图"
        :mask-closable="false"
        :z-index="888"
      >
        <uploadPictures
          :isChoice="isChoice"
          @getPic="getPic"
          :gridBtn="gridBtn"
          :gridPic="gridPic"
          v-if="modalPic"
        ></uploadPictures>
      </Modal>
    </div>
    <Modal v-model="modal3" title="二维码" @on-cancel="cancel" footer-hide>
      <div class="acea-row row-around">
        <div v-viewer class="QRpic">
          <img src="https://res.wx.qq.com/op_res/9rSix1dhHfK4rR049JL0PHJ7TpOvkuZ3mE0z7Ou_Etvjf-w1J_jVX0rZqeStLfwh" />
        </div>
      </div>
      <!-- <Spin fix v-if="spin"></Spin> -->
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import uploadPictures from '@/components/uploadPictures';
import { liveAdd, liveAuchorList } from '@/api/live';
export default {
  name: 'creat_live',
  components: {
    uploadPictures,
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '100px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  data() {
    return {
      gridBtn: {
        xl: 4,
        lg: 8,
        md: 8,
        sm: 8,
        xs: 8,
      },
      gridPic: {
        xl: 6,
        lg: 8,
        md: 12,
        sm: 12,
        xs: 12,
      },
      grid: {
        xl: 10,
        lg: 16,
        md: 18,
        sm: 24,
        xs: 24,
      },
      loading: false,
      formValidate: {
        name: '',
        anchor_name: '',
        anchor_wechat: '',
        phone: '',
        screen_type: 0,
        close_like: 1,
        close_goods: 1,
        close_comment: 1,
        cover_img: '',
        share_img: '',
        sort: 0,
        type: 0,
        start_time: '',
      },
      screen_type: [
        {
          value: '竖屏',
          label: 0,
        },
        {
          value: '横屏',
          label: 1,
        },
      ],
      type: [
        // {
        //     value:'推流',
        //     label:1
        // },
        {
          value: '手机直播',
          label: 0,
        },
      ],
      close_like: [
        {
          value: '开启',
          label: 1,
        },
        {
          value: '关闭',
          label: 0,
        },
      ],
      close_goods: [
        {
          value: '开启',
          label: 1,
        },
        {
          value: '关闭',
          label: 0,
        },
      ],
      close_comment: [
        {
          value: '开启',
          label: 1,
        },
        {
          value: '关闭',
          label: 0,
        },
      ],
      timeVal: '',
      modalPic: false,
      isChoice: '单选',
      activeIndex: 0,
      liveList: [],
      modal3: false,
      ruleValidate: {
        anchor_wechat: [{ required: true, message: 'Please select the city', trigger: 'change' }],
        name: [{ required: true, message: 'The name cannot be empty', trigger: 'blur' }],
      },
    };
  },
  mounted() {
    this.getLive();
  },
  methods: {
    cancel() {
      this.modal3 = false;
    },
    codeImg() {
      this.modal3 = true;
    },
    anchorName(e) {
      this.liveList.filter((el, index) => {
        if (el.wechat === e) {
          this.formValidate.anchor_name = el.name;
        }
      });
    },
    //主播列表；
    getLive() {
      let formValidate = {
        kerword: '',
        page: '',
        limit: '',
      };
      liveAuchorList(formValidate)
        .then((res) => {
          this.liveList = res.data.list;
        })
        .catch((error) => {
          this.$Message.error(error.msg);
        });
    },
    // 点击图文封面
    modalPicTap(type) {
      this.activeIndex = type;
      this.modalPic = true;
    },
    // 选择日期
    selectDate(e) {
      this.formValidate.start_time = e;
    },
    // 获取图片信息
    getPic(pc) {
      this.$nextTick(() => {
        if (this.activeIndex == 0) {
          this.formValidate.cover_img = pc.att_dir;
        } else {
          this.formValidate.share_img = pc.att_dir;
        }
        this.modalPic = false;
      });
    },
    // 保存
    handleSubmit(name) {
      this.loading = true;
      liveAdd(this.formValidate)
        .then((res) => {
          this.$Message.success('添加成功');
          setTimeout(() => {
            this.loading = false;
            this.$router.push({ path: this.$routeProStr + '/marketing/live/live_room' });
          }, 500);
        })
        .catch((error) => {
          setTimeout(() => {
            this.loading = false;
          }, 1000);
          this.$Message.error(error.msg);
        });
    },
  },
};
</script>

<style scoped lang="stylus">
.QRpic {
  width: 180px;
  height: 180px;

  img {
    width: 100%;
    height: 100%;
  }
}

.upload-box {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
  background: #ccc;
}

.box {
  width: 60px;
  height: 60px;
  margin-bottom: 10px;

  img {
    width: 100%;
    height: 100%;
  }
}
</style>
