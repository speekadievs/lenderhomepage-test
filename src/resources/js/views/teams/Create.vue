<template>
    <div class="card">
        <div class="card-body">
            <div class="loading" v-if="loading"></div>

            <form @submit.prevent="submit" enctype="multipart/form-data">
                <l-input v-model="form.name" name="name" v-validate="'required'">
                    Name
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
                    <button class="btn mt-4 btn-primary" :disabled="loading">
                        <template v-if="loading">
                            <i class="fa fa-refresh fa-spin"></i>
                        </template>
                        <template v-else>
                            <i class="fa fa-floppy-o"></i> Save
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
                loading: false,

                form: {
                    name: '',
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
            if (this.$route.params.id) {
                this.loading = true;

                try {
                    let response = await this.$api.getTeam(this.$route.params.id);

                    this.form.name = response.data.name;
                    this.meta.image.src = response.data.image_url;
                } catch (e) {
                    console.error(e);

                    this.$router.push({
                        name: 'teams.index'
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

                    if (this.meta.image.file) {
                        payload.image = this.meta.image.file;
                    }

                    payload = this.convertModelToFormData(payload);

                    if (this.$route.params.id) {
                        payload.append('_method', 'PUT');

                        await this.$api.updateTeam(this.$route.params.id, payload);

                        this.$notifySuccess('Team successfully updated');
                    } else {
                        await this.$api.createTeam(payload);

                        this.$notifySuccess('Team successfully added');
                    }

                    this.$router.push({
                        name: 'teams.index'
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
