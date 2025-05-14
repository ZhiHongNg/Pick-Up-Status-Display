<script setup>
import {Other} from "@/utils/api/Other";

const emit = defineEmits(['success'])
const upload = async () => {
  const response = await Other.uplaod()
  emit('success',response.data.url)
}
</script>

<template>
  <div @click="upload">
    <slot></slot>
  </div>
</template>

<style scoped lang="scss">

</style>