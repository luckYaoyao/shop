<template>
  <div>
    <div class="i-layout-page-header">
      <div class="i-layout-page-header">
        <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
      </div>
    </div>

    <Card :bordered="false" dis-hover class="ivu-mt">
      <Form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        @submit.native.prevent
      >
        <Row :gutter="24" type="flex">
          <Col span="24">
            <FormItem label="搜索：">
              <div class="acea-row row-middle">
                <Input
                  search
                  enter-button
                  @on-search="selChange"
                  placeholder="请输入语言Code"
                  element-id="name"
                  v-model="formValidate.keyword"
                  style="width: 30%"
                />
              </div>
            </FormItem>
          </Col>
        </Row>
      </Form>
    </Card>
    <Card :bordered="false" dis-hover>
      <Table
        ref="table"
        :columns="columns"
        :data="tabList"
        class="ivu-mt"
        :loading="loading"
        no-data-text="暂无数据"
        no-filtered-data-text="暂无筛选结果"
      >
        <template slot-scope="{ row, index }" slot="action">
          <a @click="edit(row)">编辑</a>
          <Divider type="vertical" />
          <a @click="del(row, '删除语言', index)">删除</a>
        </template>
      </Table>
      <div class="acea-row row-right page">
        <Page
          :total="total"
          :current="formValidate.page"
          show-elevator
          show-total
          @on-change="pageChange"
          :page-size="formValidate.limit"
        />
      </div>
    </Card>
  </div>
</template>
<script>
import { mapState } from 'vuex';
import { langCountryList } from '@/api/setting';
export default {
  data() {
    return {
      formValidate: {
        keyword: '',
        page: 1,
        limit: 20,
      },
      total: 0,
      loading: false,
      columns: [
        {
          title: '编号',
          key: 'id',
          width: 80,
        },
        {
          title: '所属语言',
          key: 'name',
          minWidth: 180,
        },
        {
          title: '状态码/文字',
          key: 'code',
          minWidth: 300,
        },
        {
          title: '操作',
          slot: 'action',
          fixed: 'right',
          width: 100,
        },
      ],
      tabList: [],
      code: null,
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : 80;
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'left';
    },
  },
  mounted() {
    this.getList();
  },
  methods: {
    selChange() {
      this.formValidate.page = 1;
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      langCountryList(this.formValidate)
        .then(async (res) => {
          this.loading = false;
          this.tabList = res.data.list;
          this.total = res.data.count;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    pageChange(index) {
      this.formValidate.page = index;
      this.getList();
    },
  },
};
</script>
<style scoped lang="stylus">
.ivu-mt .type .item {
  margin: 3px 0;
}

.tabform {
  margin-bottom: 10px;
}

.Refresh {
  font-size: 12px;
  color: #1890FF;
  cursor: pointer;
}

.ivu-form-item {
  margin-bottom: 10px;
}

.status >>> .item~.item {
  margin-left: 6px;
}

.status >>> .statusVal {
  margin-bottom: 7px;
}

/* .ivu-mt >>> .ivu-table-header */
/* border-top:1px dashed #ddd!important */
.type {
  padding: 3px 0;
  box-sizing: border-box;
}

.tabBox_img {
  width: 36px;
  height: 36px;
  border-radius: 4px;
  cursor: pointer;

  img {
    width: 100%;
    height: 100%;
  }
}
</style>
