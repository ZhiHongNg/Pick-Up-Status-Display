<script setup>
	import {
		onMounted,
		ref,
    reactive
	} from 'vue'
  import Record from "@/pages/Gamble/record.vue";
  import RcordDialog from "@/pages/Gamble/RcordDialog.vue";

  const rcordDialogRef = ref()
  const winCount = ref(0) //累计赢的次数
  const loseCount = ref(0) //累计输或和的次数
  const winGoAmount = ref(100) //赢走金额 ,赢够多少钱跳出这局
  const currentJuwin = ref(0)//当前局赢得金额
  const clearData = ()=>{
    localStorage.clear()
    window.location.reload()
  }
  const showRecord = (list)=>{
    rcordDialogRef.value.showDialog(list)
  }
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
		// {
		// 	levelName: '11A',
		// 	levelCost: 70
		// }, {
		// 	levelName: '11B',
		// 	levelCost: 70
		// }, {
		// 	levelName: '11C',
		// 	levelCost: 70
		// }, {
		// 	levelName: '11D',
		// 	levelCost: 70
		// }, {
		// 	levelName: '11E',
		// 	levelCost: 70
		// },
		// {
		// 	levelName: '12A',
		// 	levelCost: 75
		// }, {
		// 	levelName: '12B',
		// 	levelCost: 75
		// }, {
		// 	levelName: '12C',
		// 	levelCost: 75
		// }, {
		// 	levelName: '12D',
		// 	levelCost: 75
		// }, {
		// 	levelName: '12E',
		// 	levelCost: 75
		// },
		// {
		// 	levelName: '13A',
		// 	levelCost: 80
		// }, {
		// 	levelName: '13B',
		// 	levelCost: 80
		// }, {
		// 	levelName: '13C',
		// 	levelCost: 80
		// }, {
		// 	levelName: '13D',
		// 	levelCost: 80
		// }, {
		// 	levelName: '13E',
		// 	levelCost: 80
		// },
		// {
		// 	levelName: '14A',
		// 	levelCost: 85
		// }, {
		// 	levelName: '14B',
		// 	levelCost: 85
		// }, {
		// 	levelName: '14C',
		// 	levelCost: 85
		// }, {
		// 	levelName: '14D',
		// 	levelCost: 85
		// }, {
		// 	levelName: '14E',
		// 	levelCost: 85
		// },
		// {
		// 	levelName: '15A',
		// 	levelCost: 90
		// }, {
		// 	levelName: '15B',
		// 	levelCost: 90
		// }, {
		// 	levelName: '15C',
		// 	levelCost: 90
		// }, {
		// 	levelName: '15D',
		// 	levelCost: 90
		// }, {
		// 	levelName: '15E',
		// 	levelCost: 90
		// },
		// {
		// 	levelName: '16A',
		// 	levelCost: 95
		// }, {
		// 	levelName: '16B',
		// 	levelCost: 95
		// }, {
		// 	levelName: '16C',
		// 	levelCost: 95
		// }, {
		// 	levelName: '16D',
		// 	levelCost: 95
		// }, {
		// 	levelName: '16E',
		// 	levelCost: 95
		// },
		// {
		// 	levelName: '17A',
		// 	levelCost: 100
		// }, {
		// 	levelName: '17B',
		// 	levelCost: 100
		// }, {
		// 	levelName: '17C',
		// 	levelCost: 100
		// }, {
		// 	levelName: '17D',
		// 	levelCost: 100
		// }, {
		// 	levelName: '17E',
		// 	levelCost: 100
		// },
		// {
		// 	levelName: '18A',
		// 	levelCost: 110
		// }, {
		// 	levelName: '18B',
		// 	levelCost: 110
		// }, {
		// 	levelName: '18C',
		// 	levelCost: 110
		// }, {
		// 	levelName: '18D',
		// 	levelCost: 110
		// }, {
		// 	levelName: '18E',
		// 	levelCost: 110
		// },
		// {
		// 	levelName: '19A',
		// 	levelCost: 120
		// }, {
		// 	levelName: '19B',
		// 	levelCost: 120
		// }, {
		// 	levelName: '19C',
		// 	levelCost: 120
		// }, {
		// 	levelName: '19D',
		// 	levelCost: 120
		// }, {
		// 	levelName: '19E',
		// 	levelCost: 120
		// },
		// {
		// 	levelName: '20A',
		// 	levelCost: 130
		// }, {
		// 	levelName: '20B',
		// 	levelCost: 130
		// }, {
		// 	levelName: '20C',
		// 	levelCost: 130
		// }, {
		// 	levelName: '20D',
		// 	levelCost: 130
		// }, {
		// 	levelName: '20E',
		// 	levelCost: 130
		// },
		// {
		// 	levelName: '21A',
		// 	levelCost: 140
		// }, {
		// 	levelName: '21B',
		// 	levelCost: 140
		// }, {
		// 	levelName: '21C',
		// 	levelCost: 140
		// }, {
		// 	levelName: '21D',
		// 	levelCost: 140
		// }, {
		// 	levelName: '21E',
		// 	levelCost: 140
		// },
		// {
		// 	levelName: '22A',
		// 	levelCost: 150
		// }, {
		// 	levelName: '22B',
		// 	levelCost: 150
		// }, {
		// 	levelName: '22C',
		// 	levelCost: 150
		// }, {
		// 	levelName: '22D',
		// 	levelCost: 150
		// }, {
		// 	levelName: '22E',
		// 	levelCost: 150
		// },
		// {
		// 	levelName: '23A',
		// 	levelCost: 160
		// }, {
		// 	levelName: '23B',
		// 	levelCost: 160
		// }, {
		// 	levelName: '23C',
		// 	levelCost: 160
		// }, {
		// 	levelName: '23D',
		// 	levelCost: 160
		// }, {
		// 	levelName: '23E',
		// 	levelCost: 160
		// },
		// {
		// 	levelName: '24A',
		// 	levelCost: 170
		// }, {
		// 	levelName: '24B',
		// 	levelCost: 170
		// }, {
		// 	levelName: '24C',
		// 	levelCost: 170
		// }, {
		// 	levelName: '24D',
		// 	levelCost: 170
		// }, {
		// 	levelName: '24E',
		// 	levelCost: 170
		// },
		// {
		// 	levelName: '25A',
		// 	levelCost: 180
		// }, {
		// 	levelName: '25B',
		// 	levelCost: 180
		// }, {
		// 	levelName: '25C',
		// 	levelCost: 180
		// }, {
		// 	levelName: '25D',
		// 	levelCost: 180
		// }, {
		// 	levelName: '25E',
		// 	levelCost: 180
		// },
		// {
		// 	levelName: '26A',
		// 	levelCost: 190
		// }, {
		// 	levelName: '26B',
		// 	levelCost: 190
		// }, {
		// 	levelName: '26C',
		// 	levelCost: 190
		// }, {
		// 	levelName: '26D',
		// 	levelCost: 190
		// }, {
		// 	levelName: '26E',
		// 	levelCost: 190
		// },
		// {
		// 	levelName: '27A',
		// 	levelCost: 200
		// }, {
		// 	levelName: '27B',
		// 	levelCost: 200
		// }, {
		// 	levelName: '27C',
		// 	levelCost: 200
		// }, {
		// 	levelName: '27D',
		// 	levelCost: 200
		// }, {
		// 	levelName: '27E',
		// 	levelCost: 200
		// }
	]);
	const currentLevelData = ref(levelData.value[0])
	const currentLevelIndex = ref(-1)
	const currentGambleRecords = ref([])
	const runSpeed = ref(600)
  const maxCount = ref(1000)
  const thisMaxCount = ref(0)

	const clear = () => {
    currentGambleRecords.value = []
    currentGambleNumber.value = 1
    totalMoney.value = 0
    data.paymentAmount = 0
    data.count = 1
    data.totalAmount = 0
    // statisticsRecords.value = []
		// localStorage.setItem('totalGambleRecords', JSON.stringify(statisticsRecords.value))
	}

  const reset = () => {
    autoRunning.value = false //停止自动运行
    currentLevelIndex.value = -1 //投注等级重置
    thisMaxCount.value = 0
    currentGambleRecords.value = []
    currentBuy.value = 1
    currentGambleNumber.value = 1
    totalMoney.value = 0
    data.paymentAmount = 0
    data.count = 0
    data.totalAmount = 0
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
    data.totalAmount += moneyGet
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

	const getFirstElements = (array, count) => {
	  return array.slice(0, count);
	}
	//赢局
	const win = async (open) => {
    let currentBuyAmount = winCount.value === 1 ? levelData.value[currentLevelIndex.value].levelCost : levelData.value[
        currentLevelIndex.value].levelCost
		const moneyGet = open === 3 ? (currentBuyAmount) : (currentBuyAmount * 0.95);
    currentJuwin.value +=moneyGet
		const momeyRemain = totalMoney.value + (moneyGet)
		totalMoney.value = momeyRemain

		makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].levelName, currentBuyAmount, getBuyDetail(), getOpenDetail(open), '赢', moneyGet,
			momeyRemain)

    if (winCount.value === 1) {
      winCount.value = 0
    } else {
      winCount.value++
    }

    data.paymentAmount += currentBuyAmount
		loseCount.value = 0
	}
	//和局
	const tie = async (open) => {
		const moneyGet = 0
    let currentBuyAmount = winCount.value === 1 ? levelData.value[currentLevelIndex.value].levelCost  : levelData.value[
        currentLevelIndex.value].levelCost

		const momeyRemain = totalMoney.value + (moneyGet)
		totalMoney.value = momeyRemain
		makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].levelName, currentBuyAmount, getBuyDetail(), getOpenDetail(open), '和', moneyGet,
			momeyRemain)
		loseCount.value = 0
		winCount.value = 0
    data.paymentAmount += currentBuyAmount
    jumpurrentBuy.value = false
	}
	//输局

  const lastOpen = ref(-1)
  const jumpurrentBuy = ref(false)
	const lose = async (open) => {
    if (lastOpen.value === 2) {
      lastOpen.value = -1
    } else if ((lastOpen.value === -1 || lastOpen.value !== open) && lastOpen.value !== 2) {
      lastOpen.value = open
    }

    // 重新初始化
    if (jumpurrentBuy.value) jumpurrentBuy.value = false

    let currentBuyAmount = winCount.value === 1 ? levelData.value[currentLevelIndex.value].levelCost : levelData.value[
        currentLevelIndex.value].levelCost
		const moneyGet = -currentBuyAmount
    currentJuwin.value -=currentBuyAmount
		const momeyRemain = totalMoney.value + (moneyGet)
		totalMoney.value = momeyRemain
		makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].levelName, currentBuyAmount, getBuyDetail(), getOpenDetail(open), '输', moneyGet,
			momeyRemain)
    if (open === 2) {
      // loseCount.value = 0
    } else {

      loseCount.value++
    }
		winCount.value = 0
    data.paymentAmount += currentBuyAmount
    if (loseCount.value === 3 && jumpurrentBuy.value === false) {
      jumpurrentBuy.value = true
    }
	}

	//开始游戏
	const gameStart = async () => {
		open.value = await getRandomOne()
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
		if (loseCount.value >= 1 && open !== 2) {
			currentLevelIndex.value++
      // loseCount.value = 0
			currentGambleRecords.value[0].levelUp = 1
			// winCount.value = 0
			// if (currentBuy.value === 1) {
			// 	currentBuy.value = 3
			// } else {
			// 	currentBuy.value = 1
			// }
		}
	}
	//提升投注等级
	const toNextLevel = async () => {
		if (currentLevelIndex.value == -1 && thisMaxCount.value === 0) {
			currentLevelIndex.value = 0
		}

		//判断当前余额是否足够下注
		if (levelData.value.length !== currentLevelIndex.value && totalMoney.value >= levelData.value[currentLevelIndex.value].levelCost) {
			// gameStart()
			await main()
			//判断当前（上局）是否处于最后一个投注等级
			if (currentLevelIndex.value === levelData.value.length) {
				currentLevelIndex.value = 0
			}
		} else {
			// endGame() //结束当前局游戏
      autoRunning.value = false
      currentLevelIndex.value = -1
			//1.5秒后开始游戏
			// setTimeout(() => {
			// 	autoGameStart()
			// }, 1500)


		}
	}
	const statisticsRecords = ref([]) //每局统计


	//统计当前局的总流水，游玩局数
	const statistics = (endReason) => {
		//计算流水总和
		const sumAmount = currentGambleRecords.value.flat().reduce((acc, item) => acc + item.levelCost, 0);
		statisticsRecords.value.push({
			totalPlay: currentGambleRecords.value.length,
			totalCost: sumAmount, //流水总和
      endReason: endReason, //结束原因
      list:currentGambleRecords.value,
      startMoney:startMoney.value,//初始金额
      winGoAmount:winGoAmount.value,//赢走金额

		})
		// localStorage.setItem('totalGambleRecords', JSON.stringify(statisticsRecords.value))
	}
	const gamebleIndex = ref(1) //第几大局
	const autoRunning = ref(false) //是否自动运行

	//结束当前大局，相关数值重置，统计当前大局的数据∂
	const endGame = (markStatistics = true) => {
    autoRunning.value = false //停止自动运行
    currentLevelIndex.value = -1 //投注等级重置
		// if (markStatistics) {
		// 	statistics() //统计当前大局的数据
		// 	gamebleIndex.value++ //大局index+1
		// }
		// currentGambleNumber.value = 0 //小局重置为0
		// currentGambleRecords.value = []

	}
	const stopAutoGame = () => {
		autoRunning.value = false
	}
	const contineuGame = () => {
		autoRunning.value = true
    doStartGame()
	}

	//自动运行
	const autoGameStart = () => {
    // 初始化
    data.totalAmount = 0
    data.count = 0
    data.paymentAmount = 0
    thisMaxCount.value = 0
    thisCount.value = 0
    currentGambleNumber.value = 1
		totalMoney.value = startMoney.value
		currentGambleRecords.value = []
		autoRunning.value = true
    doStartGame()
	}


  const thisCount = ref(0)
  const doStartGame = async () => {
    if (autoRunning.value === false) return
    // 结束
    if ((thisMaxCount.value === maxCount.value || totalMoney.value <= 0)) {
      currentLevelIndex.value = -1
      autoRunning.value = false
      // thisMaxCount.value = 0
    } else {
      // if (thisCount.value < 60||currentJuwin.value<winGoAmount.value) {
      if (currentGambleRecords.value.length < 60&&currentJuwin.value<winGoAmount.value) {
        data.count++
        thisCount.value++
        if (autoRunning.value) {
          await toNextLevel()
        }
        setTimeout(await doStartGame, 1/1000)
      } else {
        if(currentGambleRecords.value.length >= 60){
          statistics('60局结束')
        }
        if(currentJuwin.value>=winGoAmount.value){
          statistics('赢走')
        }
        // 初始化投资
        totalMoney.value = startMoney.value

        currentLevelIndex.value = 0
        currentJuwin.value = 0
        thisCount.value = 0
        thisMaxCount.value++
        currentGambleRecords.value = []
        setTimeout(await doStartGame, 1/1000)
      }
    }


  }


	onMounted(() => {
		// document.querySelector('body').addEventListener('keydown',e=>{
		//   autoGameStart()
		// })
		// const oldData = localStorage.getItem('totalGambleRecords') //读取旧统计数据
		// if (oldData) {
		// 	statisticsRecords.value = JSON.parse(oldData)
		// }
		//todo:需要给一个变量，后面clearInterval()

	})


	// 重新
	const currentBuy = ref(1) //当前下注
	const openLog = ref([])
	const open = ref()
  const data = reactive({
    count: 0,
    totalAmount: 0,
    paymentAmount: 0
  })
	const main = async () => {
		await doCurrentBuy() // 开始下注
		await gameStart() // 游戏开始
	}

	// 开始下注
	const doCurrentBuy = async () => {
    if (jumpurrentBuy.value) {
      currentBuy.value = open.value === 3 ? 1 : 3;
    } else {
      if (open.value !== 2 && currentBuy.value !== open.value && open.value !== undefined) {
        // 出现和则切换下注
        currentBuy.value = currentBuy.value === 3 ? 1 : 3;
      }
    }
		return true
	}

	// 预防列表溢出
