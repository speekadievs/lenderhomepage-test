<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <router-link :to="{name: 'index'}" class="navbar-brand">
                LenderHomePage
            </router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name: 'index'}">
                            Home
                            <span class="sr-only">(current)</span>
                        </router-link>
                    </li>

                    <template v-if="!user">
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{name: 'auth.login'}">
                                Login
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{name: 'auth.register'}">
                                Register
                            </router-link>
                        </li>
                    </template>
                    <template v-else>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link" @click="logout">
                                Logout
                            </a>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        computed: {
            ...mapState(['user'])
        },

        methods: {
            logout() {
                this.$auth.destroyToken();

                window.location.reload();
            }
        }
    }
</script>
