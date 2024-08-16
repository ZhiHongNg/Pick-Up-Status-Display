<script setup>
import {onLoad} from "@dcloudio/uni-app";
import {deleteLock, getDevice, rename} from "@/utils/api/Lock";
import {ref} from "vue"
import InputDialog from "@/components/InputDialog.vue";
import {UserStore} from '@/store/UserStore'

const userStore = UserStore()
const device = ref({})
const inputDialogRef = ref()
const lockId = ref('')
const editDeviceName = () => {
  inputDialogRef.value.showDialog(device.value.lockAlias)
}

const updateLockAlias = async (newLockAlias) => {
  const response = await rename(device.value.lockId, newLockAlias, userStore.phone)
  if (response.data.code === 200) {
    uni.showToast({
      icon: 'success',
      title: '修改成功'
    })
    inputDialogRef.value.hideDialog()
    getCurrentDevice()
  }
}
const resetLock = () => {
  uni.vibrateShort()

  uni.showModal({
    title: '提示',
    content: '需通过蓝牙重置，是否已经靠近当前门锁？',
    success: async (res) => {
      if (res.confirm) {
        uni.vibrateShort()

        const resetCallback = await lockplugin.resetLock(device.value.lockData, disconnectCallback => {
          //todo:重置失败处理 disconnectCallback
        })
        if(resetCallback.errorCode===0){
          const response = await deleteLock(device.value.lockId, userStore.phone)
          if (response.data.code === 200) {
            uni.showToast({
              icon: 'success',
              title: '重置成功'
            })
            uni.redirectTo({
              url:'/pages/index/index'
            })
          }
        }

      } else if (res.cancel) {
        console.log('用户点击取消');
      }
    }
  });

}
const switchChange = () => {

}
const getCurrentDevice = async () => {
  device.value = await getDevice(userStore.phone, lockId.value)
  uni.setNavigationBarTitle({
    title: device.value.lockAlias
  })
}
onLoad(async (options) => {
  lockId.value = options.lockId
  getCurrentDevice()
})
const back = ()=>{
  uni.navigateBack()
}
</script>

<template>
  <div class="top-container">
    <div class="default-title" @click="back">其他设置</div>
  </div>
  <div class="fixed-container">
    <uni-list>
      <uni-list-item title="锁名称" :rightText="device.lockAlias" link @click="editDeviceName"></uni-list-item>
    </uni-list>
    <div class="default-button" @click="resetLock">重置解绑</div>
    <InputDialog ref="inputDialogRef" @confirm="updateLockAlias" title="请输入新的设备名称"/>
  </div>

</template>

<style scoped lang="scss">
.delete-button {
  width: 70%;
  padding: 15px 10px;
  text-align: center;
  background-color: #FF5722;
  color: white;
  margin: 15px auto;
  border-radius: 5px;
}
</style>