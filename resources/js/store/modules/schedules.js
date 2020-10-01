import Schedule from '../../services/Schedule';
import { store } from '../../store';


const state = {
	schedule:[],
	items:[],
};

const getters = {
	schedule () {
		return state.schedule
	},
	items () {
		return state.items
	}
};

const actions = {
	index({ commit }, event) {
		return Schedule.index(event.city).then(data => {commit('schedule', data);});
	},
	getByPosition({ commit }, event) {
		return Schedule.getByPosition(event.city).then(data => {commit('schedule', data);});
	},
	getItems({ commit }, event) {
		return Schedule.getItems(event.city).then(data => {commit('items', data);});
	},
	update({ commit }, {inputs, id, saveOrder}) {
		return Schedule.update(inputs, id, saveOrder).then((data) => {
			
		});
	},
	updateAllScheduleItem({ commit }, inputs) {
		return Schedule.updateAllScheduleItem(inputs).then((data) => {
			
		});
	},
	
	addCategory ({ commit }, data) {
		commit('addCategory', data);
	},
	addScheduleItem ({ commit }, inputs) {
		return Schedule.addScheduleItem(inputs).then((data) => {
			
		});
	},
	setItems ({ commit }, data) {
		store.dispatch('loading/setLoading', false);
		commit('setItems', data);
	},
	deleteCategory ({ commit }, data) {
		var myData = data;
		return Schedule.deleteCategory(myData.id).then((data) => {
			store.dispatch('loading/setLoading', false);
			commit('deleteCategory', myData);
		});
	},
	
	updateScheduleDrag ({ commit }, data) {
		commit('updateScheduleDrag', data);
	},
	addToSchedule ({ commit }, inputs) {
		return Schedule.addToSchedule(inputs).then((data) => {
			
		});
	},
	cut ({ commit }, data) {
		commit('cut', data);
	},
	move ({ commit }, data) {
		commit('move', data);
	},
	
	
};

const mutations = {
	schedule (state, data) {
		state.schedule = data;
		
	},
	items (state, data) {
		state.items = Object.freeze(data);
		
	},
	
	addCategory (state, data){
		let item = {
			id: data.uuid,
			name: data.uuid,
			order:data.index,
			position: data.index,
			schedule_items: []
		  };
		state.schedule.splice(data.index + 1, 0, item);

	},
	setItems (state, data){
		
		state.items = Object.freeze(data);
		store.dispatch("loading/setLoading", false);



	},
	
	deleteCategory (state, data){
		let index = data.index;
		let cutRoutines = state.schedule[index]['schedule_items'].slice();
		if(cutRoutines.length > 0) {
			state.schedule[index]['schedule_items'] = [];
			let newRoutines = state.schedule[0]['schedule_items'].concat(cutRoutines)
			state.schedule[0]['schedule_items'] = newRoutines;
		}
		state.schedule.splice(index, 1);
	},
	cut (state, data){
		let index = data.index;
		let cutIndex = data.cutIndex + 1;
		let cutRoutines = state.schedule[index]['schedule_items'].slice(cutIndex)

		state.schedule[index]['schedule_items'] = state.schedule[index]['schedule_items'].slice(0, cutIndex);

		let item = {
			id: data.uuid,
			name: data.uuid,
			order:data.index,
			position:data.index,
			schedule_items: cutRoutines
		  };

		state.schedule.splice(index + 1, 0, item);

	},
	move (state, data){
		let index = data.index;
		let cutIndex = data.cutIndex;
		let cutRoutine = state.schedule[index]['schedule_items'].slice(cutIndex, cutIndex + 1);
		
		state.schedule[index]['schedule_items'].splice(cutIndex, 1);

		let destination = state.schedule.findIndex(x => x.id === data.destinationID);
		state.schedule[destination]['schedule_items'].push(cutRoutine[0]);
		

	},
	updateScheduleDrag: (state, payload) => {
		state.schedule = payload;
	}
	
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}