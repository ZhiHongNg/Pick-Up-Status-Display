<script setup>

import {exportJSONToExcel} from "@/utils/api/Other";

const props = defineProps({
  list:{
    type:Array,
    required:true,
  },
  showJu:{
    type:Boolean,
    required:false,
    default:true
  }
})
const getFirstElements = (array, count) => {
  return array.slice(0, count);
}
const expoetFile = (jsonData)=>{
  const d = {sheetName:'sheet',data:jsonData,headers:  {
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
        currentWinValue: 100,
        playCountLabel: "游玩局数",
        playCount: 20,
        flowLabel: "流水",
        flow: 2244.50,
        reasonLabel: "结束原因",
        reason: "xxx"
  }
  }
  exportJSONToExcel([d],'单局数据.xlsx')
}
</script>

<template>
  <div class="current-gamble-container" style="padding-bottom: 10px">
    <div style="display: flex;justify-content: end;">
<!--      <div class="export-button" @click="expoetFile(list)">导出</div>-->
    </div>
    <div class="record">
    <div v-show="showJu">局数</div>
      <div>等级</div>
      <div>投注金额</div>
      <div>投注</div>
      <div>开</div>
      <div>结果</div>
      <div>输赢</div>
      <div>剩余金额</div>
      <div>是否升级</div>
    </div>
    <div class="record" :class="{'sss':record.linkActive}" v-for="(record,index) in list">
<!--      <div v-show="showJu">{{ record.times }}</div>-->
      <div v-show="showJu">{{record.times}}</div>
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
        <image v-if="record.levelUp===1" style="width: 10px;height: 10px"
               src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTU1NS40NzEgNDQwLjQxOGMtMjQuMDE1LTIzLjY3NC02Mi45NTEtMjMuNjc0LTg2Ljk0MiAwTDMwLjEzNSA4NzIuMDA5Yy0yMy45OTEgMjMuNjc0LTIzLjk5MSA2Mi4wOTggMCA4NS43NzIgMjQuMDE1IDIzLjY5OSA2Mi45MjcgMjMuNjk5IDg2Ljk0MiAwTDUxMiA1NjkuMDI3bDM5NC45MjMgMzg4Ljc1NGMyNC4wMTUgMjMuNjk5IDYyLjkyNyAyMy42OTkgODYuOTQyIDAgMjQuMDE1LTIzLjY3NCAyNC4wMTUtNjIuMDk4IDAtODUuNzcyTDU1NS40NzEgNDQwLjQxOHoiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTAuNDY4YzNhODFDaUlSeVUiIGNsYXNzPSJzZWxlY3RlZCIgZmlsbD0iIzFhYWJhOCIvPjxwYXRoIGQ9Ik0xMTcuMDUzIDU4My41NThMNTEyIDE5NC44MDRsMzk0LjkyMyAzODguNzc4YzI0LjAxNSAyMy42OTkgNjIuOTI3IDIzLjY5OSA4Ni45NDIgMGE2MC4wOTIgNjAuMDkyIDAgMCAwIDAtODUuNzk2TDU1NS40NzEgNjYuMjE5Yy0yNC4wMTUtMjMuNjc0LTYyLjk1MS0yMy42NzQtODYuOTQyIDBMMzAuMTM1IDQ5Ny43ODZjLTIzLjk5MSAyMy42OTgtMjMuOTkxIDYyLjA5OCAwIDg1Ljc5NiAyNC4wMTUgMjMuNjc0IDYyLjkyNyAyMy42NzQgODYuOTE4LS4wMjR6IiBkYXRhLXNwbS1hbmNob3ItaWQ9ImEzMTN4LnNlYXJjaF9pbmRleC4wLmkxLjQ2OGMzYTgxQ2lJUnlVIiBjbGFzcz0ic2VsZWN0ZWQiIGZpbGw9IiMxYWFiYTgiLz48L3N2Zz4=">
        </image>
        <image v-if="record.levelUp===2" style="width: 15px;height: 15px"
               src="data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0iTTMyNS44MjQgNTMzLjMzM2gxNDMuNTF2LTM4NGE0Mi42NjcgNDIuNjY3IDAgMSAxIDg1LjMzMyAwdjM4NGgxNDMuNTA5YzE4LjU2IDAgMjguMjY3IDIyLjA1OSAxNS43MjMgMzUuNzU1TDUyNy43MjMgNzcyLjE4MWEyMS4zMzMgMjEuMzMzIDAgMCAxLTMxLjQ0NiAwTDMxMC4xMDEgNTY5LjA4OGMtMTIuNTQ0LTEzLjY5Ni0yLjgzNy0zNS43NTUgMTUuNzIzLTM1Ljc1NXpNMTQ5LjMzNCA4MzJhNDIuNjY3IDQyLjY2NyAwIDEgMCAwIDg1LjMzM2g3MjUuMzMzYTQyLjY2NyA0Mi42NjcgMCAxIDAgMC04NS4zMzNIMTQ5LjMzM3oiIGZpbGw9IiNkODFlMDYiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guc2VhcmNoX2luZGV4LjAuaTQuMTliNTNhODE4eDlvS00iIGNsYXNzPSJzZWxlY3RlZCIvPjwvc3ZnPg==">
        </image>

<!--        <span v-if="record.levelUp===2" >降级</span>-->
        <span v-if="record.levelUp===0" >-</span>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.current-gamble-container {
  padding-right: 15px;

  height: 100vh;
  .record:hover {
    background-color: #546985;
  }
  .record.sss {
    background-color: #546985;

  }
  .record {
    text-align: center;
    display: flex;
    div{
      flex:1;
    }
  }
}
.gamble-container {
  .record2 {
    text-align: left;
    display: grid;
    grid: auto-flow / 200px 200px;
    //border: 1px solid #ddd;
    margin-bottom: 5px;
  }
}
.export-button{
  cursor: pointer;
}
.export-button:hover{
  color: #2d8cf0;
}
</style>