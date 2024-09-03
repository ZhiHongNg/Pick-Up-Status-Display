<script setup>
import {ref} from 'vue'
import {UserStore} from '@/store/UserStore'
import {Address} from "@/utils/api/Address";
import NumberController from "@/components/NumberController.vue";

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
const rebootCameDownText = ref('')
const rebootTime = ref(3)
const rebooting = ref(false)
const reboot = () => {
  uni.clearStorageSync('option')
  rebooting.value = true
  setInterval(()=>{
    rebootCameDownText.value = rebootTime.value+'秒后重启'
    rebootTime.value = rebootTime.value-1
  },1000)
  setTimeout(() => {
    window.location.reload()
  },3000)
}
const showDialog = (option) => {
  console.log('option', option)
  dialogVisable.value = true
  formData.value = option
}

const emit = defineEmits(['success', 'change'])
const dateStart = ref('')
const dateEnd = ref('')
const formData = ref()
const saveAddress = async () => {
  if (!formData.value.name) {
    uni.showToast({
      icon: 'none',
      title: '请填写收件人姓名'
    })
    return
  }
  if (!formData.value.phone) {
    uni.showToast({
      icon: 'none',
      title: '请填写收件人电话'
    })
    return
  }
  if (!formData.value.address) {
    uni.showToast({
      icon: 'none',
      title: '请填写收货地址'
    })
    return
  }
  if (formData.value.id) {
    await Address.update(formData.value.id, formData.value)
  } else {
    await Address.store(formData.value)
  }

  emit('success')
  hideDialog()
}
const change = () => {
  emit('change')
}
const keyRightChange = (value) => {
  formData.value.keyRight = value.detail.value ? 1 : 0
}
const dateChange = (dateRange) => {
  dateStart.value = dateRange[0]
  dateEnd.value = dateRange[1]
}
const getDialogVisable = () => {
  return dialogVisable.value
}
const switchChange = (event) => {
  formData.value.showNetworkStatus = event.detail.value
  change()
}
const activeIndex = ref(0)
defineExpose({
  showDialog, hideDialog, getDialogVisable
})
</script>

<template>
  <main>
    <div v-if="dialogVisable" class="mask-container" @click="hideDialog"></div>
    <div v-if="dialogVisable" :class="{'visable':dialogVisable}" class="main-container">
      <div class="default-title" @click="activeIndex=0">设置面板</div>
      <div style="display: flex">
        <div class="setting-index-title" :class="{'active':activeIndex===0}" @click="activeIndex=0">基本设置</div>
        <div class="setting-index-title" :class="{'active':activeIndex===1}" @click="activeIndex=1">显示设置</div>
      </div>
      <div class="form-container" v-if="activeIndex===0">
        <div class="item" style="width: 100%">
          <div class="name">显示标题</div>
          <div class="value" style="width: 100%">
            <input v-model="formData.appTitle" class="default-input" style="color: black"/>
          </div>
        </div>
        <div class="item">
          <div class="name">标题大小</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.appTitleFontSize"
                              @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">通道标题大小</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.entryTitleFontSize"
                              @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">车辆停留时长(分钟)</div>
          <div class="value">
            <NumberController :min="1" :max="60" v-model="formData.stayTimeLimit"
                              @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">显示网络连接状态</div>
          <div class="value">
            <switch :checked="formData.showNetworkStatus?true:false" @change="switchChange"/>

          </div>


        </div>
        <div class="item">
          <div class="name">车牌位数</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.endNumberCount" @change="change"></NumberController>
          </div>
        </div>

      </div>
      <div class="form-container" v-if="activeIndex===1">
        <div class="item">
          <div class="name">字体大小</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.fontSize" @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">宽度</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.itemWidth" @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">高度</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.itemHeight" @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">行间距</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.spaceX" @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">纵间距</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.spaceY" @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">填充X</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.paddingX" @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">填充Y</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.paddingY" @change="change"></NumberController>
          </div>
        </div>
        <div class="item">
          <div class="name">左边距</div>
          <div class="value">
            <NumberController :min="10" :max="100" v-model="formData.contentLeft" @change="change"></NumberController>
          </div>
        </div>
      </div>
      <div style="width:100%">
        <button @click="hideDialog" style="width: 300px;background: #607D8B;color: white;border-radius: var(--default-radius);margin-top: 30px;}">关闭面板</button>
        <button @click="reboot" style="width: 300px;background: #009688;color: white;border-radius: var(--default-radius);margin-top: 30px;}">
          {{rebooting?rebootCameDownText:'清除缓存并重启'}}
        </button>
      </div>
    </div>
  </main>
</template>

<style scoped lang="scss">
* {
  color: black;
}

.form-container {
  display: flex;
  flex-wrap: wrap;
}

.item {
  align-items: baseline;
  width: 33.33333%;
  display: flex;
  flex-wrap: wrap;

  .name {
    width: 100%;
    margin-right: 15px;
    font-size: 20px;
  }
}

.setting-index-title.active {
  border-bottom: 2px solid #4a4adc;
}

.setting-index-title {
  cursor: pointer;
  text-align: left;
  font-weight: bold;
  font-size: 20px;
  margin-bottom: 15px;
  color: #030319;
  padding-bottom: 7.5px;
  position: relative;
  display: table;
  margin-right: 15px;
}
</style>