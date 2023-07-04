<template>
  <div class="goodList">
    <el-form ref="formValidate" :model="formValidate" label-width="120px" label-position="right" class="tabform">
      <el-row :gutter="24">
        <el-col v-bind="grid" v-if="!liveStatus">
          <el-form-item label="商品分类：" label-for="pid">
            <!-- <el-select v-model="formValidate.cate_id" style="width: 200px" clearable @change="userSearchs">
              <el-option v-for="item in treeSelect" :value="item.id" :key="item.id"
                >{{ item.html + item.cate_name }}
              </el-option>
            </el-select> -->
            <el-cascader
              v-model="formValidate.cate_id"
              size="small"
              :options="treeSelect"
              :props="{ emitPath: false }"
              clearable
            ></el-cascader>
          </el-form-item>
        </el-col>
        <el-col v-bind="grid" v-if="!type && diy">
          <el-form-item label="商品类型：" label-for="pid">
            <el-select v-model="goodType" style="width: 200px" clearable @change="userSearchs">
              <el-option v-for="item in goodList" :value="item.activeValue" :key="item.activeValue" :label="item.title">
              </el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-col v-bind="grid">
          <el-form-item label="商品搜索：" label-for="store_name">
            <el-input
              search
              enter-button
              placeholder="请输入商品名称/关键字/编号"
              v-model="formValidate.store_name"
              style="width: 250px"
              @on-search="userSearchs"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <el-table
      ref="table"
      empty-text="暂无数据"
      max-height="400"
      :highlight-current-row="many !== 'many'"
      :data="tableList"
      v-loading="loading"
      @select="changeCheckbox"
      @select-all="changeCheckbox"
    >
      <el-table-column v-if="many == 'many'" type="selection" width="55"> </el-table-column>
      <el-table-column v-else width="50">
        <template slot-scope="scope">
          <el-radio v-model="templateRadio" :label="scope.row.id" @change.native="getTemplateRow(scope.row)"
            >&nbsp;</el-radio
          >
        </template>
      </el-table-column>

      <el-table-column label="商品ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="图片" width="80">
        <template slot-scope="scope">
          <div class="tabBox_img" v-viewer>
            <img v-lazy="scope.row.image" />
          </div>
        </template>
      </el-table-column>
      <el-table-column label="商品名称" min-width="250">
        <template slot-scope="scope">
          <span>{{ scope.row.store_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="商品分类" min-width="150" v-if="liveStatus">
        <template slot-scope="scope">
          <span>{{ scope.row.cate_name }}</span>
        </template>
      </el-table-column>
    </el-table>
    <div class="acea-row row-right page">
      <pagination
        v-if="total"
        :total="total"
        :page.sync="formValidate.page"
        :limit.sync="formValidate.limit"
        @pagination="pageChange"
      />
    </div>
    <div class="footer" slot="footer" v-if="many === 'many' && !diy">
      <el-button type="primary" size="large" :loading="modal_loading" long @click="ok">提交</el-button>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import { cascaderListApi, changeListApi } from '@/api/product';
import { liveGoods } from '@/api/live';
import { getProductList } from '@/api/diy';
export default {
  name: 'index',
  props: {
    is_new: {
      type: String,
      default: '',
    },
    type: {
      type: Number,
      default: 0,
    },
    diy: {
      type: Boolean,
      default: false,
    },
    ischeckbox: {
      type: Boolean,
      default: false,
    },
    liveStatus: {
      type: Boolean,
      default: false,
    },
    isLive: {
      type: Boolean,
      default: false,
    },
    isdiy: {
      type: Boolean,
      default: false,
    },
    selectIds: {
      type: Array,
      default: () => {
        return [];
      },
    },
    datas: {
      type: Object,
      default: function () {
        return {};
      },
    },
  },
  data() {
    return {
      templateRadio: 0,

      modal_loading: false,
      treeSelect: [],
      formValidate: {
        page: 1,
        limit: 15,
        cate_id: '',
        store_name: '',
        is_new: this.is_new,
      },
      total: 0,
      modals: false,
      loading: false,
      grid: {
        xl: 10,
        lg: 10,
        md: 12,
        sm: 24,
        xs: 24,
      },
      tableList: [],
      currentid: 0,
      productRow: {},
      columns4: [
        {
          title: '商品ID',
          key: 'id',
        },
        {
          title: '图片',
          slot: 'image',
        },
        {
          title: '商品名称',
          key: 'store_name',
          minWidth: 250,
        },
        {
          title: '商品分类',
          key: 'cate_name',
          minWidth: 150,
        },
      ],
      columns5: [
        {
          title: '商品ID',
          key: 'id',
        },
        {
          title: '图片',
          slot: 'image',
        },
        {
          title: '商品名称',
          key: 'name',
          minWidth: 250,
        },
      ],
      images: [],
      many: '',
      goodType: '',
      goodList: [
        {
          activeValue: 0,
          title: '商品列表',
        },
        {
          activeValue: '4',
          title: '热门榜单',
        },
        {
          activeValue: '5',
          title: '首发新品',
        },
        {
          activeValue: '6',
          title: '促销单品',
        },
        {
          activeValue: '7',
          title: '精品推荐',
        },
      ],
    };
  },
  computed: {},
  created() {
    let many = '';
    if (this.ischeckbox) {
      many = 'many';
    } else {
      many = this.$route.query.type;
    }
    this.many = many;
  },
  mounted() {
    this.goodsCategory();
    if (this.diy) {
      this.productList();
    } else {
      this.getList();
    }
  },
  methods: {
    productList() {
      let data = {
        page: this.formValidate.page,
        limit: this.formValidate.limit,
        cate_id: this.formValidate.cate_id,
        store_name: this.formValidate.store_name,
        type: this.type ? this.type : this.goodType,
      };
      this.loading = true;
      getProductList(data)
        .then((res) => {
          let data = res.data;
          this.tableList = data.list;
          this.total = res.data.count;
          this.loading = false;
        })
        .catch((res) => {
          this.loading = false;
          this.$Message.error(res.msg);
        });
    },
    getTemplateRow(row) {
      console.log('111');
      let images = [];
      let imageObject = {
        image: row.image,
        product_id: row.id,
        store_name: row.store_name,
        temp_id: row.temp_id,
      };
      images.push(imageObject);
      this.images = images;
      this.diyVal = row;
      this.$emit('getProductId', row);
    },
    changeRadio(row) {},
    changeCheckbox(selection) {
      let images = [];
      selection.forEach(function (item) {
        let imageObject = {
          image: item.image,
          product_id: item.id,
          store_name: item.store_name,
          temp_id: item.temp_id,
        };
        images.push(imageObject);
      });
      this.images = images;
      this.diyVal = selection;
      this.$emit('getProductId', selection);
    },
    // 商品分类；
    goodsCategory() {
      cascaderListApi(1)
        .then((res) => {
          this.treeSelect = res.data;
        })
        .catch((res) => {
          this.$Message.error(res.msg);
        });
    },
    pageChange() {
      if (this.diy) {
        this.productList();
      } else {
        this.getList();
      }
    },
    // 列表
    getList() {
      this.loading = true;
      if (!this.liveStatus) {
        if (this.isLive) {
          this.formValidate.is_live = 1;
        }
        changeListApi(this.formValidate)
          .then(async (res) => {
            let data = res.data;
            if (this.selectIds.length) {
              let arr = [];
              this.selectIds.map((item) => {
                data.list.map((i) => {
                  if (i.id == item) {
                    i._checked = true;
                    arr.push(i);
                  }
                });
              });
              this.changeCheckbox(arr);
            }
            this.tableList = data.list;
            this.total = res.data.count;
            this.loading = false;
          })
          .catch((res) => {
            this.loading = false;
            this.$Message.error(res.msg);
          });
      } else {
        liveGoods({
          is_show: '1',
          status: '1',
          live_id: this.datas.id,
          kerword: this.formValidate.store_name,
          page: this.formValidate.page,
          limit: this.formValidate.limit,
        })
          .then(async (res) => {
            let data = res.data;
            data.list.forEach((el) => {
              el.image = el.cover_img;
            });
            if (this.selectIds.length) {
              this.selectIds.map((item) => {
                data.list.map((i) => {
                  if (i.id == item) {
                    i._checked = true;
                  }
                });
              });
            }
            this.$nextTick((e) => {
              this.tableList = data.list;
              this.total = res.data.count;
              this.loading = false;
            });
          })
          .catch((res) => {
            this.loading = false;
            this.$Message.error(res.msg);
          });
      }
    },
    ok() {
      if (this.images.length > 0) {
        if (this.$route.query.fodder === 'image') {
          let imageValue = form_create_helper.get('image');
          form_create_helper.set('image', imageValue.concat(this.images));
          form_create_helper.close('image');
        } else {
          if (this.isdiy) {
            this.$emit('getProductId', this.diyVal);
          } else {
            this.$emit('getProductId', this.images);
          }
        }
      } else {
        this.$Message.warning('请先选择商品');
      }
    },
    // 表格搜索
    userSearchs() {
      this.currentid = 0;
      this.productRow = {};
      this.formValidate.page = 1;
      if (this.diy) {
        this.productList();
      } else {
        this.getList();
      }
    },
    clear() {
      this.productRow.id = '';
      this.currentid = '';
    },
  },
};
</script>

<style scoped lang="stylus">
.footer {
  margin: 15px 0;
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

.tabform {
  >>> .ivu-form-item {
    margin-bottom: 16px !important;
  }
}

.btn {
  margin-top: 20px;
  float: right;
}

.goodList {
  >>> table {
    width: 100% !important;
  }
}
</style>
