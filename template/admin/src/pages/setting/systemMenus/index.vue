<template>
  <div>
    <Card :bordered="false" dis-hover class="ivu-mt">
      <Form
          ref="roleData"
          :model="roleData"
          :label-width="labelWidth"
          :label-position="labelPosition"
          @submit.native.prevent
      >
        <Row type="flex" :gutter="24">
          <Col v-bind="grid">
            <FormItem label="规则状态：">
              <Select v-model="roleData.is_show" placeholder="请选择" clearable @on-change="getData">
                <Option value="1">显示</Option>
                <Option value="0">不显示</Option>
              </Select>
            </FormItem>
          </Col>
          <Col v-bind="grid">
            <FormItem label="按钮名称：" prop="status2" label-for="status2">
              <Input v-model="roleData.keyword" search enter-button placeholder="请输入按钮名称" @on-search="getData"/>
            </FormItem>
          </Col>
        </Row>
        <Row type="flex">
          <Col v-bind="grid">
            <Button v-auth="['setting-system_menus-add']" type="primary" @click="menusAdd('添加规则')" icon="md-add"
            >添加规则
            </Button
            >
          </Col>
        </Row>
      </Form>
      <vxe-table
          :border="false"
          class="vxeTable mt25"
          highlight-hover-row
          highlight-current-row
          :loading="loading"
          ref="xTable"
          header-row-class-name="false"
          :tree-config="tabconfig"
          :data="tableData"
          row-id="id"
      >
        <vxe-table-column field="id" title="ID" tooltip min-width="70"></vxe-table-column>
        <vxe-table-column field="menu_name" tree-node title="按钮名称" min-width="200"></vxe-table-column>
        <vxe-table-column field="api_url" title="接口路径" min-width="150">
          <template v-slot="{ row }">
            <span>{{ row.methods ? '[' + row.methods + ']  ' + row.api_url : row.api_url }}</span>
          </template>
        </vxe-table-column>
        <vxe-table-column field="unique_auth" title="前端权限" min-width="300"></vxe-table-column>
        <vxe-table-column field="menu_path" title="页面路由" min-width="240" tooltip="true"></vxe-table-column>
        <vxe-table-column field="flag" title="规则状态" min-width="120">
          <template v-slot="{ row }">
            <i-switch
                v-model="row.is_show"
                :value="row.is_show"
                :true-value="1"
                :false-value="0"
                @on-change="onchangeIsShow(row)"
                size="large"
            >
              <span slot="open">显示</span>
              <span slot="close">隐藏</span>
            </i-switch>
          </template>
        </vxe-table-column>
        <vxe-table-column field="date" title="操作" align="center" width="250" fixed="right">
          <template v-slot="{ row, index }">
            <span v-auth="['setting-system_menus-add']">
              <a @click="addRoute(row)" v-if="row.auth_type === 1">添加权限</a>
              <Divider type="vertical" v-if="row.auth_type === 1"/>
              <a @click="addE(row, '添加子菜单')" v-if="row.auth_type === 1">添加子菜单</a>
              <!-- <a @click="addE(row, '添加规则')" v-else>添加规则</a> -->
            </span>
            <Divider type="vertical" v-if="row.auth_type === 1"/>
            <a @click="edit(row, '编辑')">编辑</a>
            <Divider type="vertical"/>
            <a @click="del(row, '删除规则')">删除</a>
          </template>
        </vxe-table-column>
      </vxe-table>
    </Card>
    <menus-from
        :formValidate="formValidate"
        :titleFrom="titleFrom"
        @getList="getList"
        @changeMenu="getMenusUnique"
        ref="menusFrom"
        @clearFrom="clearFrom"
    ></menus-from>
    <Modal
        v-model="ruleModal"
        scrollable
        width="1100"
        title="权限列表"
        @on-ok="addRouters"
        @on-cancel="ruleModal = false"
        @on-visible-change="modalchange"
    >
      <div class="search-rule">
        <Alert>基础接口，可多选，并且添加后不会再展示出现；删除权限后才会出现；公共接口，可多选，并且添加后会继续展示；</Alert>
        <Input
            class="mr10"
            v-model="searchRule"
            placeholder="输入关键词搜索"
            clearable
            style="width: 300px"
            ref="search"
            @on-enter="searchRules"
            @on-clear="searchRules"
        />
        <Button class="mr10" type="primary" @click="searchRules">搜索</Button>
        <Button @click="init">重置</Button>
      </div>

      <Tabs v-model="routeType" @on-click="changTab">
        <TabPane :label="item.name" :name="''+index" v-for="(item,index) in foundationList"></TabPane>
      </Tabs>
      <div class="rule">
        <div
            class="rule-list"
            v-show="!arrs.length || arrs.includes(item.id)"
            :class="{ 'select-rule': seletRouteIds.includes(item.id) }"
            v-for="(item, index) in children"
            :key="index"
            @click="selectRule(item)"
        >
          <div>接口名称：{{ item.real_name }}</div>
          <div>请求方式：{{ item.method }}</div>
          <div>接口地址：{{ item.path }}</div>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script>
