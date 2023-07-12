<template>
  <div class="article-manager">
    <div class="i-layout-page-header header-title">
      <div class="fl_header">
        <router-link :to="{ path: $routeProStr + '/product/product_list' }" v-if="$route.params.id"
          ><el-button icon="ios-arrow-back" size="small" class="mr20">返回</el-button></router-link
        >
        <span class="ivu-page-header-title mr20">商品评论管理</span>
      </div>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form ref="formValidate" :model="formValidate" label-width="75px" label-position="left" @submit.native.prevent>
        <el-row :gutter="24">
          <el-col :span="24">
            <el-form-item label="评论时间：">
              <el-radio-group
                v-model="formValidate.data"
                type="button"
                @change="selectChange(formValidate.data)"
                class="mr"
              >
                <el-radio-button :label="item.val" v-for="(item, i) in fromList.fromTxt" :key="i">{{
                  item.text
                }}</el-radio-button>
              </el-radio-group>
              <el-date-picker
                :editable="false"
                @change="onchangeTime"
                v-model="timeVal"
                type="daterange"
                value-format="yyyy/MM/dd"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                
              ></el-date-picker>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="评价状态：">
              <el-select v-model="formValidate.is_reply" placeholder="请选择" clearable @change="userSearchs">
                <el-option value="1" label="已回复"></el-option>
                <el-option value="0" label="未回复"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col v-bind="grid" v-if="!$route.params.id">
            <el-form-item label="商品信息：" label-for="store_name">
              <el-input
                size="default"
                enter-button
                placeholder="请输入商品ID或者商品信息"
                clearable
                v-model="formValidate.store_name"
              />
            </el-form-item>
          </el-col>
          <el-col v-bind="grid">
            <el-form-item label="用户名称：" label-for="account">
              <el-input size="default" enter-button placeholder="请输入" clearable v-model="formValidate.account" />
            </el-form-item>
          </el-col>
          <el-col :xl="3" :lg="3" :md="12" :sm="12" :xs="24" class="search">
            <el-form-item>
              <el-button type="primary" icon="ios-search" @click="userSearchs">搜索</el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <!--            <div class="Button">-->
      <!--                <el-button type="primary" class="bnt" icon="md-add">添加评论</el-button>-->
      <!--            </div>-->
      <el-row>
        <el-col v-bind="grid">
          <el-button v-auth="['product-reply-save_fictitious_reply']" type="primary" icon="md-add" @click="add"
            >添加自评</el-button
          >
        </el-col>
      </el-row>
      <el-table
        ref="table"
        :data="tableList"
        class="ivu-mt mt25"
        v-loading="loading"
        @on-sort-change="sortMethod"
        empty-text="暂无数据"
      >
        <el-table-column label="评论ID" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.id }}</span>
          </template>
        </el-table-column>
        <el-table-column label="商品信息" min-width="130">
          <template slot-scope="scope">
            <div class="imgPic acea-scope.row scope.row-middle">
              <div class="pictrue" v-viewer><img v-lazy="scope.row.image" /></div>
              <div class="info">{{ scope.row.store_name }}</div>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="用户名称" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.nickname }}</span>
          </template>
        </el-table-column>
        <el-table-column label="评分" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.score }}</span>
          </template>
        </el-table-column>
        <el-table-column label="评价内容" min-width="130">
          <template slot-scope="scope">
            <div class="mb5 content_font">{{ scope.row.comment }}</div>
            <div v-viewer class="pictrue mr10" v-for="(item, index) in scope.row.pics || []" :key="index">
              <img v-lazy="item" :src="item" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="回复内容" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.merchant_reply_content }}</span>
          </template>
        </el-table-column>
        <el-table-column label="评价时间" min-width="130">
          <template slot-scope="scope">
            <span>{{ scope.row.add_time }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" fixed="right" width="170">
          <template slot-scope="scope">
            <a @click="reply(scope.row)">回复</a>
            <el-divider direction="vertical"></el-divider>
            <a @click="del(scope.row, '删除评论', index)">删除</a>
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
    <el-dialog :visible.sync="modals" scrollable title="回复内容">
      <el-form ref="contents" :model="contents" :rules="ruleInline" label-position="right" @submit.native.prevent>
        <el-form-item prop="content">
          <el-input v-model="contents.content" type="textarea" :rows="4" placeholder="请输入回复内容" />
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button type="primary" @click="oks">确定</el-button>
        <el-button @click="cancels">取消</el-button>
      </div>
    </el-dialog>
    <addReply
      :visible.sync="replyModal"
      :goods="goodsData"
      :attr="attrData"
      :avatar="avatarData"
      :picture="pictureData"
      @callGoods="callGoods"
      @callAttr="callAttr"
      @callPicture="callPicture"
      @removePicture="removePicture"
    ></addReply>
    <el-dialog :visible.sync="goodsModal" title="选择商品" width="960px">
      <goodsList v-if="replyModal" @getProductId="getProductId"></goodsList>
    </el-dialog>
    <el-dialog :visible.sync="attrModal" title="选择商品规格" width="960px">
      <el-table ref="table" :row-key="getRowKey" :data="goodsData.attrs" height="500">
        <el-table-column label="" width="60">
          <template slot-scope="scope">
            <el-radio v-model="templateRadio" :label="scope.row.id" @change.native="getTemplateRow(scope.row)"
              >&nbsp;</el-radio
            >
          </template>
        </el-table-column>
        <el-table-column label="图片" width="120">
          <template slot-scope="scope">
            <div class="product-data">
              <img class="image" :src="scope.row.image" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="规格" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.suk }}</span>
          </template>
        </el-table-column>
        <el-table-column label="售价" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.ot_price }}</span>
          </template>
        </el-table-column>
        <el-table-column label="优惠价" min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.price }}</span>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
    <el-dialog :visible.sync="pictureModal" width="960px" title="上传商品图" :close-on-click-modal="false">
      <uploadPictures
        :isChoice="isChoice"
        @getPic="getPic"
        @getPicD="getPicD"
        :gridBtn="gridBtn"
        :gridPic="gridPic"
        v-if="pictureModal"
      ></uploadPictures>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { replyListApi, setReplyApi, fictitiousReply } from '@/api/product';
