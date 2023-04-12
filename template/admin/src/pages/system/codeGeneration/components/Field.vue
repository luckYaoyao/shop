<template>
  <div class="main">
    <div class="mb20">
      <Button class="mr10" type="primary" @click="addRow">添加列</Button>
    </div>
    <!-- <Table border :columns="columns" :data="dataList">
      <template slot-scope="{ row }" slot="action">
        <a>删除</a>
        <Divider type="vertical" />
        <a>修改</a>
      </template>
    </Table> -->
    <div class="fied-table">
      <div class="fied-item" v-for="(item, index) in dataList" :key="index">
        <div class="fied-parameter">
          <Icon class="close" size="14" type="md-close-circle" @click="delItem(index)" />
          <div class="name">
            <Input class="from-width" v-model="item.name"></Input>
          </div>
          <div class="field">
            <Select v-if="item.field !== 'id'" class="from-width" v-model="item.field" transfer>
              <Option v-for="item in rowList" :value="item.value" :label="item.label" :key="item.value">{{
                item.label
              }}</Option>
            </Select>
            <span v-else>{{ item.field }}</span>
          </div>
        </div>
      </div>
      <div class="fied-item">
        <div class="set-up">
          <div class="name">操作</div>
          <div class="field">删除</div>
        </div>
      </div>
    </div>
    <!-- <Modal v-model="modal" width="360" title="新增列">
      <div>
        <Select v-model="rowName" @on-change="changeRow" :label-in-value="true">
          <Option v-for="item in rowList" :value="item.value" :label="item.label" :key="item.value">{{
            item.label
          }}</Option>
        </Select>
        <Input class="mt10" v-model="comment"></Input>
      </div>
      <template #footer>
        <Button type="primary" size="large" long @click="add">添加</Button>
      </template>
    </Modal> -->
  </div>
</template>

<script>
export default {
  name: '',
  props: {
    field: {
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
      modal: false,
      rowName: '',
      columns: [
        {
          title: 'id',
          key: 'id',
          align: 'center',
          width: 110,
        },
      ],
      dataList: [
        {
          name: 'id',
          field: 'id',
        },
      ],
      comment: '',
      rowData: {},
    };
  },
  created() {},
  mounted() {},
  methods: {
    addRow() {
      if (this.dataList.length >= 10) return this.$Message.warning('最多添加10个');
      let i = this.dataList.length;
      // this.modal = true;
      this.dataList.splice(i, 0, {
        name: '',
        field: '',
      });
    },
    changeRow(e) {
      this.rowData = e;
      this.rowList.map((i) => {
        if (i.value === e.value) this.rowData.comment = i.comment;
      });
    },
    // add() {
    //   let i = this.dataList.length - 1;
    //   console.log(i);
    //   this.$nextTick((e) => {
    //     this.dataList.splice(i, 0, {
    //       name: this.rowData.label,
    //       field: this.rowData.value,
    //     });
    //     this.comment = '';
    //   });
    // },
    delItem(i) {
      this.dataList.splice(i, 1);
    },
  },
};
</script>
<style lang="scss" scoped>
.ivu-table-wrapper {
  border-top: 1px solid #dcdee2;
  border-left: 1px solid #dcdee2;
}
.form-width {
  width: 500px;
}
.fied-table {
  display: flex;
  border: 1px solid #dcdee2;
  width: max-content;
  .set-up {
    width: 80px;
    .name {
      line-height: 32px;
    }
  }
}
.fied-parameter {
  position: relative;
  .close {
    position: absolute;
    top: -6px;
    right: -6px;
    z-index: 11;
  }
}

.fied-parameter,
.set-up {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  // border-radius: 6px;
  font-size: 14px;
  border: 1px solid #dcdee2;
  height: 108px;
  .name {
    width: 100%;
    height: 52px;
    text-align: center;
    padding: 10px 10px;
    background-color: #f8f8f9;
  }
  .field {
    width: 100%;
    line-height: 31px;
    height: 54px;

    text-align: center;
    border-top: 2px solid #dcdee2;
    padding: 10px 10px;
  }
  .from-width {
    width: 90px;
  }
}
</style>
