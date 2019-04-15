<template>
    <div class="form-group"
         :class="{'has-danger': $validator.errors.has(label ? label : name)}">
        <label>
            {{ label }}
        </label>
        <select
            class="form-control"
            v-model="innerValue"

            :name="name"
            :disabled="disabled"

            @change="$emit('input', innerValue)"
        >
            <slot></slot>
        </select>
        <span class="text-danger">
            {{ $validator.errors.first(label ? label : name) }}
        </span>
    </div>
</template>

<script>
    export default {
        props: {
            name: {},
            label: {
                type: String
            },
            disabled: {
                type: Boolean,
                default: false
            },
            value: {},
        },

        inject: ['$validator'],

        $_veeValidate: {
            name() {
                return this.label ? this.label : this.name
            },

            value() {
                return this.innerValue;
            }
        },

        data() {
            return {
                innerValue: this.value
            }
        },

        watch: {
            value(newValue) {
                this.innerValue = newValue;
            }
        }
    }
</script>
