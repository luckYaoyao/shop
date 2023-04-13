<template>
  <div class="main">
    <Alert type="warning" closable>表单项添加的内容将会展示在生成页面后自动生成的表单里面；
      编辑和新增都会调用此表单；生成后会新增对应的表单规则代码；可以为空不填写，生成后由开发者自行编写</Alert>
    <div class="mb20">
      <Button class="mr10" type="primary" @click="addRow">添加表单项</Button>
    </div>
    <div>
      <div class="item" v-for="(item, index) in dataList" :key="index">
        <div class="row">
          <Select v-model="item.name" transfer>
            <Option v-for="item in rowList" :value="item.value" :key="item.value">{{ item.label }}</Option>
          </Select>
        </div>
        <div class="row">
          <Input v-model="item.field" class="priceBox"></Input>
        </div>
        <div class="row">
          <Select v-model="item.type" transfer>
            <Option v-for="item in reqMetList" :value="item.value" :key="item.value">{{ item.label }}</Option>
          </Select>
        </div>
        <div class="row">
          <Select v-model="item.required" transfer>
            <Option v-for="item in requiredList" :value="item.value" :key="item.value">{{ item.label }}</Option>
          </Select>
        </div>
        <div class="row" v-if="item.type == 2">
          <Input v-model="item.textarea" class="priceBox"></Input>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: '',
  props: {
    formItem: {
      type: Object,
      default: () => {
        return {};
      },
    },
    rowList: {
      type: Array,
      default: () => {
        return [];
      },
    },
  },
  data() {
    return {
      requiredList: [
        {
          value: 0,
          label: '否',
        },
        {
          value: 1,
          label: '是',
        },
      ],
      reqMetList: [
        {
          value: 0,
          label: 'input',
        },
        {
          value: 1,
          label: 'textarea',
        },
        {
          value: 2,
          label: 'select',
        },
        {
          value: 3,
          label: 'radio',
        },
        {
          value: 4,
          label: 'number',
        },
      ],
      dataList: [],
    };
  },
  created() {},
  mounted() {},
  methods: {
    addRow() {
      this.dataList.push({
        name: '',
        type: '',
        field: '',
        required: 0,
        textarea: '',
      });
    },
  },
};
</script>
<style lang="stylus" scoped>
.ivu-table-wrapper {
  border-top: 1px solid #dcdee2;;
  border-left: 1px solid #dcdee2;;
}
.form-width {
  width: 500px;
}
.item{
  display flex
  margin-bottom 10px
  .row{
    width 140px
    margin-right 10px
  }
}
</style>
