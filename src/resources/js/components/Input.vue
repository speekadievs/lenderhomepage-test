<template>
    <div class="form-group"
         :class="{'has-danger': $validator.errors.has(label ? label : name)}">
        <label>
            <slot></slot>
        </label>
        <input class="form-control"
               v-model="innerValue"

               :type="type"
               :name="name"
               :class="{'form-control-sm': small}"
               :disabled="disabled"

               @input="$emit('input', innerValue)"
        >
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
            value: [String, Number],
            small: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: 'text',
                validate: (val) => {
                    return ['text', 'url', 'email', 'password', 'search', 'number'].indexOf(val) !== -1;
                }
            }
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
