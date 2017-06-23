/**
 * @author Lukáš Krotovič
 * @version 1.0.0
 * @package Topazz
 */

export default {
    install(_Vue) {
        const security = {
            nonce: '',
            csrfToken: {}
        };

        Object.defineProperty(_Vue.prototype, '$security', {
            get() {
                return security;
            }
        });

        _Vue.http.interceptors.push((request, next) => {
            if (['POST', 'PUT', 'DELETE', 'PATCH'].includes(request.method)){
                if (request.body instanceof FormData) {
                    for(let key in security.csrfToken) {
                        request.body.append(key, security.csrfToken[key]);
                    }
                    next();
                    return;
                }
                if (typeof request.body === "object") {
                    request.body = Object.assign({}, request.body, security.csrfToken);
                }
            }
            next();
        });

        _Vue.mixin({
            methods: {
                loadNonce() {
                    this.$http.get('nonce').then(response => {
                        this.$security.nonce = response.body;
                    });
                }, loadCsrfToken() {
                    this.$http.get('csrf').then(response => {
                        this.$security.csrfToken = response.body;
                    });
                }, canAccess(permission) {
                    if (this.$currentUser.id !== 0) {
                        return this.$currentUser.permissions.includes(permission);
                    }
                    return false;
                }
            }
        });
    }
}