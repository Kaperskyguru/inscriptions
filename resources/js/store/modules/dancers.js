import Dancers from '../../services/Dancers';
import { store } from '../../store';

const state = {
	dancers: [],
	lastDancer: null,
	check:false,
	results: []
};

const getters = {
	dancers () {
		return state.dancers
	},
	results () {
		return state.results
	},
	check () {
		return state.check
	},
	lastDancer () {
		return state.lastDancer
	}
};

const actions = {
	store({ commit }, inputs) {
		return Dancers.store(inputs).then((data) => {
			commit('pushDancer', data)
			commit('setLastDancer', data)
			if(!store.getters['auth/isAdmin']) {
				
			}
		});
	},
	getDancers({ commit }) {
		return Dancers.getDancers().then(data => commit('setDancers', data));
	},
	check({ commit }) {
		return Dancers.check().then(data => commit('check', data));
	},
	search({ commit }, inputs) {
		return Dancers.search(inputs).then(data => {
			commit('search', data);
		});
	},
	resetSearch({ commit }) {
		commit('resetSearch');
	},
	update({ commit }, inputs) {
		
		return Dancers.update(inputs).then(data => {
			commit('update', data);
		});
	},
	destroy({ commit }, id) {
		return Dancers.destroy(id).then(data => {
			// TODO Handle this
			if(!store.getters['auth/isAdmin']) {
				store.dispatch('subscriptions/getSubscriptions', false);
				commit('destroy', id);
			 }
			
		});
	}
};

const mutations = {
	setDancers (state, dancers) {
		state.dancers = dancers;
	},
	check (state, data) {
		state.check = data;
	},
	search (state, dancers) {
		state.results = dancers;
	},
	resetSearch (state) {
		state.results = [];
	},
	pushDancer (state, data) {
		state.dancers[state.dancers.length] = data.dancer;
	},
	setLastDancer (state, data) {
		state.lastDancer = data.dancer;
	},
	update (state, data) {
		let i = state.dancers.map(item => item.id).indexOf(data.dancer.id);
		state.dancers[i] =  data.dancer;
	},
	destroy (state, id) {
		let i = state.dancers.map(item => item.id).indexOf(id);
		state.dancers.splice(i, 1);
	}
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}