<template>
    <div class="card mt-3">
        <div class="card-body">
            <div class="loading" v-if="loading"></div>

            <form @submit.prevent="submit" enctype="multipart/form-data">
                <l-input v-model="form.first_name" name="first_name" v-validate="'required'" data-vv-as="first name">
                    First name
                </l-input>

                <l-input v-model="form.last_name" name="last_name" v-validate="'required'" data-vv-as="last name">
                    Last name
                </l-input>

                <div class="row">
                    <div class="col-10">
                        <div>
                            <label>Image</label>
                            <input v-validate="'mimes:image/jpeg,image/png,image/gif|size:10000'" type="file"
                                   class="form-control" name="image"
                                   @change="addImage($event.target.files)"
                                   accept="image/*">

                            <span class="text-danger help-block">
                                {{ errors.first('image') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <img v-if="meta.image.src" :src="meta.image.src" class="img-fluid">
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <button class="btn mt-4 btn-primary" :disabled="loading">
                                <template v-if="loading">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </template>
                                <template v-else>
                                    <i class="fa fa-floppy-o"></i> Save
                                </template>
                            </button>
                        </div>

                        <div class="col-6 text-right">
                            <router-link :to="{name: 'teams.show', params:{id: $route.params.id}}" class="btn mt-4 btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back
                            </router-link>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,

                form: {
                    first_name: '',
                    last_name: '',
                },

                meta: {
                    image: {
                        src: null,
                        file: null
                    }
                }
            }
        },

        async created() {
            if (this.$route.params.playerId) {
                this.loading = true;

                try {
                    let response = await this.$api.getPlayer(this.$route.params.playerId);

                    this.form.first_name = response.data.first_name;
                    this.form.last_name = response.data.last_name;
                    this.meta.image.src = response.data.image_url;
                } catch (e) {
                    console.error(e);

                    this.$router.push({
                        name: 'teams.show',
                        params: {
                            id: this.$route.params.id
                        }
                    })
                }

                this.loading = false;
            }
        },

        methods: {
            async submit() {
                await this.$validator.validateAll();

                if (this.errors.any()) {
                    return false;
                }

                this.loading = true;

                try {
                    let payload = _.cloneDeep(this.form);

                    payload.team_id = this.$route.params.id;

                    if (this.meta.image.file) {
                        payload.image = this.meta.image.file;
                    }

                    payload = this.convertModelToFormData(payload);

                    if (this.$route.params.playerId) {
                        payload.append('_method', 'PUT');

                        await this.$api.updatePlayer(this.$route.params.playerId, payload);

                        this.$notifySuccess('Player successfully updated');
                    } else {
                        await this.$api.createPlayer(payload);

                        this.$notifySuccess('Player successfully added');
                    }

                    this.$router.push({
                        name: 'teams.show',
                        params: {
                            id: this.$route.params.id
                        }
                    });
                } catch (e) {
                    console.error(e);

                    this.$setLaravelValidationErrorsFromResponse(e.response.data);
                }

                this.loading = false;
            },

            addImage(files) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    this.meta.image.src = e.target.result;
                };

                reader.readAsDataURL(files[0]);

                this.meta.image.file = files[0];
            },
        }
    }
</script>
