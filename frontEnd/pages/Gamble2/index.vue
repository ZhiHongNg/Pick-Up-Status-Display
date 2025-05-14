<script setup>
import {
  onMounted,
  ref
} from 'vue'

//投注等级
const levelData = ref([{
  levelName: '1A',
  levelCost: 20
}, {
  levelName: '1B',
  levelCost: 20
}, {
  levelName: '1C',
  levelCost: 20
}, {
  levelName: '1D',
  levelCost: 20
}, {
  levelName: '1E',
  levelCost: 20
},
  {
    levelName: '2A',
    levelCost: 25
  }, {
    levelName: '2B',
    levelCost: 25
  }, {
    levelName: '2C',
    levelCost: 25
  }, {
    levelName: '2D',
    levelCost: 25
  }, {
    levelName: '2E',
    levelCost: 25
  },
  {
    levelName: '3A',
    levelCost: 30
  }, {
    levelName: '3B',
    levelCost: 30
  }, {
    levelName: '3C',
    levelCost: 30
  }, {
    levelName: '3D',
    levelCost: 30
  }, {
    levelName: '3E',
    levelCost: 30
  },
  {
    levelName: '4A',
    levelCost: 35
  }, {
    levelName: '4B',
    levelCost: 35
  }, {
    levelName: '4C',
    levelCost: 35
  }, {
    levelName: '4D',
    levelCost: 35
  }, {
    levelName: '4E',
    levelCost: 35
  },
  {
    levelName: '5A',
    levelCost: 40
  }, {
    levelName: '5B',
    levelCost: 40
  }, {
    levelName: '5C',
    levelCost: 40
  }, {
    levelName: '5D',
    levelCost: 40
  }, {
    levelName: '5E',
    levelCost: 40
  },
  {
    levelName: '6A',
    levelCost: 45
  }, {
    levelName: '6B',
    levelCost: 45
  }, {
    levelName: '6C',
    levelCost: 45
  }, {
    levelName: '6D',
    levelCost: 45
  }, {
    levelName: '6E',
    levelCost: 45
  },
  {
    levelName: '7A',
    levelCost: 50
  }, {
    levelName: '7B',
    levelCost: 50
  }, {
    levelName: '7C',
    levelCost: 50
  }, {
    levelName: '7D',
    levelCost: 50
  }, {
    levelName: '7E',
    levelCost: 50
  },
  {
    levelName: '8A',
    levelCost: 55
  }, {
    levelName: '8B',
    levelCost: 55
  }, {
    levelName: '8C',
    levelCost: 55
  }, {
    levelName: '8D',
    levelCost: 55
  }, {
    levelName: '8E',
    levelCost: 55
  },
  {
    levelName: '9A',
    levelCost: 60
  }, {
    levelName: '9B',
    levelCost: 60
  }, {
    levelName: '9C',
    levelCost: 60
  }, {
    levelName: '9D',
    levelCost: 60
  }, {
    levelName: '9E',
    levelCost: 60
  },
  {
    levelName: '10A',
    levelCost: 65
  }, {
    levelName: '10B',
    levelCost: 65
  }, {
    levelName: '10C',
    levelCost: 65
  }, {
    levelName: '10D',
    levelCost: 65
  }, {
    levelName: '10E',
    levelCost: 65
  },
  {
    levelName: '11A',
    levelCost: 70
  }, {
    levelName: '11B',
    levelCost: 70
  }, {
    levelName: '11C',
    levelCost: 70
  }, {
    levelName: '11D',
    levelCost: 70
  }, {
    levelName: '11E',
    levelCost: 70
  },
  {
    levelName: '12A',
    levelCost: 75
  }, {
    levelName: '12B',
    levelCost: 75
  }, {
    levelName: '12C',
    levelCost: 75
  }, {
    levelName: '12D',
    levelCost: 75
  }, {
    levelName: '12E',
    levelCost: 75
  },
  {
    levelName: '13A',
    levelCost: 80
  }, {
    levelName: '13B',
    levelCost: 80
  }, {
    levelName: '13C',
    levelCost: 80
  }, {
    levelName: '13D',
    levelCost: 80
  }, {
    levelName: '13E',
    levelCost: 80
  },
  {
    levelName: '14A',
    levelCost: 85
  }, {
    levelName: '14B',
    levelCost: 85
  }, {
    levelName: '14C',
    levelCost: 85
  }, {
    levelName: '14D',
    levelCost: 85
  }, {
    levelName: '14E',
    levelCost: 85
  },
  {
    levelName: '15A',
    levelCost: 90
  }, {
    levelName: '15B',
    levelCost: 90
  }, {
    levelName: '15C',
    levelCost: 90
  }, {
    levelName: '15D',
    levelCost: 90
  }, {
    levelName: '15E',
    levelCost: 90
  },
  {
    levelName: '16A',
    levelCost: 95
  }, {
    levelName: '16B',
    levelCost: 95
  }, {
    levelName: '16C',
    levelCost: 95
  }, {
    levelName: '16D',
    levelCost: 95
  }, {
    levelName: '16E',
    levelCost: 95
  },
  {
    levelName: '17A',
    levelCost: 100
  }, {
    levelName: '17B',
    levelCost: 100
  }, {
    levelName: '17C',
    levelCost: 100
  }, {
    levelName: '17D',
    levelCost: 100
  }, {
    levelName: '17E',
    levelCost: 100
  },
  {
    levelName: '18A',
    levelCost: 110
  }, {
    levelName: '18B',
    levelCost: 110
  }, {
    levelName: '18C',
    levelCost: 110
  }, {
    levelName: '18D',
    levelCost: 110
  }, {
    levelName: '18E',
    levelCost: 110
  },
  {
    levelName: '19A',
    levelCost: 120
  }, {
    levelName: '19B',
    levelCost: 120
  }, {
    levelName: '19C',
    levelCost: 120
  }, {
    levelName: '19D',
    levelCost: 120
  }, {
    levelName: '19E',
    levelCost: 120
  },
  {
    levelName: '20A',
    levelCost: 130
  }, {
    levelName: '20B',
    levelCost: 130
  }, {
    levelName: '20C',
    levelCost: 130
  }, {
    levelName: '20D',
    levelCost: 130
  }, {
    levelName: '20E',
    levelCost: 130
  },
  {
    levelName: '21A',
    levelCost: 140
  }, {
    levelName: '21B',
    levelCost: 140
  }, {
    levelName: '21C',
    levelCost: 140
  }, {
    levelName: '21D',
    levelCost: 140
  }, {
    levelName: '21E',
    levelCost: 140
  },
  {
    levelName: '22A',
    levelCost: 150
  }, {
    levelName: '22B',
    levelCost: 150
  }, {
    levelName: '22C',
    levelCost: 150
  }, {
    levelName: '22D',
    levelCost: 150
  }, {
    levelName: '22E',
    levelCost: 150
  },
  {
    levelName: '23A',
    levelCost: 160
  }, {
    levelName: '23B',
    levelCost: 160
  }, {
    levelName: '23C',
    levelCost: 160
  }, {
    levelName: '23D',
    levelCost: 160
  }, {
    levelName: '23E',
    levelCost: 160
  },
  {
    levelName: '24A',
    levelCost: 170
  }, {
    levelName: '24B',
    levelCost: 170
  }, {
    levelName: '24C',
    levelCost: 170
  }, {
    levelName: '24D',
    levelCost: 170
  }, {
    levelName: '24E',
    levelCost: 170
  },
  {
    levelName: '25A',
    levelCost: 180
  }, {
    levelName: '25B',
    levelCost: 180
  }, {
    levelName: '25C',
    levelCost: 180
  }, {
    levelName: '25D',
    levelCost: 180
  }, {
    levelName: '25E',
    levelCost: 180
  },
  {
    levelName: '26A',
    levelCost: 190
  }, {
    levelName: '26B',
    levelCost: 190
  }, {
    levelName: '26C',
    levelCost: 190
  }, {
    levelName: '26D',
    levelCost: 190
  }, {
    levelName: '26E',
    levelCost: 190
  },
  {
    levelName: '27A',
    levelCost: 200
  }, {
    levelName: '27B',
    levelCost: 200
  }, {
    levelName: '27C',
    levelCost: 200
  }, {
    levelName: '27D',
    levelCost: 200
  }, {
    levelName: '27E',
    levelCost: 200
  }
]);
const currentLevelData = ref(levelData.value[0])
const currentLevelIndex = ref(-1)
const currentGambleRecords = ref([])
const runSpeed = ref(600)
const clear = () => {
  statisticsRecords.value = []
  localStorage.setItem('totalGambleRecords', JSON.stringify(statisticsRecords.value))
}
const getRandomOne = () => {
  const numbers = [1, 2, 3];
  return numbers[Math.floor(Math.random() * numbers.length)];
}
const currentGambleNumber = ref(1)
const totalMoney = ref(10000)
const startMoney = ref(10000)
const showLastCount = ref(50)
const makeRecord = (times, levelName, levelCost, buyDetail, openDetail, result, moneyGet, momeyRemain) => {
  currentGambleRecords.value.unshift({
    times,
    levelName,
    levelCost,
    result,
    buyDetail,
    openDetail,
    moneyGet,
    momeyRemain,
    levelUp: 0
  })

  // currentGambleRecords.value = getFirstElements(currentGambleRecords.value,showLastCount.value)
}

