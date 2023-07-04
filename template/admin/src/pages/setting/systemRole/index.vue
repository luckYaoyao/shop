<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col v-bind="grid">
            <el-form-item label="状态：" label-for="status">
              <el-select v-model="formValidate.status" placeholder="请选择" @change="userSearchs" clearable>
                <el-option value="1" label="显示"></el-option>
                <el-option value="0" label="不显示"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="身份昵称：" label-for="role_name">
              <el-input
                search
                enter-button
                placeholder="请输入身份昵称"
                v-model="formValidate.role_name"
                @on-search="userSearchs"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col v-bind="grid">
            <el-button v-auth="['setting-system_role-add']" type="primary" icon="md-add" @click="add('添加')"
              >添加身份</el-button
            >
          </el-col>
        </el-row>
      </el-form>
      <el-table
        :columns="columns1"
        :data="tableList"
        ref="table"
        class="mt25"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="暂无数据"
        no-filtered-userFrom-text="暂无筛选结果"
      >
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="身份昵称" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.role_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="权限" min-width="1000">
          <template slot-scope="scope">
            <span class="line1">{{ scope.row.rules }}</span>
          </template>
        </el-table-column>
        <el-table-column label="状态" min-width="120">
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
            <a @click="edit(scope.row, '编辑')">编辑</a>
            <el-divider direction="vertical"></el-divider>
            <a @click="del(scope.row, '删除', index)">删除</a>
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
    <!-- 新增编辑-->
    <Modal
      v-model="modals"
      @on-cancel="onCancel"
      scrollable
      footer-hide
      closable
      :title="`${modelTit}身份`"
      :mask-closable="false"
      width="600"
    >
      <el-form
        ref="formInline"
        :model="formInline"
        :rules="ruleValidate"
        label-width="100px"
        :label-position="labelPosition2"
        @submit.native.prevent
      >
        <el-form-item label="身份名称：" label-for="role_name" prop="role_name">
          <el-input placeholder="请输入身份昵称" v-model="formInline.role_name" />
        </el-form-item>
        <el-form-item label="是否开启：" prop="status">
          <el-radio-group v-model="formInline.status">
            <el-radio :label="1">开启</el-radio>
            <el-radio :label="0">关闭</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="权限：">
          <div class="trees-coadd">
            <div class="scollhide">
              <div class="iconlist">
                <Tree :data="menusList" show-checkbox ref="tree"></Tree>
              </div>
            </div>
          </div>
        </el-form-item>
        <Spin size="large" fix v-if="spinShow"></Spin>
        <el-button type="primary" size="large" long @click="handleSubmit('formInline')">提交</el-button>
      </el-form>
    </Modal>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import { roleListApi, roleSetStatusApi, menusListApi, roleCreatApi, roleInfoApi } from '@/api/setting';
export default {
  name: 'systemrRole',
  data() {
    return {
      spinShow: false,
      modals: false,
      total: 0,
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      loading: false,
      formValidate: {
        status: '',
        role_name: '',
        page: 1,
        limit: 20,
      },
      tableList: [],
      formInline: {
        role_name: '',
        status: 0,
        checked_menus: [],
        id: 0,
      },
      menusList: [],
      modelTit: '',
      ruleValidate: {
        role_name: [{ required: true, message: '请输入身份昵称', trigger: 'blur' }],
        status: [{ required: true, type: 'number', message: '请选择是否开启', trigger: 'change' }],
        // checked_menus: [
        //     { required: true, validator: validateStatus, trigger: 'change' }
        // ]
      },
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '75px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'left';
    },
    labelPosition2() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getList();
  },
  methods: {
    // 添加
    add(name) {
      this.formInline.id = 0;
      this.modelTit = name;
      this.modals = true;
      this.getmenusList();
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `setting/role/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$Message.success(res.msg);
          this.tableList.splice(num, 1);
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 修改是否显示
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        status: row.status,
      };
      roleSetStatusApi(data)
        .then(async (res) => {
          this.$Message.success(res.msg);
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 列表
    getList() {
      this.loading = true;
      this.formValidate.status = this.formValidate.status || '';
      roleListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tableList = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 表格搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 编辑
    edit(row, name) {
      this.modelTit = name;
      this.formInline.id = row.id;
      this.modals = true;
      this.rows = row;
      this.getIofo(row);
    },
    // 菜单列表
    getmenusList() {
      this.spinShow = true;
      menusListApi()
        .then(async (res) => {
          let data = res.data;
          this.menusList = data.menus;
          this.menusList.map((item, index) => {
            if (item.title === '主页') {
              // item.checked = true;
              // item.disableCheckbox = true;
              if (item.children.length) {
                item.children.map((v) => {
                  // v.checked = true;
                  // v.disableCheckbox = true;
                });
              }
            }
            item.expand = false;
          });
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$Message.error(res.msg);
        });
    },
    // 详情
    getIofo(row) {
      this.spinShow = true;
      roleInfoApi(row.id)
        .then(async (res) => {
          let data = res.data;
          this.formInline = data.role || this.formInline;
          this.formInline.checked_menus = this.formInline.rules;
          this.tidyRes(data.menus);
          this.spinShow = false;
        })
        .catch((res) => {
          this.spinShow = false;
          this.$Message.error(res.msg);
        });
    },
    tidyRes(menus) {
      let data = [];
      menus.map((menu) => {
        if (menu.title === '主页') {
          menu.checked = true;
          menu.disableCheckbox = true;
          if (menu.children.length) {
            menu.children.map((v) => {
              v.checked = true;
              v.disableCheckbox = true;
            });
          }
          data.push(menu);
        } else {
          data.push(this.initMenu(menu));
        }
      });
      this.$set(this, 'menusList', data);
    },
    initMenu(menu) {
      let data = {},
        checkMenus = ',' + this.formInline.checked_menus + ',';
      data.title = menu.title;
      data.id = menu.id;
      if (menu.children && menu.children.length > 0) {
        data.children = [];
        menu.children.map((child) => {
          data.children.push(this.initMenu(child));
        });
      } else {
        data.checked = checkMenus.indexOf(String(',' + data.id + ',')) !== -1;
        data.expand = !data.checked;
      }
      return data;
    },
    // 提交
    handleSubmit(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.formInline.checked_menus = [];
          this.$refs.tree.getCheckedAndIndeterminateNodes().map((node) => {
            this.formInline.checked_menus.push(node.id);
          });
          if (this.formInline.checked_menus.length === 0) return this.$Message.warning('请至少选择一个权限');
          roleCreatApi(this.formInline)
            .then(async (res) => {
              this.$Message.success(res.msg);
              this.modals = false;
              this.getList();
              this.$refs[name].resetFields();
              this.formInline.checked_menus = [];
            })
            .catch((res) => {
              this.$Message.error(res.msg);
            });
        } else {
          return false;
        }
      });
    },
    onCancel() {
      this.$refs['formInline'].resetFields();
      this.formInline.checked_menus = [];
    },
  },
};
</script>

<style scoped lang="stylus">
.trees-coadd
    width: 100%;
    height: 385px;
    .scollhide
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        overflow-y: scroll;
       // margin-left: 18px;
.scollhide::-webkit-scrollbar {
    display: none;
}
</style>
