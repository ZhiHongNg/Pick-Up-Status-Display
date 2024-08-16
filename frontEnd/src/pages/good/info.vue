<script setup>
import {ref} from 'vue'
import {onLoad, onShow} from '@dcloudio/uni-app'
import {UserStore} from '@/store/UserStore'
import CartDialog from "@/pages/cart/CartDialog.vue";
import {Good} from "@/utils/api/Good";
import {baseUrl} from "@/utils/request";

let lockId = ref(null)
const device = ref()
const love = ref(false)
const cartDialogRef = ref()
const userStore = UserStore()
const toOrderCreate = (goodId) => {
  uni.navigateTo({
    url: '/pages/order/create?type=good&goodId=' + goodId,
  })
}
const addCart = (goodId) => {
  cartDialogRef.value.showDialog(goodId)
}
const back = () => {
  uni.switchTab({
    url: '/pages/index/index'
  })
}
const id = ref()
const good = ref()
const getGoodInfo = async () => {
  uni.showLoading({
    title: '加载中'
  })
  const response = await Good.show(id.value)

  good.value = response.data.data
  uni.setNavigationBarTitle({
    title: good.value.name
  })
  uni.hideLoading()
}
onLoad(async (options) => {
  if (!options.id) {
    uni.showModal({
      title: '商品不存在或已下架'
    }, confirm(() => {
      console.log('123')
    }))
    return
  }
  id.value = options.id
  getGoodInfo()
})
onShow(async () => {
  checkFav()
})
const toHome = () => {
  uni.vibrateShort()
  uni.switchTab({
    url: '/pages/index/index'
  })
}
const toCart = () => {
  uni.vibrateShort()
  uni.switchTab({
    url: '/pages/cart/index'
  })

}
const checkFav = () => {
  let favGood = uni.getStorageSync('favGood')
  if (favGood.indexOf(id.value) === -1) {
    love.value = false
  } else {
    love.value = true
  }
}
const addFav = () => {
  let favGood = uni.getStorageSync('favGood')
  if(!favGood)favGood = []
  if (favGood.indexOf(id.value) === -1) {
    favGood.push(id.value)
    uni.setStorage({
      key: 'favGood',
      data: favGood
    })
  } else {
    favGood.splice(favGood.indexOf(id.value), 1)
    uni.setStorage({
      key: 'favGood',
      data: favGood
    })

  }
  uni.vibrateShort()
  if (love.value === false) {
    uni.showToast({
      title: '收藏成功',
      icon: 'success'
    })
  }
  love.value = !love.value
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
const formatRichText = (html) => {
  let newContent = html.replace(/<img[^>]*>/gi, function (match, capture) {
    match = match.replace(/style="[^"]+"/gi, '').replace(/style='[^']+'/gi, '');
    match = match.replace(/width="[^"]+"/gi, '').replace(/width='[^']+'/gi, '');
    match = match.replace(/height="[^"]+"/gi, '').replace(/height='[^']+'/gi, '');
    return match;
  });
  newContent = newContent.replace(/style="[^"]+"/gi, function (match, capture) {
    match = match.replace(/width:[^;]+;/gi, 'width:100%;').replace(/width:[^;]+;/gi, 'width:100%;');
    return match;
  });
  newContent = newContent.replace(/<br[^>]*\/>/gi, '');
  newContent = newContent.replace(/\<img/gi, '<img style="width:100%;height:auto;display:block;margin:10px 0;"');
  return newContent;
}

</script>

