<template>
  <div class="main">
    <Alert closable>
      crud生成说明
      <template #desc>
        <p>1、字段配置中表存在生成的字段为表内列的信息,并且主键、伪删除字段不允许设置为列，主键默认展示在列表中，伪删除字段不允许展示</p>
        <p>2、在字段配置中新建表时，字段类型为addTimestamps会自动创建create_time、update_time字段，字段类型为：timestamp</p>
        <p>3、在字段配置中新建表时，字段类型为addSoftDelete会字段创建delete_time字段，字段类型为：timestamp，作用是伪删除</p>
        <p>4、在字段配置中，表单类型为frameImageOne时属于图片单选，frameImages时为图片多选</p>
        <p>5、在字段配置中，表单类型为不生成时创建后不会生成对应的表单项</p>
      </template>
    </Alert>
    <Form ref="foundation" :model="foundation" :rules="foundationRules" :label-width="100">
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
        <div class="tip">选项，选择的菜单成功后会自动写入到此菜单下</div>
      </FormItem>
      <FormItem label="菜单名称">
        <Input class="form-width" v-model="foundation.menuName" placeholder="请输入表名"></Input>
        <div class="tip">
          生成菜单为可选项，不填写默认生成的菜单名称将为表名；生成后会把自动生成的权限默认加入该菜单下
        </div>
      </FormItem>
      <FormItem label="表名" prop="tableName">
        <Input class="form-width" v-model="foundation.tableName" placeholder="请输入表名" @on-blur="initfield"></Input>
        <div class="tip">
          用于生成CRUD指定的表名，不需要携带表前缀；对于生成过的表将不能在进行生成；或者可以删除对应的文件重新生成！对应系统中重要的数据表将不允许生成！
        </div>
      </FormItem>
      <FormItem label="是否存在">
        <RadioGroup v-model="foundation.isTable" @on-change="changeRadio">
          <Radio :label="1">是</Radio>
          <Radio :label="0">否</Radio>
        </RadioGroup>
        <div class="tip">
          数据库表可以生成系统存在的，也可以选择【否】后手动生成；如果不满足使用需求可以先在数据库中创建表，然后选择【是】再进行操作
        </div>
      </FormItem>
      <FormItem label="字段配置">
        <Button type="primary" @click="addRow">{{ foundation.isTable ? '生成字段' : '添加一行' }}</Button>
        <div>
          <Table
            ref="selection"
            :columns="columns"
            :data="tableField"
            no-data-text="暂无数据"
            highlight-row
            :loading="loading"
            max-height="600"
            size="small"
            no-filtered-data-text="暂无筛选结果"
          >
            <template slot-scope="{ row, index }" slot="field">
              <span v-if="foundation.isTable">{{ row.field }}</span>
              <Input
                v-else
                :disabled="['addTimestamps', 'addSoftDelete'].includes(tableField[index].file_type)"
                v-model="tableField[index].field"
              ></Input>
            </template>
            <template slot-scope="{ row, index }" slot="file_type">
              <span v-if="foundation.isTable">{{ row.file_type }}</span>
              <Select v-else v-model="tableField[index].file_type" @on-change="changeItemField($event, index)">
                <Option v-for="item in columnTypeList" :value="item" :key="item">{{ item }}</Option>
              </Select>
            </template>
            <template slot-scope="{ row, index }" slot="limit">
              <span v-if="foundation.isTable">{{ row.limit }}</span>
              <Input
                v-else
                v-model="tableField[index].limit"
                :disabled="disabledInput(index)"
              ></Input>
            </template>
            <template slot-scope="{ row, index }" slot="default">
              <span v-if="foundation.isTable">{{ row.default }}</span>
              <Input
                v-else
                v-model="tableField[index].default"
                :disabled="disabledInput(index)"
              ></Input>
            </template>
            <template slot-scope="{ row, index }" slot="comment">
              <span v-if="foundation.isTable">{{ row.comment }}</span>
              <Input
                v-else
                v-model="tableField[index].comment"
                :disabled="disabledInput(index)"
              ></Input>
            </template>
            <template slot-scope="{ row, index }" slot="required">
              <Checkbox
                v-model="tableField[index].required"
                :disabled="disabledInput(index)"
              ></Checkbox>
            </template>
            <template slot-scope="{ row, index }" slot="is_table">
              <Checkbox
                v-model="tableField[index].is_table"
                :disabled="disabledInput(index)"
              ></Checkbox>
            </template>
            <template slot-scope="{ row, index }" slot="table_name">
              <Input
                v-model="tableField[index].table_name"
                :disabled="disabledInput(index)"
              ></Input>
            </template>
            <template slot-scope="{ row, index }" slot="from_type">
              <Select
                v-model="tableField[index].from_type"
                :disabled="disabledInput(index)"
              >
                <Option v-for="item in fromTypeList" :value="item.value" :key="item.value">{{ item.label }}</Option>
              </Select>
            </template>
            <template slot-scope="{ row, index }" slot="action">
              <a v-if="!foundation.isTable" @click="del(row, index)">删除</a>
              <div v-else>无</div>
            </template>
          </Table>
        </div>
      </FormItem>
    </Form>
  </div>
