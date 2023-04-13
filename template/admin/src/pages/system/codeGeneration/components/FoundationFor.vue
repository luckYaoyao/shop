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
        <div class="tip">可选项，选择的菜单成功后会自动写入到此菜单下</div>
      </FormItem>
      <FormItem label="菜单名称">
        <Input class="form-width" v-model="foundation.menuName" placeholder="请输入表名"></Input>
        <div class="tip">生成菜单为可选项，不填写默认生成的菜单名称将为表名；生成后会把自动生成的权限默认加入该菜单下</div>
      </FormItem>
      <FormItem label="表名">
        <Input class="form-width" v-model="foundation.tableName" placeholder="请输入表名"></Input>
        <div class="tip">用于生成CRUD指定的表名，不需要携带表前缀；对于生成过的表将不能在进行生成；或者可以删除对应的文件重新生成！对应系统中重要的数据表将不允许生成！</div>
      </FormItem>
      <FormItem label="是否存在">
        <RadioGroup v-model="foundation.isTable">
          <Radio :label="1">是</Radio>
          <Radio :label="0">否</Radio>
        </RadioGroup>
        <div class="tip">数据库表可以生成系统存在的，也可以选择【否】后手动生成，不过此生成方式不交单一；如果不满足使用需求可以先在数据库中创建表，然后选择【是】再进行操作</div>
      </FormItem>
      <FormItem label="表SQL" v-if="!foundation.isTable">
        <div>
          <div class="item" v-for="(item, index) in dataList" :key="index">
            <div class="row">
              <Select v-model="item.type" transfer>
                <Option v-for="item in columnTypeList" :value="item" :key="item">{{ item }}</Option>
              </Select>
              <div class="tip">字段类型</div>
            </div>
            <div class="row">
              <Input v-model="item.field" class="priceBox" placeholder="字段名称(英文或_)"></Input>
              <div class="tip">字段名称为英文加下划线</div>
            </div>
            <div class="row">
              <Input v-model="item.limit" type="number" class="priceBox" placeholder="长度"></Input>
              <div class="tip">字段长度</div>
            </div>
            <div class="row">
              <Select v-model="item.index" transfer placeholder="是否为索引">
                <Option :value="0">否</Option>
                <Option :value="1">是</Option>
              </Select>
              <div class="tip">是否为索引</div>
            </div>
            <div class="row">
              <Input v-model="item.comment" class="priceBox" placeholder="备注"></Input>
              <div class="tip">字段备注</div>
            </div>
          </div>
        </div>
        <Button class="mr10" type="primary" @click="addRow">添加字段</Button>
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
