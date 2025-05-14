<script setup>
	import {
		onMounted,
		ref,
    reactive,
      watch
	} from 'vue'
  import Record from "@/pages/Gamble/record.vue";
  import RcordDialog from "@/pages/Gamble/RcordDialog.vue";
  import {exportJSONToExcel} from "@/utils/api/Other";
const levelDataDialogRef = ref(false)
const editLevelData = ()=>{
  if(autoRunning.value==true){
    alert('正在运行时不能调整投注')
  }else{
    levelDataDialogRef.value = true
  }
}
const debugList = ref([])
  const rcordDialogRef = ref()
  const refresh = ()=>{
    window.location.reload()
  }
  const rrrrrr = ref(150)
  const resetAndExport = ()=>{
    statistics('手动结束')
    reset()
  }
  const rate = ref({
    zhuang:0.95,
    xian:1,
    he:8,
  })
  const lastZhuangBuy = ref(1260)
  const lastHeBuy = ref(20)
  const exportToExcel = ()=>{
    const excelData = []
    statisticsRecords.value.forEach((item,index)=>{
      excelData.push({
        sheetName:'第'+(statisticsRecords.value.length-index)+'次',data:item.list,headers:  {
          times: '局数',
          levelName: '等级',
          levelCost: '投注金额',
          buyDetail: '投注',
          openDetail: '开',
          result: '结果',
          moneyGet: '输赢',
          momeyRemain: '剩余金额',
        },summaryData:{
          currentWin: "当前局赢",
          currentWinValue: item.remain,
          playCountLabel: "游玩局数",
          playCount: item.totalPlay,
          flowLabel: "流水",
          flow: item.totalCost,
          reasonLabel: '结束原因',
          reason: item.endReason
        }

      })
    })
    exportJSONToExcel(excelData,'批量导出.xlsx')
  }
  const winCount = ref(0) //累计赢的次数
  const loseCount = ref(0) //累计输或和的次数
  const winGoAmount = ref(999999) //赢走金额 ,赢够多少钱跳出这局
  const currentJuwin = ref(0)//当前局赢得金额

  const showRecord = (list)=>{
    rcordDialogRef.value.showDialog(list)
  }
  const timeSpeed = ref(10)
	//投注等级
	const levelData = ref([{
			levelName: '1',
			level: '第一级',
			levelCost: 20
		},
    {
			levelName: '1',
      level: '第二级',
			levelCost: 20
		},
    {
			levelName: '3',
      level: '第三级',
			levelCost: 60
		},
    {
			levelName: '7',
      level: '第四级',
			levelCost: 140
		},
    {
			levelName: '15',
      level: '第五级',
			levelCost: 300
		},
		{
			levelName: '31',
      level: '第六级',
			levelCost: 620
		},
		{
			levelName: '63',
      level: '第七级',
			levelCost: 1260
		},
	]);
	const currentLevelData = ref(levelData.value[0])
	const currentLevelIndex = ref(-1)
	const currentGambleRecords = ref([])
	const runSpeed = ref(600)
  const maxCount = ref(1000)
  const maxJu = ref(60000)
  const thisMaxCount = ref(0)


  const reset = () => {
    autoRunning.value = false //停止自动运行
    currentLevelIndex.value = -1 //投注等级重置
    thisMaxCount.value = 0
    currentJuwin.value = 0
    currentGambleRecords.value = []
    currentBuy.value = 1
    currentGambleNumber.value = 1
    totalMoney.value = 0
    data.paymentAmount = 0
    data.count = 0
    // data.totalAmount = 0
    clearTimeout(timer.value)
  }
	const getRandomOne = () => {
		const numbers = [1, 2, 3];
		return numbers[Math.floor(Math.random() * numbers.length)];
	}
	const currentGambleNumber = ref(1)
	const totalMoney = ref(10000)
	const startMoney = ref(10000)
	const showLastCount = ref(50)
	const makeRecord = (times, levelName, levelCost, buyDetail, openDetail, result, moneyGet, momeyRemain,levelUpNumber) => {


		currentGambleRecords.value.unshift({
			times,
			levelName,
			levelCost,
			result,
			buyDetail,
			openDetail,
			moneyGet,
			momeyRemain,
			linkActive:levelName==='第七级'?true:false,
			levelUp: levelUpNumber
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
		if (currentBuy.value === 4) {
			return '63庄，1和'
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
    if(rrrrrr.value!=0){
      return array.slice(0, count);
    }else{
      return array
    }

	}
	//赢局
	//和局
	//输局

  const lastOpen = ref(-1)
  const jumpurrentBuy = ref(false)

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

		if (currentBuy.value === 4) {

      data.paymentAmount += (lastZhuangBuy.value+lastHeBuy.value)
      if(open.value==1){
        //庄赢了
        let momenyGet = lastZhuangBuy.value*rate.value.zhuang
        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, lastZhuangBuy.value, '庄', getOpenDetail(open.value), '赢', momenyGet,
            totalMoney.value + momenyGet,2)
        totalMoney.value = totalMoney.value + momenyGet

        currentJuwin.value +=momenyGet
        //和输了

        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, lastHeBuy.value, '和', getOpenDetail(open.value), '输', -lastHeBuy.value,
            totalMoney.value-lastHeBuy.value,2)
        totalMoney.value = totalMoney.value - lastHeBuy.value
        currentJuwin.value -= 0
        currentLevelIndex.value = 0

      }else if(open.value==2){
        //和赢了
        let momenyGet = lastHeBuy.value*rate.value.he

        totalMoney.value = totalMoney.value +momenyGet
        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, lastHeBuy.value, '和', getOpenDetail(open.value), '和', momenyGet,
            totalMoney.value,2)
        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, lastZhuangBuy.value, '庄', getOpenDetail(open.value), '和', 0,
            totalMoney.value,2)
        currentJuwin.value +=momenyGet

      }else{
        //开闲

        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, lastHeBuy.value, '和', getOpenDetail(open.value), '输', -lastHeBuy.value,
            totalMoney.value - lastHeBuy.value,2)
        totalMoney.value = totalMoney.value-lastHeBuy.value


        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, lastZhuangBuy.value, '庄', getOpenDetail(open.value), '输', -lastZhuangBuy.value,
            totalMoney.value - lastZhuangBuy.value,2)
        currentJuwin.value -= lastZhuangBuy.value


        totalMoney.value = totalMoney.value-lastZhuangBuy.value
      }
      currentLevelIndex.value = 0
    }else{
      data.paymentAmount += levelData.value[currentLevelIndex.value].levelCost

      if (currentBuy.value === open.value) {
        let momenyGet = 0
        if(currentBuy.value==1){
           momenyGet = levelData.value[currentLevelIndex.value].levelCost*rate.value.zhuang
        }else if(currentBuy.value==2){
           momenyGet = levelData.value[currentLevelIndex.value].levelCost*rate.value.he
        }else{
           momenyGet = levelData.value[currentLevelIndex.value].levelCost*rate.value.xian
        }

        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, levelData.value[currentLevelIndex.value].levelCost, getBuyDetail(), getOpenDetail(open.value), '赢', momenyGet,
            totalMoney.value + (momenyGet),2)
        currentLevelIndex.value = 0
        currentJuwin.value +=momenyGet
        totalMoney.value = totalMoney.value+momenyGet


      } else if (open.value === 2) {
        let momenyGet = 0
        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, levelData.value[currentLevelIndex.value].levelCost, getBuyDetail(), getOpenDetail(open.value), '和', momenyGet,
            totalMoney.value + (momenyGet),0)
        // currentLevelIndex.value++
        currentJuwin.value +=momenyGet
        totalMoney.value = totalMoney.value+momenyGet
      } else {
        let momenyGet = -levelData.value[currentLevelIndex.value].levelCost
        makeRecord(currentGambleNumber.value, levelData.value[currentLevelIndex.value].level, levelData.value[currentLevelIndex.value].levelCost, getBuyDetail(), getOpenDetail(open.value), '输', momenyGet,
            totalMoney.value + (momenyGet),1)
        currentLevelIndex.value++
        currentJuwin.value +=momenyGet
        totalMoney.value = totalMoney.value + momenyGet
      }
    }
    if(currentGambleNumber>=10){
      if((currentGambleRecords.value[0].momeyRemain-currentGambleRecords.value[0].moneyGet)!=currentGambleRecords.value[1].momeyRemain){
        alert(1)
      }

    }
    currentGambleNumber.value++

		await checkUpgradeLevel()
	}
  watch(
      () => totalMoney.value,
      () => {
        console.log('totalMonet:',totalMoney.value,currentGambleRecords.value[0].times)
      }
  )
  //检查是否提升投注等级
	const checkUpgradeLevel = () => {
		if (loseCount.value >= 1 && open !== 2) {
			currentLevelIndex.value++
			currentGambleRecords.value[0].levelUp = 1
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

      autoRunning.value = false
      currentLevelIndex.value = -1
		}
	}
	const statisticsRecords = ref([]) //每局统计


	//统计当前局的总流水，游玩局数
	const statistics = (endReason) => {
		//计算流水总和
		const sumAmount = currentGambleRecords.value.flat().reduce((acc, item) => acc + item.levelCost, 0);
    data.totalAmount = data.totalAmount+ (currentGambleRecords.value[0].momeyRemain-startMoney.value)
    statisticsRecords.value.push({
			totalPlay: currentGambleRecords.value.length,
			totalCost: sumAmount, //流水总和
      endReason: endReason, //结束原因
      list:currentGambleRecords.value,
      startMoney:startMoney.value,//初始金额
      winGoAmount:winGoAmount.value,//赢走金额
      remain: currentGambleRecords.value[0].momeyRemain-startMoney.value,//剩余金额
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
    if(data.totalAmount===0){
      data.totalAmount = 0
    }else{
      data.totalAmount = data.totalAmount
    }

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
  const timer = ref()
  const doStartGame = async () => {
    if (autoRunning.value === false) return



    // 结束
    if ((thisMaxCount.value === maxCount.value || totalMoney.value <= 0)) {
      currentLevelIndex.value = -1
      autoRunning.value = false
      // thisMaxCount.value = 0
    } else {
      if(currentLevelIndex.value >= 0) {
        if(totalMoney.value < levelData.value[currentLevelIndex.value].levelCost){
          statistics('余额不足')
          totalMoney.value = startMoney.value
          currentLevelIndex.value = 0
          currentJuwin.value = 0
          thisCount.value = 0
          thisMaxCount.value++
          currentGambleRecords.value = []
          timer.value = setTimeout(await doStartGame, Number(timeSpeed.value))
        }
      }
      if (currentGambleRecords.value.length < maxJu.value&&currentJuwin.value<winGoAmount.value) {
        data.count++
        thisCount.value++
        if (autoRunning.value) {
          await toNextLevel()
        }
        timer.value = setTimeout(await doStartGame, Number(timeSpeed.value))
      } else {
        if(currentGambleRecords.value.length >= maxJu.value){
          statistics('60局结束')
        }

        if(totalMoney.value < levelData.value[currentLevelIndex.value].levelCost){
          statistics('不足以投注')
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
        timer.value = setTimeout(await doStartGame, Number(timeSpeed.value))
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
    if(currentLevelIndex.value == 6){
      currentBuy.value = 4
    }else{
      currentBuy.value = 3
    }
	}

	// 预防列表溢出
</script>

<template>
	<div class="gamble-container">
    <div style="  width: calc(100vw / 3);border-right: 1px solid #dddddd;overflow-y: scroll;padding-bottom: 15px">
      <record :show-ju="true" :list="getFirstElements(currentGambleRecords,rrrrrr)"/>
      <div style="width: 100%;height: 120px;"></div>
    </div>

		<div class="all-gamble-result-container" style="padding: 15px 0">
      <div style="height: 30vh;text-align: center" >
        <h1 style="margin-bottom: 10px">本次循环：</h1>
        <div style="display: flex;justify-content: space-between" >
          <div style="flex: 1">本局局数:{{currentGambleRecords.length}}</div>
          <div style="flex: 1">本局赢:
            <span v-if="currentLevelIndex>=0">{{currentGambleRecords[0].momeyRemain-startMoney}}</span>
            <span v-else>0</span>
          </div>
          <div style="flex: 1">本局交易:{{data.paymentAmount}}</div>
        </div>
        <div style="padding: 15px 0"></div>
        <h1>多次循环统计：</h1>
        <div class="record2" style="margin-top: 30px;display: flex;justify-content: space-between;text-align: center">
          <div style="flex: 1">输赢累计:{{ data.totalAmount + (currentGambleRecords.length>0?(currentGambleRecords[0].momeyRemain-startMoney):0) }}</div>
        </div>
      </div>
      <div style="height: 65vh;">
        <div style="display: flex;justify-content: space-between;;align-items: center;padding: 0 15px">
          <div style="font-size: 24px">历史记录</div>
          <div style="cursor: pointer;color: cadetblue" @click="exportToExcel" v-if="statisticsRecords.length>0" >批量导出到excel</div>
        </div>
        <div class="stat-item">
          <div>初始金额</div>
<!--          <div>赢走金额</div>-->
          <div>当前局赢</div>
          <div>游玩局数</div>
          <div>流水</div>
          <div>结束原因</div>
          <div>-</div>
        </div>
        <div style="height: 60vh;overflow: scroll">
          <div class="stat-item" v-for="(s,index) in statisticsRecords" @click="showRecord(s.list)">
            <div>{{ s.startMoney }}</div>
<!--            <div>{{ s.winGoAmount }}</div>-->
            <div>{{ s.remain }}</div>

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
			<div>等级：{{ levelData[currentLevelIndex].level }}</div>
			<div>投注额：{{ levelData[currentLevelIndex].levelCost }}</div>
		</div>
		<div v-if="currentLevelIndex===-1">
			<div>当前投注：</div>
			<div>等级：未开始</div>
			<div>投注额：未开始</div>
		</div>
		<div>
      <div style="display: flex;justify-content: flex-end">
        <div style="cursor:pointer;color: #2d8cf0" @click="editLevelData">调整</div>
      </div>
			<div class="level-step" style="width: 100%;">
				<div class="step" :class="{'active':index===currentLevelIndex}" v-for="(levelDetail,index) in levelData" >
          <div v-if="index<=5">
            <div>{{ levelDetail.level }}</div>
            <div> {{ levelDetail.levelCost }}</div>
          </div>
          <div v-if="index>5">
            {{ levelDetail.level }}
            <div style="display:flex;">
              <div>庄：{{ lastZhuangBuy }}</div>
              <div>和：{{ lastHeBuy }}</div>
            </div>
          </div>

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
      <div class="setting-item">
        <div class="name">庄赢倍率</div>
        <div class="hint">下注庄时，赢的比率</div>
        <div class="value"><input class="default-input" v-model.number="rate.zhuang" type="text"></div>
      </div>
      <div class="setting-item">
        <div class="name">闲赢倍率</div>
        <div class="hint">下注闲时，赢的比率</div>
        <div class="value"><input class="default-input" v-model.number="rate.xian" type="text"></div>
      </div>
      <div class="setting-item">
        <div class="name">和赢倍率</div>
        <div class="hint">下注和时，赢的比率</div>
        <div class="value"><input class="default-input" v-model.number="rate.he" type="text"></div>
      </div>

      <div class="setting-item">
        <div class="name">最大局数</div>
        <div class="hint">每次循环中，超出最大局数后结束当次循环</div>
        <div class="value"><input class="default-input" v-model.number="maxJu" type="text"></div>
      </div>
      <div class="setting-item">
        <div class="name">运行速率</div>
        <div class="hint">每局的运行间距，毫秒(1秒=1000毫秒)</div>
        <div class="value"><input class="default-input" v-model.number="timeSpeed" type="text"></div>
      </div>
      <div class="setting-item">
        <div class="name">限制显示条数</div>
        <div class="hint">0不限制,显示条数越大，可能导致运行卡顿</div>
        <div class="value"><input class="default-input" v-model.number="rrrrrr" type="text"></div>
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
      <div class="default-button" style="width: 200px;background-color: lightseagreen" @click="resetAndExport"
           v-show="currentLevelIndex>=0&&autoRunning===false">
        <span>结束并保存数据</span>
      </div>
      <div class="default-button" style="width: 200px;" @click="stopAutoGame"
           v-show="currentLevelIndex>=0&&autoRunning===true">
        <span>暂停</span>
      </div>
      <div class="default-button"  style="width: 200px;background-color: red" @click="refresh()"
           v-show="currentLevelIndex>=0">
        <span>重置</span>
      </div>

    </div>
	</div>
  <RcordDialog ref="rcordDialogRef"></RcordDialog>
  <div class="mask-container" v-if="levelDataDialogRef" @click="levelDataDialogRef=false"></div>
  <div class="levelDataDialog" v-show="levelDataDialogRef">
    <div  style="width: 100%;display: flex;flex-wrap: wrap;color: white">
      <div  style="display: flex;margin-bottom: 30px;padding: 15px"  :class="{'active':index===currentLevelIndex}" v-for="(levelDetail,index) in levelData" >
        <div style="width: 150px;" v-if="index<=5">
          <div style="padding-bottom: 7.5px">{{ levelDetail.level }}</div>
          <div class="value">
            <input type="text" class="default-input" style="width: 120px;color: black" v-model.number="levelDetail.levelCost" />
          </div>
        </div>
        <div  v-if="index>5" style="
    margin-bottom: 30px;
    background: #cf4b35;
    padding: 7.5px;border-radius: 12px">
          <div class="name" style="text-align: center">{{ levelDetail.level }}</div>
          <div class="value" style="display: flex">
            <div style="margin-right: 30px">

              <div style="padding-bottom: 7.5px">买庄</div>
              <input type="text" class="default-input" style="width: 120px;color: black" v-model.number="lastZhuangBuy" />
            </div>
            <div>
              <div style="padding-bottom: 7.5px">买和</div>
              <input type="text" class="default-input" style="width: 120px;color: black" v-model.number="lastHeBuy" />
            </div>
          </div>
        </div>
      </div>
      <div class="default-button" @click="levelDataDialogRef=false">关闭</div>
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

	.level-step {
    display: flex;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 30px;
    font-size: 15px;
    .step{
      position: relative;
      width: 90px;
      margin-right: 15px;
    }

    .step:not(:last-child)::after {
      content: '-';
      position: absolute;
      right: -15px;
      top: 15px;
      font-size: 14px;
    }
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
		background-color: #c0362e;
    border-radius: 12px;
	}



	.setting-container {
		display: flex;
		justify-content: space-between;
		margin-top: 15px;
    flex-wrap: wrap;
		.setting-item {
			width: 33%;
      margin-bottom: 15px;
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
  .levelDataDialog{
    width: 400px;
    height: min-content;
    position: fixed;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    background: #2a589b;
    border-radius: 12px;
    padding: 30px;
    z-index: 9999;
  }
</style>