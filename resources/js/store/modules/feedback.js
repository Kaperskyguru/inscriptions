const state = {
	feedback: {
		message: false,
		type: false
	}	
};

const getters = {
	feedback (state, getters, rootState, rootGetters) {
		return state.feedback;
	},
	feedbackClass () {
		const type = state.feedback.type;
		return `alert alert-${type}`;
	}
};

const actions = {
	setFeedback({ commit }, data) {
		commit('setFeedback', data);
	},
	setDelayedFeedback({ commit }, data) {
		setTimeout(() => {
			commit('setFeedback', data);
		}, data.delay || 10);
	},
	clearFeedback({ commit }) {
		commit('clearFeedback');
	}
};

const mutations = {
	setFeedback (state, data) {
		state.feedback.message = data.message;
		state.feedback.type = data.type;
		let layout = document.getElementById('layout-dashboard');
		
		if(layout !== null) {
			layout.scrollTop = 0;
		}
		
	},
	clearFeedback (state) {
		state.feedback.message = false,
		state.feedback.type = false;
	}
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}