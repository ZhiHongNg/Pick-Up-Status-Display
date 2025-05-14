<script setup>
import {ref} from 'vue'
import moment from "moment";
import {send} from "@/utils/api/Key";
import {addLockFingerprints} from "@/utils/api/Fingerprint";
import {UserStore} from '@/store/UserStore'
const userStore = UserStore()
const dialogVisable = ref(false)
const hideDialog = () => {
  dialogVisable.value = false

}
const showDialog = (lockData,lockId, phone) => {
  formData.value.lockData = lockData
  formData.value.lockId = lockId
  formData.value.userName = phone
  dialogVisable.value = true
}
const startDateChange = (v) => {
  dateStart.value = v
}
const dateEndChange = (v) => {
  dateEnd.value = v
}
const emit = defineEmits(['success'])
const dateStart = ref()
const dateEnd = ref()
const formData = ref({
  fingerprintName: '',
  lockId: '',
  fingerprintNumber: '',
  fingerprintType: 1,
  userName: userStore.phone,
  endDate: '',
})
const addFingerPrint = async () => {
  formData.value.startDate = moment(dateStart.value).minute(0).second(0).hour(0).valueOf()
  formData.value.endDate = moment(dateEnd.value).minute('59').second(59).hour(23).valueOf()
  if (formData.value.startDate >= formData.value.endDate) {
    uni.showToast({
      icon: 'none',
      title: '开始时间需要大于结束时间'
    })
    return
  }
  uni.showLoading({
    title:'请按照锁提示操作',
    duration:15000
  })

  const responseasd = await lockplugin.addFingerprint(formData.value.startDate, formData.value.endDate, formData.value.lockData)
  if (responseasd.errorCode === 0) {
    await addLockFingerprints({
      lockId:formData.value.lockId,
      fingerprintNumber:responseasd.fingerprintNum,
      userName:formData.value.userName,
      startDate:formData.value.startDate,
      endDate:formData.value.endDate,
      fingerprintType:1,
      fingerprintName:formData.value.fingerprintName
    })
    uni.hideLoading()
    uni.showModal({
      title: "操作成功",
      content: '',
      confirmText: '确定'
    })
  } else {
    uni.showToast({
      icon: 'none',
      title: responseasd.errorMsg
    })
  }


  emit('success')
  hideDialog()


}
const keyRightChange = (value) => {

  formData.value.keyRight = value.detail.value ? 1 : 0
}
defineExpose({
  showDialog, hideDialog
})
</script>

<template>
  <main >
    <div v-if="dialogVisable" class="mask-container" @click="hideDialog"></div>
    <div :class="{'visable':dialogVisable}" class="main-container">
      <div class="default-title">添加指纹密码</div>
      <div class="form-container">
        <div class="item">
          <div class="name">指纹名称</div>
          <div class="value">
            <input class="default-input" type="text" placeholder="如:保洁阿姨" v-model="formData.fingerprintName">
          </div>
        </div>
        <div class="item">
          <div class="name">类型</div>
          <div class="value">

            <input class="default-input" type="text" disabled value="普通指纹">
          </div>
        </div>
        <div class="item">
          <div class="name">开始时间</div>
          <div class="value">
            <uni-datetime-picker
                type="date" :value="dateStart" @change="startDateChange"
            />
          </div>
        </div>
        <div class="item">
          <div class="name">结束时间</div>
          <div class="value">
            <uni-datetime-picker
                type="date"
                :value="dateEnd" @change="dateEndChange"
            />
          </div>
        </div>
        <div class="button-container">
          <div class="default-button " @click="addFingerPrint">确认添加</div>
          <div class="text-button" @click="hideDialog">取消</div>

        </div>
      </div>
    </div>
  </main>
</template>

<style scoped lang="scss"></style>