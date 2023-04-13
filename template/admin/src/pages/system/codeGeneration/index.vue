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
    <div class="pt10" v-show="currentTab == '0'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <FoundationForm :foundation="formItem.foundation" :dataList="dataList" @addRow="addRow" />
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == '1'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <StorageLoc :storage="formItem.storage" />
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == '2'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <Field ref="Field" :field="formItem.field" :rowList="rowList" />
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == '3'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <FormItem ref="FormItem" :formItem="formItem.formItem" :rowList="rowList" />
      </Card>
    </div>
    <Card :bordered="false" dis-hover class="mt10 btn">
      <Button class="mr20" type="primary" @click="beforeTab">上一步</Button>
      <Button type="primary" @click="nextTab">{{ currentTab == 3 ? '提交' : '下一步' }}</Button>
    </Card>
  </div>
</template>

<script>
import { codeCrud } from '@/api/setting';
import StorageLoc from './components/StorageLoc.vue';
import FoundationForm from './components/FoundationFor.vue';
import Field from './components/Field.vue';
import FormItem from './components/FormItem.vue';
import { crudFilePath } from '@/api/systemCodeGeneration';
export default {
  name: 'system_code_generation',
  components: { FoundationForm, StorageLoc, Field, FormItem },
  data() {
    return {
      currentTab: 0,
      headerList: [
        { label: '基础信息', value: 'foundation' },
        { label: '存放位置', value: 'storage' },
        { label: '表格字段', value: 'field' },
        { label: '表单项', value: 'formItem' },
      ],
      formItem: {
        foundation: {
          pid: '',
          tableName: '',
          isTable: 0,
          menuName: '',
        },
        storage: {},
        field: {},
        formItem: {},
      },
      ruleValidate: {
        foundation: {},
      },
      dataList: [
        {
          type: '',
          limit: 0,
          comment: '',
          field: '',
          index: 0,
        },
      ],
      rowList: [],
    };
  },
  created() {},
  mounted: function () {},
  methods: {
    addRow() {
      this.dataList.push({
        type: '',
        limit: 0,
        default: '',
        field: '',
        index: 0,
      });
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
          for (let i = 0; i < this.dataList.length; i++) {
            const e = this.dataList[i];
            if (!e.type || !e.field || !e.comment) {
              return this.$Message.warning('请完善sql数据');
            }
          }
        }
        let data = {
          menuName: this.formItem.foundation.menuName,
          tableName: this.formItem.foundation.tableName,
          isTable: this.formItem.foundation.isTable,
          fromField: [],
          columnField: [],
        };
        crudFilePath(data)
          .then((res) => {
            console.log(res);
            this.formItem.storage = res.data.makePath;
            if (!this.formItem.foundation.isTable) {
              this.rowList = [];
              this.dataList.map((e) => {
                this.rowList.push({
                  label: e.field,
                  value: e.field,
                });
              });
            } else {
              this.rowList = res.data.tableField;
            }
            this.currentTab++;
          })
          .catch((err) => {
            this.$Message.error(err.msg);
          });
      } else if (this.currentTab == 2) {
        for (let i = 0; i < this.$refs.Field.dataList.length; i++) {
          const e = this.$refs.Field.dataList[i];
          if (!e.name || !e.field) {
            return this.$Message.warning('请完善表数据');
          }
        }
        this.currentTab++;
      } else if (this.currentTab == 3) {
        try {
          for (let i = 0; i < this.$refs.FormItem.dataList.length; i++) {
            const e = this.$refs.FormItem.dataList[i];
            if (!e.name || !e.type || !e.field || !e.required) {
              return this.$Message.warning('请完善数据');
            }
          }
          let FieldList = this.$refs.Field.dataList;
          let data = {
            ...this.formItem.foundation,
            filePath: this.formItem.storage,
            tableField: this.dataList,
            columnField: this.$refs.Field.dataList,
            fromField: this.$refs.FormItem.dataList,
          };
          console.log(data);
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
  display: flex;
  justify-content: center;
  align-items: center;
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
