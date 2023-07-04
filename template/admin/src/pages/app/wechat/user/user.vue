<template>
  <div>
    <div class="i-layout-page-header header-title">
      <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <div class="table_box">
        <el-form
          ref="formValidate"
          :model="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          class="tabform"
          @submit.native.prevent
        >
          <el-row :gutter="24" justify="end">
            <el-col :span="24" class="ivu-text-left">
              <el-form-item :label="fromList.title + '：'">
                <el-radio-group
                  type="button"
                  v-model="formValidate.data"
                  class="mr15"
                  @change="selectChange(formValidate.data)"
                >
                  <el-radio-button :label="itemn.val" v-for="(itemn, indexn) in fromList.fromTxt" :key="indexn">
                    {{ itemn.text }}
                  </el-radio-button>
                </el-radio-group>
                <DatePicker
                  :editable="false"
                  @change="onchangeTime"
                  :value="timeVal"
                  format="yyyy/MM/dd"
                  type="daterange"
                  placement="bottom-end"
                  placeholder="请选择时间"
                  style="width: 200px"
                ></DatePicker>
              </el-form-item>
            </el-col>
            <el-col :span="24" class="ivu-text-left">
              <el-form-item label="用户分组：">
                <el-radio-group type="button" v-model="formValidate.groupid">
                  <el-radio-button :label="item.id" v-for="(item, index) in groupList" :key="index">{{
                    item.name
                  }}</el-radio-button>
                </el-radio-group>
              </el-form-item>
            </el-col>
            <el-col :span="24" class="ivu-text-left">
              <el-form-item label="用户标签：">
                <TagSelect v-model="tagidList">
                  <TagSelectOption :name="item.id" v-for="(item, index) in tagList" :key="index">{{
                    item.name
                  }}</TagSelectOption>
                </TagSelect>
              </el-form-item>
            </el-col>
            <el-col :span="24" class="ivu-text-left">
              <el-col :xl="7" :lg="12" :md="12" :sm="24" :xs="24">
                <el-form-item label="用户名称：">
                  <el-input placeholder="请输入用户名称" v-model="formValidate.nickname" class="perW90"></el-input>
                </el-form-item>
              </el-col>
              <el-col :xl="7" :lg="12" :md="12" :sm="24" :xs="24" class="sex_box">
                <el-form-item label="性别：">
                  <el-select v-model="formValidate.sex" style="width: 90%" clearable>
                    <el-option :value="1" label="男"></el-option>
                    <el-option :value="2" label="女"></el-option>
                    <el-option :value="0" label="保密"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :xl="7" :lg="12" :md="12" :sm="24" :xs="24" class="subscribe_box">
                <el-form-item label="是否关注公众号：">
                  <el-select v-model="formValidate.subscribe" style="width: 90%" clearable>
                    <el-option value="1" label="是"></el-option>
                    <el-option value="0" label="否"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :xl="3" :lg="3" :md="3" :sm="24" :xs="24" class="btn_box">
                <el-form-item>
                  <el-button type="primary" icon="ios-search" label="default" class="userSearch" @click="userSearchs"
                    >搜索</el-button
                  >
                </el-form-item>
              </el-col>
            </el-col>
            <el-divider direction="vertical" dashed />
            <el-col :span="24">
              <el-button type="primary" class="mr20" @click="onSend">发送优惠券</el-button>
              <el-button class="greens mr20" size="default" @click="onSendPic">
                <Icon type="md-list"></Icon>
                发送图文消息
              </el-button>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <el-table
        ref="selection"
        :data="tabList"
        :loading="loading"
        empty-text="暂无数据"
        highlight-current-row
        class="mt25"
        @select="onSelectTab"
        @select-all="onSelectTab"
      >
        <el-table-column type="selection" width="55"> </el-table-column>
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.uid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="微信用户名称" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="头像" min-width="130">
          <template slot-scope="scope">
            <div class="tabBox_img" v-viewer>
              <img v-lazy="scope.row.headimgurl" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="性别" min-width="130">
          <template slot-scope="scope">
            <span v-show="scope.row.sex === 1">男</span>
            <span v-show="scope.row.sex === 2">女</span>
            <span v-show="scope.row.sex === 0">保密</span>
          </template>
        </el-table-column>
        <el-table-column label="地区" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.country + scope.row.province + scope.row.city }}</span>
          </template>
        </el-table-column>
        <el-table-column label="是否关注公众号" min-width="130">
          <template slot-scope="scope">
            <span v-show="scope.row.subscribe === 1">关注</span>
            <span v-show="scope.row.subscribe === 0">未关注</span>
          </template>
        </el-table-column>
        <el-table-column label="用户分组" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.groupid }}</span>
          </template>
        </el-table-column>
        <el-table-column label="用户标签" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.tagid_list }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="170">
          <template slot-scope="scope">
            <el-dropdown size="small" @command="changeMenu(scope.row, $event)">
              <span class="el-dropdown-link">操作<i class="el-icon-arrow-down el-icon--right"></i> </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item command="1" v-show="scope.row.subscribe">修改分组</el-dropdown-item>
                <el-dropdown-item command="2" v-show="scope.row.subscribe">修改标签</el-dropdown-item>
                <el-dropdown-item command="3" v-show="scope.row.subscribe">同步标签</el-dropdown-item>
                <el-dropdown-item v-show="!scope.row.subscribe">无法操作</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
    <!-- 用户分组和标签编辑-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
    <!-- 发送优惠券-->
    <send-from ref="sends" :userIds="user_ids"></send-from>
    <!--发送图文消息 -->
    <Modal
      v-model="modal13"
      scrollable
      title="发送消息"
      :z-index="100"
      width="1200"
      height="800"
      footer-hide
      class="modelBox"
    >
      <news-category
        v-if="modal13"
        :isShowSend="isShowSend"
        :userIds="user_ids"
        :scrollerHeight="scrollerHeight"
        :contentTop="contentTop"
        :contentWidth="contentWidth"
        :maxCols="maxCols"
      ></news-category>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { wechatUserListtApi, tagListtApi, groupsEditApi } from '@/api/app';