</template>

<script>
import { crudMenus, crudColumnType, crudFilePath } from '@/api/systemCodeGeneration';

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
    return {
      foundationRules: {
        // pid: [{ required: true, message: '请输入菜单', trigger: 'blur' }],
        tableName: [{ required: true, message: '请输入表名', trigger: 'blur' }],
      },
      menusList: [],
      columnTypeList: [],
      columns: [
        {
          title: '字段名称',
          slot: 'field',
          minWidth: 100,
        },
        {
          title: '字段类型',
          slot: 'file_type',
          minWidth: 100,
        },
        {
          title: '长度',
          slot: 'limit',
          minWidth: 100,
        },
        {
          title: '默认值',
          slot: 'default',
          minWidth: 100,
        },
        {
          title: '字段描述',
          slot: 'comment',
          minWidth: 100,
        },
        {
          title: '列表',
          slot: 'is_table',
          width: 70,
          align: 'center',
        },
        {
          title: '列表名',
          slot: 'table_name',
          minWidth: 120,
          align: 'center',
        },
        {
          title: '表单类型',
          slot: 'from_type',
          minWidth: 100,
        },
        {
          title: '必填',
          slot: 'required',
          width: 70,
          align: 'center',
        },
        {
          title: '操作',
          slot: 'action',
          // fixed: 'right',
          minWidth: 120,
        },
      ],
      fromTypeList: [
        {
          value: '0',
          label: '不生成',
        },
        {
          value: 'input',
          label: 'input',
        },
        {
          value: 'textarea',
          label: 'textarea',
        },
        {
          value: 'select',
          label: 'select',
        },
        {
          value: 'radio',
          label: 'radio',
        },
        {
          value: 'number',
          label: 'number',
        },
        {
          value: 'frameImageOne',
          label: 'frameImageOne',
        },
        {
          value: 'frameImages',
          label: 'frameImages',
        },
      ],
      loading: false,
      tableField: [],
    };
  },
  created() {
    this.getCrudMenus();
  },
  mounted() {},
  methods: {
    disabledInput(index){
      let fieldInfo = this.tableField[index];
      let res = ['addTimestamps', 'addSoftDelete'].includes(this.tableField[index].file_type)
      if (fieldInfo.primaryKey) {
        res = true;
      }
      if (fieldInfo.field==='delete_time' && fieldInfo.file_type === 'timestamp') {
        res = true;
      }
      return res;
    },
    initfield() {
      this.tableField = [];
    },
    changeItemField(e, i) {
      if (e === 'addSoftDelete') {
        this.$set(this.tableField[i], 'comment', '伪删除');
      }
      if (e === 'addTimestamps') {
        this.$set(this.tableField[i], 'comment', '添加和修改时间');
      }
    },
    changeRadio(status) {
      this.tableField = [];
      if (status) {
        this.addRow();
      }
    },
    addRow() {
      if (!this.foundation.tableName) return this.$Message.warning('请先填写表名');
      if (!this.tableField.length) {
        let data = {
          menuName: this.foundation.menuName,
          tableName: this.foundation.tableName,
          isTable: this.foundation.isTable,
          fromField: [],
          columnField: [],
        };
        this.loading = true;
        crudFilePath(data)
          .then((res) => {
            this.tableField = res.data.tableField.length ? res.data.tableField : [];
            this.$emit('storageData', res.data.makePath);
            this.loading = false;
            if (!this.tableField.length) {
              this.tableField.push({
                field: '',
                file_type: '',
                default: '',
                comment: '',
                required: false,
                is_table: true,
                table_name: '',
                limit: '',
                from_type: '0',
              });
            }
          })
          .catch((err) => {
            this.$Message.warning(err.msg);
            this.loading = false;
          });
      }
      if (this.foundation.isTable) {
      } else {
        console.log(this.tableField);
        for (let i = 0; i < this.tableField.length; i++) {
          const el = this.tableField[i];
          if (
            (!el.field || !el.file_type || !el.comment) &&
            !['addTimestamps', 'addSoftDelete'].includes(el.file_type)
          ) {
            return this.$Message.warning('请先完善上一条数据');
          }
          if (el.is_table && !el.table_name && !['addTimestamps', 'addSoftDelete'].includes(el.file_type)) {
            return this.$Message.warning('请输入列表名');
          }
        }
        this.tableField.push({
          field: '',
          file_type: '',
          default: '',
          comment: '',
          required: false,
          is_table: true,
          table_name: '',
          limit: '',
          from_type: '0',
        });
      }
    },
    getCrudMenus() {
      crudMenus().then((res) => {
        console.log(res);
        this.menusList = res.data;
      });
      crudColumnType().then((res) => {
        this.columnTypeList = res.data.types;
      });
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
