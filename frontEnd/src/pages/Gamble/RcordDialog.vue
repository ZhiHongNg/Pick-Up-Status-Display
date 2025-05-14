<script setup>
import {ref} from 'vue';
import Record from "@/pages/Gamble/record.vue";
const dialogVisable = ref(false)
const list = ref([])
const showDialog = (a)=>{
  list.value = a
  dialogVisable.value = true
}
const hideDialog = ()=>{
  list.value = []
  dialogVisable.value = false
}
defineExpose({
  showDialog,
  hideDialog,
})
</script>

<template>
  <div style="width: 100vw;height: 100vh;background-color: black;opacity: .7;position: fixed;left: 0;top: 0" v-if="dialogVisable==true" @click="hideDialog"></div>
  <div style="width: 45vw;height: 60vh;position: fixed;overflow-y:scroll;left: 0;top: 0;right: 0;bottom: 0;background-color: #405872;color:white;border-radius: 12px;padding: 15px;margin: auto" v-if="dialogVisable">
    <record :list="list"/>
  </div>

</template>

<style scoped lang="scss">


</style>