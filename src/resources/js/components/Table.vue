<template>
    <div>
        <div v-if="!static" class="row">
            <div class="col-2">
                <input type="text" class="form-control" placeholder="Search..." v-model="search"
                       @input="handleSearch">
            </div>
            <div class="col">
                <slot name="header-button"></slot>
            </div>
        </div>

        <table class="table table-striped table-hover mt-2">
            <thead>
            <tr>
                <th v-for="column in columns">
                    {{ column.label }}

                    <template v-if="(typeof column.sortable === 'undefined' || column.sortable) && !static">
                        <l-sort-by :sort_by="sort_by" :sort="sort" :property="column.name" @change="sortBy"/>
                    </template>
                </th>
                <th v-if="!withoutActions && !static" class="text-right">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in list">
                <td v-for="column in columns">
                    <slot :name="column.name" :item="item">
                        {{ _.get(item, column.name) }}
                    </slot>
                </td>

                <td v-if="!withoutActions && !static" class="actions text-right">
                    <slot name="actions" :item="item"></slot>
                </td>
            </tr>
            </tbody>
        </table>
        <p v-if="!list.length" class="text-center">
            <slot name="no-data">
                No data available
            </slot>
        </p>

        <div v-if="!static" class="row">
            <div class="col-1">
                <select class="form-control" v-model="count" @change="request">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col">
                <div class="pagination-cover text-right pull-right" v-if="list.length">
                    <div class="pagination">
                        <b-pagination v-model="meta.current_page" :total-rows="meta.total"
                                      :per-page="meta.per_page" :hide-goto-end-buttons="true"
                                      @change="changePage"></b-pagination>
                    </div>
                </div>
            </div>
        </div>

        <div class="loading" v-if="loading"></div>
    </div>
</template>

<script>
    import bPagination from 'bootstrap-vue/es/components/pagination/pagination'
    import _ from 'lodash'

    export default {
        props: {
            data: {
                required: true
            },

            params: {
                default: () => {
                    return {}
                }
            },

            columns: {
                required: true,
                default: () => {
                    return []
                }
            },

            withoutActions: {
                default: false
            },

            static: {
                default: false
            }
        },

        data() {
            return {
                list: [],
                meta: {},
                search: '',
                count: 10,
                sort_by: 'id',
                sort: 'desc',
                loading: false,
            }
        },

        components: {
            bPagination
        },

        created() {
            this.request();

            this.$on('reload', () => {
                this.request();
            });
        },

        methods: {
            async request() {
                this.loading = true;

                try {
                    let payload = {};

                    let data = {
                        page: this.meta.current_page ? this.meta.current_page : 1,
                        search: this.search,
                        count: this.count,
                        sort_by: this.sort_by,
                        sort: this.sort
                    };

                    if (_.isEmpty(this.params)) {
                        payload = data;
                    } else {
                        payload = data;

                        for (let key in this.params) {
                            if (this.params.hasOwnProperty(key)) {
                                payload[key] = this.params[key];
                            }
                        }
                    }

                    let response = await this.$api[this.data](payload);

                    this.$set(this, 'list', response.data);
                    this.$set(this, 'meta', response.meta);

                    this.meta.per_page = parseInt(this.meta.per_page);
                } catch (e) {
                    this.$notifyError('Whoops... There\'s a small problem on our side');
                }

                this.loading = false;
            },

            changePage(page) {
                this.meta.current_page = page;

                this.request();
            },

            sortBy(key, direction) {
                this.sort_by = key;
                this.sort = direction;

                this.request();
            },

            handleSearch: _.debounce(function () {
                this.request();
            }, 500),

            hasActions() {
                return this.$nextTick(() => {
                    return !!$('.actions').html()
                });
            }
        }
    }
</script>
