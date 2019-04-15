<template>
    <span>
        <template v-if="sort_by === property">
            <template v-if="sort === 'asc'">
                <a href="javascript:;" @click="click('desc')">
                    <i class="fa fa-sort-asc"></i>
                </a>
            </template>
            <template v-else>
                <a href="javascript:;" @click="click('asc')">
                    <i class="fa fa-sort-desc"></i>
                </a>
            </template>
        </template>
        <template v-else>
            <a href="javascript:;" @click="click('desc')">
                <i class="fa fa-sort"></i>
            </a>
        </template>
    </span>
</template>

<script>
    export default {
        props: ['sort_by', 'sort', 'property'],

        methods: {
            click(sort) {
                this.$emit('change', this.property, sort);
            }
        }
    }
</script>
