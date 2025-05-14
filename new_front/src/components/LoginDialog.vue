<template>
  <main>
    <div v-if="dialogVisable" class="mask-container" @click="hideDialog"></div>
    <div :class="{'visable':dialogVisable}" class="main-container">
<!--      <div class="default-title">发送钥匙</div>-->
        <div class="login-type">
          <div class="title" :class="{'active':loginType===1}" @click="changeLoginType(1)">微信登录</div>
          <div class="title" :class="{'active':loginType===2}" @click="changeLoginType(2)">账号登陆</div>
        </div>
      <div style="    padding: 0 40px;
    margin-top: 50px;">
        <button class="default-button"  open-type="getPhoneNumber" v-show="loginType===1" @getphonenumber="getPhoneNumber">授权登录</button>
      </div>

        <div class="password-login-container" v-show="loginType===2">
          <div class="field">
            <div class="name">用户名</div>
            <div class="value">
              <input type="text" v-model="loginForm.username"
                     :class="{'shake':loging&&!loginForm.username}" placeholder="请输入用户名">
            </div>
          </div>
          <div class="field">
            <div class="name">密码</div>
            <div class="value">
              <input type="password" v-model="loginForm.password"
                     :class="{'shake':loging&&!loginForm.password}" placeholder="请输入密码">
            </div>
          </div>
          <div class="login-button" @click="passwordLogin">
            <div></div>
            <div>{{ loging ? '登录中···' : '登录' }}</div>
          </div>
        </div>
        <div class="private"  :class="{'shake':loging&&!check}">
          <div class="check-point" :class="{'check':check}" @click="check=!check"></div>
          <p>我已阅读并且遵守 <span style="color: #007aff" @click="priviteRuleDialog.showDialog()">《用户协议》</span></p>
          <PriviteRuleDialog ref="priviteRuleDialog" @success="check = true" />
        </div>
      </div>

  </main>

</template>
<script setup >
import {ref} from "vue";
import {UserStore} from '@/store/UserStore'
import wechat, {getPhone} from "@/utils/api/wechat";
import PriviteRuleDialog from "@/components/PriviteRuleDialog.vue";

const emit = defineEmits(['success'])
const priviteRuleDialog = ref()
const getPhoneNumber = (event)=>{
  uni.login({
    provider: 'weixin', //使用微信登录
    success: function (loginRes) {
      console.log('loginRes',loginRes)
      uni.request({
        url:   "http://localhost:5000/index.php/wechat/wechatUser/login",
        data: {
          code: loginRes.code,
        },
        method: "post",
        header: {
          'content-type': 'application/json',
          'cache-control': 'no-cache',
        },
        success: async (response) => {
          userStore.setOpenid(response.data.data)
          const phoneResponse = await getPhone({
            encryptedData: event.detail.encryptedData,
            iv: event.detail.iv,
            openid: response.data.data
          })
          userStore.setPhone(phoneResponse.data.data.phoneNumber)
          emit('success',phoneResponse.data.data.phoneNumber)

          hideDialog()
        },
        fail: (response) => {

        },
        complete: (response) => {

        }
      })

      loginRes.code

    }
  });

}
const loginForm = ref({
  username: '',
  password: '',
})
const dialogVisable = ref(false)
const loginType = ref(1)
const check = ref(false)
const loging = ref(false)
const changeLoginType = (type) => {
  loginType.value = type

}
const showDialog = () => {
  dialogVisable.value = true
}
const hideDialog = () => {
  dialogVisable.value = false
}
const wechatAuthLogin = () => {
  //todo::授权登录
}
const userStore = UserStore()
const userInfo = ref(userStore.userInfo)
const successFunctionName = ref()
const checkLogin = () => {
  if (!userInfo.value.openid) {
    showDialog()
    return false
  } else {
    return true
  }
}
const passwordLogin = () => {
  if (!loginForm.value.password || !loginForm.value.username || !check.value) {
    loging.value = true
    setTimeout(() => {
      loging.value = false
    }, 1000)
    return
  }
  //todo::账号密码登录
}
const getUserProfile = (e) => {
  uni.getUserProfile({
    desc: '获取你的头像昵称信息',
    complete: (response) => {
      if (response.errMsg == 'getUserProfile:fail auth deny') {
        return
      }
      const userInfo = response.userInfo
      uni.login({
        provider: 'weixin',
        success: (loginResponse) => {
          console.log('loginResponse',loginResponse)
          return
          const code = loginResponse.code

          wechat.login({...userInfo, ...{code: loginResponse.code}, ...{member_from: ''}}, (response) => {
            if (response.data.data.openid) UserStore().setUser(response.data.data)
            emit('success')
            hideDialog()
          })
        }
      });
    }
  })
}

defineExpose({
  showDialog, hideDialog,checkLogin
})
</script>
<style lang="scss">
.example {
  /* #ifndef APP-NVUE */
  display: flex;
  /* #endif */
  justify-content: center;
  align-items: center;
  height: 150px;
  background-color: #fff;
}

.login-container {
  width: calc(100vw - 60px);
  height: fit-content;
  background-color: white;
  padding: 15px 30px;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  padding-bottom: 80px;
}

.login-type {
  display: flex;
  justify-content: left;
  flex-direction: row;
  align-items: baseline;
}

.login-type .title {
  margin-right: 10px;
  color: #5b5b5b;
  font-weight: bold;
}

.login-type .title:first-child {
  border-right: 1px solid #ddd;
  padding-right: 10px;
}

.login-type .title.active {
  font-size: 20px;
  color: black;
}

.wechat-login-button {
  margin-top: 30px;
  text-align: center;
}

.wechat-login-button image, .wechat-login-button img {
  width: 30px;
  height: 30px;
  margin-top: 4px;
  margin-right: 7px;
}

.private {
  padding: 30px 0;
  text-align: center;
}

.password-login-container {
  margin-top: 30px;
}

.password-login-container .field:first-child {
  border-bottom: 1px solid #e9e9e9;
}

.password-login-container .field {
  display: flex;
  justify-content: space-between;
  padding: 15px 0;
  align-items: center;

}

.password-login-container .field .name {
  width: 20%;
  text-align: left;
}

.password-login-container .field .value {
  width: 80%;
}

.password-login-container .field .value input {
  border-radius: 20px;
  background-color: #efefef;
  padding: 7.5px 15px;
}

.login-button {
  margin-top: 15px;
  padding: 10px;
  background-color: #007aff;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  border-radius: 20px;
}

@keyframes fade-in {
  0% {
    opacity: 0;
  }
  70% {
    opacity: 1;
  }
}

.private {
  display: flex;
  justify-content: center;
  align-items: baseline;
}

.check-point {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  border: 1px solid #555555;
  position: relative;
  margin-right: 5px;
}

.check-point.check::after {
  content: "";
  width: 9px;
  height: 9px;
  background-color: #007aff;
  position: absolute;
  border-radius: 50%;
  left: 3px;
  top: 3px;
}




</style>

