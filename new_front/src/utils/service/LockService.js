const lockplugin = requirePlugin('myPlugin')

export async function setLockTime(lockDevice) {
    const {lockData, lockId, lockAlias} = lockDevice
    uni.showLoading({
        title: '正在校对···',
        icon: 'loading',
        duration: 10000
    })
    const response = await lockplugin.setLockTime(new Date().getTime(), lockData,)
    uni.hideLoading()
    if (response.errorCode === 0) {
        uni.showToast({
            icon: 'success',
            title: response.errorMsg
        })
    } else {
        uni.showToast({
            icon: 'none',
            title: response.errorMsg
        })

    }

}

export function openLockByKey(lockDevice,callback) {
    const {lockData, lockId, lockAlias} = lockDevice
    uni.showLoading({
        title: '正在打开' + lockAlias + '···',
        icon: 'loading',
        duration: 10000
    })

    lockplugin.controlLock(3, lockData, response => {
        uni.hideLoading()
        callback()
        if (response.errorCode == 0) {
            uni.showToast({
                icon: 'success',
                title: '开锁成功'
            })
            // this.uploadRecord(lockId, lockData)
        } else {
            uni.showToast({
                icon: 'none',
                title: response.errorMsg
            })
        }
    })
}