import {mapState} from 'vuex';
import {
  getTable,
  menusDetailsApi,
  isShowApi,
  editMenus,
  getRuleList,
  menusBatch,
  getMenusUnique,
} from '@/api/systemMenus';
import formCreate from '@form-create/iview';
import menusFrom from './components/menusFrom';
import {formatFlatteningRoutes} from '@/libs/system';

export default {
  name: 'systemMenus',
  data() {
    return {
      children: [],
      tabconfig: {children: 'children', reserve: true, accordion: true},
      spinShow: false,
      ruleModal: false,
      searchRule: '',
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      roleData: {
        is_show: '',
        keyword: '',
      },
      loading: false,
      tableData: [],
      FromData: null,
      icons: '',
      formValidate: {},
      titleFrom: '',
      modalTitleSs: '',
      routeType: '0',
      arrs: [],
      foundationList: [], // 基础接口列表
      openList: [], // 公开接口列表
      seletRoute: [], // 选中路由
      seletRouteIds: [], // 选中id
      menusId: 0, // 选中分类id
    };
  },
  components: {menusFrom, formCreate: formCreate.$form()},
  computed: {
    ...mapState('admin/layout', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : 75;
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  mounted() {
    this.getData();
  },
  methods: {
    init() {
      this.searchRule = '';
      this.arrs = [];
      this.seletRouteIds = [];
      this.seletRoute = [];
    },
    addRouters() {
      let data = {
        menus: this.seletRoute,
      };
      menusBatch(data)
          .then((res) => {
            console.log(res);
            this.getData();
          })
          .catch((res) => {
            this.$Message.error(res.msg);
          });
    },
    selectRule(data) {
      if (this.seletRouteIds.includes(data.id)) {
        let i = this.seletRouteIds.findIndex((e) => e == data.id);
        this.seletRouteIds.splice(i, 1);
        this.seletRoute.splice(i, 1);
      } else {
        this.seletRouteIds.push(data.id);
        this.seletRoute.push({
          menu_name: data.name,
          unique_auth: '',
          api_url: data.path,
          path: this.menusId,
          method: data.method,
        });
      }
    },
    changTab(name) {
      this.routeType = name;
      let index = parseInt(name)
      this.children = this.foundationList[index] ? this.foundationList[index].children : []
      this.searchRules();
    },
    // 搜索规则
    searchRules() {
      if (this.searchRule.trim()) {
        this.arrs = [];
        let arr = this.foundationList;
        for (var i = 0; i < arr.length; i++) {
          if (arr[i].real_name.indexOf(this.searchRule) !== -1) {
            this.arrs.push(arr[i].id);
          }
        }
      } else {
        this.arrs = [];
      }
    },
    addRoute(row) {
      this.menusId = row.id;
      this.routeType = '0';
      this.getRuleList();
    },
    modalchange() {
    },
    // 获取权限列表
    getRuleList() {
      getRuleList().then((res) => {
        this.foundationList = res.data;
        this.children = this.foundationList[0] ? this.foundationList[0].children : []
        this.openList = [];
        this.seletRouteIds = [];
        this.seletRoute = [];
        this.ruleModal = true;
      });
    },
    // 修改规则状态
    onchangeIsShow(row) {
      let data = {
        id: row.id,
        is_show: row.is_show,
      };
      isShowApi(data)
          .then(async (res) => {
            this.$Message.success(res.msg);
            this.$store.dispatch('menus/getMenusNavList');
          })
          .catch((res) => {
            this.$Message.error(res.msg);
          });
    },
    // 请求列表
    getList() {
      this.formValidate = Object.assign({}, this.$options.data().formValidate);
      this.getData();
    },

    // 清除表单数据
    clearFrom() {
      this.formValidate = Object.assign({}, this.$options.data().formValidate);
    },
    // 添加子菜单
    addE(row, title) {
      this.formValidate = {};
      let pid = row.id.toString();
      if (pid) {
        menusDetailsApi(row.id)
            .then(async (res) => {
              this.formValidate.path = res.data.path;
              this.formValidate.path.push(row.id);
              this.formValidate.pid = pid;
              this.$refs.menusFrom.modals = true;
              this.$refs.menusFrom.valids = false;
              this.titleFrom = title;
              this.formValidate.auth_type = 1;
              this.formValidate.is_show = 0;
              this.formValidate.is_show_path = 0;
            })
            .catch((res) => {
              this.$Message.error(res.msg);
            });
      } else {
        this.formValidate.pid = pid;
        this.$refs.menusFrom.modals = true;
        this.$refs.menusFrom.valids = false;
        this.titleFrom = title;
        this.formValidate.auth_type = 1;
        this.formValidate.is_show = 0;
        this.formValidate.is_show_path = 0;
      }
      // this.formValidate.pid = row.id.toString();
      // this.$refs.menusFrom.modals = true;
      // this.$refs.menusFrom.valids = false;
      // this.titleFrom = title;
      // this.formValidate.auth_type = 1;
      // this.formValidate.is_show = '0';
    },
    // 删除
    del(row, tit) {
      let delfromData = {
        title: tit,
        url: `/setting/menus/${row.id}`,
        method: 'DELETE',
        ids: '',
      };

      this.$modalSure(delfromData)
          .then((res) => {
            this.$Message.success(res.msg);
            this.getData();
            this.$store.dispatch('menus/getMenusNavList');
          })
          .catch((res) => {
            this.$Message.error(res.msg);
          });
    },
    // 规则详情
    menusDetails(id) {
      menusDetailsApi(id)
          .then(async (res) => {
            this.formValidate = res.data;
            this.$refs.menusFrom.modals = true;
          })
          .catch((res) => {
            this.$Message.error(res.msg);
          });
    },
    // 编辑
    edit(row, title, index) {
      this.formValidate = {};
      this.menusDetails(row.id);
      this.titleFrom = title;
      this.$refs.menusFrom.valids = false;
      this.$refs.menusFrom.getAddFrom(row.id);
    },
    // 添加
    menusAdd(title) {
      this.formValidate = {};
      this.$refs.menusFrom.modals = true;
      this.$refs.menusFrom.valids = false;
      // this.formValidate = Object.assign(this.$data, this.$options.formValidate());
      this.titleFrom = title;
      this.formValidate.auth_type = 1;
      this.formValidate.is_show = 0;
      this.formValidate.is_show_path = 0;
    },
    // 新增页面表单
    // getAddFrom () {
    //     this.spinShow = true;
    //     addMenus(this.roleData).then(async res => {
    //         this.FromData = res.data;
    //         this.$refs.menusFrom.modals = true;
    //         this.spinShow = false;
    //     }).catch(res => {
    //         this.spinShow = false;
    //         this.$Message.error(res.msg);
    //     })
    // },
    // 列表
    getData() {
      this.loading = true;
      this.roleData.is_show = this.roleData.is_show || '';
      getTable(this.roleData)
          .then(async (res) => {
            this.tableData = res.data;
            this.loading = false;
          })
          .catch((res) => {
            this.loading = false;
            this.$Message.error(res.msg);
          });
    },
    getMenusUnique() {
      getMenusUnique().then((res) => {
        let data = res.data;
        this.$store.commit('userInfo/uniqueAuth', data.uniqueAuth);
        this.$store.commit('menus/getmenusNav', data.menus);
        this.$store.dispatch('routesList/setRoutesList', data.menus);
        let arr = formatFlatteningRoutes(this.$router.options.routes);
        this.formatTwoStageRoutes(arr);
      });
    },
    formatTwoStageRoutes(arr) {
      if (arr.length <= 0) return false;
      const newArr = [];
      const cacheList = [];
      arr.forEach((v) => {
        if (v && v.meta && v.meta.keepAlive) {
          newArr.push({...v});
          cacheList.push(v.name);
          this.$store.dispatch('keepAliveNames/setCacheKeepAlive', cacheList);
        }
      });
      return newArr;
    },
    // 关闭按钮
    cancel() {
      this.$emit('onCancel');
    },
  },
};
</script>

<style scoped lang="scss">
.vxeTable {
  > > > .vxe-table--header-wrapper {
    background: #fff !important;
  }
}

.rule {
  display: flex;
  flex-wrap: wrap;
  max-height: 600px;
  overflow-y: scroll;
}

/*定义滚动条高宽及背景 高宽分别对应横竖滚动条的尺寸*/
.rule::-webkit-scrollbar {
  width: 10px;
  height: 10px;
  background-color: #f5f5f5;
}

/*定义滚动条轨道 内阴影+圆角*/
.rule::-webkit-scrollbar-track {
  border-radius: 4px;
  background-color: #f5f5f5;
}

/*定义滑块 内阴影+圆角*/
.rule::-webkit-scrollbar-thumb {
  border-radius: 4px;
  background-color: #ccc;
}

.rule-list {
  background-color: #f2f2f2;
  width: 32%;
  margin: 5px;
  border-radius: 3px;
  padding: 10px;
  color: #333;
  cursor: pointer;
  transition: all 0.1s;
}

.rule-list:hover {
  background-color: #badbfb;
}

.rule-list div {
  white-space: nowrap;
}

.select-rule {
  background-color: #badbfb;
}
</style>
