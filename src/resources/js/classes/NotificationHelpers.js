export default {
    install(Vue) {
        /**
         * Display success notification.
         *
         * @param message
         */
        Vue.prototype.$notifyError = message => {
            Vue.prototype.$notify({
                type: 'error',
                text: message
            })
        };

        /**
         * Display error notification.
         *
         * @param message
         */
        Vue.prototype.$notifySuccess = message => {
            Vue.prototype.$notify({
                type: 'success',
                text: message
            })
        };

        /**
         * Display warning notification.
         *
         * @param message
         */
        Vue.prototype.$notifyWarning = message => {
            Vue.prototype.$notify({
                type: 'warning',
                text: message
            })
        }
    }
}
