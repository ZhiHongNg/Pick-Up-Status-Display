import {request} from "@/utils/request";

export function list(lockId, userName) {
    console.log('sadasdasd',{
        lockId: lockId,
        userName: userName
    })
    return request('GET', 'lock/fingerprint/list', {
        lockId: lockId,
        userName: userName
    })
}

export function dropFingerPrint(fingerprintId,lockId, username) {
    return request('DELETE', 'lock/fingerprint/delete', {
        lockId: lockId,
        fingerprintId: fingerprintId,
        userName: username,
        deleteType:1
    })
}

export function addLockFingerprints(parmater) {
    return request('POST', 'lock/fingerprint/add', parmater)
}

