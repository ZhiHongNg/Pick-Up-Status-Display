<script setup>
import {ref} from 'vue'
import {addLockPassword} from "@/utils/api/Password";
import moment from "moment";
import {getKeyboardPwdType} from "@/utils/service/LockPasswordService";
import {arrayToValueTextArray} from "@/utils/service/commonService";
import {UserStore} from '@/store/UserStore'

const dialogVisable = ref(false)
const hideDialog = () => {
  formData.value = {
    userName: userStore.phone,
    lockId: '',
    keyboardPwdVersion: 4,
    keyboardPwdName: '',
    keyboardPwdType: '',
    startDate: '',
    endDate: '',
  }
  dialogVisable.value = false
}
const showDialog = (lockId, phone) => {
  formData.value.lockId = lockId
  formData.value.userName = phone
  dialogVisable.value = true
}
const userStore = UserStore()
const emit = defineEmits(['success'])
const dateStart = ref()
const dateEnd = ref()
const formData = ref({
  userName: userStore.phone,
  lockId: '',
  keyboardPwdVersion: 4,
  keyboardPwdName: '',
  keyboardPwdType: '',
  startDate: '',
  endDate: '',
})
const addPassword = async () => {

  formData.value.startDate = moment(dateStart.value).minute(0).second(0).hour(0).valueOf()
  formData.value.endDate = moment(dateEnd.value).minute('59').second(59).hour(23).valueOf()
  if (formData.value.startDate >= formData.value.endDate) {
    uni.showToast({
      icon: 'none',
      title: '开始时间需要大于结束时间'
    })
    return
  }
  const response = await addLockPassword(formData.value)
  console.log('response',response)
  uni.showModal({
    title: "发送成功",
    content: '密码为：' + response.data.data.keyboardPwd,
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
    <div v-if="dialogVisable" class="mask-container" @click="hideDialog"></div>
    <div :class="{'visable':dialogVisable}" class="main-container">
      <div class="default-title">添加键盘密码</div>
      <div class="form-container">
        <div class="item">
          <div class="name">密码名称</div>
          <div class="value">
            <input class="default-input" type="text" placeholder="如：保洁阿姨" v-model="formData.keyboardPwdName">
          </div>
        </div>
        <div class="item">
          <div class="name">
            <span>密码类型</span>
            <uni-icons type="question"></uni-icons>
          </div>
          <div class="value">
            <uni-data-select
                v-model="formData.keyboardPwdType"
                :localdata="arrayToValueTextArray(getKeyboardPwdType())"
                @change="arrayToValueTextArray(getKeyboardPwdType())"
            ></uni-data-select>
          </div>
        </div>
        <div class="item">
          <div class="name">时间</div>
          <div class="value">
            <uni-datetime-picker type="daterange" returnType="timestamp" :value="[dateStart,dateEnd]" @change="dateChange" />
          </div>
        </div>
        <div class="button-container">
          <div class="default-button" @click="addPassword">确认添加</div>
          <div class="text-button" @click="hideDialog">取消</div>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped lang="scss"></style>