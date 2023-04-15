<template>
  <div>
    <Card :bordered="false" dis-hover class="ivu-mt">
      <Button type="primary" @click="groupAdd()" class="mr20">代码生成</Button>
      <Button type="success" @click="buildCode()" class="mr20">重新发布</Button>
      <Table
        :columns="columns1"
        :data="tabList"
        ref="table"
        class="mt25"
        :loading="loading"
        highlight-row
        no-userFrom-text="暂无数据"
        no-filtered-userFrom-text="暂无筛选结果"
      >
        <template slot-scope="{ row, index }" slot="statuss">
          <i-switch
            v-model="row.status"
            :value="row.status"
            :true-value="1"
            :false-value="0"
            @on-change="onchangeIsShow(row)"
            size="large"
          >
            <span slot="open">显示</span>
            <span slot="close">隐藏</span>
          </i-switch>
        </template>
        <template slot-scope="{ row, index }" slot="action">
          <a @click="edit(row, '编辑')">查看</a>
          <Divider type="vertical" />
          <a @click="del(row, '删除', index)">删除</a>
        </template>
      </Table>
      <div class="acea-row row-right page">
        <Page
          :total="total"
          :current="formValidate.page"
          show-elevator
          show-total
          @on-change="pageChange"
          :page-size="formValidate.limit"
        />
      </div>
    </Card>
    <Modal
      :class-name="className"
      v-model="modals"
      scrollable
      footer-hide
      closable
      :mask-closable="false"
      width="80%"
      :before-close="editModalChange"
    >
      <p slot="header" class="diy-header" ref="diyHeader">
        <span>{{ title }}</span>
      </p>
      <div style="height: 100%">
        <div class="file-box">
          <div class="file-fix"></div>
          <div class="file-content">
            <Tabs
              type="card"
              v-model="indexEditor"
              style="height: 100%"
              @on-click="toggleEditor"
              :animated="false"
              closable
              @on-tab-remove="handleTabRemove"
            >
              <TabPane
                v-for="value in editorIndex"
                :key="value.index"
                :name="value.index.toString()"
                :label="value.title"
                :icon="value.icon"
              >
                <div ref="container" :id="'container_' + value.index" style="height: 100%; min-height: 560px"></div>
              </TabPane>
            </Tabs>
          </div>
          <Spin size="large" fix v-if="spinShow"></Spin>
        </div>
      </div>
    </Modal>
    <Modal
      v-model="buildModals"
      scrollable
      title="终端"
      footer-hide
      closable
      :mask-closable="false"
      width="60%"
      :before-close="editModalChange"
    >
      <Alert type="warning">当前终端未运行于安装服务下，部分命令可能无法执行.</Alert>
      <div>
        <div v-for="(item, index) in codeBuildList" :key="index">{{ item }}</div>
      </div>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { crudList, crudDet } from '@/api/systemCodeGeneration';
import * as monaco from 'monaco-editor';
import { getCookies, removeCookies } from '@/libs/util';
import Setting from '@/setting';

