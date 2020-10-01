import Events from '../../services/Events';

const state = {
	events: []
};

const getters = {
	events () {
		return state.events
	}
};

const actions = {
	
	getEvents({ commit }) {
		return Events.getEvents().then(data => commit('setEvents', data));
	},
	removeEvent({ commit }, id) {
		commit('removeEvent', id);
	},
};

const mutations = {
	setEvents (state, events) {
		state.events = events;
	},
	removeEvent (state, id) {
		let i = state.events.map(item => item.id).indexOf(id);
		state.events.splice(i, 1);
	}
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}