const server = window.host;

export default class API {
    /**
     * Make request to the given endpoint.
     *
     * @param endpoint
     * @param method
     * @param data
     * @param withoutAccessTokenCheck
     * @param requestSettings
     * @return {Promise<any>}
     */
    static async request(endpoint, method, data, withoutAccessTokenCheck = false, requestSettings) {
        let settings = {
            method: method,
            url: server + endpoint
        };

        if (settings.method === 'GET') {
            settings['params'] = data || {}
        } else {
            settings['data'] = data || {}
        }

        if (requestSettings) {
            settings = Object.assign(settings, requestSettings)
        }

        settings.headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        };

        if (requestSettings && requestSettings.headers) {
            settings.headers = Object.assign(settings.headers, requestSettings.headers)
        }

        let callback = async (token) => {
            if (token) {
                settings.headers['Authorization'] = `Bearer ${token}`
            }

            try {
                let {data} = await axios(settings);
                return data
            } catch (e) {
                if (e && e.response && e.response.status === 401) {
                    Vue.auth.destroyToken();
                    window.location.reload()
                }

                throw e
            }
        };

        if (withoutAccessTokenCheck) {
            return callback()
        }

        let accessToken = await Vue.auth.getAccessToken();

        return callback(accessToken)
    }


    /** Auth */
    static login(data) {
        return API.request('/api/login', 'POST', data, true)
    }

    static register(data) {
        return API.request('/api/register', 'POST', data)
    }

    static refreshToken(token) {
        return API.request('/api/refresh-token', 'POST', {}, true, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
    }

    /** User */
    static getUser() {
        return API.request('/api/user', 'GET')
    }

    static updateUser(payload) {
        return API.request('/api/user', 'PUT', payload);
    }

    /** Teams */
    static getTeams(payload) {
        return API.request('/api/teams', 'GET', payload);
    }

    static createTeam(payload) {
        return API.request('/api/teams', 'POST', payload);
    }

    static updateTeam(teamId, payload) {
        return API.request(`/api/teams/${teamId}`, 'PUT', payload);
    }

    static deleteTeam(teamId) {
        return API.request(`/api/teams/${teamId}`, 'DELETE');
    }

    /** Players */
    static getPlayers(payload) {
        return API.request('/api/players', 'GET', payload);
    }

    static createPlayer(payload) {
        return API.request('/api/players', 'POST', payload);
    }

    static updatePlayer(playerId, payload) {
        return API.request(`/api/players/${playerId}`, 'PUT', payload);
    }

    static deletePlayer(playerId) {
        return API.request(`/api/players/${playerId}`, 'DELETE');
    }
}
