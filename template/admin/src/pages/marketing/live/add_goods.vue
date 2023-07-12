<template>
  <div>
    <div class="i-layout-page-header header-title">
      <div class="fl_header">
        <span>
          <el-button icon="ios-arrow-back" size="small" type="text" @click="$router.go(-1)">返回</el-button>
        </span>
        <el-divider direction="vertical"></el-divider>
        <span class="ivu-page-header-title">{{ $route.meta.title }}</span>
      </div>
    </div>
    <el-card :bordered="false" shadow="never" class="ivu-mt">
      <el-form
        ref="formValidate"
        :model="formValidate"
        :label-width="labelWidth"
        :label-position="labelPosition"
        class="tabform"
        @submit.native.prevent
      >
        <el-row :gutter="24">
          <el-col :span="24">
            <el-form-item label="选择商品：">
              <div class="box">
                <div class="box-item" v-for="(item, index) in goodsList" :key="index">
                  <img :src="item.image" alt="" />
                  <Icon type="ios-close-circle" size="20" @click="bindDelete(index, item)" />
                </div>
                <div class="upload-box" @click="selectGoods">
                  <Icon type="ios-camera-outline" size="36" />
                </div>
              </div>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div class="active-btn" v-if="goodsList.length > 0">
        <el-button type="success" @click="liveGoods">生成直播商品</el-button>
      </div>
      <div class="table-box" v-if="isShowBox">
        <el-table
          :data="tabList"
          ref="table"
          class="mt25"
          v-loading="loading"
          no-userFrom-text="暂无数据"
          no-filtered-userFrom-text="暂无筛选结果"
        >
          <el-table-column label="商品ID" width="80">
            <template slot-scope="scope">
              <span>{{ scope.row.id }}</span>
            </template>
          </el-table-column>
          <el-table-column label="商品信息" min-width="90">
            <template slot-scope="scope">
              <div class="product_box">
                <img :src="scope.row.image" alt="" />
                <span>{{ scope.row.store_name }}</span>
              </div>
            </template>
          </el-table-column>
          <el-table-column label="直播售价" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.price }}</span>
            </template>
          </el-table-column>
          <el-table-column label="库存" min-width="130">
            <template slot-scope="scope">
              <span>{{ scope.row.stock }}</span>
            </template>
          </el-table-column>
          <el-table-column label="操作" fixed="right" width="80">
            <template slot-scope="scope">
              <a @click="del(scope.row, index)">删除</a>
            </template>
          </el-table-column>
        </el-table>

        <div class="sub_btn">
          <el-button type="primary" style="width: 8%" @click="bindSub" :disabled="disabled" :loading="loadings"
            >提交</el-button
          >
        </div>
      </div>
    </el-card>
    <el-dialog :visible.sync="modals" title="商品列表" class="paymentFooter" width="900px">
      <goods-list
        ref="goodslist"
        :selectIds="selectIds"
        @getProductId="getProductId"
        v-if="modals"
        :ischeckbox="true"
      ></goods-list>
    </el-dialog>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import goodsList from '@/components/goodsList';
import { liveGoodsCreat, liveGoodsAdd } from '@/api/live';
export default {
  name: 'add_goods',
  components: {
    goodsList,
  },
  computed: {
    ...mapState('media', ['isMobile']),
    labelWidth() {
      return this.isMobile ? undefined : '100px';
    },
    labelPosition() {
      return this.isMobile ? 'top' : 'right';
    },
  },
  data() {
    return {
      isShowBox: false,
      loading: false,
      modals: false,
      goodsList: [],
      tempGoods: {},
      formValidate: {},
      tabList: [],
      disabled: false,
      loadings: false,
    };
  },
  methods: {
    selectGoods() {
      this.modals = true;
      this.selectIds = this.goodsList.map((i) => {
        return i.product_id;
      });
    },
    // 生成直播商品
    liveGoods() {
      let array = [];
      this.goodsList.map((el) => {
        array.push(el.product_id);
      });
      liveGoodsCreat({
        product_id: array,
      })
        .then((res) => {
          this.tabList = res.data;
          this.isShowBox = true;
        })
        .catch((error) => {
          this.$message.error(error.msg);
        });
    },
    getProductId(data) {
      this.goodsList = data;
      this.$nextTick((res) => {
        setTimeout(() => {
          this.modals = false;
        }, 300);
      });
    },
    bindDelete(index, item) {
      this.goodsList.splice(index, 1);
      let i = this.tabList.findIndex((e) => e.id == item.product_id);
      this.tabList.splice(i, 1);
      if (!this.goodsList.length) {
        this.isShowBox = false;
      }
    },
    del(row, index) {
      this.tabList.splice(index, 1);
      let i = this.goodsList.findIndex((e) => e.product_id == row.id);
      this.goodsList.splice(i, 1);
      if (!this.tabList.length) {
        this.isShowBox = false;
      }
    },
    // 提交
    bindSub() {
      this.disabled = true;
      this.loadings = true;
      liveGoodsAdd({
        goods_info: this.tabList,
      })
        .then((res) => {
          this.$message.success('添加成功');
          this.disabled = false;
          setTimeout(() => {
            this.$router.push({ path: this.$routeProStr + '/marketing/live/live_goods' });
          }, 500);
        })
        .catch((error) => {
          this.disabled = false;
          this.$message.error(error.msg);
        });
    },
  },
};
</script>

<style lang="stylus" scoped>
.upload-box {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
  background: #ccc;
}

.box {
  display: flex;
  flex-wrap: wrap;

  .box-item {
    position: relative;
    margin-right: 20px;

    .ivu-icon {
      position: absolute;
      right: -10px;
      top: -8px;
      color: #999;
      cursor: pointer;
    }
  }

  .upload-box, .box-item {
    width: 60px;
    height: 60px;
    margin-bottom: 10px;

    img {
      width: 100%;
      height: 100%;
    }
  }
}

.active-btn {
  padding-left: 96px;
}

.table-box {
  margin: 0 107px;
}

.sub_btn {
  margin-top: 10px;
}

.product_box {
  display: flex;

  img {
    width: 36px;
    height: 36px;
    margin-right: 10px;
  }
}
</style>
