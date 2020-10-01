import Fee from '../../services/Fee';
import { store } from '../../store';

const state = {

	
};

const getters = {
	
};

const actions = {
	
	delete({ commit }, id) {
		return Fee.delete(id).then(data => {});
	},
	store({ commit }, inputs) {
		
		return Fee.store(inputs).then(data => {});
	},
	update({ commit }, inputs) {
		
		return Fee.update(inputs).then(data => {});
	},
	// updateStatus({ commit }, inputs) {
		
	// 	return Admin.updateStatus(inputs).then(data => {});
	// },
	// search({ commit }, inputs) {
	// 	return Admin.search(inputs).then(data => {
	// 		commit('subscriptions', data);
	// 	});
	// },
	// destroySubscriptions({ commit }, id) {
	// 	return Subscriptions.destroy(id).then(data => {
	// 		commit('destroySubscriptions', id);
			
	// 	});
	// },
	// resetSearch({ commit }, event) {
	// 	return Admin.event(event).then(data => {
	// 		commit('subscriptions', data);
	// 	});
	// },
	
};

const mutations = {
	// index (state, data) {
	// 	state.index = data;
	// },
	// subscriptions (state, data) {
	// 	state.subscriptions = data;
	// },
	// subscription (state, data) {
	// 	state.subscription = data;
	// },
	
	// destroySubscriptions (state, id) {
	// 	let i = state.subscriptions.organizations.map(item => item.subscriptions[0].id).indexOf(id);
	// 	state.subscriptions.organizations.splice(i, 1);
	// }
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}