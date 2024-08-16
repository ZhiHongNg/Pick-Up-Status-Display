<script setup>
import {drop} from "@/utils/api/Key";
import {deletLockPassword} from "@/utils/api/Password";
import {dropFingerPrint} from "@/utils/api/Fingerprint";
const emit = defineEmits(['logout'])
import {UserStore} from '@/store/UserStore'
const userStore = UserStore()
const toLockScan = ()=>{
  uni.navigateTo({
    url: '/pages/device/scan'
  })
}
const showSheet = () => {
  uni.showActionSheet({
    itemList: ['退出登录'],
    success: (res) => {
      if (res.tapIndex === 0) {
        //删除钥匙
        uni.showToast({
          icon: 'success',
          title: '登出成功'
        })
        userStore.setPhone('')
        userStore.setOpenid('')
        emit('logout')
      }
    },
    fail: function (res) {

    }
  })

}
</script>

<template>
  <div class="button add-device-button">
    <uni-icons type="plusempty" size="30" @click="toLockScan"/>
  </div>
  <div class="button lougin-out-button" @click="showSheet">
    <uni-icons type="gear-filled" size="30"/>
  </div>
</template>

<style scoped lang="scss">
.button {
  width: 50px;
  height: 50px;
  position: fixed;
  left: 25px;
  border-radius: 50%;
  text-align: center;
  line-height: 50px;
  background: white;
  font-size: 20px;
  box-shadow: 1px 4px 9px 0px rgb(51 56 80 / 13%);
}

.button.add-device-button {

  bottom: 120px;
}

.button.lougin-out-button {
  bottom: 50px;
}

</style>