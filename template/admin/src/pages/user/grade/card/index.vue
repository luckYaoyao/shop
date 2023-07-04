<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form :model="gradeFrom" :label-width="labelWidth" :label-position="labelPosition" @submit.native.prevent>
        <el-row :gutter="24">
          <el-col v-bind="grid">
            <el-form-item label="批次名称：" label-for="title">
              <el-input
                search
                enter-button
                v-model="gradeFrom.title"
                placeholder="请输入批次名称"
                @on-search="userSearchs"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col v-bind="grid">
            <el-button type="primary" icon="md-add" @click="addBatch" class="mr20">添加批次</el-button>
            <el-button @click="getMemberScan">卡密使用页面二维码</el-button>
          </el-col>
        </el-row>
      </el-form>
      <el-table
        class="mt25"
        :columns="columns"
        :data="tbody"
        v-loading="loading"
        highlight-current-row
        no-userFrom-text="暂无数据"
        no-filtered-userFrom-text="暂无筛选结果"
      >
        <el-table-column label="编号" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="批次名称" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.title }}</span>
          </template>
        </el-table-column>
        <el-table-column label="体验天数" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.use_day }}</span>
          </template>
        </el-table-column>
        <el-table-column label="发卡总数量" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.total_num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="使用数量" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.use_num }}</span>
          </template>
        </el-table-column>
        <el-table-column label="制卡时间" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="是否激活" min-width="100">
          <template slot-scope="scope">
            <el-switch
              :active-value="1"
              :inactive-value="0"
              v-model="scope.row.status"
              :value="scope.row.status"
              @change="onchangeIsShow(scope.row)"
              size="large"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="备注" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.remark }}</span>
          </template>
        </el-table-column>
        <el-table-column label="制卡时间" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="170">
          <template slot-scope="scope">
            <el-dropdown size="small" @command="changeMenu(scope.row, $event, index)" :transfer="true">
              <span class="el-dropdown-link">更多<i class="el-icon-arrow-down el-icon--right"></i> </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item command="1">编辑批次名</el-dropdown-item>
                <el-dropdown-item command="2">查看卡列表</el-dropdown-item>
                <el-dropdown-item command="3">导出</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="gradeFrom.page"
          :limit.sync="gradeFrom.limit"
          @pagination="getMemberBatch"
        />
      </div>
    </el-card>
    <Modal v-model="modal" title="添加批次" footer-hide>
      <form-create v-model="fapi" :rule="rule" @submit="onSubmit"></form-create>
    </Modal>
    <Modal v-model="cardModal" title="卡列表" footer-hide width="1000">
      <cardList v-if="cardModal" :id="id"></cardList>
    </Modal>
    <Modal v-model="modal2" title="编辑批次名" footer-hide>
      <form-create :rule="rule2" @submit="onSubmit2"></form-create>
    </Modal>
    <Modal v-model="modal3" title="二维码" footer-hide>
      <div v-if="qrcode" class="acea-row row-around">
        <div v-if="qrcode && qrcode.wechat_img" class="acea-row row-column-around row-between-wrapper">
          <div v-viewer class="QRpic">
            <img v-lazy="qrcode.wechat_img" />
          </div>
          <span class="mt10">公众号二维码</span>
        </div>
        <div v-if="qrcode && qrcode.routine" class="acea-row row-column-around row-between-wrapper">
          <div v-viewer class="QRpic">
            <img v-lazy="qrcode.routine" />
          </div>
          <span class="mt10">小程序二维码</span>
        </div>
      </div>
      <Spin v-else></Spin>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import cardList from './list.vue';
import { userMemberBatch, memberBatchSave, memberBatchSetValue, exportMemberCard, userMemberScan } from '@/api/user';
import { exportmberCardList } from '@/api/export.js';

