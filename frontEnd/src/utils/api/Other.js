import RequestClass from "@/utils/RequestClass";
import * as XLSX from "xlsx";

export const Other = new RequestClass('Other')

export function validateIDCard(idCard) {
    // 省份代码
    const provinces = {
        11: "北京", 12: "天津", 13: "河北", 14: "山西", 15: "内蒙古",
        21: "辽宁", 22: "吉林", 23: "黑龙江", 31: "上海", 32: "江苏",
        33: "浙江", 34: "安徽", 35: "福建", 36: "江西", 37: "山东",
        41: "河南", 42: "湖北", 43: "湖南", 44: "广东", 45: "广西",
        46: "海南", 50: "重庆", 51: "四川", 52: "贵州", 53: "云南",
        54: "西藏", 61: "陕西", 62: "甘肃", 63: "青海", 64: "宁夏",
        65: "新疆", 71: "台湾", 81: "香港", 82: "澳门", 91: "国外"
    };

    // 身份证号码格式正则表达式
    const idCardRegex = /(^\d{15}$)|(^\d{17}(\d|X)$)/;

    // 判断格式是否正确
    if (!idCardRegex.test(idCard)) {
        return false;
    }

    // 判断省份代码是否有效
    if (!provinces[parseInt(idCard.substr(0, 2))]) {
        return false;
    }

    // 15位身份证不需要校验码，直接通过
    if (idCard.length === 15) {
        return true;
    }

    // 加权因子
    const weights = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

    // 校验码
    const checkCodes = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

    // 计算校验码
    let sum = 0;
    for (let i = 0; i < 17; i++) {
        sum += idCard[i] * weights[i];
    }
    const checkCode = checkCodes[sum % 11];

    // 比较计算出的校验码和身份证最后一位
    return checkCode === idCard[17];
}
// 传入 JSON 数据，导出为 Excel
export function exportJSONToExcel(sheetsData, fileName = "data.xlsx") {
    const workbook = XLSX.utils.book_new();

    sheetsData.forEach(({ sheetName, data, headers,summaryData }) => {
        const fields = Object.keys(headers);

        // 先将数据和header合并，并生成 Excel 工作表
        const dataWithHeaders = [
            headers,
            ...data.map(item => {
                const row = {};
                fields.forEach(field => {
                    row[field] = item[field];
                });
                return row;
            }),
        ];

        const worksheet = XLSX.utils.json_to_sheet(dataWithHeaders, { skipHeader: true });
        // 如果 summaryData 存在，将它插入到指定单元格
        if (summaryData) {
            const {
                currentWin, currentWinValue,
                playCountLabel, playCount,
                flowLabel, flow,
                reasonLabel, reason
            } = summaryData;
            // 将 summaryData 插入到 Excel 工作表中的指定单元格
            if (currentWin !== undefined) worksheet["J1"] = { t: "s", v: currentWin };
            if (currentWinValue !== undefined) worksheet["K1"] = { t: "n", v: currentWinValue };
            if (playCountLabel !== undefined) worksheet["J3"] = { t: "s", v: playCountLabel };
            if (playCount !== undefined) worksheet["K3"] = { t: "n", v: playCount };
            if (flowLabel !== undefined) worksheet["M1"] = { t: "s", v: flowLabel };
            console.warn({ t: "s", v: flow })
             worksheet["N1"] = { t: "s", v: flow };
            if (reasonLabel !== undefined) worksheet["J5"] = { t: "s", v: reasonLabel };
            if (reason !== undefined) worksheet["K5"] = { t: "s", v: reason };
        }
        worksheet['!ref'] = "A1:N100000"

        // 将生成的工作表添加到工作簿
        XLSX.utils.book_append_sheet(workbook, worksheet, sheetName || "Sheet");
    });

    // 导出 Excel 文件
    XLSX.writeFile(workbook, fileName);
}// 示例用法

