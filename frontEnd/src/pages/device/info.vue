<script setup>
import {computed, ref} from 'vue'
import {onLoad, onShow} from '@dcloudio/uni-app'
import {openLockByKey, setLockTime} from "@/utils/service/LockService";
import {getDevice} from "@/utils/api/Lock";
import {UserStore} from '@/store/UserStore'

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
  lockId.value = options.lockId
  device.value = await getDevice(userStore.phone, lockId.value)
  uni.setNavigationBarTitle({
    title: device.value.lockAlias
  });
})
onShow(async () => {
  console.log(userStore.phone, lockId.value)
  device.value = await getDevice(userStore.phone, lockId.value)
})
const boxShadowComputed = computed(() => {
  return `inset 0 0 0px ${s.value + 13}px #1296db`;
});
const transformComputed = computed(() => {
  return `rotate(180deg) translateX(${2 * s.value}px) translateY(-16px) scale(2)`;
})
const clickTime = ref(0)
const timeClickTimer = ref()
const menuClick = async (menu) => {
  uni.vibrateShort()
  const {page, key} = menu
  // device.isAdmin==true||menu.adminMenu==false
  if (key === 'open') {
    clickTime.value++
    if (clickTime.value === 1) {
      uni.showToast({
        title: '再点一次开锁'
      })
      timeClickTimer.value = setTimeout(() => {
        clickTime.value--
      }, 3000)
      return
    }
    if (clickTime.value === 2) {
      timeClickTimer.value = clearTimeout(timeClickTimer.value)
      // timeClickTimer.value = null

      openLockByKey(device.value, () => {
        setTimeout(() => {
          clickTime.value = 0
        }, 1500)

      })
      return
    }


    return
  }
  if (key === 'time') {
    await setLockTime(device.value)
  } else {
    if (key === 'password' || key === 'key' || key === 'card' || key === 'fingerprint') {
      uni.navigateTo({
        url: page + '&lockId=' + device.value.lockId
      })

    } else {
      uni.navigateTo({
        url: page + '?lockId=' + device.value.lockId
      })

    }

  }
}
// 处理长按开始的函数
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

const onShareAppMessage = (res)=> {
  if (res.from === 'button') {// 来自页面内分享按钮
    console.log(res.target)
  }
  return {
    title: '公寓设备管理小程序',
    path: '/pages/index/index',
  }
}

</script>

