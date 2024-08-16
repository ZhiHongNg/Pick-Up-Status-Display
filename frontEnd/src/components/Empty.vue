<script setup>
import image from '@/static/images/empty.png'
</script>

<template>
  <main>
    <div class="empty-container">
      <image style="width: 180px;height: 180px" :src="image"></image>
      <div>
        <slot/>
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