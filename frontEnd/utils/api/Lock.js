import {request} from "@/utils/request";
import {list} from "@/utils/api/Key";
import moment from "moment";
export async function getDecices(phone){
    const devices = []
    const response = await list(phone)
    response.data.data.list.forEach(item => {
        if (item.userType == 110301) {
            item.admin = "管理员"
            item.isAdmin = 1
        } else if ((item.keyRight == 1 && item.userType == 110302)) {
            item.admin = "授权管理员"
            item.isAdmin = 2
        } else {
            item.admin = ""
            item.isAdmin = 0
        }
        if (item.endDate == 0 && item.startDate == 0) {
            item.dateTime = "永久有效"
        } else {
            item.endDate = moment(new Date(item.endDate)).format('yyyy-MM-DD')
            item.startDate = moment(new Date(item.startDate)).format('yyyy-MM-DD')
            item.dateTime = item.startDate + "至" + item.endDate
        }
        devices.push(item)
    })
    return devices
}
export async function getDevice(phone,lockId){
    const devices = await getDecices(phone)
    const device = devices.find(item=>{
        if(item.lockId == lockId)return true
    })
    return device
}
export function init(lockData,lockAlias,userName) {
    return request('POST', 'lock/init', {
            lockData: lockData,
            lockAlias: lockAlias,
            userName: userName
        }
    )
}

export function rename(lockId, lockAlias, userName) {
    return request('POST', 'lock/rename', {
            lockId: lockId,
            lockAlias: lockAlias,
            userName: userName
        }
    )
}
export function deleteLock(lockId, userName) {
    return request('DELETE', 'lock/delete', {
            lockId: lockId,
            userName: userName
        }
    )
}
