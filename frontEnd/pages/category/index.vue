<script setup>
import {computed, ref} from 'vue'
import {onLoad, onShow} from '@dcloudio/uni-app'
import {openLockByKey} from "@/utils/service/LockService";
import {getDevice} from "@/utils/api/Lock";
import {UserStore} from '@/store/UserStore'
import CartDialog from "@/pages/cart/CartDialog.vue";
import {Category} from "@/utils/api/Category";
import {Good} from "@/utils/api/Good";
import {baseUrl} from "@/utils/request";

let timer = ref(null)
let timer2 = ref(null)
let s = ref(0)
let lockId = ref(null)
const device = ref()
const userStore = UserStore()
const back = () => {
  uni.switchTab({
    url: '/pages/index/index'
  })
}
onLoad(async (options) => {
  getCategorys()
  getGoods()
})
onShow(async () => {
})
const boxShadowComputed = computed(() => {
  return `inset 0 0 0px ${s.value + 13}px #1296db`;
});
const cartDialogRef = ref()
const addCart = (goodId)=>{
  cartDialogRef.value.showDialog(goodId)
}
const goodClick = async (id) => {
  uni.vibrateShort()
  uni.navigateTo({
    url: '/pages/good/info' + '?id=' + id
  })

}
const handlePress = () => {
  // 设置一个3秒后执行的计时器
  timer2.value = setInterval(() => {
    if (s.value >= 90) {
      openLockByKey(device.value)
      handleRelease()
    } else {
      uni.vibrateShort()
      s.value = s.value + 1
    }

  }, 1000 / 120)
  timer.value = setTimeout(() => {
    // openLockByKey(device.value)
  }, 3000)
}

