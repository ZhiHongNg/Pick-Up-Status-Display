import {lockKeys} from "@/utils/api/Key";
import moment from "moment";

export async function getLockKeys(lockId,username){
    const response = await lockKeys(lockId,username)
    const keys = []
    response.data.data.list.forEach(item => {
        if (item.userType == 110301) {
            item.is_admin = true
        } else if ((item.keyRight == 1)) {
            item.is_admin = true
        } else {
            item.is_admin = false
        }
        item.deleted = false
        if (item.keyStatus == 110401) {
            item.keyStatus = ""
        } else if (item.keyStatus == 110402) {
            item.keyStatus = "待接收"
        } else if (item.keyStatus == 110405) {
            item.keyStatus = "已冻结"
        } else if (item.keyStatus == 110408) {
            item.keyStatus = "已删除"
        } else if (item.keyStatus == 110410) {
            item.keyStatus = "已重置"
        }
        if (item.endDate == 0 && item.startDate == 0) {
            item.endDate = "有效"
            item.startDate = "永久"
        } else {
            console.log(item.startDate)
            console.log(item.endDate)
            item.endDate = moment(item.endDate).format('YYYY-MM-DD HH:mm:ss')
            item.startDate = moment(item.startDate).format('YYYY-MM-DD HH:mm:ss')
        }
        keys.push(item)
    })
    return keys
}