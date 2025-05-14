<script setup>
import {ref} from 'vue'
import {init} from "@/utils/api/Lock";
import {UserStore} from '@/store/UserStore'
import LoginDialog from "@/components/LoginDialog.vue";
import LoadingLoading from "@/components/LoadingLoading.vue";


const scanStep = ref(0)
const foundDevices = ref([])
const timer = ref()
const lockData = ref('')
const userStore = UserStore()
const bindLock = async (device) => {
  const response = await lockplugin.initLock(device, (disconnectCallback) => {
    uni.showToast({
      icon: 'none',
      title: disconnectCallback.errorMsg
    })
  }, '', device.deviceId)
  uni.showToast({
    icon: 'loading',
    title: '正在添加···'
  })

  lockData.value = response.lockData
  const initResponse = await init(response.lockData, '', userStore.phone)
  if (initResponse.data.code === 200) {
    uni.showToast({
      icon: 'success',
      title: '绑定成功!'
    })
    setTimeout(() => {
      uni.navigateTo({
        url: '/pages/device/info?lockId=' + initResponse.data.data.lockId
      })
    }, 1500)

  }
}
const loginDialogRef = ref()
const startScanDevice = () => {
  if (!userStore.phone) {
    loginDialogRef.value.showDialog()
    return
  }
  // init('VOFBUB6QNpauq3jT8PXepX+zejOlB0bbSTRmdwAQrk9Bq0OWtjk5CEflfidz8YsBBGQHsA6dVkhYdT600duFnxq+x//vaVH0K4L6f+0AG6AIkfYsJvuIvTXwEY8zcHBIGefBXqKyqUoRaarwn2Gu6bE+LF2lVx1Mx+Miyy0ey7QUzNDkkbyVoFeiQOg8Vc7g17TqasdDX8POtWKGpzEuxqdPWMk+Rlgx4w7SEuSQI8U9WKZMnarMXaYnVq9+mFEPbcbH1lParvho0ax15mHvamDRZkSlMVgmo5boaGlzlR0pYa+CoKMCc05H2XnNf9W+IaXKldoDuNSERl2soktCbPqmFvobnB/8L2fvKlT99WYfUJ/RkYLLxBCIaH3ScuhjA3gdFmNrMzquUtxwgGYchMV6jGgAiVamtz9urtsQHWR6iC5ZN1DZ221fNrrthmqNB1gPSC5itRT7qk5lyJmqFzrku9MCuNj6BZoBk96iVAuZjir7e8rtVbsyUpQnt4bGvxVVKtcPSDZIeKyg+htbS3HYpHfHI4TEF/RbgbeBqMiymIYmw4KT1kDLdG/R86yy7xMhZxdbfhhvExdmZfT+iShPNBBJez71KMv3yZ0+WU6Kpu5vwDN/mUlIj58dXHaZ98sHfvdr3WdUl9+qfEmR+mytEj+JLNFiWnLkx1IBfwtmOyeFgyh6/NVgDmLc7lFx8HAJXFzkOPm28MUzY6VH2xEOk6agYvc2bc9ydxGTZzZqsenBA5ZtZblqLGNjiKw7vR0Way1hV7YEIA+tfWDOsisIyexqrcA54mNO7QnArsy1XyFVYEVSPeSmTp5Umc92t4O5wQJQ7IdtJfL2LWEzqMJ8GncVwWi6c+jcaVVWGFPbRKXmN3Cl881whJi1gYfwAHbVJfYc3d4+rTAWvivBF2Uaxth45Mhw3jltIYNxxP+uFg+3sIwDiI9dOEtBepweSdd7e56bOIu9a6fD7kS1hS0MmIkKLrRfsuvvTlR9IC4d46PYvmNaZPEua//KrEizhcv6kbZ5mrhtFIdg3lGllBaSueHXkC0coIgUndlwamr9v3DhRwvstl3+JciOyjrAEPiSiB4aGvGJJ7IqjVajhHHDnkfVtCL8bEDINZrJY8GPLM0J3zsWzY23hmgo6iiI2XsL//qtwT0WgGeDe8CcDyd5YPQSqTIQ9BX9jhgNhuVEgMf7KIeWvK/KZ9noTuXDy1BDpM72xWhc8lHjAl1291K7QriWRMUy343PNW/1960BH8qhiv3qtfXelukqILk12HVXoDk0SR//YVFHBRWSgVdETiMfSMrrEjOBU9ZqCCgjUv9onCwdJNVFERj9Ht20abmVdx93/Sf72I7LjKDhPR6gqKiSMqFH7w4zA78MKDggPOr3/FeZj+2kqFDb8zLsGv6rivk2yLInepUWLPPVjg26At3330MH9YOqluh1cgu2wJYLs2aTYx5g9DD+N9y6Yb1FwaG7CTiXobHFsV1u/4Fz5FzQP0DRWptza3Ao6KjEAfQkIs2bDc+9PIp07vwj9ITR6D18yonQC4CJnP/Ygte9BN/Lx9s7tM6gugYF01/P4HwpMODHGPQl1Wcqt7l0U1bxJ6iCYtk98fSpKHQbRCWsWZBRKpr3ClnsppFRzAoqLI4kcFjbCR9DHqIzf1HUdfJKS10aqP/+xGpPgo+L7/B0U4Zhe4GvISPnzWoltg7MFrPaIM6iN73e+rYOlU8LHt1HrRD8VOeZcSMigvHAYt8yY3jo1w==','',13750088636)
  // return
  foundDevices.value = []
  timer.value = setTimeout(() => {
    lockplugin.stopScanBleDevice(callback => {})
    scanStep.value = 2
  }, 10000)
  scanStep.value = 1
  lockplugin.startScanBleDevice(foundDevice => {
    if (foundDevice.isSettingMode) {
      let index = foundDevices.value.findIndex(item => {
        return item.lockId === foundDevice.lockId
      })
      if (index === -1) {
        foundDevices.value.push(foundDevice)
      }
    }
  }, failCallBack => {
    uni.showToast({
      icon: 'none',
      title: failCallBack.errorMsg
    })

  })
}
const onShareAppMessage = (res)=> {
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
  <div class="top-container">
    <div class="default-title">添加设备</div>
  </div>
  <div class="fixed-container">
    <div v-if="scanStep===0">
      <div class="scan-hint">
        <image style="width: 240px; height: 240px; " mode="scaleToFill" src="../../static/images/scan_lock_hint.png"/>
        <div class="text-hint">确认您的手机上蓝牙已经开启</div>
        <div class="text-hint">并近门锁并点亮门锁面板</div>
      </div>
      <div class="">
        <div class="start-button clickable" @click="startScanDevice">开始扫描</div>
      </div>
    </div>
    <div v-if="scanStep>=1">
      <LoadingLoading v-if="foundDevices.length===0&&scanStep!=2"/>
      <div class="device-item clickable" v-for="(device,index) in foundDevices" @click="bindLock(device)" :key="index">
          <div class="deivce-icon">
            <image style="width: 50px; height: 50px; " mode="scaleToFill" src="../../static/images/lock-icon.png"/>
          </div>
          <div class="device-name">
            <div class="name">{{ device.deviceName }}</div>
            <div class="date">{{ device.lockMac }}</div>
          </div>
        <div class="lock-angle">
          <image v-if="device.rssi<=40" style="width: 25px;height: 25px"
                 src="../../static/images/signal-wifi-statusba_1.png"></image>
          <image v-if="device.rssi>40&&device.rssi<=80" style="width: 25px;height: 25px"
                 src="../../static/images/signal-wifi-statusba_2.png"></image>
          <image v-if="device.rssi>80" style="width: 25px;height: 25px"
                 src="../../static/images/signal-wifi-statusba_3.png"></image>
          </div>
        </div>
      <div v-if="scanStep===2">
        <div class="scan-hint">
          <image style="width: 240px; height: 240px; " mode="scaleToFill" src="../../static/images/scan_lock_hint.png"/>
          <div class="text-hint">没有找到设备</div>
          <div class="text-hint">请并近门锁并点亮门锁面板</div>
        </div>

        <div class="start-button clickable" @click="startScanDevice">重新扫描</div>
      </div>
    </div>
    <LoginDialog ref="loginDialogRef"/>
  </div>
</template>

<style scoped lang="scss">


.scan-hint {
  text-align: center;
}

.text-hint {
  font-size: 12px;
  margin-top: 7.5px;
}

.start-button {
  box-shadow: 1px 4px 9px 0px rgb(51 56 80 / 13%);
  width: 53vw;
  margin: 30px auto;
  padding: 15px;
  text-align: center;
  background: white;
  border-radius: 40px;
  color: #585858;
}
</style>