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
                    <FormItem :label="t('good.category_id')" type="remoteSelect" v-model="baTable.form.items!.category_id" prop="category_id" :input-attr="{ pk: 'he_.id', field: 'name', 'remote-url': '' }" :placeholder="t('Please select field', { field: t('good.category_id') })" />
                    <FormItem :label="t('good.name')" type="string" v-model="baTable.form.items!.name" prop="name" :placeholder="t('Please input field', { field: t('good.name') })" />
                    <FormItem :label="t('good.image')" type="image" v-model="baTable.form.items!.image" prop="image" />
                    <FormItem :label="t('good.price')" type="number" prop="price" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.price" :placeholder="t('Please input field', { field: t('good.price') })" />
                    <FormItem :label="t('good.original_price')" type="number" prop="original_price" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.original_price" :placeholder="t('Please input field', { field: t('good.original_price') })" />
                    <FormItem :label="t('good.intro')" type="string" v-model="baTable.form.items!.intro" prop="intro" :placeholder="t('Please input field', { field: t('good.intro') })" />
                    <FormItem :label="t('good.detail')" type="editor" v-model="baTable.form.items!.detail" prop="detail" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :placeholder="t('Please input field', { field: t('good.detail') })" />
                    <FormItem :label="t('good.stock')" type="number" prop="stock" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.stock" :placeholder="t('Please input field', { field: t('good.stock') })" />
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
import type {FormInstance, FormItemRule} from 'element-plus'
import {inject, reactive, ref} from 'vue'
import {useI18n} from 'vue-i18n'
import FormItem from '/@/components/formItem/index.vue'
import {useConfig} from '/@/stores/config'
import type baTableClass from '/@/utils/baTable'
import {buildValidatorData} from '/@/utils/validate'

const config = useConfig()
const formRef = ref<FormInstance>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    price: [buildValidatorData({ name: 'float', title: t('good.price') })],
    original_price: [buildValidatorData({ name: 'number', title: t('good.original_price') })],
    detail: [buildValidatorData({ name: 'editorRequired', title: t('good.detail') })],
    stock: [buildValidatorData({ name: 'number', title: t('good.stock') })],
    create_time: [buildValidatorData({ name: 'date', title: t('good.create_time') })],
    update_time: [buildValidatorData({ name: 'date', title: t('good.update_time') })],
})
</script>

<style scoped lang="scss"></style>