<template>
  <div class="top-container" style="background: none">
    <div class="default-title" @click="back">产品详情</div>
  </div>
  <!--  搜索-->
  <div class="fixed-container" style="padding: 0;width: 100vw;background-color: var(--default-background)">
    <div class="good-banner-container">
      <swiper style="width: 100vw;height: 42vh" circular :indicator-dots="true" :autoplay="true" :interval="2000"
              :duration="1000">
        <swiper-item>
          <div class="banner-item" :style="{backgroundImage: 'url('+baseUrl+good.image+')'}"></div>
        </swiper-item>
      </swiper>
    </div>
    <div class="good-desc-container">
      <div class="amount">
        <div class="price">
          <span class="currency">￥</span>
          <span>{{ good.price }}</span>
          <div class="o-price">
            <div>原价</div>
            <div style="text-decoration: line-through">￥{{ good.original_price }}</div>
          </div>
        </div>

        <div class="share-button">
          <image style="width: 30px;height: 30px;" :src="good.image"></image>
        </div>
      </div>
      <div class="name">{{ good.name }}</div>

    </div>
    <div class="good-detail-container">
      <div class="title">
        产品介绍
      </div>
      <div class="detail">
        <rich-text type="node" :nodes="formatRichText(good.detail)"/>
      </div>
    </div>
    <div class="good-footer-container">
      <div class="quick-button">
        <div class="button-item" @click="toHome">
          <div class="icon">
            <image style="width: 20px;height: 20px"
                   src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMi4wOTMgMTQ2Ljg1YzIyMy42NTEgMTY5LjAwNyAzMjUuNzI1IDI0Ni42NDUgMzcyLjI3IDI4Mi4yMDZ2NDU1LjMwOEgxMzkuNjM3VjQyOS41NjhjNDUuNTIyLTM0LjYzIDE0Ni45NDQtMTExLjUyMyAzNzIuNDU3LTI4Mi43MTdtLS4wOTMtNy4yMTV2LTkzLjA5Yy0xMy4yMTkgMC0yNi4zNDUgMy41MzctMzcuMjgzIDExLjc3NS0yNjYuMjQgMjAxLjk2MS0zNjcuMTA0IDI3OC41NzUtNDA1LjI3MSAzMDcuNTI2YTU3LjY3IDU3LjY3IDAgMCAwLTIyLjkgNDUuNzU0djUwNy42NzJjMCAzMi4wNyAyNi45OTYgNTguMTgyIDYwLjI3NiA1OC4xODJoODEwLjM1NmMzMy4yOCAwIDYwLjI3Ny0yNi4xMTIgNjAuMjc3LTU4LjE4MnYtNTA4LjA5YzAtMTcuNzM0LTguMzc5LTM0LjQ5LTIyLjcxNS00NS41NjgtMzcuNzk1LTI4Ljk5OC0xMzguMjQtMTA1LjUxOS00MDUuNDEtMzA3LjM4N0E2Mi4wOTIgNjIuMDkyIDAgMCAwIDUxMiA0Ni41NDV2OTMuMDkxeiIgZmlsbD0iI2NkY2RjZCIvPjwvc3ZnPg=="></image>
          </div>
          <div class="text">首页</div>
        </div>
        <div class="button-item" @click="addFav">
          <div class="icon">
            <image v-if="!love" style="width: 20px;height: 20px"
                   src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmk4LjQ0YjMzYTgxYmtXSXJUIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTkyMyAyODMuNmEyNjAuMDQgMjYwLjA0IDAgMCAwLTU2LjktODIuOCAyNjQuNCAyNjQuNCAwIDAgMC04NC01NS41QTI2NS4zNCAyNjUuMzQgMCAwIDAgNjc5LjcgMTI1Yy00OS4zIDAtOTcuNCAxMy41LTEzOS4yIDM5LTEwIDYuMS0xOS41IDEyLjgtMjguNSAyMC4xLTktNy4zLTE4LjUtMTQtMjguNS0yMC4xLTQxLjgtMjUuNS04OS45LTM5LTEzOS4yLTM5LTM1LjUgMC02OS45IDYuOC0xMDIuNCAyMC4zLTMxLjQgMTMtNTkuNyAzMS43LTg0IDU1LjVhMjU4LjQ0IDI1OC40NCAwIDAgMC01Ni45IDgyLjhjLTEzLjkgMzIuMy0yMSA2Ni42LTIxIDEwMS45IDAgMzMuMyA2LjggNjggMjAuMyAxMDMuMyAxMS4zIDI5LjUgMjcuNSA2MC4xIDQ4LjIgOTEgMzIuOCA0OC45IDc3LjkgOTkuOSAxMzMuOSAxNTEuNiA5Mi44IDg1LjcgMTg0LjcgMTQ0LjkgMTg4LjYgMTQ3LjNsMjMuNyAxNS4yYzEwLjUgNi43IDI0IDYuNyAzNC41IDBsMjMuNy0xNS4yYzMuOS0yLjUgOTUuNy02MS42IDE4OC42LTE0Ny4zIDU2LTUxLjcgMTAxLjEtMTAyLjcgMTMzLjktMTUxLjYgMjAuNy0zMC45IDM3LTYxLjUgNDguMi05MSAxMy41LTM1LjMgMjAuMy03MCAyMC4zLTEwMy4zLjEtMzUuMy03LTY5LjYtMjAuOS0xMDEuOXpNNTEyIDgxNC44UzE1NiA1ODYuNyAxNTYgMzg1LjVDMTU2IDI4My42IDI0MC4zIDIwMSAzNDQuMyAyMDFjNzMuMSAwIDEzNi41IDQwLjggMTY3LjcgMTAwLjRDNTQzLjIgMjQxLjggNjA2LjYgMjAxIDY3OS43IDIwMWMxMDQgMCAxODguMyA4Mi42IDE4OC4zIDE4NC41IDAgMjAxLjItMzU2IDQyOS4zLTM1NiA0MjkuM3oiIGZpbGw9IiNkYmRiZGIiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTYuNDRiMzNhODFia1dJclQiIGNsYXNzPSJzZWxlY3RlZCIvPjxwYXRoIGQ9Ik02NzkuNyAyMDFjLTczLjEgMC0xMzYuNSA0MC44LTE2Ny43IDEwMC40QzQ4MC44IDI0MS44IDQxNy40IDIwMSAzNDQuMyAyMDFjLTEwNCAwLTE4OC4zIDgyLjYtMTg4LjMgMTg0LjUgMCAyMDEuMiAzNTYgNDI5LjMgMzU2IDQyOS4zczM1Ni0yMjguMSAzNTYtNDI5LjNDODY4IDI4My42IDc4My43IDIwMSA2NzkuNyAyMDF6IiBmaWxsPSIjZmZmIiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmk0LjQ0YjMzYTgxYmtXSXJUIiBjbGFzcz0ic2VsZWN0ZWQiLz48L3N2Zz4="></image>
            <image v-if="love" style="width: 20px;height: 20px"
                   src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMiA4OTZhNDIuNjY3IDQyLjY2NyAwIDAgMS0zMC4yOTMtMTIuMzczTDE1MC4xODcgNTUxLjY4YTIyNC40MjcgMjI0LjQyNyAwIDAgMSAwLTMxNS43MzMgMjIzLjU3MyAyMjMuNTczIDAgMCAxIDMxNS43MzMgMGw0Ni4wOCA0Ni4wOCA0Ni4wOC00Ni4wOGEyMjMuNTczIDIyMy41NzMgMCAwIDEgMzE1LjczMyAwIDIyNC40MjcgMjI0LjQyNyAwIDAgMSAwIDMxNS43MzNsLTMzMS41MiAzMzEuOTQ3QTQyLjY2NyA0Mi42NjcgMCAwIDEgNTEyIDg5NnoiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTE2LjQ0YjMzYTgxYmtXSXJUIiBjbGFzcz0ic2VsZWN0ZWQiIGZpbGw9IiNkODFlMDYiLz48L3N2Zz4="></image>
          </div>
          <div class="text">{{ love == true ? '已收藏' : '收藏' }}</div>
        </div>
        <div class="button-item" @click="toCart">
          <div class="icon">
            <image style="width: 20px;height: 20px"
                   src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTM1NC43NzggODIxLjEwNWMtMzQuMDkxIDAtNjEuNzAzIDI3LjYxMi02MS43MDMgNjEuNzAzIDAgMzQuMDkyIDI3LjYxMiA2MS43MDQgNjEuNzAzIDYxLjcwNHM2MS43MDQtMjcuNjEyIDYxLjcwNC02MS43MDRjMC0zNC4wOTEtMjcuNjEzLTYxLjcwMy02MS43MDQtNjEuNzAzem00MzEuOTIzIDBjLTM0LjA5MSAwLTYxLjcwMyAyNy42MTItNjEuNzAzIDYxLjcwMyAwIDM0LjA5MiAyNy42MTIgNjEuNzA0IDYxLjcwMyA2MS43MDRzNjEuNzA0LTI3LjYxMiA2MS43MDQtNjEuNzA0YzAtMzQuMDkxLTI3LjYxMi02MS43MDMtNjEuNzA0LTYxLjcwM3ptNjEuMjEtMzAuODUySDM0Ni43ODljLTQ0LjQ1NyAwLTgzLjU0Ny0zNS4xNy04OC45NzYtODAuMDZsLTUyLjMyNS0zNjguMzY5LTI5Ljc3Mi0xNzIuMTUyYy0xLjg1MS0xNS4wMjUtMTUuNTUtMjcuMzA0LTI4Ljk0LTI3LjMwNGgtMzkuMzA0Yy0xNy4wMyAwLTMwLjg1Mi0xMy44MjEtMzAuODUyLTMwLjg1MXMxMy44MjItMzAuODUyIDMwLjg1Mi0zMC44NTJoMzkuMzA1YzQ1LjAxMiAwIDg0LjUzMyAzNS4xNzEgODkuOTk1IDgwLjA2bDI5LjcxIDE3MS41MDUgNTIuNTQgMzY5Ljk0MmMxLjcyNyAxNC4zMTUgMTQuNjg1IDI2LjM3OCAyNy43NjYgMjYuMzc4SDg0Ny45MWMxNy4wNjEgMCAzMC44NTIgMTMuNzkxIDMwLjg1MiAzMC44NTIuMDAxIDE3LjA2LTEzLjc5IDMwLjg1MS0zMC44NSAzMC44NTF6bS00NjIuNTYtMTIzLjQwNmMtMTYuMDExIDAtMjkuNTU2LTEyLjM0MS0zMC43MjctMjguNi0xLjI2NS0xNi45OTkgMTEuNTA4LTMxLjc3NiAyOC40NzYtMzMuMDFsNDE4LjUzNC0zMC44NTJjMTUuMzYzLS4wOTIgMjguMzIyLTEyLjE1NiAzMC4wMTgtMjUuODg1bDQ4LjU5Mi0yNzguMDM1YzEuMjM0LTEwLjM2Ni0xLjYzNS0yMS43Mi03LjgzNy0yOC43NTQtMy45OC00LjUwNS04Ljk3Ny02Ljc4OC0xNC44MDktNi43ODhIMzIzLjQzMmMtMTcuMDMgMC0zMC44NTEtMTMuODIxLTMwLjg1MS0zMC44NTFzMTMuODIxLTMwLjg1MiAzMC44NTEtMzAuODUyaDUzNC4xNjZjMjMuNTcxIDAgNDUuMjI4IDkuNzggNjAuOTk0IDI3LjU4MSAxOC4wMTggMjAuMzMxIDI2LjI4NiA0OC45OTMgMjIuNjc3IDc4LjcwM0w4OTIuNjQ2IDU1Ny41N2MtNS4yNDQgNDMuMjU0LTQ0LjMwMyA3OC40MjUtODguNzYgNzguNDI1bC00MTYuMjUgMzAuNzZjLS43NzIuMDYxLTEuNTQ0LjA5Mi0yLjI4NC4wOTJ6IiBmaWxsPSIjY2RjZGNkIi8+PC9zdmc+"></image>
          </div>
          <div class="text">购物车</div>
        </div>
      </div>
      <div class="buy-cart-container">
        <div class="add-cart-button" @click="addCart(good.id)">加入购物车</div>
        <div class="buy-button" @click="toOrderCreate(good.id)">立即购买</div>
      </div>
    </div>
  </div>
  <CartDialog ref="cartDialogRef"></CartDialog>