const winCount = ref(0) //累计赢的次数
const loseCount = ref(0) //累计输或和的次数
//获得当局下注详情
const getBuyDetail = () => {
  if (currentBuy.value === 1) {
    return '庄'
  }
  if (currentBuy.value === 3) {
    return '闲'
  }
}
//获得当局开局结果详情
const getOpenDetail = (open) => {
  if (open === 1) {
    return '庄'
  }

  if (open === 2) {
    return '和'

  }
  if (open === 3) {
    return '闲'

  }
}
//获得数组前x个的内容，主要防止数组过长

// const getFirstElements = (array, count) => {
//   return array.slice(0, count);
// }
//赢局
const win = (open) => {
  const moneyGet = levelData.value[currentLevelIndex.value].levelCost + levelData.value[currentLevelIndex.value]
      .levelCost * 0.95
  const momeyRemain = totalMoney.value + (moneyGet)
  totalMoney.value = momeyRemain
  makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].levelName, levelData.value[
          currentLevelIndex.value].levelCost, getBuyDetail(), getOpenDetail(open), '赢', moneyGet,
      momeyRemain)
  winCount.value++
  loseCount.value = 0
}
//和局
const tie = (open) => {
  const moneyGet = 0
  const momeyRemain = totalMoney.value + (moneyGet)
  totalMoney.value = momeyRemain
  makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].levelName, levelData.value[
          currentLevelIndex.value].levelCost, getBuyDetail(), getOpenDetail(open), '和', moneyGet,
      momeyRemain)
  loseCount.value++
  winCount.value = 0
}
//输局

