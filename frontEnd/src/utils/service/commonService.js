export function arrayToValueTextArray(array){
    console.log('array',array)
    const valueTextArray = []
    array.forEach((item,index)=>{
        valueTextArray.push({value:index,text:item})
    })
    return valueTextArray
}