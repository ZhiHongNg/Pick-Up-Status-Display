<script setup>
import {onMounted, ref} from "vue";
import {onLoad} from '@dcloudio/uni-app'
import AddAddresDialog from "@/components/AddAddresDialog.vue";
import {Address} from '@/utils/api/Address'
import EmptyBlock from "@/components/EmptyBlock.vue";

onLoad(async (options) => {
  type.value = options.type
  if (type.value === 'good') {
    goodId.value = options.goodId
    getGoodInfo()
  } else {
    getCart()
  }

})
const addAddresDialogRef = ref()
const type = ref('')
const getCart = () => {

}
const itemClick = (item) => {
  if (type.value === 'choose') {
    uni.setStorage({
      key: 'chooseAddress',
      data: item,
      success: () => {
        uni.navigateBack()
      }
    })
  } else {
    uni.showActionSheet({
      itemList: ['删除收货地址', '编辑收货地址'],
      success: (res) => {
        if (res.tapIndex === 0) {
          console.log(item,item)
          Address.drop(item.id)
          uni.showToast({
            icon: 'success',
            title: '删除成功'
          })
          getAddresss()
        } else if (res.tapIndex === 1) {
          addAddresDialogRef.value.showDialog(item)
        }
      },
      fail: function (res) {

      }
    })

  }
  //todo:选择地址
  //todo:地址信息保存到本地

}
const addAddress = () => {
  addAddresDialogRef.value.showDialog()
}

const back = () => {
  uni.switchTab({
    url: '/pages/index/index'
  })
}

const getGoodInfo = () => {

}
const addresses = ref([])
const getAddresss = async () => {
  const response = await Address.index()
  addresses.value = response.data.data
}
onLoad((options) => {
  type.value = options.type
})
onMounted(() => {
  getAddresss()
})
</script>