const lose = (open) => {
  const moneyGet = -levelData.value[currentLevelIndex.value].levelCost
  const momeyRemain = totalMoney.value + (moneyGet)
  totalMoney.value = momeyRemain
  makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].levelName, levelData.value[
          currentLevelIndex.value].levelCost, getBuyDetail(), getOpenDetail(open), '输', moneyGet,
      momeyRemain)
  loseCount.value++
  winCount.value = 0
}

//开始游戏
const gameStart = async () => {
  open.value = await getRandomOne()
  console.log("开：" + getOpenDetail(open.value))
  // 记录赢的记录
  if (openLog.value.length === 0) {
    openLog.value.push(open.value)
  } else if (openLog.value[0] !== open.value) {
    openLog.value = [open.value];
  } else {
    openLog.value.push(open.value);
  }

  if (currentBuy.value === open.value) {
    await win(open.value)
  } else if (open.value === 2) {
    await tie(open.value)
  } else {
    await lose(open.value)
  }

  currentGambleNumber.value++
  await checkUpgradeLevel()
}
//检查是否提升投注等级
const checkUpgradeLevel = () => {
  if (loseCount.value === 3) {
    currentLevelIndex.value++
    currentGambleRecords.value[0].levelUp = 1
    loseCount.value = 0
    winCount.value = 0
    // if (currentBuy.value === 1) {
    // 	currentBuy.value = 3
    // } else {
    // 	currentBuy.value = 1
    // }
  }
}
//提升投注等级
const toNextLevel = () => {
  if (currentLevelIndex.value == -1) {
    currentLevelIndex.value = 0
  }
  //判断当前余额是否足够下注
  if (totalMoney.value >= levelData.value[currentLevelIndex.value].levelCost) {
    // gameStart()
    main()
    //判断当前（上局）是否处于最后一个投注等级
    if (currentLevelIndex.value === levelData.value.length) {
      currentLevelIndex.value = 0
    }
  } else {
    endGame() //结束当前局游戏

    //1.5秒后开始游戏
    setTimeout(() => {
      autoGameStart()
    }, 1500)


  }
}
const statisticsRecords = ref([]) //每局统计

