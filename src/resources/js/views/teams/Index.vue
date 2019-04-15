<template>
    <div class="card">
        <div class="card-body">
            <l-table ref="table" :columns="columns" :data="'getTeams'">
                <template slot="header-button">
                    <router-link :to="{name: 'teams.create'}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus"></i>
                        Add Team
                    </router-link>
                </template>

                <template slot="image_url" slot-scope="props">
                    <template v-if="props.item.image_url">
                        <img :src="props.item.image_url" class="img-fluid" style="width: 80px">
                    </template>
                    <template v-else>
                        -
                    </template>
                </template>

                <template slot="actions" slot-scope="props">
                    <router-link :to="{name: 'teams.show', params: {id: props.item.id}}" class="btn btn-primary btn-sm">
                        <i class="fa fa-eye"></i> View
                    </router-link>

                    <router-link :to="{name: 'teams.edit', params: {id: props.item.id}}" class="btn btn-primary btn-sm">
                        <i class="fa fa-pencil"></i> Edit
                    </router-link>

                    <button type="button" class="btn btn-danger btn-sm" @click="remove(props.item)">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </template>

            </l-table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                columns: [
                    {
                        name: 'image_url',
                        label: 'Logo',
                        sortable: false
                    },
                    {
                        name: 'name',
                        label: 'Name'
                    },
                    {
                        name: 'players_count',
                        label: 'Players'
                    }
                ]
            }
        },

        methods: {
            async remove(team) {
                let result = await Swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#999',
                    confirmButtonText: 'Yes, delete it!'
                });

                if (result.value) {
                    Swal(
                        'Deleted!',
                        'The team has been deleted.',
                        'success'
                    );

                    await this.$api.deleteTeam(team.id);

                    this.$refs.table.$emit('reload');
                }
            },
        }
    }
</script>
