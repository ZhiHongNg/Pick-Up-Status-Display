<script setup>
import {onLoad} from "@dcloudio/uni-app";
import {ref} from "vue";
import {getLockKeys} from "@/utils/service/LockKeyService";
import {getDevice} from "@/utils/api/Lock";
import {getLockPasswords} from "@/utils/service/LockPasswordService";
import AddPasswordDialog from "@/components/password/AddPasswordDialog.vue";
import {deletLockPassword} from "@/utils/api/Password";
import AddKeyDialog from "@/components/key/AddKeyDialog.vue";
import {drop} from "@/utils/api/Key";
import Empty from "@/components/Empty.vue";
import AddFingerPrintDialog from "@/components/fingerprint/AddFingerPrintDialog.vue";
import {dropFingerPrint} from "@/utils/api/Fingerprint";
import {UserStore} from '@/store/UserStore'
import {getLockFingerPrints} from "@/utils/service/LockFingerPrintService";
import LoadingLoading from "@/components/LoadingLoading.vue";
const addPasswordDialogRef = ref()
const addKeyDialogRef = ref()
const addFingerPrintDialogRef = ref()

const lockId = ref()
const type = ref()
const typeStr = ref('')
const device = ref({})
const passwords = ref([])
const fingerprints = ref([])
const keys = ref([])

const back = () => {
  uni.navigateBack()
}

const userStore = UserStore()
const hidePassword = (passwordId) => {
  const list = passwords.value
  list.forEach((item) => {
    if (item.passwordId == passwordId) {
      item.hiding = !item.hiding
    }
  })
  passwords.value = list
}
const loading = ref(false)
const getKeys = async () => {
  loading.value = true
  keys.value = await getLockKeys(lockId.value, userStore.phone)
  loading.value = false
}
const getPasswords = async () => {
  loading.value = true
  passwords.value = await getLockPasswords(lockId.value, userStore.phone)
  loading.value = false
}
const getFingerprints = async () => {
  loading.value = true
  fingerprints.value = await getLockFingerPrints(lockId.value, userStore.phone)
  loading.value = false
}
const showSheet = (item) => {
  if (type.value === 'key') {
    uni.showActionSheet({
      itemList: ['删除钥匙'],
      success: (res) => {
        if (res.tapIndex === 0) {

          //删除钥匙
          if (device.value.keyId == item.keyId) {
            uni.showToast({
              icon: 'none',
              title: '不能删除该钥匙，您正在使用该锁匙'
            })
            return
          }
          drop(item.keyId, userStore.phone)
          uni.showToast({
            icon: 'success',
            title: '删除成功'
          })
          getKeys()
        } else if (res.tapIndex === 1) {
          //修改有效期
        }
      },
      fail: function (res) {

      }
    })
  } else if (type.value === 'password') {
    uni.showActionSheet({
      itemList: ['删除密码'],
      success: async (res) => {
        if (res.tapIndex === 0) {
          uni.showLoading({
            title: '操作中···'
          })

          lockplugin.deletePasscode(item.keyboardPwd, device.value.lockData, async (disconnectCallback) => {
            if (disconnectCallback.errorCode === 0) {
              deletLockPassword(device.value.lockId, item.keyboardPwdId, userStore.phone).then(() => {
                uni.hideLoading()
                reload()
                uni.showToast({
                  icon: 'success',
                  title: disconnectCallback.errorMsg
                })
              })

            }else{
              uni.hideLoading()
              uni.showToast({
                icon: 'none',
                title: disconnectCallback.errorMsg
              })

            }
          })

        } else if (res.tapIndex === 1) {
          //修改有效期
        }
      },
      fail: function (res) {

      }
    })
  } else if (type.value === 'fingerprint') {
    uni.showActionSheet({
      itemList: ['删除指纹'],
      success: (res) => {
        if (res.tapIndex === 0) {
          //删除钥匙
          uni.showLoading({
            title: '操作中···'
          })
          lockplugin.deleteFingerprint(item.fingerprintNumber, device.value.lockData, async (disconnectCallback) => {
            uni.hideLoading()
            if (disconnectCallback.errorCode === 0 || disconnectCallback.errorCode === 26) {
              //26指蓝牙删掉了，api还没有没有删掉
              await dropFingerPrint(item.fingerprintId, device.value.lockId, userStore.phone)
              reload()
              uni.showToast({
                icon: 'success',
                title: '删除成功'
              })
            } else {
              uni.showToast({
                icon: 'none',
                title: disconnectCallback.errorMsg
              })
            }


          })

          reload()
        } else if (res.tapIndex === 1) {
          //修改有效期
        }
      },
      fail: function (res) {

      }
    })

  }
}
onLoad(async (options) => {
  lockId.value = options.lockId
  type.value = options.type
  if (options.type === 'key') {
    typeStr.value = '钥匙'
  } else if (options.type === 'password') {
    typeStr.value = '密码'
  } else if (options.type === 'card') {
    typeStr.value = '房卡'
  } else if (options.type === 'fingerprint') {
    typeStr.value = '指纹'
  }
  device.value = await getDevice(userStore.phone, lockId.value)
  if (type.value == 'key') {
    reload()
    uni.setNavigationBarTitle({
      title: device.value.lockAlias + '钥匙管理'
    })
  } else if (type.value === 'password') {
    reload()
    uni.setNavigationBarTitle({
      title: device.value.lockAlias + '密码管理'
    })
  } else if (type.value === 'fingerprint') {
    reload()
    uni.setNavigationBarTitle({
      title: device.value.lockAlias + '指纹管理'
    })
  }

})
const add = () => {
  if (type.value === 'key') {
    addKeyDialogRef.value.showDialog(lockId.value, userStore.phone)
  } else if (type.value == 'password') {
    addPasswordDialogRef.value.showDialog(lockId.value, userStore.phone)
  } else if (type.value == 'fingerprint') {
    addFingerPrintDialogRef.value.showDialog(device.value.lockData, device.value.lockId, userStore.phone)
  }
}
const reload = () => {
  if (type.value == 'key') {
    getKeys()
  } else if (type.value == 'password') {
    getPasswords()
  } else if (type.value == 'fingerprint') {
    getFingerprints()
  }
}

