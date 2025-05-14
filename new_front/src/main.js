import {createSSRApp} from "vue";
import App from "./App.vue";
import './utils/index.scss'
import './utils/order.scss'
import './utils/uni.css'
import * as Pinia from 'pinia';
// uni.loadFontFace({
// 	family: 'Bitstream Vera Serif Bold',
// 	source: 'url("https://sungd.github.io/Pacifico.ttf")',
// 	success() {
// 		console.log('success')
// 	}
// })
export function createApp() {
	const app = createSSRApp(App);
	app.use(Pinia.createPinia());

	return {
		app,
	};
}
