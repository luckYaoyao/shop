import Vue from 'vue';
import VueI18n from 'vue-i18n'


Vue.use(VueI18n)

let lang = '';
// #ifdef MP || APP-PLUS
lang = uni.getStorageSync('locale') != '' ? uni.getStorageSync('locale') : 'zh-CN';
// #endif
// #ifdef H5
lang = uni.getStorageSync('locale') != '' ? uni.getStorageSync('locale') : navigator.language;
// #endif
const i18n = new VueI18n({
	locale: lang ? lang : 'zh-CN',
	messages: uni.getStorageSync('localeJson')
})
export default i18n
