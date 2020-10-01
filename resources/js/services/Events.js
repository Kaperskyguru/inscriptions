import Vue from 'vue';
import axios from 'axios';

export default {
	getEvents() {
		return axios.get(`/api/v1/events`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},
}