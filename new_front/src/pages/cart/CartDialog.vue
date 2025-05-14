<script setup>
import {ref, watch,onMounted} from "vue";
import moment from "moment";
import {send} from "@/utils/api/Key";
import {UserStore} from '@/store/UserStore'
import NumberController from "@/components/NumberController.vue";
import {Good} from "@/utils/api/Good";
import {baseUrl} from "@/utils/request";

const userStore = UserStore()
const dialogVisable = ref(false)
const cartNumber = ref(1)
const startDateChange = (v) => {
  dateStart.value = v
}
const dateEndChange = (v) => {
  dateEnd.value = v
}
const hideDialog = () => {
  dialogVisable.value = false

}
const goodId = ref()
const good = ref({
  id: null,
  name: '',
  price: 0,
  image: 0,
})

const showDialog = async (id) => {
  goodId.value = id
  const response = await Good.show(id)
  good.value = response.data.data
  cartNumber.value = await loadCartNumber()
  dialogVisable.value = true
}
onMounted(()=>{
  cartNumber.value = loadCartNumber()
})
const loadCartNumber = async () => {
  let number = 1;
  let carts = uni.getStorageSync('carts')
  console.log('carts',carts)
  if (!carts) {
    number = 1
  } else {
    carts.forEach((item, i) => {
      if (item.id === goodId.value) {
        number = item.number
      }
    })

  }
  console.log('number',number)
  return number
}

const emit = defineEmits(['success'])
const dateStart = ref('')
const dateEnd = ref('')
const formData = ref()
const addKey = async () => {
  formData.value.startDate = moment(dateStart.value).minute(0).second(0).hour(0).valueOf()
  formData.value.endDate = moment(dateEnd.value).minute('59').second(59).hour(23).valueOf()
  if (formData.value.startDate >= formData.value.endDate) {
    uni.showToast({
      icon: 'none',
      title: '开始时间需要大于结束时间'
    })
    return
  }
  const response = await send(formData.value)
  console.log('response', response)
  uni.showModal({
    title: "发送成功",
    content: '',
    confirmText: '确定'
  })
  emit('success')
  hideDialog()


}
const changeCartNumber = (value) => {
  uni.vibrateShort()
  cartNumber.value = value
}
watch(() => cartNumber.value, (newValue) => {
  console.log('newValue', newValue)
})
const saveCart = () => {
  let carts = uni.getStorageSync('carts')
  if (!carts) carts = []
  let index = -1
  carts.forEach((item, i) => {
    if (item.id === goodId.value) {
      index = i
    }
  })
  if (index === -1) {
    carts.push({
      id: good.value.id,
      number: cartNumber.value,
      tick:false
    })
  } else {
    carts[index].number = cartNumber.value
  }
  uni.setStorage({
    key: 'carts',
    data: carts
  })
  hideDialog()

  uni.showToast({
    title: '已将商品加入购物车',
    icon: 'success'
  })
}


defineExpose({
  showDialog, hideDialog
})
</script>

<template>
  <main>
    <div v-if="dialogVisable" class="mask-container" @click="hideDialog"></div>
    <div :class="{'visable':dialogVisable}" class="main-container">
      <div class="cart-item">
        <div class="good-image">
          <image
                 :src="baseUrl+good.image"></image>
        </div>
        <div class="good-desc">
          <div class="name">{{ good.name }}</div>
          <div class="price-and-number">
            <div class="price">
              ￥{{ good.price }}
              <div class="stock">剩余库存：{{good.stock}}</div>
            </div>
            <NumberController v-model="cartNumber" @change="changeCartNumber" :min="1" :max="good.stock"/>
          </div>
        </div>
      </div>
      <div class="default-button" @click="saveCart">加入购物车</div>
    </div>
  </main>
</template>

<style scoped lang="scss">
.cart-item {
  margin-bottom: 0px;
}
.stock{
  color: #818181;
  font-size: 12px;
}
</style>