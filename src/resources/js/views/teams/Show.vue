<template>
    <div>
        <div class="loading" v-if="loading"></div>

        <template v-if="team">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col my-auto">
                            <h1>{{ team.name }}</h1>
                        </div>
                        <div class="col text-right">
                            <img :src="team.image_url" style="width: 80px;">
                        </div>
                    </div>
                </div>
            </div>

            <router-view></router-view>
        </template>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                team: null
            }
        },

        async created() {
            this.loading = true;

            try {
                let response = await this.$api.getTeam(this.$route.params.id);

                this.$set(this, 'team', response.data);
            } catch (e) {
                console.error(e);

                this.$router.push({
                    name: 'teams.index'
                });
            }

            this.loading = false;
        },
    }
</script>
