import {request} from "../request";

export function getPhone(parameter) {
    return request('GET', 'wechatUser/getPhone', parameter)
}
export function login(parameter, callback) {
    request('POST', 'wechatUser/login', parameter, callback)
}
export function add(parameter, callback) {
    request('POST', 'wechatUser/add', parameter, callback)
}

export function update(parameter, callback) {
    request('PUT', 'wechatUser/userUpdate', parameter, callback)
}

export function verify(parameter, callback) {
    request('POST', 'wechatUser/verify', parameter, callback)
}

export function index(parameter, callback) {
    request('GET', 'wechatUser', parameter, callback)
}

export function show(id, callback) {
    request('GET', 'wechatUser/' + id, {}, callback)
}


export function checkCanGetCuepon(callback) {
    request('GET', 'wechatUser/checkCanGetCuepon', {}, callback)
}
export function getCuepon(type, number, startDate, endDate, desc, fromOpenid,from_type,callback) {
    request('GET', 'wechatUser/getCuepon', {type, number, startDate, endDate, desc,fromOpenid,from_type}, callback)
}


export default {login, add, index, show}

