import Vue from 'vue';
import axios from 'axios';

export default {
	store(data) {
		return axios.post('/api/v1/routines/store', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	create() {
		return axios.get('/api/v1/routines/create')
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	edit(id) {
		return axios.get(`/api/v1/routines/edit/${id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	update (data, id) {
		return axios.post(`/api/v1/routines/update/${id}`, data, id)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	upload (data) {
		return axios.post(`/api/v1/routines/uploadSong`, data, {
			headers: { 'content-type': 'multipart/form-data' }
		})
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	destroy(id) {
		return axios.delete(`/api/v1/routines/${id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	getRoutines() {
		return axios.get(`/api/v1/routines`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	getSubscriptionRoutines(id) {
		return axios.post(`/api/v1/routines/subscription/${id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
}