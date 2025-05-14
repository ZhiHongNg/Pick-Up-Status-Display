<script setup>
import {ref} from 'vue'
import moment from "moment";
import {send} from "@/utils/api/Key";
import {gsap} from "gsap";
import {UserStore} from '@/store/UserStore'

const userStore = UserStore()
const dialogVisable = ref(false)
const startDateChange = (v) => {
  dateStart.value = v
}
const dateEndChange = (v) => {
  dateEnd.value = v
}
const hideDialog = () => {
  dialogVisable.value = false

}
const carDetail = ref()
const showDialog = (carItem, x = 30, y = 0) => {
  setTimeout(()=>{
    carDetail.value = carItem
    gsap.to('.dialog-container',{
      left:x,
      top:y,
      duration:.5
    })
  })
  dialogVisable.value = true
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
const dateChange = (dateRange) => {
  dateStart.value = dateRange[0]
  dateEnd.value = dateRange[1]
}

defineExpose({
  showDialog, hideDialog
})
</script>

<template>
  <main>
    <div :class="{'visable':dialogVisable}" class="dialog-container">
      <div class="default-title" >车辆详情</div>
      <div class="car-name">{{carDetail.plate}}</div>
    </div>
  </main>
</template>

<style scoped lang="scss">
.dialog-container {
  background-color: white;
  left: 0;
  top: 0;
  position: fixed;
  width: calc(100vw / 4);
  height: calc(100vh / 3);
  padding: 15px;
  border-radius: 12px;
  display: none;

}
.visable{
  display: block;

}
</style>