<script setup>
import {ref} from "vue"

const emit = defineEmits(['success'])
const dialogVisable = ref(false)
const showDialog = () => {
  dialogVisable.value = true
}
const hideDialog = () => {
  dialogVisable.value = false
  emit('success')
}
defineExpose({
  showDialog, hideDialog
})
</script>

<template>
  <div v-if="dialogVisable" class="main-container" style="    z-index: 9999;
    height: 80vh;
    width: calc(100vw - 30px);
    overflow: scroll;
    text-align: left;
    bottom: 0;">
    <h1 class="ep">用户隐私协议</h1>
    感谢您选择使用我们的智能门锁管理应用程序（以下简称“本应用”）。我们重视您的隐私并致力于保护您的个人信息。本隐私协议解释了我们如何收集、使用和保护您的个人信息。

    <h1 class="ep">信息的收集与使用</h1>
    为了向您提供本应用的完整功能和更优质的服务，我们需要收集您的电话号码。您的电话号码是我们提供服务的必要信息，主要用于：

    <h1 class="ep">登录和身份验证</h1>
    通过电话号码获取与您相关的智能锁列表
    我们承诺，收集的电话号码仅用于上述目的，不会用于任何其他用途。

    <h1 class="ep">信息的保护</h1>
    我们采取各种安全措施来保护您的个人信息免受未经授权访问、使用或披露。这包括物理、技术和管理方面的措施。

    <h1 class="ep">对第三方的分享</h1>
    我们不会将您的电话号码出售、出租或以其他方式提供给第三方。但为了提供必要的服务，我们可能会在法律允许的范围内与合作伙伴共享此信息，例如技术支持或其他服务。在任何情况下，我们都会要求这些第三方尊重您的个人信息安全，并按照本隐私政策处理您的信息。

    <h1 class="ep">您的权利</h1>
    您可以随时查看、更改或删除您的个人信息。如果您希望执行这些权利，或者如果您有任何关于我们使用您的信息的疑问，请通过我们的客户支持联系我们。

    <h1 class="ep">改动和更新</h1>
    本隐私协议的任何更改都会及时更新在本应用上，并且我们会尽可能地通知您这些更改。使用本应用即表示您同意本隐私协议的条款。
    <div style="width: 100%;height: 300px"></div>
    <div v-if="dialogVisable" class="default-button" @click="hideDialog">同意</div>

  </div>

</template>

<style scoped lang="scss">
.ep {
  text-align: center;
  font-weight: bold;
  margin: 15px;
  font-size: 20px;
}

.default-button {
  position: fixed;
  left: 0;
  width: 80%;
  margin: auto;
  right: 0;
  bottom: 15px;
}
</style>