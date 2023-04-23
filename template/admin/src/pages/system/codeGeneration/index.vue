<template>
  <div class="code-wapper">
    <div class="i-layout-page-header header-title">
      <div class="fl_header">
        <router-link :to="{ path: $routeProStr + '/system/code_generation_list' }"
          ><Button icon="ios-arrow-back" size="small" type="text">返回</Button></router-link
        >
        <Divider type="vertical" />
        <span class="ivu-page-header-title mr20" style="padding: 0">添加功能</span>
      </div>
    </div>
    <div class="message">
      <Card :bordered="false" dis-hover class="">
        <Steps :current="currentTab">
          <Step :title="item.label" v-for="(item, index) in headerList" :key="index"></Step>
        </Steps>
      </Card>
    </div>
    <div class="pt10 tab-1" v-show="currentTab == '0'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <FoundationForm
          ref="Foundation"
          :foundation="formItem.foundation"
          :tableField="tableField"
          @storageData="storageData"
        />
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == '1'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <StorageLoc :storage="formItem.storage" />
      </Card>
    </div>
    <Card :bordered="false" class="btn">
      <Button class="mr20" @click="beforeTab">上一步</Button>
      <Button type="primary" @click="nextTab">{{ currentTab == 1 ? '提交' : '下一步' }}</Button>
    </Card>
  </div>
</template>

<script>
import { codeCrud } from '@/api/setting';
import StorageLoc from './components/StorageLoc.vue';
import FoundationForm from './components/FoundationFor.vue';
import { getMenusUnique } from '@/api/systemMenus';
import { formatFlatteningRoutes } from '@/libs/system';

export default {
  name: 'system_code_generation',
  components: { FoundationForm, StorageLoc },
  data() {
    return {
      currentTab: 0,
      headerList: [
        { label: '基础信息', value: 'foundation' },
        { label: '存放位置', value: 'storage' },
      ],
      formItem: {
        foundation: {
          pid: '',
          tableName: '',
          modelName: '',
          isTable: 1,
          menuName: '',
        },
        storage: {},
        field: {},
        formItem: {},
      },
      ruleValidate: {
        foundation: {},
      },
      tableField: [],
      rowList: [],
      reqloading: false,
    };
  },
  created() {},
  mounted: function () {},
  methods: {
    storageData(data) {
      this.formItem.storage = data;
    },
    beforeTab() {
      this.currentTab--;
    },
    nextTab() {
      if (this.currentTab == 0) {
        // if (!this.formItem.foundation.pid) return this.$Message.warning('请选择菜单');
        if (!this.formItem.foundation.tableName) return this.$Message.warning('请输入表名');
        if (!this.formItem.foundation.modelName) return this.$Message.warning('请输入模块名');
        if (!this.formItem.foundation.isTable) {
          if (!this.$refs.Foundation.tableField.length) return this.$Message.warning('请先添加表数据');
          if (this.$refs.Foundation.tableField.length)
            for (let i = 0; i < this.$refs.Foundation.tableField.length; i++) {
              const el = this.$refs.Foundation.tableField[i];
              if (
                ['addSoftDelete', 'addTimestamps'].indexOf(el.field_type) === -1 &&
                (!el.field || !el.field_type || !el.comment)
              ) {
                return this.$Message.warning('请完善sql表数据');
              }
            }
        } else {
          if (!this.$refs.Foundation.tableField.length) return this.$Message.warning('请先生成表数据');
        }
        this.currentTab++;
      } else if (this.currentTab == 1) {
        if (this.reqloading) return;
        let data = {
          ...this.formItem.foundation,
          filePath: this.formItem.storage,
          tableField: this.$refs.Foundation.tableField,
        };
        this.reqloading = true;
        codeCrud(data)
          .then((res) => {
            this.$Message.success(res.msg);
            this.getMenusUnique();
            this.reqloading = false;
            this.$router.push({
              name: 'system_code_generation_list',
            });
          })
          .catch((err) => {
            this.reqloading = false;
            this.$Message.error(err.msg);
          });
      } else {
        if (this.currentTab < 3) this.currentTab++;
      }
    },
    getMenusUnique() {
      getMenusUnique().then((res) => {
        let data = res.data;
        this.$store.commit('userInfo/uniqueAuth', data.uniqueAuth);
        this.$store.commit('menus/getmenusNav', data.menus);
        this.$store.dispatch('routesList/setRoutesList', data.menus);
        let arr = formatFlatteningRoutes(this.$router.options.routes);
        this.formatTwoStageRoutes(arr);
        let routes = formatFlatteningRoutes(data.menus);
        this.$store.commit('menus/setOneLvRoute', routes);
        this.bus.$emit('routesListChange');
      });
    },
    formatTwoStageRoutes(arr) {
      if (arr.length <= 0) return false;
      const newArr = [];
      const cacheList = [];
      arr.forEach((v) => {
        if (v && v.meta && v.meta.keepAlive) {
          newArr.push({ ...v });
          cacheList.push(v.name);
          this.$store.dispatch('keepAliveNames/setCacheKeepAlive', cacheList);
        }
      });
      return newArr;
    },
  },
};
</script>
<style lang="scss" scoped>
.ivu-steps .ivu-steps-title {
  line-height: 26px;
}
.code-wapper {
  padding-bottom: 90px;
}
.btn {
  position: fixed;
  bottom: 10px;
  height: 80px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 88.7%;
  background-color: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(4px);
}
.tab-1 {
  padding-bottom: 100px;
}
/deep/ .el-input__inner {
  padding-left: 7px;
}
/deep/ .ivu-form-item {
  margin-bottom: 17px;
}
/deep/ .ivu-form-item-error-tip {
  padding-top: 2px;
}
/deep/ .tip {
  color: #bbb;
  line-height: 16px;
  padding-top: 5px;
}
</style>
