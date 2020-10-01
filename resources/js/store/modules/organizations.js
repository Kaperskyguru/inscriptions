import Organization from '../../services/Organization';

const state = {
};

const getters = {
	
};

const actions = {
	update({ commit }, inputs) {
		return Organization.update(inputs).then((data) => {
			
		});
	},
	
};

const mutations = {
	
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}