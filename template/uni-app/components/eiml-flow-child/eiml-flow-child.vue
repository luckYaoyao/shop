<template>
	<view class="product-list">
		<view class="product-item" @click="goGoodsDetail(item)">
			<!-- <view class="product-item" v-for="(item, index) in goodLists" @click="goGoodsDetail(item)"> -->
			<image :src="item.image"></image>
			<view class="info">
				<view class="title line2">{{ item.store_name }}</view>
				<view class="tag" v-if="item.activity && item.activity.type === '1'">秒杀</view>
				<view class="tag" v-if="item.activity && item.activity.type === '2'">砍价</view>
				<view class="tag" v-if="item.activity && item.activity.type === '3'">拼团</view>
				<view class="price-box">
					<view>
						<text>￥</text>
						{{ item.price }}
					</view>
					<view class="sales">
						已售 {{item.sales}}
					</view>
				</view>
			</view>
			<!-- </view> -->
		</view>
	</view>
</template>

<script>
	import {
		goShopDetail,
		goPage
	} from '@/libs/order.js'
	export default {
		props: ["item"],
		data() {
			return {

			}
		},
		methods: {
			goGoodsDetail(item) {
				goPage().then(res => {
					goShopDetail(item, this.uid).then(res => {
						uni.navigateTo({
							url: `/pages/goods_details/index?id=${item.id}`
						})
					})
				})
			},
		}
	}
</script>

<style lang="scss" scoped>


	.product-list {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		margin-top: 10rpx;
		padding: 0 10rpx;

		.product-item {
			position: relative;
			width: 354rpx;
			background: #fff;
			border-radius: 10rpx;
			margin-bottom: 20rpx;

			image {
				width: 100%;
				height: 344rpx;
				border-radius: 10rpx 10rpx 0 0;
			}

			.info {
				padding: 14rpx 16rpx;

				.title {
					font-size: 28rpx;
				}

				.tag {
					border-radius: 4rpx;
					border: 1px solid var(--view-theme);
					color: var(--view-theme);
					font-size: 20rpx;
					padding: 2rpx 4rpx;
					margin: 10rpx 0;
					width: max-content;
				}

				.price-box {
					font-size: 34rpx;
					font-weight: 700;
					margin-top: 8px;
					color: var(--view-priceColor);
					display: flex;
					justify-content: space-between;
					// align-items: flex-end;
					align-items: center;

					text {
						font-size: 26rpx;
					}

					.sales {
						color: #999999;
						font-size: 24rpx;
					}
				}
			}
		}
	}
</style>
