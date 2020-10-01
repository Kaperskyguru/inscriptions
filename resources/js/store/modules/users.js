import Users from '../../services/Users';
import { store } from '../../store';


const state = {
	users: []
};

const getters = {
	users () {
		return state.users
	}
};

const actions = {
	update({ commit }, inputs) {
		
		return Users.update(inputs).then(data => {
			commit('update', data);
		});
	},
};

const mutations = {
	setUsers (state, users) {
		state.users = users;
	},
	removeUser (state, id) {
		let i = state.users.map(item => item._id).indexOf(id);
		state.users.splice(i, 1);
	},
	update(state,data) {
		console.log(data);
		store.commit('auth/setLoggedIn', true);
		//commit('setUser', data.user);
	}
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}