import {lockPasswords} from "@/utils/api/Password";
import moment from "moment/moment";

export async function getLockPasswords(lockId, userName) {
    const response = await lockPasswords(lockId, userName)
    const passwords = []
    response.data.data.list.forEach(item => {
        item.endDate = moment(item.endDate).format('YYYY-MM-DD')
        item.startDate = moment(item.startDate).format('YYYY-MM-DD')
        item.keyboardPwdType = getKeyboardPwdTypeStr(item.keyboardPwdType)
        item.hiding = false
        passwords.push(item)
    })
    return passwords
}
const keyboardPwdType = ['请选择', '单次有效', '永久有效', '限期', '删除', '周未循环', '每日循环', '工作日循环', '周一循环', '周二循环', '周三循环', '周四循环', '周五循环', '周六循环', '周日循环']
export function getKeyboardPwdTypeStr(type) {
    return keyboardPwdType[type]
}
export function getKeyboardPwdType() {
    return keyboardPwdType
}
