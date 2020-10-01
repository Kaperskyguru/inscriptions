import Vue from 'vue';
import axios from 'axios';

export default {
	store(data) {
		return axios.post('/api/v1/dancers/store', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	update (data) {
		return axios.post('/api/v1/dancers/update', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	search (data) {
		return axios.post('/api/v1/dancers/search', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	destroy(id) {
		return axios.delete(`/api/v1/dancers/${id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	getDancers() {
		return axios.get(`/api/v1/dancers`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	check() {
		return axios.get(`/api/v1/dancers/check`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
}