// 处理长按结束的函数
const handleRelease = () => {
  console.log('handleRelease')
  // 如果计时器存在，则清除它
  if (timer.value) {
    clearTimeout(timer.value)
    clearInterval(timer2.value)
    timer.value = null
    timer2.value = null
    s.value = null
  }
}
const menus = ref([
  {
    name: '钥匙管理',
    icon: 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEzNjYgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTEwMTkuNDc3IDE4My4zN2MtOC42MDUtOC42MDYtMjIuNTU4LTguNjA2LTMxLjE2NSAwbC01NC41MzkgNTQuNTQtNzAuMTIyLTcwLjEyMmMtOC42MDctOC42MDctMjIuNTYtOC42MDctMzEuMTY2IDAtOC42MDYgOC42MDUtOC42MDYgMjIuNTYgMCAzMS4xNjVsNzAuMTIyIDcwLjEyMi0xNzkuOTQzIDE3OS45NDRjLTkwLjkwMS03NS4yMDYtMjI1LjgwNC03MC4yNjgtMzEwLjkxIDE0Ljg0LTkwLjM2NSA5MC4zNjQtOTAuMzY1IDIzNi44NzIgMCAzMjcuMjM2czIzNi44NzMgOTAuMzY0IDMyNy4yMzUgMGM4NS4xMDctODUuMTA3IDkwLjA0Ni0yMjAuMDEgMTQuODQtMzEwLjkxMWwxOTUuNTE5LTE5NS41MTkuMDA4LS4wMDguMDA4LS4wMDggNzAuMTE1LTcwLjExNGM4LjYwNS04LjYwNCA4LjYwNS0yMi41NTktLjAwMi0zMS4xNjR6TTcwNy44MjQgNzU5LjkzYy03My4xNTIgNzMuMTUxLTE5MS43NTMgNzMuMTUxLTI2NC45MDUgMC03My4xNTItNzMuMTUzLTczLjE1Mi0xOTEuNzU0IDAtMjY0LjkwNyA3My4xNTItNzMuMTUyIDE5MS43NTMtNzMuMTUyIDI2NC45MDUgMCA3My4xNTIgNzMuMTUzIDczLjE1MiAxOTEuNzU2IDAgMjY0LjkwNnoiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTkuN2U4ODNhODFiS1o2dkUiIGNsYXNzPSJzZWxlY3RlZCIgZmlsbD0iI2ZmNmY2NyIvPjwvc3ZnPg==',
    page: '/pages/device/key?type=key',
    key: 'key',
    adminMenu: true,
    color: '#fff3f3'
  },
  {
    name: '密码管理',
    icon: 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmkxOC43ZTg4M2E4MWJLWjZ2RSIgd2lkdGg9IjIwMCIgaGVpZ2h0PSIyMDAiPjxwYXRoIGQ9Ik01MTIgNjRhNDQ4IDQ0OCAwIDEgMCA0NDggNDQ4QTQ0OC41IDQ0OC41IDAgMCAwIDUxMiA2NHptMCA4MzJhMzg0IDM4NCAwIDEgMSAzODQtMzg0IDM4NC41IDM4NC41IDAgMCAxLTM4NCAzODR6IiBmaWxsPSIjMzZhYjYwIiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmkxMi43ZTg4M2E4MWJLWjZ2RSIgY2xhc3M9InNlbGVjdGVkIi8+PHBhdGggZD0iTTI3MiA0MDBhNDggNDggMCAxIDAgOTYgMCA0OCA0OCAwIDEgMC05NiAweiIgZmlsbD0iIzM2YWI2MCIgZGF0YS1zcG0tYW5jaG9yLWlkPSJhMzEzeC5zZWFyY2hfaW5kZXguMC5pMjMuN2U4ODNhODFiS1o2dkUiIGNsYXNzPSJzZWxlY3RlZCIvPjxwYXRoIGQ9Ik00NDggNDQ4YTQ4IDQ4IDAgMSAwLTQ4LTQ4IDQ4IDQ4IDAgMCAwIDQ4IDQ4em0xMjgtOTZhNDggNDggMCAxIDAgNDggNDggNDggNDggMCAwIDAtNDgtNDh6bTEyOCAwYTQ4IDQ4IDAgMSAwIDQ4IDQ4IDQ4IDQ4IDAgMCAwLTQ4LTQ4eiIgZmlsbD0iIzM2YWI2MCIgZGF0YS1zcG0tYW5jaG9yLWlkPSJhMzEzeC5zZWFyY2hfaW5kZXguMC5pMTkuN2U4ODNhODFiS1o2dkUiIGNsYXNzPSJzZWxlY3RlZCIvPjxwYXRoIGQ9Ik0yNzIgNTI4YTQ4IDQ4IDAgMSAwIDk2IDAgNDggNDggMCAxIDAtOTYgMHoiIGZpbGw9IiMzNmFiNjAiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTIyLjdlODgzYTgxYktaNnZFIiBjbGFzcz0ic2VsZWN0ZWQiLz48cGF0aCBkPSJNNDQ4IDU3NmE0OCA0OCAwIDEgMC00OC00OCA0OCA0OCAwIDAgMCA0OCA0OHptMTI4IDY0SDQ0OGE0OCA0OCAwIDAgMCAwIDk2aDEyOGE0OCA0OCAwIDEgMCAwLTk2em0wLTE2MGE0OCA0OCAwIDEgMCA0OCA0OCA0OCA0OCAwIDAgMC00OC00OHptMTI4IDBhNDggNDggMCAxIDAgNDggNDggNDggNDggMCAwIDAtNDgtNDh6IiBmaWxsPSIjMzZhYjYwIiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmkyMS43ZTg4M2E4MWJLWjZ2RSIgY2xhc3M9InNlbGVjdGVkIi8+PC9zdmc+',
    page: '/pages/device/key?type=password',
    key: 'password',
    adminMenu: true,
    color: '#f0fcf8'
  },
  {
    name: '门卡管理',
    icon: 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmkyNy43ZTg4M2E4MWJLWjZ2RSIgd2lkdGg9IjIwMCIgaGVpZ2h0PSIyMDAiPjxwYXRoIGQ9Ik00NDAuMjU1IDUxOS4xNjljLTIuMzA3IDAtNC42MTUtLjMzLTYuODQtMS4wNzItMTEuNy0zLjc5LTE4LjEyNy0xNi4zMTQtMTQuMzM3LTI4LjAxNSA4LjQwNS0yNi4wMzcgMjYuNTMyLTQ3LjIxMyA1MC45MjItNTkuNzM4IDI0LjM5LTEyLjQ0MiA1Mi4xNTgtMTQuNjY3IDc4LjE5NS02LjI2MiAxMS43IDMuNzkgMTguMTI4IDE2LjMxNSAxNC4zMzcgMjguMDE1LTMuNzkgMTEuNy0xNi4zMTQgMTguMTI3LTI4LjAxNSAxNC4zMzctMTQuNzQ5LTQuNzc5LTMwLjQ4Ny0zLjU0My00NC4yNDcgMy41NDMtMTMuNzYgNy4wODYtMjQuMDYgMTkuMDM0LTI4Ljc1NyAzMy43ODMtMy4yMTMgOS4zOTQtMTEuODY1IDE1LjQwOS0yMS4yNTggMTUuNDA5em0tODUuMDM1LTMyLjcxMmMtMi4zMDcgMC00LjYxNC0uMzMtNi44MzktMS4wNzEtMTEuNy0zLjc5LTE4LjEyNy0xNi4zMTUtMTQuMzM3LTI4LjAxNiAxNS4zMjYtNDcuMzc4IDQ4LjIwMy04NS44NTggOTIuNTMzLTEwOC41MTcgNDQuMzMtMjIuNjYgOTQuODQtMjYuNjk3IDE0Mi4xMzUtMTEuMjg4IDExLjcgMy43OSAxOC4xMjggMTYuMzE0IDE0LjMzNyAyOC4wMTVzLTE2LjMxNCAxOC4xMjctMjguMDE1IDE0LjMzN2MtMzYuMDA3LTExLjctNzQuNDg3LTguNTctMTA4LjE4OCA4LjY1Mi0zMy43IDE3LjIyLTU4Ljc0OSA0Ni41NTQtNzAuNDUgODIuNjQ0LTMuMDQ4IDkuMzExLTExLjc4MiAxNS4yNDQtMjEuMTc2IDE1LjI0NHpNNDk5LjM2MyA1NDIuNDUxYTI2LjM2NyAyNi4zNjcgMCAxIDAgNDYuMzk1LTI1LjA2NyAyNi4zNjcgMjYuMzY3IDAgMSAwLTQ2LjM5NSAyNS4wNjd6TTcyMS42NDIgNDc3LjMxYy03LjkxIDAtMTUuNTczLTQuMjAyLTE5LjYxLTExLjdsLTM2LjI1Ni02Ny4xNTRjLTUuODUtMTAuNzk0LTEuODEyLTI0LjMwNyA4Ljk4Mi0zMC4xNTcgMTAuNzk0LTUuODUgMjQuMzA3LTEuODEzIDMwLjE1NyA4Ljk4MWwzNi4yNTUgNjcuMTU0YzUuODUgMTAuNzk0IDEuODEzIDI0LjMwNy04Ljk4MSAzMC4xNTgtMy4yOTYgMS44OTUtNi45MjIgMi43MTktMTAuNTQ3IDIuNzE5eiIgZmlsbD0iI2IzYWY2YiIvPjxwYXRoIGQ9Ik00NjQuNTYyIDg1MS43MjVjLTUuOTMzIDAtMTEuOTQ4LS45ODktMTcuNzk4LTMuMTMxLTEzLjUxMy00LjY5Ny0yNC43Mi0xNC40Mi0zMS43MjMtMjcuMzU2TDE3MS45NjkgMzcxLjI2NWMtMTQuODMyLTI3LjQzOC01Ljc2OC02MS4wNTYgMjAuMTA0LTc1LjA2NGwzMTYuMTYtMTcwLjcyN2MxMi45MzYtNy4wMDQgMjcuODUtOC4wNzUgNDIuMTA1LTMuMDUgMTMuNTEzIDQuNjk3IDI0LjcxOSAxNC40MiAzMS43MjMgMjcuMzU3bDEyMi45MzcgMjI3LjU4MmM1Ljg1IDEwLjc5NCAxLjgxMiAyNC4zMDctOC45ODIgMzAuMTU3LTEwLjc5NCA1Ljg1LTI0LjMwNyAxLjgxMy0zMC4xNTctOC45ODFMNTQyLjkyMiAxNzAuOTU3Yy0xLjY0OC0zLjEzMS00LjM2Ny01LjUyLTcuMzMzLTYuNTEtMS41NjYtLjU3Ni0zLjk1Ni0uOTg4LTYuMTguMjQ4TDIxMy4yNDkgMzM1LjM0Yy00LjIwMiAyLjMwNy01LjE5IDkuMDY0LTIuMTQyIDE0Ljc0OUw0NTQuMTggODAwLjA2MmMxLjY0OCAzLjEzIDQuMzY3IDUuNTIgNy4zMzMgNi41MDkgMS41NjYuNTc3IDMuOTU1Ljk4OSA2LjE4LS4yNDdMNzgzLjc3IDYzNS41OTZjMi4yMjQtMS4yMzYgMy4yMTMtMy4zNzggMy41NDMtNS4wMjYuNzQxLTMuMDQ5LjI0Ny02LjU5Mi0xLjQ4My05LjcyM2wtMjAuNjgyLTM4LjIzMmMtNS44NS0xMC43OTQtMS44MTMtMjQuMzA4IDguOTgxLTMwLjE1OCAxMC43OTQtNS44NSAyNC4zMDctMS44MTMgMzAuMTU4IDguOTgxbDIwLjY4MSAzOC4yMzNjNy4wMDQgMTIuOTM2IDguOSAyNy42ODUgNS41MjEgNDEuNTI4LTMuNjI1IDE0LjY2Ny0xMi42OSAyNi41MzItMjUuNjI2IDMzLjUzNkw0ODguODcgODQ1LjU0NWE1MC41MjEgNTAuNTIxIDAgMCAxLTI0LjMwNyA2LjE4eiIgZmlsbD0iI2IzYWY2YiIvPjwvc3ZnPg==',
    page: '/pages/device/card',
    key: 'card',
    adminMenu: true,
    color: '#fffbef'
  },
  {
    name: '指纹管理',
    icon: 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTExMy40MzEgMjg2LjE4NWEyMy4yNzMgMjMuMjczIDAgMCAxIDQwLjQ5NSAyMi45N0MxMTQuMzYyIDM3OC44OCA5My4wOSA0NTguOTg1IDkzLjA5IDU0Mi40ODdhNDg4LjcyNyA0ODguNzI3IDAgMCAwIDEuMzUgMzYuMjM2IDIzLjI3MyAyMy4yNzMgMCAwIDEtNDYuNDMgMy40NDQgNTM1LjI3MyA1MzUuMjczIDAgMCAxLTEuNDY2LTM5LjY4YzAtOTEuNTc4IDIzLjM0My0xNzkuNTk1IDY2Ljg4Ni0yNTYuMzAyek05NTkuMDcgNjgxLjAzYTIzLjI3MyAyMy4yNzMgMCAwIDEtNDQuOTE2LTEyLjI0MmMxMS4xLTQwLjY1NyAxNi43OC04My4wODMgMTYuNzgtMTI2LjMgMC05MS4wNjctMjUuMjc1LTE3OC4wMTQtNzEuODQ0LTI1MS43NDJhMjMuMjczIDIzLjI3MyAwIDAgMSAzOS4zMzEtMjQuODU1YzUxLjI3IDgxLjE1MiA3OS4wMzUgMTc2LjcxIDc5LjAzNSAyNzYuNTk2IDAgNDcuMzg0LTYuMjM4IDkzLjg4Mi0xOC4zODYgMTM4LjU0M3pNODI5LjY5NiAxODAuMDE1YTIzLjI3MyAyMy4yNzMgMCAxIDEtMzIuODM4IDMyLjk3N0M3MTkuODI1IDEzNi4zNzggNjE5LjA1NSA5My4wOTEgNTEyIDkzLjA5MWMtMTE0LjcxMSAwLTIyMi4wNjggNDkuNjg3LTMwMC41MiAxMzYuMzMyYTIzLjI3MyAyMy4yNzMgMCAwIDEtMzQuNTE0LTMxLjIzMkMyNjQuMDc2IDEwMS45OCAzODMuODYgNDYuNTQ1IDUxMiA0Ni41NDVjMTE5LjYyMiAwIDIzMi4xMjIgNDguMjkxIDMxNy42NzMgMTMzLjQ3em0tNDU1LjAyOCA3LjMzYTIzLjI3MyAyMy4yNzMgMCAwIDEgMTguMTUyIDQyLjg2OUMyODAuNiAyNzcuNzM3IDIwNi4xMjcgMzg4LjAwMyAyMDYuMDMzIDUxNC4xNGMtOC4zNzggOTAuNjctMzQuOTA5IDE1Ni45OTgtODAuODAyIDE5OC4wNTFhMjMuMjczIDIzLjI3MyAwIDAgMS0zMS4wMjMtMzQuNjc2YzM1Ljg2My0zMi4wOTMgNTguMTEyLTg3LjY2OSA2NS4zOTYtMTY1LjUxNmEzNTIuNDY1IDM1Mi40NjUgMCAwIDEgMjE1LjA2NC0zMjQuNjU1em00MzIuNDMgNzAwLjc2NmEyMy4yNzMgMjMuMjczIDAgMSAxLTQxLjYzNS0yMC44MDZjOS4xLTE4LjIgMTYuOTktMzcuMTkgMjMuNjkyLTU2LjkyNSAyNy41MzItODEuMDYgMzMuMzUtMTU2LjM0NyAyOS43NDItMjYxLjAwNGwtLjY5OC0yMC4zNjRjLS4yMzItNy4wNzUtLjMyNi0xMi4zOC0uMzI2LTE3LjAxMmEyMy4yNzMgMjMuMjczIDAgMCAxIDQ2LjU0NiAwYzAgNC4wNS4wOTMgOC45MzcuMzAyIDE1LjU0NmwuNjk5IDIwLjIyNGMzLjc3IDEwOS44NDctMi40MiAxODkuODgyLTMyLjIxIDI3Ny41NzQtNy4zNTQgMjEuNjktMTYuMDU4IDQyLjYzNi0yNi4xMTIgNjIuNzY3em0zNy40NDYtNDkzLjA4YTIzLjI3MyAyMy4yNzMgMCAwIDEtNDMuOTE2IDE1LjQ1MyAzMDUuOTkgMzA1Ljk5IDAgMCAwLTMwNS42NC0yMDMuODY5IDIzLjI3MyAyMy4yNzMgMCAwIDEtMi41Ni00Ni40OTkgMzUyLjUzNSAzNTIuNTM1IDAgMCAxIDM1Mi4xMTYgMjM0LjkxNXptLTI3My44NS0xMjUuNDE2YTIzLjI3MyAyMy4yNzMgMCAwIDEtNy42OCA0NS45MTdjLTY2LjUxNC0xMS4xMDEtMTI2LjkwNiA1LjM3Ni0xODIuNzYgNTAuMDYtMzUuMDI2IDI4LjAyLTUwLjE3NyA1OC4zMi01Ny44OCAxMDQuNTE3LTEuNDY2IDguODQ0LTIuMzc0IDE1LjY0LTQuNDIyIDMyLjU4Mi01LjY3OSA0Ni4zMTMtMTAuMTQ3IDY3LjQ0NC0yMi44NTQgOTIuODU4YTIzLjI3MyAyMy4yNzMgMCAxIDEtNDEuNjM1LTIwLjgwNmM5LjU0Mi0xOS4wODMgMTMuMjY2LTM2LjU4NCAxOC4yNy03Ny43MyAyLjE2NC0xNy41OTUgMy4xMTgtMjQuODEgNC43NDctMzQuNTYgOS40MjUtNTYuNjQ2IDI5LjcyLTk3LjIxIDc0LjcwNS0xMzMuMTkgNjYuMDI1LTUyLjgzIDEzOS43My03Mi45MzcgMjE5LjUwOS01OS42NDh6TTIxNS42NDUgNjY5LjIwN2EyMy4yNzMgMjMuMjczIDAgMCAxIDQ0LjE0OCAxNC43MzJjLTEzLjQyOCA0MC4yODUtMzkuNzk2IDc5LjgyNS03OC43NTQgMTE4LjgwN2EyMy4yNzMgMjMuMjczIDAgMSAxLTMyLjkzMS0zMi45MDdjMzQuMTg3LTM0LjE4OCA1Ni41NzYtNjcuNzcgNjcuNTM3LTEwMC42MzJ6bTQ0NC40NjMtMjg3LjA0NmEyMy4yNzMgMjMuMjczIDAgMCAxIDMyLjkzLTMyLjkwN2M0MS40MjYgNDEuNDI1IDYxLjY3MyAxMDIuMTY3IDYxLjY3MyAxODEuMDM4IDAgMTk1LjkxLTQxLjQ5NSAzNDMuNDYtMTMzLjM5OSA0NTMuNzQ5YTIzLjI3MyAyMy4yNzMgMCAxIDEtMzUuNzctMjkuNzljODQuMDE0LTEwMC44NCAxMjIuNjI0LTIzOC4wMzMgMTIyLjYyNC00MjMuOTU5IDAtNjcuNDQ0LTE2LjMzOC0xMTYuMzg3LTQ4LjA1OC0xNDguMTN6TTY0NC45OCA1MTIuMjU2YTIzLjI3MyAyMy4yNzMgMCAwIDEtNDYuNTQ1LS41MTJjLjY5OC02MC4xMzctMjUuMzY3LTg2LjE3OS04Ni40MzUtODYuMTc5cy04Ny4xMSAyNi4wNjYtODYuNTA1IDg3Ljk3MWMtMTIuNjYgMTg5Ljk5OS04My4zODYgMzI1LjAyNy0yMTIuMzg3IDQwMi40MDlhMjMuMjczIDIzLjI3MyAwIDEgMS0yMy45NDctMzkuODlDMzAzLjk4OCA4MDcuMTY4IDM2Ny4zNiA2ODYuMTk2IDM3OS4wMiA1MTIuMjU2IDM3OC4wNDIgNDI2LjEgNDI1LjE0NiAzNzkuMDIgNTEyIDM3OS4wMnMxMzMuOTU4IDQ3LjA4IDEzMi45OCAxMzMuMjM2ek00ODguNzI3IDUxMmEyMy4yNzMgMjMuMjczIDAgMCAxIDQ2LjU0NiAwYzAgMTg5LjI1NC02My41ODEgMzQxLjg3Ni0xOTAuNTU3IDQ1Ni4xNDVhMjMuMjczIDIzLjI3MyAwIDAgMS0zMS4xNC0zNC41ODNDNDMwLjQwNyA4MjguMzkyIDQ4OC43MjggNjg4LjQ1NCA0ODguNzI4IDUxMnptMTA5Ljk4NyAxMDYuMTdhMjMuMjczIDIzLjI3MyAwIDAgMSA0Ni4wMSA3LjA3NWMtMjUuMTggMTYzLjU2LTgyLjQwOCAyODQuMzkzLTE3Mi40NSAzNjEuNTY1YTIzLjI3MyAyMy4yNzMgMCAxIDEtMzAuMjU1LTM1LjMyOGM4MC42MTctNjkuMTIgMTMzLjA5Ny0xNzkuODk4IDE1Ni42OTUtMzMzLjMxMnoiIGZpbGw9IiM2RDc3OTMiLz48L3N2Zz4=',
    page: '/pages/device/key?type=fingerprint',
    key: 'fingerprint',
    adminMenu: true,
    color: '#f0f0fc'
  },
  {
    name: '操作纪录',
    icon: 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTUxMi41IDgyOUMzMzcuNzAyIDgyOSAxOTYgNjg3LjI5OCAxOTYgNTEyLjVTMzM3LjcwMiAxOTYgNTEyLjUgMTk2IDgyOSAzMzcuNzAyIDgyOSA1MTIuNSA2ODcuMjk4IDgyOSA1MTIuNSA4Mjl6bTAtNjBDNjU0LjE2MSA3NjkgNzY5IDY1NC4xNjEgNzY5IDUxMi41UzY1NC4xNjEgMjU2IDUxMi41IDI1NiAyNTYgMzcwLjgzOSAyNTYgNTEyLjUgMzcwLjgzOSA3NjkgNTEyLjUgNzY5em05OS40NjEtMTk4LjYyN2wtNDIuNDUzIDQyLjRMNDgyIDUyNS4xNTZ2LTE3MC4wNGg2MHYxNDUuMjFsNjkuOTYxIDcwLjA0N3oiIGZpbGw9IiM3MDcwNzAiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTMyLjdlODgzYTgxYktaNnZFIiBjbGFzcz0ic2VsZWN0ZWQiLz48L3N2Zz4=',
    page: '/pages/device/record',
    key: 'history',
    adminMenu: true,
    color: '#607d8b4d'
  },
  {
    name: '其他设置',
    icon: 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTQzOS4yNjQgMjA4YTE2IDE2IDAgMCAwLTE2IDE2djY3Ljk2OGEyMzkuNzQ0IDIzOS43NDQgMCAwIDAtNDYuNDk2IDI2Ljg5NmwtNTguOTEyLTM0QTE2IDE2IDAgMCAwIDI5NiAyOTAuNzJsLTgwIDEzOC41NmExNiAxNiAwIDAgMCA1Ljg1NiAyMS44NTZsNTguODk2IDM0YTI0Mi42MjQgMjQyLjYyNCAwIDAgMCAwIDUzLjcyOGwtNTguODggMzRhMTYgMTYgMCAwIDAtNi43MiAyMC4xNzZsLjg0OCAxLjY4IDgwIDEzOC41NmExNiAxNiAwIDAgMCAyMS44NTYgNS44NTZsNTguOTEyLTM0YTIzOS43NDQgMjM5Ljc0NCAwIDAgMCA0Ni40OTYgMjYuODhWODAwYTE2IDE2IDAgMCAwIDE2IDE2aDE2MGExNiAxNiAwIDAgMCAxNi0xNnYtNjcuOTY4YTIzOS43NDQgMjM5Ljc0NCAwIDAgMCA0Ni41MTItMjYuODk2bDU4LjkxMiAzNGExNiAxNiAwIDAgMCAyMS44NTYtNS44NTZsODAtMTM4LjU2YTE2IDE2IDAgMCAwLTQuMjg4LTIwLjgzMmwtMS41NjgtMS4wMjQtNTguODk2LTM0YTI0Mi42MjQgMjQyLjYyNCAwIDAgMCAwLTUzLjcyOGw1OC44OC0zNGExNiAxNiAwIDAgMCA2LjcyLTIwLjE3NmwtLjg0OC0xLjY4LTgwLTEzOC41NmExNiAxNiAwIDAgMC0yMS44NTYtNS44NTZsLTU4LjkxMiAzNGEyMzkuNzQ0IDIzOS43NDQgMCAwIDAtNDYuNDk2LTI2Ljg4VjIyNGExNiAxNiAwIDAgMC0xNi0xNmgtMTYwem0zMiA0OGg5NnY2Ny4zNzZsMjguOCAxMi41NzZhMTkyLjIxIDE5Mi4yMSAwIDAgMSAzNy4xODQgMjEuNTJsMjUuMjggMTguNjg4IDU4LjQ0OC0zMy43MjggNDggODMuMTM2LTU4LjM2OCAzMy42OCAzLjQ3MiAzMS4yYTE5NC42MjQgMTk0LjYyNCAwIDAgMSAwIDQzLjEwNGwtMy40NzIgMzEuMiA1OC4zNjggMzMuNjgtNDggODMuMTM2LTU4LjQzMi0zMy43MjgtMjUuMjk2IDE4LjY4OGExOTIuMjEgMTkyLjIxIDAgMCAxLTM3LjE4NCAyMS41MmwtMjguOCAxMi41NzZWNzY4aC05NnYtNjcuMzc2bC0yOC43ODQtMTIuNTc2YTE5Mi4yMSAxOTIuMjEgMCAwIDEtMzcuMTg0LTIxLjUybC0yNS4yOC0xOC42ODgtNTguNDQ4IDMzLjcyOC00OC04My4xMzYgNTguMzY4LTMzLjY4LTMuNDcyLTMxLjJhMTk0LjYyNCAxOTQuNjI0IDAgMCAxIDAtNDMuMTA0bDMuNDcyLTMxLjItNTguMzY4LTMzLjY4IDQ4LTgzLjEzNkwzODAgMzc2LjE2bDI1LjI5Ni0xOC42ODhhMTkxLjc0NCAxOTEuNzQ0IDAgMCAxIDM3LjE4NC0yMS41MmwyOC44LTEyLjU3NlYyNTZ6bTQ3LjI4IDE0NGExMTIgMTEyIDAgMSAwIDAgMjI0IDExMiAxMTIgMCAwIDAgMC0yMjR6bTAgNDhhNjQgNjQgMCAxIDEgMCAxMjggNjQgNjQgMCAwIDEgMC0xMjh6IiBmaWxsPSIjNUE2MjZBIi8+PC9zdmc+',
    page: '/pages/device/setting',
    key: 'setting',
    adminMenu: true,
    color: '#607d8b4d'
  },
  {
    name: '双击开锁',
    icon: 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTU0NS40OSAxMDI0SDIzNi40MjRBMTI5LjQ3NyAxMjkuNDc3IDAgMCAxIDEwNy4xMSA4OTQuNjg2VjEyOS4zMTRBMTI5LjQ3NyAxMjkuNDc3IDAgMCAxIDIzNi40MjQgMEg1NDUuNDlhMTI5LjQ3NyAxMjkuNDc3IDAgMCAxIDEyOS4zMTQgMTI5LjMxNHY0MzAuMzk0YTIzLjE0MSAyMy4xNDEgMCAwIDEtNDYuMjAxIDBWMTI5LjMxNEE4My4xMTMgODMuMTEzIDAgMCAwIDU0NS40OSA0Ni4ySDIzNi40MjRhODMuMTEzIDgzLjExMyAwIDAgMC04My4xMTMgODMuMTEzdjc2NS4zNzJhODMuMTEzIDgzLjExMyAwIDAgMCA4My4xMTMgODMuMTEzSDU0NS40OWE4My4xMTMgODMuMTEzIDAgMCAwIDgzLjExMy04My4xMTNWODMxLjEzYTIzLjE0MSAyMy4xNDEgMCAxIDEgNDYuMiAwdjYzLjM5NEExMjkuNDc3IDEyOS40NzcgMCAwIDEgNTQ1LjQ5IDEwMjR6IiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmkwLjQwNDgzYTgxdmxzUU5XIiBjbGFzcz0ic2VsZWN0ZWQiIGZpbGw9IiM4YThhOGEiLz48cGF0aCBkPSJNNDc4LjM0NyA0MzUuMjg0SDMwMy41NjZhNjYuMDgzIDY2LjA4MyAwIDAgMS02Ni4wODMtNjYuMDAyVjI1OS42MDZhNjYuMTY0IDY2LjE2NCAwIDAgMSA2Ni4wODMtNjYuMDgzaDE3NC43ODFhNjYuMTY0IDY2LjE2NCAwIDAgMSA2Ni4wODMgNjYuMDgzdjEwOS42NzZhNjYuMDgzIDY2LjA4MyAwIDAgMS02Ni4wODMgNjYuMDAyem0tMTc0Ljc4MS0xOTUuNTZhMTkuODgyIDE5Ljg4MiAwIDAgMC0xOS44ODIgMTkuODgydjEwOS42NzZhMTkuOCAxOS44IDAgMCAwIDE5Ljg4MiAxOS44aDE3NC43ODFhMTkuOCAxOS44IDAgMCAwIDE5Ljg4Mi0xOS44VjI1OS42MDZhMTkuODgyIDE5Ljg4MiAwIDAgMC0xOS44ODItMTkuODgyek00MjEuODggODMzLjk4YTE0NS40NDggMTQ1LjQ0OCAwIDAgMS0xNDUuMjg1LTE0NS4yODVBMTQ3LjMyMiAxNDcuMzIyIDAgMCAxIDQyMS44OCA1NDMuMzNhMTQ1LjIwMyAxNDUuMjAzIDAgMCAxIDUyLjcyIDkuODYgMjMuMTQxIDIzLjE0MSAwIDEgMS0xNi43MDUgNDMuMTA0IDk4LjUxMyA5OC41MTMgMCAwIDAtMzYuMDE1LTYuNzYzIDEwMC40NjkgMTAwLjQ2OSAwIDAgMC05OS4wODQgOTkuMTY1IDk5LjE2NSA5OS4xNjUgMCAwIDAgOTkuMDg0IDk5LjA4NCA5Ny43OCA5Ny43OCAwIDAgMCAzNi4wMTUtNi42ODIgMjMuMDYgMjMuMDYgMCAwIDEgMTYuNzg2IDQzLjAyNCAxNDMuOSAxNDMuOSAwIDAgMS01Mi44MDEgOS44NnoiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTEuNDA0ODNhODF2bHNRTlciIGNsYXNzPSJzZWxlY3RlZCIgZmlsbD0iIzhhOGE4YSIvPjxwYXRoIGQ9Ik04NTMuMDg5IDc1MC4zOEg0MzEuNjU4YTYzLjg4MyA2My44ODMgMCAwIDEgMC0xMjcuNjg1aDQyMS40M2E2My44ODMgNjMuODgzIDAgMCAxIDAgMTI3LjY4NHptLTQyMS40MzEtODEuNDg0YTE3LjY4MiAxNy42ODIgMCAwIDAgMCAzNS4yODJoNDIxLjQzYTE3LjY4MiAxNy42ODIgMCAwIDAgMC0zNS4yODJ6IiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmkyLjQwNDgzYTgxdmxzUU5XIiBjbGFzcz0ic2VsZWN0ZWQiIGZpbGw9IiM4YThhOGEiLz48L3N2Zz4=',
    page: '/pages/device/setting',
    key: 'open',
    adminMenu: false,
    color: '#f7f7f7'
  },
  // {name: '时间校对', icon: '../../static/images/lock-history.png', page: '/pages/device/timeSet', key: 'time',color: '#1400964d'},
])
const step = ref(0)