//统计当前局的总流水，游玩局数
const statistics = () => {
  //计算流水总和
  const sumAmount = currentGambleRecords.value.flat().reduce((acc, item) => acc + item.levelCost, 0);
  statisticsRecords.value.push({
    totalPlay: currentGambleRecords.value.length, //游玩次数
    totalCost: sumAmount, //流水总和
  })
  localStorage.setItem('totalGambleRecords', JSON.stringify(statisticsRecords.value))
}
const gamebleIndex = ref(1) //第几大局
const autoRunning = ref(false) //是否自动运行

//结束当前大局，相关数值重置，统计当前大局的数据
const endGame = (markStatistics = true) => {
  clearInterval(doStartGame)
  // autoRunning.value = false //停止自动运行
  // if (markStatistics) {
  // 	statistics() //统计当前大局的数据
  // 	gamebleIndex.value++ //大局index+1
  // }
  currentGambleNumber.value = 0 //小局重置为0
  currentLevelIndex.value = -1 //投注等级重置
  currentGambleRecords.value = []

}
const stopAutoGame = () => {
  autoRunning.value = false
}
const contineuGame = () => {
  autoRunning.value = true
}

//自动运行
const autoGameStart = () => {
  totalMoney.value = startMoney.value
  currentGambleRecords.value = []
  autoRunning.value = true

}

const doStartGame = setInterval(async () => {
  if (autoRunning.value) {
    await toNextLevel()
  } else {

  }
}, 100)


onMounted(() => {
  // document.querySelector('body').addEventListener('keydown',e=>{
  //   autoGameStart()
  // })
  const oldData = localStorage.getItem('totalGambleRecords') //读取旧统计数据
  if (oldData) {
    statisticsRecords.value = JSON.parse(oldData)
  }
  //todo:需要给一个变量，后面clearInterval()


})


// 重新
const currentBuy = ref(1) //当前下注
const openLog = ref([])
const open = ref()
const main = async () => {
  console.log('开始下注')
  await doCurrentBuy() // 开始下注
  console.log('游戏开始')
  await gameStart() // 游戏开始
}

// 开始下注
const doCurrentBuy = async () => {
  if (open.value === 2) {
    // 出现和则切换下注
    currentBuy.value = currentBuy.value === 3 ? 1 : 3;
  } else if (openLog.value.length === 3) {
    currentBuy.value = openLog.value[0]
    openLog.value = []
  }

  return true
}

// 预防列表溢出
const getFirstElements = (array, count) => {
  return array.slice(0, count);
}
</script>

