<template>
	<div>
		<div class="i-layout-page-header">
			<div class="i-layout-page-header">
				<span class="ivu-page-header-title">{{ $route.meta.title }}</span>
			</div>
		</div>
		<Card :bordered="false" dis-hover class="ivu-mt">
			<login-from v-if="isShowLogn" @on-Login="onLogin"></login-from>
			<div v-if="isShowList" class="backs" @click="goBack">
				<Icon type="ios-folder-outline" class="mr5" /><span>返回上级</span>
			</div>
			<Table v-if="isShowList" ref="selection" :columns="columns4" :data="tabList" :loading="loading"
				no-data-text="暂无数据" highlight-row class="mt20" @on-current-change="currentChange"
				no-filtered-data-text="暂无筛选结果">
				<template slot-scope="{ row }" slot="filename">
					<Icon type="ios-folder-outline" v-if="row.isDir" class="mr5" />
					<Icon type="ios-document-outline" v-else class="mr5" />
					<span>{{ row.filename }}</span>
				</template>
				<template slot-scope="{ row }" slot="isWritable">
					<span v-text="row.isWritable ? '是' : '否'"></span>
				</template>
				<template slot-scope="{ row, index }" slot="action">
					<a @click="open(row)" v-if="row.isDir">打开</a>
					<a @click="edit(row)" v-else>编辑</a>
				</template>
			</Table>
		</Card>
		<!-- <codemirror :rows='rows' :code='code' :modals='modals' :title='title'></codemirror> -->
		<Modal v-model="modals" scrollable footer-hide closable :title="title" :mask-closable="false" width="900">
			<Button type="primary" id="savefile" class="mr5 mb15" @click="savefile">保存</Button>
			<Button id="undo" class="mr5 mb15" @click="undofile">撤销</Button>
			<Button id="redo" class="mr5 mb15" @click="redofile">回退</Button>
			<Button id="refresh" class="mb15" @click="refreshfile">刷新</Button>
			<div class="file-box">
				<div class="file-left cm-s-ambiance CodeMirror">
					<Tree :data="navList" :render="renderContent" :load-data="loadData" expand-node></Tree>
				</div>
				<div class="file-content">
					<textarea ref="mycode" class="codesql public_text" v-model="code"></textarea>
				</div>
				<Spin size="large" fix v-if="spinShow"></Spin>
			</div>

		</Modal>
	</div>
</template>

