<template>
  <div>
    <div class="i-layout-page-header header-title">
      <div class="fl_header">
        <router-link :to="{ path: $routeProStr + '/system/config/system_config_tab/index' }"
          ><el-button icon="ios-arrow-back" size="small" type="text">返回</el-button></router-link
        >
        <el-divider direction="vertical"></el-divider>
        <span class="ivu-page-header-title mr20" style="padding: 0" v-text="$route.meta.title"></span>
      </div>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-row>
        <el-col v-bind="grid">
          <el-button type="primary" @click="goIndex" class="mr20">配置分类</el-button>
          <el-button type="primary" icon="md-add" @click="configureAdd">添加配置</el-button>
        </el-col>
      </el-row>
      <el-divider direction="vertical" dashed />
      <el-table
        :data="classList"
        ref="table"
        :loading="loading"
        no-userFrom-text="暂无数据"
        no-filtered-userFrom-text="暂无筛选结果"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="配置名称" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.info }}</span>
          </template>
        </el-table-column>
        <el-table-column label="字段变量" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.menu_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="字段类型" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="值" min-width="130">
          <template slot-scope="scope">
            <span
              v-if="
                scope.row.type === 'text' ||
                scope.row.type === 'textarea' ||
                scope.row.type === 'radio' ||
                scope.row.type === 'checkbox'
              "
              >{{ scope.row.value }}</span
            >
            <div class="valBox acea-row" v-if="scope.row.type === 'upload' && scope.row.upload_type === 3">
              <div v-if="scope.row.value.length">
                <div
                  class="valPicbox acea-scope.row scope.row-column-around"
                  v-for="(item, index) in scope.row.value"
                  :key="index"
                >
                  <div class="valPicbox_pic"><Icon type="md-document" /></div>
                  <span class="valPicbox_sp">{{ item.filename }}</span>
                </div>
              </div>
            </div>
            <div class="valBox acea-row" v-if="scope.row.type === 'upload' && scope.row.upload_type !== 3">
              <div v-if="scope.row.value.length">
                <div class="valPicbox acea-row row-column-around" v-for="(item, index) in scope.row.value" :key="index">
                  <div class="valPicbox_pic"><img v-lazy="item.filepath" /></div>
                  <span class="valPicbox_sp">{{ item.filename }}</span>
                </div>
              </div>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="是否显示" min-width="130">
          <template slot-scope="scope">
            <el-switch
              class="defineSwitch"
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
              active-text="显示"
              inactive-text="隐藏"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="120">
          <template slot-scope="scope">
            <a @click="edit(scope.row)">编辑</a>
            <el-divider direction="vertical"></el-divider>
            <a @click="del(scope.row, '删除分类', index)">删除</a>
          </template>
        </el-table-column>
      </el-table>
    </el-card>

    <!-- 新建 表单-->
    <el-dialog
      :visible.sync="modals2"
      title="添加配置字段"
      :close-on-click-modal="false"
      :show-close="false"
      width="700px"
    >
      <el-tabs v-model="typeFrom.type" @tab-click="onhangeTab" class="tabsName">
        <el-tab-pane label="文本框 " name="0"></el-tab-pane>
        <el-tab-pane label="多行文本框" name="1"></el-tab-pane>
        <el-tab-pane label="单选框" name="2"></el-tab-pane>
        <el-tab-pane label="文件上传" name="3"></el-tab-pane>
        <el-tab-pane label="多选框" name="4"></el-tab-pane>
        <el-tab-pane label="下拉框" name="5"></el-tab-pane>
      </el-tabs>
      <form-create
        v-if="rules.length != 0"
        :rule="rules"
        @submit="onSubmit"
        class="formBox"
        ref="fc"
        handleIcon="false"
      ></form-create>
    </el-dialog>
    <!-- 编辑表单-->
    <edit-from ref="edits" :FromData="FromData" @submitFail="submitFail"></edit-from>
  </div>
</template>

<script>
import { configTabListApi, configTabAddApi, configTabEditApi, configSetStatusApi } from '@/api/system';
import formCreate from '@form-create/iview';
import editFrom from '@/components/from/from';
import request from '@/libs/request';
export default {
  name: 'list',
  components: { formCreate: formCreate.$form(), editFrom },
  data() {
    return {
      modals2: false,
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      loading: false,
      formValidate: {
        tab_id: 0,
        page: 1,
        limit: 20,
      },
      total: 0,
      FromData: null,
      FromRequestData: {},
      modalTitleSs: '',
      classList: [],
      num: 0,
      typeFrom: {
        type: 0,
        tab_id: this.$route.params.id,
      },
      rules: [],
    };
  },
  watch: {
    $route: {
      handler: function (val, oldVal) {
        this.getList();
      },
      // 深度观察监听
      deep: true,
    },
  },
  mounted() {
    this.getList();
  },
  methods: {
    // 点击tab
    onhangeTab() {
      this.classAdd();
    },
    // 新增表单
    classAdd() {
      configTabAddApi(this.typeFrom)
        .then(async (res) => {
          if (res.data.status === false) {
            return this.$authLapse(res.data);
          }
          let data = res.data || {};
          this.FromRequestData = { action: data.action, method: data.method };
          this.rules = data.rules;
          this.modals2 = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 编辑表单
    edit(row) {
      configTabEditApi(row.id)
        .then(async (res) => {
          if (res.data.status === false) {
            return this.$authLapse(res.data);
          }
          let data = res.data || {};
          this.FromRequestData = { action: data.action, method: data.method };
          this.rules = data.rules;
          this.$refs.edits.modals = true;
          this.modals2 = true;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 提交表单
    onSubmit(formData) {
      request({
        url: this.FromRequestData.action,
        method: this.FromRequestData.method,
        data: formData,
      })
        .then((res) => {
          this.$message.success(res.msg);
          setTimeout(() => {
            this.modals2 = false;
          }, 1000);
          setTimeout(() => {
            this.getList();
          }, 1500);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 修改成功
    submitFail() {
      this.getList();
    },
    // 跳转到配置分类页面
    goIndex() {
      this.$router.push({
        path: this.$routeProStr + '/system/config/system_config_tab/index',
      });
    },
    // 添加配置
    configureAdd() {
      // this.modals2 = true;
      this.classAdd();
    },
    // 列表
    getList() {
      this.loading = true;
      this.formValidate.tab_id = this.$route.params.id;
      configTabListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.classList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    pageChange(index) {
      this.formValidate.page = index;
      this.getList();
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `/setting/config/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.classList.splice(num, 1);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 修改是否显示
    onchangeIsShow(row) {
      configSetStatusApi(row.id, row.status)
        .then(async (res) => {
          this.$message.success(res.msg);
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
  },
};
</script>
<style scoped lang="stylus">
.tabsName {
  margin-bottom: 15px;
}

.valBox {
  margin: 10px 0;
}

.valPicbox {
  border: 1px solid #e7eaec;
}

.valPicbox_pic {
  width: 200px;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;

  img {
    width: 100%;
    height: 100%;
  }

  >>> .ivu-icon-md-document {
    font-size: 70px;
    color: #dadada;
  }
}

.valPicbox_sp {
  display: block;
  font-size: 12px;
  width: 200px;
  padding: 7px;
  box-sizing: border-box;
  border-top: 1px solid #e7eaec;
}
</style>
