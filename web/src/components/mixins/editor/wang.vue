<template>
    <div v-if="state.mounted" :style="style" class="ba-editor wangeditor">
        <Toolbar class="wangeditor-toolbar" :editor="editorRef" :defaultConfig="toolbarConfig" :mode="mode" />
        <Editor
            :style="state.editorStyle"
            v-model="state.value"
            :defaultConfig="state.editorConfig"
            :mode="mode"
            @onCreated="handleCreated"
            @onChange="handleChange"
            v-bind="$attrs"
        />
    </div>
</template>

<script setup lang="ts">
import '@wangeditor/editor/dist/css/style.css' // 引入 css
import {CSSProperties, onBeforeUnmount, onMounted, reactive, shallowRef, watch} from 'vue'
import {i18nChangeLanguage, IEditorConfig, IToolbarConfig} from '@wangeditor/editor'
import {Editor, Toolbar} from '@wangeditor/editor-for-vue'
import {useConfig} from '/@/stores/config'
import {fileUpload} from '/@/api/common'
import NProgress from 'nprogress'

interface Props {
    // 编辑区高度
    height?: string
    mode?: 'default' | 'simple'
    placeholder?: string
    modelValue: string | null
    // https://www.wangeditor.com/v5/toolbar-config.html#getconfig
    toolbarConfig?: Partial<IToolbarConfig>
    // https://www.wangeditor.com/v5/editor-config.html#placeholder
    editorConfig?: Partial<IEditorConfig>
    // 编辑区style
    editorStyle?: CSSProperties
    // 整体的style
    style?: CSSProperties
    // 图片和文件上传到服务器而不是云存储
    fileForceLocal?: boolean
}

type VideoInsertFnType = (url: string) => void
type ImgInsertFnType = (url: string, alt: string, href: string) => void

const props = withDefaults(defineProps<Props>(), {
    height: '320px',
    mode: 'default',
    placeholder: '请输入内容...',
    modelValue: '',
    toolbarConfig: () => {
        return {
            excludeKeys: ['|'],
        }
    },
    editorConfig: () => {
        return {}
    },
    editorStyle: () => {
        {
            return {}
        }
    },
    style: () => {
        return {}
    },
    fileForceLocal: false,
})

const config = useConfig()
const editorRef = shallowRef()
const emits = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const state: {
    mounted: boolean
    value: string
    editorConfig: Partial<IEditorConfig>
    editorStyle: CSSProperties
} = reactive({
    mounted: false,
    value: !props.modelValue ? '<p></p>' : props.modelValue,
    editorConfig: props.editorConfig,
    editorStyle: props.editorStyle,
})

onMounted(() => {
    i18nChangeLanguage(config.lang.defaultLang == 'zh-cn' ? 'zh-CN' : config.lang.defaultLang)
    state.editorConfig.placeholder = props.placeholder

    // 图片上传配置
    state.editorConfig.MENU_CONF = {}
    state.editorConfig.MENU_CONF['uploadImage'] = {
        fieldName: 'file',
        maxFileSize: 10 * 1024 * 1024, // 10M
        async customUpload(file: File, insertFn: ImgInsertFnType) {
            NProgress.configure({ showSpinner: true, trickle: false })
            NProgress.start()
            let fd = new FormData()
            fd.append('file', file)
            fileUpload(fd, {}, props.fileForceLocal, {
                onUploadProgress: (evt) => {
                    NProgress.set(evt.progress!)
                },
            }).then((res) => {
                if (res.code == 1) {
                    insertFn(res.data.file.full_url, res.data.file.name, res.data.file.full_url)
                }
                NProgress.done()
            })
        },
    }

    // 视频上传配置
    state.editorConfig.MENU_CONF['uploadVideo'] = {
        fieldName: 'file',
        async customUpload(file: File, insertFn: VideoInsertFnType) {
            NProgress.configure({ showSpinner: true, trickle: false })
            NProgress.start()
            let fd = new FormData()
            fd.append('file', file)
            fileUpload(fd, {}, props.fileForceLocal, {
                onUploadProgress: (evt) => {
                    NProgress.set(evt.progress!)
                },
            }).then((res) => {
                if (res.code == 1) {
                    insertFn(res.data.file.full_url)
                }
                NProgress.done()
            })
        },
    }

    state.editorStyle.height = props.height
    state.editorStyle['overflow-y'] = 'hidden'
    state.mounted = true
})

// 组件销毁时，也及时销毁编辑器
onBeforeUnmount(() => {
    if (editorRef.value == null) return
    editorRef.value.destroy()
})

const handleCreated = (editor: any) => {
    editorRef.value = editor // 记录 editor 实例
}

const handleChange = () => {
    emits('update:modelValue', editorRef.value.getHtml())
}

const getRef = () => {
    return editorRef.value
}

defineExpose({
    getRef,
})

watch(
    () => props.modelValue,
    (newVal) => {
        state.value = !newVal ? '<p></p>' : newVal
    }
)
</script>

<style scoped lang="scss">
.ba-editor {
    border: 1px solid var(--el-border-color-lighter);
    z-index: 9999;
    :deep(.w-e-scroll) {
        scrollbar-width: none;
        &::-webkit-scrollbar {
            width: 5px;
        }
        &::-webkit-scrollbar-thumb {
            background: #eaeaea;
            border-radius: var(--el-border-radius-base);
            box-shadow: none;
            -webkit-box-shadow: none;
        }
        &:hover {
            &::-webkit-scrollbar-thumb:hover {
                background: #c8c9cc;
            }
        }
    }
}
.wangeditor-toolbar {
    border-bottom: 1px solid var(--el-border-color-lighter);
}

// 暗黑样式
@at-root .dark {
    .ba-editor {
        --w-e-textarea-bg-color: var(--ba-bg-color-overlay);
        --w-e-toolbar-bg-color: var(--ba-bg-color-overlay);
        --w-e-toolbar-border-color: var(--el-color-info-light-3);
    }
}
</style>
