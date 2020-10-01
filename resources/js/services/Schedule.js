import Vue from 'vue';
import axios from 'axios';

export default {
	index (event) {
		return axios.get(`/api/v1/admin/${event}/schedule`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	getByPosition (event) {
		return axios.get(`/api/v1/admin/${event}/schedule/get-by-position`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	getItems(event) {
		return axios.get(`/api/v1/admin/${event}/schedule/get-items`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	update (data, id, saveOrder) {
		return axios.post(`/api/v1/admin/schedule/update/${id}`, data, id)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	updateAllScheduleItem (data) {
		return axios.post(`/api/v1/admin/schedule-item/updateAll`, data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	addToSchedule (data) {
		return axios.post(`/api/v1/admin/schedule-item/addToSchedule`, data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
	deleteCategory (id) {
		return axios.post(`/api/v1/admin/schedule-title/delete/${id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},	
}