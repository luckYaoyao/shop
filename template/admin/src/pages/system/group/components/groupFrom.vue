<template>
  <div>
    <el-dialog :visible.sync="modals" width="850" :title="titleFrom" :close-on-click-modal="false">
      <el-form
        ref="formValidate"
        :model="formValidate"
        label-width="100px"
        :rules="ruleValidate"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24">
            <el-form-item label="数据组名称：" prop="name">
              <el-input v-model="formValidate.name" placeholder="请输入数据组名称" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="数据字段：" prop="config_name">
              <el-input v-model="formValidate.config_name" placeholder="请输入数据字段" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="数据简介：" prop="info">
              <el-input v-model="formValidate.info" placeholder="请输入数据简介" style="width: 90%"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item label="数类型：" prop="cate_id">
              <el-radio-group v-model="formValidate.cate_id">
                <el-radio :label="0">默认</el-radio>
                <el-radio :label="1">数据</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="24" v-for="(item, index) in formValidate.typelist" :key="index">
            <el-col v-bind="grid">
              <el-form-item
                :label="'字段' + (index + 1) + '：'"
                :prop="'typelist.' + index + '.name.value'"
                :rules="{ required: true, message: '请输入字段名称：姓名', trigger: 'blur' }"
              >
                <el-input v-model="item.name.value" placeholder="字段名称：姓名"></el-input>
              </el-form-item>
            </el-col>
            <el-col v-bind="grid" class="goupBox">
              <el-form-item
                :prop="'typelist.' + index + '.title.value'"
                :rules="{ required: true, message: '请输入字段配置名', trigger: 'blur' }"
              >
                <el-input v-model="item.title.value" placeholder="字段配置名：name"></el-input>
              </el-form-item>
            </el-col>
            <el-col v-bind="grid" prop="type" class="goupBox mr15">
              <el-form-item
                :prop="'typelist.' + index + '.type.value'"
                :rules="{ required: true, message: '请选择字段类型', trigger: 'change' }"
              >
                <i-select placeholder="字段类型" v-model="item.type.value">
                  <i-option value="input">文本框</i-option>
                  <i-option value="textarea">多行文本框</i-option>
                  <i-option value="radio">单选框</i-option>
                  <i-option value="checkbox">多选框</i-option>
                  <i-option value="select">下拉选择</i-option>
                  <i-option value="upload">单图</i-option>
                  <i-option value="uploads">多图</i-option>
                </i-select>
              </el-form-item>
            </el-col>
            <el-col span="1">
              <Icon type="ios-close-circle-outline" class="cur" @click="delGroup(index)" />
            </el-col>
            <el-col
              :span="24"
              v-if="item.type.value === 'radio' || item.type.value === 'checkbox' || item.type.value === 'select'"
            >
              <el-form-item
                :prop="'typelist.' + index + '.param.value'"
                :rules="{ required: true, message: '请输入参数方式', trigger: 'blur' }"
              >
                <el-input
                  type="textarea"
                  :rows="4"
                  :placeholder="item.param.placeholder"
                  v-model="item.param.value"
                  style="width: 90%"
                ></el-input>
              </el-form-item>
            </el-col>
          </el-col>
          <el-col>
            <el-form-item>
              <el-button type="primary" @click="addType">添加字段</el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="handleReset">取 消</el-button>
        <el-button type="primary" @click="handleSubmit('formValidate')" :disabled="valids">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { groupAddApi, groupInfoApi } from '@/api/system';
export default {
  name: 'menusFrom',
  props: {
    groupId: {
      type: Number,
      default: 0,
    },
    titleFrom: {
      type: String,
      default: '',
    },
    addId: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      iconVal: '',
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      modals: false,
      modal12: false,
      ruleValidate: {
        name: [{ required: true, message: '请输入数据组名称', trigger: 'blur' }],
        config_name: [{ required: true, message: '请输入数据字段', trigger: 'blur' }],
        info: [{ required: true, message: '请输入数据简介', trigger: 'blur' }],
        names: [{ required: true, message: '请输入字段名称', trigger: 'blur' }],
      },
      FromData: [],
      valids: false,
      list2: [],
      formValidate: {
        name: '',
        config_name: '',
        info: '',
        typelist: [],
        cate_id: 0,
      },
    };
  },
  watch: {
    addId(n) {
      if (n === 'addId') {
        this.formValidate.typelist = [];
      }
    },
  },
  methods: {
    // 点击添加字段
    addType() {
      this.formValidate.typelist.push({
        name: {
          value: '',
        },
        title: {
          value: '',
        },
        type: {
          value: '',
        },
        param: {
          placeholder: '参数方式例如:\n1=白色\n2=红色\n3=黑色',
          value: '',
        },
      });
    },
    // 删除字段
    delGroup(index) {
      this.formValidate.typelist.splice(index, 1);
    },
    // 详情
    fromData(id) {
      groupInfoApi(id)
        .then(async (res) => {
          this.formValidate = res.data.info;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 提交
    handleSubmit(name) {
      let data = {
        url: this.groupId ? `/setting/group/${this.groupId}` : 'setting/group',
        method: this.groupId ? 'put' : 'post',
        datas: this.formValidate,
      };
      this.$refs[name].validate((valid) => {
        if (valid) {
          if (this.formValidate.typelist.length === 0) return this.$message.error('请添加字段名称：姓名！');
          groupAddApi(data)
            .then(async (res) => {
              this.$message.success(res.msg);
              this.modals = false;
              this.$refs[name].resetFields();
              this.formValidate.typelist = [];
              this.$emit('getList');
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          if (!this.formValidate.name) return this.$message.error('请添加数据组名称！');
          if (!this.formValidate.config_name) return this.$message.error('请添加数据字段！');
          if (!this.formValidate.info) return this.$message.error('请添加数据简介！');
        }
      });
    },
    handleReset() {
      this.modals = false;
      this.$refs['formValidate'].resetFields();
      this.$emit('clearFrom');
    },
  },
  created() {},
  mounted() {},
};
</script>

<style scoped lang="stylus">
.cur
   cursor pointer
.goupBox >>> .ivu-form-item-content
   margin-left: 43px!important;
</style>