import addReply from '../components/addReply.vue';
import goodsList from '@/components/goodsList/index';
import uploadPictures from '@/components/uploadPictures';

export default {
  name: 'product_productEvaluate',
  components: {
    addReply,
    goodsList,
    uploadPictures,
  },
  data() {
    return {
      templateRadio: 0,
      modals: false,
      replyModal: false,
      pictureModal: false,
      goodsModal: false,
      attrModal: false, // 选择商品规格
      grid: {
        xl: 7,
        lg: 10,
        md: 12,
        sm: 12,
        xs: 24,
      },
      gridPic: {
        xl: 6,
        lg: 8,
        md: 12,
        sm: 12,
        xs: 12,
      },
      gridBtn: {
        xl: 4,
        lg: 8,
        md: 8,
        sm: 8,
        xs: 8,
      },
      formValidate: {
        is_reply: '',
        data: '',
        store_name: '',
        key: '',
        order: '',
        account: '',
        product_id: this.$route.params.id === undefined ? 0 : this.$route.params.id,
        page: 1,
        limit: 15,
      },
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
      value: '45',
      tableList: [],
      goodsAddType: '',
      goodsData: {},
      attrData: {},
      avatarData: {},
      pictureData: [],
      selectProductAttrList: [],
      isChoice: '',
      picTit: '',
      tableIndex: 0,
      total: 0,
      loading: false,
      timeVal: [],
      contents: {
        content: '',
      },
      ruleInline: {
        content: [{ required: true, message: '请输入回复内容', trigger: 'blur' }],
      },
      rows: {},
    };
  },
  computed: {},
  created() {
    if (this.$route.query.is_reply == 0) this.formValidate.is_reply = this.$route.query.is_reply;
    this.getList();
  },
  watch: {
    '$route.params.id'(to, from) {
      this.formValidate.product_id = 0;
      this.getList();
    },
    replyModal(value) {
      if (!value) {
        this.goodsData = {};
        this.attrData = {};
        this.avatarData = {};
        this.pictureData = [];
        this.getList();
      }
    },
  },
  methods: {
    // 添加虚拟评论；
    add() {
      // this.$modalForm(fictitiousReply(this.formValidate.product_id)).then(() => this.getList());
      this.replyModal = true;
    },
    getRowKey(row) {
      return row.id;
    },
    getTemplateRow(row) {
      this.attrData = row;
      this.attrModal = false;
    },
    oks() {
      this.modals = true;
      this.$refs['contents'].validate((valid) => {
        if (valid) {
          setReplyApi(this.contents, this.rows.id)
            .then(async (res) => {
              this.$message.success(res.msg);
              this.modals = false;
              this.$refs['contents'].resetFields();
              this.getList();
            })
            .catch((res) => {
              this.$message.error(res.msg);
            });
        } else {
          return false;
        }
      });
    },
    cancels() {
      this.modals = false;
      this.$refs['contents'].resetFields();
    },
    // 删除
    del(row, tit, num) {
      let delfromData = {
        title: tit,
        num: num,
        url: `product/reply/${row.id}`,
        method: 'DELETE',
        ids: '',
      };
      this.$modalSure(delfromData)
        .then((res) => {
          this.$message.success(res.msg);
          this.tableList.splice(num, 1);
          this.total = this.total - 1;
        })
        .catch((res) => {
          this.$message.error(res.msg);
        });
    },
    // 回复
    reply(row) {
      this.modals = true;
      this.rows = row;
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e || [];
      this.formValidate.data = this.timeVal[0] ? this.timeVal ? this.timeVal.join('-') : '' : '';
      this.formValidate.page = 1;
      this.getList();
    },
    sortMethod(a) {
      if (a.order === 'normal') {
        this.formValidate.key = '';
        this.formValidate.order = '';
      } else {
        this.formValidate.key = a.key;
        this.formValidate.order = a.order;
      }
      this.getList();
    },
    // 选择时间
    selectChange(tab) {
      this.formValidate.data = tab;
      this.timeVal = [];
      this.formValidate.page = 1;
      this.getList();
    },
    // 列表
    getList() {
      this.loading = true;
      this.formValidate.is_reply = this.formValidate.is_reply || '';
      this.formValidate.store_name = this.formValidate.store_name || '';
      replyListApi(this.formValidate)
        .then(async (res) => {
          let data = res.data;
          this.tableList = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.msg);
        });
    },
    // 表格搜索
    userSearchs() {
      this.formValidate.page = 1;
      this.getList();
    },
    search() {},
    callGoods() {
      this.goodsModal = true;
    },
    callAttr() {
      this.attrModal = true;
    },
    getProductId(goods) {
      this.goodsData = goods;
      this.goodsModal = false;
      this.attrData.unique = '';
    },
    getPic(pc) {
      this.avatarData = pc;
      this.pictureModal = false;
    },
    getPicD(pc) {
      let pictureData = [...this.pictureData];
      pictureData = pictureData.concat(pc);
      pictureData.sort((a, b) => a.att_id - b.att_id);
      let picture = [];
      for (let i = 0; i < pictureData.length; i++) {
        if (pictureData[i + 1] && pictureData[i].att_id != pictureData[i + 1].att_id) {
          picture.push(pictureData[i]);
        }
        if (!pictureData[i + 1]) {
          picture.push(pictureData[i]);
        }
      }
      this.pictureData = picture;
      this.pictureModal = false;
    },
    callPicture(type) {
      this.isChoice = type;
      this.pictureModal = true;
    },
    removePicture(att_id) {
      let index = this.pictureData.findIndex((item) => item.att_id === att_id);
      this.pictureData.splice(index, 1);
    },
  },
};
</script>
<style scoped lang="stylus">
.content_font {
  color: #2b85e4;
}

.search {
  >>> .ivu-form-item-content {
    margin-left: 0 !important;
  }
}

.ivu-mt .Button .bnt {
  margin-right: 6px;
}

.ivu-mt .ivu-table-row {
  font-size: 12px;
  color: rgba(0, 0, 0, 0.65);
}

.ivu-mt >>> .ivu-table-cell {
  padding: 10px 0 !important;
}

.pictrue {
  width: 36px;
  height: 36px;
  display: inline-block;
  cursor: pointer;
}

.pictrue img {
  width: 100%;
  height: 100%;
  display: block;
  object-fit: cover;
}

.ivu-mt .imgPic .info {
  width: 60%;
  margin-left: 10px;
}

.ivu-mt .picList .pictrue {
  height: 36px;
  margin: 7px 3px 0 3px;
}

.ivu-mt .picList .pictrue img {
  height: 100%;
  display: block;
}
.product-data {
  display: flex;
  align-items: center;

  .image {
    width: 50px !important;
    height: 50px !important;
    margin-right: 10px;
  }
}
</style>
