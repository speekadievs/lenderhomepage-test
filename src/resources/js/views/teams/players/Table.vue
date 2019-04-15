<template>
    <div class="card mt-3">
        <div class="card-body">
            <l-table ref="table" :columns="columns" :data="'getPlayers'" :params="{team_id: $route.params.id}">
                <template slot="header-button">
                    <router-link :to="{name: 'players.create'}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus"></i>
                        Add Player
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
                    <router-link :to="{name: 'players.edit', params: {id: $route.params.id, playerId: props.item.id}}" class="btn btn-primary btn-sm">
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
                        label: 'Image',
                        sortable: false
                    },
                    {
                        name: 'first_name',
                        label: 'First name'
                    },
                    {
                        name: 'last_name',
                        label: 'Last name'
                    }
                ]
            }
        },

        methods: {
            async remove(player) {
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
                        'The player has been deleted.',
                        'success'
                    );

                    await this.$api.deletePlayer(player.id);

                    this.$refs.table.$emit('reload');
                }
            },
        }
    }
</script>
