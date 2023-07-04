<template>
  <div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <div class="table_box">
        <el-form
          ref="formValidate"
          :label-width="labelWidth"
          :label-position="labelPosition"
          class="tabform"
          @submit.native.prevent
        >
          <el-row :gutter="24" justify="end">
            <el-col :span="24" class="ivu-text-left">
              <el-form-item :label="fromList.title + '：'">
                <el-radio-group
                  type="button"
                  v-model="formValidate.data"
                  class="mr15"
                  @change="selectChange(formValidate.data)"
                >
                  <el-radio-button :label="itemn.val" v-for="(itemn, indexn) in fromList.fromTxt" :key="indexn">
                    {{ itemn.text }}
                  </el-radio-button>
                </el-radio-group>
                <DatePicker
                  :editable="false"
                  @change="onchangeTime"
                  :value="timeVal"
                  format="yyyy/MM/dd"
                  type="daterange"
                  placement="bottom-end"
                  placeholder="请选择时间"
                  style="width: 200px"
                ></DatePicker>
              </el-form-item>
            </el-col>
            <el-col :span="24" class="ivu-text-left">
              <el-col :xl="5" :lg="12" :md="12" :sm="24" :xs="24" class="sex_box">
                <el-form-item label="名称：">
                  <el-select v-model="formValidate.admin_id" style="width: 90%" clearable @change="userSearchs">
                    <el-option
                      :value="item.id"
                      v-for="(item, index) in dataList"
                      :key="index"
                      :label="item.real_name"
                    ></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <!--<el-col :xl="5" :lg="12" :md="12" :sm="24" :xs="24">-->
              <!--<el-form-item label="行为：">-->
              <!--<el-input  placeholder="请输入行为" v-model="formValidate.pages" style="width: 90%;" clearable></el-input>-->
              <!--</el-form-item>-->
              <!--</el-col>-->
              <el-col :xl="5" :lg="12" :md="12" :sm="24" :xs="24" class="subscribe_box">
                <el-form-item label="链接：">
                  <el-input
                    placeholder="请输入链接"
                    v-model="formValidate.path"
                    style="width: 90%"
                    clearable
                  ></el-input>
                </el-form-item>
              </el-col>
              <el-col :xl="5" :lg="12" :md="12" :sm="24" :xs="24" class="subscribe_box">
                <el-form-item label="IP：">
                  <el-input placeholder="请输入IP" v-model="formValidate.ip" style="width: 90%" clearable></el-input>
                </el-form-item>
              </el-col>
              <el-col :xl="3" :lg="12" :md="3" :sm="24" :xs="24" class="btn_box">
                <!--<el-form-item>-->
                <el-button type="primary" icon="ios-search" label="default" class="userSearch" @click="userSearchs"
                  >搜索</el-button
                >
                <!--</el-form-item>-->
              </el-col>
            </el-col>
          </el-row>
        </el-form>
      </div>
      <el-table ref="selection" :data="tabList" :loading="loading" empty-text="暂无数据" highlight-current-row>
        <el-table-column label="ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="ID/名称" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.admin_id + ' / ' + scope.row.admin_name }}</span>
          </template>
        </el-table-column>
        <el-table-column label="链接" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.path }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作ip" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.ip }}</span>
          </template>
        </el-table-column>
        <el-table-column label="类型" min-width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.type }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作时间" min-width="100">
          <template slot-scope="scope">
            <span> {{ scope.row.add_time | formatDate }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div class="acea-row row-right page">
        <pagination
          v-if="total"
          :total="total"
          :page.sync="formValidate.page"
          :limit.sync="formValidate.limit"
          @pagination="getList"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { searchAdminApi, systemListApi } from '@/api/system';
import { formatDate } from '@/utils/validate';
export default {
  name: 'systemLog',
  filters: {
    formatDate(time) {
      if (time !== 0) {
        let date = new Date(time * 1000);
        return formatDate(date, 'yyyy-MM-dd hh:mm');
      }
    },
  },
  data() {
    return {
      fromList: {
        title: '选择时间',
        custom: true,
        fromTxt: [
          { text: '全部', val: '' },
          { text: '今天', val: 'today' },
          { text: '昨天', val: 'yesterday' },
          { text: '最近7天', val: 'lately7' },
          { text: '最近30天', val: 'lately30' },
          { text: '本月', val: 'month' },
          { text: '本年', val: 'year' },
        ],
      },
      timeVal: [],
      formValidate: {
        limit: 20,
        page: 1,
        pages: '',
        data: '',
        path: '',
        ip: '',
        admin_id: '',
      },
      loading: false,
      tabList: [],
      total: 0,

      dataList: [],
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
    this.getSearchAdmin();
    this.getList();
  },
  methods: {
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.formValidate.data = this.timeVal.join('-');
      this.formValidate.page = 1;
      this.getList();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.data = tab;
      this.timeVal = [];
      this.formValidate.page = 1;
      this.getList();
    },
    // 搜索条件
    getSearchAdmin() {
      searchAdminApi()
        .then(async (res) => {
          this.dataList = res.data.info;
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    // 列表
    getList() {
      this.loading = true;
      systemListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tabList = data.list;
          this.total = data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    // 搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
  },
};
</script>

<style scoped></style>
