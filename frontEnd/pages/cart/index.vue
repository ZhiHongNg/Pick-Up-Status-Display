<script setup>
import {computed, ref} from 'vue'
import {onLoad, onShow} from '@dcloudio/uni-app'
import NumberController from "@/components/NumberController.vue";
import {getCartGoods} from "@/utils/service/GoodService";
import {baseUrl} from "@/utils/request";

const choose = ref(false)

const goodClick = async (id) => {
  uni.vibrateShort()
  uni.navigateTo({
    url: '/pages/good/info' + '?id=' + id
  })
}
const chooseAll = () => {
  if (choosenItemIds.value.length === 0) {
    cartGoods.value.forEach(item => {
      choosenItemIds.value.push(item.id)
    })
  } else {
    choosenItemIds.value = []
  }
}
const choosenItemIds = ref([])
const tickClick = async (id) => {
  uni.vibrateShort()
  var index = choosenItemIds.value.indexOf(id)
  const carts = uni.getStorageSync('carts')
  if (index !== -1) {
    choosenItemIds.value.splice(index, 1);
    carts.forEach((item,cartIndex)=>{
      if(item.id===id){
        carts[cartIndex].tick = false
      }
    })

  } else {
    choosenItemIds.value.push(id)
    carts.forEach((item,cartIndex)=>{
      if(item.id===id){
        carts[cartIndex].tick = true
      }
    })

  }
  uni.setStorage({
    key: 'carts',
    data: carts
  })

}
const changeItemNumber = (cartGood) => {
  let carts = uni.getStorageSync('carts')
  carts.forEach((item,index) => {
    if (item.id === cartGood.id) {
      carts[index].number = cartGood.number
    }
  })
  uni.setStorage({
    key: 'carts',
    data: carts
  })
}
const toOrderCreate = ()=>{
  // setCartTick

  uni.navigateTo({
    url: '/pages/order/create?type=cart'
  })
}
const cartGoods = ref([])
const getCart = async ()=>{
  cartGoods.value = await getCartGoods()
}

onLoad(async (options) => {
  getCart()
})
onShow(async () => {
  getCart()
})
const totalAmount = computed(() => {
  let totalPrcie = 0
  cartGoods.value.forEach((item) => {
    if (choosenItemIds.value.indexOf(item.id) !== -1) {
      totalPrcie = totalPrcie + item.price * item.number
    }
  })
  return totalPrcie.toFixed(2)
})
const onShareAppMessage = (res) => {
  if (res.from === 'button') {// 来自页面内分享按钮
    console.log(res.target)
  }
  return {
    title: '公寓设备管理小程序',
    path: '/pages/index/index',
  }
}

</script>

