<script setup>
defineProps({
  msg: {
    type: String,
    required: true
  }
})
import image from '@/static/images/empty.png'

</script>

<template>
  <main>
    <div class="empty-container">
      <image style="width: 180px;height: 180px" :src="image"></image>
      <div style="color:#a2a2a2;">
        {{ msg }}
      </div>
    </div>
  </main>

</template>

<style scoped lang="scss">
.empty-container {
  width: 100%;
  height: 350px;
  text-align: center;
}
</style>