import axios from 'axios';

export default {
	delete(id) {
		return axios.delete(`/api/v1/admin/fee/delete/${id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	store (data) {
		return axios.post('/api/v1/admin/fee/add', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	update (data) {
		return axios.post('/api/v1/admin/fee/update', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
	
}