import newsCategory from '@/components/newsCategory/index';
import editFrom from '@/components/from/from';
import sendFrom from '@/components/sendCoupons/index';
export default {
  name: 'user',
  components: {
    newsCategory,
    editFrom,
    sendFrom,
  },
  data() {
    return {
      tagidList: [],
      isShowSend: true,
      maxCols: 4,
      scrollerHeight: '600',
      contentTop: '130',
      contentWidth: '98%',
      modal13: false,
      timeVal: [],
      fromList: {
        title: '选择时间',
        custom: true,
        fromTxt: [
          { text: '全部', val: '' },
          { text: '今天', val: 'today' },
          { text: '昨天', val: 'yesterday' },
          { text: '最近7天', val: 'lately7' },
          { text: '最近30天', val: 'lately30' },
          { text: '本月', val: 'month' },
          { text: '本年', val: 'year' },
        ],
      },
      formValidate: {
        limit: 15,
        page: 1,
        nickname: '',
        data: '',
        tagid_list: '',
        sex: '',
        groupid: 0,
        subscribe: '',
        export: 2,
      },
      loading: false,
      tabList: [],
      total: 0,
      value2: '',
      grid: {
        xl: 8,
        lg: 8,
        md: 8,
        sm: 24,
        xs: 24,
      },
      tagList: [],
      groupList: [],
      FromData: null,
      selectionList: [],
      user_ids: '',
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '85px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getListTag();
    this.getList();
  },
  methods: {
    // 操作
    changeMenu(row, name) {
      switch (name) {
        case '1':
          this.editGroup(`app/wechat/user_group/${row.openid}/edit`);
          break;
        case '2':
          this.editGroup(`app/wechat/user_tag/${row.openid}/edit`);
          break;
        default:
          let delfromData = {
            title: '同步该用户标签',
            url: `app/wechat/syn_tag/${row.openid}`,
            method: 'PUT',
            ids: '',
          };
          this.$modalSure(delfromData)
            .then((res) => {
              this.$Message.success(res.msg);
              this.getList();
            })
            .catch((res) => {
              this.$Message.error(res.msg);
            });
      }
    },
    // 修改用户分组 标签
    editGroup(url) {
      groupsEditApi(url)
        .then(async (res) => {
          this.FromData = res.data;
          this.$refs.edits.modals = true;
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 修改成功
    submitFail() {
      this.getList();
    },
    // 同步标签
    submitModel() {
      this.getList();
    },
    // 点击发送优惠券
    onSend() {
      if (this.selectionList.length === 0) {
        this.$Message.warning('请选择要发送优惠券的用户');
      } else {
        this.$refs.sends.modals = true;
        this.$refs.sends.getList();
      }
    },
    // 发送图文消息
    onSendPic() {
      if (this.selectionList.length === 0) {
        this.$Message.warning('请选择要发送图文消息的用户');
      } else {
        this.modal13 = true;
      }
    },
    // 全选
    onSelectTab(selection) {
      this.selectionList = selection;
      let data = [];
      this.selectionList.map((item) => {
        data.push(item.uid);
      });
      this.user_ids = data.join(',');
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal.join('-');
      this.getList();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.data = tab;
      this.timeVal = [];
      this.getList();
    },
    // 标签 分组
    getListTag() {
      let obj = {
        id: '',
        name: '全部',
      };
      tagListtApi()
        .then(async (res) => {
          let data = res.data;
          this.tagList = data.tagList;
          this.groupList = data.groupList;
          this.groupList.unshift(obj);
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 列表
    getList() {
      this.loading = true;
      this.formValidate.sex = this.formValidate.sex || '';
      this.formValidate.subscribe = this.formValidate.subscribe || '';
      this.formValidate.tagid_list = this.tagidList.join(',');
      wechatUserListtApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 搜索
    userSearchs() {
      this.getList();
    },
  },
};
</script>

<style scoped lang="stylus">
.Refresh
    font-size 12px
    color #1890FF
    cursor pointer
.userFrom
    >>> .ivu-form-item-content
        margin-left: 0px !important
.tabBox_img
    width 36px
    height 36px
    border-radius:4px
    cursor pointer
    img
        width 100%
        height 100%
.subscribe_box
   >>> .ivu-form-item-label
      width 110px !important
   >>> .ivu-form-item-content
      margin-left  110px !important
.sex_box
    >>> .ivu-form-item-label
      width 60px !important
    >>> .ivu-form-item-content
      margin-left  60px !important
.btn_box
    >>> .ivu-form-item-content
      margin-left 0 !important
.modelBox
    >>> .ivu-modal-body
        padding 0 16px 16px 16px !important
</style>
