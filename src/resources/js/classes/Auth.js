import API from './Api'

window.awaitingRefreshResponse = null;

export default function (Vue) {
    Vue.auth = {
        /**
         * Store tokens in local storage.
         *
         * @param {string} token
         * @param {number} expiration
         */
        setToken(token, expiration) {
            localStorage.setItem('token', token);
            localStorage.setItem('expiration', expiration);
        },

        /**
         * Remove tokens from local storage.
         *
         * @return {void}
         */
        destroyToken() {
            localStorage.removeItem('token');
            localStorage.removeItem('expiration');
        },

        /**
         * Access token setter/updated middleware that should be executed before each request.
         *
         * @return {Promise<*>}
         */
        async getAccessToken() {
            // Prevent multiple refresh token requests.
            if (window.awaitingRefreshResponse) {
                return window.awaitingRefreshResponse
            }

            let token = localStorage.getItem('token');
            let expiration = localStorage.getItem('expiration');

            if (!token) {
                return null;
            }

            if (Date.now() < parseInt(expiration)) {
                return token;
            }

            // Fetch new refresh token.
            window.awaitingRefreshResponse = new Promise(async (resolve) => {
                try {
                    let response = await API.refreshToken(token);

                    this.setToken(
                        response.token,
                        Date.now() + (parseInt(response.expires) * 1000)
                    );

                    window.awaitingRefreshResponse = null;
                    resolve(response.token)
                } catch (e) {
                    this.destroyToken();
                    resolve()
                }
            });

            return window.awaitingRefreshResponse
        },

        isAuthenticated() {
            let token = localStorage.getItem('token');
            let expiration = localStorage.getItem('expiration');

            if (token) {
                return parseInt(expiration) > Date.now()
            }

            return false
        }
    };

    Object.defineProperties(Vue.prototype, {
        $auth: {
            get() {
                return Vue.auth
            }
        }
    })
}
