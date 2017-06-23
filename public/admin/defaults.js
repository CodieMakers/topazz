/**
 * @author Lukáš Krotovič
 * @version 1.0.0
 * @package Topazz
 */

export default {
    install(Vue) {

        const defaults = {
            currentUser: {
                id: 0
            }
        };

        Object.defineProperty(Vue.prototype, '$currentUser', {
            get() {
                return defaults.currentUser;
            },
            set(user) {
                defaults.currentUser = user;
            }
        });
    }
}