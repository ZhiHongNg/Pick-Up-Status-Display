<script setup>
import {ref} from 'vue'
import moment from "moment";
import {send} from "@/utils/api/Key";
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
const showDialog = (lockId, phone) => {
  formData.value = {
    receiverUsername: '',
    keyName: '',
    userName: userStore.phone,
    lockId: '',
    remoteEnable: 4,
    keyRight: 0,
    startDate: '',
    endDate: '',
  }
  formData.value.lockId = lockId
  formData.value.userName = phone
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
const keyRightChange = (value) => {
  formData.value.keyRight = value.detail.value ? 1 : 0
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
    <div v-if="dialogVisable" class="mask-container" @click="hideDialog"></div>
    <div :class="{'visable':dialogVisable}" class="main-container">
      <div class="default-title">发送钥匙</div>
      <div class="form-container">
        <div class="item">
          <div class="name">接收者电话</div>
          <div class="value">
            <input class="default-input" type="text" placeholder="接收钥匙用户的电话号码"
                   v-model="formData.receiverUsername">
          </div>
        </div>
        <div class="item">
          <div class="name">接收者姓名</div>
          <div class="value">
            <input class="default-input" type="text" placeholder="如:保洁阿姨" v-model="formData.keyName">
          </div>
        </div>
        <div class="item">
          <div class="name">时间</div>
          <div class="value">
            <uni-datetime-picker type="daterange" returnType="timestamp" :value="[dateStart,dateEnd]" @change="dateChange" />
          </div>
        </div>
        <div class="item">
          <div class="name">
            <span>是否管理员</span>

          </div>
          <div class="value">
            <switch color="#6d5ffd" @change="keyRightChange" style="    transform: scale(0.9);"/>
          </div>
        </div>
        <div class="button-container">
          <div class="default-button" @click="addKey">确认添加</div>
          <div class="text-button" @click="hideDialog">取消</div>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped lang="scss">
</style>