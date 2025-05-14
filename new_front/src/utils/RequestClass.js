import {baseUrl, request} from "./request";

export default class RequestClass {
    constructor(baseUrl) {
        this.baseUrl = baseUrl
    }
    index(parameter = []) {
        return request('GET', this.baseUrl + '/index', parameter)
    }

    show(id) {
        return request('GET', this.baseUrl + '/show?id=' + id)
    }

    store(parameter) {
        return request('POST', this.baseUrl + '/store', parameter)
    }

    update(id, parameter) {
        return request('PUT', this.baseUrl + '/update?id=' + id, parameter)
    }

    drop(id) {
        return request('DELETE', this.baseUrl + '/destroy?id=' + id)
    }
    request(action,method='GET',parameter={}){
        return request(method, this.baseUrl + '/'+action,parameter)
    }

    async uplaod(action = 'upload', parameter = {}) {
        const chooseImageRes = await uni.chooseImage({})
        const tempFilePaths = chooseImageRes.tempFilePaths;
        const response  = await uni.uploadFile({
            header: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            url: baseUrl + 'index.php/wechat/' + this.baseUrl + '/' + action, //仅为示例，非真实的接口地址
            filePath: tempFilePaths[0],
            name: 'file',
            formData: parameter,
        })
        return JSON.parse(response.data)
    }
}