import Vue from 'vue';
import Vuex from 'vuex';

//Modules
import users from './modules/users';
import auth from './modules/auth';
import loading from './modules/loading';
import feedback from './modules/feedback';
import validate from './modules/validate';
import organizations from './modules/organizations';
import dancers from './modules/dancers';
import events from './modules/events';
import subscriptions from './modules/subscriptions';
import routines from './modules/routines';
import admin from './modules/admin';
import states from './modules/states';
import schedules from './modules/schedules';
import fees from './modules/fees';

Vue.use(Vuex);

const state = {

};

const getters = {

};

const actions = {

};

const mutations = {
	
};

export const store = new Vuex.Store({
  state,
	actions,
	mutations,
  getters,
  modules: {
    users,
    auth,
    loading,
    feedback,
    validate,
    organizations,
    dancers,
    events,
    subscriptions,
    routines,
    admin,
    states,
    schedules,
    fees,
  }
});

