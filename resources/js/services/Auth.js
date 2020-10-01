import Vue from 'vue';
import axios from 'axios';

export default {
	register (data) {
		return axios.post('/api/v1/auth/register', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	login (data) {
		return axios.post('/api/v1/auth/login', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	update (data) {
		return axios.post('/api/v1/auth/update', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	authenticate () {
		return axios.get('/api/v1/auth/authenticate')
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	refresh (token) {
		return axios.post('/api/v1/auth/refresh', { token: token })
		  .then(response => response.data)
		  .catch(error => Promise.reject(error.response));
	},
	validateStep (data, step) {
		return axios.post(`/api/v1/auth/validateStep/${step}`, data)
		  .then(response => response.data)
		  .catch(error => Promise.reject(error.response));
	},
	requestResetPassword (data) {
		return axios.post(`/api/v1/auth/reset-password`, data)
		  .then(response => response.data)
		  .catch(error => Promise.reject(error.response));
	},
	resetPassword (data) {
		return axios.post(`/api/v1/auth/reset/password`, data)
		  .then(response => response.data)
		  .catch(error => Promise.reject(error.response));
	}
}