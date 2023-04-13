<template>
  <div>
    <div class="i-layout-page-header header-title">
      <div class="fl_header">
        <router-link :to="{ path: $routeProStr + '/system/code_generation_list' }"
          ><Button icon="ios-arrow-back" size="small" type="text">返回</Button></router-link
        >
        <Divider type="vertical" />
        <span class="ivu-page-header-title mr20" style="padding: 0">代码生成</span>
      </div>
    </div>
    <div class="message">
      <Card :bordered="false" dis-hover class="">
        <Steps :current="currentTab">
          <Step :title="item.label" v-for="(item, index) in headerList" :key="index"></Step>
        </Steps>
      </Card>
    </div>
    <div class="pt10  tab-1" v-show="currentTab == '0'">
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
    <Card :bordered="false" dis-hover class="btn">
      <Button class="mr20" @click="beforeTab">上一步</Button>
      <Button type="primary" @click="nextTab">{{ currentTab == 1 ? '提交' : '下一步' }}</Button>
    </Card>
  </div>
</template>

<script>
import { codeCrud } from '@/api/setting';
import StorageLoc from './components/StorageLoc.vue';
import FoundationForm from './components/FoundationFor.vue';
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
        if (!this.formItem.foundation.tableName) {
          return this.$Message.warning('请输入表名');
        }
        if (!this.formItem.foundation.isTable) {
          if (!this.$refs.Foundation.tableField.length) return this.$Message.warning('请先添加表数据');
          if (this.$refs.Foundation.tableField.length)
            for (let i = 0; i < this.$refs.Foundation.tableField.length; i++) {
              const el = this.$refs.Foundation.tableField[i];
              if (!el.field || !el.file_type || !el.default || !el.comment) {
                return this.$Message.warning('请完善sql表数据');
              }
            }
        } else {
          if (!this.$refs.Foundation.tableField.length) return this.$Message.warning('请先生成表数据');
        }
        this.currentTab++;
      } else if (this.currentTab == 1) {
        try {
          let data = {
            ...this.formItem.foundation,
            filePath: this.formItem.storage,
            tableField: this.$refs.Foundation.tableField,
            // columnField: this.$refs.Field.dataList,
            // fromField: this.$refs.FormItem.dataList,
          };
          codeCrud(data)
            .then((res) => {
              this.$Message.success(res.msg);
              this.$router.push({
                name: 'system_code_generation_list',
              });
            })
            .catch((err) => {
              this.$Message.error(err.msg);
            });
        } catch (error) {
          console.log(error);
        }
      } else {
        if (this.currentTab < 3) this.currentTab++;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.ivu-steps .ivu-steps-title {
  line-height: 26px;
}
.btn {
  position: fixed;
  bottom: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 85%;
}
.tab-1 {
  padding-bottom: 100px;
}
/deep/ .el-input__inner {
  padding-left: 7px;
}
/deep/ .ivu-form-item {
  margin-bottom: 10px;
}
/deep/ .tip {
  color: #bbb;
}
</style>
