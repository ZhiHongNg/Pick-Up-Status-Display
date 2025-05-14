import moment from "moment";
import {Good} from "@/utils/api/Good";

export async function getFavGoods() {
    let storageFavGoods = uni.getStorageSync('favGood')
    let goods = []
    if (!storageFavGoods) {
        goods = []
    }else{
        let goodIds = []
        storageFavGoods.forEach(item=>{
            goodIds.push(Number(item))
        })
        const response = await Good.index({
            ids:goodIds.join(','),
        })
        goods = response.data.data

    }
    // storageFavGoods.forEach
    return goods
}
export async function getCartGoods(fliterByTick=false) {
    let storageCartGoods = uni.getStorageSync('carts')
    let goods = []
    if (!storageCartGoods) {
        goods = []
    }else{
        let goodIds = []
        let goodNumbersArray = []
        storageCartGoods.forEach(item=>{
            if(fliterByTick){
                if(item.tick){
                    goodIds.push(Number(item.id))
                    goodNumbersArray[item.id] = item.number
                }
            }else{
                goodIds.push(Number(item.id))
                goodNumbersArray[item.id] = item.number
            }

        })
        const response = await Good.index({
            ids:goodIds.join(','),
        })
        goods = response.data.data
        goods.map((item)=>{
            item.number = goodNumbersArray[item.id]
        })
        console.log('goods',goods)
    }
    // storageFavGoods.forEach
    return goods
}
export async function removeTickCartGoods(){
    let cartGoods = uni.getStorageSync('carts')
    cartGoods = cartGoods.filter(item => {
        return !item.tick
    })
    await uni.setStorage({
        key: 'carts',
        data: cartGoods
    })
    return
}