import {request} from "@/utils/request";

export function list(userName,) {
    return request('GET', 'lock/key/list', {
        UserName: userName,
        pageSize: 1000
    })
}


export function send(parameter) {
    return request('POST', 'lock/key/send', parameter)
}

export function drop(keyId, username) {
    return request('DELETE', 'lock/key', {
        keyId: keyId,
        userName: username
    })
}


export function lockKeys(lockId, userName, PageNo = 1, pageSize = 1000) {
    return request('GET', 'lock/listKey', {
        lockId: lockId,
        pageNo: PageNo,
        pageSize: pageSize,
        userName: userName
    })

}
