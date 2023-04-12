<template>
  <div class="main">
    <Form ref="foundation" :model="foundation" :rules="foundation" :label-width="100">
      <FormItem label="菜单">
        <!-- <Select class="form-width" v-model="foundation.pid">
          <Option value="beijing">New York</Option>
          <Option value="shanghai">London</Option>
          <Option value="shenzhen">Sydney</Option>
        </Select> -->
        <el-cascader
          class="form-width"
          v-model="foundation.pid"
          size="small"
          :options="menusList"
          :props="{ checkStrictly: true, multiple: false, emitPath: false }"
          clearable
        ></el-cascader>
      </FormItem>
      <FormItem label="菜单名称">
        <Input class="form-width" v-model="foundation.menuName" placeholder="请输入表名"></Input>
      </FormItem>
      <FormItem label="表名">
        <Input class="form-width" v-model="foundation.tableName" placeholder="请输入表名"></Input>
      </FormItem>
      <FormItem label="是否存在">
        <RadioGroup v-model="foundation.isTable">
          <Radio :label="0">否</Radio>
          <Radio :label="1">是</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="表SQL" v-if="!foundation.isTable">
        <div>
          <div class="item" v-for="(item, index) in dataList" :key="index">
            <div class="row">
              <Select v-model="item.type" transfer>
                <Option v-for="item in columnTypeList" :value="item" :key="item">{{ item }}</Option>
              </Select>
            </div>
            <div class="row">
              <Input v-model="item.field" class="priceBox" placeholder="字段名称(英文或_)"></Input>
            </div>
            <div class="row">
              <Input v-model="item.limit" type="number" class="priceBox" placeholder="长度"></Input>
            </div>
            <div class="row">
              <Select v-model="item.index" transfer placeholder="是否为索引">
                <Option :value="0">否</Option>
                <Option :value="1">是</Option>
              </Select>
            </div>
            <div class="row">
              <Input v-model="item.comment" class="priceBox" placeholder="备注"></Input>
            </div>
          </div>
        </div>
        <Button class="mr10" type="primary" @click="addRow">添加行</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
import { crudMenus, crudColumnType } from '@/api/systemCodeGeneration';

export default {
  name: '',
  props: {
    foundation: {
      type: Object,
      default: () => {
        return {};
      },
    },
    dataList: {
      type: Array,
      default: () => {
        return [];
      },
    },
  },
  data() {
    return {
      menusList: [],
      columnTypeList: [],
    };
  },
  created() {
    this.getCrudMenus();
  },
  mounted() {},
  methods: {
    getCrudMenus() {
      crudMenus().then((res) => {
        console.log(res);
        this.menusList = res.data;
      });
      crudColumnType().then((res) => {
        this.columnTypeList = res.data.types;
      });
    },
    addRow() {
      this.$emit('addRow');
    },
  },
};
</script>
<style lang="stylus" scoped>
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