<template>
  <div style="width: 100vw;height: 100vh;background-repeat: no-repeat;
    background-size: cover;background-image: url(data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNTQwIDk2MCIgd2lkdGg9IjU0MCIgaGVpZ2h0PSI5NjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZmlsbD0iI2ZmZiIgZD0iTTAgMGg1NDB2OTYwSDB6Ii8+PGcgZmlsbD0iIzhkYjVmMSI+PGNpcmNsZSByPSI0OCIgY3g9IjQ2MyIgY3k9IjM3NiIvPjxjaXJjbGUgcj0iMyIgY3g9IjUwNCIgY3k9IjY2NSIvPjxjaXJjbGUgcj0iNiIgY3g9IjI2NyIgY3k9Ijg4MSIvPjxjaXJjbGUgcj0iMjIiIGN4PSIyNjkiIGN5PSIzNzQiLz48Y2lyY2xlIHI9IjEwIiBjeD0iNDQyIiBjeT0iNzY2Ii8+PGNpcmNsZSByPSI0MyIgY3g9IjI2NyIgY3k9IjE1MiIvPjxjaXJjbGUgcj0iNCIgY3g9IjE0OSIgY3k9IjM5MyIvPjxjaXJjbGUgcj0iNDYiIGN4PSI0NDciIGN5PSIxOCIvPjxjaXJjbGUgcj0iNDMiIGN4PSIxMjYiIGN5PSI2MTYiLz48Y2lyY2xlIHI9IjYiIGN4PSI0MDciIGN5PSI1MzUiLz48Y2lyY2xlIHI9IjQ0IiBjeD0iOTUiIGN5PSI4MDUiLz48Y2lyY2xlIHI9IjYiIGN4PSIyOSIgY3k9IjQ0NyIvPjxjaXJjbGUgcj0iMjciIGN4PSIxMDMiIGN5PSIxMDgiLz48Y2lyY2xlIHI9IjIyIiBjeD0iMzAzIiBjeT0iNjM4Ii8+PGNpcmNsZSByPSI0IiBjeD0iNDM2IiBjeT0iMTgxIi8+PGNpcmNsZSByPSIyMSIgY3g9IjM0NSIgY3k9Ijc2NCIvPjxjaXJjbGUgcj0iNCIgY3g9IjIyIiBjeT0iMzM4Ii8+PGNpcmNsZSByPSIyMSIgY3g9IjQ4MSIgY3k9Ijg4MSIvPjxjaXJjbGUgcj0iNDUiIGN4PSIzNzIiIGN5PSIzMTQiLz48Y2lyY2xlIHI9IjQiIGN4PSIyMDQiIGN5PSIyNzEiLz48Y2lyY2xlIHI9IjQxIiBjeD0iMTciIGN5PSI4NzUiLz48Y2lyY2xlIHI9IjIiIGN4PSI3NyIgY3k9IjcwNiIvPjxjaXJjbGUgcj0iMzkiIGN4PSIxOTIiIGN5PSI3NSIvPjxjaXJjbGUgcj0iMTkiIGN4PSIxNzgiIGN5PSI5NTAiLz48L2c+PC9zdmc+)">
    <div class="background">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
    <div style="width:100%;height:100vh;background: rgb(0 0 0 / .1);"></div>
    <div class="top-container" style="background: none">
      <div class="return-title" @click="back">返回</div>
    </div>
    <div class="fixed-container" style="background: #ffffffab">
      <div class="menu-container">
        <div class="device-item ">
          <div class="deivce-icon">
            <image style="width: 50px; height: 50px; " mode="scaleToFill" src="../../static/images/new_lock.png"/>
          </div>
          <div class="device-name">
            <div class="name">{{ device.lockAlias }}</div>
            <div class="date">
              时效：
            </div>
            <div class="date">
              {{ device.dateTime }}
            </div>
          </div>
          <div class="lock-angle"></div>
        </div>

        <template v-if="device.isAdmin">
          <div class="menu-item" :class="{'ooo':menu.key==='open'}" v-for="(menu,key) in menus" :key="key"
               @click="menuClick(menu)">
            <div class="icon" :class="{'open-button':menu.key==='open','step1':clickTime===1,'step2':clickTime===2}"
                 :style="{backgroundColor:menu.color}">
              <image style="width: 50px;height: 50px;z-index: 99" :src="menu.icon"></image>
            </div>
            <div class="name">{{ menu.name }}</div>
          </div>
        </template>
        <template v-if="!device.isAdmin">
          <div class="menu-item" :class="{'ooo':menu.key==='open'}" v-for="(menu,key) in menus" :key="key" v-show="menu.key==='open'"
               @click="menuClick(menu)">
            <div class="icon" :class="{'open-button':menu.key==='open','step1':clickTime===1,'step2':clickTime===2}"
                 :style="{backgroundColor:menu.color}">
              <image style="width: 50px;height: 50px;z-index: 99" :src="menu.icon"></image>
            </div>
            <div class="name">{{ menu.name }}</div>
          </div>
        </template>

      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.fixed-container {
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}

.menu-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;

  .menu-item {
    text-align: center;
    margin-bottom: 40px;

    .icon {
      line-height: 40px;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background-color: #ff6f67;
      border-radius: 21px;
      padding: 15px;
      margin: 15px;
      box-shadow: var(--default-shadow);
      position: relative;
      overflow: hidden;

    }
  }
}

.open-button {
  width: calc(100% - 60px) !important;
}