<template>
  <div class="top-container" style="background: none">
    <div class="default-title">购物车</div>
  </div>
  <div class="fixed-container" style="padding: 0;width: 100vw;background-color: var(--default-background)">
    <div class="platform-jingle-container">
      <div class="jingle-item">
        <image
            src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMiA5NDYuN0MyNzkuNSA5NDYuNyA5MC4zIDc1Ny41IDkwLjMgNTI1UzI3OS41IDEwMy4yIDUxMiAxMDMuMnM0MjEuNyAxODkuMiA0MjEuNyA0MjEuN1M3NDQuNSA5NDYuNyA1MTIgOTQ2Ljd6bTAtNzc2LjVjLTE5NS42IDAtMzU0LjcgMTU5LjEtMzU0LjcgMzU0LjdTMzE2LjQgODc5LjYgNTEyIDg3OS42czM1NC43LTE1OS4xIDM1NC43LTM1NC43UzcwNy42IDE3MC4yIDUxMiAxNzAuMnoiIGZpbGw9IiNiZmJmYmYiLz48cGF0aCBkPSJNNDgwLjIgNjc4LjljLTguNiAwLTE3LjItMy4zLTIzLjctOS44TDI5NS43IDUwOC40Yy0xMy4xLTEzLjEtMTMuMS0zNC4zIDAtNDcuNCAxMy4xLTEzLjEgMzQuMy0xMy4xIDQ3LjQgMGwxMzcuMSAxMzcgMjI0LjYtMjI0LjdjMTMuMS0xMy4xIDM0LjMtMTMuMSA0Ny40IDAgMTMuMSAxMy4xIDEzLjEgMzQuMyAwIDQ3LjRMNTAzLjggNjY5LjFjLTYuNSA2LjYtMTUuMSA5LjgtMjMuNiA5Ljh6IiBmaWxsPSIjYmZiZmJmIi8+PC9zdmc+"
            style="width: 14px;height: 14px"></image>
        <div>100%正品保证</div>
      </div>
      <div class="jingle-item">
        <image
            src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMiA5NDYuN0MyNzkuNSA5NDYuNyA5MC4zIDc1Ny41IDkwLjMgNTI1UzI3OS41IDEwMy4yIDUxMiAxMDMuMnM0MjEuNyAxODkuMiA0MjEuNyA0MjEuN1M3NDQuNSA5NDYuNyA1MTIgOTQ2Ljd6bTAtNzc2LjVjLTE5NS42IDAtMzU0LjcgMTU5LjEtMzU0LjcgMzU0LjdTMzE2LjQgODc5LjYgNTEyIDg3OS42czM1NC43LTE1OS4xIDM1NC43LTM1NC43UzcwNy42IDE3MC4yIDUxMiAxNzAuMnoiIGZpbGw9IiNiZmJmYmYiLz48cGF0aCBkPSJNNDgwLjIgNjc4LjljLTguNiAwLTE3LjItMy4zLTIzLjctOS44TDI5NS43IDUwOC40Yy0xMy4xLTEzLjEtMTMuMS0zNC4zIDAtNDcuNCAxMy4xLTEzLjEgMzQuMy0xMy4xIDQ3LjQgMGwxMzcuMSAxMzcgMjI0LjYtMjI0LjdjMTMuMS0xMy4xIDM0LjMtMTMuMSA0Ny40IDAgMTMuMSAxMy4xIDEzLjEgMzQuMyAwIDQ3LjRMNTAzLjggNjY5LjFjLTYuNSA2LjYtMTUuMSA5LjgtMjMuNiA5Ljh6IiBmaWxsPSIjYmZiZmJmIi8+PC9zdmc+"
            style="width: 14px;height: 14px"></image>
        <div>所有商品精挑细选</div>
      </div>
      <div class="jingle-item">
        <image
            src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMiA5NDYuN0MyNzkuNSA5NDYuNyA5MC4zIDc1Ny41IDkwLjMgNTI1UzI3OS41IDEwMy4yIDUxMiAxMDMuMnM0MjEuNyAxODkuMiA0MjEuNyA0MjEuN1M3NDQuNSA5NDYuNyA1MTIgOTQ2Ljd6bTAtNzc2LjVjLTE5NS42IDAtMzU0LjcgMTU5LjEtMzU0LjcgMzU0LjdTMzE2LjQgODc5LjYgNTEyIDg3OS42czM1NC43LTE1OS4xIDM1NC43LTM1NC43UzcwNy42IDE3MC4yIDUxMiAxNzAuMnoiIGZpbGw9IiNiZmJmYmYiLz48cGF0aCBkPSJNNDgwLjIgNjc4LjljLTguNiAwLTE3LjItMy4zLTIzLjctOS44TDI5NS43IDUwOC40Yy0xMy4xLTEzLjEtMTMuMS0zNC4zIDAtNDcuNCAxMy4xLTEzLjEgMzQuMy0xMy4xIDQ3LjQgMGwxMzcuMSAxMzcgMjI0LjYtMjI0LjdjMTMuMS0xMy4xIDM0LjMtMTMuMSA0Ny40IDAgMTMuMSAxMy4xIDEzLjEgMzQuMyAwIDQ3LjRMNTAzLjggNjY5LjFjLTYuNSA2LjYtMTUuMSA5LjgtMjMuNiA5Ljh6IiBmaWxsPSIjYmZiZmJmIi8+PC9zdmc+"
            style="width: 14px;height: 14px"></image>
        <div>售后无忧</div>
      </div>
    </div>
    <div class="cart-good-count">商品数量：{{ cartGoods.length }}件</div>
    <div class="cart-item" v-for="item in cartGoods">
      <div class="tick-icon" @click.stop="tickClick(item.id)">
        <image v-if="choosenItemIds.indexOf(item.id)!==-1" style="width: 30px;height: 30px;"
               src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjUgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmk1LjIzNzMzYTgxWkV4MEFCIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMy4xLjZDMjMwLjQ2NC42IDEuNCAyMjkuNjY1IDEuNCA1MTIuM1MyMzAuNDY0IDEwMjQgNTEzLjEgMTAyNHM1MTEuNy0yMjkuMDY2IDUxMS43LTUxMS43Uzc5NS43MzQuNiA1MTMuMS42em0yMjUuNTY3IDM5OS44NjVMNDYxLjQzIDY3Ny42MDNjLTkuNzk1IDkuNzk0LTI1LjU4NSA5Ljc5NC0zNS4zOCAwTDI4Ny42MzEgNTM5LjA4NGMtOS43OTQtOS43OTQtOS43OTQtMjUuNTg1IDAtMzUuMzggOS43OTUtOS43OTMgMjUuNTg1LTkuNzkzIDM1LjM4IDBsMTIwLjgyOSAxMjAuOTMgMjU5LjU0OC0yNTkuNTQ4YzkuNzk0LTkuNzk0IDI1LjU4NS05Ljc5NCAzNS4zOCAwIDkuNjkzIDkuNzk0IDkuNjkzIDI1LjU4NS0uMSAzNS4zOHoiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTYuMjM3MzNhODFaRXgwQUIiIGZpbGw9IiMxODdiY2EiLz48L3N2Zz4="></image>

        <image v-if="choosenItemIds.indexOf(item.id)===-1" style="width: 30px;height: 30px;"
               src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMiA5NjcuMTExYTQ1NS4xMTEgNDU1LjExMSAwIDEgMCAwLTkxMC4yMjIgNDU1LjExMSA0NTUuMTExIDAgMCAwIDAgOTEwLjIyMnptMCA1Ni44ODlBNTEyIDUxMiAwIDEgMSA1MTIgMGE1MTIgNTEyIDAgMCAxIDAgMTAyNHoiIGZpbGw9IiNiZmJmYmYiLz48L3N2Zz4="></image>

      </div>
      <div class="good-image" @click="goodClick(item.id)">
        <image :src="baseUrl+item.image"/>
      </div>
      <div class="good-desc" @click="goodClick(item.id)">
        <div class="name">{{ item.name }}</div>
        <div class="price-and-number" @click.stop="console.log('')">
          <div class="price">￥{{ item.price }}</div>
          <NumberController :max="item.stock" :min="1" v-model="item.number" :key="item.id"
                            @change="changeItemNumber(item)"/>
        </div>
      </div>
    </div>
    <div style="width: 100vw;height: 120px"></div>

  </div>
  <div class="cart-footer-container">
    <div class="choose-all-button">
      <image v-if="choose" style="width: 24px;height: 24px;margin-right: 7.5px"
             src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjUgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmk1LjIzNzMzYTgxWkV4MEFCIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMy4xLjZDMjMwLjQ2NC42IDEuNCAyMjkuNjY1IDEuNCA1MTIuM1MyMzAuNDY0IDEwMjQgNTEzLjEgMTAyNHM1MTEuNy0yMjkuMDY2IDUxMS43LTUxMS43Uzc5NS43MzQuNiA1MTMuMS42em0yMjUuNTY3IDM5OS44NjVMNDYxLjQzIDY3Ny42MDNjLTkuNzk1IDkuNzk0LTI1LjU4NSA5Ljc5NC0zNS4zOCAwTDI4Ny42MzEgNTM5LjA4NGMtOS43OTQtOS43OTQtOS43OTQtMjUuNTg1IDAtMzUuMzggOS43OTUtOS43OTMgMjUuNTg1LTkuNzkzIDM1LjM4IDBsMTIwLjgyOSAxMjAuOTMgMjU5LjU0OC0yNTkuNTQ4YzkuNzk0LTkuNzk0IDI1LjU4NS05Ljc5NCAzNS4zOCAwIDkuNjkzIDkuNzk0IDkuNjkzIDI1LjU4NS0uMSAzNS4zOHoiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTYuMjM3MzNhODFaRXgwQUIiIGZpbGw9IiMxODdiY2EiLz48L3N2Zz4="></image>

      <image v-if="choose" style="width: 24px;height: 24px;margin-right: 7.5px"
             src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMiA5NjcuMTExYTQ1NS4xMTEgNDU1LjExMSAwIDEgMCAwLTkxMC4yMjIgNDU1LjExMSA0NTUuMTExIDAgMCAwIDAgOTEwLjIyMnptMCA1Ni44ODlBNTEyIDUxMiAwIDEgMSA1MTIgMGE1MTIgNTEyIDAgMCAxIDAgMTAyNHoiIGZpbGw9IiNiZmJmYmYiLz48L3N2Zz4="></image>

      <div @click="chooseAll">全选({{ choosenItemIds.length }})</div>
    </div>
    <div class="total-calc-button" @click="toOrderCreate">
      <div class="total-price">￥{{ totalAmount }}</div>
      <div class="calc-button">
        下单结算
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.platform-jingle-container {
  padding: 15px;
  display: flex;
  background-color: var(--default-background);
  justify-content: space-around;

  .jingle-item {
    display: flex;
    font-size: 14px;
    align-items: center;

    image {
      margin-right: 3px;
    }
  }
}




</style>