<script setup>
import {onMounted, ref, watch} from "vue";
import {generateLicensePlates, Parking} from "@/utils/parking";
import {gsap} from "gsap";
import moment from "moment";
import {MotionPathPlugin} from "gsap/MotionPathPlugin";
import SettingBorad from "@/components/SettingBorad.vue";
import {generateRandomString, scoketUrl} from "@/utils/request";

gsap.registerPlugin(MotionPathPlugin);
const setProperty = () => {
  document.documentElement.style.setProperty('--fontSize', option.value.fontSize + 'px');
  document.documentElement.style.setProperty('--itemWidth', option.value.itemWidth + 'px');
  document.documentElement.style.setProperty('--itemHeight', option.value.itemHeight + 'px');
  document.documentElement.style.setProperty('--spaceY', option.value.spaceY + 'px');
  document.documentElement.style.setProperty('--spaceX', option.value.spaceX + 'px');
  document.documentElement.style.setProperty('--paddingX', option.value.paddingX + 'px');
  document.documentElement.style.setProperty('--paddingY', option.value.paddingY + 'px');
  document.documentElement.style.setProperty('--entryTitleFontSize', option.value.entryTitleFontSize + 'px');
  document.documentElement.style.setProperty('--appTitleFontSize', option.value.appTitleFontSize + 'px');
  document.documentElement.style.setProperty('--contentLeft', option.value.contentLeft + 'px');
}
const changeOption = () => {
  uni.setStorage({
    key: 'option',
    data: option.value
  })
  setProperty()
}
const option = ref({
  showNetworkStatus: false,
  appTitle: '城际珠海站网约车上客区',
  stayTimeLimit: 10,
  appTitleFontSize: 100,
  entryTitleFontSize: 100,
  itemWidth: 400,
  itemHeight: 120,
  spaceX: 18,
  spaceY: 30,
  paddingX: 15,
  paddingY: 5,
  fontSize: 74,
  endNumberCount:3,
  contentLeft:300,
})
const scoketLive = ref('未连接')
const cars = ref([])
const currentTime = ref()
const carDetailDialogRef = ref()
const hideCarDetail = () => {
  carDetailDialogRef.value.hideDialog()
}
const showCarDetial = (carItem) => {
  let carItemNode = document.querySelector('div[plate=' + carItem.plate + ']')
  carDetailDialogRef.value.showDialog(carItem, carItemNode.offsetLeft + carItemNode.offsetWidth + 10, carItemNode.offsetTop + carItemNode.offsetHeight)
}
const moveCount = ref(0)
const checkIsOverTime = () => {
  let carItems = document.querySelectorAll('.car-item');

  const timeLimit = Number(option.value.stayTimeLimit) * 60
  const outCarsArray = []
  carItems.forEach(car => {
    if ((Math.abs(Number(car.getAttribute('entryTime')) - moment().unix())) >= timeLimit) {
      outCarsArray.push({
        plate: car.getAttribute('plate')
      })
    }
  })
  carOut(outCarsArray)
}
const itemMove = () => {
  if (keyadown.value == true && moveCount.value === 1) {
    return
  }
  moveCount.value = 1
  const areaItem = document.querySelector('.area-item');
  if (!areaItem) return;
  const areaContainerItem = document.querySelector('.arcea-container');
  let itemWidth = Number(option.value.itemWidth) + Number((Number(option.value.paddingX) * 2)) + Number(option.value.spaceX); // 单个item的宽度，包括间距
  let itemHeight = Number(option.value.itemHeight) + (Number(option.value.paddingY) * 2); // 单个item的高度，包括间距
  // 每行可放置的item数量
  let preLineItemNumber = Math.floor(areaItem.clientWidth / itemWidth);
  let carItems = document.querySelectorAll('.car-item');
  let lineOption = []
  let lineOption2 = {}
  entryRegions.value.forEach((region, index) => {
    lineOption2[region.id] = {
      lineNumber: 0,
      colNumber: 0,
      entryId: region.id,
    }
    lineOption.push({
      lineNumber: 0,
      colNumber: 0,
      entryId: region.id,
    })
  })

  carItems.forEach((item, index) => {
    let entryid = item.getAttribute('entryid')
    if (item.className.indexOf('remove') != -1) {
      item.remove()
    } else {
      let posistion = {
        top: Number(lineOption2[entryid].lineNumber) * ((Number(itemHeight) + Number(option.value.spaceY) * 2) + 1),
        left: Number(lineOption2[entryid].colNumber) * Number((Number(itemWidth)) + 2),
        opacity: 1
      }
      gsap.to(item, posistion)
      lineOption2[item.getAttribute('entryid')].colNumber++;
      if (lineOption2[item.getAttribute('entryid')].colNumber >= preLineItemNumber) {
        lineOption2[item.getAttribute('entryid')].colNumber = 0;
        lineOption2[item.getAttribute('entryid')].lineNumber++;
      }


    }

  });
};
const keyadown = ref(false)
const itemRemove = async () => {

  const areaItem = document.querySelector('.area-item');
  if (!areaItem) return
  let itemWidth = 100 + 15 + 7.5; // 单个item的宽度，包括间距
  let itemHeight = 20 + 15; // 单个item的高度，包括间距
  // 每行可放置的item数量
  let preLineItemNumber = Math.floor(areaItem.clientWidth / itemWidth);
  let carItems = document.querySelectorAll('.car-item');
  let lineNumber = 1;
  let colNumber = 1;
  carItems.forEach((item, index) => {
    const entryid = item.getAttribute('entryid');
    if (item.className.indexOf('remove') != -1) {
      item.remove()
    } else {
      gsap.to(item, {
        top: lineNumber * itemHeight,

        left: colNumber * itemWidth,
      });
      colNumber++;
      if (colNumber >= preLineItemNumber) {
        colNumber = 0;
        lineNumber++;
      }
    }

  });
};
const updateCarItemColor = () => {

  currentTime.value = moment().format('YYYY-MM-DD HH:mm:ss');
  let level1 = 10 //分钟
  let level2 = 15//分钟
  gsap.utils.toArray('.car-item').forEach((item, index) => {
    var a = moment.unix(item.getAttribute('entryTime'), 'YYYY-MM-DD HH:mm:ss');
    var b = moment(currentTime.value, 'YYYY-MM-DD HH:mm:ss');
    let timeDiffMinutes = Math.abs(a.diff(b, 'minutes'))
    let backgroundColor = '';
    if (timeDiffMinutes > level2) {
      backgroundColor = '';
    } else if (timeDiffMinutes > level1) {
      backgroundColor = '';
    } else {
      // backgroundColor = 'green';
    }
    if (backgroundColor) {
      // gsap.to(item, {backgroundColor, duration: 1, borderColor: backgroundColor});
      gsap.to(item, {backgroundColor, duration: 1});
    }
  })

}
const interval = ref()
const entryRegions = ref([])
const entryCars = ref([])
const socketTask = ref()
const resizeContainer = () => {
  itemMove()
}
const clickTime = ref(1)
const parkingCars = ref([])
const settingBoradRef = ref()
const getParkingCars = async () => {
  const response = await Parking.index()
  parkingCars.value = response.data.data
}
const scoketRelinkTimer = ref()
const sendSocketMessage = (msg) => {
  uni.sendSocketMessage({
    data: msg
  })
}
const connectWebSocket = () => {
  socketTask.value = uni.connectSocket({
    // url: 'ws://localhost:8080', // 替换为你的 WebSocket 服务器地址
    url: scoketUrl + '?' + generateRandomString('screen'), // 替换为你的 WebSocket 服务器地址
    method: 'GET',
    success: () => {

    },
    fail: (err) => {
      scoketRelinkTimer.value = setTimeout(() => {
        connectWebSocket()
        console.log('connectWebSocket()')
      }, 1500)
    }
  });

  // 监听 WebSocket 打开事件
  socketTask.value.onOpen(() => {
    clearInterval(scoketRelinkTimer.value)
    console.log('WebSocket 连接成功')
    scoketLive.value = '连接中'
  });

  // 监听 WebSocket 收到消息事件
  socketTask.value.onMessage(async (res) => {
    let scoketData = JSON.parse(res.data)
    if (scoketData.type === 'update') {
      let carsStatus = scoketData.data
      handleNewMessage(carsStatus)
    } else if (scoketData.type === 'clear') {
      //获取所有item
      clearAllCar()
    }


  })

  // 监听 WebSocket 错误事件
  socketTask.value.onError((err) => {
  });

  // 监听 WebSocket 关闭事件
  socketTask.value.onClose(() => {
    connectWebSocket()
    scoketLive.value = '连接失败，正在尝试重连'
  });
}
const handleNewMessage = (carsStatus) => {
  let inCars = carsStatus.filter((item, index) => {
    if (item.status == 1) {
      return true
    } else {
      return false
    }
  })
  let outCars = carsStatus.filter((item, index) => {
    if (item.status === 2) {
      return true
    } else {
      return false
    }
  })
  carIn(inCars)
  carOut(outCars)
}
const clearAllCar = () => {
  let cars = document.querySelectorAll('.car-item')
  let outCarsArray = []
  cars.forEach(item=>{
    outCarsArray.push({
      plate:item.getAttribute('plate')
    })
  })
  carOut(outCarsArray)
}
const carIn = ((carItems) => {
  if (carItems.length === 0) {
    return false
  }

  carItems.forEach((car, index) => {
    if(document.querySelectorAll('.car-item[plate='+car.plate+']').length==0){
      let carItem
      if (document.querySelectorAll('.car-item[entryId="' + car.entry_id + '"]').length > 0) {
        carItem = document.querySelector('.car-item').cloneNode(true);
      } else {
        carItem = document.createElement('div')
      }
      carItem.className = 'car-item new';
      carItem.style = '    width: var(--itemWidth);height: var(--itemHeight);line-height: var(--itemHeight);top: 0;position: absolute;padding: var(--paddingY) var(--paddingX);text-align: left;border-radius: 12px;flex: auto;font-size: var(--fontSize);text-shadow: -1px -1px 0 #393939, 1px -1px 0 #393939, -1px 1px 0 #393939, 1px 1px 0 #393939;color:white;font-weight:blod'
      carItem.setAttribute('entryTime', car.timestamp);
      carItem.setAttribute('plate', car.plate);
      carItem.setAttribute('entryid', car.entry_id);
      carItem.textContent = car.plate;
      // carItem.addEventListener('mouseenter', () => showCarDetial(car));
      // carItem.addEventListener('mouseleave', hideCarDetail);
      carItem.addEventListener('click', () => carOut([car]));
      document.querySelectorAll('.car-container[entryid="' + car.entry_id + '"]')[0].appendChild(carItem);
      gsap.from(carItem, {
        opacity: 0,
      })
    }

    // sendSocketMessage(JSON.stringify(carItems))
  })


})
const carOutCount = ref(0)
watch(() => carOutCount.value, () => {
  if (carOutCount.value >= 3) {

  } else {

  }
})
watch(() => option.value, () => {
  itemRemove()
})


