// const apiUrl = 'https://apartment.cntopc.com/api/'
// const oUrl = window.location.hostname + ':6464/';
export const oUrl = '192.168.2.7:6464/'
export const apiUrl = 'http://' + oUrl + 'wechat/'

export const baseUrl = 'http://' + oUrl
// export  const baseUrl = 'https://parking.cntopc.com/'

export const scoketUrl = 'ws://' + oUrl + 'websocket/'
// export const scoketUrl = 'ws://localhost:6464/'

export function request(method, action, parameter = {}) {
    return uni.request({
        timeout:'5000',
        url: apiUrl + action,
        data: parameter,
        method:method,
        header: {
            'Content-Type': 'application/json',
            'openid':uni.getStorageSync('openid')
        }
    })
}
export function generateRandomString(prefix) {
    const randomString = Math.random().toString(36).substring(2, 15) +
        Math.random().toString(36).substring(2, 15);
    return `${prefix}_${randomString}`;
}