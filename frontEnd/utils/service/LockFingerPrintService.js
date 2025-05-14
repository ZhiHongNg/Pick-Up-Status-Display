import {list} from "@/utils/api/Fingerprint";
import moment from "moment/moment";

export async function getLockFingerPrints(lockId, userName){
    const fingerList = []
    const response = await list(lockId, userName)
    response.data.data.list.forEach(item=>{
        item.endDate = moment(item.endDate).format('YYYY-MM-DD')
        item.startDate = moment(item.startDate).format('YYYY-MM-DD')
        fingerList.push(item)
    })
    return fingerList
}