const showSetting = () => {
  if (settingBoradRef.value.getDialogVisable() == false) {
    settingBoradRef.value.showDialog(option.value)
  } else {
    settingBoradRef.value.hideDialog()
  }

}
const carOut = ((carItems) => {
  carOutCount.value++
  carItems.forEach(car => {
    //查找后三位相同的车牌
    let lastThreeDigits = car.plate.slice(-option.value.endNumberCount);
    // let carItem = document.querySelector('.car-item[plate=' + car.plate + ']')
    document.querySelectorAll('.car-item').forEach(carItem=>{
      if(lastThreeDigits===carItem.getAttribute('plate').slice(-option.value.endNumberCount)){
        gsap.to(carItem, {
          opacity: 0,
          transform: "scale(0.001)",
        })
        carItem.className = "car-item remove"
      }

    })

  })
  sendSocketMessage(JSON.stringify(carItems))

})
const mockCar = (entry_id)=>{
  let cars = [
    {plate:"粤CD8Q263",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤A7B123",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤B6C456",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤D9E789",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤E4F012",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤F3G345",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤G2H678",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤H1J901",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤J8K234",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤K7L567",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤L6M890",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤M5N123",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤N4O456",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤O3P789",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤P2Q012",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤Q1R345",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤R9S678",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤S8T901",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤T7U234",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤U6V567",time:"1725327232",camId:98,entry_id:entry_id},
    {plate:"粤V5W890",time:"1725327232",camId:98,entry_id:entry_id}
  ]
  // cars = JSON.parse(cars)
  console.log(cars)
  carIn(cars)

}
const carClick = ()=>{
  console.log('3')
}
onMounted(async () => {


  document.addEventListener('keydown', e => {
    if (e.key === 'b') {
      itemMove()
    }
    if (e.key === 'a') {

      let newCar = generateLicensePlates(1)
      handleNewMessage(newCar)

    }
    if (e.key === 's') {
      showSetting()
    }
    if (e.key === '+') {
      currentTime.value = moment(currentTime.value).add(1, 'minutes').format('YYYY-MM-DD HH:mm:ss')
    }
    if (e.code === 'Space') {
      socketTask.value = uni.closeSocket()
      return
      const item = document.querySelector('.car-item')
      if (clickTime.value === 1) {
        gsap.to(item, {
          rotate: 90,
          duration: 1,
          ease: 'power1.inOut'
        })
        clickTime.value = clickTime.value + 1
      } else {
        gsap.to(item, {
          motionPath: {
            path: "#path",
            align: "#path",
            autoRotate: false,
            alignOrigin: [0.5, 0.5],
          },
          duration: 2
        })
        clickTime.value = 1
      }
    }
    if (e.key === '-') {
      currentTime.value = moment(currentTime.value).add(-1, 'minutes').format('YYYY-MM-DD HH:mm:ss')

    }
  })

  const storageOption = uni.getStorageSync('option')
  if (storageOption) {
    option.value = storageOption
  }
  setProperty()
  await getParkingCars().catch(() => {
    setTimeout(() => {
      window.location.reload()
    }, 1500)
  })
  const response = await Parking.request('entry', 'GET', {}).catch(() => {
    setTimeout(() => {
      window.location.reload()
    }, 1500)
  })
  entryRegions.value = response.data.data
  entryRegions.value.forEach((entryItem, index) => {
    entryRegions.value[index].list = []
    parkingCars.value.forEach(carItem => {
      if (carItem.entry_id == entryItem.id) {
        entryRegions.value[index].list.push(carItem)
      }
    })
  })

  entryCars.value = entryRegions.value
  currentTime.value = moment().format('YYYY-MM-DD HH:mm:ss');
  connectWebSocket()
  interval.value = setInterval(() => {
    itemMove()
    checkIsOverTime()
    updateCarItemColor()
    document.querySelector('.middle-line').style.top = (document.querySelector('.app-title').clientHeight+document.querySelector('.area-item').clientHeight)+'px'
    document.querySelector('.middle-line').style.height = 'calc(100vh - '+(document.querySelector('.app-title').clientHeight+document.querySelector('.area-item').clientHeight)+'px'+')'
    document.querySelector('.middle-line').style.left = document.querySelector('.area-item').clientWidth+'px'
  }, 1000)
  window.addEventListener('resize', () => {
    resizeContainer()
  })
})
</script>

<template>
  <div style="width: 100vw;height: 100vh;background-color: #414e9c ">
    <div class="middle-line"></div>
    <div class="status-container" v-show="option.showNetworkStatus">
      连接状态:
      <span style="color: green">{{ scoketLive }}</span>
    </div>
<!--    @click="clearAllCar"-->
    <h1 class="app-title" style="color:white;text-align: center;padding: 30px 0;font-size: var(--appTitleFontSize)" @click="showSetting">{{ option.appTitle }}</h1>
    <div class="area-container">
      <div class="area-item"  v-for="entryRegion in entryRegions" :entryid="entryRegion.id">
        <div class="name" @click="mockCar(entryRegion.id)"   style="font-size: var(--entryTitleFontSize)">{{ entryRegion.name }}</div>
        <div class="area-container">
          <div class="car-container" :entryid="entryRegion.id">
            <div  class="car-item" v-for="(car,index) in entryRegion.list" :entryTime="car.timestamp" :key="index" style="font-weight: bold"
                 :entryid="entryRegion.id"
                 :plate="car.plate">
              {{ car.plate }}
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
  <SettingBorad ref="settingBoradRef" @change="changeOption"></SettingBorad>
  <!--  M106.27802829143366 25.112108725071096C124.51420128541152 60.538118221124165 144.99252871867975 184.75336887216582 215.69506625530084 237.66816570138946C286.39760379192194 290.5829625306131 478.02688896851697 325.1121023672423 530.4932535111602 342.6008897004129-->
  <path style="    position: fixed;
    left: 50px;
    top: 0;
    transform: rotate(90deg);" id="path" d="M0 100s269.931 86.612 520 0c250.069-86.612 480 0 480 0" stroke="black"
        stroke-width="1"/>
  <!--  <CarDetailDialog ref="carDetailDialogRef"/>-->
</template>

<style lang="scss">


.area-container {
  padding-bottom: 5px;
  display: flex;

}

.area-item {
  width: 50%;
}

.middle-line{
  width: 10px;
  height: 100vh;
  position: fixed;
  border-right: 15px dashed white;
}
.area-item .name {
  //background-color: rgba(0, 128, 0, 0.32);
  //border: 2px solid rgba(0, 128, 0, 0.48);
  color:#FFEB3B;
  font-weight: bold;
  flex: 1;
  padding: 15px;
  font-size: 30px;
  text-align: center;
  margin-bottom: var(--spaceY);
}

.car-container {
  padding: 7.5px 5px;
  position: relative;
  left:var(--contentLeft);
}

.car-item.new {
  //transform: translateX(50px);
  //opacity: 0;
}

.car-item {
  width: var(--itemWidth);
  height: var(--itemHeight);
  line-height: var(--itemHeight);
  left: 0;
  top: 0;
  position: absolute;
  padding: var(--paddingY) var(--paddingX);
  //margin: var(--spaceY) var(--spaceX);
  //border: 1px solid green;
  text-align: center;
  border-radius: 12px;
  flex: auto;
  font-size: var(--fontSize);
  text-shadow: -1px -1px 0 #393939,
  1px -1px 0 #393939,
  -1px 1px 0 #393939,
  1px 1px 0 #393939;
  font-weight: bold;
}

.status-container {
  position: fixed;
  right: 15px;
  color:white;
}

</style>
<style>
:root {
  --fontSize: 20px;
  --itemHeight: 20px;
  --itemWidth: 20px;
  --spaceX: 20px;
  --spaceY: 20px;
  --paddingX: 20px;
  --paddingY: 20px;
  --appTitleFontSize: 30px;
  --entryTitleFontSize: 24px;
  --contentLeft: 300px;
}

body {
  background-color: rgb(20, 22, 35);

  font-size: 16px;
}

</style>
