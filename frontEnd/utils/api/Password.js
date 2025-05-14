import {request} from "@/utils/request";

export function lockPasswords(lockId, userName) {
    return request('GET', 'lock/listKeyboardPwd', {
        lockId: lockId,
        userName: userName
    })
}

export function addLockPassword(parameter) {
    return request('POST', 'lock/keyboardPwd/get', parameter)
}

export function deletLockPassword(lockId, keyboardPwdId, userName) {
    return request('DELETE', 'lock/keyboardPwd/delete', {
            lockId,
            keyboardPwdId,
            userName,
            deleteType: 1,
        }
    )
}