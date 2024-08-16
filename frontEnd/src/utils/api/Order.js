import RequestClass from "@/utils/RequestClass";
import moment from "moment";

export const Order = new RequestClass('Order')
export const getStatusName = (statusCode) => {
    if (statusCode === 0) return '待支付'
    if (statusCode === 1) return '已支付'
    if (statusCode === 2) return '已发货'
    if (statusCode === 4) return '已完成'
    if (statusCode === 5) return '已取消'
}
export const getStatusHints = (statusCode, cancel_reason = '', create_time='') => {
    if (statusCode === 0) return '请在' + moment.unix(create_time).add(30, 'minutes').format('YYYY-MM-DD HH:mm:ss') + '前完成支付'
    if (statusCode === 1) return '已成功支付，等待发货'
    if (statusCode === 2) return '商品已发出，请留意物流信息'
    if (statusCode === 4) return ''
    if (statusCode === 5) return cancel_reason
}