</script>

<template>
	<div class="gamble-container">
    <div style="  width: calc(100vw / 3);border-right: 1px solid #dddddd;overflow-y: scroll;padding-bottom: 15px">
      <record :show-ju="true" :list="currentGambleRecords"/>
      <div style="width: 100%;height: 120px;"></div>
    </div>

		<div class="all-gamble-result-container" style="padding: 15px 0">
      <div style="height: 30vh;text-align: center">
        <div style="display: flex;justify-content: space-between" >
          <div style="flex: 1">当前局数:{{currentGambleRecords.length}}</div>
          <div style="flex: 1">当前局赢:{{currentJuwin}}</div>
        </div>
        <div class="record2" style="margin-top: 30px;display: flex;justify-content: space-between;text-align: center">
          <div style="flex: 1">总局数:{{ data.count }}</div>
          <div style="flex: 1">累计交易:{{data.paymentAmount}}</div>
        </div>
        <div class="record2" style="margin-top: 30px;display: flex;justify-content: space-between;text-align: center">
          <div style="flex: 1">输赢累计:{{ data.totalAmount }}</div>
          <div style="flex: 1">当前循环:{{ thisMaxCount }}</div>
        </div>
      </div>
      <div style="height: 65vh;">
        <div style="display: flex;justify-content: space-between;;align-items: center;padding: 0 15px">
          <div style="font-size: 24px">历史记录</div>
          <div style="cursor: pointer;color: cadetblue" @click="clearData" >清空历史纪录</div>
        </div>
        <div class="stat-item">
          <div>初始金额</div>
          <div>赢走金额</div>
          <div>游玩局数</div>
          <div>流水</div>
          <div>结束原因</div>
          <div>-</div>
        </div>
        <div style="height: 60vh;overflow: scroll">
          <div class="stat-item" v-for="(s,index) in statisticsRecords" @click="showRecord(s.list)">
            <div>{{ s.startMoney }}</div>
            <div>{{ s.winGoAmount }}</div>
            <div>{{ s.totalPlay }}</div>
            <div>{{ s.totalCost }}</div>
            <div>{{ s.endReason }}</div>
            <div >查看详情</div>
          </div>
        </div>

      </div>
		</div>
