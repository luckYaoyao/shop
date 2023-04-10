<template>
  <div class="main">
    <div class="mb20">
      <Button class="mr10" type="primary" @click="addRow">添加列</Button>
      <Button>添加</Button>
    </div>
    <Table border :columns="columns" :data="dataList">
      <!-- <template slot-scope="{ row }" slot="id">
        <Input v-model="row.id" class="priceBox"></Input>
      </template>
      <template slot-scope="{ row }" slot="field">
        <Input v-model="row.field" class="priceBox"></Input>
      </template> -->
    </Table>
    <Modal v-model="modal" width="360" title="新增列">
      <div>
        <Select v-model="rowName">
          <Option v-for="item in rowList" :value="item.value" :key="item.value">{{ item.label }}</Option>
        </Select>
      </div>
      <template #footer>
        <Button type="primary" size="large" long @click="add">添加</Button>
      </template>
    </Modal>
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
  },
  data() {
    return {
      modal: false,
      rowName: '',
      rowList: [
        {
          value: 'New York',
          label: 'New York',
        },
        {
          value: 'London',
          label: 'London',
        },
        {
          value: 'Sydney',
          label: 'Sydney',
        },
        {
          value: 'Ottawa',
          label: 'Ottawa',
        },
        {
          value: 'Paris',
          label: 'Paris',
        },
        {
          value: 'Canberra',
          label: 'Canberra',
        },
      ],
      columns: [
        {
          title: 'id',
          key: 'id',
          align: 'center',
          width: 110,
        },
        {
          title: 'field',
          key: 'field',
          align: 'center',
          minWidth: 110,
        },
      ],
      dataList: [
        {
          id: '字段id',
          field: '字段field',
        },
      ],
    };
  },
  created() {},
  mounted() {},
  methods: {
    addRow() {
      this.modal = true;
    },
    add() {
      this.dataList[0][this.rowName] = '字段';
      this.$nextTick((e) => {
        this.columns.push({
          title: this.rowName,
          key: this.rowName,
          align: 'center',
          minWidth: 110,
        });
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
</style>
