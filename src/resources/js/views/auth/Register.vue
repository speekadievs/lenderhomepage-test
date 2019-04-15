<template>
    <div class="card">
        <div class="card-body">
            <form @submit.prevent="submit">
                <div v-if="alert.visible" class="alert alert-danger">
                    {{ alert.message }}
                </div>

                <l-input v-model="form.name" name="name">
                    Name
                </l-input>

                <l-input v-model="form.email" name="email">
                    Email address
                </l-input>

                <l-input type="password" v-model="form.password" name="password">
                    Password
                </l-input>

                <l-input type="password" v-model="form.password_confirmation"
                         name="password_confirmation">
                    Password confirmation
                </l-input>


                <div class="form-group">
                    <button class="btn mt-4 btn-primary" :disabled="loading">
                        <template v-if="loading">
                            <i class="fa fa-refresh fa-spin"></i>
                        </template>
                        <template v-else>
                            Register
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
                alert: {
                    visible: false,
                    message: ''
                },

                form: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },

                loading: false,
            }
        },

        methods: {
            async submit() {
                this.loading = true;

                this.alert.visible = false;

                try {
                    await this.$api.register(this.form);

                    this.$router.push({
                        name: 'auth.login',
                        params: {
                            successMessage: 'Registration successful. You can now login.'
                        }
                    });
                } catch (e) {
                    this.password = '';
                    this.password_confirmation = '';

                    this.$setLaravelValidationErrorsFromResponse(e.response.data);
                }

                this.loading = false;

            }
        }
    }
</script>
