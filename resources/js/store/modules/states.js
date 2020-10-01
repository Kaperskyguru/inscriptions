import States from '../../services/States';

const state = {
	states:[]
};

const getters = {
	getStates () {
		return state.states
	}
};

const actions = {
	getByCountryID({ commit }, country_id) {
		return States.getByCountryID(country_id).then(data => commit('setStates', data));
	},
	
};

const mutations = {
	setStates (state, data) {
		state.states = data;
	},
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}