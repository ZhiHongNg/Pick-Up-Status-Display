
import {defineStore} from 'pinia'

export const UserStore = defineStore('PWxUser', {
    state: () => {
        return {
            openid: uni.getStorageSync('openid'),
            phone: uni.getStorageSync('phone')

        }
    },
    getters: {
        getOpenid() {
            return this.openid
        },
        getPhone() {
            return this.phone
        },
    },
    actions: {
        setPhone(phone){
            uni.setStorage({
                key: 'phone',
                data: phone
            })
            this.phone = phone
        },
        setOpenid(openid){
            uni.setStorage({
                key: 'openid',
                data: openid
            })

            this.openid = openid
        }
    }
})