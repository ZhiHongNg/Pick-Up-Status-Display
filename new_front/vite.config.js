import {defineConfig} from 'vite'
import uni from '@dcloudio/vite-plugin-uni'
import {resolve} from 'path'

const pathResolve = (dir) => {
  return resolve(__dirname, ".", dir)
}

const alias = {
  '@': pathResolve("src")
}
export default defineConfig({
  plugins: [
    uni(),
  ],
  resolve: {
    alias: alias
  },
})