<!--		<div style="color: #2d8cf0;width: 20px" @click="reset()">清空</div>-->
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
        <div class="hint">每局携带的金额，低于投注金额跳出当前局</div>
				<div class="value"><input class="default-input" v-model.number="startMoney" type="text"></div>
			</div>
			<!--      <div class="setting-item">-->
			<!--        <div class="name">运行间隙（毫秒）</div>-->
			<!--        <div class="value"><input class="default-input" v-model="runSpeed" type="text"></div>-->
			<!--      </div>-->
			<div class="setting-item">
				<div class="name">赢走金额</div>
        <div class="hint">当前局赢够赢走金额后，跳出当前局</div>
				<div class="value"><input class="default-input" v-model="winGoAmount" type="text"></div>
			</div>
      <div class="setting-item">
        <div class="name">循环次数</div>
        <div class="hint">进行多少局</div>
        <div class="value"><input class="default-input" v-model.number="maxCount" type="text"></div>
      </div>
		</div>
		<div style="display: flex;gap: 20px;">
      <div class="default-button" style="width: 200px;" @click="autoGameStart"
           v-show="currentLevelIndex===-1&&autoRunning===false">
        <span>开始</span>
      </div>
      <div class="default-button" style="width: 200px;" @click="contineuGame"
           v-show="currentLevelIndex>=0&&autoRunning===false">
        <span>继续</span>
      </div>
      <div class="default-button" style="width: 200px;" @click="stopAutoGame"
           v-show="currentLevelIndex>=0&&autoRunning===true">
        <span>暂停</span>
      </div>
      <div class="default-button" style="width: 200px;" @click="reset()"
           v-show="currentLevelIndex>=0">
        <span>重置</span>
      </div>
    </div>
	</div>
  <RcordDialog ref="rcordDialogRef"></RcordDialog>
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
		//justify-content: space-between;
	}

	.level-container {
		position: fixed;
		right: 0;
		top: 0;
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



	.setting-container {
		display: flex;
		justify-content: space-between;
		margin-top: 15px;

		.setting-item {
			width: 160px;

			.name {}

			.value {
				.default-input {
					width: 80%;
					color: #2c405a;
				}
			}
		}
	}


	.all-gamble-result-container ,.level-container{
    width: 30vw;
	}

  .pl-2 {
    padding-left: 10px;
  }
  .hint{
    font-size: 12px;
    color: #7a7a7a;
    height: 40px;
  }
  .stat-item:hover{
    background-color: #2d8cf0;
    cursor: pointer;
  }
  .stat-item{
    display: flex;justify-content: space-between;
    padding: 5px 0;
    div{
      width: calc(100% / 6);
      text-align: center;
    }
  }
</style>