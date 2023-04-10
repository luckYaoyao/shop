<template>
  <div class="main">
    <Form ref="foundation" :model="foundation" :rules="foundation" :label-width="100">
      <FormItem label="菜单">
        <Select class="form-width" v-model="foundation.pid">
          <Option value="beijing">New York</Option>
          <Option value="shanghai">London</Option>
          <Option value="shenzhen">Sydney</Option>
        </Select>
      </FormItem>
      <FormItem label="表名">
        <Input class="form-width" v-model="foundation.tableName" placeholder="请输入表名"></Input>
      </FormItem>
      <FormItem label="是否存在">
        <RadioGroup v-model="foundation.required">
          <Radio :label="1">是</Radio>
          <Radio :label="0">否</Radio>
        </RadioGroup>
      </FormItem>
      <FormItem label="表SQL">
        <Input
          class="form-width"
          v-model="foundation.sql"
          type="textarea"
          :autosize="{ minRows: 4, maxRows: 8 }"
          placeholder="请输入表SQL"
        ></Input>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: '',
  props: {
    foundation: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {};
  },
  created() {},
  mounted() {},
  methods: {},
};
</script>
<style lang="stylus" scoped>
.form-width {
  width: 500px;
}
</style>
