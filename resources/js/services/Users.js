import Vue from 'vue';
import axios from 'axios';

export default {
	update (data) {
		return axios.post('/api/v1/users/update', data)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));		
	},
}