</template>

<style scoped lang="scss">
.good-container {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;

  .good-item {
    margin-bottom: 15px;
    width: 42vw;

    .image {
      height: 42vw;
      width: 42vw;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 12px;
    }

    .name {
      font-size: 12px;
    }

    .price {
      display: flex;
      margin-top: 7.5px;
      justify-content: space-between;
      align-items: center;

      .amount {
        display: flex;
        margin-top: 5px;
        font-size: 18px;
        color: var(--default-color);
        font-weight: bold;

        span {
          font-size: 12px;
        }
      }

      .cart-button {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--default-color);
        border-radius: 50%;
      }
    }
  }
}

.good-banner-container {
  width: 100vw;
}

.banner-item {
  width: 100vw;
  height: 42vh;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center center;
}

.good-desc-container {
  background-color: white;
  width: calc(100% - 30px);
  padding: 15px;
  margin-bottom: 15px;

  .amount {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 7.5px;

    .price {
      .currency {
        font-size: 12px;
      }

      display: flex;
      align-items: baseline;
      margin-top: 5px;
      font-size: 18px;
      color: var(--default-color);
      font-weight: bold;

      span {
        font-size: 20px;
      }
    }
  }

  .name {
    font-weight: bold;
    margin-bottom: 7.5px;
    font-size: 16px;
  }

  .o-price {
    display: flex;
    color: var(--second-text-color);
    font-size: 12px;
    margin-left: 7.5px;
  }
}

.good-detail-container {
  padding: 15px 15px 45px 15px;
  background-color: white;

  .title {
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 15px;
  }

  .detail {
    width: 100%;

    p img, p image, img, image {
      width: 100%;
    }
  }
}

.good-footer-container {
  border-top: 1px solid #ddd;
  position: fixed;
  bottom: 0;
  left: 0;
  display: flex;
  background-color: white;
  padding: 15px 7.5px 30px 7.5px;
  width: calc(100% - 15px);
  justify-content: space-between;

  .quick-button {
    flex: 3;
    display: flex;
    justify-content: space-around;
    align-items: center;

    .button-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      color: var(--second-text-color);
    }
  }
}

rich-text {
  width: 100%;

  p image, image, p img, img {
    width: 100%;
  }
}
</style>