<template>
	<view class="viewSpaceBetween">
		<view class="column" :style="{'width':columnWidth+'%'}" v-for="(itemTab,indexTab) in tabList"
			v-bind:key="indexTab">
			<view v-for="(item,index) in itemTab" v-bind:key="index">
				<slot :name="item"></slot>
			</view>
		</view>

		<!-- 
		 下段代码是预加载，渲染出来后遍能读取到组件的属性了，然后进行排版
		 想要代码简单且通用，就需要牺牲一些东西
		-->
		<scroll-view style="width: 0rpx;height: 0rpx;">
			<view v-for="(item,index) in childCount" v-bind:key="index">
				<slot :name="index"></slot>
			</view>
		</scroll-view>

	</view>
</template>

<script>
	export default {
		props: ["columnNum"],
		data() {
			return {
				defColumnNum: 2, //默认列数，如果不传，则默认两列
				columnWidth: 0,

				childCount: 0,

				lastChildCount: 0, //记录上次加载的数量,当数量发生改变时，则需要刷新

				tabList: []
			}
		},
		updated() {
			this.childCount = Object.getOwnPropertyNames(this.$slots).length;
			if (this.childCount != this.lastChildCount) {
				var that = this;
				this.$nextTick(function() {
					that.refreshList();
				});
			}
		},
		mounted() {
			this.childCount = Object.getOwnPropertyNames(this.$slots).length;
			var that = this;
			this.$nextTick(function() {
				that.refreshList();
			});

		},
		methods: {
			getTemp() {
				return 0;
			},


			refreshList() {
				var slots = this.$slots;

				this.childCount = Object.getOwnPropertyNames(slots).length;

				this.lastChildCount = this.childCount;

				if (this.columnNum == null || this.columnNum == "") {
					this.columnNum = this.defColumnNum;
				}

				this.columnWidth = 100 / this.columnNum;

				var temTabList = [];
				var temTabHeightList = [];
				for (var i = 0; i < this.columnNum; i++) {
					var temArr = [];
					temTabList[i] = temArr;
					temTabHeightList[i] = 0;
				}

				for (var i = 0; i < this.childCount; i++) {

					var item = slots[i];

					var offsetHeight = 0;
					if (item[0].elm != null) {
						offsetHeight = item[0].elm.offsetHeight;
					}


					if (offsetHeight > 0) {
						//查询出最小的列，将item添加到该列
						var tempIndex = 0;
						var tempHeight = 1000000;
						var tempStr = "";
						for (var j = 0; j < temTabHeightList.length; j++) {
							var temItem = temTabHeightList[j];
							tempStr += "j:" + temItem + ",";
							if (temItem < tempHeight) {
								tempIndex = j;
								tempHeight = temItem;
							}
						}


						temTabList[tempIndex].push(i);
						var tempHeight = temTabHeightList[tempIndex];
						tempHeight += item[0].elm.offsetHeight;
						temTabHeightList[tempIndex] = tempHeight;
					} else {
						var tab = i % this.columnNum;
						temTabList[tab].push(i);
					}
				}

				this.tabList = temTabList;
			}
		}
	}
</script>

<style lang="scss">
	.viewSpaceBetween {
		display: -webkit-flex;
		display: flex;
		-webkit-justify-content: space-between;
		justify-content: space-between;
		align-items: flex-start;
	}

	.column {
		flex-grow: 1;
	}

</style>
