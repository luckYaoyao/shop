<template>
  <div class="main">
    <el-alert closable>
      crud生成说明
      <template #title>
        <p>
          1、字段配置中表存在生成的字段为表内列的信息,并且主键、伪删除字段不允许设置为列，主键默认展示在列表中，伪删除字段不允许展示
        </p>
        <p>2、在字段配置中新建表时，主键不需要增加列，会自动增加一行主键id</p>
        <p>
          3、在字段配置中新建表时，字段类型为addTimestamps会自动创建create_time、update_time字段，字段类型为：timestamp
        </p>
        <p>
          4、在字段配置中新建表时，字段类型为addSoftDelete会字段创建delete_time字段，字段类型为：timestamp，作用是伪删除
        </p>
        <p>5、在字段配置中，表单类型为不生成时创建后不会生成对应的表单项</p>
        <p>6、添加字段id、create_time、update_time、delete_time为不可用字段</p>
      </template>
    </el-alert>
    <div class="df">
      <el-button class="mr20" type="primary" @click="addRow">添加一行</el-button>
      <el-checkbox class="mr10" v-model="isCreate" @change="addCreate">添加与修改时间</el-checkbox>
      <el-checkbox class="mr10" v-model="isDelete" @change="addDelete">伪删除</el-checkbox>
    </div>
    <div>
      <el-table
        ref="selection"
        :data="tableField"
        empty-text="暂无数据"
        highlight-current-row
        v-loading="loading"
        max-height="600"
        size="small"
      >
        <el-table-column label="字段名称" min-width="100">
          <template slot-scope="scope">
            <el-input
              :disabled="disabledInput(scope.$index)"
              v-model="scope.row.field"
              @blur="changeField(scope.$index)"
            ></el-input>
          </template>
        </el-table-column>
        <el-table-column label="字段类型" min-width="130">
          <template slot-scope="scope">
            <el-select
              v-model="scope.row.field_type"
              :disabled="disabledInput(scope.$index)"
              @change="changeItemField($event, scope.$index)"
            >
              <el-option v-for="item in columnTypeList" :value="item" :key="item" :label="item"></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="长度" min-width="130">
          <template slot-scope="scope">
            <el-input v-model="scope.row.limit" :disabled="disabledInput(scope.$index)"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="默认值" min-width="130">
          <template slot-scope="scope">
            <el-input v-model="scope.row.default" :disabled="disabledInput(scope.$index)"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="字段描述" min-width="130">
          <template slot-scope="scope">
            <el-input
              v-model="scope.row.comment"
              :disabled="disabledInput(scope.$index)"
              @change="(e) => changeComment(e, scope.$index)"
            ></el-input>
          </template>
        </el-table-column>
        <el-table-column label="列表" min-width="130">
          <template slot-scope="scope">
            <el-checkbox v-model="scope.row.is_table" :disabled="disabledInput(scope.$index)"></el-checkbox>
          </template>
        </el-table-column>
        <el-table-column label="列表名" min-width="130">
          <template slot-scope="scope">
            <el-input v-model="scope.row.table_name" :disabled="disabledInput(scope.$index)"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="表单类型" min-width="130">
          <template slot-scope="scope">
            <el-select v-model="scope.row.from_type" :disabled="disabledInput(scope.$index)">
              <el-option
                v-for="item in fromTypeList"
                :value="item.value"
                :key="item.value"
                :label="item.label"
              ></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="字典配置" min-width="130">
          <template slot-scope="scope">
            <div class="table-options" v-if="['select', 'radio', 'checkbox'].includes(scope.row.from_type)">
              <el-select>
                <el-option
                  v-for="item in scope.row.options"
                  :value="item.value"
                  :key="item.value"
                  :label="item.label"
                ></el-option>
              </el-select>
              <Icon class="create" type="md-create" @click="eidtOptions(scope.$index)" />
            </div>
            <div v-else>--</div>
          </template>
        </el-table-column>
        <el-table-column label="必填" min-width="130">
          <template slot-scope="scope">
            <el-checkbox v-model="scope.row.required" :disabled="disabledInput(scope.$index)"></el-checkbox>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="100">
          <template slot-scope="scope">
            <a v-if="!scope.row.primaryKey && !disabledInput(scope.$index)" @click="del(row, scope.$index)">删除</a>
            <span v-else>--</span>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <el-dialog :visible.sync="optionsModal" title="字典配置" :close-on-click-modal="false" width="400px">
      <div class="options-list">
        <div class="item" v-for="(item, index) in optionsList" :key="index">
          <el-input class="mr10" v-model="item.label" placeholder="label" style="width: 150px" />
          <el-input class="mr10" v-model="item.value" placeholder="value" style="width: 150px" />
          <Icon v-if="index == optionsList.length - 1" class="add" type="md-add-circle" @click="addOneOptions" />
          <Icon v-if="index > 0" class="add" type="md-remove-circle" @click="delOneOptions(index)" />
        </div>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="optionsModal = false">取 消</el-button>
        <el-button type="primary" @click="addOptions">确 定</el-button>
      </span>
    </el-dialog>
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
    id: {
      type: String | Number,
    },
  },
  data() {
    return {
      foundationRules: {},
      menusList: [],
      columnTypeList: [],
      optionsModal: false,
      isCreate: false,
      isDelete: false,
      fromTypeList: [],
      loading: false,
      tableField: [],
      optionsList: [],
      index: 0,
      deleteField: [],
    };
  },
  created() {
    this.getCrudMenus();
  },
  mounted() {},
  methods: {
    disabledInput(index) {
      let fieldInfo = this.tableField[index];
      let res = ['addTimestamps', 'addSoftDelete'].includes(this.tableField[index].field_type);
      if (fieldInfo.primaryKey) {
        res = true;
      } else if (fieldInfo.field === 'delete_time' && fieldInfo.field_type === 'timestamp') {
        res = true;
      } else if (
        (fieldInfo.field === 'create_time' || fieldInfo.field === 'update_time') &&
        fieldInfo.field_type === 'timestamp'
      ) {
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
    eidtOptions(i) {
      this.index = i;
      this.optionsList = this.tableField[i].options || [{ label: '', value: '' }];
      this.optionsModal = true;
    },
    addOptions() {
      this.$set(this.tableField[this.index], 'options', this.optionsList);
    },
    changeRadio(status) {
      this.tableField = [];
      if (status) {
        this.addRow();
      }
    },
    initTableName() {
      this.tableField = [];
    },
    addRow() {
      for (let i = 0; i < this.tableField.length; i++) {
        const el = this.tableField[i];
        if ((!el.field || !el.field_type) && !['addTimestamps', 'addSoftDelete'].includes(el.field_type)) {
          return this.$message.warning('请先完善上一条数据');
        }
        if (
          el.is_table &&
          !el.table_name &&
          !Number(el.primaryKey) &&
          !['addTimestamps', 'addSoftDelete'].includes(el.field_type)
        ) {
          return this.$message.warning('请输入列表名');
        }
      }
      let i = this.tableField.length;
      let spliceIndex = 0;
      this.tableField.map((e) => {
        if (e.field === 'create_time' || e.field === 'update_time') {
          spliceIndex++;
        }
        if (e.field === 'delete_time') {
          spliceIndex++;
        }
      });
      i = this.tableField.length - spliceIndex;
      this.tableField.splice(i, 0, {
        field: '',
        field_type: '',
        default: '',
        comment: '',
        required: false,
        is_table: true,
        table_name: '',
        limit: '',
        primaryKey: 0,
        from_type: '0',
      });
      // this.tableField.push();
    },
    addCreate(status) {
      if (status) {
        let haveCre = this.tableField.findIndex((e) => e.field === 'create_time');
        let haveUp = this.tableField.findIndex((e) => e.field === 'update_time');
        if (haveCre > 0 || haveUp > 0) {
          this.$nextTick((e) => {
            this.isCreate = false;
          });
          return this.$message.warning('已存在 create_time或update_time');
        }
        let data = [
          {
            field: 'create_time',
            field_type: 'timestamp',
            default: '',
            comment: '添加时间',
            required: false,
            is_table: true,
            table_name: '添加时间',
            limit: '',
            primaryKey: 0,
            from_type: '0',
          },
          {
            field: 'update_time',
            field_type: 'timestamp',
            default: '',
            comment: '修改时间',
            required: false,
            is_table: true,
            table_name: '修改时间',
            limit: '',
            primaryKey: 0,
            from_type: '0',
          },
        ];
        this.tableField = [...this.tableField, ...data];
      } else {
        let i = this.tableField.findIndex((e) => e.field === 'create_time');
        this.tableField.splice(i, 2);
      }
    },
    addDelete(status) {
      if (status) {
        let haveDel = this.tableField.findIndex((e) => e.field === 'delete_time');
        if (haveDel > 0) {
          this.isDelete = false;
          return this.$message.warning('已存在 delete_time');
        }
        let data = [
          {
            field: 'delete_time',
            field_type: 'timestamp',
            default: '',
            comment: '伪删除',
            required: false,
            is_table: false,
            table_name: '伪删除',
            limit: '',
            primaryKey: 0,
            from_type: '0',
          },
        ];
        this.tableField = [...this.tableField, ...data];
      } else {
        let i = this.tableField.findIndex((e) => e.field === 'delete_time');
        this.tableField.splice(i, 1);
      }
    },
    changeField(index) {
      console.log(this.tableField[index].field);
      if (this.tableField[index].field) {
        for (let i = 0; i < this.tableField.length; i++) {
          const e = this.tableField[i];
          if (['id', 'create_time', 'update_time', 'delete_time'].includes(this.tableField[index].field)) {
            this.$message.warning('列表中已存在该字段名称');
            this.tableField[index].field = '';
            return;
          }
        }
      }
    },
    changeComment(e, index) {
      this.tableField[index].table_name = e.target.value;
    },
    getCrudMenus() {
      crudMenus().then((res) => {
        console.log(res);
        this.menusList = res.data;
      });
      crudColumnType().then((res) => {
        this.columnTypeList = res.data.types;
        this.fromTypeList = res.data.form;
      });
    },
    del(row, index) {
      this.tableField.splice(index, 1);
      if (this.id) {
        this.deleteField.push(row.field);
      }
    },
    addOneOptions() {
      this.optionsList.push({
        label: '',
        value: '',
      });
    },
    delOneOptions(i) {
      this.optionsList.splice(i, 1);
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
.table-options{
  display flex
  align-items center
  .create{
    font-size: 16px;
    margin-left 10px
    cursor pointer
  }
}
.options-list{
  .item{
    display flex
    align-items center
    .add{
      font-size: 24px
      color #2d8cf0
    }
  }
}
</style>
