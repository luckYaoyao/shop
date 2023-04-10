<template>
  <div>
    <div class="message">
      <Card :bordered="false" dis-hover class="">
        <Tabs v-model="currentTab" @on-click="changeTab">
          <TabPane :label="item.label" :name="item.value.toString()" v-for="(item, index) in headerList" :key="index" />
        </Tabs>
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == 'foundation'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <FoundationForm :foundation="formItem.foundation" />
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == 'storage'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <StorageLoc :storage="formItem.storage" />
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == 'field'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <Field :field="formItem.field" />
      </Card>
    </div>
    <div class="pt10" v-show="currentTab == 'formItem'">
      <Card :bordered="false" dis-hover class="ivu-mt">
        <FormItem :formItem="formItem.formItem" />
      </Card>
    </div>
  </div>
</template>

<script>
import { codeCurd } from '@/api/setting';
import FoundationForm from './components/FoundationForm.vue';
import StorageLoc from './components/StorageLoc.vue';
import Field from './components/Field.vue';
import FormItem from './components/FormItem.vue';
export default {
  name: 'system_code_generation',
  components: { FoundationForm, StorageLoc, Field, FormItem },
  data() {
    return {
      currentTab: 'formItem',
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
          required: 0,
          sql: '',
        },
        storage: {},
        field: {},
        formItem: {},
      },
      ruleValidate: {
        foundation: {},
      },
    };
  },
  created() {},
  mounted: function () {},
  methods: {
    changeTab(data) {
      this.currentTab = data;
    },
  },
};
</script>
<style scoped></style>
