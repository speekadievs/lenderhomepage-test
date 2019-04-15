<template>
    <div>
        <template v-if="userLoaded">
            <main-header />

            <div class="container mt-5">
                <div v-if="user" class="text-center">
                    Welcome back {{ user.name }}
                </div>

                <router-view></router-view>
            </div>
        </template>
        <template v-else>
            <div class="loading"></div>
        </template>
    </div>
</template>

<script>
    import MainHeader from '../components/MainHeader.vue';
    import {mapState} from 'vuex';

    export default {
        components: {
            MainHeader
        },

        computed: {
            ...mapState(['userLoaded', 'user'])
        }
    }
</script>