const onShareAppMessage = (res) => {
  if (res.from === 'button') {// 来自页面内分享按钮
    console.log(res.target)
  }
  return {
    title: '公寓设备管理小程序',
    path: '/pages/index/index',
  }
}

const categorys = ref([])
const goods = ref([])
const categoryId = ref(0)
const categoryChoose =  (id)=>{
  categoryId.value = id
  getGoods()
}
const getGoods = async ()=>{
  let parameter = {}
  if(categoryId.value){
    parameter.category_id  = categoryId.value
  }
  const response = await Good.index(parameter)
  goods.value = response.data.data
}
const getCategorys = async ()=>{
  const response = await Category.index()
  categorys.value = response.data.data
}


</script>

<template>
  <div class="top-container" style="background: none">
    <div class="default-title" @click="back">产品分类</div>
  </div>
  <div class="fixed-container">
    <div class="category-container">
      <div class="category-item" @click="categoryChoose(0)" :class="{'active':categoryId===0}">
        不限
      </div>
      <div class="category-item" v-for="item in categorys" @click="categoryChoose(item.id)" :class="{'active':categoryId===item.id}">
        {{item.name}}
      </div>
    </div>
    <div class="good-container">
      <div class="good-item" v-for="item in goods" @click="goodClick(item.id)" :key="item.id">
        <div class="image" :style="{backgroundImage: 'url('+baseUrl+item.image+')'}"
             style="background-size: contain;background-position: center center;background-repeat: no-repeat">

        </div>
        <div class="name">{{ item.name }}</div>
        <div class="price">
          <div class="amount">
            <span>￥</span>
            <span style="font-size: 20px">{{item.price}}</span>
          </div>
          <div class="cart-button"  @click.stop="addCart(item.id)">
            <image style="width: 20px;height: 20px"
                   src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTM4NCA5NjBjLTQ3LjEwNCAwLTg1LjMxMi0zNi40OC04NS4zMTItODEuNDcyUzMzNi44OTYgNzk3LjEyIDM4NCA3OTcuMTJzODUuMzEyIDM2LjQ4IDg1LjMxMiA4MS40MDhDNDY5LjMxMiA5MjMuNTIgNDMxLjEwNCA5NjAgMzg0IDk2MHptNDI2LjY4OCAwYy00Ny4xNjggMC04NS4zNzYtMzYuNDgtODUuMzc2LTgxLjQ3MnMzOC4yMDgtODEuNDA4IDg1LjM3Ni04MS40MDhjNDcuMTA0IDAgODUuMzEyIDM2LjQ4IDg1LjMxMiA4MS40MDhDODk2IDkyMy41MiA4NTcuNzkyIDk2MCA4MTAuNjg4IDk2MHpNMjYyLjI3MiAxODYuMjRIMTAyNGwtOTEuOTY4IDQwNy4yMzJoLTU3Ny45MmwxMy4xMiA1OC41Nmg1NTQuMTEybC0xOC41NiA4MS40MDhIMjk4LjI0bC0zMS41NTItMTM5Ljk2OGgtLjI1NmwtNTkuNTItMjY1LjYtNDEuMTUyLTE4Mi40SDBWNjRoMjM0Ljc1MmwyNy41MiAxMjIuMjR6bTE4LjM2OCA4MS40MDhMMzM1LjY4IDUxMmg1MjcuNDI0bDU1LjIzMi0yNDQuMzUySDI4MC42NHoiIGZpbGw9IiNmZmYiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTAuM2U5NDNhODFHM1Z2M1YiIGNsYXNzPSJzZWxlY3RlZCIvPjwvc3ZnPg=="></image>
          </div>
        </div>
      </div>
    </div>
  </div>
<CartDialog ref="cartDialogRef"></CartDialog>
</template>

<style scoped lang="scss">
.good-container {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;

  .good-item {
    margin-bottom: 15px;
    width: 42vw;

    .image {
      height: 42vw;
      width: 42vw;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 12px;
    }

    .name {
      font-size: 12px;
      height: 32px;
      overflow: hidden;
    }

    .price {
      display: flex;
      margin-top: 7.5px;
      justify-content: space-between;
      align-items: center;

      .amount {
        display: flex;
        margin-top: 5px;
        font-size: 18px;
        color: var(--default-color);
        font-weight: bold;

        span {
          font-size: 12px;
        }


      }

      .cart-button {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--default-color);
        border-radius: 50%;
      }
    }
  }
}
.category-container{
  display: flex;
  margin-bottom: 15px;
  .category-item.active{
    background-color: var(--default-color);
    color: white;
  }
  .category-item{
    padding: 5px 10px;
    background-color: #ececec;
    border-radius: var(--default-radius);
    font-size: 14px;
    margin-right: 5px;
  }
}
</style>