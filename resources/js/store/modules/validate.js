import Auth from '../../services/Auth';

const state = {
	step: 1,
	totalStep: 3
	
};

const getters = {
	getStep () {
		return state.step;
	},
	
};

const actions = {
	nextStep({ commit }, credentials) {
		return Auth.validateStep(credentials, state.step).then((data) => {
			commit('nextStep');
		});
		
	},
	prevStep({ commit }) {
		commit('prevStep');
	},
	resetStep({ commit }) {
		commit('resetStep');
	},
	
	
};

const mutations = {
	nextStep (state) {
		if(state.step < state.totalStep){
			state.step += 1;
		}
		
	},
	prevStep (state) {
		if(state.step > 1) {
			state.step -= 1;
		}
		
	},
	resetStep (state) {
		state.step = 1;
	}
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}