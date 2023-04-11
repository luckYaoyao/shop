<template>
  <div>
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
        <Field :field="formItem.field" />
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == '3'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <FormItem :formItem="formItem.formItem" />
      </Card>
    </div>
    <Card :bordered="false" dis-hover class="mt10">
      <Button class="mr20" type="primary" @click="beforeTab">上一步</Button>
      <Button type="primary" @click="nextTab">下一步</Button>
    </Card>
  </div>
</template>

<script>
import { codeCurd } from '@/api/setting';
import FoundationForm from './components/FoundationForm.vue';
import StorageLoc from './components/StorageLoc.vue';
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
          default: '',
          field: '',
        },
      ],
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
      });
    },
    beforeTab() {
      this.currentTab--;
    },
    nextTab() {
      if (this.currentTab == 0) {
        console.log(this.formItem);
        let data = {
          menuName: this.formItem.foundation.menuName,
          tableName: this.formItem.foundation.tableName,
          isTable: this.formItem.foundation.isTable,
          fromField: [],
          columnField: [],
        };
        crudFilePath(data).then((res) => {
          console.log(res);
        });
      }
      this.currentTab++;
    },
  },
};
</script>
<style lang="scss" scoped></style>
