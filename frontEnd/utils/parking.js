import moment from "moment";

export function generateLicensePlates(number=20) {
    const provinces = ["京", "津", "沪", "渝", "冀", "豫", "云", "辽", "黑", "湘", "皖", "鲁", "新", "苏", "浙", "赣", "鄂", "桂", "甘", "晋", "蒙", "陕", "吉", "闽", "贵", "粤", "青", "藏", "川", "宁", "琼"];
    const regions = ["1-8号上车点（A通道）", "9-15号上车点（B通道）"];

    function getRandomElement(array) {
        return array[Math.floor(Math.random() * array.length)];
    }

    function getRandomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function getRandomPlate() {
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        const numbers = "0123456789";
        let plate = "";
        for (let i = 0; i < 2; i++) {
            plate += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        for (let i = 0; i < 4; i++) {
            plate += numbers.charAt(Math.floor(Math.random() * numbers.length));
        }
        return plate;
    }
    function getEntryTime(){
        const now = moment();

        // 随机生成1到20之间的分钟数
        const randomMinutes = Math.floor(Math.random() * 20) + 1;
        // 当前时间减去随机生成的分钟数
        const randomTime = now.subtract(randomMinutes, 'minutes');

        // 返回格式化的时间
        return randomTime.format('YYYY-MM-DD HH:mm:ss');
    }

    const items = [];
    for (let i = 0; i < number; i++) {
        const province = getRandomElement(provinces);
        const isElectric = Math.random() < 0.2; // 20% chance to be an electric car
        const plate = getRandomPlate();
        const type = 'in';
        const entryTime = getEntryTime()
        const entryId = getRandomNumber(1, 2);
        const entryRegion = getRandomElement(regions);
        const entryNumber = getRandomNumber(1, 19);

        items.push({
            licensePlate: `${province}${plate}`,
            province,
            isElectric,
            type,
            plate,
            entryTime,
            entryId,
            entryRegion,
            entryNumber
        });
    }
    return items;
}
import RequestClass from "@/utils/RequestClass";

export const Parking = new RequestClass('Parking')