export default {
  name: 'index',
  components: { cardList },
  data() {
    return {
      cardModal: false,
      id: 0,
      grid: {
        xl: 7,
        lg: 7,
        md: 12,
        sm: 24,
        xs: 24,
      },
      columns: [
        {
          title: '编号',
          key: 'id',
        },
        {
          title: '批次名称',
          key: 'title',
        },
        {
          title: '体验天数',
          key: 'use_day',
        },
        {
          title: '发卡总数量',
          key: 'total_num',
        },
        {
          title: '使用数量',
          key: 'use_num',
        },
        {
          title: '制卡时间',
          key: 'add_time',
        },
        {
          title: '是否激活',
          slot: 'status',
        },
        {
          title: '备注',
          key: 'remark',
        },
        {
          title: '操作',
          slot: 'action',
          fixed: 'right',
        },
      ],
      tbody: [],
      total: 0,
      gradeFrom: {
        title: '',
        page: 1,
        limit: 15,
      },
      loading: false,
      modal: false,
      rule: [
        {
          type: 'input',
          field: 'title',
          title: '批次名称',
          validate: [
            {
              required: true,
              message: '请输入批次名称',
              trigger: 'blur',
            },
          ],
        },
        {
          type: 'InputNumber',
          field: 'total_num',
          title: '制卡数量',
          value: 1,
          props: {
            min: 1,
            precision: 0,
            max: 100000,
          },
          on: {
            'on-change': (data) => {
              if (data > 100000) {
                this.$nextTick((e) => {
                  this.rule[1].value = 100000;
                });
              }
            },
          },
        },
        {
          type: 'InputNumber',
          field: 'use_day',
          title: '体验天数',
          value: 1,
          props: {
            min: 1,
            precision: 0,
            max: 100000,
          },
          on: {
            'on-change': (data) => {
              if (data > 100000) {
                this.$nextTick((e) => {
                  this.rule[2].value = 100000;
                });
              }
            },
          },
        },
        {
          type: 'radio',
          field: 'status',
          title: '是否激活',
          value: '0',
          options: [
            {
              value: '0',
              label: '冻结',
            },
            {
              value: '1',
              label: '激活',
            },
          ],
        },
        {
          type: 'input',
          field: 'remark',
          title: '备注',
          props: {
            type: 'textarea',
          },
        },
      ],
      modal2: false,
      rule2: [
        {
          type: 'hidden',
          field: 'id',
          value: '',
        },
        {
          type: 'input',
          field: 'title',
          title: '批次名称',
          value: '',
          validate: [
            {
              required: true,
              message: '请输入批次名称',
              trigger: 'blur',
            },
          ],
        },
      ],
      modal3: false,
      qrcode: null,
      fapi: {},
    };
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '75px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  created() {
    this.getMemberBatch(this.gradeFrom);
  },
  methods: {
    // 批次列表
    getMemberBatch() {
      this.loading = true;
      userMemberBatch(this.gradeFrom)
        .then((res) => {
          this.loading = false;
          this.tbody = res.data.list;
          this.total = res.data.count;
        })
        .catch((err) => {
          this.loading = false;
          this.$Message.error(err.msg);
        });
    },
    // 批次名称查询
    userSearchs() {
      this.gradeFrom.page = 1;
      this.getMemberBatch();
    },
    // 激活 | 冻结
    onchangeIsShow(row) {
      memberBatchSetValue(row.id, {
        field: 'status',
        value: row.status,
      })
        .then((res) => {
          this.$Message.success(res.msg);
        })
        .catch((err) => {
          this.$Message.error(err.msg);
        });
    },
    // 导出
    async export(row) {
      let [th, filekey, data, fileName] = [[], [], [], ''];
      let lebData = await this.getExcelData(row.id);
      if (!fileName) fileName = lebData.filename;
      if (!filekey.length) {
        filekey = lebData.fileKey;
      }
      if (!th.length) th = lebData.header;
      data = data.concat(lebData.export);
      this.$exportExcel(th, filekey, fileName, data);
    },
    getExcelData(excelData) {
      return new Promise((resolve, reject) => {
        exportmberCardList(excelData).then((res) => {
          resolve(res.data);
        });
      });
    },
    // 更多
    changeMenu(row, name) {
      switch (name) {
        case '1':
          this.rule2[0].value = row.id;
          this.rule2[1].value = row.title;
          this.modal2 = true;
          break;
        case '2':
          this.id = row.id;
          this.cardModal = true;
          break;
        case '3':
          this.export(row);
          break;
      }
    },
    // 添加批次弹窗
    addBatch() {
      this.fapi.resetFields();
      this.modal = true;
    },
    // 提交批次
    onSubmit(formData) {
      memberBatchSave(0, formData)
        .then((res) => {
          this.modal = false;
          this.$Message.success(res.msg);
          this.getMemberBatch();
          this.fapi.resetFields();
        })
        .catch((err) => {
          this.$Message.error(err.msg);
        });
    },
    onSubmit2(formData) {
      memberBatchSetValue(formData.id, {
        field: 'title',
        value: formData.title,
      })
        .then((res) => {
          this.modal2 = false;
          this.$Message.success(res.msg);
          this.getMemberBatch();
        })
        .catch((err) => {
          this.$Message.error(err.msg);
        });
    },
    // 会员卡二维码
    getMemberScan() {
      this.$Spin.show();
      userMemberScan()
        .then((res) => {
          this.$Spin.hide();
          this.qrcode = res.data;
          this.modal3 = true;
        })
        .catch((err) => {
          this.$Spin.hide();
          this.$Message.error(err.msg);
        });
    },
  },
};
</script>

<style lang="less" scoped>
.QRpic {
  width: 180px;
  height: 180px;

  img {
    width: 100%;
    height: 100%;
  }
}
</style>