<template>
  <div class="top-container" style="background: none">
    <div class="default-title" @click="back">{{ type === 'choose' ? '选择配送地址' : '地址管理' }}</div>
  </div>
  <div class="fixed-container" style=";background-color: var(--default-background)">
    <div>
      <EmptyBlock msg="尚未添加收货地址" v-if="!addresses"/>
      <div class="address-item" v-for="item in addresses" @click="itemClick(item)">
        <div class="contract">
          <div class="ship-name">{{ item.name }}</div>
          <div class="ship-phone">
            <!--            <image style="width: 20px;height: 20px"-->
            <!--                   src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTQ0Ni43NTcgMTk0LjcwNmMzMy43OTIgNTIuODgzIDQ3LjA1NSA5MC4zMDcgMzUuNTk2IDEzNi42NTYtNS45NSAyNC40MDUtMTguMjM3IDQwLjAzMy00MC41NyA1OC43MDlsLTIyLjk5MSAxOC43NDktMi43OCAyLjE5NC0xNS4zNiAxMS41ODFjLTEwLjQxIDguMDQ2LTE0LjQ4MiAxMi41NTYtMTUuMjYyIDE1LjMxMS00LjY4MSAxNi41OCAzLjIxOCAzOC40OTggMjIuMDE2IDY3Ljg3NyAzMS4yNTYgNDguNjE1IDY3Ljc3OSA4OS45NDEgMTEyLjM5NiAxMjYuMTIzIDMwLjEzNSAyNC40NzggNDcuNzg3IDMzLjcxOCA2MC4xNDggMzEuMjU2IDkuNzUyLTEuOTUgMTcuMjEzLTcuNjggMzQuNDUtMjYuMjU4bDcuMDIyLTcuNDg1YzI2Ljg0My0yOC4xMzYgNDUuNDk1LTQwLjc5IDc3LjIzOS00NS41NjggNDUuMy02LjgwMyA3OS43MjUgNy45NDggMTE5LjM2OSA0MC43MTZsMTEuOCAxMC4wNyAxMi4yNCAxMC43MjcgMjUuNzIxIDIxLjUwNGM0MC42NjggMzQuNzkxIDYxLjQ4OSA2NC44MDQgNTguNDE3IDEwNi4yMDMtMi4zMTYgMzAuMTEtMTUuODcyIDQ4LjE3Ny00My43MTUgNzIuMDk1bC02LjMxNSA1LjQxMmEyODguOTE0IDI4OC45MTQgMCAwIDAtNS4wMjIgNC40ODZsLTE2LjA5MiAxNC45Ny04LjQ4NCA3Ljc1M2ExODcuNzMzIDE4Ny43MzMgMCAwIDEtMjIuNzQ4IDE3LjYyOGMtNDcuMDggMzAuNTI1LTEwMy45ODQgNDUuMDMxLTE2Ny42NDMgNDMuMDgxLTU1LjAyOC0xLjcwNy0xMDEuMDEtMTYuNzk4LTE1Ni4zMy00Ni4wOC03NC45OTctMzkuNzE3LTEzOC4xMTktOTMuODE4LTIwOS42NzctMTczLjY0MS02OC4xNDUtNzUuOTcxLTExOC45My0xNTQuODItMTQ3LjYyNy0yMzYuODM3QzEwMi40IDM2Ny4xMDQgMTIxLjgzMiAyNDQuNTY1IDE5MS43NTYgMTY1LjRjNS43My02LjUxIDkuOTk2LTEwLjcyNyAxOC44OTUtMTguMjg1bDI1LjQwNS0yMS4wOSAxMi40MzUtMTAuOTIzYzE5LjA2Ni0xNi40ODEgMzIuMTM0LTI0LjMwNyA1Mi44MDktMjguMTg0IDQ4LjcxMy05LjE0MyA4Mi44OTUgMTUuNzAxIDExNy40MTggNjQuOTAybDYuNTEgOS41ODIgNS44NTIgOC45MjMgMTUuNjc3IDI0LjM4MXptLTg2LjU1MyAxNjYuNzQybDEyLjkyMi05Ljc1MyA1LjgwMy00LjgyNyAxNS42MDQtMTIuNjNjMTEuMTY2LTkuMzM3IDE1LjQzMy0xNC43MjYgMTYuODIyLTIwLjMzMyA0Ljg3Ny0xOS44NDYtMS42ODItNDAuMjUzLTIyLjQzLTczLjc3N2wtMy44MDMtNi4wNDYtMjMuMTM4LTM1LjY3Yy0yMC43OTctMzAuNjk1LTM1LjE4Mi00MS44NjItNDcuMjAyLTM5LjU5NC00LjMxNS44MDQtOC40MTEgMy4xNDUtMTYuNTMgOS45NzFsLTE0Ljk0NSAxMy4wNjktMjAuODIyIDE3LjIzN2MtNy44MDIgNi41MS0xMS4zODYgOS44MDEtMTQuNTMgMTMuMTY2bC01Ljc3OSA2Ljc1M2MtNDguMTI4IDU4LjkwNC02MS4zNjcgMTUwLjY3NC0zMC41NzQgMjM4Ljc2MyAyNS4xMzcgNzEuODUgNzAuODUxIDE0Mi44NDggMTMzLjA0NyAyMTIuMTYzIDY2LjMxNiA3My45NzIgMTIzLjI3IDEyMi44MDcgMTg5LjQ0IDE1Ny44NDIgNDYuMjc1IDI0LjUwMyA4Mi4yMzcgMzYuMzAzIDEyNC4zNDMgMzcuNTk2IDQ5LjI5OCAxLjUzNiA5MS41MjYtOS4yNjUgMTI1LjYzNS0zMS4zNTQgNC4xOTQtMi43MDcgOC43MjgtNi4yOSAxNC40ODItMTEuNDFsMy42MDktMy4yNDMgMTkuOTkyLTE4LjYyNyA2LjE5My01LjQ4NiA2LjQ4NS01LjU1OWMxNC4wMi0xMi4wNDQgMTguMDY2LTE3LjQ1NyAxOC40MzItMjIuMTEzLjczMi05LjktNy43MjktMjIuNzcyLTI4LjY3Mi00MS4zNWwtNC4zNjQtMy44MDRjLTQuMDcyLTMuNDg2LTIwLjU3OC0xNy4wOTEtMjQuNDA1LTIwLjM4MmwtMTguMDQyLTE1Ljc3NS02LjY4LTUuNjA4Yy0yNC42OTktMjAuMzU4LTQxLjY0My0yNy40NzctNjEuNTYzLTI0LjQ3OC0xMS41MDcgMS43MDctMTkuNDggNy4xNDQtMzUuOTg2IDI0LjU1MmwtMTAuNTU3IDExLjI2NGMtMjQuMzggMjUuNTc1LTQwLjkxMSAzNy4zMDItNjguNjU3IDQyLjg2MS00Mi4zNSA4LjQ4NS03My4zMTMtNy43MjgtMTIwLjYxMi00Ni4xNzctNTAuNzYxLTQxLjE1NS05Mi40NTMtODguMzMyLTEyNy45MDMtMTQzLjQzMy0yOS41MjUtNDYuMjAyLTQzLjA4LTgzLjc0OS0zMC44NDEtMTI3LjE3MSA2LjU4Mi0yMy41MjggMTkuODk0LTM3LjY0NSA0NS4yMjYtNTYuNjM3eiIgZmlsbD0iI2JmYmZiZiIvPjwvc3ZnPg=="></image>-->
            <div class="text">{{ item.phone }}</div>
          </div>
        </div>
        <div class="detail-address">
          <image
              src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMiA2NGMtMTk0LjA4IDAtMzUyIDE1Ny45Mi0zNTIgMzUyIDAgMTg3LjM2IDMxNS40MjQgNTIwLjAzMiAzMjguODMyIDUzNC4wOEM0OTQuODggOTU2LjQ0OCA1MDMuMjY0IDk2MCA1MTIgOTYwaC43MDRjOC45OTIgMCAxNy40NzItNC4xOTIgMjMuMzkyLTEwLjk0NGwxMDkuMjE2LTEyNS4xMkM3OTAuNDMyIDY0Ni4xNzYgODY0IDUwOC45MjggODY0IDQxNmMwLTE5NC4wOC0xNTcuOTItMzUyLTM1Mi0zNTJ6bTAgNTEyYy04OC4zODQgMC0xNjAtNzEuNjE2LTE2MC0xNjBzNzEuNjE2LTE2MCAxNjAtMTYwIDE2MCA3MS42MTYgMTYwIDE2MC03MS42MTYgMTYwLTE2MCAxNjB6IiBmaWxsPSIjYmZiZmJmIi8+PC9zdmc+"></image>
          <div class="text">{{ item.address }}</div>
        </div>
      </div>
    </div>
    <div class="footer-container">
      <div class="add-address-button" @click="addAddress">添加地址</div>
    </div>
  </div>
  <AddAddresDialog ref="addAddresDialogRef" @success="getAddresss"/>

</template>

<style scoped lang="scss">
.footer-container {
  width: calc(100% - 30px);
  padding: 15px 15px 35px 15px;
  background-color: white;
  display: flex;
  justify-content: center;
  position: fixed;
  left: 0;
  bottom: 0;

  .add-address-button {
    border-radius: 120px;
    background-color: var(--default-color);
    padding: 15px;
    width: 80%;
    text-align: center;
    color: white;
  }
}
</style>