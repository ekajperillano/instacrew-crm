import * as download from 'downloadjs';
export default class request {

	constructor(app, container = 'instacrewMain', withLoader = true) {
		this.app = app;
		this.container = container;
		this.withLoader = withLoader;
	}

    download(url, filename, mimeType = null) {
		return new Promise((resolve, reject) => {
			this.app.$http.get(url, { responseType: 'blob' }).then(res => {
				download(res.data, filename, mimeType);
				resolve(['sucess', null]);
			}, err => { 
				resolve([null, ['Problem downloading file.'] ]);
			});
		});
	}
	
	image(url) {
		return new Promise((resolve, reject) => {
			this.app.$http.get(url, {responseType: 'arraybuffer'}).then(res => {
				resolve([res.data, null]);
			}, err => {
				let errors = (err.response.data.errors) ? err.response.data.errors : ['Problem on viewing the image.'];
				resolve([null, errors]);
			});
		});
	}

	export(url, filename) {
		this.app.$http.get(url).then(res => {
			download(res.data, filename);
		}, err => {});
	}

	get(url, params = null) {
		let loader;
		if(this.withLoader) {
			loader = this.app.$loading.show({
				container : this.app.$refs[this.container]
			});
		}
		

		return new Promise((resolve, reject) => {
			this.app.$http.get(url, {params}).then(res => {
				if(this.withLoader) {
					loader.hide();
				}
				resolve([res.data.data, null]);
			}, err => {
				if(this.withLoader) {
					loader.hide();
				}

				const errrors = err.response.data.errors || ['Problem connecting to server.'];

				resolve([null, err.response.data.errors]);
			});
		});
	}

	post(url, data) {
		let loader = this.app.$loading.show({
    		container : this.app.$refs[this.container]
  		});

		return new Promise((resolve, reject) => {
			this.app.$http.post(url, data).then(res => {
				loader.hide();
				resolve([res.data.data, null]);
			}, err => {
				loader.hide();
				resolve([null, err.response.data.errors]);
			});
		});
	}

	put(url, data) {
		let loader = this.app.$loading.show({
    		container : this.app.$refs[this.container]
  		});

		return new Promise((resolve, reject) => {
			this.app.$http.patch(url, data).then(res => {
				loader.hide();
				resolve([res.data.data, null]);
			}, err => {
				loader.hide();
				resolve([null, err.response.data.errors]);
			});
		});
	}

	patch(url, data) {
		let loader = this.app.$loading.show({
    		container : this.app.$refs[this.container]
  		});

		return new Promise((resolve, reject) => {
			this.app.$http.patch(url, data).then(res => {
				loader.hide();
				resolve([res.data.data, null]);
			}, err => {
				loader.hide();
				resolve([null, err.response.data.errors]);
			});
		});
	}

	delete(url) {
		let loader = this.app.$loading.show({
    		container : this.app.$refs[this.container]
  		});

		return new Promise((resolve, reject) => {
			this.app.$http.delete(url).then(res => {
				loader.hide();
				resolve([res.data.status, null]);
			}, err => {
				loader.hide();
				resolve([null, err.response.data.errors]);
			});
		});
	}
}