<template>
  <div class="gamble-container">
    <div class="current-gamble-container">
      <div class="record">
        <div>局数</div>
        <div>等级</div>
        <div>投注金额</div>
        <div>投注</div>
        <div>开</div>
        <div>结果</div>
        <div>输赢</div>
        <div>剩余金额</div>
        <div>是否升级</div>
      </div>
      <div class="record" v-for="record in getFirstElements(currentGambleRecords,showLastCount)">
        <div>{{ record.times }}</div>
        <div>{{ record.levelName }}</div>
        <div>{{ record.levelCost }}</div>
        <div>{{ record.buyDetail }}</div>
        <div>{{ record.openDetail }}</div>

        <div>
					<span :style="{ color: record.result === '赢' ? 'green' : record.result === '输' ? 'red' : '' }">
						{{ record.result }}
					</span>
        </div>
        <div>{{ record.moneyGet }}</div>
        <div>{{ record.momeyRemain }}</div>
        <div>

          <image v-if="record.levelUp" style="width: 10px;height: 10px"
                 src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTU1NS40NzEgNDQwLjQxOGMtMjQuMDE1LTIzLjY3NC02Mi45NTEtMjMuNjc0LTg2Ljk0MiAwTDMwLjEzNSA4NzIuMDA5Yy0yMy45OTEgMjMuNjc0LTIzLjk5MSA2Mi4wOTggMCA4NS43NzIgMjQuMDE1IDIzLjY5OSA2Mi45MjcgMjMuNjk5IDg2Ljk0MiAwTDUxMiA1NjkuMDI3bDM5NC45MjMgMzg4Ljc1NGMyNC4wMTUgMjMuNjk5IDYyLjkyNyAyMy42OTkgODYuOTQyIDAgMjQuMDE1LTIzLjY3NCAyNC4wMTUtNjIuMDk4IDAtODUuNzcyTDU1NS40NzEgNDQwLjQxOHoiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTAuNDY4YzNhODFDaUlSeVUiIGNsYXNzPSJzZWxlY3RlZCIgZmlsbD0iIzFhYWJhOCIvPjxwYXRoIGQ9Ik0xMTcuMDUzIDU4My41NThMNTEyIDE5NC44MDRsMzk0LjkyMyAzODguNzc4YzI0LjAxNSAyMy42OTkgNjIuOTI3IDIzLjY5OSA4Ni45NDIgMGE2MC4wOTIgNjAuMDkyIDAgMCAwIDAtODUuNzk2TDU1NS40NzEgNjYuMjE5Yy0yNC4wMTUtMjMuNjc0LTYyLjk1MS0yMy42NzQtODYuOTQyIDBMMzAuMTM1IDQ5Ny43ODZjLTIzLjk5MSAyMy42OTgtMjMuOTkxIDYyLjA5OCAwIDg1Ljc5NiAyNC4wMTUgMjMuNjc0IDYyLjkyNyAyMy42NzQgODYuOTE4LS4wMjR6IiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmkxLjQ2OGMzYTgxQ2lJUnlVIiBjbGFzcz0ic2VsZWN0ZWQiIGZpbGw9IiMxYWFiYTgiLz48L3N2Zz4=">
          </image>
          <span v-else>-</span>
        </div>
      </div>
    </div>
    <div class="all-gamble-result-container">
      <div class="record2">
        <div>总局数</div>
        <div>累计交易</div>
      </div>
      <div class="record2" v-for="statisticsRecord in statisticsRecords">
        <div>{{ statisticsRecord.totalPlay }}</div>
        <div>{{ statisticsRecord.totalCost }}</div>
      </div>

      <div class="record2" style="margin-top: 30px">
        <div>平均局数</div>
        <div>平均交易</div>
      </div>
      <div class="record2">
        <div>
          {{ Number(statisticsRecords.flat().reduce((acc, item) => acc + item.totalPlay, 0) / statisticsRecords.length) }}
        </div>
        <div>
          {{ Number(statisticsRecords.flat().reduce((acc, item) => acc + item.totalCost, 0) / statisticsRecords.length) }}
        </div>
      </div>
    </div>
    <div style="color: #2d8cf0" @click="clear()">清空</div>
  </div>
  <div class="level-container">
    <div v-if="currentLevelIndex>=0">
      <div>当前投注：</div>
      <div>等级：{{ levelData[currentLevelIndex].levelName }}</div>
      <div>投注额：{{ levelData[currentLevelIndex].levelCost }}</div>
    </div>
    <div v-if="currentLevelIndex===-1">
      <div>当前投注：</div>
      <div>等级：未开始</div>
      <div>投注额：未开始</div>
    </div>
    <div>
      <div class="level-table">
        <div>A</div>
        <div>B</div>
        <div>C</div>
        <div>D</div>
        <div>E</div>
        <div :class="{'active':index===currentLevelIndex}" v-for="(levelDetail,index) in levelData">
          {{ levelDetail.levelCost }}
        </div>
      </div>
    </div>
    <div class="setting-container">
      <div class="setting-item">
        <div class="name">初始金额</div>
        <div class="value"><input class="default-input" v-model.number="startMoney" type="text"></div>
      </div>
      <!--      <div class="setting-item">-->
      <!--        <div class="name">运行间隙（毫秒）</div>-->
      <!--        <div class="value"><input class="default-input" v-model="runSpeed" type="text"></div>-->
      <!--      </div>-->
      <div class="setting-item">
        <div class="name">结果条数</div>
        <div class="value"><input class="default-input" v-model="showLastCount" type="text"></div>
      </div>
    </div>
    <div class="default-button" style="width: 240px;margin: 25px auto" @click="autoGameStart"
         v-show="currentLevelIndex===-1&&autoRunning===false">
      <span>开始</span>
    </div>
    <div class="default-button" style="width: 240px;margin: 25px auto" @click="contineuGame"
         v-show="currentLevelIndex>=0&&autoRunning===false">
      <span>继续</span>
    </div>
    <div class="default-button" style="width: 240px;margin: 25px auto" @click="stopAutoGame"
         v-show="currentLevelIndex>=0&&autoRunning===true">
      <span>暂停</span>
    </div>
    <div class="default-button" style="width: 240px;margin: 25px auto" @click="endGame(false)"
         v-show="currentLevelIndex>=0">
      <span>重置</span>
    </div>

  </div>


