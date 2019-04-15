import API from './classes/Api'

import Vue from 'vue';
import Vuex from 'vuex'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        userLoaded: false,
        user: null
    },

    mutations: {
        updateData(state, {object, data}) {
            state[object] = data
        },
    },

    actions: {
        async getUser({commit, state}, forceUpdate = false) {
            if (state.user && state.user.id && !forceUpdate) {
                return
            }

            const user = await API.getUser();

            commit('updateData', {object: 'user', data: user.data});

            commit('updateData', {object: 'userLoaded', data: true});
        },

        removeUser({commit}) {
            commit('updateData', {object: 'user', data: null})
        }
    }
});
