<template>
  <Modal v-model="modals" scrollable title="备注" class="order_box" :closable="false">
    <el-form ref="formValidate" :model="formValidate" :rules="ruleValidate" label-width="85px" @submit.native.prevent>
      <el-form-item label="备注：" prop="remark">
        <el-input
          v-model="formValidate.remark"
          :maxlength="200"
          show-word-limit
          type="textarea"
          placeholder="请输入备注信息"
          style="width: 100%"
        />
      </el-form-item>
    </el-form>
    <div slot="footer">
      <el-button type="primary" @click="putRemark('formValidate')">提交</el-button>
      <el-button @click="cancel('formValidate')">取消</el-button>
    </div>
  </Modal>
</template>

<script>
export default {
  name: 'orderMark',
  data() {
    return {
      formValidate: {
        remark: '',
      },
      modals: false,
      ruleValidate: {
        remark: [
          { required: true, message: '请输入备注信息', trigger: 'blur' },
          // { type: 'string', min: 20, message: 'Introduce no less than 20 words', trigger: 'blur' }
        ],
      },
    };
  },
  props: {
    remark: {
      default: '',
      type: String,
    },
  },
  methods: {
    cancel(name) {
      this.modals = false;
      this.$refs[name].resetFields();
    },
    putRemark(name) {
      this.$refs[name].validate((valid) => {
        if (valid) {
          this.$emit('submitFail', this.formValidate.remark);
        } else {
          this.$Message.warning('请填写备注信息');
        }
      });
    },
  },
};
</script>

<style scoped></style>
