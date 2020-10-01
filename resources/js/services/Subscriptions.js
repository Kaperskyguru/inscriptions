import Vue from 'vue';
import axios from 'axios';

export default {
	getSubscriptions() {
		return axios.get(`/api/v1/subscriptions`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	getAllSubscriptions() {
		return axios.get(`/api/v1/subscriptions/all`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	show(id) {
		return axios.get(`/api/v1/subscriptions/${id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	destroy(id) {
		return axios.delete(`/api/v1/subscriptions/${id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	update (data, id) {
		return axios.post(`/api/v1/subscriptions/update/${id}`, data, id)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	store(data) {
		return axios.post('/api/v1/subscriptions/store', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
}