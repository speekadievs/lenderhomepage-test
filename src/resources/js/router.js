import Vue from 'vue';
import {store} from './store'
import VueRouter from 'vue-router'

import Layout from './views/Layout.vue';
import Login from './views/auth/Login.vue';
import Register from './views/auth/Register.vue';
import Index from './views/teams/Index.vue';
import Create from './views/teams/Create.vue';
import Show from './views/teams/Show.vue'

import PlayerTable from './views/teams/players/Table'
import CreatePlayer from './views/teams/players/Create'

Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'index',
            component: Layout,
            meta: {
                authenticatedOnly: true
            },
            children: [
                {
                    path: '/teams',
                    name: 'teams.index',
                    component: Index,
                },
                {
                    path: '/teams/create',
                    name: 'teams.create',
                    component: Create
                },
                {
                    path: '/teams/:id/edit',
                    name: 'teams.edit',
                    component: Create
                },
                {
                    path: '/teams/:id',
                    component: Show,
                    children: [
                        {
                            path: '/teams/:id',
                            name: 'teams.show',
                            component: PlayerTable
                        },
                        {
                            path: '/teams/:id/players/create',
                            name: 'players.create',
                            component: CreatePlayer
                        },
                        {
                            path: '/teams/:id/players/:playerId/edit',
                            name: 'players.edit',
                            component: CreatePlayer
                        }
                    ]
                }
            ]
        },
        {
            path: '/auth',
            name: 'auth',
            component: Layout,
            meta: {
                guestOnly: true
            },
            children: [
                {
                    path: '/login',
                    name: 'auth.login',
                    component: Login,
                },
                {
                    path: '/register',
                    name: 'auth.register',
                    component: Register,
                },
            ]
        },
    ]
});

router.beforeEach(async (to, from, next) => {
    // Token expiration check.
    await Vue.auth.getAccessToken();

    let isAuthenticated = Vue.auth.isAuthenticated();

    if (isAuthenticated) {
        await store.dispatch('getUser')
    } else {
        store.commit('updateData', {object: 'userLoaded', data: true});
    }

    // Guest accessing route that requires to be logged in.
    if (!isAuthenticated && to.matched.some(record => record.meta.authenticatedOnly) && to.name !== 'auth.login') {
        return next({name: 'auth.login'})
    }

    // Logged in user accessing route that is available to guests only.
    if (isAuthenticated && to.matched.some(record => record.meta.guestOnly)) {
        return next({name: 'index'})
    }

    if (to.name === 'index') {
        return next({name: 'teams.index'})
    }

    if (to.name === 'auth') {
        return next({name: 'auth.login'})
    }

    let meta = {};
    to.matched.forEach(record => {
        if (record.meta) {
            for (let key in record.meta) {
                if (record.meta.hasOwnProperty(key)) {
                    meta[key] = record.meta[key];
                }
            }
        }
    });

    next({
        meta: meta
    })
});
