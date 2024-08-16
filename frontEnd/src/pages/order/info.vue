<script setup>
import {onMounted, ref} from "vue";
import {onLoad, onShow} from '@dcloudio/uni-app'
import {baseUrl} from "@/utils/request";
import {getStatusHints, getStatusName, Order} from "@/utils/api/Order";
import moment from "moment";

const orderId = ref()
const setFrontImage = (url) => {
  order.value.idFront = url
}
const setBackImage = (url) => {
  order.value.idBack = url
}

const type = ref('good')
const creating = ref(false)
const chooseAddress = () => {
  uni.navigateTo({
    url: '/pages/address/index?type=choose'
  })
}
const address = ref()
onShow(async (options) => {

})
const orderInfo = ref()
const getOrderInfo = async () => {
  const response = await Order.show(orderId.value)
  orderInfo.value = response.data.data
  if (!orderInfo.value.id) {
    uni.showModal({
      title: '订单不存在'
    })
  }

}
const payOrder = () => {

}
onLoad(async (options) => {
  orderId.value = options.id
  if (!orderId.value) {
    uni.showModal({
      title: '订单不存在'
    })
  } else {
    getOrderInfo()
  }


})
const back = () => {
  uni.switchTab({
    url: '/pages/index/index'
  })
}
onMounted(() => {

})
</script>

<template>
  <div class="top-container" style="background: none">
    <div class="default-title" @click="back">订单详情</div>
  </div>
  <div class="fixed-container" style="padding: 0;width: 100vw;background-color: var(--default-background)"
       v-if="orderInfo">
    <div class="order-status-container">
      <div class="status-name">{{ getStatusName(orderInfo.status) }}</div>
      <div>{{getStatusHints(orderInfo.status,orderInfo.cancel_reason,orderInfo.create_time)}}</div>

    </div>
    <div class="cart-good-count">共{{ orderInfo.items.length }}件商品</div>
    <div class="cart-item" v-for="item in orderInfo.items" :key="item.id">
      <div class="good-image">
        <image :src="baseUrl+item.image"></image>
      </div>
      <div class="good-desc">
        <div class="name">{{ item.name }}</div>
        <div class="price-and-number">
          <div class="price">￥{{ item.price }}</div>

        </div>
      </div>
      <div class="good-count-number"> x{{ item.number }}</div>
    </div>
    <div class="address-container">
      <template>
        <div style="display: flex;justify-content: space-between;">
          <div>
            <div style="display: flex;justify-content: flex-start;align-items: baseline">
              <div style="font-size: 20px;font-weight: bold">
                {{ orderInfo.name }}
              </div>
              <div style="font-size: 14px;margin-left: 12px">
                {{ orderInfo.phone }}
              </div>
            </div>
            <div style="margin-top: 7.5px">
              {{ orderInfo.address }}
            </div>
          </div>

        </div>
      </template>

    </div>
    <div class="cuepon-container">
      <div class="name">优惠券</div>
      <div class="" style="color: #dddddd">未使用优惠券</div>
    </div>
    <div class="remark-container">
      <div class="name">备注说明</div>
      <div class="remark">
        <span style="font-size: 12px">{{ orderInfo.remark ? orderInfo.remark : '无备注信息' }}</span>
      </div>
    </div>

    <div class="order-desc">
      <div class="desc-item">
        <div class="name">订单编号</div>
        <div class="value">2024070300001</div>
      </div>
      <div class="desc-item">
        <div class="name">下单时间</div>
        <div class="value">{{  moment.unix(orderInfo.create_time).format('YYYY-MM-DD HH:mm:ss')  }}</div>
      </div>
      <div class="desc-item">
        <div class="name">支付方式</div>
        <div class="value">在线支付</div>
      </div>
      <div class="desc-item">
        <div class="name">配送方式</div>
        <div class="value">快递运输</div>
      </div>
      <div class="desc-item">
        <div class="name">清关姓名</div>
        <div class="value">{{ orderInfo.id_name }}</div>
      </div>
      <div class="desc-item">
        <div class="name">清关证件号</div>
        <div class="value">{{ orderInfo.id_number }}</div>
      </div>
    </div>
    <div style="width: 100vw;height: 5vh"></div>
    <div class="cart-footer-container">
      <div class="choose-all-button" style="display: flex;flex:3">
        <div>合计：</div>
        <div class="total-price">￥{{ orderInfo.amount }}</div>
      </div>
      <div class="total-calc-button" @click="payOrder" style="display: flex;justify-content: flex-end" v-if="orderInfo.status===0">
        <div class="calc-button">
          立即支付
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped lang="scss">



</style>