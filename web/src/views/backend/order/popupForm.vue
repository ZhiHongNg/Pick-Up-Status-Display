<template>
    <!-- 对话框表单 -->
    <!-- 建议使用 Prettier 格式化代码 -->
    <!-- el-form 内可以混用 el-form-item、FormItem、ba-input 等输入组件 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="['Add', 'Edit'].includes(baTable.form.operate!)"
        @close="baTable.toggleForm"
        width="50%"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="config.layout.shrink ? '':'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @submit.prevent=""
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    :label-position="config.layout.shrink ? 'top' : 'right'"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem :label="t('order.openid')" type="string" v-model="baTable.form.items!.openid" prop="openid" :placeholder="t('Please input field', { field: t('order.openid') })" />
                    <FormItem :label="t('order.amount')" type="number" prop="amount" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.amount" :placeholder="t('Please input field', { field: t('order.amount') })" />
                    <FormItem :label="t('order.name')" type="string" v-model="baTable.form.items!.name" prop="name" :placeholder="t('Please input field', { field: t('order.name') })" />
                    <FormItem :label="t('order.phone')" type="string" v-model="baTable.form.items!.phone" prop="phone" :placeholder="t('Please input field', { field: t('order.phone') })" />
                    <FormItem :label="t('order.address')" type="string" v-model="baTable.form.items!.address" prop="address" :placeholder="t('Please input field', { field: t('order.address') })" />
                    <FormItem :label="t('order.pay_sn')" type="string" v-model="baTable.form.items!.pay_sn" prop="pay_sn" :placeholder="t('Please input field', { field: t('order.pay_sn') })" />
                    <FormItem :label="t('order.remark')" type="string" v-model="baTable.form.items!.remark" prop="remark" :placeholder="t('Please input field', { field: t('order.remark') })" />
                    <FormItem :label="t('order.status')" type="number" v-model="baTable.form.items!.status" prop="status" :data="{ content: { '0': t('order.status 0'), '1': t('order.status 1'), '2': t('order.status 2'), '4': t('order.status 4'), '5': t('order.status 5') } }" :placeholder="t('Please input field', { field: t('order.status') })" />
                    <FormItem :label="t('order.cancel_reason')" type="string" v-model="baTable.form.items!.cancel_reason" prop="cancel_reason" :placeholder="t('Please input field', { field: t('order.cancel_reason') })" />
                    <FormItem :label="t('order.id_number')" type="number" prop="id_number" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.id_number" :placeholder="t('Please input field', { field: t('order.id_number') })" />
                    <FormItem :label="t('order.id_name')" type="string" v-model="baTable.form.items!.id_name" prop="id_name" :placeholder="t('Please input field', { field: t('order.id_name') })" />
                    <FormItem :label="t('order.id_front_image')" type="image" v-model="baTable.form.items!.id_front_image" prop="id_front_image" />
                    <FormItem :label="t('order.id_back_image')" type="image" v-model="baTable.form.items!.id_back_image" prop="id_back_image" />
                    <FormItem :label="t('order.pay_time')" type="number" prop="pay_time" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.pay_time" :placeholder="t('Please input field', { field: t('order.pay_time') })" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm()">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import type { FormInstance, FormItemRule } from 'element-plus'
import { inject, reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FormItem from '/@/components/formItem/index.vue'
import { useConfig } from '/@/stores/config'
import type baTableClass from '/@/utils/baTable'
import { buildValidatorData } from '/@/utils/validate'

const config = useConfig()
const formRef = ref<FormInstance>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    amount: [buildValidatorData({ name: 'number', title: t('order.amount') })],
    status: [buildValidatorData({ name: 'number', title: t('order.status') })],
    id_number: [buildValidatorData({ name: 'number', title: t('order.id_number') })],
    create_time: [buildValidatorData({ name: 'date', title: t('order.create_time') })],
    pay_time: [buildValidatorData({ name: 'number', title: t('order.pay_time') })],
    update_time: [buildValidatorData({ name: 'date', title: t('order.update_time') })],
})
</script>

<style scoped lang="scss"></style>
