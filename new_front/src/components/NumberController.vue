<script setup>
import {nextTick, onMounted, ref, watch} from 'vue'

const emit = defineEmits(['change'])
const plus = () => {
  if(thisNumber.value===props.max){
    return
  }
  thisNumber.value = Number(Number(thisNumber.value) + 1)
  emit('update:modelValue',Number(thisNumber.value))
  emit('change',Number(thisNumber.value))

}
const onWheel = (event) => {
  if (event.deltaY < 0) {
    // 向上滚动，增加值
    minus()
  } else {
    // 向下滚动，减少值
    plus()

  }
}
const minus = () => {
  if(thisNumber.value===props.min){
   return
  }
  thisNumber.value = Number(thisNumber.value) - 1
  emit('update:modelValue',Number(thisNumber.value))
  emit('change',Number(thisNumber.value))
}

const thisNumber = ref(1)
const props = defineProps({
  modelValue: {
    type: Number
  },
  min:{
    type: Number,
    default:0
  },
  max:{
    type:Number,
    default:9999999
  }
})
watch(()=>props.modelValue,(newValue)=>{
  thisNumber.value = newValue
  emit('update:modelValue',thisNumber.value)
})
watch(()=>thisNumber.value,(newValue)=>{
  emit('update:modelValue',thisNumber.value)
  emit('change',thisNumber.value)

})
const inputRef = ref()
const numberControllerRef = ref()
onMounted(async () => {
  thisNumber.value = props.modelValue
  await nextTick(() => {
    numberControllerRef.value.querySelector('.numberController').addEventListener('wheel', event => {
      onWheel(event)
    })


  })
})
</script>

<template>
  <div class="number-controller" ref="numberControllerRef">
    <div class="plus" @click="minus" style="border-right: none" :class="{'unclickAble':thisNumber===min}">-</div>
    <div >
      <input v-model="thisNumber" style="text-align: center" ref="inputRef" class="numberController">
    </div>
    <div class="minus" style="border-left: none" @click="plus"  :class="{'unclickAble':thisNumber===max}">+</div>
  </div>
</template>

<style scoped lang="scss">
.number-controller {
  display: flex;
  justify-content: center;
  color: black;
}
.number-controller div{
  text-align: center;
}
.number-controller div, .number-controller view {
  border: 1px solid var(--second-text-color);
  width: 44px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
}

.number-controller .plus {
  border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;
}

.number-controller .minus {
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
}

.number-controller .plus, .number-controller .minus {
  color: var(--second-text-color);
}
.unclickAble{
  color: rgba(128, 128, 128, 0.5) !important;
  border-color: rgba(128, 128, 128, 0.5) !important;
}

</style>