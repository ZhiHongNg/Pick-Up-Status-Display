<script setup>
import {computed, onMounted, ref} from "vue";
import {onLoad, onShow} from '@dcloudio/uni-app'
import NumberController from "@/components/NumberController.vue";
import {Good} from "@/utils/api/Good";
import {baseUrl} from "@/utils/request";
import UploadImage from "@/components/UploadImage.vue";
import {validateIDCard} from "@/utils/api/Other";
import {Order} from "@/utils/api/Order";
import {getCartGoods, removeTickCartGoods} from "@/utils/service/GoodService";

const goodId = ref()
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
const createOrder = async () => {

  order.value.goods = []
  orderGoods.value.forEach(item => {
    order.value.goods.push(item)
  })
  order.value.name = address.value.name
  order.value.phone = address.value.phone
  order.value.address = address.value.address

  if (!order.value.name || !order.value.phone || !order.value.address) {
    uni.showToast({
      icon: 'none',
      title: '请填写完整信息'
    })
    return
  }
  if (!order.value.realname || !order.value.idnumber) {
    uni.showToast({
      icon: 'none',
      title: '请填写完整信息'
    })
    return
  }
  if (!order.value.idBack || !order.value.idFront) {
    uni.showToast({
      icon: 'none',
      title: '请填写完整信息'
    })
    return
  }
  if (!order.value.goods.length === 0) {
    uni.showToast({
      icon: 'none',
      title: '订单异常'
    })
    return
  }
  if (!validateIDCard(order.value.idnumber)) {
    uni.showToast({
      icon: 'none',
      title: '身份证号码错误，请检查'
    })
    return
  }
  creating.value = true
  const response = await Order.store(order.value)
  creating.value = false
  if (response.data.code === 1) {
    //去掉已经下单的购物车商品
    if (type.value === 'cart')await removeTickCartGoods()
    uni.redirectTo({
      url: '/pages/order/info?id=' + response.data.data
    })
  } else {
    uni.showToast({
      icon: 'error',
      title: response.data.msg
    })
  }
}
const address = ref()
onShow(async (options) => {
  address.value = uni.getStorageSync('chooseAddress')
})
onLoad(async (options) => {
  type.value = options.type

  if (type.value === 'good') {
    goodId.value = options.goodId
    getGoodInfo()
  } else {
    orderGoods.value = await getCartGoods(true)
  }
})
const back = () => {
  uni.switchTab({
    url: '/pages/index/index'
  })
}
const orderGoods = ref([])
const totalAmount = computed(() => {
  let totalPrcie = 0
  orderGoods.value.forEach((item) => {
    totalPrcie = totalPrcie + item.price * item.number
  })
  return totalPrcie.toFixed(2)
})
const order = ref({
  realname: '',
  idnumber: null,
  idFront: '',
  idBack: '',
  address: '',
  name: '',
  phone: '',
  remark: '',
  goods: []
})
const getGoodInfo = async () => {
  if (type.value === 'good') {
    const response = await Good.show(goodId.value)
    let goodItem = response.data.data
    goodItem.number = 1
    orderGoods.value.push(goodItem)
  } else if (type.value === 'cart') {

  }
}
onMounted(() => {

})
</script>