.open-button:after {
  content: "";
  width: 100%;
  height: 100%;
  position: absolute;
  left: -100%;
  top: 0;
  background-color: #2196f380;
  border-radius: 21px;
  border-top-left-radius: 21px;
  border-top-right-radius: 21px;
  border-bottom-right-radius: 21px;
  border-bottom-left-radius: 21px;
  transition: all 0.3s cubic-bezier(0.01, -0.04, 0.56, 1.35);
  transform: scale(1);


}

.open-button.step1 {
  //transform: scale(1.2);
}

.open-button.step2 {
  //transform: scale(1.4);
}

.open-button.step1:after {
  left: -50%;
}

.open-button.step2:after {
  left: 0;
}

.ooo {
  width: 100%;
}

.fixed-container {
  width: calc(100% - 40px);
  position: fixed;
  overflow: scroll;
  height: fit-content;
  background: white;
  top: unset !important;
  bottom: 5vh;
  border-radius: 20px;
  box-shadow: var(--default-shadow);
  padding: 10px;
  margin: 10px;
}

@keyframes animate {
  0%{
    transform: translateY(0) rotate(0deg);
    opacity: 1;
    border-radius: 0;
  }
  100%{
    transform: translateY(-1000px) rotate(720deg);
    opacity: 0;
    border-radius: 50%;
  }
}

.background {
  position: fixed;
  width: 100vw;
  height: 100vh;
  top: 0;
  left: 0;
  margin: 0;
  padding: 0;
  background: #4e54c8;
  overflow: hidden;
}
.background view {
  position: absolute;
  display: block;
  list-style: none;
  width: 20px;
  height: 20px;
  background: rgba(255, 255, 255, 0.2);
  animation: animate 4s linear infinite;
}




.background view:nth-child(0) {
  left: 38%;
  width: 20px;
  height: 20px;
  bottom: -20px;
  animation-delay: 1s;
}
.background view:nth-child(1) {
  left: 79%;
  width: 22px;
  height: 22px;
  bottom: -22px;
  animation-delay: 2s;
}
.background view:nth-child(2) {
  left: 13%;
  width: 133px;
  height: 133px;
  bottom: -133px;
  animation-delay: 7s;
}
.background view:nth-child(3) {
  left: 64%;
  width: 77px;
  height: 77px;
  bottom: -77px;
  animation-delay: 13s;
}
.background view:nth-child(4) {
  left: 10%;
  width: 110px;
  height: 110px;
  bottom: -110px;
  animation-delay: 8s;
}
.background view:nth-child(5) {
  left: 64%;
  width: 73px;
  height: 73px;
  bottom: -73px;
  animation-delay: 14s;
}
.background view:nth-child(6) {
  left: 72%;
  width: 108px;
  height: 108px;
  bottom: -108px;
  animation-delay: 15s;
}
.background view:nth-child(7) {
  left: 84%;
  width: 128px;
  height: 128px;
  bottom: -128px;
  animation-delay: 19s;
}
.background view:nth-child(8) {
  left: 24%;
  width: 113px;
  height: 113px;
  bottom: -113px;
  animation-delay: 20s;
}
.background view:nth-child(9) {
  left: 64%;
  width: 111px;
  height: 111px;
  bottom: -111px;
  animation-delay: 7s;
}
.background view:nth-child(10) {
  left: 81%;
  width: 48px;
  height: 48px;
  bottom: -48px;
  animation-delay: 47s;
}
.background view:nth-child(11) {
  left: 62%;
  width: 44px;
  height: 44px;
  bottom: -44px;
  animation-delay: 27s;
}
.background view:nth-child(12) {
  left: 73%;
  width: 22px;
  height: 22px;
  bottom: -22px;
  animation-delay: 60s;
}
.background view:nth-child(13) {
  left: 84%;
  width: 41px;
  height: 41px;
  bottom: -41px;
  animation-delay: 27s;
}
.background view:nth-child(14) {
  left: 38%;
  width: 59px;
  height: 59px;
  bottom: -59px;
  animation-delay: 57s;
}
.background view:nth-child(15) {
  left: 73%;
  width: 50px;
  height: 50px;
  bottom: -50px;
  animation-delay: 38s;
}
</style>