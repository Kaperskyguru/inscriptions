import Subscriptions from '../../services/Subscriptions';
import Routines from '../../services/Routines';
import { store } from '../../store';


const state = {
	currentSubscription:null,
	subscriptions: [],
};

const getters = {
	subscriptions () {
		return state.subscriptions
	},
	
	currentSubscription () {
		return state.currentSubscription
	}
};

const actions = {
	
	getSubscriptions({ commit }) {
		return Subscriptions.getSubscriptions().then(data => commit('setSubscriptions', data));
	},
	
	show({ commit }, id) {
		return Subscriptions.show(id).then(data => {
			commit('pushCurrentSubscription', data)
		});
	},
	store({ commit }, inputs) {
		return Subscriptions.store(inputs).then(data => commit('pushSubscription', data));
	},
	update({ commit }, {inputs, id}) {
		return Subscriptions.update(inputs, id).then(data => {
		   store.dispatch('subscriptions/getSubscriptions', false);
	   // 	//commit('update', data);
		});
   },
	destroyRoutine({ commit,dispatch }, id) {
		return Routines.destroy(id).then(data => {
			// TODO Handle this better
			if(!store.getters['auth/isAdmin']) {
				store.dispatch('subscriptions/getSubscriptions', false);
			 }
			
			commit('destroyRoutine', id);
		});
	}
};

const mutations = {
	setSubscriptions (state, subscriptions) {
		state.subscriptions = subscriptions;
	},
	setAutocompleteSubscriptions (state, subscriptions) {
		state.autocomplete = subscriptions;
	},
	pushSubscription (state, data) {
		state.subscriptions.push(data.subscription);
	},
	pushCurrentSubscription (state, data) {
		state.currentSubscription = null;
		state.currentSubscription = data.subscription;
	},
	destroyRoutine(state, id){
		let i = state.currentSubscription.routines.map(item => item.id).indexOf(id);
		state.currentSubscription.routines.splice(i, 1);
	}
	
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}