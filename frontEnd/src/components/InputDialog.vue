<script setup>
import {ref} from "vue"

const emit = defineEmits(['confirm'])
const dialogVisavle = ref(false)
const inputValue = ref()
const confirmClick = () => {
  uni.vibrateShort()

  emit('confirm',inputValue.value)
}
const showDialog = (oringinalValue) => {
  uni.vibrateShort()

  inputValue.value = oringinalValue
  dialogVisavle.value = true
}
const hideDialog = () => {
  uni.vibrateShort()

  dialogVisavle.value = false
}
defineExpose({
  showDialog,hideDialog
})
</script>

<template>
  <main>
      <div v-if="dialogVisavle" class="mask-container" @click="dialogVisavle"></div>
      <div :class="{'visable':dialogVisavle}" class="main-container">
        <div class="default-title">修改锁名</div>
        <div class="form-container">
          <div class="item">
            <div class="name">锁名</div>
            <div class="value">
              <input class="default-input" type="text" placeholder="请输入锁名"
                     v-model="inputValue">
            </div>
          </div>
        </div>
        <div class="button-container">
          <div class="default-button" @click="confirmClick">确认</div>
          <div class="text-button" @click="hideDialog">取消</div>
        </div>
      </div>
  </main>
</template>

<style scoped lang="scss">




.value {
  margin: 15px 0;

}

.value input {
  border: 1px solid #ddd;
  height: 40px;
  border-radius: 10px;
  padding-left: 10px;
}

.footer-container {
  display: flex;
  justify-content: space-around;
  margin-top: 30px;

  .button {
    padding: 10px 10px;
    text-align: center;
    width: 24vw;
    border-radius: 5px;
    color: white;
  }

  .button.cancel {
    background-color: #9E9E9E;
  }

  .button.confirm {
    background-color: #2196f3;
  }
}
</style>