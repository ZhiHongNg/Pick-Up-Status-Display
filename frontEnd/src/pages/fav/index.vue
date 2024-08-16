<script setup>
import {ref} from 'vue'
import {onLoad, onShow} from '@dcloudio/uni-app'
import {UserStore} from '@/store/UserStore'
import {getFavGoods} from "@/utils/service/GoodService";
import EmptyBlock from "@/components/EmptyBlock.vue";
import {baseUrl} from "@/utils/request";

const choose = ref(false)
const userStore = UserStore()
const  favGoods = ref([])
const getFav = async ()=>{
  favGoods.value = await getFavGoods()
  uni.hideLoading()
}
onShow(async () => {
  uni.showLoading({
    title: '加载中'
  })
  getFav()
})
const goodClick = async (id) => {
  uni.vibrateShort()
  uni.navigateTo({
    url: '/pages/good/info' + '?id=' + id
  })

}

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
    <div class="default-title">商品收藏</div>
  </div>
  <div class="fixed-container" style="padding: 0;width: 100vw;background-color: var(--default-background)">
    <div class="cart-good-count">共{{ favGoods.length }}件商品</div>
    <EmptyBlock v-if="!favGoods.length" msg="您未收藏任何商品"></EmptyBlock>
    <div class="cart-item" v-for="item in favGoods" @click="goodClick(item.id)">

      <div class="good-image">
        <image :src="baseUrl+item.image"></image>
      </div>
      <div class="good-desc">
        <div class="name">{{ item.name }}</div>
        <div class="price-and-number">
          <div class="price">￥{{ item.price }}</div>
        </div>
      </div>
    </div>
    <div style="width: 100vw;height: 120px"></div>

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