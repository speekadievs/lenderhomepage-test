<template>
    <div class="card">
        <div class="card-body">
            <form @submit.prevent="submit">
                <div v-if="success.visible" class="alert alert-success">
                    {{ success.message }}
                </div>

                <div v-if="alert.visible" class="alert alert-danger">
                    {{ alert.message }}
                </div>

                <l-input v-model="username">
                    Email
                </l-input>

                <l-input type="password" v-model="password">
                    Password
                </l-input>

                <div class="form-group">
                    <button class="btn mt-4 btn-primary" :disabled="loading">
                        <template v-if="loading">
                            <i class="fa fa-refresh fa-spin"></i>
                        </template>
                        <template v-else>
                            Log In
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                username: '',
                password: '',

                alert: {
                    visible: false,
                    message: ''
                },

                success: {
                    visible: false,
                    message: ''
                },

                loading: false

            }
        },

        created() {
            if (this.$route.params.successMessage) {
                this.success.message = this.$route.params.successMessage;
                this.success.visible = true;
            }

            if (this.$route.params.alertMessage) {
                this.alert.message = this.$route.params.alertMessage;
                this.alert.visible = true;
            }
        },

        methods: {
            async submit() {
                this.loading = true;

                this.success.visible = false;
                this.alert.visible = false;

                try {
                    this.$auth.destroyToken();

                    let response = await this.$api.login({
                        username: this.username,
                        password: this.password
                    });

                    this.$auth.setToken(
                        response.token,
                        Date.now() + (response.expires * 1000)
                    );

                    this.$router.push({name: 'films.index'})
                } catch (e) {
                    this.password = '';

                    this.alert.message = e.response.data.message;
                    this.alert.visible = true;
                }

                this.loading = false;
            }
        }
    }
</script>
