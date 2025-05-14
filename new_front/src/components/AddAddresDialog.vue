<script setup>
import {ref} from 'vue'
import {Address} from "@/utils/api/Address";

const dialogVisable = ref(false)
const hideDialog = () => {
  dialogVisable.value = false

}
const showDialog = (data = {}) => {
  if (data.id) {
    formData.value = data
  } else {
    formData.value = {
      phone: '',
      name: '',
      address: '',
    }

  }
  dialogVisable.value = true
}

const emit = defineEmits(['success'])
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
  if(formData.value.id){
    await Address.update(formData.value.id,formData.value)
  }else{
    await Address.store(formData.value)
  }

  emit('success')
  hideDialog()


}

defineExpose({
  showDialog, hideDialog
})
</script>

<template>
  <main>
    <div v-if="dialogVisable" class="mask-container" @click="hideDialog"></div>
    <div :class="{'visable':dialogVisable}" class="main-container">
      <div class="default-title">{{formData.id?'编辑':'添加'}}地址</div>
      <div class="form-container">
        <div class="item">
          <div class="name">收货人姓名</div>
          <div class="value">
            <input class="default-input" type="text" v-model="formData.name">
          </div>
        </div>
        <div class="item">
          <div class="name">收货人电话</div>
          <div class="value">
            <input class="default-input" type="text" v-model="formData.phone">
          </div>
          <div class="item">
            <div class="name">收货地址</div>
            <div class="value">
            <textarea style="background-color: #f6f7f9;
    width: calc(100% - 15px);
    border-radius: 12px;
    padding: 7.5px;
    height: 80px;" placeholder="请填写省市区完整地址" v-model="formData.address"/>
            </div>
          </div>
          <div class="button-container">
            <div class="default-button" @click="saveAddress">确认{{formData.id?'编辑':'添加'}}</div>
            <div class="text-button" @click="hideDialog">取消</div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped lang="scss">
</style>