<template>
  <div class="top-container" style="background: none">
    <div class="default-title" @click="back">提交订单</div>
  </div>
  <div class="fixed-container" style="padding: 0;width: 100vw;background-color: var(--default-background)">
    <div class="address-container" @click="chooseAddress">
      <template v-if="!address.id">
        <div style="color: var(--default-color)">请选择收货地址</div>
        <image
            src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTM2OS42NDcgODI4LjcwN2MtOC4xODkgMC0xNi4zNzktMy4xMjQtMjIuNjI3LTkuMzczLTEyLjQ5Ny0xMi40OTYtMTIuNDk3LTMyLjc1OCAwLTQ1LjI1NEw2MDkuMDk5IDUxMmwtMjYyLjA4LTI2Mi4wNzljLTEyLjQ5Ny0xMi40OTctMTIuNDk3LTMyLjc1OCAwLTQ1LjI1NXMzMi43NTgtMTIuNDk3IDQ1LjI1NSAwTDY3Ni45OCA0ODkuMzczYzEyLjQ5NyAxMi40OTcgMTIuNDk3IDMyLjc1OCAwIDQ1LjI1NEwzOTIuMjc0IDgxOS4zMzRjLTYuMjQ4IDYuMjQ5LTE0LjQzOCA5LjM3My0yMi42MjcgOS4zNzN6IiBmaWxsPSIjOGE4YThhIi8+PC9zdmc+"></image>
      </template>
      <template v-else>
        <div style="display: flex;justify-content: space-between;" @click="chooseAddress">
          <div>
            <div style="display: flex;justify-content: flex-start;align-items: baseline">
              <div style="font-size: 20px;font-weight: bold">
                {{ address.name }}
              </div>
              <div style="font-size: 14px;margin-left: 12px">
                {{ address.phone }}
              </div>
            </div>
            <div style="margin-top: 7.5px">
              {{ address.address }}
            </div>
          </div>
          <div style="width: 24px;display: flex;align-items: center">
            <image style="height: 24px;width: 24px" src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTc5NS41MjggNTEwLjMxM0wzMjIuNDQgOTgzLjQwMWwtNDIuNTg3LTQyLjU4NiA0MzAuNDQyLTQzMC40NDFMMjc5Ljg1MyA3OS44MTJsNDIuNTg3LTQyLjU4NyA0NzMuMDg4IDQ3My4wODh6IiBmaWxsPSIjYmZiZmJmIi8+PC9zdmc+"></image>
          </div>
        </div>
      </template>

    </div>
    <div class="cart-good-count">共{{ orderGoods.length }}件商品</div>
    <div class="cart-item" v-for="item in orderGoods" :key="item.id">
      <div class="good-image">
        <image :src="baseUrl+item.image"></image>
      </div>
      <div class="good-desc">
        <div class="name">{{ item.name }}</div>
        <div class="price-and-number">
          <div class="price">￥{{ item.price }}</div>
          <NumberController v-model="item.number" :max="item.stock" :min="1"/>
        </div>
      </div>
      <!--      <div class="good-count-number"> x1</div>-->
    </div>
    <div class="cuepon-container">
      <div class="name">优惠券</div>
      <div class="" style="color: #dddddd">无可用优惠券</div>
    </div>
    <div class="remark-container">
      <div class="name">备注说明</div>
      <textarea placeholder="请填写备注说明，100字以内" v-model="order.remark"/>
    </div>
    <div class="id-container">
      <div class="title">为确保海关顺利清关，收货人姓名请与身份证信息保持一致</div>
      <div class="item">
        <div class="name">
          <span style="color: red">*</span>真实姓名
        </div>
        <div class="value">
          <input type="text" class="default-input" v-model="order.realname"
                 :class="{'shake':creating&&!order.realname}">
        </div>
      </div>
      <div class="item">
        <div class="name">
          <span style="color: red">*</span>身份证号码
        </div>
        <div class="value">
          <input type="text" class="default-input" v-model="order.idnumber"
                 :class="{'shake':creating&&!order.idnumber}">
        </div>
        <div class="item" style="display: flex;justify-content: space-around">
          <UploadImage @success="setFrontImage">
            <div class="id-image" :style="{backgroundImage: 'url('+baseUrl+order.idFront+')'}">
              <image v-if="!order.idFront"
                     src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTcyNS41MDQgMjg5LjI4YTY1LjMzNSA2NS4zMzUgMCAwIDAtNjUuNTM2IDY1LjUzNiA2NS4zMzUgNjUuMzM1IDAgMCAwIDY1LjUzNiA2NS41MzYgNjUuMzM1IDY1LjMzNSAwIDAgMCA2NS41MzYtNjUuNTM2YzAtMzUuODQtMjkuMTg0LTY1LjUzNi02NS41MzYtNjUuNTM2em0wIDExOC4yNzJjLTI5LjE4NCAwLTUyLjczNi0yMy41NTItNTIuNzM2LTUyLjczNiAwLTI5LjE4NCAyMy41NTItNTIuNzM2IDUyLjczNi01Mi43MzYgMjkuMTg0IDAgNTIuNzM2IDIzLjU1MiA1Mi43MzYgNTIuNzM2IDAgMjkuMTg0LTIzLjU1MiA1Mi43MzYtNTIuNzM2IDUyLjczNnoiIGZpbGw9IiNGRjhDOEMiLz48cGF0aCBkPSJNNzI1LjUwNCA0MjguMDMyYzU5LjkwNCAwIDEwOC41NDQgNDYuNTkyIDExMi42NCAxMDUuNDcyIDAgNC4wOTYgMy41ODQgNy4xNjggNy42OCA3LjE2OCA0LjYwOCAwIDguMTkyLTMuNTg0IDcuNjgtOC4xOTItNC4wOTYtNjcuMDcyLTU5LjkwNC0xMTkuODA4LTEyNy40ODgtMTE5LjgwOFM2MDIuNjI0IDQ2NS40MDggNTk4LjUyOCA1MzIuNDhjLS41MTIgNC42MDggMy4wNzIgOC4xOTIgNy42OCA4LjE5MiA0LjA5NiAwIDcuMTY4LTMuMDcyIDcuNjgtNy4xNjggMy4wNzItNTguODggNTEuNzEyLTEwNS40NzIgMTExLjYxNi0xMDUuNDcyek02MS40NCAyOTUuNDI0aDE2My44NHYzMy4yOEg2MS40NHptMCA5OS44NGgzMDYuNjg4djMzLjI4SDYxLjQ0em0wIDEwMC4zNTJINTEydjMzLjI4SDYxLjQ0em0wIDE2MC43NjhoNTQyLjIwOHYyOC42NzJINjEuNDR6IiBmaWxsPSIjRkY4QzhDIi8+PHBhdGggZD0iTTc2NC45MjgwMDAwMDAwMDAxIDczOC4zMDRhMTI5LjUzNiAxMjkuNTM2IDAgMSAwIDI1OS4wNzIgMCAxMjkuNTM2IDEyOS41MzYgMCAxIDAtMjU5LjA3MiAweiIgZmlsbD0iIzIwRDMzMSIvPjxwYXRoIGQ9Ik05ODguNjcyIDY3OS40MjRjLTMuMDcyLTMuMDcyLTcuNjgtMy4wNzItMTAuNzUyIDBMODc2LjAzMiA3ODEuMzEybC02NS4wMjQtNjUuMDI0Yy0zLjA3Mi0zLjA3Mi03LjY4LTMuMDcyLTEwLjc1MiAwLTMuMDcyIDMuMDcyLTMuMDcyIDcuNjggMCAxMC43NTJsNzAuMTQ0IDcwLjE0NGMyLjA0OCAyLjA0OCA1LjYzMiAyLjU2IDguMTkyIDEuNTM2IDEuMDI0LS41MTIgMS41MzYtMS4wMjQgMi41Ni0xLjUzNkw5ODguMTYgNjkwLjE3NmMzLjU4NC0zLjA3MiAzLjU4NC03LjY4LjUxMi0xMC43NTJ6IiBmaWxsPSIjRkZGIi8+PC9zdmc+"/>
              <div v-if="!order.idFront">身份证正面</div>
              <div v-if="!order.idFront" class="upload">点击上传</div>
            </div>
          </UploadImage>
          <UploadImage @success="setBackImage">
            <div class="id-image" :style="{backgroundImage: 'url('+baseUrl+order.idBack+')'}">
              <image v-if="!order.idBack"
                     src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDE0OTggMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTE5LjQ3NiAxOS40NzZoODMuMTQ5VjBIMHYxMDIuNjI1aDE5LjQ3NlYxOS40NzZ6bTAgOTg1LjA0OGg4My4xNDlWMTAyNEgwVjkyMS4zNzVoMTkuNDc2djgzLjE0OXpNMTQ3OS40NDQgMTkuNDc2aC04My4xNDhWMGgxMDIuNjI0djEwMi42MjVoLTE5LjQ3NlYxOS40NzZ6bTAgOTg1LjA0OGgtODMuMTQ4VjEwMjRoMTAyLjYyNFY5MjEuMzc1aC0xOS40NzZ2ODMuMTQ5eiIgZmlsbD0iI0ZDQzc5RiIvPjxwYXRoIGQ9Ik0xNDAwLjc5IDg4OC40MTZjMCAyMy45Ny0xOS40NzYgNDIuNjk3LTQyLjY5OCA0Mi42OTdIMTQwLjA4Yy0yMy45NyAwLTQyLjY5OC0xOS40NzYtNDIuNjk4LTQyLjY5N1YxMzUuNTg0YzAtMjMuOTcgMTkuNDc2LTQyLjY5NyA0Mi42OTgtNDIuNjk3aDEyMTguMDEzYzExLjIzNiAwIDIyLjQ3MyA0LjQ5NCAzMC43MTMgMTIuNzM0IDguMjQgOC4yNCAxMi43MzQgMTguNzI3IDEyLjczNCAzMC43MTNsLS43NDkgNzUyLjA4MnoiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMzc5Ljc4NiA0NzEuOTI0YzMyLjk2IDAgNjIuMTc0LTEwLjQ4NyA4OC4zOTItMzIuMjF2LTQyLjY5OUgyOTEuMzk0djQyLjY5OGMyNi4yMTggMjEuNzI0IDU2LjE4MiAzMi4yMSA4OC4zOTIgMzIuMjF6IiBmaWxsPSIjZGJkYmRiIi8+PHBhdGggZD0iTTI1OS4xODQgMzMxLjg0NWMwIDY1LjkyIDUzLjE4NSAxMTguMzU1IDExOC4zNTUgMTE4LjM1NXMxMTguMzU2LTUzLjE4NSAxMTguMzU2LTExOC4zNTVjMC02NS45Mi01My4xODUtMTE4LjM1Ni0xMTguMzU2LTExOC4zNTYtNjUuMTcgMC0xMTguMzU1IDUyLjQzNi0xMTguMzU1IDExOC4zNTZ6IiBmaWxsPSIjZGJkYmRiIi8+PHBhdGggZD0iTTI4MC45MDcgMzU4LjgxMmgxOTguNTA4djQ5LjQ0SDI4MC45MDd2LTQ5LjQ0eiIgZmlsbD0iI0YxRjFGNyIvPjxwYXRoIGQ9Ik0yODcuNjQ5IDMzMS44NDVjMCA1MC4xODkgNDAuNDUgOTAuNjQgOTAuNjQgOTAuNjRzODkuODktNDAuNDUxIDg5Ljg5LTkwLjY0LTQwLjQ1MS05MC42NC05MC42NC05MC42NC04OS44OSA0MC40NTEtODkuODkgOTAuNjR6IiBmaWxsPSIjZGJkYmRiIi8+PHBhdGggZD0iTTM1NS4wNjcgMzU1LjA2N2g0OS40NGE2LjcxNCA2LjcxNCAwIDAgMSA2Ljc0MSA2Ljc0MXYzLjc0NmgtNjMuNjcydi0zLjc0NmMwLTMuNzQ1IDMuNzQ1LTYuNzQxIDcuNDktNi43NDF6bS0zMi45NiAyMC45NzRINDMzLjcyYzguOTg5IDAgMTUuNzMgMi45OTYgMTUuNzMgNi43NDJ2My43NDVIMzA2LjM3NnYtMy43NDVjMC0zLjc0NiA3LjQ5LTYuNzQyIDE1LjczLTYuNzQyeiIgZmlsbD0iI0YxRjFGNyIvPjxwYXRoIGQ9Ik0zNDcuNTc2IDM2NS41NTRoNjQuNDIxdjEwLjQ4N2gtNjMuNjcydi0xMC40ODd6IiBmaWxsPSIjRTRFNUU4Ii8+PHBhdGggZD0iTTM3OS43ODYgMjg3LjY0OWwtMTIuNzM0IDYuNzQyIDIuMjQ3LTEzLjQ4NC05LjczOC0xMC40ODcgMTQuMjMzLTIuMjQ3IDUuOTkyLTEyLjczNSA2Ljc0MiAxMi43MzUgMTMuNDg0IDIuMjQ3LTkuNzM4IDkuNzM4IDIuMjQ3IDEzLjQ4NC0xMi43MzUtNS45OTN6bS0yMC45NzQgMTQ5LjA2OGMwIDExLjk4NSA5LjczOCAyMC45NzQgMjAuOTc0IDIwLjk3NHMyMC45NzUtOS43MzggMjAuOTc1LTIwLjk3NC05LjczOC0yMC45NzQtMjAuOTc1LTIwLjk3NC0yMC45NzQgOC45ODktMjAuOTc0IDIwLjk3NHptLTI4LjQ2NS0xNDMuMDc1bC02Ljc0MiAyLjk5NiAxLjQ5OC02Ljc0Mi01LjI0My01LjI0MyA2Ljc0MS0uNzUgMy43NDYtNi43NDEgMi45OTYgNi43NDEgNi43NDIuNzUtNS4yNDQgNS4yNDMgMS40OTggNi43NDItNS45OTItMi45OTZ6bTI0LjcyIDI0LjcybC02Ljc0MiAyLjk5NiAxLjQ5OC02Ljc0Mi01LjI0NC01LjI0NCA2Ljc0Mi0uNzQ5IDMuNzQ2LTYuNzQyIDIuOTk2IDYuNzQyIDYuNzQyLjc1LTQuNDk1IDUuMjQzIDEuNDk4IDYuNzQyLTYuNzQxLTIuOTk3em00OS40NCAwbC02Ljc0MyAyLjk5NiAxLjQ5OS02Ljc0Mi01LjI0NC01LjI0NCA2Ljc0Mi0uNzQ5IDMuNzQ1LTYuNzQyIDIuOTk3IDYuNzQyIDYuNzQxLjc1LTQuNDk0IDUuMjQzIDEuNDk4IDYuNzQyLTYuNzQyLTIuOTk3em0yNC43MTktMjQuNzJsLTYuNzQyIDIuOTk2IDEuNDk4LTYuNzQyLTUuMjQzLTUuMjQzIDYuNzQyLS43NSAzLjc0NS02Ljc0MSAyLjk5NiA2Ljc0MSA2Ljc0Mi43NS00LjQ5NCA1LjI0MyAxLjQ5OCA2Ljc0Mi02Ljc0Mi0yLjk5NnoiIGZpbGw9IiNGRkYiLz48cGF0aCBkPSJNNTQ5LjA4IDU4My41MzhoNjg5LjkwOHY3OC42NTRINTQ5LjA4di03OC42NTR6bTAgMTQ5LjA2OGg2ODkuOTA4djc4LjY1NEg1NDkuMDh2LTc4LjY1NHptNzUuNjU3LTUxOC4zNjh2MTUuNzMxaDMyLjk2djQ0LjE5NmgtOC45ODl2LTUuMjQzaC0yMy45N3YzMC43MTJoLTguOTl2LTMwLjcxMmgtMjMuOTd2NS4yNDNoLTguOTlWMjI5LjIyaDMyLjk2di0xNS43M2w4Ljk5Ljc0OHptLTguMjQgNDYuNDQ0di0yMS43MjRoLTIzLjk3djIxLjcyNGgyMy45N3ptMzIuOTYgMHYtMjEuNzI0aC0yMy45N3YyMS43MjRoMjMuOTd6bTI0LjcyLTEyLjczNWwtMi45OTYtOC45ODljMTEuMjM2LTYuNzQxIDE5LjQ3Ni0xNC45ODEgMjQuNzItMjQuNzJsNy40OSAzLjc0NmMtMi45OTYgNS4yNDQtNi43NDEgMTAuNDg3LTEwLjQ4NyAxNC45ODJ2MjkuOTYzaC04LjI0di0yMi40NzNjLTMuNzQ1IDIuMjQ4LTcuNDkgNS4yNDQtMTAuNDg3IDcuNDkxem0zMy43MDkgMjAuMjI2di04LjI0aDguOTg5djguMjRoMzUuOTU2djguOTg5aC0zNS45NTZ2MjIuNDcyaC04Ljk5di0yMi40NzJINjcyLjY4di04LjI0aDM1LjIwN3ptMTQuOTgyLTguMjRjLTUuOTkzIDAtOC45OS0yLjk5Ny04Ljk5LTguOTlWMjQ1LjdjLTMuNzQ1IDEuNDk4LTcuNDkgMi45OTYtMTEuOTg1IDQuNDk1bC01LjI0My02Ljc0MmM1Ljk5Mi0yLjI0NyAxMS45ODUtMy43NDYgMTYuNDgtNi43NDJ2LTIwLjIyNWg4LjI0djE1LjczYzguMjQtNC40OTQgMTUuNzMtOC45ODggMjAuOTc0LTE0LjIzMmw1Ljk5MyA1Ljk5M2MtNy40OTEgNi43NDEtMTcuMjMgMTIuNzM0LTI2Ljk2OCAxNy4yMjl2Ny40OWMwIDEuNDk5Ljc1IDIuOTk3IDIuOTk3IDIuOTk3SDczNy4xYzIuMjQ3IDAgMy43NDYtLjc1IDQuNDk1LTEuNDk4Ljc0OS0uNzUgMi4yNDctNC40OTUgMi4yNDctMTAuNDg4bDguMjQgMi45OTdjLS43NSA4LjI0LTIuMjQ3IDEyLjczNC00LjQ5NSAxNC4yMzItMi4yNDcgMS40OTktNS4yNDMgMi4yNDgtOC45ODkgMi4yNDggMS40OTkuNzQ5LTE1LjczLjc0OS0xNS43My43NDl6bTg0LjY0Ni0yNC43MmM1LjI0NCAyNC43MiAxOC43MjcgNDIuNjk4IDM5LjcwMiA1Ni4xODFsLTUuOTkzIDcuNDkxYy0xNy45NzgtMTEuOTg1LTI5Ljk2My0yNy43MTYtMzYuNzA1LTQ3LjE5Mi01Ljk5MyAxOS40NzYtMTguNzI3IDM0LjQ1OC0zOC4yMDMgNDcuMTkybC01LjI0NC03LjQ5YzI0LjcyLTE0Ljk4MiAzNy40NTQtMzUuOTU3IDM4LjIwMy02Mi4xNzV2LTE0LjIzMmg4Ljk5YzAgNy40OSAwIDE0LjIzMi0uNzUgMjAuMjI1em02My42NzMgNTEuNjg3YzcuNDktMS40OTggMTQuOTgxLTMuNzQ2IDIyLjQ3Mi02Ljc0MmwyLjI0NyA4LjI0Yy04Ljk4OSAyLjk5Ni0xOS40NzYgNS45OTMtMzIuOTYgOC4yNGwtMi4yNDctOC4yNGMxLjQ5OS0uNzUgMi4yNDgtMi4yNDcgMi4yNDgtNC40OTV2LTY3LjQxN2g2Ni42Njh2MjguNDY1aC0yNC43MmMwIDMuNzQ1Ljc1IDcuNDkgMS40OTkgMTEuMjM2aDI5LjIxNHY4LjI0SDkwOC42NGMuNzQ5IDEuNDk4Ljc0OSAzLjc0NiAxLjQ5OCA1LjI0NCAyLjk5NiA2Ljc0MiA1Ljk5MyAxMS4yMzYgOC45ODkgMTQuMjMyIDIuOTk2IDIuOTk3IDUuOTkzIDQuNDk1IDguMjQgNC40OTUuNzQ5IDAgMS40OTgtLjc1IDIuMjQ3LTIuMjQ3Ljc1LTEuNDk4Ljc1LTUuMjQ0IDEuNDk4LTExLjIzN2w3LjQ5MSA0LjQ5NWMtMS40OTggNy40OS0yLjk5NiAxMi43MzQtNC40OTQgMTQuOTgyLTEuNDk4IDIuMjQ3LTMuNzQ2IDMuNzQ1LTUuOTkzIDMuNzQ1LTQuNDk0IDAtOC45ODktMi4yNDctMTMuNDgzLTYuNzQyLTQuNDk1LTQuNDk0LTguOTktMTAuNDg3LTEyLjczNS0xNy45NzgtLjc0OS0yLjI0Ny0yLjI0Ny01LjI0My0yLjk5Ni04LjI0aC0yOC40NjZsLjc1IDIxLjcyNHptMC02MC42NzZ2MTEuOTg1aDQ5LjQ0di0xMS45ODVoLTQ5LjQ0em0wIDIwLjIyNXYxMS4yMzZoMjYuMjE4Yy0uNzUtMy43NDUtMS40OTktNi43NDEtMS40OTktMTEuMjM2aC0yNC43MnptMTA4LjYxNyAzMC43MTNjLTcuNDkgOC4yNC0xNy4yMjkgMTUuNzMtMjcuNzE2IDIyLjQ3MmwtNS45OTMtNy40OWMxMS4yMzYtNS45OTMgMjAuMjI1LTEyLjczNSAyNy43MTYtMjAuMjI2bDUuOTkzIDUuMjQ0em0tMTMuNDg0LTQ0Ljk0NXYtMTcuOTc5aDguOTl2MTcuOTc5aDIzLjk3di0xNy45NzloOC45OXYxNy45NzlIMTAyNHY4LjI0aC0xNS43M3YxOS40NzZoMjAuMjI1djguMjRoLTgyLjR2LTguMjRoMjAuMjI1di0xOS40NzdIOTUxLjM0di04LjI0aDE0Ljk4MXptMzIuOTYgMjguNDY1di0xOS40NzZoLTIzLjk3djE5LjQ3NmgyMy45N3ptMjkuOTY0IDMxLjQ2MWwtNi43NDIgNi43NDJjLTYuNzQyLTYuNzQyLTE0Ljk4Mi0xNC4yMzItMjYuMjE4LTIxLjcyM2w2Ljc0Mi01Ljk5M2M5LjczOCA3LjQ5IDE4LjcyNyAxNC4yMzMgMjYuMjE4IDIwLjk3NHptMjUuNDY5LTUzLjE4NXYtMTEuMjM2Yy00LjQ5NS43NS04LjI0Ljc1LTEyLjczNSAxLjQ5OGwtMi45OTYtNy40OWMxNC4yMzItLjc1IDI1LjQ2OS0zLjc0NiAzNC40NTgtNi43NDJsNC40OTQgNi43NDFjLTQuNDk0IDEuNDk5LTkuNzM4IDIuOTk3LTE0LjIzMiA0LjQ5NXYxMi43MzRoMTQuOTgxdjguMjRoLTE0Ljk4MXYzLjc0NmM1LjI0MyA0LjQ5NCAxMC40ODcgOS43MzggMTUuNzMgMTQuOTgxbC01LjI0MyA3LjQ5MWMtNC40OTUtNS45OTItNy40OTEtMTAuNDg3LTEwLjQ4Ny0xNC4yMzJ2MzguOTUyaC04LjI0di0zNi43MDVjLTMuNzQ2IDcuNDktOC4yNCAxNC45ODItMTQuMjMzIDIwLjk3NGwtMy43NDUtOS43MzhjOC4yNC03LjQ5IDE0LjIzMi0xNS43MyAxNy45NzgtMjUuNDY5aC0xNC45ODJ2LTguMjRoMTQuMjMzem02Mi45MjMgNTYuMTgyaC04LjI0di02Ljc0MmgtMTkuNDc2djcuNDloLTguMjR2LTc1LjY1N2gzNi43MDVsLS43NSA3NC45MDl6bS04LjI0LTE0Ljk4MnYtNTIuNDM2aC0xOS40NzZ2NTIuNDM2aDE5LjQ3NnptMTAwLjM3NyAxOC43MjdoLTguOTg5di0zLjc0NWgtNTkuMTc4djMuNzQ1aC04LjI0di04MC45MDFoNzcuMTU2djgwLjkwMXptLTguOTg5LTExLjk4NXYtNjAuNjc2aC01OS4xNzhWMjg2LjloNTkuMTc4em0tNy40OS01My45MzR2Ny40OWgtMTcuOTc5djExLjIzN2gxNC45ODJ2Ny40OWgtMTQuOTgydjEyLjczNWg4Ljk5Yy0xLjQ5OS0zLjc0NS0zLjc0Ni02Ljc0Mi01LjI0NC05LjczOGw2Ljc0MS0yLjI0N2MyLjI0OCAyLjk5NiAzLjc0NiA1Ljk5MiA1Ljk5MyA5LjczOGwtNS4yNDMgMi4yNDdoOC45ODl2Ny40OWgtNTAuOTM4di03LjQ5aDIwLjk3NHYtMTIuNzM0aC0xNC45ODJ2LTcuNDkxaDE0Ljk4MnYtMTEuMjM3aC0xOC43Mjd2LTcuNDloNDYuNDQzek01ODQuMjg2IDQzOS43MTNsLTYuNzQyLTUuOTkyYzYuNzQyLTExLjIzNyA5LjczOC0yNi4yMTggOS43MzgtNDQuMTk2di0zMS40NjJoNjkuNjY1djIzLjk3SDYzMS40OHY4Ljk5aDI4LjQ2NXY4LjI0SDYzMS40OHY4Ljk4OWgyMi40NzN2MzIuMjFoLTguMjR2LTMuNzQ1aC0zNS45NTZ2My43NDVoLTguMjR2LTMyLjIxaDIwLjk3NHYtOC45OWgtMjYuOTY3Yy0xLjQ5OCAxNC45ODItNC40OTQgMjguNDY2LTExLjIzNiA0MC40NTF6bTExLjk4NS03My40MXY4LjI0aDUyLjQzNnYtOC4yNGgtNTIuNDM2em0wIDE1LjczdjguOTloMjYuOTY3di04Ljk5aC0yNi45Njd6bTQ5LjQ0IDQ1LjY5NXYtMTEuOTg1aC0zNS45NTZ2MTEuOTg1aDM1Ljk1NnptODguMzkyIDBjNy40OS0xLjQ5OCAxNC45ODItMy43NDYgMjIuNDcyLTYuNzQybDIuMjQ4IDguMjRjLTguOTkgMi45OTYtMTkuNDc3IDUuOTkzLTMyLjk2IDguMjRsLTIuMjQ3LTguMjRjMS40OTgtLjc0OSAyLjI0Ny0yLjI0NyAyLjI0Ny00LjQ5NHYtNjcuNDE4aDY2LjY2OXYyOS4yMTRoLTI0LjcyYzAgMy43NDYuNzQ5IDcuNDkxIDEuNDk4IDExLjIzNmgyOS4yMTR2OC4yNGgtMjYuOTY3Yy43NSAxLjQ5OS43NSAzLjc0NiAxLjQ5OCA1LjI0NCAyLjk5NyA2Ljc0MiA1Ljk5MyAxMS4yMzYgOC45OSAxNC4yMzMgMi45OTYgMi45OTYgNS45OTIgNC40OTQgOC4yNCA0LjQ5NC43NDggMCAxLjQ5Ny0uNzQ5IDIuMjQ3LTIuMjQ3Ljc0OS0xLjQ5OC43NDktNS4yNDQgMS40OTgtMTEuMjM2bDcuNDkgNC40OTRjLTEuNDk4IDcuNDkxLTIuOTk2IDEyLjczNS00LjQ5NCAxNC45ODItMS40OTggMi4yNDctMy43NDUgMy43NDUtNS45OTMgMy43NDUtNC40OTQgMC04Ljk4OS0yLjI0Ny0xMy40ODMtNi43NDItNC40OTUtNC40OTQtOC45OS0xMC40ODctMTIuNzM1LTE3Ljk3OC0uNzQ5LTIuMjQ3LTIuMjQ3LTUuMjQzLTIuOTk2LTguMjRoLTI3LjcxNnYyMC45NzV6bTAtNjEuNDI1djExLjk4NWg0OS40NHYtMTEuOTg1aC00OS40NHptMCAyMC4yMjV2MTAuNDg3aDI2LjIxOGMtLjc1LTMuNzQ1LTEuNDk4LTYuNzQxLTEuNDk4LTExLjIzNmwtMjQuNzIuNzV6bTE2My4zLTMxLjQ2MWwtMi4yNDcgNi43NDFoMjYuMjE4djM4Ljk1M2MzLjc0Ni00LjQ5NSA2Ljc0Mi05LjczOCA4Ljk5LTE0LjIzM2w3LjQ5IDQuNDk1Yy00LjQ5NCA4LjI0LTkuNzM4IDE0Ljk4MS0xNi40OCAyMC45NzR2MTguNzI3YzAgNS45OTMtMi45OTYgOC45OS04LjI0IDguOTloLTkuNzM4bC0yLjI0Ny04LjI0aDguOTg5YzEuNDk4IDAgMi4yNDctLjc1IDIuMjQ3LTIuOTk3di05LjczOGwtNC40OTQgMi45OTZjLTEyLjczNSA3LjQ5MS0yOC40NjYgMTIuNzM1LTQ2LjQ0NCAxNS43MzFsLTMuNzQ1LTcuNDljMjIuNDczLTMuNzQ2IDM5LjcwMi05LjczOSA1MC4xODktMTcuMjNoLTUwLjE4OXYtOC4yNGgxMC40ODd2LTQyLjY5OGgxOC43MjdjLjc1LTIuMjQ3IDEuNDk5LTUuMjQzIDIuMjQ4LTguMjRsOC4yNCAxLjQ5OXptMTUuNzMxIDIxLjcyM3YtNi43NDJIODc2LjQzdjYuNzQyaDM2LjcwNXptMCAxMy40ODR2LTYuNzQySDg3Ni40M3Y2Ljc0MmgzNi43MDV6bTAgMTQuOTgxdi03LjQ5SDg3Ni40M3Y3LjQ5aDM2LjcwNXptMTA1LjYyMS00Ny4xOTJjLTIuMjQ3IDYuNzQyLTUuMjQzIDEzLjQ4My04Ljk4OSAxOS40NzZ2NjIuMTc0aC04Ljk4OXYtNDguNjlsLTguMjQgOS43MzgtMi45OTYtOC45OWM5LjczOC0xMC40ODYgMTYuNDgtMjIuNDcyIDIwLjk3NC0zNi43MDRsOC4yNCAyLjk5NnptMy43NDYgNDAuNDV2LTguMjRjLTEuNDk4IDEuNDk5LTIuOTk3IDIuOTk3LTMuNzQ2IDQuNDk1bC02Ljc0MS01Ljk5M2M4LjI0LTguMjQgMTQuOTgxLTE5LjQ3NiAxOS40NzYtMzIuOTZsOC4yNCAyLjk5N2MtNC40OTUgMTIuNzM0LTkuNzM4IDIzLjIyMi0xNi40OCAzMS40NjJoNDEuMmMwIDIwLjIyNSAwIDMyLjk2LTEuNDk5IDM4LjIwMy0uNzQ5IDYuNzQyLTUuMjQzIDkuNzM4LTExLjk4NSA5LjczOGgtOS43MzhsLTIuMjQ3LTguMjRjMy43NDUgMCA3LjQ5Ljc1IDEwLjQ4Ny43NXM0LjQ5NC0yLjI0OCA1LjI0NC01Ljk5M2MuNzQ5LTIuMjQ4Ljc0OS0xMS4yMzcuNzQ5LTI1LjQ3aC0xMy40ODRjLTEuNDk4IDkuNzM5LTMuNzQ1IDE3LjIzLTYuNzQyIDIzLjIyMi0yLjk5NiA2Ljc0Mi04LjI0IDEyLjczNS0xNC45ODEgMTcuOTc4bC01Ljk5My02Ljc0MWM1Ljk5My00LjQ5NSA5LjczOC04Ljk5IDEyLjczNC0xNC4yMzMgMi4yNDgtNS4yNDQgNC40OTUtMTEuMjM2IDUuOTkzLTE5LjQ3NmwtMTAuNDg3LTEuNDk4em01My4xODUtMTEuOTg1bC01Ljk5MyA2Ljc0MmMtMTEuMjM2LTguOTktMTguNzI3LTIwLjIyNS0yMi40NzItMzQuNDU4bDcuNDktNC40OTVjMy43NDYgMTMuNDg0IDExLjIzNyAyMy45NzEgMjAuOTc1IDMyLjIxMXptNzQuMTYtNC40OTR2MzcuNDU0YzIuMjQ3LTIuMjQ3IDUuMjQzLTQuNDk1IDguMjQtNy40OWwyLjI0NyA4Ljk4OGMtNS45OTMgNS4yNDQtMTEuMjM3IDkuNzM4LTE3Ljk3OCAxNC4yMzNsLTIuOTk3LTcuNDkxYzEuNDk4LTEuNDk4IDIuMjQ3LTIuOTk2IDIuMjQ3LTQuNDk1di0zMi45NmgtMTIuNzM0di04LjI0aDIwLjk3NHptNS45OTItMTIuNzM1bC01Ljk5MyA1Ljk5M2MtMy43NDUtNC40OTUtOC4yNC04Ljk5LTE0Ljk4MS0xMy40ODRsNS45OTItNS45OTJjNS45OTMgNC40OTQgMTEuMjM3IDguOTg5IDE0Ljk4MiAxMy40ODN6IiBmaWxsPSIjREREIi8+PHBhdGggZD0iTTExNTkuNTg0IDM2Ny4wNTJ2LTguMjRoNTIuNDM2djguMjRoLTE3Ljk3OHYyMy45N2gxNi40OHY4LjI0aC0xNi40OHYyOS4yMTVoMTkuNDc3djguMjRoLTU2Ljkzdi04LjI0aDguMjR2LTQ3LjE5Mmg4LjIzOXY0Ny4xOTJoMTEuMjM2di02MS40MjVoLTI0LjcyeiIgZmlsbD0iI0UyRTVFRiIvPjxwYXRoIGQ9Ik05MTcuNjMgMzMyLjU5NGgtNDYuNDQzcy0yMy45NzEtNjIuOTIzLTM5LjcwMi02Mi45MjNINjU3LjY5N2MtMTQuOTgyIDAtNDcuMTkyIDYyLjkyMy00Ny4xOTIgNjIuOTIzaC0zMi45NmMtMi45OTYtNS45OTMtMTQuMjMzLTIzLjk3LTE3Ljk3OC0yMy45N2gtNTMuMTg1Yy00LjQ5NSAwLTE0LjIzMyAyMy45Ny0xNC4yMzMgMjUuNDY4IDAgMS40OTguNzUgMi45OTcgMS40OTggNC40OTUtMjkuOTYzIDEyLjczNC00OC42OSA0MS4yLTQ4LjY5IDc0LjE2VjYzMC43M2MwIDIwLjk3NCA4LjI0IDQxLjIgMjMuMjIxIDU2LjkzIDE0Ljk4MiAxNC45ODIgMzUuMjA4IDIzLjIyMiA1Ni45MzEgMjMuMjIyaDM5Mi41MmM0NC4xOTcgMCA4MC4xNTMtMzUuOTU2IDgwLjkwMi03OS40MDNWNDEyLjc0NmMtLjc0OS00NC4xOTYtMzYuNzA1LTgwLjE1Mi04MC45MDEtODAuMTUyem0tMzY0LjA1NiA2OS42NjVjLTUuOTkyIDUuOTkzLTE0LjIzMiA4Ljk4OS0yMi40NzIgOC45ODlzLTE2LjQ4LTIuOTk2LTIyLjQ3My04Ljk4OWMtNS45OTMtNS45OTMtOC45ODktMTQuMjMzLTguOTg5LTIyLjQ3MyAwLTE3LjIyOSAxNC4yMzMtMzEuNDYxIDMxLjQ2Mi0zMS40NjEgOC4yNCAwIDE2LjQ4IDIuOTk2IDIyLjQ3MiA4Ljk4OSA1Ljk5MyA1Ljk5MyA4Ljk5IDE0LjIzMiA4Ljk5IDIyLjQ3MnMtMi45OTcgMTYuNDgtOC45OSAyMi40NzN6bTE5MS4wMTcgMjUzLjE5Yy04Mi40IDAtMTQ5LjgxNy02Ni42NjgtMTQ5LjgxNy0xNDkuODE2IDAtODIuNCA2Ny40MTgtMTQ5LjgxNyAxNDkuODE3LTE0OS44MTcgODMuMTQ5IDAgMTQ5LjgxNyA2Ni42NjggMTQ5LjgxNyAxNDkuODE3IDAgODIuNC02Ni42NjggMTQ5LjgxNy0xNDkuODE3IDE0OS44MTd6IiBmaWxsPSIjRkNDNzlGIi8+PHBhdGggZD0iTTc0NC41OTEgNjA4LjI1N2MtNTYuOTMgMC0xMDIuNjI1LTQ1LjY5NC0xMDIuNjI1LTEwMi42MjRzNDUuNjk1LTEwMi42MjUgMTAyLjYyNS0xMDIuNjI1IDEwMi42MjUgNDUuNjk0IDEwMi42MjUgMTAyLjYyNS00Ni40NDQgMTAyLjYyNC0xMDIuNjI1IDEwMi42MjR6IiBmaWxsPSIjRkNDNzlGIi8+PC9zdmc+"/>
              <div v-if="!order.idBack">身份证国标面</div>
              <div v-if="!order.idBack" class="upload">点击上传</div>
            </div>
          </UploadImage>

        </div>
      </div>
    </div>
    <div style="width: 100vw;height: 5vh"></div>
    <div class="cart-footer-container">
      <div class="choose-all-button" style="display: flex;flex:3">
        <div>合计：</div>
        <div class="total-price">￥{{ totalAmount }}</div>
      </div>
      <div class="total-calc-button" @click="createOrder" style="display: flex;justify-content: flex-end">
        <div class="calc-button" :class="{'creating':creating}">
          {{ creating ? '正在提交' : '提交订单' }}
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped lang="scss">

</style>