<script>
	import { resolveComponent } from 'vue'
	import {
		opendirListApi,
		openfileApi,
		savefileApi,
		opendirLoginApi
	} from '@/api/system';
	import CodeMirror from 'codemirror/lib/codemirror';
	import loginFrom from './components/loginFrom';
	import codemirror from './components/codemirror';
	import 'codemirror/theme/ambiance.css';
	import {
		setCookies,
		getCookies,
		removeCookies
	} from '@/libs/util';


	// 核心样式
	// import 'codemirror/lib/codemirror.css'
	// 引入主题后还需要在 options 中指定主题才会生效
	import 'codemirror/theme/cobalt.css'

	// 需要引入具体的语法高亮库才会有对应的语法高亮效果
	// codemirror 官方其实支持通过 /addon/mode/loadmode.js 和 /mode/meta.js 来实现动态加载对应语法高亮库
	// 但 vue 貌似没有无法在实例初始化后再动态加载对应 JS ，所以此处才把对应的 JS 提前引入
	// import 'codemirror/mode/javascript/javascript.js'
	// import 'codemirror/mode/css/css.js'
	// import 'codemirror/mode/xml/xml.js'
	// import 'codemirror/mode/clike/clike.js'
	// import 'codemirror/mode/markdown/markdown.js'
	// import 'codemirror/mode/python/python.js'
	// import 'codemirror/mode/r/r.js'
	// import 'codemirror/mode/shell/shell.js'
	// import 'codemirror/mode/sql/sql.js'
	// import 'codemirror/mode/swift/swift.js'
	// import 'codemirror/mode/vue/vue.js'
	import 'codemirror/addon/edit/closebrackets.js'


	require('codemirror/mode/javascript/javascript');
	// import { resolveComponent } from 'vue'
	export default {
		name: 'opendir',
		data() {
			return {
				editor: '',
				isShowLogn: false, // 登录
				isShowList: false, // 登录之后列表
				code: '',
				modals: false,
				spinShow: false,
				loading: false,
				tabList: [],
				columns4: [{
						title: '文件/文件夹名',
						slot: 'filename',
						minWidth: 150,
						back: '返回上级',
					},
					{
						title: '文件/文件夹路径',
						key: 'real_path',
						minWidth: 150,
					},
					{
						title: '文件/文件夹大小',
						key: 'size',
						minWidth: 100,
					},
					{
						title: '是否可写',
						slot: 'isWritable',
						minWidth: 100,
					},
					{
						title: '更新时间',
						key: 'mtime',
						minWidth: 150,
					},
					{
						title: '操作',
						slot: 'action',
						minWidth: 150,
					},
				],
				formItem: {
					dir: '',
					superior: 0,
					filedir: '',
				},
				rows: {},
				pathname: '',
				title: '',
				navList: []
			};
		},
		components: {
			loginFrom,
			codemirror
		},
		mounted() {
			this.editor = CodeMirror.fromTextArea(this.$refs.mycode, {
				value: 'http://www.crmeb.com', // 文本域默认显示的文本
				mode: 'text/javascript',
				theme: 'ambiance', // CSS样式选择
				indentUnit: 8, // 缩进单位，默认2
				smartIndent: true, // 是否智能缩进
				tabSize: 4, // Tab缩进，默认4
				readOnly: false, // 是否只读，默认false
				showCursorWhenSelecting: true,
				lineNumbers: true, // 是否显示行号
				lineWrapping: true,   //内容超过编辑器的宽时，应该滚动显示还是换行显示
				autoCloseBrackets:true,   //代码自动补全
				indentWithTabs: true,
				matchBrackets: true,
				extraKeys: {
					'Ctrl': 'autocomplete'
				}, //自定义快捷键
				gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter', 'CodeMirror-lint-markers'],
			});
			// 初始化
			// this._initialize()

			//代码自动提示功能，记住使用cursorActivity事件不要使用change事件，这是一个坑，那样页面直接会卡死
			editor.on('cursorActivity', function() {
				editor.showHint()
			})
		},
		created() {
			this.getList();
			// this.onIsLogin();
		},
		methods: {
			
			
			
			// 点击行
			currentChange(currentRow) {
				if (currentRow.isDir) {
					this.open(currentRow);
				} else {
					this.edit(currentRow);
				}
			},
			// 列表
			getList() {
				this.loading = true;
				opendirListApi(this.formItem)
					.then(async (res) => {
						let data = res.data;
						this.tabList = data.list;
						this.navList = data.navList
						this.dir = data.dir;
						this.isShowLogn = false;
						this.isShowList = true;
						this.loading = false;
					})
					.catch((res) => {
						if (res.status == 110008) {
							this.$Message.error(res.msg);
							this.isShowLogn = true;
							this.isShowList = false;
							this.loading = false;
						} else {
							this.loading = false;
							this.$Message.error(res.msg);
						}

					});
			},
			// 返回上级
			goBack() {
				this.formItem = {
					dir: this.dir,
					superior: 1,
					filedir: '',
				};
				this.getList();
			},
			// 打开
			open(row) {
				this.rows = row;
				this.formItem = {
					dir: row.path,
					superior: 0,
					filedir: row.filename,
				};
				this.getList();
			},
			// 编辑
			edit(row) {

				this.spinShow = true;
				this.pathname = row.pathname;
				this.title = row.filename;
				openfileApi(row.pathname)
					.then(async (res) => {
						let data = res.data;
						this.code = res.data.content;
						if(data.mode) this.editor.setOption(data.mode);
						this.editor.setValue(this.code);
						this.editor.refresh();
						this.modals = true;
						this.spinShow = false;
					})
					.catch((res) => {
						this.spinShow = false;
						this.$Message.error(res.msg);
					});
			},
			// 保存
			savefile() {
				let data = {
					comment: this.editor.getValue(),
					filepath: this.pathname,
				};
				savefileApi(data)
					.then(async (res) => {
						this.$Message.success(res.msg);
						this.modals = false;
					})
					.catch((res) => {
						this.$Message.error(res.msg);
					});
			},
			// 撤销
			undofile() {
				this.editor.undo();
			},
			redofile() {
				this.editor.redo();
			},
			// 刷新
			refreshfile() {
				this.editor.refresh();
			},

			// 查看是否登录
			onIsLogin() {
				this.spinShow = true;
				let file_login_status = window.localStorage.getItem("file_login_status"); //保存数据
				if (file_login_status) {
					this.getList();
				} else {
					this.isShowLogn = true;
					this.spinShow = false;
					this.isShowList = false;
				}


			},
			// 登录跳转
			onLogin(data) {
				let expires = this.getExpiresTime(data.expires_time);
				// 记录用户登陆信息
				setCookies('file_token', data.token, expires);
				this.getList();
			},
			getExpiresTime(expiresTime) {
				let nowTimeNum = Math.round(new Date() / 1000);
				let expiresTimeNum = expiresTime - nowTimeNum;
				return parseFloat(parseFloat(parseFloat(expiresTimeNum / 60) / 60) / 24);
			},
			// 侧边栏异步加载
			loadData (item, callback) {
				if(item.isDir)
				{
					this.formItem = {
						dir: item.path,
						superior: 0,
						filedir: item.title,
					};
					opendirListApi(this.formItem)
						.then(async (res) => {
							callback(res.data.navList);
						})
						.catch((res) => {
							if (res.status == 110008) {
								this.$Message.error(res.msg);
								this.isShowLogn = true;
								this.isShowList = false;
								this.loading = false;
							} else {
								this.loading = false;
								this.$Message.error(res.msg);
							}
						});
				}
			},
			// 自定义显示
			renderContent (h, { root, node, data }) {
				return h('span', {
					style: {
						display: "inline-block",
						cursor: "pointer",
						userSelect: 'none'
					},
					on: {
						click: () => {
						  this.clickDir(data);
						}
					}
				},data.title);
			},
			clickDir(data){
				if(!data.isDir)
				{
					openfileApi(data.pathname)
						.then(async (res) => {
							let data = res.data;
							this.code = res.data.content;
							if(data.mode) this.editor.setOption(data.mode);
							this.editor.setValue(this.code);
							this.editor.refresh();
						})
						.catch((res) => {
							this.spinShow = false;
							this.$Message.error(res.msg);
						});
				}
			}
			
		},
	};
</script>
<style scoped lang="stylus">
.mt20
    >>>.ivu-icon-ios-folder-outline
       font-size 14px !important
    >>> .ivu-icon-ios-document-outline
       font-size 18px !important
    >>> .ivu-table-row
       cursor pointer
.mr5
   margin-right 5px
.backs
   cursor pointer
   display inline-block
>>>.CodeMirror
 height: 70vh !important
 
.file-box
	display: flex
	align-items: center
	justify-content: space-between
.file-box
	.file-left
		width:25%
		max-width: 400px
		overflow: auto
.file-box
	.file-content
		flex: 75%
		overflow: hidden
</style>
