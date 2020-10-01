import Admin from '../../services/Admin';
import Subscriptions from '../../services/Subscriptions';
import { store } from '../../store';

const state = {
	index: null,
	subscriptions:null,
	subscription:null
	
};

const getters = {
	index () {
		return state.index
	},
	subscriptions () {
		return state.subscriptions
	},
	subscription () {
		return state.subscription
	},
	schedule () {
		return state.schedule
	}
};

const actions = {
	index({ commit }) {
		return Admin.index().then(data => commit('index', data));
	},
	subscriptions({ commit }, event) {
		return Admin.event(event).then(data => commit('subscriptions', data));
	},
	subscription({ commit }, args) {

		return Admin.subscription(args.event, args.subscription_id).then(data => commit('subscription', data));
	},
	addPayment({ commit }, inputs) {
		
		return Admin.addPayment(inputs).then(data => {});
	},
	updatePayment({ commit }, inputs) {
		
		return Admin.updatePayment(inputs).then(data => {});
	},
	deletePayment({ commit }, id) {
		
		return Admin.deletePayment(id).then(data => {});
	},
	updateStatus({ commit }, inputs) {
		
		return Admin.updateStatus(inputs).then(data => {});
	},
	search({ commit }, inputs) {
		return Admin.search(inputs).then(data => {
			commit('subscriptions', data);
		});
	},
	destroySubscriptions({ commit }, id) {
		return Subscriptions.destroy(id).then(data => {
			commit('destroySubscriptions', id);
			
		});
	},
	resetSearch({ commit }, event) {
		return Admin.event(event).then(data => {
			commit('subscriptions', data);
		});
	},
	
};

const mutations = {
	index (state, data) {
		state.index = data;
	},
	subscriptions (state, data) {
		state.subscriptions = data;
	},
	subscription (state, data) {
		state.subscription = data;
	},
	
	destroySubscriptions (state, id) {
		let i = state.subscriptions.organizations.map(item => item.subscriptions[0].id).indexOf(id);
		state.subscriptions.organizations.splice(i, 1);
	}
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}