</template>

<style>
html {
  background-color: #2c405a;
}
</style>
<style scoped lang="scss">
.gamble-container {
  position: fixed;
  left: 0;
  top: 0;
  width: 70vw;
  height: 100vh;
  background-color: #2c405a;
  padding: 15px;
  color: white;
  display: flex;
  justify-content: space-between;
}

.level-container {
  position: fixed;
  right: 0;
  top: 0;
  width: 25vw;
  height: 100vh;
  background-color: #2c405a;
  padding: 15px;
  color: white;
}

.level-table {
  height: 70vh;
  display: grid;
  grid: auto-flow / 1fr 1fr 1fr 1fr 1fr;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 12px;
}

.active {
  background-color: red;
}

.gamble-container {
  .record {
    text-align: center;
    display: grid;
    grid: auto-flow / 80px 80px 80px 80px 80px 80px 80px 80px 80px;
  }

  .record2 {
    text-align: left;
    display: grid;
    grid: auto-flow / 200px 200px;
    //border: 1px solid #ddd;
    margin-bottom: 5px;
  }
}

.setting-container {
  display: flex;
  justify-content: left;
  margin-top: 15px;

  .setting-item {
    width: 200px;

    .name {}

    .value {
      .default-input {
        width: 80%;
        color: #2c405a;
      }
    }
  }
}

.current-gamble-container {
  padding-right: 15px;
  border-right: 1px solid #dddddd;
}
</style>