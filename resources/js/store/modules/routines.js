import Routines from '../../services/Routines';
import { store } from '../../store';

const state = {
	routines: [],
	subscriptionRoutines: [],
	routineData:null
};

const getters = {
	routines () {
		return state.routines
	},
	subscriptionRoutines () {
		return state.subscriptionRoutines
	},
	getRoutineData () {
		return state.routineData
	}
};

const actions = {
	store({ commit, dispatch }, inputs) {
		return Routines.store(inputs).then(data => {
			if(!store.getters['auth/isAdmin']) {
				commit('pushRoutine', data)
				store.dispatch('subscriptions/getSubscriptions', false);
			}
			
		});
	},
	create({ commit }) {
		return Routines.create().then(data => commit('create', data));
	},
	edit({ commit }, id) {
		return Routines.edit(id).then(data => commit('edit', data));
	},
	getRoutines({ commit }) {
		return Routines.getRoutines().then(data => commit('setRoutines', data));
	},
	getSubscriptionRoutines({ commit }, id) {
		return Routines.getSubscriptionRoutines(id).then(data => commit('setSubscriptionRoutines', data));
	},
	resetSubscriptionRoutines({ commit }) {
		commit('resetSubscriptionRoutines');
	},
	update({ commit, getters}, {inputs, id}) {
		 return Routines.update(inputs, id).then(data => {
			 if(!store.getters['auth/isAdmin']) {
				store.dispatch('subscriptions/getSubscriptions', false);
			 }
			
		// 	//commit('update', data);
		 });
	},
	upload({ commit, getters}, inputs) {
		return Routines.upload(inputs).then(data => {
			// if(!store.getters['auth/isAdmin']) {
			//    store.dispatch('subscriptions/getSubscriptions', false);
			// }
		   
	   // 	//commit('update', data);
		});
   },
	destroy({ commit }, id) {
		return Routines.destroy(id).then((data) => {
			if(!store.getters['auth/isAdmin']) {
				commit('destroy', id);
			 }
		});
	}
};

const mutations = {
	setRoutines (state, routines) {
		state.routines = routines;
	},
	setSubscriptionRoutines (state, routines) {
		state.subscriptionRoutines = routines;
	},
	resetSubscriptionRoutines (state) {
		state.subscriptionRoutines = [];
	},
	create (state, data) {
		state.routineData = data;
		
	},
	edit (state, data) {
		state.routineData = data;
		
	},
	pushRoutine (state, data) {
		state.routines[state.routines.length] = data.routine;
	},
	update (state, data) {
		let i = state.routines.map(item => item.id).indexOf(data.routine.id);
		state.routines[i] =  data.routine;
	},
	destroy (state, id) {
		//console.log(rootGetters);
		//let i = state.routines.map(item => item.id).indexOf(id);
		//state.routines.splice(i, 1);
	}
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}