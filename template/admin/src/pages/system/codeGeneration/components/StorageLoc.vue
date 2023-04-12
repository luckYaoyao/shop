<template>
  <div class="main">
    <Form ref="foundation" :model="storage" :rules="storageRules" :label-width="130">
      <FormItem label="生成api位置">
        <Input class="form-width" v-model="storage.api" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="生成controller位置">
        <Input class="form-width" v-model="storage.controller" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="生成service位置">
        <Input class="form-width" v-model="storage.service" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="生成dao位置">
        <Input class="form-width" v-model="storage.dao" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="生成model位置">
        <Input class="form-width" v-model="storage.model" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="生成pages位置">
        <Input class="form-width" v-model="storage.pages" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="生成route位置">
        <Input class="form-width" v-model="storage.route" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="生成router位置">
        <Input class="form-width" v-model="storage.router" placeholder="请输入"></Input>
      </FormItem>
      <FormItem label="生成validate位置">
        <Input class="form-width" v-model="storage.validate" placeholder="请输入"></Input>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: '',
  props: {
    storage: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      storageRules: {},
    };
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