</script>

<template>
  <div class="top-container">
    <div class="default-title" @click="back">{{ typeStr }}管理</div>
  </div>
  <div class="fixed-container">
    <!--    <div class="loading-container">-->
    <!--      <uni-icons type="reload" size="20" color="#ffffff"></uni-icons>-->
    <!--    </div>-->
    <LoadingLoading v-if="loading===true"/>
    <Empty
        v-if="loading===false&&((type=='key'&&keys.length===0)||(type=='fingerprint'&&fingerprints.length===0)||(type=='password'&&passwords.length===0))">
      没有找到相关内容
    </Empty>
    <div class="device-item" v-if="loading===false&&type=='key'" style="justify-content: space-between" v-for="key in keys"
         @click="showSheet(key)">
      <div class="device-name">
        <div class="name">
          {{ key.keyName }}
        </div>
        <div class="date">{{ key.startDate }}-{{ key.endDate }}</div>
        <span v-if="key.is_admin" class="default-tag">{{ key.is_admin ? '管理员' : '' }}</span>
      </div>
      <div class="key-icon">
        <image style="width: 50px; height: 50px;" mode="scaleToFill" src="../../static/images/keys.png"/>
      </div>
    </div>
    <div class="device-item" v-if="loading===false&&type=='password'" style="justify-content: space-between"
         v-for="password in passwords" @click="showSheet(password)">
      <div class="device-name">
        <div class="name">
          {{ password.keyboardPwdName }}
          <span class="default-tag">{{ password.keyboardPwdType }}</span>
        </div>
        <div class="date">{{ password.startDate }}-{{ password.endDate }}</div>
      </div>
      <div class="key-icon" style="text-align: center" @click.stop="hidePassword(password.passwordId)">
        <image v-if="password.hiding" style="width: 50px; height: 50px;" mode="scaleToFill"
               src="../../static/images/password.png"/>
        <div v-if="!password.hiding" style="color: #2196f3;font-size: 26px;font-weight: bold">
          {{ password.keyboardPwd }}
        </div>
        <div v-if="!password.hiding" style="font-size: 12px">点击隐藏</div>
      </div>
    </div>
    <div class="device-item" v-if="loading===false&&type=='fingerprint'" style="justify-content: space-between"
         v-for="fingerprint in fingerprints" @click="showSheet(fingerprint)">
      <div class="device-name">
        <div class="name">
          {{ fingerprint.fingerprintName }}
        </div>
        <div class="date">{{ fingerprint.startDate }}-{{ fingerprint.endDate }}</div>
      </div>
      <div class="key-icon" style="text-align: center">
        <image style="width: 50px; height: 50px;" mode="scaleToFill"
               src="../../static/images/fingerprint.png"/>
      </div>
    </div>

  </div>
  <div class="add-button-container">
    <div class="default-button" @click="add">添加{{ typeStr }}</div>
  </div>
  <AddKeyDialog @success="reload" ref="addKeyDialogRef"></AddKeyDialog>
  <AddPasswordDialog @success="reload" ref="addPasswordDialogRef"></AddPasswordDialog>
  <AddFingerPrintDialog @success="reload" ref="addFingerPrintDialogRef"></AddFingerPrintDialog>


</template>

<style scoped lang="scss">

.refresh-button {
  padding: 5px 10px;
  text-align: center;
  background-color: #b1b1b1;
  color: white;
  border-radius: 5px;
  display: flex;
  align-items: center;
}


.loading-container {
  position: absolute;
  width: 90%;
  height: 100%;
  background: #ffffffa1;
}
</style>