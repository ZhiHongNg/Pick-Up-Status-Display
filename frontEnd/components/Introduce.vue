<script setup>
import image01 from '@/static/images/introduce01.png'
</script>

<template>
  <div class="introduce-container">
    <div class="introduce-title">基因秒表背后的科学</div>
    <div class="second-title">什么是表观遗传？</div>
    <text class="introduce-text">
      表观遗传是指基因表达模式的变化，而非基因序列的变化。表观遗传变化与衰老密切相关。
    </text>
    <text class="introduce-text">
      在发育过程中，表观遗传机制通过调控基因表达，赋予和保持每个细胞的身份和功能。例如，尽管每个细胞都包含相同的基因组序列，但通过表观遗传，肝脏细胞可以发挥肝脏细胞的功能，心脏细胞可以发挥心脏细胞的功能，等等。正是由于表观遗传，我们才拥有复杂的器官系统:执行维持生命所必需的各种功能
    </text>
    <image class="introduce-image" :src="image01"></image>
    <div class="second-title">为什么表观遗传学很重要？</div>

    <text class="introduce-text">表观遗传是指基因表达模式的变化，而非基因序列的变化。表观遗传变化与衰老密切相关·在发育过程中，表观遗传机制通过调控基因表达，赋予和保持每个细胞的身份和功能。例如，尽管每个细胞都包含相同的基因组序列，但通过表观遗传，肝脏细胞可以发挥肝脏细胞的功能，心脏细胞可以发挥心脏细胞的功能，等等。正是由于表观遗传，我们才拥有复杂的器官系统，执行维持生命所必需的各种功能。</text>

    <div class="second-title">关于我们基因秒表（iWatchAge）</div>
    <text class="introduce-text">基因年龄是指细胞的基因表达模式，而非基因序列的变化。它可以根据与衰老和衰退相关的各种表观遗传标志物来计算·表观遗传标志物，如第五碱基，已被证明可以准确地测量生物年龄。
    </text>
    <text class="introduce-text">
      此外，最近的研究还证明了不同的疾病或综合症会对生物年龄产生不利影响。例如，2019年的一项研究发现，患有2型糖尿病的人的基因年龄比健康人高出约5岁。加州大学洛杉矶分校的Steve Horvath博士开创了基于第五碱基的基因年龄预测领域。他创建了一个表观遗传衰老时钟，被称为Horvath时钟。Horvath时钟使用353个DNA胞嘧啶甲基化位点(通常称为CpG位点)来计算基因年龄。在2013年的一项研究中，Horvath使用了大约8000名人类研究对象的数据，证明了Horvath时钟是一种准确的基因年龄测量方法。这项研究已被引用超过1500次，并仍然是基因年龄表观遗传学测量的金标准。元能基因秒表(iWatchAge)报告是基于Horvath时钟的。它使用了Horvath时钟的353个CpG位点，以及额外的2000个CpG位点，以提高生物年龄测量的准确性。总而言之，基因年龄与日历年龄有很强的相关性。它可以用来量化和监控衰老过程，以及评估生活方式改变和各种逆龄措施的效果。</text>
  </div>
</template>

<style scoped lang="scss">
.introduce-container {
  padding: 15px;
  text-align: left;
  transform: translateY(115px);
}

.introduce-title {
  font-size: 30px;
  color: #009d95;
  text-align: center;
  padding: 15px 0;
}

.second-title {
  font-size: 24px;
  color: #009d95;
  text-align: center;
  padding: 37.5px 0;
}

.introduce-text {
  font-style: normal;
  color: black;
  font-weight: 100;
  display: block;
  padding: 0 15px;
}
.introduce-image {
  width: 100%;
}
</style>