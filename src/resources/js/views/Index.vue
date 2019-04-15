<template>
    <div class="card">
        <div class="card-body">
            <div class="loading" v-if="loading"></div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                list: [],
                meta: {
                    previous_page: 0,
                    current_page: 1,
                    next_page: 0,
                    query: ''
                },
                query: ''
            }
        },

        created() {
            this.load();
        },

        methods: {
            async load(page) {
                this.loading = true;

                this.loading = false;
            },

            search() {
                this.meta.previous_page = 0;
                this.meta.next_page = 0;
                this.meta.current_page = 1;
                this.meta.query = this.query;

                this.load();
            }
        }
    }
</script>