export default {
  data() {
    return {
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      formValidate: {
        page: 1,
        limit: 20,
        title: '',
      },
      loading: false,
      buildModals: false,
      tabList: [],
      codeBuildList: [],
      total: 0,
      columns1: [
        {
          title: 'ID',
          key: 'id',
          width: 80,
        },
        {
          title: '菜单名',
          key: 'name',
          minWidth: 130,
        },
        {
          title: '表名',
          key: 'table_name',
          minWidth: 130,
        },
        {
          title: '字符集',
          key: 'table_collation',
          minWidth: 130,
        },
        {
          title: '表备注',
          key: 'table_comment',
          minWidth: 130,
        },
        {
          title: '添加时间',
          key: 'add_time',
          minWidth: 130,
        },
        {
          title: '操作',
          slot: 'action',
          fixed: 'right',
          minWidth: 150,
        },
      ],
      FromData: null,
      titleFrom: '',
      groupId: 0,
      addId: '',
      editorList: [], //编辑器数组
      indexEditor: 0, //当前编辑器索引
      code: '', //当前文件打开时的内容
      contextData: null, //左侧导航右键点击是产生的数据对象

      fileType: '', // 文件操作类型 createFolder|创建文件夹 createFile|创建文件 delFolder|删除文件夹或者文件
      className: '', //全屏 class名
      spinShow: false,
      modals: false, //编辑器开关
      editor: '', //当前编辑器对象
      editorIndex: [],
      title: '',
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : 75;
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.getList();
  },
  beforeDestroy() {
    if (this.source) {
      this.source.close(); //关闭EventSource
    }
  },
  methods: {
    buildCode() {
      this.buildModals = true;
      if (typeof EventSource !== 'undefined') {
        //支持eventSource
        var postURL = Setting.apiBaseURL + '/system/crud/npm?token=' + getCookies('token');
        this.source = new EventSource(postURL);
        let self = this; //因EventSource中this的指向变了，所以要提前存储一下
        this.source.onopen = function (res) {
          console.log('项目链接Eventsource成功', res);
        };
        this.source.onmessage = function (data) {
          //代码块
          console.log(data);
        };
        this.source.onerror = function (err) {
          console.log('项目Eventsource链接失败', err);
          //链接失败后EventSource会每隔三秒左右重新发起链接
        };
      } else {
        console.log('暂不支持EventSource');
      }
    },
    // 跳转到组合数据列表页面
    goList(row) {
      this.$router.push({
        path: this.$routeProStr + '/system/config/system_group/list/' + row.id,
      });
    },
    // 列表
    getList() {
      this.loading = true;
      crudList(this.formValidate)
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
    pageChange(index) {
      this.formValidate.page = index;
      this.getList();
    },
    // 表格搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 点击添加
    groupAdd() {
      this.$router.push({
        name: 'system_code_generation',
      });
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `system/crud/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$Message.success(res.msg);
          this.tabList.splice(num, 1);
          this.getList();
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 编辑
    edit(row) {
      console.log(row);
      this.spinShow = true;
      // 创建代码容器
      this.title = row.name;
      this.$nextTick((e) => {
        this.openfile(row.id, false);
      });
    },
    //打开文件
    openfile(id) {
      try {
        console.log(id);
        let that = this;
        this.editorIndex = [];
        this.editorList = [];
        crudDet(id)
          .then(async (res) => {
            let data = res.data[0];
            res.data.map((i, index) => {
              let data = i;
              this.editorIndex.push({
                tab: true,
                index: index + '',
                title: data.name,
                pathname: data.path,
              });
              that.code = data.content;
              this.initEditor(index, data.content);
              this.$nextTick((e) => {
                // 保存相对信息
                that.editorList[index].path = data.path;
                that.editorList[index].oldCode = that.content;
                that.editorIndex[index].title = data.name;
                console.log('111');
              });
            });
            that.modals = true;
            that.spinShow = false;
          })
          .catch((res) => {
            that.catchFun(res);
          });
      } catch (error) {
        console.log(error);
      }
    },
    /**
     * 窗口最大化
     */
    winChanges() {
      if (this.className) {
        this.className = '';
      } else {
        this.className = 'diy-fullscreen';
      }
    },
    /**
     * 初始化编辑器
     */
    initEditor(index, conetnt) {
      try {
        let that = this;
        console.log('111');
        that.$nextTick(() => {
          // 初始化编辑器，确保dom已经渲染
          that.editor = monaco.editor.create(document.getElementById('container_' + index), {
            value: conetnt, //编辑器初始显示文字
            language: 'sql', //语言支持自行查阅demo
            automaticLayout: true, //自动布局
            theme: 'vs', //官方自带三种主题vs, hc-black, or vs-dark
            foldingStrategy: 'indentation', // 代码可分小段折叠
            overviewRulerBorder: false, // 不要滚动条的边框
            scrollbar: {
              // 滚动条设置
              verticalScrollbarSize: 4, // 竖滚动条
              horizontalScrollbarSize: 10, // 横滚动条
            },
            autoIndent: true, // 自动布局
            tabSize: 4, // tab缩进长度
            autoClosingOvertype: 'always',
            readOnly: true,
          });
          that.editorList.push({
            editor: that.editor,
            oldCode: that.code,
            path: '',
            index: index,
          });
        });
      } catch (error) {
        console.log(error);
      }
    },

    /**
     * 处理接口回调
     * @param {Object} res
     */
    catchFun(res) {
      if (res.status) {
        if (res.status == 400) this.$Message.error(res.msg);
        if (res.status == 110008) {
          // this.$Message.error(res.msg);
          this.isShowLogn = true;
          this.isShowList = false;
          this.loading = false;
        }
      } else {
        // this.$Message.error('文件编码不被兼容，无法正确读取文件!');
      }
      //关闭蒙版层
      if (this.spinShow) this.spinShow = false;
      // 关闭文件列表展示
      if (this.loading) this.loading = false;
    },
    //编辑器状态变化
    editModalChange() {
      let that = this;
      that.editorList.forEach(function (value, index) {
        // 销毁当前编辑器
        that.editorList[index].editor.dispose();
        that.editorList[index].editor = null;
      });
      // 初始话数据
      that.modals = false; //编辑器开关
      that.editor = ''; //当前编辑器对象
      that.editorIndex = [
        //选项卡数组
        {
          tab: true,
          index: '0',
          title: '',
          icon: '',
        },
      ];
      that.editorList = []; //编辑器数组
      that.indexEditor = '0'; //当前编辑器索引
      that.code = ''; //当前文件打开时的内容
      that.contextData = null; //左侧导航右键点击是产生的数据对象
    },
    /**
     * 切换选项卡
     * @param {Object} index
     */
    toggleEditor(index) {
      index = Number(index);
      this.code = this.editorList[index].oldCode; //设置文件打开时的代码
      this.editor = this.editorList[index].editor; //设置编辑器实例
    },
    handleTabRemove(index) {
      let that = this;
      // 关闭选项卡
      that.editorIndex[index].tab = false; // 关闭选项卡
    },
  },
};
</script>

<style lang="scss" scoped>
// 自定义方法缩小
>>> .diy-fullscreen {
  overflow: hidden;

  .ivu-modal {
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    height: 100%;
    width: 100% !important;

    .ivu-modal-content {
      height: 100%;

      .ivu-modal-body {
        height: 100%;
      }
    }

    .ivu-tabs {
      .ivu-tabs-content-animated {
        height: 92%;
        background-color: #2f2f2f !important;
      }
    }

    .ivu-tabs-content {
      height: 100%;
    }

    .ivu-tabs {
      .ivu-tabs-tabpane {
        height: 92%;
      }
    }
  }
}
.diy-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  .diy-header-icon {
    margin-right: 30px;
    cursor: pointer;
  }

  .diy-header-icon:hover {
    opacity: 0.8;
  }
}
>>> .ivu-modal {
  top: 70px;
}

.ivu-modal-content {
  .ivu-modal-body {
    min-height: 632px;
    height: 80vh;
    overflow: hidden;
  }
}

.ivu-tabs {
  .ivu-tabs-content-animated {
    min-height: 560px;
    height: 73vh;
    margin-top: -1px;
  }

  .ivu-tabs-tabpane {
    min-height: 560px;
    height: 73vh;
    margin-top: -1px;
  }
}

.ivu-tabs-nav .ivu-tabs-tab .ivu-icon {
  color: #f00;
}

>>> body .ivu-select-dropdown .ivu-dropdown-transfer {
  background: red !important;
}

// 导航栏右键样式 无效
.file-left /deep/ .ivu-select-dropdown.ivu-dropdown-transfer .ivu-dropdown-menu .ivu-dropdown-item:hover {
  background-color: #e5e5e5 !important;
}

// 选项卡头部
>>> .ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-nav-container {
  background-color: #333;
}
</style>
