import Vue from 'vue';
import axios from 'axios';

export default {
	
	getByCountryID (country_id) {
		return axios.get(`/api/v1/states/getByCountryID/${country_id}`)
			.then(response => response.data)
			.catch(error => Promise.reject(error.response));
	},	
}